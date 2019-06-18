<?php

namespace App\Providers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use TheDarkKid\Flysystem\Cloudinary\CloudinaryAdapter;
use League\Flysystem\Filesystem;
class CloudinaryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Storage::extend('cloudinary', function ($app, $config) {
            return new Filesystem(new CloudinaryAdapter($config));
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
