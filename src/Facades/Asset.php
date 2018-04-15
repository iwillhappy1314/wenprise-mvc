<?php

namespace Wenprise\Facades;


/**
 * @method static \Wenprise\Asset\Asset add($handle, $path, $deps = [], $version = '1.0', $mixed = null, $type = '')
 */
class Asset extends Facade {
	/**
	 * Return the service provider key responsible for the asset class.
	 * The key must be the same as the one used when registering
	 * the service provider.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() {
		return 'asset';
	}
}
