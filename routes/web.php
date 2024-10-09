<?php

use App\Http\Controllers\PagesController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\QuotesController;
use App\Http\Controllers\TimelineController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContentImageController;
use App\Http\Controllers\CommentsController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminArticleCategoriesController;
use App\Http\Controllers\AdminArticleController;
use App\Http\Controllers\AdminBlogController;

route::get('/', [PagesController::class, ('home')]);
route::get('/calculators/mortgage-payments', [PagesController::class, 'mortgagePayments']);
route::get('/article/{slug}', [PagesController::class, 'article']);
Route::get('/calculators/stamp-duty', [PagesController::class, 'calculateStampDuty'])->name('stamp-duty.calculate');

Route::resource('/blog', BlogController::class);
Route::resource('/quotes', QuotesController::class);
Route::resource('/timeline', TimelineController::class);

Route::middleware('auth')->group(function () {

    // Standard Routes
    Route::resource('/profile', ProfileController::class);
    Route::post('/comments', [CommentsController::class, 'store'])->name('comments.store');

    // Protect the Dashboard routes behind Admin
    Route::prefix('admin')->group(function () {
        Route::resource('/', AdminController::class)->middleware('can:Admin');
        Route::resource('/articles', AdminArticleCategoriesController::class)->middleware('can:Admin');
        Route::resource('/article', AdminArticleController::class)->middleware('can:Admin');
        Route::resource('/blog', AdminBlogController::class)->middleware('can:Admin');
    });

});

Route::get('/logout', function(){
    Session::flush();
    Auth::logout();
    return Redirect::to("/");
});

require __DIR__.'/auth.php';

