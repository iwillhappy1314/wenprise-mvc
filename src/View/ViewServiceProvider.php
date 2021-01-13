<?php

namespace Wenprise\View;

use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory;
use Wenprise\Foundation\ServiceProvider;

class ViewServiceProvider extends ServiceProvider {
	public function register() {
		$this->registerEngineResolver();
		$this->registerViewFactory();
	}

	/**
	 * Register the EngineResolver instance to the application.
	 */
	protected function registerEngineResolver() {
		$serviceProvider = $this;

		$this->app->singleton( 'view.engine.resolver', function () use ( $serviceProvider ) {
			$resolver = new EngineResolver();

			// Register the engines.
			foreach ( [ 'php', 'blade' ] as $engine ) {
				$serviceProvider->{'register' . ucfirst( $engine ) . 'Engine'}( $engine, $resolver );
			}

			return $resolver;
		} );
	}

	/**
	 * Register the PHP engine to the EngineResolver.
	 *
	 * @param string                                  $engine Name of the engine.
	 * @param \Illuminate\View\Engines\EngineResolver $resolver
	 */
	protected function registerPhpEngine( $engine, EngineResolver $resolver ) {
		$resolver->register( $engine, function () {
			return new PhpEngine();
		} );
	}

	/**
	 * Register the Blade engine to the EngineResolver.
	 *
	 * @param string                                  $engine Name of the engine.
	 * @param \Illuminate\View\Engines\EngineResolver $resolver
	 */
	protected function registerBladeEngine( $engine, EngineResolver $resolver ) {
		$container = $this->app;
		$storage   = $container[ 'path.storage' ] . 'views';

		if ( ! realpath( $storage ) ) {
			\wp_mkdir_p( $storage );
		}

		$filesystem    = $container[ 'filesystem' ];
		$bladeCompiler = new BladeCompiler( $filesystem, $storage );
		$this->app->instance( 'blade', $bladeCompiler );
		$resolver->register( $engine, function () use ( $bladeCompiler ) {
			return new CompilerEngine( $bladeCompiler );
		} );
	}

	/**
	 * Register the view factory. The factory is
	 * available in all views.
	 */
	protected function registerViewFactory() {
		// Register the View Finder first.
		$this->app->singleton( 'view.finder', function ( $container ) {
			return new ViewFinder( $container[ 'filesystem' ], [], [ 'blade.php', 'scout.php', 'php' ] );
		} );

		$this->app->singleton( 'view', function ( $container ) {
			$factory = new Factory( $container[ 'view.engine.resolver' ], $container[ 'view.finder' ], $container[ 'events' ] );
			// Set the container.
			$factory->setContainer( $container );
			// Tell the factory to also handle the scout template for backwards compatibility.
			$factory->addExtension( 'scout.php', 'blade' );

			// We will also set the container instance on this view environment since the
			// view composers may be classes registered in the container, which allows
			// for great testable, flexible composers for the application developer.
			$factory->setContainer( $container );

			$factory->share( 'app', $container );

			return $factory;
		} );
	}


	/**
	 * Register custom Blade directives for use into views.
	 */
	public function boot() {
		$blade = $this->app[ 'view' ]->getEngineResolver()->resolve( 'blade' )->getCompiler();

		/*
		 * Add the "@loop" directive.
		 */
		$blade->directive( 'loop', function () {
			return '<?php if(have_posts()) { while(have_posts()) { the_post(); ?>';
		} );

		/*
		 * Add the "@endloop" directive.
		 */
		$blade->directive( 'endloop', function () {
			return '<?php }} ?>';
		} );

		/*
		 * Add the "@query" directive.
		 */
		$blade->directive( 'query', function ( $expression ) {
			return '<?php $_wenpriseQuery = (is_array(' . $expression . ')) ? new WP_Query(' . $expression . ') : ' . $expression . '; if($_wenpriseQuery->have_posts()) { while($_wenpriseQuery->have_posts()) { $_wenpriseQuery->the_post(); ?>';
		} );

		/*
		 * Add the "@endquery" directive.
		 */
		$blade->directive( 'endquery', function () {
			return '<?php }} wp_reset_postdata(); ?>';
		} );

		/*
		 * Add the "@wp_head" directive
		 */
		$blade->directive( 'wp_head', function () {
			return '<?php wp_head(); ?>';
		} );

		/*
		 * Add the "@wp_footer" directive
		 */
		$blade->directive( 'wp_footer', function () {
			return '<?php wp_footer(); ?>';
		} );
	}
}
