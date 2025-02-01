<?php

use App\Http\Controllers\PagesController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\QuotesController;
use App\Http\Controllers\TimelineController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\GalleryController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

// 3rd Party packages 

use Spatie\Sitemap\SitemapGenerator;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminArticleCategoriesController;
use App\Http\Controllers\AdminArticleController;
use App\Http\Controllers\AdminBlogController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminSupportController;
use App\Http\Controllers\LinksController;
use App\Http\Controllers\AdminLinksController;
use App\Http\Controllers\AdminTimelineController;
use App\Http\Controllers\AdminGalleryController;
use App\Http\Controllers\AdminQuotesController;
use App\Http\Controllers\AdminGalleryCategoryController;
use App\Http\Controllers\AdminGalleryAlbumController;

// Base Pages

Route::get('/', [PagesController::class, ('home')]);
Route::get('/calculators/mortgage-payments', [PagesController::class, 'mortgagePayments']);
Route::get('/article/{slug}', [PagesController::class, 'article']);
Route::get('/calculators/stamp-duty', [PagesController::class, 'calculateStampDuty'])->name('stamp-duty.calculate');
Route::get('/contact', [PagesController::class, 'contact']);
Route::get('/about', [PagesController::class, 'about']);


Route::resource('/blog', BlogController::class);
Route::resource('/quotes', QuotesController::class);
Route::resource('/timeline', TimelineController::class);
Route::resource('/links', LinksController::class);
Route::resource('/gallery', GalleryController::class);

// Routes first protected by Auth

Route::middleware('auth')->group(function () {

    // Standard Routes that require login to access
    Route::resource('/profile', ProfileController::class);
    Route::post('/comments', [CommentsController::class, 'store'])->name('comments.store');
    Route::resource('support', SupportController::class);

    // Protect the Dashboard routes behind both Auth and Can
    Route::prefix('admin')->middleware('can:Admin')->group(function () {
        Route::resource('/', AdminController::class);
        Route::resource('/users', AdminUserController::class);
        Route::resource('/articles', AdminArticleCategoriesController::class);
        Route::resource('/article', AdminArticleController::class);
        Route::resource('/blog', AdminBlogController::class);
        Route::resource('/support', AdminSupportController::class);
        Route::resource('/links', AdminLinksController::class);
        Route::get('/timeline', [AdminTimelineController::class, 'index']);
        Route::get('/quotes', [AdminQuotesController::class, 'index']);
        Route::get('/gallery', [AdminGalleryController::class, 'index'])->name('admin.gallery.index');
        Route::resource('/gallery/categories', AdminGalleryCategoryController::class)->except(['show']);
        Route::resource('/gallery/albums', AdminGalleryAlbumController::class)->except(['show']);
        
        Route::get('/gallery/categories/{id}/edit', [AdminGalleryCategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/gallery/categories/{id}', [AdminGalleryCategoryController::class, 'update'])->name('categories.update');
        
        Route::get('/gallery/albums/{id}/edit', [AdminGalleryAlbumController::class, 'edit'])->name('albums.edit');
        Route::put('/gallery/albums/{id}', [AdminGalleryAlbumController::class, 'update'])->name('albums.update');
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

