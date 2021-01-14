<?php

namespace Wenprise\Mvc\Flash;

use Plasticbrain\FlashMessages\FlashMessages;
use Wenprise\Mvc\Foundation\ServiceProvider;

class FlashServiceProvider extends ServiceProvider {
	public function register() {
		$this->app->bind( 'flash', function ( $container ) {
			return new FlashMessages();
		} );
	}
}