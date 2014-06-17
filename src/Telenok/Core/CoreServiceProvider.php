<?php

namespace Telenok\Core;

use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider {

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
        $this->package('telenok/core');
  
        include __DIR__.'/../../config/route.php';
        
        //DONOTDELETETHISCOMMENT
        //return;
        //~DONOTDELETETHISCOMMENT

        include __DIR__.'/../../config/event.php'; 

        \Event::fire('telenok.compile.setting');
        
        $routersPath = storage_path().'/route/route.php';

        if (!file_exists($routersPath)) 
        {
            \Event::fire('telenok.compile.route');
        }
        
        include $routersPath;

        \Auth::extend('custom', function()
        { 
            return new \Telenok\Core\Security\Guard(
                new \Telenok\Core\Security\UserProvider($this->app['hash'], $this->app['config']['auth.model']),
                $this->app['session.store']
            );
        });

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //DONOTDELETETHISCOMMENT
        //return;
        //~DONOTDELETETHISCOMMENT
        
        $this->app->singleton('telenok.config', '\Telenok\Core\Config'); 
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

}