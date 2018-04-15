<?php

namespace Wenprise\Facades;

use Wenprise\Page\PageBuilder;

/**
 * @method static PageBuilder make( $slug, $title, $parent = null )
 * @method static PageBuilder set( array $params = [] )
 * @method static PageBuilder build()
 * @method static PageBuilder displayPage()
 * @method static PageBuilder get( $property = null )
 */
class Page extends Facade {
	/**
	 * Return the service provider key responsible for the page class.
	 * The key must be the same as the one used when registering
	 * the service provider.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() {
		return 'page';
	}
}
