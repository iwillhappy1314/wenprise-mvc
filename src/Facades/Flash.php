<?php

namespace Wenprise\Facades;


/**
 * @method info($message, $redirectUrl=null, $sticky=false)
 * @method success($message, $redirectUrl=null, $sticky=false)
 * @method warning($message, $redirectUrl=null, $sticky=false)
 * @method error($message, $redirectUrl=null, $sticky=false)
 * @method sticky($message=true, $redirectUrl=null, $type)
 * @method add($message, $type, $redirectUrl=null, $sticky=false)
 * @method display($types=null, $print=true)
 * @method hasErrors()
 * @method hasMessages($type=null)
 * @method formatMessage($msgDataArray, $type)
 * @method doRedirect()
 * @method clear($types=[])
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
