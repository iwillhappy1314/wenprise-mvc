<?php

namespace Wenprise\Flash;

use Plasticbrain\FlashMessages\FlashMessages;
use Wenprise\Foundation\ServiceProvider;

class FlashServiceProvider extends ServiceProvider {
	public function register() {
		$this->app->bind( 'flash', function ( $container ) {
			return new FlashMessages();
		} );
	}
}