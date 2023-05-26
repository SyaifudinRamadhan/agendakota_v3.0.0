<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => "event"], function () {
    Route::post('buat', "Api\EventController@store");
    Route::post('recommendation', "Api\HomepageController@recomendEvent");
    Route::post('by-city', "Api\HomepageController@cityFilter");
    Route::post('by-time', "Api\HomepageController@timeFilter");
    Route::post('featured', "Api\HomepageController@featuredEvent");
    Route::post('favorite-organizer', "Api\HomepageController@favoriteOrganizer");
    Route::post('cat-list', "Api\HomepageController@categoryList");
    Route::post('topic-list', "Api\HomepageController@topicList");
    Route::post('city-list', "Api\HomepageController@cityList");
    Route::post('banners', "Api\HomepageController@bannerList");
    Route::post('explore', "Api\EventController@explore");
    Route::post('{id}', "Api\EventController@detail");
    Route::group(['prefix' => "{id}"], function () {
        Route::post('/', "Api\EventController@detail");
        Route::post('overview', "Api\EventController@overview");
        Route::post('link/update', "Api\EventController@updateLink");
        Route::post('speaker', "Api\EventController@speaker");
        Route::group(['prefix' => "rundown"], function () {
            Route::post('/', "Api\EventController@rundown");
            Route::post('store', "Api\RundownController@store");
        });
        Route::group(['prefix' => "handbook"], function () {
            Route::post('/', "Api\EventController@handbook");
            Route::post('store', "Api\HandbookController@store");
            Route::post('{handbookID}/delete', "Api\HandbookController@delete");
        });
        Route::group(['prefix' => "sponsor"], function () {
            Route::post('/', "Api\EventController@sponsor");
            Route::post('store', "Api\SponsorController@store");
            Route::post('{sponsorID}/delete', "Api\SponsorController@delete");
            Route::post('{sponsorID}/update', "Api\SponsorController@update");
        });
        Route::group(['prefix' => "session"], function () {
            Route::post('/', "Api\EventController@session");
            Route::post('{sessionID}/delete', "Api\SessionController@delete");
        });

        Route::group(['prefix' => "ticket"], function () {
            Route::post('buy', "Api\TicketController@buy");
            Route::post('checkout', "Api\TicketController@checkout");
        });
    });
});

Route::group(['prefix' => "user"], function () {
    Route::post('organization', "Api\UserController@organization");
    Route::post('login', "Api\UserController@login");
    Route::post('logout', "Api\UserController@logout");
    Route::post('register', "Api\UserController@register");
    Route::post('profile', "Api\UserController@profile");
    Route::post('otp', "Api\UserController@otpAuth");
    Route::post('register-google', "UserController@register")->name('api.user.registerWithGoogle')->middleware('NoCors');

    Route::group(['prefix' => "bank"], function () {
        Route::post('store', "Api\WithdrawController@storeBank");
        Route::post('delete', "Api\WithdrawController@deleteBank");
        Route::post('update', "Api\WithdrawController@updateBank");
        Route::post('/', "Api\UserController@banks");
    });
});

Route::post('otp', 'Api\OtpController@auth');

Route::group(['prefix' => "organization"], function () {
    Route::post('store', "OrganizationController@store");
    Route::post('create', "Api\OrganizationController@create");
    Route::post('profile/{id}', "Api\OrganizationController@profile");
    Route::post('profile/{id}/update', "Api\OrganizationController@update");
    Route::post('{id}/withdraw', "Api\OrganizationController@withdraw");
    
    Route::post('event', "Api\OrganizationController@event");
    Route::group(['prefix' => "event/{id}"], function () {
        Route::post('dashboard', "Api\EventController@dashboard");
        Route::post('ticket-sales', "Api\EventController@ticketSales");
        Route::get('visitor', "Api\EventController@visitor");
    });

    Route::group(['prefix' => "{organizerID}/team"], function () {
        Route::post('invite', "Api\OrganizationController@inviteTeam");
        Route::post('remove', "Api\OrganizationController@removeTeam");
        Route::get('/', "Api\OrganizationController@getTeam");
    });

    Route::post('checkin', "Api\EventController@checkin");
});

Route::group(['prefix' => "category"], function () {
    Route::post('/', "Api\CategoryController@get");
});
Route::group(['prefix' => "city"], function () {
    Route::post('/', "Api\CityController@get");
});

Route::group(['prefix' => "purchase"], function () {
    Route::post('/', "Api\UserController@myTicket");
    Route::post('/{id}', "Api\PurchaseController@get");
});

Route::group(['prefix' => "rajaongkir"], function () {
    Route::post('province', "RajaongkirController@province")->name('rajaongkir.province');
    Route::post('city', "RajaongkirController@city")->name('rajaongkir.city');
});

Route::post('invoice-callback', "Api\TicketController@invoiceCallback");

Route::post(
    'visitor-scan/{organizerID}/{eventID}/{orderID}/{userID}',
    "CheckinController@scanCode"
);