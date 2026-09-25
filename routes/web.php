<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\InitiativeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\CmsMediaUploadController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\setLanguange;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/en');

/*
|--------------------------------------------------------------------------
| Public site (/{lang})
|--------------------------------------------------------------------------
*/
Route::middleware([setLanguange::class])->group(function () {
    Route::group(['prefix' => '{lang}', 'where' => ['lang' => 'id|en']], function () {
        Route::get('/', [IndexController::class, 'index'])->name('index');
        Route::get('/about', [PagesController::class, 'about'])->name('about');
        Route::get('/team-technic', [TeamController::class, 'index'])->name('team-technic');
        Route::get('/team-scientific', [TeamController::class, 'scientific'])->name('team-scientific');
        Route::get('/faq', [FaqController::class, 'listFaq'])->name('faq');
        Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');
    });
});

/*
|--------------------------------------------------------------------------
| CMS auth
|--------------------------------------------------------------------------
*/
Route::middleware(['guest'])->group(function () {
    Route::get('/cms/login', [DashboardController::class, 'login'])->name('login');
});

/*
|--------------------------------------------------------------------------
| CMS (requires login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('cms')->name('cms.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Pages
    Route::get('/pages', [PagesController::class, 'cmsIndex'])->name('pages.index');
    Route::get('/pages/{slug}', [PagesController::class, 'cmsEdit'])->name('pages.edit');

    // News
    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/create', [NewsController::class, 'create'])->name('news.create');
    Route::get('/news/{id}/edit', [NewsController::class, 'edit'])->name('news.edit');

    // FAQ
    Route::get('/listfaq', [FaqController::class, 'index'])->name('faq.index');
    Route::get('/addfaq', [FaqController::class, 'add'])->name('faq.create');
    Route::get('/editfaq/{id}', [FaqController::class, 'edit'])->name('faq.edit');

    // Team
    Route::get('/team', [TeamController::class, 'cmsIndex'])->name('team.index');
    Route::get('/team/create', [TeamController::class, 'cmsCreate'])->name('team.create');
    Route::get('/team/{id}/edit', [TeamController::class, 'cmsEdit'])->name('team.edit');

    // Initiatives
    Route::get('/initiatives', [InitiativeController::class, 'index'])->name('initiatives.index');
    Route::get('/initiatives/create', [InitiativeController::class, 'create'])->name('initiatives.create');
    Route::get('/initiatives/{id}/edit', [InitiativeController::class, 'edit'])->name('initiatives.edit');

    // Partners
    Route::get('/partners', [PartnerController::class, 'index'])->name('partners.index');
    Route::get('/partners/create', [PartnerController::class, 'create'])->name('partners.create');
    Route::get('/partners/{id}/edit', [PartnerController::class, 'edit'])->name('partners.edit');

    // Media Manager (replaces LFM for TinyMCE & CMS)
    Route::get('/media', [CmsMediaUploadController::class, 'index'])->name('media.index');
    Route::get('/media/files', [CmsMediaUploadController::class, 'listFiles'])->name('media.list');
    Route::post('/media/upload', [CmsMediaUploadController::class, 'upload'])->name('media.upload');
    Route::post('/tinymce-upload', [CmsMediaUploadController::class, 'upload'])->name('tinymce.upload');
    Route::delete('/media/delete', [CmsMediaUploadController::class, 'delete'])->name('media.delete');

    // Admin only
    Route::middleware(['admin'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    });

    Route::post('/logout', [DashboardController::class, 'logout'])->name('logout');
});
