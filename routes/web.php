<?php

use App\Http\Controllers\PagesController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\SupportController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

// 3rd Party packages 

use Spatie\Sitemap\SitemapGenerator;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminBlogController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminSupportController;
use App\Http\Controllers\AdminServersController;
use App\Http\Controllers\AdminCpanelController;
use App\Http\Controllers\CpanelController;


// Base Pages

Route::get('/', [PagesController::class, ('home')]);
Route::get('/article/{slug}', [PagesController::class, 'article']);
Route::get('/contact', [PagesController::class, 'contact']);
Route::get('/about', [PagesController::class, 'about']);

Route::resource('/blog', BlogController::class);

// Routes first protected by Auth

Route::middleware('auth')->group(function () {

    // Standard Routes that require login to access
    Route::resource('/profile', ProfileController::class);
    Route::post('/comments', [CommentsController::class, 'store'])->name('comments.store');
    Route::resource('support', SupportController::class);

    Route::resource('hosting', CpanelController::class);

    // Protect the Dashboard routes behind both Auth and Can
    Route::prefix('admin')->middleware('can:Admin')->group(function () {
        Route::resource('/', AdminController::class);
        Route::resource('/users', AdminUserController::class);
        Route::resource('/blog', AdminBlogController::class);
        Route::resource('/support', AdminSupportController::class);
        Route::resource('/servers', AdminServersController::class);
        Route::get('/cpanel', [AdminCpanelController::class, 'index']);

        Route::post('/cpanel/create-account', [AdminCpanelController::class, 'createAccount'])
            ->name('admin.cpanel.create');

        Route::post('/cpanel/rejectHosting', [AdminCpanelController::class, 'rejectHosting'])
            ->name('admin.cpanel.reject');

        Route::post('/cpanel/banHosting', [AdminCpanelController::class, 'banHosting'])
            ->name('admin.cpanel.ban');

        Route::post('/cpanel/suspendAccount', [AdminCpanelController::class, 'suspendAccount'])
            ->name('admin.cpanel.suspend');

        Route::post('/cpanel/deleteAccount', [AdminCpanelController::class, 'deleteAccount'])
            ->name('admin.cpanel.delete');

        Route::post('/cpanel/unsuspendAccount', [AdminCpanelController::class, 'unsuspendAccount'])
            ->name('admin.cpanel.unsuspend');
        
    });

});

// Sitemap by Spatie - Need to run generate-sitemap

Route::get('/generate-sitemap', function () {
    SitemapGenerator::create(config('app.url'))->writeToFile(public_path('sitemap.xml'));
    
    return 'Sitemap generated!';
});

// Logout route to clear session.

Route::get('/logout', function(){
    Session::flush();
    Auth::logout();
    return Redirect::to("/");
});

require __DIR__.'/auth.php';

