<?php

namespace Wenprise\Mvc;

/*----------------------------------------------------*/
// 目录分隔符
/*----------------------------------------------------*/
defined('DS') ? DS : define('DS', DIRECTORY_SEPARATOR);


/*
 * 启动框架的主 Class
 */
class App
{
    /**
     * Wenprise 实例.
     *
     * @var \Wenprise\Mvc\App
     */
    protected static $instance = null;

    /**
     * 框架版本
     *
     * @var float
     */
    const VERSION = '1.3';

    /**
     * 服务容器
     *
     * @var \Wenprise\Mvc\Foundation\Application
     */
    public $container;


    private function __construct()
    {
        $this->bootstrap();
    }


    /**
     * 获取 Wenprise 类实例
     *
     * @return \Wenprise\Mvc\App
     */
    public static function instance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }


    /**
     * 启动框架
     */
    protected function bootstrap()
    {

        /*
         * 定义存储路径
         */
        defined('WENPRISE_STORAGE') ? WENPRISE_STORAGE : define('WENPRISE_STORAGE', WP_CONTENT_DIR.DS.'storage');

        /*
         * 定义框架路径，是路径而不是 URL
         */
        $paths['core']    = __DIR__.DS;
        $paths['sys']     = __DIR__.DS.'src'.DS.'Wenprise'.DS;
        $paths['storage'] = WENPRISE_STORAGE;

        Helpers::set_paths($paths);

        /*
         * 为项目初始化服务容器
         */
        $this->container = new \Wenprise\Mvc\Foundation\Application();

        /*
         * 创建一个新请求实例，并注册，通过提供一个实例，该实例可共享
         */
        $request = \Wenprise\Mvc\Foundation\Request::capture();
        $this->container->instance('request', $request);

        /*
         * 设置 Facade 应用.
         */
        \Wenprise\Mvc\Facades\Facade::setFacadeApplication($this->container);

        /*
         * 注册路径到到容器，一般在这个阶段、插件应该注册他们的路径到 $GLOBALS 数组中
         */
        $this->container->registerAllPaths(Helpers::get_path());


        /**
         * 注册启动对应的服务
         */
        $this->registerProviders();
        $this->registerClassAlias();


        /*
         * 项目 hooks.
         * 按他们的调用顺序添加
         * 
         * @todo: redirect_canonical 会导致某些页面意外被跳转到首页，具体原因有待调查
         * 下面一行改为 remove_action('template_redirect', 'redirect_canonical'); 可以暂时解决，但会影响标准化URL的功能
         */
        \add_action('template_redirect', 'redirect_canonical');
        \add_action('template_redirect', 'wp_redirect_admin_locations');
        \add_action('template_redirect', [$this, 'setRouter'], 20);
    }


    /**
     * 注册核心框架服务提供者
     */
    protected function registerProviders()
    {
        $providers = \apply_filters('wprs_service_providers', [
            \Wenprise\Mvc\Config\ConfigServiceProvider::class,
            \Wenprise\Mvc\Kernel\KernelServiceProvider::class,
            \Wenprise\Mvc\Flash\FlashServiceProvider::class,
            \Wenprise\Mvc\Finder\FinderServiceProvider::class,
            \Wenprise\Mvc\Route\RouteServiceProvider::class,
            \Wenprise\Mvc\View\ViewServiceProvider::class,
        ]);

        foreach ($providers as $provider) {
            $this->container->register($provider);
        }
    }


    /**
     * 注册类别名，也就是 Facades
     */
    protected function registerClassAlias()
    {
        $aliases = [
            'Blade'   => \Wenprise\Mvc\Facades\Blade::class,
            'Config'  => \Wenprise\Mvc\Facades\Config::class,
            'Flash'   => \Wenprise\Mvc\Facades\Flash::class,
            'Input'   => \Wenprise\Mvc\Facades\Input::class,
            'Request' => \Wenprise\Mvc\Facades\Request::class,
            'Route'   => \Wenprise\Mvc\Facades\Route::class,
            'View'    => \Wenprise\Mvc\Facades\View::class,
        ];

        /*
         * 类别名
         */
        if (!empty($aliases) && is_array($aliases)) {
            foreach ($aliases as $alias => $full_name) {
                class_alias($full_name, $alias);
            }
        }
    }


    /**
     * 挂载到前端路由
     * 在主题默认模版之前设置路由 API
     */
    public function setRouter()
    {
        if (\is_feed() || \is_comment_feed()) {
            return;
        }

        try {
            $request = $this->container['request'];

            /* @var $response \Illuminate\Http\Response */
            $response = $this->container['router']->dispatch($request);

            // 因为 WordPress 已经发送了headers，所以在这里，我们只发送内容
            $response->sendContent();
            die();
        } catch (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $exception) {
            /*
             * 退回到 WordPress 模版
             */
        }
    }
}

