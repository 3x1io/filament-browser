<?php

namespace io3x1\FilamentBrowser;

use Filament\PluginServiceProvider;
use io3x1\FilamentBrowser\Pages\Browser;
use Spatie\LaravelPackageTools\Package;

class FilamentBrowserProvider extends PluginServiceProvider
{

    public static string $name = 'filament-browser';

    protected array $resources = [];

    protected array $pages = [
        Browser::class
    ];

    public function configurePackage(Package $package): void
    {
        $package->name('filament-browser');
    }

    public function boot(): void
    {
        parent::boot();

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'filament-browser');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $this->publishes([
            __DIR__ . '/../publish' => public_path(),
        ], 'filament-browser-js');
    }
}
