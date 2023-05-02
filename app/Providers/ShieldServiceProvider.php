<?php
namespace App\Providers;

// Include any required classes, interfaces etc...
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
// use Illuminate\Support\ServiceProvider;
use App\Support\Cerberus\Cerberus;
use App\Support\Cerberus\JWTShield;
use App\Repositories\User\UserRepository;


class ShieldServiceProvider extends ServiceProvider
{
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('shield', function($app){
			return new Cerberus(
				new JWTShield(
					$app['request'],
					new UserRepository($app),
					config('jwt.secret')
				)
			);
		});
	}



	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot()
	{
        parent::boot();
	    //
	}

}