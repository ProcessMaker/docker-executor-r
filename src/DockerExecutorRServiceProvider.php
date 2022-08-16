<?php

namespace ProcessMaker\Package\DockerExecutorR;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use ProcessMaker\Models\ScriptExecutor;
use ProcessMaker\Package\DockerExecutorR\Listeners\PackageListener;
use ProcessMaker\Package\Packages\Events\PackageEvent;
use ProcessMaker\Traits\PluginServiceProviderTrait;

class DockerExecutorRServiceProvider extends ServiceProvider
{
    use PluginServiceProviderTrait;

    const version = '1.0.0'; // Required for PluginServiceProviderTrait

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
            $scriptExecutor = ScriptExecutor::install([
                'language' => 'r',
                'title' => 'R Executor',
                'description' => 'Default R Executor',
            ]);

            // Build the instance image. This is the same as if you were to build it from the admin UI
            \Artisan::call('processmaker:build-script-executor r');

            // Restart the workers so they know about the new supported language
            \Artisan::call('horizon:terminate');
        });

        $config = [
            'name' => 'R',
            'runner' => 'RRunner',
            'mime_type' => 'application/R',
            'options' => [],
            'init_dockerfile' => [
                'ARG SDK_DIR',
                'COPY $SDK_DIR /opt/sdk-r',
                // 'WORKDIR /opt/sdk-r',
                // 'RUN R -e \'install.packages("httr")\'',
                // 'RUN R -e \'install.packages("caTools")\'',
                // 'RUN R -e \'install.packages("testthat")\'',
                // 'RUN R CMD build .',
                // 'RUN R CMD check pmsdk_' . self::version . '.tar.gz',
                // 'RUN R CMD INSTALL pmsdk_1.0.0.tar.gz',
                // 'RUN R -e \'install.packages("pmsdk")\'',
            ],
            'package_path' => __DIR__ . '/..',
            'package_version' => self::version,
            'sdk' => false,
        ];
        config(['script-runners.r' => $config]);

        $this->app['events']->listen(PackageEvent::class, PackageListener::class);

        // Complete the plugin booting
        $this->completePluginBoot();
    }
}
