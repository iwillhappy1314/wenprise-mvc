<?php

namespace Wenprise\Mvc;

use Plasticbrain\FlashMessages\FlashMessages;
use Wenprise\Mvc\Foundation\Application;

class Helpers
{
    /**
     * 生成通知消息
     *
     * @param $type    string 通知消息类型
     * @param $message string 通知消息内容
     * @param $url string 跳转 URL
     * @param $sticky bool 是否固定
     *
     * @return \Plasticbrain\FlashMessages\FlashMessages
     */
    public static function flash($type, $message, $url = null, $sticky = false)
    {
        if (!class_exists('\Plasticbrain\FlashMessages\FlashMessages')) {
            wp_die('Please install plasticbrain/php-flash-messages library');
        }
        
        $msg = new FlashMessages();
        $msg->$type($message, $url, $sticky);

        return $msg;
    }


    /**
     * 在后台显示通知消息
     *
     * @param  string  $type
     * @param  string  $message
     */
    public static function admin_flash($type, $message)
    {
        \add_action('admin_notices', function () use ($type, $message)
        {
            $class = 'notice notice-'.$type;
            printf('<div class="%1$s"><p>%2$s</p></div>', \esc_attr($class), \esc_html($message));
        });
    }


    /**
     * 显示通知消息
     */
    public static function show_messages()
    {
        $msg = new FlashMessages();

        ob_start();
        $msg->display();

        return ob_get_clean();
    }


    /**
     * 全局注册路径
     *
     * @param  array  $paths  Paths to register using alias => path pairs.
     */
    public static function set_paths(array $paths)
    {
        foreach ($paths as $name => $path) {
            if (!realpath($path)) {
                \wp_mkdir_p($path);
            }
            if (!isset($GLOBALS['wenprise.paths'][$name])) {
                $GLOBALS['wenprise.paths'][$name] = realpath($path).DS;
            }
        }
    }


    /**
     * 获取前面注册的路径
     *
     * @param  string  $name  The path name/alias. If none is provided, returns all registered paths.
     *
     * @return string|array
     */
    public static function get_path($name = '')
    {
        if (!empty($name)) {
            return $GLOBALS['wenprise.paths'][$name];
        }

        return $GLOBALS['wenprise.paths'];
    }


    /**
     * 获取设置值
     *
     * @param $key
     *
     * @return mixed
     */
    public static function get_config($key)
    {
        return \Wenprise\Mvc\Facades\Config::get($key);
    }


    /**
     * 快速获取实例
     *
     * @param  null  $abstract  抽象实例名称
     * @param  array  $parameters
     *
     * @return mixed
     */
    public static function init_app($abstract = null, array $parameters = [])
    {
        $application = isset($GLOBALS['wenprise']) ? $GLOBALS['wenprise']->container : Application::getInstance();

        if ($abstract === null) {
            return $application;
        }

        return $application->make($abstract, $parameters);
    }


    /**
     * 快速获取实例
     *
     * @param  null  $abstract  抽象实例名称
     * @param  array  $parameters
     *
     * @return mixed
     */
    public static function get_container($abstract = null, array $parameters = [])
    {
        return static::init_app($abstract, $parameters);
    }


    /**
     * 获取 Wenprise 类实例
     *
     * @return \Wenprise\Mvc\App
     */
    public static function get_app_instance()
    {
        if (!class_exists('Wenprise\\Mvc\\App')) {
            \wp_die('Wenprise has not yet been initialized. Please make sure the Wenprise framework is installed.');
        }

        return \Wenprise\Mvc\App::instance();
    }


    /**
     * 创建视图的辅助函数
     *
     * @param  string  $view  视图相对路径，名
     * @param  array  $data  传入的数据
     *
     * @param  array  $mergeData
     *
     * @return string
     */
    public static function render_view($view = null, array $data = [], array $mergeData = [])
    {
        $factory = Helpers::get_container('view');

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($view, $data, $mergeData)->render();
    }


    /**
     * 渲染 Knp Menu 生成的菜单
     *
     * @param $menus
     *
     * @return mixed|string
     */
    public static function render_menu($menus)
    {
        if (!class_exists('\Knp\Menu\Renderer\ListRenderer')) {
            wp_die('Please install knplabs/knp-menu library');
        }
        
        $current_link = (isset($_SERVER['HTTPS']) ? 'https' : 'http')."://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        foreach ($menus as $m) {

            /** @var \Knp\Menu\MenuItem $m */
            $m->setLinkAttribute('class', 'c-menu__link');
            if ($m->getUri() === $current_link) {
                $m->setCurrent(true);
                $m->setLinkAttribute('class', 'c-menu__link is-active');
            }
        }

        $renderer = new \Knp\Menu\Renderer\ListRenderer(new \Knp\Menu\Matcher\Matcher());
        $menus    = $renderer->render($menus, [
            'currentClass'  => 'is-active', 'branch_class' => 'c-menu__item', 'leaf_class' => 'c-menu__item',
            'ancestorClass' => 'c-menu__item',
        ]);

        $menus = str_replace(['&lt;', '&gt;', '&quot;'], ['<', '>', '"'], $menus);

        return $menus;
    }


    /**
     * 使用排除法判断是否为 APP 页面
     */
    public static function is_app()
    {
        global $wp;

        return array_key_exists('is_wenprise_route', $wp->query_vars) && $wp->query_vars['is_wenprise_route'] === 1;
    }
}