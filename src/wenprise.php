<?php

/*----------------------------------------------------*/
// 目录分隔符
/*----------------------------------------------------*/
defined( 'DS' ) ? DS : define( 'DS', DIRECTORY_SEPARATOR );

/*----------------------------------------------------*/
// 存储路径
/*----------------------------------------------------*/
defined( 'WENPRISE_STORAGE' ) ? WENPRISE_STORAGE : define( 'WENPRISE_STORAGE', WP_CONTENT_DIR . DS . 'storage' );

/*
 * 启动框架的主 Class
 */
if ( ! class_exists( 'Wenprise' ) ) {
	class Wenprise {
		/**
		 * Wenprise 实例.
		 *
		 * @var \Wenprise
		 */
		protected static $instance = null;

		/**
		 * 框架版本
		 *
		 * @var float
		 */
		const VERSION = '1.3.2';

		/**
		 * 服务容器
		 *
		 * @var \Wenprise\Foundation\Application
		 */
		public $container;

		private function __construct() {
			$this->bootstrap();
		}

		/**
		 * 获取 Wenprise 类实例
		 *
		 * @return \Wenprise
		 */
		public static function instance() {
			if ( is_null( static::$instance ) ) {
				static::$instance = new static();
			}

			return static::$instance;
		}


		/**
		 * 启动框架
		 */
		protected function bootstrap() {
			/*
			 * 定义框架路径，是路径而不是 URL
			 */
			$paths[ 'core' ]    = __DIR__ . DS;
			$paths[ 'sys' ]     = __DIR__ . DS . 'src' . DS . 'Wenprise' . DS;
			$paths[ 'storage' ] = WENPRISE_STORAGE;
			wprs_set_paths( $paths );

			/*
			 * 为项目初始化服务容器
			 */
			$this->container = new \Wenprise\Foundation\Application();

			/*
			 * 创建一个新的请求实例，并注册，通过提供一个实例，该实例可共享
			 */
			$request = \Wenprise\Foundation\Request::capture();
			$this->container->instance( 'request', $request );

			/*
			 * 设置 Facade 应用.
			 */
			\Wenprise\Facades\Facade::setFacadeApplication( $this->container );

			/*
			 * 注册路径到到容器，一般在这个阶段、插件应该注册他们的路径到 $GLOBALS 数组中
			 */
			$this->container->registerAllPaths( wprs_path() );


			/**
			 * 注册启动对应的服务
			 */
			$this->registerProviders();
			$this->registerClassAlias();


			/*
			 * 项目 hooks.
			 * 按他们的调用顺序添加
			 */
			add_action( 'template_redirect', 'redirect_canonical' );
			add_action( 'template_redirect', 'wp_redirect_admin_locations' );
			add_action( 'template_redirect', [ $this, 'setRouter' ], 20 );
		}


		/**
		 * 注册核心框架服务提供者
		 */
		protected function registerProviders() {
			$providers = apply_filters( 'wprs_service_providers', [
				Wenprise\Ajax\AjaxServiceProvider::class,
				Wenprise\Asset\AssetServiceProvider::class,
				Wenprise\Config\ConfigServiceProvider::class,
				Wenprise\Kernel\KernelServiceProvider::class,
				Wenprise\Flash\FlashServiceProvider::class,
				Wenprise\Hook\HookServiceProvider::class,
				Wenprise\Html\HtmlServiceProvider::class,
				Wenprise\Finder\FinderServiceProvider::class,
				Wenprise\Page\PageServiceProvider::class,
				Wenprise\Route\RouteServiceProvider::class,
				Wenprise\View\ViewServiceProvider::class,
			] );

			foreach ( $providers as $provider ) {
				$this->container->register( $provider );
			}
		}


		/**
		 * 注册类别名，也就是 Facades
		 */
		protected function registerClassAlias() {
			$aliases = [
				'Action'  => Wenprise\Facades\Action::class,
				'Asset'   => Wenprise\Facades\Asset::class,
				'Ajax'    => Wenprise\Facades\Ajax::class,
				'Blade'   => Wenprise\Facades\Blade::class,
				'Config'   => Wenprise\Facades\Config::class,
				'Flash'   => Wenprise\Facades\Flash::class,
				'Filter'  => Wenprise\Facades\Filter::class,
				'Html'    => Wenprise\Facades\Html::class,
				'Input'   => Wenprise\Facades\Input::class,
				'Page'    => Wenprise\Facades\Page::class,
				'Request' => Wenprise\Facades\Request::class,
				'Route'   => Wenprise\Facades\Route::class,
				'View'    => Wenprise\Facades\View::class,
			];

			/*
			 * 主题别名
			 */
			if ( ! empty( $aliases ) && is_array( $aliases ) ) {
				foreach ( $aliases as $alias => $fullname ) {
					class_alias( $fullname, $alias );
				}
			}
		}


		/**
		 * 挂载到前端路由
		 * 在主题默认模版之前设置路由 API
		 */
		public function setRouter() {
			if ( is_feed() || is_comment_feed() ) {
				return;
			}

			try {
				$request  = $this->container[ 'request' ];

				/* @var $response \Illuminate\Http\Response*/
				$response = $this->container[ 'router' ]->dispatch( $request );

				// 因为 WordPress 已经发送了headers，所以在这里，我们只发送内容
				$response->sendContent();
				die();
			} catch ( \Symfony\Component\HttpKernel\Exception\NotFoundHttpException $exception ) {
				/*
				 * 退回到 WordPress 模版
				 */
			}
		}


	}
}

/*
 * 全局注册实例
 */
$GLOBALS[ 'wenprise' ] = Wenprise::instance();