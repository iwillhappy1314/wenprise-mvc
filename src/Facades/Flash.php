<?php

namespace Wenprise\Facades;


/**
 * @method static info( $message, $redirectUrl = null, $sticky = false )
 * @method static success( $message, $redirectUrl = null, $sticky = false )
 * @method static warning( $message, $redirectUrl = null, $sticky = false )
 * @method static error( $message, $redirectUrl = null, $sticky = false )
 * @method static sticky( $message = true, $redirectUrl = null, $type )
 * @method static add( $message, $type, $redirectUrl = null, $sticky = false )
 * @method static display( $types = null, $print = true )
 * @method static hasErrors()
 * @method static hasMessages( $type = null )
 * @method static formatMessage( $msgDataArray, $type )
 * @method static doRedirect()
 * @method static clear( $types = [] )
 */
class Flash extends Facade {
	/**
	 * Return the service provider key responsible for the ajax class.
	 * The key must be the same as the one used when registering
	 * the service provider.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() {
		return 'flash';
	}
}
