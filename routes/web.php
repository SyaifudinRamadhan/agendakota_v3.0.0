<?php

use App\Http\Controllers\OrganizationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('pwd', function () {
    return bcrypt('inikatasandi');
});
Route::get('otp', function () {
    $user = App\Models\User::where('email', 'riyan.satria.619@gmail.com')->first();
    $otpArr = [
        'code' => '1234',
        'action' => 'register'
    ];
    $otp = json_decode(json_encode($otpArr), false);

    return new App\Mail\OtpMailer([
        'user' => $user,
        'otp' => $otp,
    ]);
});

//Umum

Route::middleware(['Cors'])->group(function () {
    Route::get('test-vid-page', "TestController@testOnly");
    Route::get('studio', "TestController@testStudio");
});

Route::get('buat-event', "HomePageController@createEvent")->name('create-event');
Route::get('buat-event/after', "HomePageController@afterCreateEvent");
Route::get('/', "HomePageController@homePage")->name('user.homePage');
Route::get('/new', "HomePageController@homePageNew")->name('user.homePage.new');
Route::get('/faq', "HomePageController@faq")->name('homepage.faq');
Route::get('page/{slug}', "HomePageController@page")->name('homepage.page');
Route::get('city', "HomePageController@city")->name('homepage.city');

Route::get('login', "UserController@loginPage")->name('user.loginPage');
Route::get('login-google/{email?}', "UserController@loginWithGoogle")->name('user.loginWithGoogle');
Route::get('login2', "UserController@login2Page")->name('user.loginPage2');
Route::get('register', "UserController@registerPage")->name('user.registerPage');
Route::get('register-google/{email?}', "UserController@registerWithGoogle")->name('user.registerWithGoogle');
Route::get('forgot-password', "UserController@forgotPasswordPage")->name('user.forgotPasswordPage');//form
Route::post('forgot-password', "UserController@forgotPassword")->name('user.forgotPassword');//store
Route::get('reset-password/{email}', "UserController@resetPasswordPage")->name('user.resetPasswordPage');//form
Route::post('reset-password/', "UserController@updatePassword")->name('user.updatePassword');
Route::post('login', "UserController@login")->name('user.login');
Route::post('register', "UserController@register")->name('user.register');
Route::get('loginReg/{provider}', "LoginGoogleController@redirectToProvider")->name('user.loginGooglePage');
Route::get('login-reg-google/{provider}/{param}',"LoginGoogleController@redirectToProvider")->name('user.loginGooglePage2');
Route::get('loginReg/{provider}/callback', "LoginGoogleController@handleProvideCallback")->name('user.loginGoogle');

//Stream
Route::get('stream/{slug}', "StreamController@index")->name('stream.index');
Route::get('chat/{slug}', "StreamController@chat")->name('stream.chat');
Route::get('stream/{slug}/stage', "StreamController@stage")->name('stream.stage');

Route::get('logout', "UserController@logout")->name('user.logout');

//Message
Route::get('messages/latest/{id}', "MessageController@lastMessage")->name('message.latest');
Route::get('messages/all/{id}', "MessageController@AllMessage")->name('message.all');
Route::put('messages/send/chat', "MessageController@SendMessage")->name('message.send');
Route::post('messages/search/user/', "MessageController@searchUser")->name('message.search.user');
Route::post('messages/tambah/user/', "ConnectionController@addConnection")->name('message.tambah.user');

// //Ongkir
Route::get('province','OngkirController@get_province')->name('ongkir.province');
Route::get('/city/{id}','OngkirController@get_city')->name('ongkir.city');
Route::get('/origin={asal}&destination={tujuan}&weight={berat}&courier={kurir}','OngkirController@get_ongkir')->name('ongkir.cek');

//laravel gmail
Route::get('verification/{email}', "UserController@emailVerification")->name('user.emailVerification');
Route::get('forgot-password/{email}', "UserController@emailforgotPassword")->name('user.emailforgotPassword');
Route::get('register/invitation/{email}/{event}/{sender}/{ticket}/{purchase}', "InvitationController@registerinvitation")->name('user.event.register-invitation');
Route::get('{id}/confirmation-member/{email}', 'OrganizationController@ConfirmationMember')->name('organization.confirmation-member');
Route::get('{id}/register-member', 'OrganizationController@CreateAkunMember')->name('organization.create-akun-member');
Route::get('verify-bank/{email}/{accountNumber}/{accessCode}/{command}', 'WithdrawController@verifyBankAccount')->name('user.bankAccountVerification');



//homepage
Route::get('explore', "HomePageController@exploreNew")->name('explore');
Route::get('explore-new', "HomePageController@exploreNewNew")->name('explore.new');
Route::post('explore-core', "HomePageController@exploreCore")->name('explore-core');
// Route Homepage Lama (yang masih dipakai hanya /all category dan exhibition)
Route::group(['prefix' => "homepage"], function () {
    // Route::get('/', "HomePageController@homePage")->name('user.homePage');
    Route::post('/search', "HomePageController@search")->name('user.homepage.search');
    Route::get('/allcategory', "HomePageController@allcategory")->name('user.homepage.allcategory');
    Route::get('/{eventID}/{exhibitionID}/exhibitions', "ExhibitorController@showExhibitor")->name('user.home.exhibitions');
    Route::get('/{exhibtionID}/exhibition/vidcallexhibitor', "StreamExhibitorController@streamExhibitor")->name('user.home.exhibitions.vidcall');
    // Route Lama tidak digunakan
    // Route::group(['prefix' => "explore"], function () {
    //     // Route::group(['middleware' => ['Category']], function () {
    // 	//Middleware Category
	// 	Route::group(['middleware' => ['Category']], function() {
	// 		Route::get('/{category}', "HomePageController@category")->name('user.category');
	// 	});
    //         Route::get('/{filter}', "HomePageController@explore")->name('user.explore');
    //     // });
    // });
});


//Admin
Route::group(['prefix' => "admin"], function () {
	Route::get("login", "AdminController@loginPage")->name('admin.loginPage');
	Route::post("login", "AdminController@login")->name('admin.login');
	Route::get('logout', "AdminController@logout")->name('admin.logout');
    Route::get('/', function () {
        return redirect()->route('admin.loginPage');
    });
});


//MIddleware EventDetail
Route::group(['middleware' => ['EventDetail']], function() {
    Route::get('event-detail/{slug}', "HomePageController@eventDetail")->name('user.eventDetail');
    Route::get('event-detail-new/{slug}', "HomePageController@eventDetailNew")->name('user.eventDetail.new');
    Route::post('event-detail/{slug}/buyticket', "CartController@pembelian")->name('user.pembelian');
});

// Route detail organisasi
Route::get('organization-detail/{slug}', 'OrganizationController@organizationDetail')->name('user.organizationDetail');

// Route Custom Link
Route::get('/event/{slug_custom}', "CustomLinkController@index")->name('event.slugcustom.view');

//Middleware Admin
Route::group(['middleware' => ['Admin']], function() {
	Route::group(['prefix' => "admin"], function () {
        Route::get("dashboard", "AdminController@dashboard")->name('admin.dashboard');
        Route::get("karyawan", "AdminController@karyawan")->name('admin.karyawan');
        Route::get("user", "AdminController@user")->name('admin.user');        
        Route::get("finance", "AdminController@finance")->name('admin.finance');
        Route::get("manager", "AdminController@manager")->name('admin.manager');
        Route::get("author", "AdminController@author")->name('admin.author');
        Route::get("event", "AdminController@event")->name('admin.event');
        Route::get('dataevent', 'AdminController@getDataEvent')->name('data.event');
        Route::get("organizer", "AdminController@organizer")->name('admin.organizer');
        Route::get("category", "AdminController@category")->name('admin.category');
        Route::get('/FAQ', "FaqController@index")->name('admin.faq');
        Route::get("page", "PageController@show")->name('admin.page');

        Route::get('datakaryawan', 'AdminController@getDataKaryawan')->name('data.karyawan');
        Route::get('datauser', 'AdminController@getDataUser')->name('data.user');
        Route::get('datafinance', 'AdminController@getDataFinance')->name('data.finance');
        Route::get('datamanager', 'AdminController@getDataManager')->name('data.manager');
        Route::get('dataauthor', 'AdminController@getDataAuthor')->name('data.author');
        Route::get('dataorganizer', 'AdminController@getDataOrganizer')->name('data.organizer');
        
        Route::get('finance-report', "AdminController@financeReport")->name('admin.finance-report');

        Route::group(['prefix' => "user"], function () {
            Route::group(['prefix' => 'organization-type'], function () {
                Route::get('/', "AdminController@organizationType")->name('admin.user.organizationType');
                Route::post('store', "OrganizationController@storeType")->name('admin.user.organizationType.store');
                Route::get('{id}/delete', "OrganizationController@deleteType")->name('admin.user.organizationType.delete');
            });
            Route::post("store", "AdminController@storeDataUser")->name('admin.user.store'); 
            Route::get("{id}/detil", "AdminController@detil_user")->name('admin.user.detil');
            // Route::get("{id}/delete", "AdminController@delete_user")->name('admin.user.delete');  
            Route::get('editUser','AdminController@editUser')->name('admin.user.edit');
            Route::post('updateUser','AdminController@update_user')->name('admin.user.update');                     
        });

        Route::group(['prefix' => "event"], function() {
            Route::get('edit', "AdminController@eventEdit")->name('admin.event.dataedit');
            // Route::get('{idEvent}/delete', "AdminController@deleteEvent")->name('admin.event.delete');
            Route::get('organization/{orgID}/delEvent/{eventID}/delete', "EventController@delete")->name('admin.event.delete');
            // Route::get('{idEvent}/detail', "AdminController@eventDetail")->name('admin.event.detail');
            Route::post('update', "AdminController@updateEvent")->name('admin.event.update');
            Route::get('{eventID}/set-featured', "EventController@setFeatured")->name('admin.event.set_featured');
            Route::get('{eventID}/unset-featured', "EventController@unsetFeatured")->name('admin.event.unset_featured');
        });

        Route::group(['prefix' => "city"], function () {
            Route::post('store', "CityController@store")->name('admin.city.store');
            Route::post('update', "CityController@update")->name('admin.city.update');
            Route::get('{id}/delete', "CityController@delete")->name('admin.city.delete');
            Route::get('priority/{id}/{type}', "CityController@priority")->name('admin.city.priority');
            Route::get('/', "AdminController@city")->name('admin.city');
        });

        Route::group(['prefix' => "karyawan"], function () {
            Route::post("store", "AdminController@storeDataKaryawan")->name('admin.karyawan.store'); 
            Route::get("{id}/detil", "AdminController@detil_karyawan")->name('admin.karyawan.detil');
            Route::get("{id}/delete", "AdminController@delete_karyawan")->name('admin.karyawan.delete'); 
            Route::get('editKaryawan','AdminController@editKaryawan')->name('admin.karyawan.edit');
            Route::post('updateKaryawan','AdminController@update_karyawan')->name('admin.karyawan.update');

                     
        });

        // Route::group(['prefix' => "finance"], function () {
        //     // Route lama akan dinonaftikan
        //     Route::post("store", "AdminController@storeDataFinance")->name('admin.finance.store'); 
        //     Route::get("{id}/detil", "AdminController@detil_finance")->name('admin.finance.detil');
        //     Route::get("{id}/delete", "AdminController@delete_finance")->name('admin.finance.delete');
        //     Route::get('editFinance','AdminController@editFinance')->name('admin.finance.edit');
        //     Route::post('updateFinance','AdminController@update_finance')->name('admin.finance.update');                   
        // });

        Route::group(['prefix' => "package"], function ()
        {
            Route::get('/','AdminController@packagesSelling')->name('admin.packages');
            Route::get('/user-pkg','AdminController@manageUserPkg')->name('admin.package.user-mng');
            Route::get('/user-pkg/{userID}/detail','PackagePricingController@viewUserPkgPayment')->name('admin.package.user-detail');
            Route::post('/user-pkg/change-active','PackagePricingController@changeActivePkg')->name('admin.package.change-active');
            Route::get('/all','AdminController@packages')->name('admin.package.all');
            Route::get('/{pkgID}/delete','PackagePricingController@delete')->name('admin.package.delete');
            Route::post('/{pkgID}/update','PackagePricingController@update')->name('admin.package.update');
            Route::post('/store','PackagePricingController@store')->name('admin.package.store');
        });

        Route::group(['prefix' => "finance-report"], function ()
        {
            Route::post('/verifyWithdraw','WithdrawController@adminVerifyWithdraw')->name('admin.finance-report.verify');  
            Route::get('/withdrawals', 'AdminController@withdrawReport')->name('admin.finance-report.withdrawals');
            Route::get('/withdraw-users', 'AdminController@userWithdraw')->name('admin.finance-report.withdraw-user');
            Route::get('/withdraw-spec/{userID}/user', 'AdminController@withdrawReportSpecial')->name('admin.finance-report.withdraw-userid');
        });

        Route::group(['prefix' => "manager"], function () {
            Route::post("store", "AdminController@storeDataManager")->name('admin.manager.store'); 
            Route::get("{id}/detil", "AdminController@detil_manager")->name('admin.manager.detil');
            Route::get("{id}/delete", "AdminController@delete_manager")->name('admin.manager.delete');
            Route::get('editManager','AdminController@editManager')->name('admin.manager.edit');
            // Route::post('updateManager','AdminController@adminVerifyWithdraw')->name('admin.manager.verify');                      
        });

        Route::group(['prefix' => "author"], function () {
            Route::post("store", "AdminController@storeDataAuthor")->name('admin.author.store'); 
            Route::get("{id}/detil", "AdminController@detil_author")->name('admin.author.detil');
            Route::get("{id}/delete", "AdminController@delete_author")->name('admin.author.delete');   
            Route::get('editAuthor','AdminController@editAuthor')->name('admin.author.edit');
            Route::post('updateAuthor','AdminController@update_author')->name('admin.author.update');                    
        });
        
        Route::group(['prefix' => "organizer"], function () {
            Route::get("{id}/detil", "AdminController@detil_organizer")->name('admin.organizer.detil');
            Route::get("{id}/delete", "AdminController@delete_organizer")->name('admin.organizer.delete');            
        });
        
        Route::group(['prefix' => "category"], function () {
            Route::post('store', "CategoryController@store")->name('admin.category.store');
            Route::post('update', "CategoryController@update")->name('admin.category.update');
            Route::get('delete/{id?}', "CategoryController@delete")->name('admin.category.delete');
        });
    
        Route::group(['prefix' => "page"], function () {
            Route::post('image_upload', 'PageController@uploadImage')->name('uploadImage');
            Route::get('create', "PageController@create")->name('admin.page.create');
            Route::post('store', "PageController@store")->name('admin.page.store');
            Route::get('edit/{id?}', "PageController@edit")->name('admin.page.edit');
            Route::post('update', "PageController@update")->name('admin.page.update');
            Route::post('destroy/{id?}', "PageController@destroy")->name('admin.page.destroy');
            // Update per tanggal 21 Juni => menambahkan menu banner depan
            Route::get('create/banner',"AdminController@frontBanners")->name('admin.home.banner');
            Route::get('banner/{id}/{type}',"HomeBanner@priority")->name('admin.home.banner.priority');
            Route::get('rm/banner/{id}',"HomeBanner@delete")->name('admin.home.banner.delete');
            Route::post('banner/store',"HomeBanner@store")->name('admin.home.banner.store');
        });
        
        Route::group(['prefix' => "faq"], function () {
            Route::get('index', "FaqController@index")->name('admin.faq.index');
            Route::get('create', "FaqController@create")->name('admin.faq.create'); 
            Route::post('store', "FaqController@store")->name('admin.faq.store');  
            Route::get('edit/{id?}', "FaqController@edit")->name('admin.faq.edit');  
            Route::post('update', "FaqController@update")->name('admin.faq.update'); 
            Route::post('destroy/{id?}', "FaqController@destroy")->name('admin.faq.destroy'); 

        });
           
    });
});

//Middleware Manager
// Route::group(['middleware' => ['Manager']], function() {
//     Route::group(['prefix' => "admin"], function () {
//         Route::get('dataevent', 'AdminController@getDataEvent')->name('data.event');
//         Route::get("event", "AdminController@event")->name('admin.event');
//         Route::group(['prefix' => "event"], function() {
//             Route::get('edit', "AdminController@eventEdit")->name('admin.event.dataedit');
//             Route::get('{idEvent}/delete', "AdminController@deleteEvent")->name('admin.event.delete');
//             Route::get('{idEvent}/detail', "AdminController@eventDetail")->name('admin.event.detail');
//             Route::post('update', "AdminController@updateEvent")->name('admin.event.update');
//         });
//     });
// });


//Middleware Finance
Route::group(['middleware' => ['Finance']], function (){
    Route::group(['prefix' => "admin"], function () {

    });
});


//Middleware Author
Route::group(['middleware' => ['Author']], function (){
    Route::group(['prefix' => "admin"], function () {
        
    });
});


//Middleware User
Route::group(['middleware' => ['User']], function() {

	//Laravel Gmail
	Route::post('invitation/{eventID}', "InvitationController@sendInvitation")->name('user.event.invitation');

	//Basic user
	Route::get('events', "UserController@events")->name('user.events');
    Route::get('myTickets', "UserController@myTickets")->name('user.myTickets');
    Route::get('my-ticketsNew', "UserController@myTicketsNew")->name('user.myTickets.new');
    Route::get('my-tickets/{purchaseID}', "UserController@detailTicket")->name('user.myTickets.detail');
    Route::group(['prefix' => 'userSelfCheckin'], function ()
    {
        Route::get('/', 'UserController@selfCheckin')->name('user.selfCheckin');
        Route::get('/{uniqueID}/checkin', 'CheckinController@scanCodeByUser')->name('user.selfCheckin.get');
    });
    Route::get('shareTickets', "UserController@ticketShare")->name('user.shareTickets');
    Route::get('shareTickets2', "CheckoutController@ticketShare")->name('user.shareTickets2');
    Route::post('checkout', "CheckoutController@checkoutPay")->name('user.checkout');
    Route::post('saveShare', "CheckoutController@saveShare")->name('user.saveShare');
    Route::post('changeShare', "CheckoutController@changeShare")->name('user.changeShare');
    Route::get('member-upgrade', "PackagePricingController@upgradePage")->name('user.upgradePkg');
    Route::post('member-upgrade/change', "PackagePricingController@updateUserPkg")->name('user.upgradePkg.update');

    Route::get('myTickets/{orderID}/cancel-pay', "PaymentController@cancelPayment")->name('user.cancelPay');

    Route::group(['prefix' => "detailTicket"], function(){
        Route::get('{id}', "UserController@detailTicket")->name('user.detailTicket');
        Route::get('{id}/print_ticket/{select}', "UserController@printTicket")->name('user.printTicket');
    });

    Route::get('connections', "UserController@connections")->name('user.connections');
    Route::get('invitations', "UserController@invitations")->name('user.invitations');
    Route::get('invitation/ignore/{invitationID}', "UserController@invitationIgnore")->name('user.invitationsIgnore');
    Route::get('invitation/accept/{invitationID}', "UserController@invitationAccept")->name('user.invitationsAccept');
    Route::get('invitation/delete/{id}', "UserController@deleteInvitation")->name('user.invitationsDelete');
    Route::get('profile', "UserController@profile")->name('user.profile');
    Route::get('profilePage', "UserController@profilePage")->name('user.profile');
    Route::post('{userId}/profilePage', "UserController@updateProfile")->name('user.updateProfile');
    Route::post('{userId}/update-password', "UserController@updateProfilePassword")->name('user.updateProfilePassword');
    
    //Message
    Route::group(['prefix' => 'messages'], function(){
        Route::get('/', "UserController@messages")->name('user.messages');
        Route::get('messages/latest/{id}', "MessageController@lastMessage")->name('message.latest');
        Route::get('messages/all/{id}', "MessageController@AllMessage")->name('message.all');
        Route::get('messages/allGroup/{id}', "MessageController@AllMessageGroup")->name('message.allGroup');
        Route::get('messages/allGroup2/{id}', "MessageController@AllMessageGroup2")->name('message.allGroup');
        Route::put('messages/send/chat', "MessageController@SendMessage")->name('message.send');
        Route::put('messages/send/chatGroup', "MessageController@sendMessageGroup")->name('message.sendGroup');
        Route::post('messages/search/user/', "MessageController@searchUser")->name('message.search.user');
        Route::post('messages/tambah/user/', "ConnectionController@addConnection")->name('message.tambah.user');
        
    });

    Route::get('joinStream/{purchaseID}', "SessionController@joinStream")->name('user.joinStream');
    Route::get('joinStream/{purchaseID}/quiz',"QuizController@showQuiz")->name('user.quizShow');
    Route::get('joinStream/{purchaseID}/quiz/{quizID}/questions',"QuizController@showQuizQuestion")->name('user.quizQuestions');
    Route::get('myOrganization', "UserController@myOrganization")->name('user.myorganization');

    Route::group(['prefix' => "organization"], function () {
        // Route::get('/', "UserController@organization")->name('user.organization');
        Route::get('create', 'OrganizationController@create')->name('organization.create')->middleware('User');
        Route::post('store', 'OrganizationController@store')->name('organization.store')->middleware('User');
        Route::post('delete/{organizationID}', 'OrganizationController@delete')->name('organization.delete')->middleware('User');
    });

    Route::group(['prefix' => "exhibitions"], function ()
    {
        Route::get('/', 'UserController@myExhibitions')->name('myExhibitions');
        Route::get('edit/{eventID}/{exhibitorID}', "ExhibitorController@edit2")->name('myExhibitionEdit');
        // Route::get('{eventID}/showProduct', "ScrappingController@showProducts")->('productsShow');
        Route::post('update/{organizationID}/{eventID}/{exhibitorID}', "ExhibitorController@update")->name('myExhibitionUpdate');
        Route::post('storeHandbook/{organizationID}/{eventID}/{exhibitorID}', "HandbookController@store")->name('myHandbookStore');
        Route::post('deleteHandbook/{organizationID}/{eventID}/{exhibitorID}', "HandbookController@delete")->name('myHandbookDelete');
        Route::post('{eventID}/saveurl', "ScrappingController@getProducts")->name('productSave');
        Route::post('{eventID}/saveurl', "ScrappingController@saveProducts")->name('productSave2');
        Route::get('product/{eventID}/{productID}/delete', "ScrappingController@delete")->name('productDelete');
    });

    Route::group(['prefix' => "speakerEvents"], function (){
        Route::get('/', 'UserController@speakerEvents')->name('myIsSpeaker');
        Route::get('{eventID}/allSession', 'UserController@speakerEventSessions')->name('mySessionsSpeaker');
    });

    Route::get('join-stream-special/{sessionID}/{rundownID}', 'SessionController@joinStreamSpc')->name('streamSpecial');
    //Link Zoom
    // Route::get('{id}/event/{eventID}/session/{sessionID}/url', "SessionController@url")->name('organization.event.session.url');


	//Middleware Organisasi
	Route::group(['middleware' => ['Organisasi']], function() {

		Route::group(['prefix' => 'organization'], function(){
			Route::post('{id}/update', 'OrganizationController@update')->name('organization.update');
            Route::get('{id}/profil', "OrganizationController@profilePage")->name('organization.profilOrganisasi');
			Route::post('{id}/invite-teams', 'OrganizationController@InviteTeams')->name('organization.invite-teams');
            Route::post('{id}/team/{teamID}/delete', 'OrganizationTeamController@delete')->name('organization.team.delete');
            // Withdrawals routes
            Route::post('{id}/store-bank-account', "WithdrawController@storeBankAccount")->name('organization.bankaccount.store');
            Route::post('{id}/del-bank-account', "WithdrawController@delBankAccount")->name('organization.bankaccount.delete');
            Route::post('{id}/store-withdraw', "WithdrawController@storeWithdraw")->name('organization.withdraw.store');
            Route::post('{id}/del-withdraw', "WithdrawController@delWithdraw")->name('organization.withdraw.delete');
            Route::get('{id}/{withdrawID}/detail-withdraw','WithdrawController@seeWithdraw')->name('organization.withdraw.detail');
		});

		Route::group(['prefix' => '{id}/event'], function() {
	    	Route::get('create', "EventController@create")->name('organization.event.create');
	        Route::post('store', "EventController@store")->name('organization.event.store');
            Route::get('create2', "EventController@createSecond")->name('organization.event.create2');
	    });


		//Middleware MiddlewareEvent
		Route::group(['middleware' => ['MiddlewareEvent']], function() {

			Route::group(['prefix' => '{id}/event'], function() {

                //publish - unpublish Event
                Route::post('{eventID}/publish', 'EventController@eventPublish')->name('organization.event.publish');
	            Route::post('{eventID}/unPublish', 'EventController@eventUnPublish')->name('organization.event.un_publish');

	            Route::get('{eventID}/edit', "EventController@edit")->name('organization.event.edit');
	            Route::post('{eventID}/update', "EventController@update")->name('organization.event.update');
	            Route::get('{eventID}/delete', "EventController@delete")->name('organization.event.delete');
	            Route::get('{eventID}/event-overview', "EventController@eventoverview")->name('organization.event.eventoverview');
                Route::post('{eventID}/event-overview/create-link', "CustomLinkController@createUpdate")->name('organization.event.customlink');
                Route::post('{eventID}/event-overview/update-link', "CustomLinkController@update")->name('organization.event.customlink.update');
                Route::get('{eventID}/event-code-user-scan', 'EventController@getQREvent')->name('organization.event.qr-event');
                Route::get('{eventID}/event-code-download','EventController@getQREventDownload')->name('organization.event.qr-event-download');
	            Route::group(['prefix' => "{eventID}/session"], function() {
	            	Route::get('/', "EventController@sessions")->name('organization.event.sessions');
                    Route::get('config', "EventController@configSession")->name('organization.event.session.config');
	                Route::post('store', "SessionController@store")->name('organization.event.session.store');
	                Route::post('update', "SessionController@update")->name('organization.event.session.update');
	                Route::get('{sessionID}/delete', "SessionController@delete")->name('organization.event.session.delete');
                    // Try Zoom / Stream system    
                    Route::get('{sessionID}/url', "SessionController@url")->name('organization.event.session.url');
	            });

                Route::get('{eventID}/{sessionID}/studio-stream', "StreamStudioController@studioStream")->name('organization.event.studio-stream');

                Route::group(['prefix' => "{eventID}/rundown"], function() {
                    Route::get('/', "EventController@rundowns")->name('organization.event.rundowns');
                    Route::post('store', "RundownController@store")->name('organization.event.rundown.store');
                    Route::get('delete/{idDel}', "RundownController@delete")->name('organization.event.rundown.delete');
                });
	            
	            // Route::get('{eventID}/booths', "EventController@booths")->name('organization.event.booths');
	            // Route::get('{eventID}/booths/categories', "EventController@boothCategory")->name('organization.event.booth.category');
	            Route::group(['prefix' => "{eventID}/booth-categories"], function () {
	                Route::post('store', "ExhibitorController@storeCategory")->name('organization.event.booth.category.store');
	                Route::post('update', "ExhibitorController@updateCategory")->name('organization.event.booth.category.update');
	                Route::post('delete', "ExhibitorController@deleteCategory")->name('organization.event.booth.category.delete');
	                Route::get('/', "EventController@boothCategory")->name('organization.event.booth.category');
	            });
	            
	            Route::group(['prefix' => "{eventID}/ticket"], function() {
	            	Route::get('/', "EventController@tickets")->name('organization.event.tickets');
	                Route::post('store', "TicketController@store")->name('organization.event.ticket.store');
	                Route::post('update', "TicketController@update")->name('organization.event.ticket.update');
	                Route::get('{ticketID}/delete', "TicketController@delete")->name('organization.event.ticket.delete');
	            });
	            
                Route::group(['prefix' =>  "{eventID}/handbooks"], function(){
                    Route::get('/', "EventController@handbooks")->name('organization.event.handbooks');
                    Route::post('/store', "HandbookController@store")->name('organization.event.handbooks.store');
                    Route::post('/delete', "HandbookController@delete")->name('organization.event.handbooks.delete');
                });
                Route::group(['prefix' =>  "{eventID}/certificate"], function(){
                    Route::get('/', "EventController@certificate")->name('organization.event.certificate');
                    Route::post('post', "CertificateController@store")->name('organization.event.certificate.store');
                });
                Route::group(['prefix' =>  "{eventID}/site"], function(){
                    Route::get('/', "EventController@site")->name('organization.event.site');
                    Route::post('create', "SiteController@create")->name('organization.event.site.create');
                    Route::post('update', "SiteController@update")->name('organization.event.site.update');
                    Route::get('tes', "SiteController@createConfigFile");
                });

                Route::group(['prefix' =>  "{eventID}/ticketSelling"], function(){
                    Route::get('/', "EventController@ticketSelling")->name('organization.event.ticketSelling');
                    Route::get('/qr-checkin', "CheckinController@qrCodeCheckin")->name('organization.event.ticketSelling.checkinqr');
                    Route::get('/qr-scanning/{orderID}/{userID}', "CheckinController@scanCode")->name('organization.event.ticketSelling.qrscan');
                    Route::post('/checkin', "CheckinController@index")->name('organization.event.ticketSelling.checkin');
                    Route::get('/download', "CheckinController@exportCheckin")->name('organization.event.ticketSelling.download');
                });

                Route::group(['prefix' =>  "{eventID}/vipLounge"], function(){
                    Route::get('/', "EventController@vipLounge")->name('organization.event.vipLounge');
                    Route::post('/save', 'VipLoungeController@save')->name('organization.event.vipLounge.save');
                });

                Route::group(['prefix' =>  "{eventID}/receptionist"], function(){
                    Route::get('/', "EventController@receptionist")->name('organization.event.receptionist');
                    Route::post('/store', "ReceptionistEventController@store")->name('organization.event.receptionist.store');
                    Route::post('/delete', "ReceptionistEventController@delete")->name('organization.event.receptionist.delete');
                });

	            Route::get('{eventID}/handouts', "EventController@handouts")->name('organization.event.handouts');
	            
	            Route::group(['prefix' => "{eventID}/speaker"], function() {
	            	Route::get('/', "EventController@speakers")->name('organization.event.speakers');
	            	Route::get('create', "SpeakerController@create")->name('organization.event.speaker.create');
	                Route::post('store', "SpeakerController@store")->name('organization.event.speaker.store');
	                Route::get('{speakerID}/edit', "SpeakerController@edit")->name('organization.event.speaker.edit');
	                Route::post('{speakerID}/update', "SpeakerController@update")->name('organization.event.speaker.update');
	                Route::get('{speakerID}/delete', "SpeakerController@delete")->name('organization.event.speaker.delete');
	            });

                Route::get('{eventID}/media-partners', 'EventController@mediaPartners')->name('organization.event.media');

                Route::group(['prefix' => "{eventID}/sponsor"], function () {
                	Route::get('/', "EventController@sponsors")->name('organization.event.sponsors');
                    Route::get('{sponsorID}/increase', "SponsorController@increase")->name('organization.event.sponsor.increase');
                    Route::get('{sponsorID}/decrease', "SponsorController@decrease")->name('organization.event.sponsor.decrease');
                    Route::post('store', "SponsorController@store")->name('organization.event.sponsor.store');
                    Route::post('update', "SponsorController@update")->name('organization.event.sponsor.update');
                    Route::delete('{sponsorID}/delete', "SponsorController@delete")->name('organization.event.sponsor.delete');
                });

                //exhibitor page
                Route::group(['prefix' => '{eventID}/exhibitor'], function () {
                	Route::get('/', "EventController@exhibitors")->name('organization.event.exhibitors');
                	Route::get('create-exhibitor', "ExhibitorController@createExhibitornPage")->name('organization.event.exhibitor.create');
                    Route::post('store', "ExhibitorController@store")->name('organization.event.exhibitor.store');
                    Route::post('update/{exhibitorID}', "ExhibitorController@update")->name('organization.event.exhibitor.update');
                    Route::get('{exhibitorID}/edit', "ExhibitorController@edit")->name('organization.event.exhibitor.edit');
                    Route::delete('{exhibitorID}/delete', "ExhibitorController@delete")->name('organization.event.exhibitor.delete');

                });

                Route::get('{eventID}/lounge', "EventController@lounge")->name('organization.event.lounge');
                Route::group(['prefix' => '{eventID}/lounged'], function (){
                    Route::post('store', "LoungeController@receiveRequest")->name('organization.event.lounge.store');
                });
                Route::get('{eventID}/vip-lounge', "EventController@viplounge")->name('organization.event.viplounge');

                Route::group(['prefix' => "{eventID}/quiz"], function() {
                	Route::get('/', "EventController@quiz")->name('organization.event.quiz');
		            Route::post('store', "QuizController@store")->name('organization.event.quiz.store');
		            Route::post('/{quizID}/update', "QuizController@update")->name('organization.event.quiz.update');
		            Route::get('{quizID}/delete', "QuizController@delete")->name('organization.event.quiz.delete');
		            
		            Route::get('{quizID}/questions', "QuizController@questions")->name('organization.event.quiz.questions');
		            Route::group(['prefix' => "{quizID}/question"], function() {
		                Route::post('store', "QuestionController@store")->name('organization.event.quiz.question.store');
		                Route::post('update', "QuestionController@update")->name('organization.event.quiz.question.update');
		                Route::get('{questionID}/delete', "QuestionController@delete")->name('organization.event.quiz.question.delete');
		            });
		        });

	        });



		//Middleware MiddlewareEvent ending
		});

	
	//Middleware Organisai ending
	});

//Middleware User ending
});


Route::group(['prefix' => "agent"], function () {
    Route::get('login', "AgentController@loginPage")->name("agent.loginPage");
    Route::post('login', "AgentController@login")->name("agent.login");
    Route::get('logout', "AgentController@logout")->name("agent.logout");

    Route::get('dashboard', "AgentController@dashboard")->name("agent.dashboard");
    Route::get('product', "AgentController@product")->name("agent.product");
    Route::get('handout', "AgentController@handout")->name("agent.handout");

    Route::group(['prefix' => "handout"], function () {
        Route::post('store', "BoothHandoutController@store")->name('agent.handout.store');
        Route::post('delete', "BoothHandoutController@delete")->name('agent.handout.delete');
    });

    Route::group(['prefix' => "product"], function () {
        Route::post('store', "BoothProductController@store")->name('agent.product.store');
        Route::post('update', "BoothProductController@update")->name('agent.product.update');
        Route::post('delete', "BoothProductController@delete")->name('agent.product.delete');
    });
});

Route::get('cok', function () {
    return route('organization.event.ticketSelling.qrscan', [
        51, 2, 'f2e9hf', 2
    ]);
});

Route::get('bagus', function () {
	$output = shell_exec(public_path("tes.sh tokobagus.id"));
	echo "<pre>".$output."</pre>";
});
Route::get('{slug}', "PageController@read")->name('page.read');

?>
