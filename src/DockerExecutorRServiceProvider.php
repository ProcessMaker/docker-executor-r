<?php
namespace ProcessMaker\Package\DockerExecutorR;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use ProcessMaker\Traits\PluginServiceProviderTrait;
use ProcessMaker\Package\Packages\Events\PackageEvent;
use ProcessMaker\Package\DockerExecutorR\Listeners\PackageListener;

class DockerExecutorRServiceProvider extends ServiceProvider
{
    use PluginServiceProviderTrait;

    const version = '0.0.1'; // Required for PluginServiceProviderTrait

    public function register()
    {
    }

    /**
     * After all service provider's register methods have been called, your boot method
     * will be called. You can perform any initialization code that is dependent on
     * other service providers at this time.  We've included some example behavior
     * to get you started.
     *
     * See: https://laravel.com/docs/5.6/providers#the-boot-method
     */
    public function boot()
    {
        \Artisan::command('docker-executor-r:install', function () {
            // nothing to do here
        });
        
        $config = [
            'name' => 'R',
            'runner' => 'RRunner',
            'mime_type' => 'application/R',
            'image' => env('SCRIPTS_R_IMAGE', 'processmaker4/executor-r'),
            'options' => [
            ]
        ];
        config(['script-runners.r' => $config]);

        $this->app['events']->listen(PackageEvent::class, PackageListener::class);

        // Complete the plugin booting
        $this->completePluginBoot();
    }
}