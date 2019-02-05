<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() == 'local') {
            $this->app->register('Wn\Generators\CommandsServiceProvider');
        }
        $this->app->bind('League\Fractal\Manager', function($app) {
            $manager = new \League\Fractal\Manager;
    
            // Useing the root serializer.
            $manager->setSerializer(new \App\Http\Serializers\RootSerializer);
    
            return $manager;
        });
    }
}
