<?php

/*----------------------------------------------------*/
// The directory separator.
/*----------------------------------------------------*/
defined( 'DS' ) ? DS : define( 'DS', DIRECTORY_SEPARATOR );

/*----------------------------------------------------*/
// Storage path.
/*----------------------------------------------------*/
defined( 'WENPRISE_STORAGE' ) ? WENPRISE_STORAGE : define( 'WENPRISE_STORAGE', WP_CONTENT_DIR . DS . 'storage' );

if ( ! function_exists( 'wprs_set_paths' ) ) {
	/**
	 * Register paths globally.
	 *
	 * @param array $paths Paths to register using alias => path pairs.
	 */
	function wprs_set_paths( array $paths ) {
		foreach ( $paths as $name => $path ) {
			if ( ! isset( $GLOBALS[ 'wenprise.paths' ][ $name ] ) ) {
				$GLOBALS[ 'wenprise.paths' ][ $name ] = realpath( $path ) . DS;
			}
		}
	}
}

if ( ! function_exists( 'wprs_path' ) ) {
	/**
	 * Helper function to retrieve a previously registered path.
	 *
	 * @param string $name The path name/alias. If none is provided, returns all registered paths.
	 *
	 * @return string|array
	 */
	function wprs_path( $name = '' ) {
		if ( ! empty( $name ) ) {
			return $GLOBALS[ 'wenprise.paths' ][ $name ];
		}

		return $GLOBALS[ 'wenprise.paths' ];
	}
}


/*
 * Main class that bootstraps the framework.
 */
if ( ! class_exists( 'Wenprise' ) ) {
	class Wenprise {
		/**
		 * Wenprise instance.
		 *
		 * @var \Wenprise
		 */
		protected static $instance = null;

		/**
		 * Framework version.
		 *
		 * @var float
		 */
		const VERSION = '1.3.2';

		/**
		 * The service container.
		 *
		 * @var \Wenprise\Foundation\Application
		 */
		public $container;

		private function __construct() {
			$this->bootstrap();
		}

		/**
		 * Retrieve Wenprise class instance.
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
		 * Bootstrap the core plugin.
		 */
		protected function bootstrap() {
			/*
			 * Define core framework paths.
			 * These are real paths, not URLs to the framework files.
			 */
			$paths[ 'core' ]    = __DIR__ . DS;
			$paths[ 'sys' ]     = __DIR__ . DS . 'src' . DS . 'Wenprise' . DS;
			$paths[ 'storage' ] = WENPRISE_STORAGE;
			wprs_set_paths( $paths );

			/*
			 * Instantiate the service container for the project.
			 */
			$this->container = new \Wenprise\Foundation\Application();

			/*
			 * Create a new Request instance and register it.
			 * By providing an instance, the instance is shared.
			 */
			$request = \Wenprise\Foundation\Request::capture();
			$this->container->instance( 'request', $request );

			/*
			 * Setup the facade.
			 */
			\Wenprise\Facades\Facade::setFacadeApplication( $this->container );

			/*
			 * Register into the container, the registered paths.
			 * Normally at this stage, plugins should have
			 * their paths registered into the $GLOBALS array.
			 */
			$this->container->registerAllPaths( wprs_path() );

			/*
			 * Register core service providers.
			 */
			$this->registerProviders();


			/*
			 * Set up database
			 */
			$this->setup();


			/*
			 * Project hooks.
			 * Added in their called order.
			 */
			add_action( 'template_redirect', 'redirect_canonical' );
			add_action( 'template_redirect', 'wp_redirect_admin_locations' );
			add_action( 'template_redirect', [ $this, 'setRouter' ], 20 );
		}


		/**
		 * Register core framework service providers.
		 */
		protected function registerProviders() {
			/*
			 * Service providers.
			 */
			$providers = apply_filters( 'wprs_service_providers', [
				Wenprise\Ajax\AjaxServiceProvider::class,
				Wenprise\Hook\HookServiceProvider::class,
				Wenprise\Finder\FinderServiceProvider::class,
				Wenprise\Route\RouteServiceProvider::class,
				Wenprise\View\ViewServiceProvider::class,
			] );

			foreach ( $providers as $provider ) {
				$this->container->register( $provider );
			}
		}


		protected function setup() {

			/*----------------------------------------------------*/
			// 配置 Corcel 数据库连接
			/*----------------------------------------------------*/
			global $table_prefix;
			$collate = ( defined( 'DB_COLLATE' ) && DB_COLLATE ) ? DB_COLLATE : 'utf8_general_ci';

			/*----------------------------------------------------*/
			// Illuminate database
			/*----------------------------------------------------*/
			$capsule = new Illuminate\Database\Capsule\Manager();
			$capsule->addConnection( [
				'driver'    => 'mysql',
				'host'      => DB_HOST,
				'database'  => DB_NAME,
				'username'  => DB_USER,
				'password'  => DB_PASSWORD,
				'charset'   => DB_CHARSET,
				'collation' => $collate,
				'prefix'    => $table_prefix,
			] );
			$capsule->setAsGlobal();
			$capsule->bootEloquent();

			$GLOBALS[ 'wenprise.capsule' ] = $capsule;

		}


		/**
		 * Hook into front-end routing.
		 * Setup the router API to be executed before
		 * theme default templates.
		 */
		public function setRouter() {
			if ( is_feed() || is_comment_feed() ) {
				return;
			}

			try {
				$request  = $this->container[ 'request' ];
				$response = $this->container[ 'router' ]->dispatch( $request );

				// We only send back the content because, headers are already defined
				// by WordPress internals.
				$response->sendContent();
				die();
			} catch ( \Symfony\Component\HttpKernel\Exception\NotFoundHttpException $exception ) {
				/*
				 * Fallback to WordPress templates.
				 */
			}
		}


	}
}

/*
 * Globally register the instance.
 */
$GLOBALS[ 'wenprise' ] = Wenprise::instance();
