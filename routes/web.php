<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\RegisterController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Admin\Article\ArticleController;
use App\Http\Controllers\Admin\ArticleCategory\ArticleCategoryController;
use App\Http\Controllers\Admin\Keyword\KeywordController;
use App\Http\Controllers\Admin\Log\LogController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/admin/logout', [LoginController::class, 'logout'])->name('logout');


Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {

    Route::get('/', function () {
        return redirect()->route('dashboard');
    })->name('admin.home');

    Route::get('/dashboard', function () {
        return view('admin.dashboard.index');
    })->name('dashboard');

    // keywords routes
    Route::group(['prefix' => 'keywords'],function(){
        Route::get('/get', [KeywordController::class, 'getKeywords'])->name('admin.keywords.get');
        Route::post('/store', [KeywordController::class, 'store'])->name('admin.keywords.store');
    });
    

    Route::group(['prefix' => 'articles','middleware' => 'can:article.view'],function(){
        Route::get('/',[ArticleController::class, 'index'])->name('admin.articles');
        Route::get('/data',[ArticleController::class, 'getArticlesDataTable'])->name('admin.articles.dataTable');
        Route::get('/create',[ArticleController::class, 'create'])->name('admin.articles.create');
        Route::post('/store',[ArticleController::class, 'store'])->name('admin.articles.store');
        Route::get('/edit/{id}',[ArticleController::class, 'edit'])->name('admin.articles.edit');
        Route::put('/update/{id}',[ArticleController::class, 'update'])->name('admin.articles.update');
        Route::delete('/delete/{id}',[ArticleController::class, 'destroy'])->name('admin.articles.delete');
    });
    
    Route::group(['prefix' => 'article-categories','middleware' => 'can:articleCategory.view'],function(){
        Route::get('/',[ArticleCategoryController::class, 'index'])->name('admin.article.categories');
        Route::get('/data',[ArticleCategoryController::class, 'getArticleCategoriesDataTable'])->name('admin.article.categories.dataTable');
        Route::get('/create',[ArticleCategoryController::class, 'create'])->name('admin.article.categories.create');
        Route::post('/store',[ArticleCategoryController::class, 'store'])->name('admin.article.categories.store');
        Route::get('/edit/{id}',[ArticleCategoryController::class, 'edit'])->name('admin.article.categories.edit');
        Route::put('/update/{id}',[ArticleCategoryController::class, 'update'])->name('admin.article.categories.update');
        Route::delete('/delete/{id}',[ArticleCategoryController::class, 'destroy'])->name('admin.article.categories.delete');
    });


    Route::group(['prefix' => 'users'],function(){
        Route::get('/',[UserController::class, 'index'])->name('admin.users');
        Route::get('/create',[UserController::class, 'create'])->name('admin.users.create');
        Route::post('/store',[UserController::class, 'store'])->name('admin.users.store');
        Route::get('/edit/{user}',[UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/update/{user}',[UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/delete/{user}',[UserController::class, 'destroy'])->name('admin.users.delete');
    });

    Route::group(['prefix' => 'logs'],function(){
        Route::get('/',[LogController::class, 'index'])->name('admin.logs');
        Route::get('/data',[LogController::class, 'getLogsDataTable'])->name('admin.logs.dataTable');
    });
    
    
});