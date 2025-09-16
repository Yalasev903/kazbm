<?php

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiteController;
use App\Http\Middleware\ForceJsonResponse;
use App\Http\Middleware\UserAuthenticate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use Illuminate\Support\Facades\Http;


Route::get('/set-lang/{lang}', function ($lang) {
    Session::put('locale', $lang);
    return redirect()->back();
})->name("lang.change");


Route::controller(SiteController::class)
    ->group(function () {
        Route::get('/{page?}','getPage')->name('pages.get');
        Route::get('/articles/{slug}','getArticle')->name('article.show');
        Route::get('/catalog/{slug}','getCategory')->name('category.show');
        Route::get('/catalog/{category}/{slug}','getProduct')->name('product.show');
    });


Route::get('/forgot-password/{token}', [AuthController::class, 'forgot'])->name('user.forgot_password');
Route::get('/order/invoice/{id_hash}', [OrderController::class, 'invoice'])->name('order.invoice.show');

Route::controller(ProfileController::class)
    ->prefix('profile')
    ->name('profile.')
    ->middleware(UserAuthenticate::class)
    ->group(function () {
        Route::get('/index','index')->name('index');
        Route::get('/history','history')->name('history');
        Route::post('/settings','settings')->name('settings');
    });

Route::prefix('ajax')
    ->name('ajax.')
    ->middleware(ForceJsonResponse::class)
    ->group(function () {

        Route::controller(AjaxController::class)
            ->group(function () {
                # search Products


                Route::get('/delivery-costs', 'getDeliveryCosts')->name('calculator.getDeliveryCosts');

                # search Products
                Route::post('/search/products', 'search')->name('product.search');

                # get Filter data
                Route::post('/filter/products','products')->name('product.get');

                # set Pagination
                Route::post('/pagination/articles','articles')->name('article.pagination');
            });

        Route::controller(CartController::class)
            ->prefix('cart')
            ->name('cart.')
            ->group(function () {

                # CRUD cart products
                Route::post('/add', 'add')->name('add');
                Route::post('/copy', 'copy')->name('copy');
                Route::post('/update', 'update')->name('update');
                Route::post('/remove', 'remove')->name('remove');
            });

        Route::controller(OrderController::class)
            ->group(function () {
                Route::post('/order/create', 'store')->name('order.create');
            });

        Route::controller(AuthController::class)
            ->prefix('user')
            ->name('user.')
            ->group(function () {
                Route::post('/register', 'register')->name('register');
                Route::post('/login', 'login')->name('login');
                Route::post('/recovery', 'recovery')->name('recovery');
                Route::post('/logout', 'logout')->name('logout');
            });

        # send Application
        Route::post('/application/call',[FeedbackController::class, 'call'])->name('application.call');
        Route::post('/application/consultation',[FeedbackController::class, 'consultation'])->name('application.consult');

    });


