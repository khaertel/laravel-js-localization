<?php namespace JsLocalization;

use App;
use Artisan;
use Config;
use View;
use Illuminate\Support\ServiceProvider;
use JsLocalization\Console\RefreshCommand;

class JsLocalizationServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('andywer/js-localization');

		require __DIR__.'/../../bindings.php';
		require __DIR__.'/../../routes.php';
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerNamespaces();
		$this->registerRefreshCommand();
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('js-localization');
	}

	private function registerNamespaces ()
	{
		$fs = App::make('files');

		if ($fs->isDirectory( app_path().'/config/packages/andywer/js-localization' )) {
			Config::addNamespace('js-localization', app_path().'/config/packages/andywer/js-localization');
		} else {
			Config::addNamespace('js-localization', __DIR__.'/../config');
		}

		View::addNamespace('js-localization', __DIR__.'/../views');
	}

	/**
	 * Register js-localization.refresh
	 */
	private function registerRefreshCommand ()
	{
		$this->app['js-localization.refresh'] = $this->app->share(function($app)
		{
			return new RefreshCommand;
		});

		$this->commands('js-localization.refresh');
	}

}