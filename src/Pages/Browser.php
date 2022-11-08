<?php

namespace io3x1\FilamentBrowser\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\File;

class Browser extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $pageHeading = 'Browser';

    protected static string $view = 'filament-browser::browser';

    protected static ?string $navigationGroup = 'Settings';

    protected static function getNavigationLabel(): string
    {
        return __('Browser');
    }

    protected function getViewData(): array
    {
        $startPath = config('filament-browser.start_path');
        $folders =  File::directories($startPath);
        $files =  File::files($startPath);
        $foldersArray = [];
        $filesArray = [];
        $root = $startPath;
        $name = $startPath;

        foreach ($folders as $folder) {
            array_push($foldersArray, [
                "path" => $folder,
                "name" => str_replace($startPath . DIRECTORY_SEPARATOR, '', $folder),
            ]);
        }

        foreach ($files as $file) {
            array_push($filesArray, [
                "path" => $file->getRealPath(),
                "name" => str_replace($startPath . DIRECTORY_SEPARATOR, '', $file),
            ]);
        }

        if ($root == base_path()) {
            array_push($filesArray, [
                "path" => base_path('.env'),
                "name" => ".env",
            ]);
        }

        $foldersArray = array_filter($foldersArray, function ($folder) {
            $path = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $folder['path']);
            return !in_array($path, config('filament-browser.hidden_folders'));
        });

        $filesArray = array_filter($filesArray, function ($file) {
            $path = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $file['path']);
            return !in_array($path, config('filament-browser.hidden_files'));
        });

        $exploadName = explode(DIRECTORY_SEPARATOR, $root);
        $count = count($exploadName);
        $setName = $exploadName[$count - 1];

        return [
            "folders" => $foldersArray,
            "files" => $filesArray,
            "back_path" => str_replace(DIRECTORY_SEPARATOR . $name, '', $root),
            "back_name" => $setName,
            "current_path" => $root
        ];
    }

    public static function navigationGroup(?string $group): void
    {
        static::$navigationGroup = $group;
    }

    public static function navigationIcon(?string $icon): void
    {
        static::$navigationIcon = $icon;
    }

    public static function navigationSort(?int $sort): void
    {
        static::$navigationSort = $sort;
    }

    public static function navigationLabel(?string $string): void
    {
        static::$navigationLabel = $string;
    }

    public static function heading(?string $string): void
    {
        static::$pageHeading = $string;
    }

    protected function getHeading(): string
    {
        return static::$pageHeading;
    }
}
