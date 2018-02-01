<?php
	
namespace Wenprise\Kernel;

use Wenprise\Foundation\ServiceProvider;
use Illuminate\Http\Request;

class KernelServiceProvider extends ServiceProvider {
	
	/**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
	    
	    $this->app->instance('wprs_headers', []);
	        
        add_filter( 'wp_headers', function($headers) {
	        
	        $this->app['wprs_headers'] = array_merge($this->app['wprs_headers'], $headers);
	        
	        return [];
	        
        });
        
        $this->app->singleton('router', function ($container) {
            return new Router($container['events'], $container);
        });
	    
	    $this->app->singleton(Request::class, function() {
		
		    return $this->app->request;
		    
	    });
        
	}
	
}
