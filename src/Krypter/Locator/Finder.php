<?php namespace Krypter\Locator;

use App, Config, Request, Redirect, Route;

class SupportedLocalesMustAnArrayException extends \Exception {}

class Finder {

	protected $path;

	protected $locale;

	protected $locales;

	protected $redirect;

	function __construct()
	{
		$path = Request::path();

		$this->path = [
			'full' => $path,
			'locale' => explode('/', $path)[0]
		];

		$this->guardSupportedLocales();
		$this->locales = Config::get('locator::supported_locales');

		$this->locale = $this->redirect = Config::get('app.fallback_locale');
	}

	public function shouldRedirect()
	{
		$redirect = true;
		
		foreach($this->locales as $locale)
	    {
	    	if($this->path['locale'] == $locale)
	    	{
	    		App::setLocale($locale);
	    		$redirect = false;
	    		break;
	    	}
	    }

	    return $redirect;
	}

	public function redirect()
	{
		return $this->shouldRedirect() ? $this->forceRedirect() : null;
	}

	public function forceRedirect()
	{
		return Redirect::to('/'.$this->redirect.'/'.$this->path['full']);
	}

	public function route($parameters, $callback)
	{
		if( ! $this->shouldRedirect()) Route::group($this->parameters($parameters), $callback);
	}

	protected function parameters($parameters)
	{
		$prefix = ['prefix' => Config::get('app.locale')];

		return is_array($parameters) ? array_merge($parameters, $prefix) : $prefix;
	}

	protected function guardSupportedLocales()
	{
		if( ! is_array(Config::get('locator::supported_locales'))) throw new SupportedLocalesMustAnArrayException("Supported locales config must be an array.", 500);
	}

}