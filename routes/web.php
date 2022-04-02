<?php

use Illuminate\Support\Facades\Route;
use io3x1\FilamentBrowser\Http\Controllers\BrowserController;


Route::post('admin/browser/json', [BrowserController::class, 'index'])->middleware('web');
