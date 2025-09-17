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

Route::get('/debug-route', function() {
    $currentCity = app()->has('currentCity') ? app()->get('currentCity') : null;
    $route = request()->route();

    return response()->json([
        'current_city' => $currentCity ? $currentCity->toArray() : null,
        'session_city' => session('current_city_slug'),
        'route_parameters' => $route ? $route->parameters() : [],
        'request_path' => request()->path(),
        'request_url' => request()->url(),
        'all_cities' => \App\Models\City::all()->pluck('slug', 'id')
    ]);
});


Route::get('/set-lang/{lang}', function ($lang) {
    Session::put('locale', $lang);
    return redirect()->back();
})->name("lang.change");

Route::get('/', function() {
    $defaultCity = \App\Models\City::where('is_default', true)->first();
    return redirect("/{$defaultCity->slug}");
});

Route::group(['prefix' => '{city}', 'middleware' => ['detect.city']], function () {

    // Главная
    Route::get('/', function($city){
        return app(SiteController::class)->getPage(request(), '/'); // slug = '/' для home
    })->name('home.city');

    // Статические страницы
    Route::get('/about', fn($city) => app(SiteController::class)->getPage(request(), 'about'))->name('about.city');
    Route::get('/contacts', fn($city) => app(SiteController::class)->getPage(request(), 'contacts'))->name('contacts.city');

    // Статьи
    Route::get('/articles/{slug}', [SiteController::class, 'getArticle'])->name('article.city.show');

    // Категории
    Route::get('/catalog/{slug}', [SiteController::class, 'getCategory'])->name('category.city.show');

    // Товары
    Route::get('/catalog/{category}/{slug}', [SiteController::class, 'getProduct'])->name('product.city.show');

    // Catch-all для остальных страниц
    Route::get('/{slug}', function($city, $slug){
        return app(SiteController::class)->getPage(request(), $slug);
    })->where('slug', '.*')->name('pages.city.get');
});

// Редирект с корня на дефолтный город
Route::get('/', function(){
    $defaultCity = \App\Models\City::where('is_default', true)->first();
    if (!$defaultCity) abort(404, 'Default city not found');
    return redirect("/{$defaultCity->slug}");
});

// legacy маршруты только для fallback (если нет city)
Route::controller(SiteController::class)->group(function () {
    Route::get('/{page?}', 'getPage')
         ->where('page', '.*')
         ->name('pages.get');
    Route::get('/articles/{slug}', 'getArticle')->name('article.show');
    Route::get('/catalog/{slug}', 'getCategory')->name('category.show');
    Route::get('/catalog/{category}/{slug}', 'getProduct')->name('product.show');
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


            // Добавьте это в конец файла routes/web.php
// Route::get('/test-city/{city?}', function ($city = null) {
//     $currentCity = app('currentCity');
//     return response()->json([
//         'city_from_url' => $city,
//         'current_city' => $currentCity ? $currentCity->toArray() : null,
//         'session_city' => session('current_city_slug'),
//         'all_cities' => \App\Models\City::all()->toArray()
//     ]);
// })->middleware('detect.city');

// Добавьте в routes/web.php для тестирования
// Route::get('/test/{city?}', function ($city = null) {
//     return "Город из URL: " . ($city ?? 'не указан');
// });




