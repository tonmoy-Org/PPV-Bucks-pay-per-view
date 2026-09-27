<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Advertiser\Auth')->name('advertiser.')->group(function () {

    Route::controller('LoginController')->group(function(){
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login');
        Route::get('logout', 'logout')->name('logout');
    });

    Route::controller('RegisterController')->group(function(){
        Route::get('register', 'showRegistrationForm')->name('register');
        Route::post('register', 'register')->middleware('registration.status');
        Route::post('check-mail', 'checkUser')->name('checkUser');
    });

    Route::controller('ForgotPasswordController')->group(function(){
        Route::get('password/reset', 'showLinkRequestForm')->name('password.request');
        Route::post('password/email', 'sendResetCodeEmail')->name('password.email');
        Route::get('password/code-verify', 'codeVerify')->name('password.code.verify');
        Route::post('password/verify-code', 'verifyCode')->name('password.verify.code');
    });
    Route::controller('ResetPasswordController')->group(function(){
        Route::post('password/reset', 'reset')->name('password.update');
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
    });

    Route::controller('SocialiteController')->prefix('social')->group(function () {
        Route::get('login/{provider}', 'socialLogin')->name('social.login');
        Route::get('login/callback/{provider}', 'callback')->name('social.login.callback');
    });

});


Route::middleware('advertiser')->name('advertiser.')->group(function () {
    //authorization
    Route::namespace('Advertiser')->controller('AuthorizationController')->group(function () {
        Route::get('authorization', 'authorizeForm')->name('authorization');
        Route::get('resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'emailVerification')->name('verify.email');
        Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');
        Route::post('verify-g2fa', 'g2faVerification')->name('go2fa.verify');
    });

    Route::middleware(['advertiser.check'])->group(function () {

        Route::get('advertiser-data', 'Advertiser\AdvertiserController@advertiserData')->name('data');
        Route::post('advertiser-data-submit', 'Advertiser\AdvertiserController@advertiserDataSubmit')->name('data.submit');

        Route::middleware('advertiser.registration.complete')->namespace('advertiser')->group(function () {

            Route::controller('AdvertiserController')->group(function () {
                Route::get('dashboard', 'home')->name('home');
                Route::get('plan', 'plan')->name('plan');

                //2FA
                Route::get('twofactor', 'show2faForm')->name('twofactor');
                Route::post('twofactor/enable', 'create2fa')->name('twofactor.enable');
                Route::post('twofactor/disable', 'disable2fa')->name('twofactor.disable');


                Route::any('deposit/history', 'depositHistory')->name('deposit.history');
                Route::get('transactions','transactions')->name('transactions');

                //get package
                Route::get('get-package','packages')->name('get.packages');

                Route::get('attachment-download/{fil_hash}', 'attachmentDownload')->name('attachment.download');
            });

            //Profile setting
            Route::controller('ProfileController')->group(function(){
                Route::get('profile/setting', 'profile')->name('profile.setting');
                Route::post('profile/setting', 'submitProfile');
                Route::get('change-password', 'changePassword')->name('change.password');
                Route::post('change-password', 'submitPassword');

                Route::post('submit-skill', 'submitSkill')->name('skill');

                Route::post('add-language/{id?}', 'addLanguage')->name('language.add');
                Route::post('remove-language/{language}', 'removeLanguage')->name('language.remove');

                Route::post('add/socialLink/{id?}', 'addSocialLink')->name('add.socialLink');
                Route::post('remove-socialLink/{id}', 'removeSocialLink')->name('remove.socialLink');

                Route::post('add-education/{id?}', 'addEducation')->name('add.education');
                Route::post('remove-education/{id}', 'removeEducation')->name('remove.education');

                Route::post('add-qualification/{id?}', 'addQualification')->name('add.qualification');
                Route::post('remove-qualification/{id}', 'removeQualification')->name('remove.qualification');

            });


            // Ptc Manage
            Route::controller('PtcManageController')->group(function(){
                Route::get('ptc', 'index')->name('ptc.index');
                Route::get('ptc/create', 'create')->name('ptc.create');
                Route::post('ptc/store', 'store')->name('ptc.store');
                Route::get('ptc/edit/{id}', 'edit')->name('ptc.edit');
                Route::post('ptc/update/{id}', 'update')->name('ptc.update');
                Route::get('active/ptc', 'activePtc')->name('ptc.active');
                Route::get('pending/ptc', 'pendingPtc')->name('ptc.pending');
            });


              //affiliate
              Route::controller('AffiliateController')->group(function(){
                Route::get('reffered', 'reffered')->name('reffered');
                Route::get('reffered-commissions', 'refferedCommission')->name('reffered.commission');
                Route::post('reffer-link', 'refferlinkSend')->name('refferlink.send');

            });


            Route::controller('TicketController')->prefix('ticket')->group(function () {
                Route::get('all', 'supportTicket')->name('ticket');
                Route::get('new', 'openSupportTicket')->name('ticket.open');
                Route::post('create', 'storeSupportTicket')->name('ticket.store');
                Route::get('view/{ticket}', 'viewTicket')->name('ticket.view');
                Route::post('reply/{ticket}', 'replyTicket')->name('ticket.reply');
                Route::post('close/{ticket}', 'closeTicket')->name('ticket.close');
                Route::get('download/{ticket}', 'ticketDownload')->name('ticket.download');
            });

            // Withdraw
            Route::controller('WithdrawController')->prefix('withdraw')->name('withdraw')->group(function () {
                Route::get('/', 'withdrawMoney');
                Route::post('/', 'withdrawStore')->name('.money');
                Route::get('preview', 'withdrawPreview')->name('.preview');
                Route::post('preview', 'withdrawSubmit')->name('.submit');
                Route::get('history', 'withdrawLog')->name('.history');
            });

        });


        // Payment
        Route::middleware('advertiser.registration.complete')->controller('Gateway\PaymentController')->group(function(){
            Route::get('payment/{id}', 'payment')->name('payment');
            Route::any('/deposit', 'deposit')->name('deposit');
            Route::post('deposit/insert', 'depositInsert')->name('deposit.insert');
            Route::get('deposit/confirm', 'depositConfirm')->name('deposit.confirm');
            Route::get('deposit/manual', 'manualDepositConfirm')->name('deposit.manual.confirm');
            Route::post('deposit/manual', 'manualDepositUpdate')->name('deposit.manual.update');
        });

    });
});
