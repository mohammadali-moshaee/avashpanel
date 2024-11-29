<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\RegisterController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Admin\Article\ArticleController;
use App\Http\Controllers\Admin\ArticleCategory\ArticleCategoryController;
use App\Http\Controllers\Admin\Keyword\KeywordController;
use App\Http\Controllers\Admin\Log\LogController;
use App\Http\Controllers\Admin\SocialPage\SocialPageController;
use App\Http\Controllers\Admin\ContactInfo\ContactInfoController;
use App\Http\Controllers\Admin\ProductCategory\CategoryController;
use App\Http\Controllers\Admin\Attribute\AttributeController;
use App\Http\Controllers\Admin\Product\ProductController;


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
    
    
    Route::group(['prefix' => 'social-pages'],function(){
        Route::get('/',[SocialPageController::class, 'index'])->name('admin.social-pages');
        Route::post('/update/{socialPage}',[SocialPageController::class, 'update'])->name('admin.social-pages.update');
    });

    Route::group(['prefix' => 'contact-info'],function(){
        Route::get('/',[ContactInfoController::class, 'index'])->name('admin.contact-info');
        Route::post('/update/{contactInfo}',[ContactInfoController::class, 'update'])->name('admin.contact-info.update');
    });

    Route::group(['prefix' => 'shop'],function(){

        // SHOP - category
        Route::group(['prefix' => 'category'],function(){
            Route::get('/',[CategoryController::class, 'index'])->name('admin.shop.category.index');
            Route::get('/edit/{category}',[CategoryController::class, 'edit'])->name('admin.shop.category.edit');
            Route::put('/update/{category}',[CategoryController::class, 'update'])->name('admin.shop.category.update');
            Route::get('/create',[CategoryController::class, 'create'])->name('admin.shop.category.create');
            Route::post('/store',[CategoryController::class, 'store'])->name('admin.shop.category.store');
            Route::delete('/delete/{category}',[CategoryController::class, 'destroy'])->name('admin.shop.category.delete');
        });

        // SHOP - attribute
        Route::group(['prefix' => 'attribute'],function(){
            Route::get('/',[AttributeController::class, 'index'])->name('admin.shop.attribute.index');
            Route::get('/data',[AttributeController::class, 'getAttributesDataTable'])->name('admin.shop.attribute.dataTable');
            Route::get('/create',[AttributeController::class, 'create'])->name('admin.shop.attribute.create');
            Route::post('/store',[AttributeController::class, 'store'])->name('admin.shop.attribute.store');
            Route::get('/edit/{attribute}',[AttributeController::class, 'edit'])->name('admin.shop.attribute.edit');
            Route::put('/update/{attribute}',[AttributeController::class, 'update'])->name('admin.shop.attribute.update');
            Route::delete('/delete/{attribute}',[AttributeController::class, 'destroy'])->name('admin.shop.attribute.delete');

        });
        
        // SHOP - products
        Route::group(['prefix' => 'product'],function(){
            Route::get('/',[ProductController::class, 'index'])->name('admin.shop.product.index');
            Route::get('/data',[ProductController::class, 'getProductsDataTable'])->name('admin.shop.product.dataTable');
            Route::get('/create',[ProductController::class, 'create'])->name('admin.shop.product.create');
            Route::post('/get-attributes',[ProductController::class, 'getAttributesByCategory'])->name('admin.shop.product.get-attributes');
            Route::post('/store',[ProductController::class, 'store'])->name('admin.shop.product.store');
            Route::get('/edit/{product}',[ProductController::class, 'edit'])->name('admin.shop.product.edit');
            Route::put('/update/{product}',[ProductController::class, 'update'])->name('admin.shop.product.update');
            Route::delete('/delete/{product}',[ProductController::class, 'destroy'])->name('admin.shop.product.delete');

        });


    });
    
    
});