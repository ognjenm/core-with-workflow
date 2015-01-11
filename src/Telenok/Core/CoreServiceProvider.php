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
        $this->app['view']->addNamespace('core', __DIR__ . '/../../views');
        $this->app['translator']->addNamespace('core', __DIR__ . '/../../lang'); 

        include __DIR__ . '/../../config/route.php';
        include __DIR__ . '/../../config/IoC.php';

        $this->commands('command.telenok.install');
        $this->commands('command.telenok.migrate');

        if (!storage_path() . '/installedTelenokCore')
		{
	        return;
		}

        include __DIR__ . '/../../config/event.php';

        \Event::fire('telenok.compile.setting');

        if (!\Request::is('telenok', 'telenok/*'))
        {
            $routersPath = storage_path() . '/route/route.php';

            if (!file_exists($routersPath))
            {
                \Event::fire('telenok.compile.route');
            }

            include $routersPath;
        }

        \Auth::extend('custom', function()
        {
            return new \Telenok\Core\Security\Guard(
                    new \Telenok\Core\Security\UserProvider($this->app['hash'], $this->app['config']['auth.model']), $this->app['session.store']
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
        // The path to the user config file
        $userConfigPath = app()->configPath() . '/packages/telenok/core/config.php';

        // Path to the default config
        $defaultConfigPath = __DIR__ . '/../../config/config.php';

        // Load the default config
        $config = $this->app['files']->getRequire($defaultConfigPath);

        if (file_exists($userConfigPath)) 
        {       
            // User has their own config, let's merge them properly
            $userConfig = $this->app['files']->getRequire($userConfigPath);
            $config = array_replace_recursive($config, $userConfig);
        }

        $this->app['config']->set('core::config', $config);
		
        $this->app['command.telenok.install'] = $this->app->share(function($app)
        {
            return new \Telenok\Core\Command\Install();
        });

        $this->app['command.telenok.migrate'] = $this->app->share(function($app)
        {
			$packagePath = $app['path.base'].'/vendor';

			return new \Telenok\Core\Command\Migrate($app['migrator'], $packagePath);
        });

        if (!storage_path() . '/installedTelenokCore')
		{
	        return;
		}

        $this->app->singleton('telenok.config', '\Telenok\Core\Config');

        $this->registerMemcache();
    }

    public function registerMemcache()
    {
        $cfg = $this->app['config'];
        
        $isCacheDriver = $cfg['cache.driver'] == 'memcache';
        $servers = $cfg['cache.memcache']?:$cfg['cache.memcached'];
        $prefix = $cfg['cache.prefix'];
        $isSessionDriver = $cfg['session.driver'] == 'memcache';
        $minutes = $cfg['session.lifetime'];
        $memcache = $repo = $handler = $manager = $driver = null;
        
        if ($isCacheDriver)
        {
            $memcache = (new \Telenok\Core\Support\Memcache\MemcacheConnector())->connect($servers);
            $repo = new \Illuminate\Cache\Repository(new \Telenok\Core\Support\Memcache\MemcacheStore($memcache, $prefix));
            
            $this->app->resolving('cache', function($cache) use ($repo)
            {
                $cache->extend('memcache', function($app) use ($repo)
                {
                    return $repo;
                });
            });
            
            if ($isSessionDriver)
            {
                $handler = new \Telenok\Core\Support\Memcache\MemcacheHandler($repo, $minutes);
                $manager = new \Telenok\Core\Support\Memcache\MemcacheSessionManager($handler);
                
                $driver = $manager->driver('memcache');
                
                $this->app->resolving('session', function($session) use ($driver)
                {
                    $session->extend('memcache', function($app) use ($driver)
                    {
                        return $driver;
                    });
                });
            }
        }
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
