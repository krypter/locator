<?php namespace Krypter\Locator\Facade;

/**
 * @see \Krypter\Locator\Finder
 */
class Locator extends \Illuminate\Support\Facades\Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'locator'; }

}
