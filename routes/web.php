<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// use Illuminate\Routing\Route;

Route::namespace('Auth')->middleware('guest:web')->group(function() {

    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::get('/', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login')->name('login');

    Route::get('reset-password/{token}/{email}', 'ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('reset-password/change', 'ResetPasswordController@reset')->name('password.change');

    Route::get('forgot-password', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('forgot-password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('email/verify/{id}', 'VerificationController@verify')->name('verification.verify');

});

// Route::middleware('auth:web')->group(function() {
Route::middleware(['auth:web'])->group(function() {

    Route::namespace('Auth')->group(function() {
        Route::get('logout', 'LoginController@logout')->name('logout');

        Route::get('email/reset', 'VerificationController@resend')->name('verification.resend');
        Route::get('email/verify', 'VerificationController@show')->name('verification.notice');
        Route::get('email/verify/{id}', 'VerificationController@verify')->name('verification.verify');
    });


    /////////////////////////
    // Dashboard Controller//
    ////////////////////////

    Route::middleware('verified')->group(function() {

        Route::namespace('Dashboards')->group(function() {
            Route::get('dashboard', 'DashboardController@index')->name('dashboards.index');
            Route::get('/', 'DashboardController@index')->name('dashboards.index');
            Route::get('', 'DashboardController@index')->name('dashboards.index');
            Route::get('dashboard/small-box/{id}', 'DashboardController@fetchSmallBoxData')->name('dashboards.small-index');
            Route::get('dashboard/profit-and-loss/{id}', 'DashboardController@fetchProfitAndLoss')->name('dashboards.small-index');
        });

        Route::namespace('Globals')->group(function() {
            Route::get('fetch-client', 'GlobalController@fetchClients')->name('global.fetch-clients');
        });

            // Route::get('/', function () {
            //     return view('dashboards.index');
            // })->name('dashboards.index');

        // //////////////////
        // // ActivityLogs //
        // //////////////////
        // Route::namespace('ActivityLogs')->group(function() {

        //     Route::get('activity-logs', 'ActivityLogController@index')->name('activity-logs.index');
        //     Route::post('activity-logs/fetch', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch');

        
        //     Route::post('activity-logs/fetch?id={id?}&payment-methods=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.payment-methods');
        //     Route::post('activity-logs/fetch?id={id?}&products=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.products');
        //     Route::post('activity-logs/fetch?id={id?}&vendors=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.vendors');
        //     Route::post('activity-logs/fetch?id={id?}&terms=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.terms');
        //     Route::post('activity-logs/fetch?id={id?}&purchase-orders=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.purchase-orders');
        //     Route::post('activity-logs/fetch?id={id?}&purchase-order-lines=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.purchase-order-lines');
        //     Route::post('activity-logs/fetch?id={id?}&customers=1', 'ActivityLogFetchController@fetch')->name('activity-logs.fetch.customers');
            
        // });

        //////////////////////////////
        // PaymentMethods Controller //
        //////////////////////////////
        Route::namespace('PaymentMethods')->group(function() {

            Route::get('payment-methods', 'PaymentMethodController@index')->name('payment-methods.index');
            Route::get('payment-methods/create', 'PaymentMethodController@create')->name('payment-methods.create');
            Route::post('payment-methods/store', 'PaymentMethodController@store')->name('payment-methods.store');
            Route::get('payment-methods/show/{id}', 'PaymentMethodController@show')->name('payment-methods.show');
            Route::post('payment-methods/update/{id}', 'PaymentMethodController@update')->name('payment-methods.update');
            Route::post('payment-methods/{id}/archive', 'PaymentMethodController@archive')->name('payment-methods.archive');
            Route::post('payment-methods/{id}/restore', 'PaymentMethodController@restore')->name('payment-methods.restore');

            Route::post('payment-methods/fetch', 'PaymentMethodFetchController@fetch')->name('payment-methods.fetch');
            Route::post('payment-methods/fetch?archived=1', 'PaymentMethodFetchController@fetch')->name('payment-methods.fetch-archive');
            Route::post('payment-methods/fetch-item/{id?}', 'PaymentMethodFetchController@fetchView')->name('payment-methods.fetch-item');
            Route::post('payment-methods/fetch-pagination/{id}', 'PaymentMethodFetchController@fetchPagePagination')->name('payment-methods.fetch-pagination');

        });

        ///////////////////////////////////
        // BankReconciliation Controller //
        ///////////////////////////////////
        Route::namespace('BankReconciliations')->group(function() {
            Route::get('bank-reconciliations/form', 'BankReconciliationController@showForm')->name('bank-reconciliations.form');
            Route::get('bank-reconciliations', 'BankReconciliationController@index')->name('bank-reconciliations.index');
            Route::post('bank-reconciliations/post/{id}', 'BankReconciliationController@post')->name('bank-reconciliations.post');
            Route::post('bank-reconciliations/approved/{id}', 'BankReconciliationController@approved')->name('bank-reconciliations.approved');

            Route::post('bank-reconciliations/generate-cash-register', 'BankReconciliationController@generateCashRegisters')->name('bank-reconciliations.generate-cash-register');
            Route::post('bank-reconciliations/generate-match', 'BankReconciliationController@generateMatch')->name('bank-reconciliations.generate-match');
            Route::post('bank-reconciliations/generate-adjustments', 'BankReconciliationController@generateAdjustments')->name('bank-reconciliations.generate-adjustments');
            
            Route::post('bank-reconciliations/generate-reconciliation', 'BankReconciliationController@generateReconciliation')->name('bank-reconciliations.generate-reconciliation');

            Route::get('bank-reconciliations/create', 'BankReconciliationController@create')->name('bank-reconciliations.create');
            Route::post('bank-reconciliations/store', 'BankReconciliationController@store')->name('bank-reconciliations.store');
            Route::get('bank-reconciliations/show/{id}', 'BankReconciliationController@show')->name('bank-reconciliations.show');
            Route::post('bank-reconciliations/update/{id}', 'BankReconciliationController@update')->name('bank-reconciliations.update');
            Route::post('bank-reconciliations/{id}/archive', 'BankReconciliationController@archive')->name('bank-reconciliations.archive');
            Route::post('bank-reconciliations/{id}/restore', 'BankReconciliationController@restore')->name('bank-reconciliations.restore');

            Route::post('bank-reconciliations/fetch', 'BankReconciliationFetchController@fetch')->name('bank-reconciliations.fetch');
            Route::post('bank-reconciliations/fetch?archived=1', 'BankReconciliationFetchController@fetch')->name('bank-reconciliations.fetch-archive');
            Route::post('bank-reconciliations/fetch-item/{id?}', 'BankReconciliationFetchController@fetchView')->name('bank-reconciliations.fetch-item');
            Route::post('bank-reconciliations/fetch-pagination/{id}', 'BankReconciliationFetchController@fetchPagePagination')->name('bank-reconciliations.fetch-pagination');
            
            Route::post('bank-reconciliations/fetch-details', 'BankReconciliationFetchController@fetchDetails')->name('bank-reconciliations.fetch-details');
        });

        Route::namespace('BankReconciliationLines')->group(function() {
            Route::post('bank-reconciliation-lines/store', 'BankReconciliationLineController@store')->name('bank-reconciliation-lines.store');
            Route::post('bank-reconciliation-lines/update/{id}', 'BankReconciliationLineController@update')->name('bank-reconciliation-lines.update');
            Route::post('bank-reconciliation-lines/{id}/archive', 'BankReconciliationLineController@archive')->name('bank-reconciliation-lines.archive');
            Route::post('bank-reconciliation-lines/{id}/restore', 'BankReconciliationLineController@restore')->name('bank-reconciliation-lines.restore');

            Route::post('bank-reconciliation-lines/fetch', 'BankReconciliationLineFetchController@fetch')->name('bank-reconciliation-lines.fetch');
            Route::post('bank-reconciliation-lines/fetch?archived=1', 'BankReconciliationLineFetchController@fetch')->name('bank-reconciliation-lines.fetch-archive');
            Route::post('bank-reconciliation-lines/fetch-item/{id?}', 'BankReconciliationLineFetchController@fetchView')->name('bank-reconciliation-lines.fetch-item');
            Route::post('bank-reconciliation-lines/fetch-pagination/{id}', 'BankReconciliationLineFetchController@fetchPagePagination')->name('bank-reconciliation-lines.fetch-pagination');
        });

        Route::namespace('BankReconciliationJournals')->group(function() {
            Route::get('bank-reconciliation-journals', 'BankReconciliationJournalController@index')->name('bank-reconciliation-journals.index');
            Route::get('bank-reconciliation-journals/create', 'BankReconciliationJournalController@create')->name('bank-reconciliation-journals.create');
            Route::get('bank-reconciliation-journals/show/{id}', 'BankReconciliationJournalController@show')->name('bank-reconciliation-journals.show');

            Route::post('bank-reconciliation-journals/store', 'BankReconciliationJournalController@store')->name('bank-reconciliation-journals.store');
            Route::post('bank-reconciliation-journals/update/{id}', 'BankReconciliationJournalController@update')->name('bank-reconciliation-journals.update');
            Route::post('bank-reconciliation-journals/{id}/archive', 'BankReconciliationJournalController@archive')->name('bank-reconciliation-journals.archive');
            Route::post('bank-reconciliation-journals/{id}/restore', 'BankReconciliationJournalController@restore')->name('bank-reconciliation-journals.restore');

            Route::post('bank-reconciliation-journals/fetch', 'BankReconciliationJournalFetchController@fetch')->name('bank-reconciliation-journals.fetch');
            Route::post('bank-reconciliation-journals/fetch?archived=1', 'BankReconciliationJournalFetchController@fetch')->name('bank-reconciliation-journals.fetch-archive');
            Route::post('bank-reconciliation-journals/fetch-item/{id?}', 'BankReconciliationJournalFetchController@fetchView')->name('bank-reconciliation-journals.fetch-item');
            Route::post('bank-reconciliation-journals/fetch-pagination/{id}', 'BankReconciliationJournalFetchController@fetchPagePagination')->name('bank-reconciliation-journals.fetch-pagination');

            Route::post('bank-reconciliation-journal-vouchers/store', 'BankReconciliationJournalVoucherController@store')->name('bank-reconciliation-journal-vouchers.store');
            Route::post('bank-reconciliation-journal-vouchers/update/{id}', 'BankReconciliationJournalVoucherController@update')->name('bank-reconciliation-journal-vouchers.update');
            Route::post('bank-reconciliation-journal-vouchers/{id}/archive', 'BankReconciliationJournalVoucherController@archive')->name('bank-reconciliation-journal-vouchers.archive');
            Route::post('bank-reconciliation-journal-vouchers/{id}/restore', 'BankReconciliationJournalVoucherController@restore')->name('bank-reconciliation-journal-vouchers.restore');

            Route::post('bank-reconciliation-journal-vouchers/fetch', 'BankReconciliationJournalVoucherFetchController@fetch')->name('bank-reconciliation-journal-vouchers.fetch');
            Route::post('bank-reconciliation-journal-vouchers/fetch?archived=1', 'BankReconciliationJournalVoucherFetchController@fetch')->name('bank-reconciliation-journal-vouchers.fetch-archive');
            Route::post('bank-reconciliation-journal-vouchers/fetch-item/{id?}', 'BankReconciliationJournalVoucherFetchController@fetchView')->name('bank-reconciliation-journal-vouchers.fetch-item');
            Route::post('bank-reconciliation-journal-vouchers/fetch-pagination/{id}', 'BankReconciliationJournalVoucherFetchController@fetchPagePagination')->name('bank-reconciliation-journal-vouchers.fetch-pagination');
        });

        Route::namespace('TaxTables')->group(function() {
            Route::get('tax-postings', 'TaxTableController@index')->name('tax-tables.index');
            Route::get('tax-postings/create', 'TaxTableController@create')->name('tax-tables.create');
            Route::get('tax-postings/show/{id}', 'TaxTableController@show')->name('tax-tables.show');

            Route::post('tax-postings/store', 'TaxTableController@store')->name('tax-tables.store');
            Route::post('tax-postings/update/{id}', 'TaxTableController@update')->name('tax-tables.update');
            Route::post('tax-postings/{id}/archive', 'TaxTableController@archive')->name('tax-tables.archive');
            Route::post('tax-postings/{id}/restore', 'TaxTableController@restore')->name('tax-tables.restore');

            Route::post('tax-postings/fetch', 'TaxTableFetchController@fetch')->name('tax-tables.fetch');
            Route::post('tax-postings/fetch?archived=1', 'TaxTableFetchController@fetch')->name('tax-tables.fetch-archive');
            Route::post('tax-postings/fetch-item/{id?}', 'TaxTableFetchController@fetchView')->name('tax-tables.fetch-item');
            Route::post('tax-postings/fetch-pagination/{id}', 'TaxTableFetchController@fetchPagePagination')->name('tax-tables.fetch-pagination');

            Route::post('tax-postings-lines/store', 'TaxTableLineController@store')->name('tax-table-lines.store');
            Route::post('tax-postings-lines/update/{id}', 'TaxTableLineController@update')->name('tax-table-lines.update');
            Route::post('tax-postings-lines/{id}/archive', 'TaxTableLineController@archive')->name('tax-table-lines.archive');
            Route::post('tax-postings-lines/{id}/restore', 'TaxTableLineController@restore')->name('tax-table-lines.restore');

            Route::post('tax-postings-lines/fetch', 'TaxTableLineFetchController@fetch')->name('tax-table-lines.fetch');
            Route::post('tax-postings-lines/fetch?archived=1', 'TaxTableLineFetchController@fetch')->name('tax-table-lines.fetch-archive');
            Route::post('tax-postings-lines/fetch-item/{id?}', 'TaxTableLineFetchController@fetchView')->name('tax-table-lines.fetch-item');
            Route::post('tax-postings-lines/fetch-pagination/{id}', 'TaxTableLineFetchController@fetchPagePagination')->name('tax-table-lines.fetch-pagination');
        });

        Route::namespace('PaymentCancellationJournals')->group(function() {
            Route::get('payment-cancellation-journals', 'PaymentCancellationJournalController@index')->name('payment-cancellation-journals.index');
            Route::get('payment-cancellation-journals/create', 'PaymentCancellationJournalController@create')->name('payment-cancellation-journals.create');
            Route::get('payment-cancellation-journals/show/{id}', 'PaymentCancellationJournalController@show')->name('payment-cancellation-journals.show');

            Route::post('payment-cancellation-journals/store', 'PaymentCancellationJournalController@store')->name('payment-cancellation-journals.store');
            Route::post('payment-cancellation-journals/update/{id}', 'PaymentCancellationJournalController@update')->name('payment-cancellation-journals.update');
            Route::post('payment-cancellation-journals/{id}/archive', 'PaymentCancellationJournalController@archive')->name('payment-cancellation-journals.archive');
            Route::post('payment-cancellation-journals/{id}/restore', 'PaymentCancellationJournalController@restore')->name('payment-cancellation-journals.restore');

            Route::post('payment-cancellation-journals/fetch', 'PaymentCancellationJournalFetchController@fetch')->name('payment-cancellation-journals.fetch');
            Route::post('payment-cancellation-journals/fetch?archived=1', 'PaymentCancellationJournalFetchController@fetch')->name('payment-cancellation-journals.fetch-archive');
            Route::post('payment-cancellation-journals/fetch-item/{id?}', 'PaymentCancellationJournalFetchController@fetchView')->name('payment-cancellation-journals.fetch-item');
            Route::post('payment-cancellation-journals/fetch-pagination/{id}', 'PaymentCancellationJournalFetchController@fetchPagePagination')->name('payment-cancellation-journals.fetch-pagination');

            Route::post('payment-cancellation-journal-vouchers/store', 'PaymentCancellationJournalVoucherController@store')->name('payment-cancellation-journal-vouchers.store');
            Route::post('payment-cancellation-journal-vouchers/update/{id}', 'PaymentCancellationJournalVoucherController@update')->name('payment-cancellation-journal-vouchers.update');
            Route::post('payment-cancellation-journal-vouchers/{id}/archive', 'PaymentCancellationJournalVoucherController@archive')->name('payment-cancellation-journal-vouchers.archive');
            Route::post('payment-cancellation-journal-vouchers/{id}/restore', 'PaymentCancellationJournalVoucherController@restore')->name('payment-cancellation-journal-vouchers.restore');

            Route::post('payment-cancellation-journal-vouchers/fetch', 'PaymentCancellationJournalVoucherFetchController@fetch')->name('payment-cancellation-journal-vouchers.fetch');
            Route::post('payment-cancellation-journal-vouchers/fetch?archived=1', 'PaymentCancellationJournalVoucherFetchController@fetch')->name('payment-cancellation-journal-vouchers.fetch-archive');
            Route::post('payment-cancellation-journal-vouchers/fetch-item/{id?}', 'PaymentCancellationJournalVoucherFetchController@fetchView')->name('payment-cancellation-journal-vouchers.fetch-item');
            Route::post('payment-cancellation-journal-vouchers/fetch-pagination/{id}', 'PaymentCancellationJournalVoucherFetchController@fetchPagePagination')->name('payment-cancellation-journal-vouchers.fetch-pagination');
        });

        Route::namespace('PaymentSchedules')->group(function() {
            Route::get('payment-schedules', 'PaymentScheduleController@index')->name('payment-schedules.index');
            Route::get('payment-schedules/pn', 'PaymentScheduleController@indexPN')->name('payment-schedules.index-pn');
            Route::get('payment-schedules/create/{customer_invoice_number?}/{bill_exchange_number?}/{type?}', 'PaymentScheduleController@create')->name('payment-schedules.create');
            Route::get('payment-schedules/create-pn/{vendor_invoice_number?}/{promissory_note_number?}/{type?}', 'PaymentScheduleController@createPN')->name('payment-schedules.create-pn');
            Route::get('payment-schedules/show/{id}', 'PaymentScheduleController@show')->name('payment-schedules.show');

            Route::post('payment-schedules/store', 'PaymentScheduleController@store')->name('payment-schedules.store');
            Route::post('payment-schedules/update/{id}', 'PaymentScheduleController@update')->name('payment-schedules.update');
            Route::post('payment-schedules/{id}/archive', 'PaymentScheduleController@archive')->name('payment-schedules.archive');
            Route::post('payment-schedules/{id}/restore', 'PaymentScheduleController@restore')->name('payment-schedules.restore');

            Route::post('payment-schedules/{id}/approve', 'PaymentScheduleController@approve')->name('payment-schedules.approve');
            
            Route::post('payment-schedules/fetch', 'PaymentScheduleFetchController@fetch')->name('payment-schedules.fetch');
            Route::post('payment-schedules/fetch?archived=1', 'PaymentScheduleFetchController@fetch')->name('payment-schedules.fetch-archive');
            Route::post('payment-schedules/fetch-item/{id?}', 'PaymentScheduleFetchController@fetchView')->name('payment-schedules.fetch-item');
            Route::post('payment-schedules/fetch-pagination/{id}', 'PaymentScheduleFetchController@fetchPagePagination')->name('payment-schedules.fetch-pagination');

            Route::post('payment-schedule-lines/store', 'PaymentScheduleLineController@store')->name('payment-schedule-lines.store');
            Route::post('payment-schedule-lines/update/{id}', 'PaymentScheduleLineController@update')->name('payment-schedule-lines.update');
            Route::post('payment-schedule-lines/{id}/archive', 'PaymentScheduleLineController@archive')->name('payment-schedule-lines.archive');
            Route::post('payment-schedule-lines/{id}/restore', 'PaymentScheduleLineController@restore')->name('payment-schedule-lines.restore');

            Route::post('payment-schedule-lines/fetch', 'PaymentScheduleLineFetchController@fetch')->name('payment-schedule-lines.fetch');
            Route::post('payment-schedule-lines/fetch?archived=1', 'PaymentScheduleLineFetchController@fetch')->name('payment-schedule-lines.fetch-archive');
            Route::post('payment-schedule-lines/fetch-item/{id?}', 'PaymentScheduleLineFetchController@fetchView')->name('payment-schedule-lines.fetch-item');
            Route::post('payment-schedule-lines/fetch-pagination/{id}', 'PaymentScheduleLineFetchController@fetchPagePagination')->name('payment-schedule-lines.fetch-pagination');
        });

        Route::namespace('WithholdingTaxes')->group(function() {
            Route::get('withholding-taxes', 'WithholdingTaxController@index')->name('withholding-taxes.index');
            Route::get('withholding-taxes/create', 'WithholdingTaxController@create')->name('withholding-taxes.create');
            Route::get('withholding-taxes/show/{id}', 'WithholdingTaxController@show')->name('withholding-taxes.show');

            Route::post('withholding-taxes/store', 'WithholdingTaxController@store')->name('withholding-taxes.store');
            Route::post('withholding-taxes/update/{id}', 'WithholdingTaxController@update')->name('withholding-taxes.update');
            Route::post('withholding-taxes/{id}/archive', 'WithholdingTaxController@archive')->name('withholding-taxes.archive');
            Route::post('withholding-taxes/{id}/restore', 'WithholdingTaxController@restore')->name('withholding-taxes.restore');

            Route::post('withholding-taxes/{id}/approve', 'WithholdingTaxController@approve')->name('withholding-taxes.approve');
            
            Route::post('withholding-taxes/fetch', 'WithholdingTaxFetchController@fetch')->name('withholding-taxes.fetch');
            Route::post('withholding-taxes/fetch?archived=1', 'WithholdingTaxFetchController@fetch')->name('withholding-taxes.fetch-archive');
            Route::post('withholding-taxes/fetch-item/{id?}', 'WithholdingTaxFetchController@fetchView')->name('withholding-taxes.fetch-item');
            Route::post('withholding-taxes/fetch-pagination/{id}', 'WithholdingTaxFetchController@fetchPagePagination')->name('withholding-taxes.fetch-pagination');

            Route::post('withholding-tax-lines/store', 'WithholdingTaxLineController@store')->name('withholding-tax-lines.store');
            Route::post('withholding-tax-lines/update/{id}', 'WithholdingTaxLineController@update')->name('withholding-tax-lines.update');
            Route::post('withholding-tax-lines/{id}/archive', 'WithholdingTaxLineController@archive')->name('withholding-tax-lines.archive');
            Route::post('withholding-tax-lines/{id}/restore', 'WithholdingTaxLineController@restore')->name('withholding-tax-lines.restore');

            Route::post('withholding-tax-lines/fetch', 'WithholdingTaxLineFetchController@fetch')->name('withholding-tax-lines.fetch');
            Route::post('withholding-tax-lines/fetch?archived=1', 'WithholdingTaxLineFetchController@fetch')->name('withholding-tax-lines.fetch-archive');
            Route::post('withholding-tax-lines/fetch-item/{id?}', 'WithholdingTaxLineFetchController@fetchView')->name('withholding-tax-lines.fetch-item');
            Route::post('withholding-tax-lines/fetch-pagination/{id}', 'WithholdingTaxLineFetchController@fetchPagePagination')->name('withholding-tax-lines.fetch-pagination');
        });

        Route::namespace('PaymentReversals')->group(function() {
            Route::get('payment-reversals', 'PaymentReversalController@index')->name('payment-reversals.index');
            Route::get('payment-reversals/create', 'PaymentReversalController@create')->name('payment-reversals.create');
            Route::get('payment-reversals/show/{id}', 'PaymentReversalController@show')->name('payment-reversals.show');

            Route::post('payment-reversals/store', 'PaymentReversalController@store')->name('payment-reversals.store');
            Route::post('payment-reversals/update/{id}', 'PaymentReversalController@update')->name('payment-reversals.update');
            Route::post('payment-reversals/{id}/archive', 'PaymentReversalController@archive')->name('payment-reversals.archive');
            Route::post('payment-reversals/{id}/restore', 'PaymentReversalController@restore')->name('payment-reversals.restore');

            Route::post('payment-reversals/fetch', 'PaymentReversalFetchController@fetch')->name('payment-reversals.fetch');
            Route::post('payment-reversals/fetch?archived=1', 'PaymentReversalFetchController@fetch')->name('payment-reversals.fetch-archive');
            Route::post('payment-reversals/fetch-item/{id?}', 'PaymentReversalFetchController@fetchView')->name('payment-reversals.fetch-item');
            Route::post('payment-reversals/fetch-pagination/{id}', 'PaymentReversalFetchController@fetchPagePagination')->name('payment-reversals.fetch-pagination');
        });

        //////////////////////////////
        // Products Controller //
        //////////////////////////////
        Route::namespace('ProductInventories')->group(function() {
            Route::namespace('Products')->group(function() {
                Route::get('products', 'ProductController@index')->name('products.index');
                Route::get('products/create', 'ProductController@create')->name('products.create');
                Route::post('products/store', 'ProductController@store')->name('products.store');
                Route::get('products/show/{id}', 'ProductController@show')->name('products.show');
                Route::post('products/update/{id}', 'ProductController@update')->name('products.update');
                Route::post('products/{id}/archive', 'ProductController@archive')->name('products.archive');
                Route::post('products/{id}/restore', 'ProductController@restore')->name('products.restore');
    
                Route::post('products/fetch', 'ProductFetchController@fetch')->name('products.fetch');
                Route::post('products/fetch?archived=1', 'ProductFetchController@fetch')->name('products.fetch-archive');
                Route::post('products/fetch-item/{id?}', 'ProductFetchController@fetchView')->name('products.fetch-item');
                Route::post('products/fetch-pagination/{id}', 'ProductFetchController@fetchPagePagination')->name('products.fetch-pagination');
            });

            Route::namespace('Variants')->group(function() {
                Route::get('variants', 'VariantController@index')->name('variants.index');
                Route::get('variants/product/{product?}/create', 'VariantController@create')->name('variants.create');
                Route::post('variants/store/{product?}', 'VariantController@store')->name('variants.store');
                Route::get('variants/show/{id}', 'VariantController@show')->name('variants.show');
                Route::post('variants/update/{id}', 'VariantController@update')->name('variants.update');
                Route::post('variants/{id}/archive', 'VariantController@archive')->name('variants.archive');
                Route::post('variants/{id}/restore', 'VariantController@restore')->name('variants.restore');
    
                Route::post('variants/fetch', 'VariantFetchController@fetch')->name('variants.fetch');
                Route::post('variants/fetch?archived=1', 'VariantFetchController@fetch')->name('variants.fetch-archive');
                Route::post('variants/fetch-item/{id?}', 'VariantFetchController@fetchView')->name('variants.fetch-item');
                Route::post('variants/fetch-pagination/{id}', 'VariantFetchController@fetchPagePagination')->name('variants.fetch-pagination');
            });

            Route::namespace('InventoryOnHand')->group(function() {
                Route::get('inventory-on-hands', 'InventoryOnHandController@index')->name('inventory-on-hands.index');
                Route::get('inventory-on-hands/show/{id}', 'InventoryOnHandController@show')->name('inventory-on-hands.show');
                Route::post('inventory-on-hands/update/{id}', 'InventoryOnHandController@update')->name('inventory-on-hands.update');
                
                Route::get('inventory-on-hands/create', 'InventoryOnHandController@create')->name('inventory-on-hands.create');
                Route::post('inventory-on-hands/store', 'InventoryOnHandController@store')->name('inventory-on-hands.store');
                Route::post('inventory-on-hands/{id}/archive', 'InventoryOnHandController@archive')->name('inventory-on-hands.archive');
                Route::post('inventory-on-hands/{id}/restore', 'InventoryOnHandController@restore')->name('inventory-on-hands.restore');
    
                Route::post('inventory-on-hands/fetch', 'InventoryOnHandFetchController@fetch')->name('inventory-on-hands.fetch');
                Route::post('inventory-on-hands/fetch?archived=1', 'InventoryOnHandFetchController@fetch')->name('inventory-on-hands.fetch-archive');
                Route::post('inventory-on-hands/fetch-item/{id?}', 'InventoryOnHandFetchController@fetchView')->name('inventory-on-hands.fetch-item');
            });
        });

        ////////////////////////////// 
        // Vendors Controller //
        //////////////////////////////
        Route::namespace('Vendors')->group(function() {

            Route::get('vendors', 'VendorController@index')->name('vendors.index');
            Route::get('vendors/create', 'VendorController@create')->name('vendors.create');
            Route::post('vendors/store', 'VendorController@store')->name('vendors.store');
            Route::get('vendors/show/{id}', 'VendorController@show')->name('vendors.show');
            Route::post('vendors/update/{id}', 'VendorController@update')->name('vendors.update');
            Route::post('vendors/{id}/archive', 'VendorController@archive')->name('vendors.archive');
            Route::post('vendors/{id}/restore', 'VendorController@restore')->name('vendors.restore');

            Route::post('vendors/fetch', 'VendorFetchController@fetch')->name('vendors.fetch');
            Route::post('vendors/fetch?archived=1', 'VendorFetchController@fetch')->name('vendors.fetch-archive');
            Route::post('vendors/fetch-item/{id?}', 'VendorFetchController@fetchView')->name('vendors.fetch-item');
            Route::post('vendors/fetch-pagination/{id}', 'VendorFetchController@fetchPagePagination')->name('vendors.fetch-pagination');

        });

        Route::namespace('VendorBankAccounts')->group(function() {
            Route::get('vendor-bank-accounts', 'VendorBankAccountController@index')->name('vendor-bank-accounts.index');
            Route::get('vendor-bank-accounts/create/{vendorid}', 'VendorBankAccountController@create')->name('vendor-bank-accounts.create');
            Route::get('vendor-bank-accounts/show/{id}', 'VendorBankAccountController@show')->name('vendor-bank-accounts.show');
            Route::post('vendor-bank-accounts/store', 'VendorBankAccountController@store')->name('vendor-bank-accounts.store');
            Route::post('vendor-bank-accounts/update/{id}', 'VendorBankAccountController@update')->name('vendor-bank-accounts.update');

            Route::post('vendor-bank-accounts/{id}/archive', 'VendorBankAccountController@archive')->name('vendor-bank-accounts.archive');
            Route::post('vendor-bank-accounts/{id}/restore', 'VendorBankAccountController@restore')->name('vendor-bank-accounts.restore');

            Route::post('vendor-bank-accounts/fetch', 'VendorBankAccountFetchController@fetch')->name('vendor-bank-accounts.fetch');
            Route::post('vendor-bank-accounts/fetch?archived=1', 'VendorBankAccountFetchController@fetch')->name('vendor-bank-accounts.fetch-archive');
            Route::post('vendor-bank-accounts/fetch-item/{id?}', 'VendorBankAccountFetchController@fetchView')->name('vendor-bank-accounts.fetch-item');
            Route::post('vendor-bank-accounts/fetch-pagination/{id}', 'VendorBankAccountFetchController@fetchPagePagination')->name('vendor-bank-accounts.fetch-pagination');
        });

        Route::namespace('BankPostings')->group(function() {
            Route::get('bank-postings', 'BankPostingController@index')->name('bank-postings.index');
            Route::get('bank-postings/create', 'BankPostingController@create')->name('bank-postings.create');
            Route::get('bank-postings/show/{id}', 'BankPostingController@show')->name('bank-postings.show');
            Route::post('bank-postings/store', 'BankPostingController@store')->name('bank-postings.store');
            Route::post('bank-postings/update/{id}', 'BankPostingController@update')->name('bank-postings.update');

            Route::post('bank-postings/{id}/archive', 'BankPostingController@archive')->name('bank-postings.archive');
            Route::post('bank-postings/{id}/restore', 'BankPostingController@restore')->name('bank-postings.restore');

            Route::post('bank-postings/fetch', 'BankPostingFetchController@fetch')->name('bank-postings.fetch');
            Route::post('bank-postings/fetch?archived=1', 'BankPostingFetchController@fetch')->name('bank-postings.fetch-archive');
            Route::post('bank-postings/fetch-item/{id?}', 'BankPostingFetchController@fetchView')->name('bank-postings.fetch-item');
            Route::post('bank-postings/fetch-pagination/{id}', 'BankPostingFetchController@fetchPagePagination')->name('bank-postings.fetch-pagination');
        });

        Route::namespace('BillsExchanges')->group(function() {
            Route::get('bills-exchanges', 'BillsExchangeController@index')->name('bills-exchanges.index');
            Route::get('bills-exchanges/create', 'BillsExchangeController@create')->name('bills-exchanges.create');
            Route::get('bills-exchanges/show/{id}', 'BillsExchangeController@show')->name('bills-exchanges.show');
            Route::post('bills-exchanges/store', 'BillsExchangeController@store')->name('bills-exchanges.store');
            Route::post('bills-exchanges/update/{id}', 'BillsExchangeController@update')->name('bills-exchanges.update');

            Route::post('bills-exchanges/approve/{id}', 'BillsExchangeController@approve')->name('bills-exchanges.approve');
            Route::post('bills-exchanges/redraw/{id}', 'BillsExchangeController@redraw')->name('bills-exchanges.redraw');
            Route::post('bills-exchanges/remit/{id}', 'BillsExchangeController@remit')->name('bills-exchanges.remit');
            Route::post('bills-exchanges/post/{id}', 'BillsExchangeController@post')->name('bills-exchanges.post');
            Route::post('bills-exchanges/settle/{id}', 'BillsExchangeController@settle')->name('bills-exchanges.settle');

            Route::post('bills-exchanges/{id}/archive', 'BillsExchangeController@archive')->name('bills-exchanges.archive');
            Route::post('bills-exchanges/{id}/restore', 'BillsExchangeController@restore')->name('bills-exchanges.restore');

            Route::post('bills-exchanges/fetch', 'BillsExchangeFetchController@fetch')->name('bills-exchanges.fetch');
            Route::post('bills-exchange-adjustments/fetch', 'BillsExchangeAdjustmentFetchController@fetch')->name('bills-exchange-adjustments.fetch');
            Route::post('bills-exchanges/fetch?archived=1', 'BillsExchangeFetchController@fetch')->name('bills-exchanges.fetch-archive');
            Route::post('bills-exchanges/fetch-item/{id?}', 'BillsExchangeFetchController@fetchView')->name('bills-exchanges.fetch-item');
            Route::post('bills-exchanges/fetch-pagination/{id}', 'BillsExchangeFetchController@fetchPagePagination')->name('bills-exchanges.fetch-pagination');
        });

        Route::namespace('PurchasePromissoryNotes')->group(function() {
            Route::get('purchase-promissory-notes', 'PromissoryNoteController@index')->name('purchase-promissory-notes.index');
            Route::get('purchase-promissory-notes/create', 'PromissoryNoteController@create')->name('purchase-promissory-notes.create');
            Route::get('purchase-promissory-notes/show/{id}', 'PromissoryNoteController@show')->name('purchase-promissory-notes.show');
            Route::post('purchase-promissory-notes/store', 'PromissoryNoteController@store')->name('purchase-promissory-notes.store');
            Route::post('purchase-promissory-notes/update/{id}', 'PromissoryNoteController@update')->name('purchase-promissory-notes.update');

            Route::post('purchase-promissory-notes/approve/{id}', 'PromissoryNoteController@approve')->name('purchase-promissory-notes.approve');
            Route::post('purchase-promissory-notes/redraw/{id}', 'PromissoryNoteController@redraw')->name('purchase-promissory-notes.redraw');
            Route::post('purchase-promissory-notes/remit/{id}', 'PromissoryNoteController@remit')->name('purchase-promissory-notes.remit');
            Route::post('purchase-promissory-notes/post/{id}', 'PromissoryNoteController@post')->name('purchase-promissory-notes.post');
            Route::post('purchase-promissory-notes/settle/{id}', 'PromissoryNoteController@settle')->name('purchase-promissory-notes.settle');

            Route::post('purchase-promissory-notes/{id}/archive', 'PromissoryNoteController@archive')->name('purchase-promissory-notes.archive');
            Route::post('purchase-promissory-notes/{id}/restore', 'PromissoryNoteController@restore')->name('purchase-promissory-notes.restore');

            Route::post('purchase-promissory-notes/fetch', 'PromissoryNoteFetchController@fetch')->name('purchase-promissory-notes.fetch');
            Route::post('purchase-promissory-notes/fetch?archived=1', 'PromissoryNoteFetchController@fetch')->name('purchase-promissory-notes.fetch-archive');
            Route::post('purchase-promissory-notes/fetch-item/{id?}', 'PromissoryNoteFetchController@fetchView')->name('purchase-promissory-notes.fetch-item');
            Route::post('purchase-promissory-notes/fetch-pagination/{id}', 'PromissoryNoteFetchController@fetchPagePagination')->name('purchase-promissory-notes.fetch-pagination');
        });

        Route::namespace('Collections')->group(function() {
            Route::get('collections', 'CollectionController@index')->name('collections.index');
            Route::get('collections/create/{boe?}', 'CollectionController@create')->name('collections.create');
            Route::get('collections/show/{id}', 'CollectionController@show')->name('collections.show');
            Route::post('collections/store', 'CollectionController@store')->name('collections.store');
            Route::post('collections/update/{id}', 'CollectionController@update')->name('collections.update');

            Route::post('collections/post/{id}', 'CollectionController@post')->name('collections.post');
            Route::post('collections/close/{id}', 'CollectionController@close')->name('collections.close');
            Route::post('collections/write/off/{id}', 'CollectionController@writeOff')->name('collections.writeOff');

            Route::post('collections/{id}/archive', 'CollectionController@archive')->name('collections.archive');
            Route::post('collections/{id}/restore', 'CollectionController@restore')->name('collections.restore');

            Route::post('collections/fetch', 'CollectionFetchController@fetch')->name('collections.fetch');
            Route::post('collections/fetch?archived=1', 'CollectionFetchController@fetch')->name('collections.fetch-archive');
            Route::post('collections/fetch-item/{id?}', 'CollectionFetchController@fetchView')->name('collections.fetch-item');
            Route::post('collections/fetch-pagination/{id}', 'CollectionFetchController@fetchPagePagination')->name('collections.fetch-pagination');
        });

        Route::namespace('InterestNotes')->group(function() {
            Route::get('interest-notes', 'InterestNoteController@index')->name('interest-notes.index');
            Route::get('interest-notes/create', 'InterestNoteController@create')->name('interest-notes.create');
            Route::get('interest-notes/show/{id}', 'InterestNoteController@show')->name('interest-notes.show');
            Route::post('interest-notes/store', 'InterestNoteController@store')->name('interest-notes.store');
            Route::post('interest-notes/update/{id}', 'InterestNoteController@update')->name('interest-notes.update');
            Route::post('interest-notes/post/{id}', 'InterestNoteController@post')->name('interest-notes.post');

            Route::post('interest-notes/{id}/archive', 'InterestNoteController@archive')->name('interest-notes.archive');
            Route::post('interest-notes/{id}/restore', 'InterestNoteController@restore')->name('interest-notes.restore');

            Route::post('interest-notes/fetch', 'InterestNoteFetchController@fetch')->name('interest-notes.fetch');
            Route::post('interest-notes/fetch?archived=1', 'InterestNoteFetchController@fetch')->name('interest-notes.fetch-archive');
            Route::post('interest-notes/fetch-item/{id?}', 'InterestNoteFetchController@fetchView')->name('interest-notes.fetch-item');
            Route::post('interest-notes/fetch-pagination/{id}', 'InterestNoteFetchController@fetchPagePagination')->name('interest-notes.fetch-pagination');
        });

        Route::namespace('InterestSetups')->group(function() {
            Route::get('interest-setups', 'InterestSetupController@index')->name('interest-setups.index');
            Route::get('interest-setups/create', 'InterestSetupController@create')->name('interest-setups.create');
            Route::get('interest-setups/show/{id}', 'InterestSetupController@show')->name('interest-setups.show');
            Route::post('interest-setups/store', 'InterestSetupController@store')->name('interest-setups.store');
            Route::post('interest-setups/update/{id}', 'InterestSetupController@update')->name('interest-setups.update');

            Route::post('interest-setups/{id}/archive', 'InterestSetupController@archive')->name('interest-setups.archive');
            Route::post('interest-setups/{id}/restore', 'InterestSetupController@restore')->name('interest-setups.restore');

            Route::post('interest-setups/fetch', 'InterestSetupFetchController@fetch')->name('interest-setups.fetch');
            Route::post('interest-setups/fetch?archived=1', 'InterestSetupFetchController@fetch')->name('interest-setups.fetch-archive');
            Route::post('interest-setups/fetch-item/{id?}', 'InterestSetupFetchController@fetchView')->name('interest-setups.fetch-item');
            Route::post('interest-setups/fetch-pagination/{id}', 'InterestSetupFetchController@fetchPagePagination')->name('interest-setups.fetch-pagination');
        });

        Route::namespace('InterestCalculations')->group(function() {
            Route::get('interest-calculations', 'InterestCalculationController@index')->name('interest-calculations.index');
            Route::get('interest-calculations/create', 'InterestCalculationController@create')->name('interest-calculations.create');
            Route::get('interest-calculations/show/{id}', 'InterestCalculationController@show')->name('interest-calculations.show');
            Route::post('interest-calculations/store', 'InterestCalculationController@store')->name('interest-calculations.store');
            Route::post('interest-calculations/update/{id}', 'InterestCalculationController@update')->name('interest-calculations.update');

            Route::post('interest-calculations/{id}/archive', 'InterestCalculationController@archive')->name('interest-calculations.archive');
            Route::post('interest-calculations/{id}/restore', 'InterestCalculationController@restore')->name('interest-calculations.restore');

            Route::post('interest-calculations/fetch', 'InterestCalculationFetchController@fetch')->name('interest-calculations.fetch');
            Route::post('interest-calculations/fetch?archived=1', 'InterestCalculationFetchController@fetch')->name('interest-calculations.fetch-archive');
            Route::post('interest-calculations/fetch-item/{id?}', 'InterestCalculationFetchController@fetchView')->name('interest-calculations.fetch-item');
            Route::post('interest-calculations/fetch-pagination/{id}', 'InterestCalculationFetchController@fetchPagePagination')->name('interest-calculations.fetch-pagination');
        });

        Route::namespace('InterestAdjustments')->group(function() {
            Route::get('interest-adjustments', 'InterestAdjustmentController@index')->name('interest-adjustments.index');
            Route::get('interest-adjustments/create', 'InterestAdjustmentController@create')->name('interest-adjustments.create');
            Route::get('interest-adjustments/show/{id}', 'InterestAdjustmentController@show')->name('interest-adjustments.show');
            Route::post('interest-adjustments/store', 'InterestAdjustmentController@store')->name('interest-adjustments.store');
            Route::post('interest-adjustments/update/{id}', 'InterestAdjustmentController@update')->name('interest-adjustments.update');
            Route::post('interest-adjustments/action/{id}/{action}', 'InterestAdjustmentController@action')->name('interest-adjustments.action');

            Route::post('interest-adjustments/{id}/archive', 'InterestAdjustmentController@archive')->name('interest-adjustments.archive');
            Route::post('interest-adjustments/{id}/restore', 'InterestAdjustmentController@restore')->name('interest-adjustments.restore');

            Route::post('interest-adjustments/fetch', 'InterestAdjustmentFetchController@fetch')->name('interest-adjustments.fetch');
            Route::post('interest-adjustments/fetch?archived=1', 'InterestAdjustmentFetchController@fetch')->name('interest-adjustments.fetch-archive');
            Route::post('interest-adjustments/fetch-item/{id?}', 'InterestAdjustmentFetchController@fetchView')->name('interest-adjustments.fetch-item');
            Route::post('interest-adjustments/fetch-pagination/{id}', 'InterestAdjustmentFetchController@fetchPagePagination')->name('interest-adjustments.fetch-pagination');
        });

        //////////////////////////////
        // Cost Centers Controller //
        //////////////////////////////
        Route::namespace('CostCenters')->group(function() {

            Route::get('cost-centers', 'CostCenterController@index')->name('cost-centers.index');
            Route::get('cost-centers/create', 'CostCenterController@create')->name('cost-centers.create');
            Route::post('cost-centers/store', 'CostCenterController@store')->name('cost-centers.store');
            Route::get('cost-centers/show/{id}', 'CostCenterController@show')->name('cost-centers.show');
            Route::post('cost-centers/update/{id}', 'CostCenterController@update')->name('cost-centers.update');
            Route::post('cost-centers/{id}/archive', 'CostCenterController@archive')->name('cost-centers.archive');
            Route::post('cost-centers/{id}/restore', 'CostCenterController@restore')->name('cost-centers.restore');

            Route::post('cost-centers/fetch', 'CostCenterFetchController@fetch')->name('cost-centers.fetch');
            Route::post('cost-centers/fetch?archived=1', 'CostCenterFetchController@fetch')->name('cost-centers.fetch-archive');
            Route::post('cost-centers/fetch-item/{id?}', 'CostCenterFetchController@fetchView')->name('cost-centers.fetch-item');
            Route::post('cost-centers/fetch-pagination/{id}', 'CostCenterFetchController@fetchPagePagination')->name('cost-centers.fetch-pagination');

        });

        //////////////////////////////
        // Chart of Accounts Controller //
        //////////////////////////////
        Route::namespace('ChartOfAccounts')->group(function() {

            Route::get('chart-of-accounts', 'ChartOfAccountController@index')->name('chart-of-accounts.index');
            Route::get('chart-of-accounts/create', 'ChartOfAccountController@create')->name('chart-of-accounts.create');
            Route::post('chart-of-accounts/store', 'ChartOfAccountController@store')->name('chart-of-accounts.store');
            Route::get('chart-of-accounts/show/{id}', 'ChartOfAccountController@show')->name('chart-of-accounts.show');
            Route::post('chart-of-accounts/update/{id}', 'ChartOfAccountController@update')->name('chart-of-accounts.update');
            Route::post('chart-of-accounts/{id}/archive', 'ChartOfAccountController@archive')->name('chart-of-accounts.archive');
            Route::post('chart-of-accounts/{id}/restore', 'ChartOfAccountController@restore')->name('chart-of-accounts.restore');

            Route::post('chart-of-accounts/fetch', 'ChartOfAccountFetchController@fetch')->name('chart-of-accounts.fetch');
            Route::post('chart-of-accounts/fetch?archived=1', 'ChartOfAccountFetchController@fetch')->name('chart-of-accounts.fetch-archive');
            Route::post('chart-of-accounts/fetch-item/{id?}', 'ChartOfAccountFetchController@fetchView')->name('chart-of-accounts.fetch-item');
            Route::post('chart-of-accounts/fetch-pagination/{id}', 'ChartOfAccountFetchController@fetchPagePagination')->name('chart-of-accounts.fetch-pagination');

        });

        //////////////////////////////
        // Charges Controller       //
        //////////////////////////////
        Route::namespace('Charges')->group(function() {

            Route::get('charges', 'ChargeController@index')->name('charges.index');
            Route::get('charges/create', 'ChargeController@create')->name('charges.create');
            Route::post('charges/store', 'ChargeController@store')->name('charges.store');
            Route::get('charges/show/{id}', 'ChargeController@show')->name('charges.show');
            Route::post('charges/update/{id}', 'ChargeController@update')->name('charges.update');
            Route::post('charges/{id}/archive', 'ChargeController@archive')->name('charges.archive');
            Route::post('charges/{id}/restore', 'ChargeController@restore')->name('charges.restore');

            Route::post('charges/fetch', 'ChargeFetchController@fetch')->name('charges.fetch');
            Route::post('charges/fetch?archived=1', 'ChargeFetchController@fetch')->name('charges.fetch-archive');
            Route::post('charges/fetch-item/{id?}', 'ChargeFetchController@fetchView')->name('charges.fetch-item');
            Route::post('charges/fetch-pagination/{id}', 'ChargeFetchController@fetchPagePagination')->name('charges.fetch-pagination');

        });

        //////////////////////////////
        // PaymentFees Controller   //
        //////////////////////////////
        Route::namespace('PaymentFees')->group(function() {

            Route::get('payment-fees', 'PaymentFeeController@index')->name('payment-fees.index');
            Route::get('payment-fees/create', 'PaymentFeeController@create')->name('payment-fees.create');
            Route::post('payment-fees/store', 'PaymentFeeController@store')->name('payment-fees.store');
            Route::get('payment-fees/show/{id}', 'PaymentFeeController@show')->name('payment-fees.show');
            Route::post('payment-fees/update/{id}', 'PaymentFeeController@update')->name('payment-fees.update');
            Route::post('payment-fees/{id}/archive', 'PaymentFeeController@archive')->name('payment-fees.archive');
            Route::post('payment-fees/{id}/restore', 'PaymentFeeController@restore')->name('payment-fees.restore');

            Route::post('payment-fees/fetch', 'PaymentFeeFetchController@fetch')->name('payment-fees.fetch');
            Route::post('payment-fees/fetch?archived=1', 'PaymentFeeFetchController@fetch')->name('payment-fees.fetch-archive');
            Route::post('payment-fees/fetch-item/{id?}', 'PaymentFeeFetchController@fetchView')->name('payment-fees.fetch-item');
            Route::post('payment-fees/fetch-pagination/{id}', 'PaymentFeeFetchController@fetchPagePagination')->name('payment-fees.fetch-pagination');

        });

        ////////////////////////////////////////
        // Vendor Payment Fee Setup Controller//
        ///////////////////////////////////////
        Route::namespace('VendorPaymentFeeSetups')->group(function() {

            Route::get('vendor-payment-fee-setups', 'VendorPaymentFeeSetupController@index')->name('vendor-payment-fee-setups.index');
            Route::get('vendor-payment-fee-setups/create', 'VendorPaymentFeeSetupController@create')->name('vendor-payment-fee-setups.create');
            Route::post('vendor-payment-fee-setups/store', 'VendorPaymentFeeSetupController@store')->name('vendor-payment-fee-setups.store');
            Route::get('vendor-payment-fee-setups/show/{id}', 'VendorPaymentFeeSetupController@show')->name('vendor-payment-fee-setups.show');
            Route::post('vendor-payment-fee-setups/update/{id}', 'VendorPaymentFeeSetupController@update')->name('vendor-payment-fee-setups.update');
            Route::post('vendor-payment-fee-setups/{id}/archive', 'VendorPaymentFeeSetupController@archive')->name('vendor-payment-fee-setups.archive');
            Route::post('vendor-payment-fee-setups/{id}/restore', 'VendorPaymentFeeSetupController@restore')->name('vendor-payment-fee-setups.restore');

            Route::post('vendor-payment-fee-setups/fetch', 'VendorPaymentFeeSetupFetchController@fetch')->name('vendor-payment-fee-setups.fetch');
            Route::post('vendor-payment-fee-setups/fetch?archived=1', 'VendorPaymentFeeSetupFetchController@fetch')->name('vendor-payment-fee-setups.fetch-archive');
            Route::post('vendor-payment-fee-setups/fetch-item/{id?}', 'VendorPaymentFeeSetupFetchController@fetchView')->name('vendor-payment-fee-setups.fetch-item');
            Route::post('vendor-payment-fee-setups/fetch-pagination/{id}', 'VendorPaymentFeeSetupFetchController@fetchPagePagination')->name('vendor-payment-fee-setups.fetch-pagination');

        });

        //////////////////////////////
        // Discounts Controller      //
        //////////////////////////////
        Route::namespace('Discounts')->group(function() {

            Route::get('discounts', 'DiscountController@index')->name('discounts.index');
            Route::get('discounts/create', 'DiscountController@create')->name('discounts.create');
            Route::post('discounts/store', 'DiscountController@store')->name('discounts.store');
            Route::get('discounts/show/{id}', 'DiscountController@show')->name('discounts.show');
            Route::post('discounts/update/{id}', 'DiscountController@update')->name('discounts.update');
            Route::post('discounts/{id}/archive', 'DiscountController@archive')->name('discounts.archive');
            Route::post('discounts/{id}/restore', 'DiscountController@restore')->name('discounts.restore');

            Route::post('discounts/fetch', 'DiscountFetchController@fetch')->name('discounts.fetch');
            Route::post('discounts/fetch?archived=1', 'DiscountFetchController@fetch')->name('discounts.fetch-archive');
            Route::post('discounts/fetch-item/{id?}', 'DiscountFetchController@fetchView')->name('discounts.fetch-item');
            Route::post('discounts/fetch-pagination/{id}', 'DiscountFetchController@fetchPagePagination')->name('discounts.fetch-pagination');

        });

        //////////////////////////////
        // CustomerSummary Controller      //
        //////////////////////////////
        Route::namespace('CustomerSummaries')->group(function() {

            Route::get('customer-summaries', 'CustomerSummaryController@index')->name('customer-summaries.index');
            Route::get('customer-summaries/create', 'CustomerSummaryController@create')->name('customer-summaries.create');
            Route::post('customer-summaries/store', 'CustomerSummaryController@store')->name('customer-summaries.store');
            Route::get('customer-summaries/show/{id}', 'CustomerSummaryController@show')->name('customer-summaries.show');
            Route::post('customer-summaries/update/{id}', 'CustomerSummaryController@update')->name('customer-summaries.update');
            Route::post('customer-summaries/{id}/archive', 'CustomerSummaryController@archive')->name('customer-summaries.archive');
            Route::post('customer-summaries/{id}/restore', 'CustomerSummaryController@restore')->name('customer-summaries.restore');
            Route::post('customer-summaries/{id}/approved', 'CustomerSummaryController@approved')->name('customer-summaries.approved');

            Route::post('customer-summaries/fetch', 'CustomerSummaryFetchController@fetch')->name('customer-summaries.fetch');
            Route::post('customer-summaries/fetch?archived=1', 'CustomerSummaryFetchController@fetch')->name('customer-summaries.fetch-archive');
            Route::post('customer-summaries/fetch-item/{id?}', 'CustomerSummaryFetchController@fetchView')->name('customer-summaries.fetch-item');
            Route::post('customer-summaries/fetch-pagination/{id}', 'CustomerSummaryFetchController@fetchPagePagination')->name('customer-summaries.fetch-pagination');

        });

        /////////////////////////////////////
        // Customer Payment Fee Controller //
        ////////////////////////////////////
        Route::namespace('CustomerPaymentFees')->group(function() {

            Route::get('customer-payment-fees', 'CustomerPaymentFeeController@index')->name('customer-payment-fees.index');
            Route::get('customer-payment-fees/create', 'CustomerPaymentFeeController@create')->name('customer-payment-fees.create');
            Route::post('customer-payment-fees/store', 'CustomerPaymentFeeController@store')->name('customer-payment-fees.store');
            Route::get('customer-payment-fees/show/{id}', 'CustomerPaymentFeeController@show')->name('customer-payment-fees.show');
            Route::post('customer-payment-fees/update/{id}', 'CustomerPaymentFeeController@update')->name('customer-payment-fees.update');
            Route::post('customer-payment-fees/{id}/archive', 'CustomerPaymentFeeController@archive')->name('customer-payment-fees.archive');
            Route::post('customer-payment-fees/{id}/restore', 'CustomerPaymentFeeController@restore')->name('customer-payment-fees.restore');

            Route::post('customer-payment-fees/fetch', 'CustomerPaymentFeeFetchController@fetch')->name('customer-payment-fees.fetch');
            Route::post('customer-payment-fees/fetch?archived=1', 'CustomerPaymentFeeFetchController@fetch')->name('customer-payment-fees.fetch-archive');
            Route::post('customer-payment-fees/fetch-item/{id?}', 'CustomerPaymentFeeFetchController@fetchView')->name('customer-payment-fees.fetch-item');
            Route::post('customer-payment-fees/fetch-pagination/{id}', 'CustomerPaymentFeeFetchController@fetchPagePagination')->name('customer-payment-fees.fetch-pagination');
        });
        
        ///////////////////////////////////
        // Vendor Payment Fee Controller//
        /////////////////////////////////
        Route::namespace('VendorPaymentFees')->group(function() {

            Route::get('vendor-payment-fees', 'VendorPaymentFeeController@index')->name('vendor-payment-fees.index');
            Route::get('vendor-payment-fees/create', 'VendorPaymentFeeController@create')->name('vendor-payment-fees.create');
            Route::post('vendor-payment-fees/store', 'VendorPaymentFeeController@store')->name('vendor-payment-fees.store');
            Route::get('vendor-payment-fees/show/{id}', 'VendorPaymentFeeController@show')->name('vendor-payment-fees.show');
            Route::post('vendor-payment-fees/update/{id}', 'VendorPaymentFeeController@update')->name('vendor-payment-fees.update');
            Route::post('vendor-payment-fees/{id}/archive', 'VendorPaymentFeeController@archive')->name('vendor-payment-fees.archive');
            Route::post('vendor-payment-fees/{id}/restore', 'VendorPaymentFeeController@restore')->name('vendor-payment-fees.restore');

            Route::post('vendor-payment-fees/fetch', 'VendorPaymentFeeFetchController@fetch')->name('vendor-payment-fees.fetch');
            Route::post('vendor-payment-fees/fetch?archived=1', 'VendorPaymentFeeFetchController@fetch')->name('vendor-payment-fees.fetch-archive');
            Route::post('vendor-payment-fees/fetch-item/{id?}', 'VendorPaymentFeeFetchController@fetchView')->name('vendor-payment-fees.fetch-item');
            Route::post('vendor-payment-fees/fetch-pagination/{id}', 'VendorPaymentFeeFetchController@fetchPagePagination')->name('vendor-payment-fees.fetch-pagination');

        });

        // //////////////////////////////
        // // Chart of Accounts Main Account Controller //
        // //////////////////////////////
        // Route::namespace('ChartOfAccountsMainAccount')->group(function() {

        //     Route::get('chart-of-accounts-main-account', 'ChartOfAccountMainAccountController@index')->name('chart-of-accounts-main-account.index');
        //     Route::get('chart-of-accounts-main-account/create', 'ChartOfAccountMainAccountController@create')->name('chart-of-accounts-main-account.create');
        //     Route::post('chart-of-accounts-main-account/store', 'ChartOfAccountMainAccountController@store')->name('chart-of-accounts-main-account.store');
        //     Route::get('chart-of-accounts-main-account/show/{id}', 'ChartOfAccountMainAccountController@show')->name('chart-of-accounts-main-account.show');
        //     Route::post('chart-of-accounts-main-account/update/{id}', 'ChartOfAccountMainAccountController@update')->name('chart-of-accounts-main-account.update');
        //     Route::post('chart-of-accounts-main-account/{id}/archive', 'ChartOfAccountMainAccountController@archive')->name('chart-of-accounts-main-account.archive');
        //     Route::post('chart-of-accounts-main-account/{id}/restore', 'ChartOfAccountMainAccountController@restore')->name('chart-of-accounts-main-account.restore');

        //     Route::post('chart-of-accounts-main-account/fetch', 'ChartOfAccountMainAccountFetchController@fetch')->name('chart-of-accounts-main-account.fetch');
        //     Route::post('chart-of-accounts-main-account/fetch?archived=1', 'ChartOfAccountMainAccountFetchController@fetch')->name('chart-of-accounts-main-account.fetch-archive');
        //     Route::post('chart-of-accounts-main-account/fetch-item/{id?}', 'ChartOfAccountMainAccountFetchController@fetchView')->name('chart-of-accounts-main-account.fetch-item');
        //     Route::post('chart-of-accounts-main-account/fetch-pagination/{id}', 'ChartOfAccountMainAccountFetchController@fetchPagePagination')->name('chart-of-accounts-main-account.fetch-pagination');

        // });        

        //////////////////////////////
        // Payment Day Controller //
        //////////////////////////////
        Route::namespace('PaymentDays')->group(function() {

            Route::get('payment-days', 'PaymentDayController@index')->name('payment-days.index');
            Route::get('payment-days/create', 'PaymentDayController@create')->name('payment-days.create');
            Route::post('payment-days/store', 'PaymentDayController@store')->name('payment-days.store');
            Route::get('payment-days/show/{id}', 'PaymentDayController@show')->name('payment-days.show');
            Route::post('payment-days/update/{id}', 'PaymentDayController@update')->name('payment-days.update');
            Route::post('payment-days/{id}/archive', 'PaymentDayController@archive')->name('payment-days.archive');
            Route::post('payment-days/{id}/restore', 'PaymentDayController@restore')->name('payment-days.restore');

            Route::post('payment-days/fetch', 'PaymentDayFetchController@fetch')->name('payment-days.fetch');
            Route::post('payment-days/fetch?archived=1', 'PaymentDayFetchController@fetch')->name('payment-days.fetch-archive');
            Route::post('payment-days/fetch-item/{id?}', 'PaymentDayFetchController@fetchView')->name('payment-days.fetch-item');
            Route::post('payment-days/fetch-pagination/{id}', 'PaymentDayFetchController@fetchPagePagination')->name('payment-days.fetch-pagination');

        });

        //////////////////////////////
        // Terms Of Payment Controller //
        //////////////////////////////
        Route::namespace('TermsOfPayments')->group(function() {

            Route::get('terms-vendor', 'TermsOfPaymentController@index')->name('terms.index');
            Route::get('terms-vendor/create', 'TermsOfPaymentController@create')->name('terms.create');
            Route::post('terms-vendor/store', 'TermsOfPaymentController@store')->name('terms.store');
            Route::get('terms-vendor/show/{id}', 'TermsOfPaymentController@show')->name('terms.show');
            Route::post('terms-vendor/update/{id}', 'TermsOfPaymentController@update')->name('terms.update');
            Route::post('terms-vendor/{id}/archive', 'TermsOfPaymentController@archive')->name('terms.archive');
            Route::post('terms-vendor/{id}/restore', 'TermsOfPaymentController@restore')->name('terms.restore');

            Route::post('terms-vendor/fetch', 'TermsOfPaymentFetchController@fetch')->name('terms.fetch');
            Route::post('terms-vendor/fetch?archived=1', 'TermsOfPaymentFetchController@fetch')->name('terms.fetch-archive');
            Route::post('terms-vendor/fetch-item/{id?}', 'TermsOfPaymentFetchController@fetchView')->name('terms.fetch-item');
            Route::post('terms-vendor/fetch-pagination/{id}', 'TermsOfPaymentFetchController@fetchPagePagination')->name('terms.fetch-pagination');

            Route::get('term-custopmers', 'TermsOfPaymentCustomerController@index')->name('term-customers.index');
            Route::get('term-custopmers/create', 'TermsOfPaymentCustomerController@create')->name('term-customers.create');
            Route::post('term-custopmers/store', 'TermsOfPaymentCustomerController@store')->name('term-customers.store');
            Route::get('term-custopmers/show/{id}', 'TermsOfPaymentCustomerController@show')->name('term-customers.show');
            Route::post('term-custopmers/update/{id}', 'TermsOfPaymentCustomerController@update')->name('term-customers.update');
            Route::post('term-custopmers/{id}/archive', 'TermsOfPaymentCustomerController@archive')->name('term-customers.archive');
            Route::post('term-custopmers/{id}/restore', 'TermsOfPaymentCustomerController@restore')->name('term-customers.restore');

            Route::post('term-custopmers/fetch', 'TermsOfPaymentCustomerFetchController@fetch')->name('term-customers.fetch');
            Route::post('term-custopmers/fetch?archived=1', 'TermsOfPaymentCustomerFetchController@fetch')->name('term-customers.fetch-archive');
            Route::post('term-custopmers/fetch-item/{id?}', 'TermsOfPaymentCustomerFetchController@fetchView')->name('term-customers.fetch-item');
            Route::post('term-custopmers/fetch-pagination/{id}', 'TermsOfPaymentCustomerFetchController@fetchPagePagination')->name('term-customers.fetch-pagination');
        });

        //////////////////////////////
        // Purchase Orders Controller //
        //////////////////////////////
        Route::namespace('PurchaseOrders')->group(function() {
        
            Route::get('purchase-orders', 'PurchaseOrderController@index')->name('purchase-orders.index');
            Route::get('purchase-orders/create', 'PurchaseOrderController@create')->name('purchase-orders.create');
            Route::post('purchase-orders/store', 'PurchaseOrderController@store')->name('purchase-orders.store');
            Route::get('purchase-orders/show/{id}', 'PurchaseOrderController@show')->name('purchase-orders.show');
            Route::post('purchase-orders/update/{id}', 'PurchaseOrderController@update')->name('purchase-orders.update');
            Route::post('purchase-orders/{id}/archive', 'PurchaseOrderController@archive')->name('purchase-orders.archive');
            Route::post('purchase-orders/{id}/restore', 'PurchaseOrderController@restore')->name('purchase-orders.restore');
            Route::get('purchase-orders/print/{id}', 'PurchaseOrderController@printPDF')->name('purchase-orders.print');


            Route::post('purchase-orders/confirmation/{id}', 'PurchaseOrderController@confirmation')->name('purchase-orders.confirmation');

            Route::post('purchase-orders/fetch', 'PurchaseOrderFetchController@fetch')->name('purchase-orders.fetch');
            Route::post('purchase-orders/fetch?archived=1', 'PurchaseOrderFetchController@fetch')->name('purchase-orders.fetch-archive');
            Route::post('purchase-orders/fetch-item/{id?}', 'PurchaseOrderFetchController@fetchView')->name('purchase-orders.fetch-item');
            Route::post('purchase-orders/fetch-pagination/{id}', 'PurchaseOrderFetchController@fetchPagePagination')->name('purchase-orders.fetch-pagination');

        });

        ///////////////////////////////////////
        // Purchase Order Returns Controller //
        //////////////////////////////////////
        Route::namespace('PurchaseOrderReturns')->group(function() {
        
            Route::get('purchase-order-returns', 'PurchaseOrderReturnController@index')->name('purchase-order-returns.index');
            Route::get('purchase-order-returns/create', 'PurchaseOrderReturnController@create')->name('purchase-order-returns.create');
            Route::post('purchase-order-returns/store', 'PurchaseOrderReturnController@store')->name('purchase-order-returns.store');
            Route::get('purchase-order-returns/show/{id}', 'PurchaseOrderReturnController@show')->name('purchase-order-returns.show');
            Route::post('purchase-order-returns/update/{id}', 'PurchaseOrderReturnController@update')->name('purchase-order-returns.update');
            Route::post('purchase-order-returns/{id}/archive', 'PurchaseOrderReturnController@archive')->name('purchase-order-returns.archive');
            Route::post('purchase-order-returns/{id}/restore', 'PurchaseOrderReturnController@restore')->name('purchase-order-returns.restore');
            Route::get('purchase-order-returns/print/{id}', 'PurchaseOrderReturnController@printPDF')->name('purchase-order-returns.print');
            Route::post('purchase-order-returns/{id}/cancel', 'PurchaseOrderReturnController@cancel')->name('purchase-order-returns.cancel');


            Route::post('purchase-order-returns/confirmation/{id}', 'PurchaseOrderReturnController@confirmation')->name('purchase-order-returns.confirmation');

            Route::post('purchase-order-returns/fetch', 'PurchaseOrderReturnFetchController@fetch')->name('purchase-order-returns.fetch');
            Route::post('purchase-order-returns/fetch?archived=1', 'PurchaseOrderReturnFetchController@fetch')->name('purchase-order-returns.fetch-archive');
            Route::post('purchase-order-returns/fetch-item/{id?}', 'PurchaseOrderReturnFetchController@fetchView')->name('purchase-order-returns.fetch-item');
            Route::post('purchase-order-returns/fetch-pagination/{id}', 'PurchaseOrderReturnFetchController@fetchPagePagination')->name('purchase-order-returns.fetch-pagination');

        });

        //////////////////////////////
        // Vendor Invoices Controller //
        //////////////////////////////
        Route::namespace('VendorInvoices')->group(function() {

            Route::get('vendor-invoices', 'VendorInvoiceController@index')->name('vendor-invoices.index');
            Route::get('vendor-invoices/create/{purchase_order_number?}', 'VendorInvoiceController@create')->name('vendor-invoices.create');
            Route::post('vendor-invoices/store/{purchase_order_number?}', 'VendorInvoiceController@store')->name('vendor-invoices.store');
            Route::get('vendor-invoices/show/{id}', 'VendorInvoiceController@show')->name('vendor-invoices.show');
            Route::post('vendor-invoices/update/{id}', 'VendorInvoiceController@update')->name('vendor-invoices.update');
            Route::post('vendor-invoices/{id}/archive', 'VendorInvoiceController@archive')->name('vendor-invoices.archive');
            Route::post('vendor-invoices/{id}/restore', 'VendorInvoiceController@restore')->name('vendor-invoices.restore');
            Route::get('vendor-invoices/print/{id}', 'VendorInvoiceController@printPDF')->name('vendor-invoices.print');
            Route::post('vendor-invoices/{id}/cancel', 'VendorInvoiceController@cancel')->name('vendor-invoices.cancel');

            Route::post('vendor-invoices/approved/{id}', 'VendorInvoiceController@approved')->name('vendor-invoices.approved');
            Route::post('vendor-invoices/posted/{id}', 'VendorInvoiceController@posted')->name('vendor-invoices.posted');

            Route::post('vendor-invoices/to-invoice/{id}', 'VendorInvoiceController@toInvoice')->name('vendor-invoices.to-invoice');

            Route::get('vendor-invoices/report', 'VendorInvoiceController@vendorInvoiceAging')->name('vendor-invoice-aging.index');

            Route::post('vendor-invoices/fetch', 'VendorInvoiceFetchController@fetch')->name('vendor-invoices.fetch');
            Route::post('vendor-invoices/fetch?archived=1', 'VendorInvoiceFetchController@fetch')->name('vendor-invoices.fetch-archive');
            Route::post('vendor-invoices/fetch-item/{purchase_order_number?}/{id?}', 'VendorInvoiceFetchController@fetchView')->name('vendor-invoices.fetch-item');
            Route::post('vendor-invoices/fetch-pagination/{id}', 'VendorInvoiceFetchController@fetchPagePagination')->name('vendor-invoices.fetch-pagination');


            Route::post('vendor-invoices/aging/fetch', 'VendorInvoiceFetchController@fetch')->name('vendor-invoices-aging.fetch');

        });


        //////////////////////////////
        // Purchase Order Lines Controller //
        //////////////////////////////
        Route::namespace('VendorInvoiceLines')->group(function() {

            Route::post('vendor-invoice-lines/{id}/archive', 'VendorInvoiceLineController@archive')->name('vendor-invoice-lines.archive');
            Route::post('vendor-invoice-lines/{id}/restore', 'VendorInvoiceLineController@restore')->name('vendor-invoice-lines.restore');
            Route::post('vendor-invoice-lines/{id}/approve', 'VendorInvoiceLineController@approve')->name('vendor-invoice-lines.approve');
            Route::post('vendor-invoice-lines/{id}/reject', 'VendorInvoiceLineController@reject')->name('vendor-invoice-lines.reject');

        });

        //////////////////////////////
        // Purchase Delivery Receipt Controller //
        //////////////////////////////
        Route::namespace('PuchaseDeliveryReceipts')->group(function() {

            Route::get('purchase-delivery-receipts', 'PurchaseDeliveryReceiptController@index')->name('purchase-delivery-receipts.index');
            Route::get('purchase-delivery-receipts/create/{id?}', 'PurchaseDeliveryReceiptController@create')->name('purchase-delivery-receipts.create');
            Route::post('purchase-delivery-receipts/store/{id?}', 'PurchaseDeliveryReceiptController@store')->name('purchase-delivery-receipts.store');
            Route::get('purchase-delivery-receipts/show/{id}', 'PurchaseDeliveryReceiptController@show')->name('purchase-delivery-receipts.show');
            Route::post('purchase-delivery-receipts/update/{id}', 'PurchaseDeliveryReceiptController@update')->name('purchase-delivery-receipts.update');
            Route::post('purchase-delivery-receipts/{id}/archive', 'PurchaseDeliveryReceiptController@archive')->name('purchase-delivery-receipts.archive');
            Route::post('purchase-delivery-receipts/{id}/restore', 'PurchaseDeliveryReceiptController@restore')->name('purchase-delivery-receipts.restore');
            Route::get('purchase-delivery-receipts/print/{id}', 'PurchaseDeliveryReceiptController@printPDF')->name('purchase-delivery-receipts.print');
            Route::post('purchase-delivery-receipts/{id}/cancel', 'PurchaseDeliveryReceiptController@cancel')->name('purchase-delivery-receipts.cancel');

            Route::post('purchase-delivery-receipts/approved/{id}', 'PurchaseDeliveryReceiptController@approved')->name('purchase-delivery-receipts.approved');
            Route::post('purchase-delivery-receipts/posted/{id}', 'PurchaseDeliveryReceiptController@posted')->name('purchase-delivery-receipts.posted');

            Route::post('purchase-delivery-receipts/to-invoice/{id}', 'PurchaseDeliveryReceiptController@toInvoice')->name('purchase-delivery-receipts.to-invoice');

            Route::post('purchase-delivery-receipts/fetch', 'PuchaseDeliveryReceiptFetchController@fetch')->name('purchase-delivery-receipts.fetch');
            Route::post('purchase-delivery-receipts/fetch?archived=1', 'PuchaseDeliveryReceiptFetchController@fetch')->name('purchase-delivery-receipts.fetch-archive');
            Route::post('purchase-delivery-receipts/fetch-item/{purchase_order_number?}/{id?}', 'PuchaseDeliveryReceiptFetchController@fetchView')->name('purchase-delivery-receipts.fetch-item');
            Route::post('purchase-delivery-receipts/fetch-pagination/{id}', 'PuchaseDeliveryReceiptFetchController@fetchPagePagination')->name('purchase-delivery-receipts.fetch-pagination');

        });


        //////////////////////////////
        // Purchase Delivery Receipt Lines Controller //
        //////////////////////////////
        Route::namespace('PurchaseDeliveryReceiptLines')->group(function() {

            Route::post('purchase-delivery-receipt-lines/{id}/archive', 'PurchaseDeliveryReceiptLineController@archive')->name('purchase-delivery-receipt-lines.archive');
            Route::post('purchase-delivery-receipt-lines/{id}/restore', 'PurchaseDeliveryReceiptLineController@restore')->name('purchase-delivery-receipt-lines.restore');
            Route::post('purchase-delivery-receipt-lines/{id}/approve', 'PurchaseDeliveryReceiptLineController@approve')->name('purchase-delivery-receipt-lines.approve');
            Route::post('purchase-delivery-receipt-lines/{id}/reject', 'PurchaseDeliveryReceiptLineController@reject')->name('purchase-delivery-receipt-lines.reject');

        });


        //////////////////////////////
        // Purchase Order Lines Controller //
        //////////////////////////////
        Route::namespace('PurchaseOrderLines')->group(function() {

            Route::post('purchase-order-lines/{id}/archive', 'PurchaseOrderLineController@archive')->name('purchase-order-lines.archive');
            Route::post('purchase-order-lines/{id}/restore', 'PurchaseOrderLineController@restore')->name('purchase-order-lines.restore');
            Route::post('purchase-order-lines/{id}/approve', 'PurchaseOrderLineController@approve')->name('purchase-order-lines.approve');
            Route::post('purchase-order-lines/{id}/reject', 'PurchaseOrderLineController@reject')->name('purchase-order-lines.reject');

            Route::post('purchase-order-lines/fetch', 'PurchaseOrderLineFetchController@fetch')->name('purchase-order-lines.fetch');
            Route::post('purchase-order-lines/fetch?archived=1', 'PurchaseOrderLineFetchController@fetch')->name('purchase-order-lines.fetch-archive');
            Route::post('purchase-order-lines/fetch-item/{id?}', 'PurchaseOrderLineFetchController@fetchView')->name('purchase-order-lines.fetch-item');
            Route::post('purchase-order-lines/fetch-pagination/{id}', 'PurchaseOrderLineFetchController@fetchPagePagination')->name('purchase-order-lines.fetch-pagination');

        });   

        //////////////////////////////
        // Purchase Order Return Lines Controller //
        //////////////////////////////
        Route::namespace('PurchaseOrderReturnLines')->group(function() {

            Route::post('purchase-order-return-lines/{id}/archive', 'PurchaseOrderReturnLineController@archive')->name('purchase-order-return-lines.archive');
            Route::post('purchase-order-return-lines/{id}/restore', 'PurchaseOrderReturnLineController@restore')->name('purchase-order-return-lines.restore');
            Route::post('purchase-order-return-lines/{id}/approve', 'PurchaseOrderReturnLineController@approve')->name('purchase-order-return-lines.approve');
            Route::post('purchase-order-return-lines/{id}/reject', 'PurchaseOrderReturnLineController@reject')->name('purchase-order-return-lines.reject');

            Route::post('purchase-order-return-lines/fetch', 'PurchaseOrderReturnLineFetchController@fetch')->name('purchase-order-return-lines.fetch');
            Route::post('purchase-order-return-lines/fetch?archived=1', 'PurchaseOrderReturnLineFetchController@fetch')->name('purchase-order-return-lines.fetch-archive');
            Route::post('purchase-order-return-lines/fetch-item/{id?}', 'PurchaseOrderReturnLineFetchController@fetchView')->name('purchase-order-return-lines.fetch-item');
            Route::post('purchase-order-return-lines/fetch-pagination/{id}', 'PurchaseOrderReturnLineFetchController@fetchPagePagination')->name('purchase-order-return-lines.fetch-pagination');

        });

        //////////////////////////////
        // Adjustments Controller //
        //////////////////////////////
        Route::namespace('Adjustments')->group(function() {

            Route::get('adjustments', 'AdjustmentController@index')->name('adjustments.index');
            Route::get('adjustments/create', 'AdjustmentController@create')->name('adjustments.create');
            Route::post('adjustments/store', 'AdjustmentController@store')->name('adjustments.store');
            Route::get('adjustments/show/{id}', 'AdjustmentController@show')->name('adjustments.show');
            Route::post('adjustments/update/{id}', 'AdjustmentController@update')->name('adjustments.update');
            Route::post('adjustments/{id}/archive', 'AdjustmentController@archive')->name('adjustments.archive');
            Route::post('adjustments/{id}/restore', 'AdjustmentController@restore')->name('adjustments.restore');

            Route::post('adjustments/fetch', 'AdjustmentFetchController@fetch')->name('adjustments.fetch');
            Route::post('adjustments/fetch?archived=1', 'AdjustmentFetchController@fetch')->name('adjustments.fetch-archive');
            Route::post('adjustments/fetch-item/{id?}', 'AdjustmentFetchController@fetchView')->name('adjustments.fetch-item');
            Route::post('adjustments/fetch-pagination/{id}', 'AdjustmentFetchController@fetchPagePagination')->name('adjustments.fetch-pagination');

        });

        //////////////////////////////
        // Customers Controller //
        //////////////////////////////
        Route::namespace('Customers')->group(function() {

            Route::get('customers', 'CustomerController@index')->name('customers.index');
            Route::get('customers/create', 'CustomerController@create')->name('customers.create');
            Route::post('customers/store', 'CustomerController@store')->name('customers.store');
            Route::get('customers/show/{id}', 'CustomerController@show')->name('customers.show');
            Route::post('customers/update/{id}', 'CustomerController@update')->name('customers.update');
            Route::post('customers/{id}/archive', 'CustomerController@archive')->name('customers.archive');
            Route::post('customers/{id}/restore', 'CustomerController@restore')->name('customers.restore');

            Route::post('customers/confirmation/{id}', 'CustomerController@confirmation')->name('customers.confirmation');

            Route::post('customers/fetch', 'CustomerFetchController@fetch')->name('customers.fetch');
            Route::post('customers/fetch?archived=1', 'CustomerFetchController@fetch')->name('customers.fetch-archive');
            Route::post('customers/fetch-item/{id?}', 'CustomerFetchController@fetchView')->name('customers.fetch-item');
            Route::post('customers/fetch-pagination/{id}', 'CustomerFetchController@fetchPagePagination')->name('customers.fetch-pagination');

        });

        Route::namespace('CustomerBankAccounts')->group(function() {
            Route::get('customer-bank-accounts', 'CustomerBankAccountController@index')->name('customer-bank-accounts.index');
            Route::get('customer-bank-accounts/create/{vendorid}', 'CustomerBankAccountController@create')->name('customer-bank-accounts.create');
            Route::get('customer-bank-accounts/show/{id}', 'CustomerBankAccountController@show')->name('customer-bank-accounts.show');
            Route::post('customer-bank-accounts/store', 'CustomerBankAccountController@store')->name('customer-bank-accounts.store');
            Route::post('customer-bank-accounts/update/{id}', 'CustomerBankAccountController@update')->name('customer-bank-accounts.update');

            Route::post('customer-bank-accounts/{id}/archive', 'CustomerBankAccountController@archive')->name('customer-bank-accounts.archive');
            Route::post('customer-bank-accounts/{id}/restore', 'CustomerBankAccountController@restore')->name('customer-bank-accounts.restore');

            Route::post('customer-bank-accounts/fetch', 'CustomerBankAccountFetchController@fetch')->name('customer-bank-accounts.fetch');
            Route::post('customer-bank-accounts/fetch?archived=1', 'CustomerBankAccountFetchController@fetch')->name('customer-bank-accounts.fetch-archive');
            Route::post('customer-bank-accounts/fetch-item/{id?}', 'CustomerBankAccountFetchController@fetchView')->name('customer-bank-accounts.fetch-item');
            Route::post('customer-bank-accounts/fetch-pagination/{id}', 'CustomerBankAccountFetchController@fetchPagePagination')->name('customer-bank-accounts.fetch-pagination');
        });

        //////////////////////////////
        // Sales Orders Controller //
        //////////////////////////////
        Route::namespace('SalesOrderReturns')->group(function() {

            Route::get('sales-order-returns', 'SalesOrderReturnController@index')->name('sales-order-returns.index');
            Route::get('sales-order-returns/create', 'SalesOrderReturnController@create')->name('sales-order-returns.create');
            Route::post('sales-order-returns/store', 'SalesOrderReturnController@store')->name('sales-order-returns.store');
            Route::get('sales-order-returns/show/{id}', 'SalesOrderReturnController@show')->name('sales-order-returns.show');
            Route::post('sales-order-returns/update/{id}', 'SalesOrderReturnController@update')->name('sales-order-returns.update');
            Route::post('sales-order-returns/{id}/archive', 'SalesOrderReturnController@archive')->name('sales-order-returns.archive');
            Route::post('sales-order-returns/{id}/restore', 'SalesOrderReturnController@restore')->name('sales-order-returns.restore');
            Route::get('sales-order-returns/print/{id}', 'SalesOrderReturnController@printPDF')->name('sales-order-returns.print');

            Route::post('sales-order-returns/confirmation/{id}', 'SalesOrderReturnController@confirmation')->name('sales-order-returns.confirmation');

            Route::post('sales-order-returns/fetch', 'SalesOrderReturnFetchController@fetch')->name('sales-order-returns.fetch');
            Route::post('sales-order-returns/fetch?archived=1', 'SalesOrderReturnFetchController@fetch')->name('sales-order-returns.fetch-archive');
            Route::post('sales-order-returns/fetch-item/{id?}', 'SalesOrderReturnFetchController@fetchView')->name('sales-order-returns.fetch-item');
            Route::post('sales-order-returns/fetch-pagination/{id}', 'SalesOrderReturnFetchController@fetchPagePagination')->name('sales-order-returns.fetch-pagination');

        });


        /////////////////////////////////////////
        // Sales Order Return Lines Controller //
        ////////////////////////////////////////
        Route::namespace('SalesOrderReturnLines')->group(function() {

            Route::post('sales-order-return-lines/{id}/archive', 'SalesOrderReturnLineController@archive')->name('sales-order-return-lines.archive');
            Route::post('sales-order-return-lines/{id}/restore', 'SalesOrderReturnLineController@restore')->name('sales-order-return-lines.restore');
            Route::post('sales-order-return-lines/{id}/approve', 'SalesOrderReturnLineController@approve')->name('sales-order-return-lines.approve');
            Route::post('sales-order-return-lines/{id}/reject', 'SalesOrderReturnLineController@reject')->name('sales-order-return-lines.reject');

            Route::post('sales-order-return-lines/fetch', 'SalesOrderReturnLineFetchController@fetch')->name('sales-order-return-lines.fetch');
            Route::post('sales-order-return-lines/fetch?archived=1', 'SalesOrderReturnLineFetchController@fetch')->name('sales-order-return-lines.fetch-archive');
            Route::post('sales-order-return-lines/fetch-item/{id?}', 'SalesOrderReturnLineFetchController@fetchView')->name('sales-order-return-lines.fetch-item');
            Route::post('sales-order-return-lines/fetch-pagination/{id}', 'SalesOrderReturnLineFetchController@fetchPagePagination')->name('sales-order-return-lines.fetch-pagination');

        });


        //////////////////////////////
        // Sales Orders Controller //
        //////////////////////////////
        Route::namespace('SalesOrders')->group(function() {

            Route::get('sales-orders', 'SalesOrderController@index')->name('sales-orders.index');
            Route::get('sales-orders/create', 'SalesOrderController@create')->name('sales-orders.create');
            Route::post('sales-orders/store', 'SalesOrderController@store')->name('sales-orders.store');
            Route::get('sales-orders/show/{id}', 'SalesOrderController@show')->name('sales-orders.show');
            Route::post('sales-orders/update/{id}', 'SalesOrderController@update')->name('sales-orders.update');
            Route::post('sales-orders/{id}/archive', 'SalesOrderController@archive')->name('sales-orders.archive');
            Route::post('sales-orders/{id}/restore', 'SalesOrderController@restore')->name('sales-orders.restore');
            Route::get('sales-orders/print/{id}', 'SalesOrderController@printPDF')->name('sales-orders.print');

            Route::post('sales-orders/confirmation/{id}', 'SalesOrderController@confirmation')->name('sales-orders.confirmation');

            Route::post('sales-orders/fetch', 'SalesOrderFetchController@fetch')->name('sales-orders.fetch');
            Route::post('sales-orders/fetch?archived=1', 'SalesOrderFetchController@fetch')->name('sales-orders.fetch-archive');
            Route::post('sales-orders/fetch-item/{id?}', 'SalesOrderFetchController@fetchView')->name('sales-orders.fetch-item');
            Route::post('sales-orders/fetch-pagination/{id}', 'SalesOrderFetchController@fetchPagePagination')->name('sales-orders.fetch-pagination');

        });


        //////////////////////////////
        // Sales Order Lines Controller //
        //////////////////////////////
        Route::namespace('SalesOrderLines')->group(function() {

            Route::post('sales-order-lines/{id}/archive', 'SalesOrderLineController@archive')->name('sales-order-lines.archive');
            Route::post('sales-order-lines/{id}/restore', 'SalesOrderLineController@restore')->name('sales-order-lines.restore');
            Route::post('sales-order-lines/{id}/approve', 'SalesOrderLineController@approve')->name('sales-order-lines.approve');
            Route::post('sales-order-lines/{id}/reject', 'SalesOrderLineController@reject')->name('sales-order-lines.reject');

            Route::post('sales-order-lines/fetch', 'SalesOrderLineFetchController@fetch')->name('sales-order-lines.fetch');
            Route::post('sales-order-lines/fetch?archived=1', 'SalesOrderLineFetchController@fetch')->name('sales-order-lines.fetch-archive');
            Route::post('sales-order-lines/fetch-item/{id?}', 'SalesOrderLineFetchController@fetchView')->name('sales-order-lines.fetch-item');
            Route::post('sales-order-lines/fetch-pagination/{id}', 'SalesOrderLineFetchController@fetchPagePagination')->name('sales-order-lines.fetch-pagination');

        });


        //////////////////////////////
        // Customer Invoices Controller //
        //////////////////////////////
        Route::namespace('CustomerInvoices')->group(function() {

            Route::get('customer-invoices', 'CustomerInvoiceController@index')->name('customer-invoices.index');
            Route::get('customer-invoices/create/{sales_order_number?}', 'CustomerInvoiceController@create')->name('customer-invoices.create');
            Route::post('customer-invoices/store/{sales_order_number?}', 'CustomerInvoiceController@store')->name('customer-invoices.store');
            Route::get('customer-invoices/show/{id}', 'CustomerInvoiceController@show')->name('customer-invoices.show');
            Route::post('customer-invoices/update/{id}', 'CustomerInvoiceController@update')->name('customer-invoices.update');
            Route::post('customer-invoices/{id}/archive', 'CustomerInvoiceController@archive')->name('customer-invoices.archive');
            Route::post('customer-invoices/{id}/restore', 'CustomerInvoiceController@restore')->name('customer-invoices.restore');
            Route::get('customer-invoices/print/{id}', 'CustomerInvoiceController@printPDF')->name('customer-invoices.print');

            Route::post('customer-invoices/confirmation/{id}', 'CustomerInvoiceController@confirmation')->name('customer-invoices.confirmation');

            Route::post('customer-invoices/posted/{id}', 'CustomerInvoiceController@post')->name('customer-invoices.posted');

            Route::post('customer-invoices/fetch', 'CustomerInvoiceFetchController@fetch')->name('customer-invoices.fetch');
            Route::post('customer-invoices/fetch?archived=1', 'CustomerInvoiceFetchController@fetch')->name('customer-invoices.fetch-archive');
            Route::post('customer-invoices/fetch-item/{sales_orders?}/{id?}', 'CustomerInvoiceFetchController@fetchView')->name('customer-invoices.fetch-item');
            Route::post('customer-invoices/fetch-pagination/{id}', 'CustomerInvoiceFetchController@fetchPagePagination')->name('customer-invoices.fetch-pagination');

            Route::get('customer-invoices/report', 'CustomerInvoiceController@customerInvoiceAging')->name('customer-invoice-aging.index');
            Route::post('customer-invoices/aging/fetch', 'CustomerInvoiceFetchController@fetch')->name('customer-invoices-aging.fetch');

        });

        //////////////////////////////
        // Customer Invoice Lines Controller //
        //////////////////////////////
        Route::namespace('CustomerInvoiceLines')->group(function() {

            Route::post('customer-invoice-lines/{id}/archive', 'CustomerInvoiceLineController@archive')->name('customer-invoice-lines.archive');
            Route::post('customer-invoice-lines/{id}/restore', 'CustomerInvoiceLineController@restore')->name('customer-invoice-lines.restore');
            Route::post('customer-invoice-lines/{id}/approve', 'CustomerInvoiceLineController@approve')->name('customer-invoice-lines.approve');
            Route::post('customer-invoice-lines/{id}/reject', 'CustomerInvoiceLineController@reject')->name('customer-invoice-lines.reject');

        });


        //////////////////////////////
        // Customer Invoices Controller //
        //////////////////////////////
        Route::namespace('SalesDeliveryReceipts')->group(function() {

            Route::get('sales-delivery-receipts', 'SalesDeliveryReceiptController@index')->name('sales-delivery-receipts.index');
            Route::get('sales-delivery-receipts/create/{customer_invoice?}', 'SalesDeliveryReceiptController@create')->name('sales-delivery-receipts.create');
            Route::post('sales-delivery-receipts/store/{customer_invoice?}', 'SalesDeliveryReceiptController@store')->name('sales-delivery-receipts.store');
            Route::get('sales-delivery-receipts/show/{id}', 'SalesDeliveryReceiptController@show')->name('sales-delivery-receipts.show');
            Route::post('sales-delivery-receipts/update/{id}', 'SalesDeliveryReceiptController@update')->name('sales-delivery-receipts.update');
            Route::post('sales-delivery-receipts/{id}/archive', 'SalesDeliveryReceiptController@archive')->name('sales-delivery-receipts.archive');
            Route::post('sales-delivery-receipts/{id}/restore', 'SalesDeliveryReceiptController@restore')->name('sales-delivery-receipts.restore');
            Route::get('sales-delivery-receipts/print/{id}', 'SalesDeliveryReceiptController@printPDF')->name('sales-delivery-receipts.print');

            Route::post('sales-delivery-receipts/confirmation/{id}', 'SalesDeliveryReceiptController@confirmation')->name('sales-delivery-receipts.confirmation');

            Route::post('sales-delivery-receipts/posted/{id}', 'SalesDeliveryReceiptController@post')->name('sales-delivery-receipts.posted');

            Route::post('sales-delivery-receipts/fetch', 'SalesDeliveryReceiptFetchController@fetch')->name('sales-delivery-receipts.fetch');
            Route::post('sales-delivery-receipts/fetch?archived=1', 'SalesDeliveryReceiptFetchController@fetch')->name('sales-delivery-receipts.fetch-archive');
            Route::post('sales-delivery-receipts/fetch-item/{sales_orders?}/{id?}', 'SalesDeliveryReceiptFetchController@fetchView')->name('sales-delivery-receipts.fetch-item');
            Route::post('sales-delivery-receipts/fetch-pagination/{id}', 'SalesDeliveryReceiptFetchController@fetchPagePagination')->name('sales-delivery-receipts.fetch-pagination');

        });

        //////////////////////////////
        // Customer Invoice Lines Controller //
        //////////////////////////////
        Route::namespace('CustomerInvoiceLines')->group(function() {

            Route::post('customer-invoice-lines/{id}/archive', 'CustomerInvoiceLineController@archive')->name('customer-invoice-lines.archive');
            Route::post('customer-invoice-lines/{id}/restore', 'CustomerInvoiceLineController@restore')->name('customer-invoice-lines.restore');
            Route::post('customer-invoice-lines/{id}/approve', 'CustomerInvoiceLineController@approve')->name('customer-invoice-lines.approve');
            Route::post('customer-invoice-lines/{id}/reject', 'CustomerInvoiceLineController@reject')->name('customer-invoice-lines.reject');

        });

        //////////////////////////////////////////
        // Customer Payment Fee Setup Controller//
        /////////////////////////////////////////
        Route::namespace('CustomerPaymentFeeSetups')->group(function() {

            Route::get('customer-payment-fee-setups', 'CustomerPaymentFeeSetupController@index')->name('customer-payment-fee-setups.index');
            Route::get('customer-payment-fee-setups/create', 'CustomerPaymentFeeSetupController@create')->name('customer-payment-fee-setups.create');
            Route::post('customer-payment-fee-setups/store', 'CustomerPaymentFeeSetupController@store')->name('customer-payment-fee-setups.store');
            Route::get('customer-payment-fee-setups/show/{id}', 'CustomerPaymentFeeSetupController@show')->name('customer-payment-fee-setups.show');
            Route::post('customer-payment-fee-setups/update/{id}', 'CustomerPaymentFeeSetupController@update')->name('customer-payment-fee-setups.update');
            Route::post('customer-payment-fee-setups/{id}/archive', 'CustomerPaymentFeeSetupController@archive')->name('customer-payment-fee-setups.archive');
            Route::post('customer-payment-fee-setups/{id}/restore', 'CustomerPaymentFeeSetupController@restore')->name('customer-payment-fee-setups.restore');

            Route::post('customer-payment-fee-setups/fetch', 'CustomerPaymentFeeSetupFetchController@fetch')->name('customer-payment-fee-setups.fetch');
            Route::post('customer-payment-fee-setups/fetch?archived=1', 'CustomerPaymentFeeSetupFetchController@fetch')->name('customer-payment-fee-setups.fetch-archive');
            Route::post('customer-payment-fee-setups/fetch-item/{id?}', 'CustomerPaymentFeeSetupFetchController@fetchView')->name('customer-payment-fee-setups.fetch-item');
            Route::post('customer-payment-fee-setups/fetch-pagination/{id}', 'CustomerPaymentFeeSetupFetchController@fetchPagePagination')->name('customer-payment-fee-setups.fetch-pagination');

        });

        
        //////////////////////////////
        // Customer Payments //
        //////////////////////////////
        Route::namespace('CustomerPayments')->group(function() {
            Route::get('customer-payments', 'CustomerPaymentController@index')->name('customer-payments.index');
            Route::get('customer-payments/create/{customer_invoice?}', 'CustomerPaymentController@create')->name('customer-payments.create');
            Route::post('customer-payments/store', 'CustomerPaymentController@store')->name('customer-payments.store');
            Route::get('customer-payments/show/{id}', 'CustomerPaymentController@show')->name('customer-payments.show');
            Route::post('customer-payments/update/{id}', 'CustomerPaymentController@update')->name('customer-payments.update');
            Route::post('customer-payments/{id}/archive', 'CustomerPaymentController@archive')->name('customer-payments.archive');
            Route::post('customer-payments/{id}/restore', 'CustomerPaymentController@restore')->name('customer-payments.restore');

            Route::post('customer-payments/approved/{id}', 'CustomerPaymentController@approved')->name('customer-payments.approved');
            Route::post('customer-payments/posted/{id}', 'CustomerPaymentController@posted')->name('customer-payments.posted');

            Route::post('customer-payments/fetch', 'CustomerPaymentFetchController@fetch')->name('customer-payments.fetch');
            Route::post('customer-payments/fetch?archived=1', 'CustomerPaymentFetchController@fetch')->name('customer-payments.fetch-archive');
            Route::post('customer-payments/fetch-item/{id?}', 'CustomerPaymentFetchController@fetchView')->name('customer-payments.fetch-item');
            Route::post('customer-payments/fetch-pagination/{id}', 'CustomerPaymentFetchController@fetchPagePagination')->name('customer-payments.fetch-pagination');
        });

        //////////////////////////////
        // Vendor Payments //
        //////////////////////////////
        Route::namespace('VendorPayments')->group(function() {
            Route::get('vendor-payments', 'VendorPaymentController@index')->name('vendor-payments.index');
            Route::get('vendor-payments/create/{vendor_invoice?}', 'VendorPaymentController@create')->name('vendor-payments.create');
            Route::post('vendor-payments/store', 'VendorPaymentController@store')->name('vendor-payments.store');
            Route::get('vendor-payments/show/{id}', 'VendorPaymentController@show')->name('vendor-payments.show');
            Route::post('vendor-payments/update/{id}', 'VendorPaymentController@update')->name('vendor-payments.update');
            Route::post('vendor-payments/{id}/archive', 'VendorPaymentController@archive')->name('vendor-payments.archive');
            Route::post('vendor-payments/{id}/restore', 'VendorPaymentController@restore')->name('vendor-payments.restore');
            Route::post('vendor-payments/{id}/cancel', 'VendorPaymentController@cancel')->name('vendor-payments.cancel');

            
            Route::post('vendor-payments/approved/{id}', 'VendorPaymentController@approved')->name('vendor-payments.approved');
            Route::post('vendor-payments/posted/{id}', 'VendorPaymentController@posted')->name('vendor-payments.posted');

            Route::post('vendor-payments/fetch', 'VendorPaymentFetchController@fetch')->name('vendor-payments.fetch');
            Route::post('vendor-payments/fetch?archived=1', 'VendorPaymentFetchController@fetch')->name('vendor-payments.fetch-archive');
            Route::post('vendor-payments/fetch-item/{id?}', 'VendorPaymentFetchController@fetchView')->name('vendor-payments.fetch-item');
            Route::post('vendor-payments/fetch-pagination/{id}', 'VendorPaymentFetchController@fetchPagePagination')->name('vendor-payments.fetch-pagination');
        });

        //////////////////////////////
        // Customer Payment Lines   //
        //////////////////////////////
        Route::namespace('CustomerPaymentLines')->group(function() {
            Route::post('customer-payment-lines/{id}/archive', 'CustomerPaymentLineController@archive')->name('customer-payment-lines.archive');
            Route::post('customer-payment-lines/{id}/restore', 'CustomerPaymentLineController@restore')->name('customer-payment-lines.restore');
            Route::post('customer-payment-lines/{id}/approve', 'CustomerPaymentLineController@approve')->name('customer-payment-lines.approve');
            Route::post('customer-payment-lines/{id}/reject', 'CustomerPaymentLineController@reject')->name('customer-payment-lines.reject');
        });


        //////////////////////////////
        // Invoice Approval Journals Controller //
        //////////////////////////////
        Route::namespace('InvoiceApprovalJournals')->group(function() {

            Route::get('po-invoice-approval-journals', 'InvoiceApprovalJournalController@index')->name('po-invoice-approval-journals.index');
            Route::get('po-invoice-approval-journals/create', 'InvoiceApprovalJournalController@create')->name('po-invoice-approval-journals.create');
            // Route::get('po-invoice-approval-journals/create/{vendor_invoice_number?}', 'InvoiceApprovalJournalController@create')->name('po-invoice-approval-journals.create');
            // Route::post('po-invoice-approval-journals/store/{vendor_invoice_number?}', 'InvoiceApprovalJournalController@store')->name('po-invoice-approval-journals.store');
            Route::post('po-invoice-approval-journals/store', 'InvoiceApprovalJournalController@store')->name('po-invoice-approval-journals.store');
            Route::get('po-invoice-approval-journals/show/{id}', 'InvoiceApprovalJournalController@show')->name('po-invoice-approval-journals.show');
            Route::get('po-invoice-approval-journals/edit/{id}', 'InvoiceApprovalJournalController@edit')->name('po-invoice-approval-journals.edit');
            Route::post('po-invoice-approval-journals/update/{id}', 'InvoiceApprovalJournalController@update')->name('po-invoice-approval-journals.update');
            Route::post('po-invoice-approval-journals/{id}/archive', 'InvoiceApprovalJournalController@archive')->name('po-invoice-approval-journals.archive');
            Route::post('po-invoice-approval-journals/{id}/restore', 'InvoiceApprovalJournalController@restore')->name('po-invoice-approval-journals.restore');
            Route::post('po-invoice-approval-journals/{id}/post', 'InvoiceApprovalJournalController@post')->name('po-invoice-approval-journals.post');

            // Route::post('po-invoice-approval-journals/fetch', 'InvoiceApprovalJournalController@fetch')->name('po-invoice-approval-journals.fetch');

            Route::post('po-invoice-approval-journals/voucher/create/{id}', 'InvoiceApprovalJournalController@createVouchers')->name('po-invoice-approval-journals.voucher-create');
            Route::post('po-invoice-approval-journals/voucher/update/{id}', 'InvoiceApprovalJournalController@updateVoucher')->name('po-invoice-approval-journals.voucher-update');
            
            Route::post('po-invoice-approval-journals/voucher/update-status', 'InvoiceApprovalJournalController@updateStatusVoucher')->name('po-invoice-approval-journals.voucher-status-update');
            Route::post('po-invoice-approval-journals/header/update-status', 'InvoiceApprovalJournalController@updateStatusHeader')->name('po-invoice-approval-journals.header-status-update');

            Route::post('po-invoice-approval-journals/header-validate/{id}', 'InvoiceApprovalJournalController@validateJournal')->name('po-invoice-approval-journals.validate');
            Route::post('po-invoice-approval-journals/validate-voucher/{id}', 'InvoiceApprovalJournalController@validateVoucher')->name('po-invoice-approval-journals.validate-voucher');

            Route::post('po-invoice-approval-journals/fetch', 'InvoiceApprovalJournalFetchController@fetch')->name('po-invoice-approval-journals.fetch');
            Route::post('po-invoice-approval-journals/fetch?archived=1', 'InvoiceApprovalJournalFetchController@fetch')->name('po-invoice-approval-journals.fetch-archive');
            // // Route::post('po-invoice-approval-journals/fetch-item/{vendor_invoice_number}/{id?}', 'InvoiceApprovalJournalFetchController@fetchView')->name('po-invoice-approval-journals.fetch-item');
            Route::post('po-invoice-approval-journals/fetch-item/{id?}', 'InvoiceApprovalJournalFetchController@fetchView')->name('po-invoice-approval-journals.fetch-item');
            Route::post('po-invoice-approval-journals/fetch-pagination/{id}', 'InvoiceApprovalJournalFetchController@fetchPagePagination')->name('po-invoice-approval-journals.fetch-pagination');

            Route::post('po-invoice-approval-journals/fetch-vouchers', 'InvoiceApprovalJournalVoucherFetchController@fetch')->name('po-invoice-approval-journals.fetch-vouchers');
            Route::post('po-invoice-approval-journals/fetch-vouchers?archived=1', 'InvoiceApprovalJournalVoucherFetchController@fetch')->name('po-invoice-approval-journals.fetch-vouchers-archive');
            Route::post('po-invoice-approval-journals/fetch-vouchers-pagination/{id}', 'InvoiceApprovalJournalVoucherFetchController@fetchPagePagination')->name('po-invoice-approval-journals.fetch-vouchers-pagination');

        });



        ///////////////////////////////////////////////
        // Purchase Order Return Journals Controller //
        //////////////////////////////////////////////
        Route::namespace('PurchaseReturnJournals')->group(function() {

            Route::get('purchase-return-journals', 'PurchaseReturnJournalController@index')->name('purchase-return-journals.index');
            Route::get('purchase-return-journals/create', 'PurchaseReturnJournalController@create')->name('purchase-return-journals.create');
            // Route::get('purchase-return-journals/create/{vendor_invoice_number?}', 'PurchaseReturnJournalController@create')->name('purchase-return-journals.create');
            // Route::post('purchase-return-journals/store/{vendor_invoice_number?}', 'PurchaseReturnJournalController@store')->name('purchase-return-journals.store');
            Route::post('purchase-return-journals/store', 'PurchaseReturnJournalController@store')->name('purchase-return-journals.store');
            Route::get('purchase-return-journals/show/{id}', 'PurchaseReturnJournalController@show')->name('purchase-return-journals.show');
            Route::get('purchase-return-journals/edit/{id}', 'PurchaseReturnJournalController@edit')->name('purchase-return-journals.edit');
            Route::post('purchase-return-journals/update/{id}', 'PurchaseReturnJournalController@update')->name('purchase-return-journals.update');
            Route::post('purchase-return-journals/{id}/archive', 'PurchaseReturnJournalController@archive')->name('purchase-return-journals.archive');
            Route::post('purchase-return-journals/{id}/restore', 'PurchaseReturnJournalController@restore')->name('purchase-return-journals.restore');
            Route::post('purchase-return-journals/{id}/post', 'PurchaseReturnJournalController@post')->name('purchase-return-journals.post');

            // Route::post('purchase-return-journals/fetch', 'PurchaseReturnJournalController@fetch')->name('purchase-return-journals.fetch');

            Route::post('purchase-return-journals/voucher/create/{id}', 'PurchaseReturnJournalController@createVouchers')->name('purchase-return-journals.voucher-create');
            Route::post('purchase-return-journals/voucher/update/{id}', 'PurchaseReturnJournalController@updateVoucher')->name('purchase-return-journals.voucher-update');
            
            Route::post('purchase-return-journals/voucher/update-status', 'PurchaseReturnJournalController@updateStatusVoucher')->name('purchase-return-journals.voucher-status-update');
            Route::post('purchase-return-journals/header/update-status', 'PurchaseReturnJournalController@updateStatusHeader')->name('purchase-return-journals.header-status-update');

            Route::post('purchase-return-journals/header-validate/{id}', 'PurchaseReturnJournalController@validateJournal')->name('purchase-return-journals.validate');
            Route::post('purchase-return-journals/validate-voucher/{id}', 'PurchaseReturnJournalController@validateVoucher')->name('purchase-return-journals.validate-voucher');

            Route::post('purchase-return-journals/fetch', 'PurchaseReturnJournalFetchController@fetch')->name('purchase-return-journals.fetch');
            Route::post('purchase-return-journals/fetch?archived=1', 'PurchaseReturnJournalFetchController@fetch')->name('purchase-return-journals.fetch-archive');
            // // Route::post('purchase-return-journals/fetch-item/{vendor_invoice_number}/{id?}', 'PurchaseReturnJournalFetchController@fetchView')->name('purchase-return-journals.fetch-item');
            Route::post('purchase-return-journals/fetch-item/{id?}', 'PurchaseReturnJournalFetchController@fetchView')->name('purchase-return-journals.fetch-item');
            Route::post('purchase-return-journals/fetch-pagination/{id}', 'PurchaseReturnJournalFetchController@fetchPagePagination')->name('purchase-return-journals.fetch-pagination');

            Route::post('purchase-return-journals/fetch-vouchers', 'PurchaseReturnJournalVoucherFetchController@fetch')->name('purchase-return-journals.fetch-vouchers');
            Route::post('purchase-return-journals/fetch-vouchers?archived=1', 'PurchaseReturnJournalVoucherFetchController@fetch')->name('purchase-return-journals.fetch-vouchers-archive');
            Route::post('purchase-return-journals/fetch-vouchers-pagination/{id}', 'PurchaseReturnJournalVoucherFetchController@fetchPagePagination')->name('purchase-return-journals.fetch-vouchers-pagination');

        });

        
        ///////////////////////////////////////////////
        // Sales Order Return Journals Controller //
        //////////////////////////////////////////////
        Route::namespace('SalesReturnJournals')->group(function() {

            Route::get('sales-return-journals', 'SalesReturnJournalController@index')->name('sales-return-journals.index');
            Route::get('sales-return-journals/create', 'SalesReturnJournalController@create')->name('sales-return-journals.create');
            // Route::get('sales-return-journals/create/{vendor_invoice_number?}', 'SalesReturnJournalController@create')->name('sales-return-journals.create');
            // Route::post('sales-return-journals/store/{vendor_invoice_number?}', 'SalesReturnJournalController@store')->name('sales-return-journals.store');
            Route::post('sales-return-journals/store', 'SalesReturnJournalController@store')->name('sales-return-journals.store');
            Route::get('sales-return-journals/show/{id}', 'SalesReturnJournalController@show')->name('sales-return-journals.show');
            Route::get('sales-return-journals/edit/{id}', 'SalesReturnJournalController@edit')->name('sales-return-journals.edit');
            Route::post('sales-return-journals/update/{id}', 'SalesReturnJournalController@update')->name('sales-return-journals.update');
            Route::post('sales-return-journals/{id}/archive', 'SalesReturnJournalController@archive')->name('sales-return-journals.archive');
            Route::post('sales-return-journals/{id}/restore', 'SalesReturnJournalController@restore')->name('sales-return-journals.restore');
            Route::post('sales-return-journals/{id}/post', 'SalesReturnJournalController@post')->name('sales-return-journals.post');

            // Route::post('sales-return-journals/fetch', 'SalesReturnJournalController@fetch')->name('sales-return-journals.fetch');

            Route::post('sales-return-journals/voucher/create/{id}', 'SalesReturnJournalController@createVouchers')->name('sales-return-journals.voucher-create');
            Route::post('sales-return-journals/voucher/update/{id}', 'SalesReturnJournalController@updateVoucher')->name('sales-return-journals.voucher-update');
            
            Route::post('sales-return-journals/voucher/update-status', 'SalesReturnJournalController@updateStatusVoucher')->name('sales-return-journals.voucher-status-update');
            Route::post('sales-return-journals/header/update-status', 'SalesReturnJournalController@updateStatusHeader')->name('sales-return-journals.header-status-update');

            Route::post('sales-return-journals/header-validate/{id}', 'SalesReturnJournalController@validateJournal')->name('sales-return-journals.validate');
            Route::post('sales-return-journals/validate-voucher/{id}', 'SalesReturnJournalController@validateVoucher')->name('sales-return-journals.validate-voucher');

            Route::post('sales-return-journals/fetch', 'SalesReturnJournalFetchController@fetch')->name('sales-return-journals.fetch');
            Route::post('sales-return-journals/fetch?archived=1', 'SalesReturnJournalFetchController@fetch')->name('sales-return-journals.fetch-archive');
            // // Route::post('sales-return-journals/fetch-item/{vendor_invoice_number}/{id?}', 'SalesReturnJournalFetchController@fetchView')->name('sales-return-journals.fetch-item');
            Route::post('sales-return-journals/fetch-item/{id?}', 'SalesReturnJournalFetchController@fetchView')->name('sales-return-journals.fetch-item');
            Route::post('sales-return-journals/fetch-pagination/{id}', 'SalesReturnJournalFetchController@fetchPagePagination')->name('sales-return-journals.fetch-pagination');

            // Route::post('sales-return-journals/fetch-vouchers', 'SalesReturnJournalVoucherFetchController@fetch')->name('sales-return-journals.fetch-vouchers');
            // Route::post('sales-return-journals/fetch-vouchers?archived=1', 'SalesReturnJournalVoucherFetchController@fetch')->name('sales-return-journals.fetch-vouchers-archive');
            // Route::post('sales-return-journals/fetch-vouchers-pagination/{id}', 'SalesReturnJournalVoucherFetchController@fetchPagePagination')->name('sales-return-journals.fetch-vouchers-pagination');

        });

        //////////////////////////////
        // Customer Invoice Journals Controller //
        //////////////////////////////
        Route::namespace('CustomerInvoiceJournals')->group(function() {

            Route::get('so-invoice-approval-journals', 'CustomerInvoiceJournalController@index')->name('so-invoice-approval-journals.index');
            Route::get('so-invoice-approval-journals/create', 'CustomerInvoiceJournalController@create')->name('so-invoice-approval-journals.create');
            Route::post('so-invoice-approval-journals/store/', 'CustomerInvoiceJournalController@store')->name('so-invoice-approval-journals.store');
            Route::get('so-invoice-approval-journals/show/{id}', 'CustomerInvoiceJournalController@show')->name('so-invoice-approval-journals.show');
            Route::get('so-invoice-approval-journals/edit/{id}', 'CustomerInvoiceJournalController@edit')->name('so-invoice-approval-journals.edit');
            Route::post('so-invoice-approval-journals/update/{id}', 'CustomerInvoiceJournalController@update')->name('so-invoice-approval-journals.update');
            Route::post('so-invoice-approval-journals/{id}/archive', 'CustomerInvoiceJournalController@archive')->name('so-invoice-approval-journals.archive');
            Route::post('so-invoice-approval-journals/{id}/restore', 'CustomerInvoiceJournalController@restore')->name('so-invoice-approval-journals.restore');
            Route::post('so-invoice-approval-journals/{id}/post', 'CustomerInvoiceJournalController@post')->name('so-invoice-approval-journals.post');

            // Route::post('so-invoice-approval-journals/fetch', 'CustomerInvoiceJournalController@fetch')->name('so-invoice-approval-journals.fetch');
            Route::post('so-invoice-approval-journals/voucher/create/{id}', 'CustomerInvoiceJournalController@createVouchers')->name('so-invoice-approval-journals.voucher-create');
            Route::post('so-invoice-approval-journals/voucher/update/{id}', 'CustomerInvoiceJournalController@updateVoucher')->name('so-invoice-approval-journals.voucher-update');

            Route::post('so-invoice-approval-journals/voucher/update-status', 'CustomerInvoiceJournalController@updateStatusVoucher')->name('so-invoice-approval-journals.voucher-status-update');
            Route::post('so-invoice-approval-journals/header/update-status', 'CustomerInvoiceJournalController@updateStatusHeader')->name('so-invoice-approval-journals.header-status-update');

            Route::post('so-invoice-approval-journals/header-validate/{id}', 'CustomerInvoiceJournalController@validateJournal')->name('so-invoice-approval-journals.validate');
            Route::post('so-invoice-approval-journals/validate-voucher/{id}', 'CustomerInvoiceJournalController@validateVoucher')->name('so-invoice-approval-journals.validate-voucher');

            Route::post('so-invoice-approval-journals/fetch', 'CustomerInvoiceJournalFetchController@fetch')->name('so-invoice-approval-journals.fetch');
            Route::post('so-invoice-approval-journals/fetch?archived=1', 'CustomerInvoiceJournalFetchController@fetch')->name('so-invoice-approval-journals.fetch-archive');
            Route::post('so-invoice-approval-journals/fetch-item/{id?}', 'CustomerInvoiceJournalFetchController@fetchView')->name('so-invoice-approval-journals.fetch-item');
            Route::post('so-invoice-approval-journals/fetch-pagination/{id}', 'CustomerInvoiceJournalFetchController@fetchPagePagination')->name('so-invoice-approval-journals.fetch-pagination');

            Route::post('so-invoice-approval-journals/fetch-vouchers', 'CustomerInvoiceVoucherJournalFetchController@fetch')->name('so-invoice-approval-journals.fetch-vouchers');
            Route::post('so-invoice-approval-journals/fetch-vouchers?archived=1', 'CustomerInvoiceVoucherJournalFetchController@fetch')->name('so-invoice-approval-journals.fetch-vouchers-archive');
            Route::post('so-invoice-approval-journals/fetch-vouchers-pagination/{id}', 'CustomerInvoiceVoucherJournalFetchController@fetchPagePagination')->name('so-invoice-approval-journals.fetch-vouchers-pagination');
        });


        //////////////////////////////////////////
        // Customer Payment Journals Controller //
        /////////////////////////////////////////
        Route::namespace('CustomerPaymentJournals')->group(function() {

            Route::get('customer-payment-journals', 'CustomerPaymentJournalController@index')->name('customer-payment-journals.index');
            Route::get('customer-payment-journals/create', 'CustomerPaymentJournalController@create')->name('customer-payment-journals.create');
            Route::post('customer-payment-journals/store/', 'CustomerPaymentJournalController@store')->name('customer-payment-journals.store');
            Route::get('customer-payment-journals/show/{id}', 'CustomerPaymentJournalController@show')->name('customer-payment-journals.show');
            Route::get('customer-payment-journals/edit/{id}', 'CustomerPaymentJournalController@edit')->name('customer-payment-journals.edit');
            Route::get('customer-payment-journals/header/show/{id}', 'CustomerPaymentJournalController@showUpdate')->name('customer-payment-journals.header-show');
            Route::post('customer-payment-journals/update/{id}', 'CustomerPaymentJournalController@update')->name('customer-payment-journals.update');
            Route::post('customer-payment-journals/{id}/archive', 'CustomerPaymentJournalController@archive')->name('customer-payment-journals.archive');
            Route::post('customer-payment-journals/{id}/restore', 'CustomerPaymentJournalController@restore')->name('customer-payment-journals.restore');
            Route::post('customer-payment-journals/{id}/post', 'CustomerPaymentJournalController@post')->name('customer-payment-journals.post');

            // Route::post('customer-payment-journals/fetch', 'CustomerPaymentJournalController@fetch')->name('customer-payment-journals.fetch');
            Route::post('customer-payment-journals/voucher/create/{id}', 'CustomerPaymentJournalController@createVouchers')->name('customer-payment-journals.voucher-create');
            Route::post('customer-payment-journals/voucher/update/{id}', 'CustomerPaymentJournalController@updateVoucher')->name('customer-payment-journals.voucher-update');

            Route::post('customer-payment-journals/voucher/update-status', 'CustomerPaymentJournalController@updateStatusVoucher')->name('customer-payment-journals.voucher-status-update');
            Route::post('customer-payment-journals/header/update-status', 'CustomerPaymentJournalController@updateStatusHeader')->name('customer-payment-journals.header-status-update');

            Route::post('customer-payment-journals/header-validate/{id}', 'CustomerPaymentJournalController@validateJournal')->name('customer-payment-journals.validate');
            Route::post('customer-payment-journals/validate-voucher', 'CustomerPaymentJournalController@validateVoucher')->name('customer-payment-journals.validate-voucher');

            Route::post('customer-payment-journals/fetch', 'CustomerPaymentJournalFetchController@fetch')->name('customer-payment-journals.fetch');
            Route::post('customer-payment-journals/fetch?archived=1', 'CustomerPaymentJournalFetchController@fetch')->name('customer-payment-journals.fetch-archive');
            Route::post('customer-payment-journals/fetch-item/{id?}', 'CustomerPaymentJournalFetchController@fetchView')->name('customer-payment-journals.fetch-item');
            Route::post('customer-payment-journals/fetch-pagination/{id}', 'CustomerPaymentJournalFetchController@fetchPagePagination')->name('customer-payment-journals.fetch-pagination');

            Route::post('customer-payment-journals/fetch-vouchers', 'CustomerPaymentJournalVoucherFetchController@fetch')->name('customer-payment-journals.fetch-vouchers');
            Route::post('customer-payment-journals/fetch-vouchers?archived=1', 'CustomerPaymentJournalVoucherFetchController@fetch')->name('customer-payment-journals.fetch-vouchers-archive');
            Route::post('customer-payment-journals/fetch-vouchers-pagination/{id}', 'CustomerPaymentJournalVoucherFetchController@fetchPagePagination')->name('customer-payment-journals.fetch-vouchers-pagination');

        });

        ////////////////////////////////////////
        // Vendor Payment Journals Controller //
        ///////////////////////////////////////
        Route::namespace('VendorPaymentJournals')->group(function() {

            Route::get('vendor-payment-journals', 'VendorPaymentJournalController@index')->name('vendor-payment-journals.index');
            Route::get('vendor-payment-journals/create/', 'VendorPaymentJournalController@create')->name('vendor-payment-journals.create');
            Route::post('vendor-payment-journals/store/', 'VendorPaymentJournalController@store')->name('vendor-payment-journals.store');
            Route::get('vendor-payment-journals/show/{id}', 'VendorPaymentJournalController@show')->name('vendor-payment-journals.show');
            Route::get('vendor-payment-journals/edit/{id}', 'VendorPaymentJournalController@edit')->name('vendor-payment-journals.edit');
            Route::post('vendor-payment-journals/update/{id}', 'VendorPaymentJournalController@update')->name('vendor-payment-journals.update');
            Route::post('vendor-payment-journals/{id}/archive', 'VendorPaymentJournalController@archive')->name('vendor-payment-journals.archive');
            Route::post('vendor-payment-journals/{id}/restore', 'VendorPaymentJournalController@restore')->name('vendor-payment-journals.restore');
            Route::post('vendor-payment-journals/{id}/post', 'VendorPaymentJournalController@post')->name('vendor-payment-journals.post');

            // Route::post('vendor-payment-journals/fetch', 'VendorPaymentJournalController@fetch')->name('vendor-payment-journals.fetch');
            Route::post('vendor-payment-journals/voucher/create/{id}', 'VendorPaymentJournalController@createVouchers')->name('vendor-payment-journals.voucher-create');
            Route::post('vendor-payment-journals/voucher/update/{id}', 'VendorPaymentJournalController@updateVoucher')->name('vendor-payment-journals.voucher-update');

            Route::post('vendor-payment-journals/voucher/update-status', 'VendorPaymentJournalController@updateStatusVoucher')->name('vendor-payment-journals.voucher-status-update');
            Route::post('vendor-payment-journals/header/update-status', 'VendorPaymentJournalController@updateStatusHeader')->name('vendor-payment-journals.header-status-update');

            Route::post('vendor-payment-journals/header-validate/{id}', 'VendorPaymentJournalController@validateJournal')->name('vendor-payment-journals.validate');
            Route::post('vendor-payment-journals/validate-voucher', 'VendorPaymentJournalController@validateVoucher')->name('vendor-payment-journals.validate-voucher');

            Route::post('vendor-payment-journals/fetch', 'VendorPaymentJournalFetchController@fetch')->name('vendor-payment-journals.fetch');
            Route::post('vendor-payment-journals/fetch?archived=1', 'VendorPaymentJournalFetchController@fetch')->name('vendor-payment-journals.fetch-archive');
            Route::post('vendor-payment-journals/fetch-item/{id?}', 'VendorPaymentJournalFetchController@fetchView')->name('vendor-payment-journals.fetch-item');
            Route::post('vendor-payment-journals/fetch-pagination/{id}', 'VendorPaymentJournalFetchController@fetchPagePagination')->name('vendor-payment-journals.fetch-pagination');

            Route::post('vendor-payment-journals/fetch-vouchers', 'VendorPaymentJournalVoucherFetchController@fetch')->name('vendor-payment-journals.fetch-vouchers');
            Route::post('vendor-payment-journals/fetch-vouchers?archived=1', 'VendorPaymentJournalVoucherFetchController@fetch')->name('vendor-payment-journals.fetch-vouchers-archive');
            Route::post('vendor-payment-journals/fetch-vouchers-pagination/{id}', 'VendorPaymentJournalVoucherFetchController@fetchPagePagination')->name('vendor-payment-journals.fetch-vouchers-pagination');

        });

        //////////////////////////////
        // Financial Dimension Controller //
        //////////////////////////////
        /**
         * @todo vendor payments is still ongoing in design
         */
        Route::namespace('FinancialDimensions')->group(function() {

            Route::get('financial-dimensions', 'FinancialDimensionController@index')->name('financial-dimensions.index');
            Route::get('financial-dimensions/create/{financial_dimension?}', 'FinancialDimensionController@create')->name('financial-dimensions.create');
            Route::post('financial-dimensions/store/', 'FinancialDimensionController@store')->name('financial-dimensions.store');
            Route::get('financial-dimensions/show/{id}', 'FinancialDimensionController@show')->name('financial-dimensions.show');
            Route::post('financial-dimensions/update/{id}', 'FinancialDimensionController@update')->name('financial-dimensions.update');
            Route::post('financial-dimensions/{id}/archive', 'FinancialDimensionController@archive')->name('financial-dimensions.archive');
            Route::post('financial-dimensions/{id}/restore', 'FinancialDimensionController@restore')->name('financial-dimensions.restore');

            Route::post('financial-dimensions/fetch', 'FinancialDimensionFetchController@fetch')->name('financial-dimensions.fetch');
            Route::post('financial-dimensions/fetch?archived=1', 'FinancialDimensionFetchController@fetch')->name('financial-dimensions.fetch-archive');
            Route::post('financial-dimensions/fetch-item/{id?}', 'FinancialDimensionFetchController@fetchView')->name('financial-dimensions.fetch-item');
            Route::post('financial-dimensions/fetch-pagination/{id}', 'FinancialDimensionFetchController@fetchPagePagination')->name('financial-dimensions.fetch-pagination');

        });

        //////////////////////////////
        // Financial Dimension Value Controller //
        //////////////////////////////
        Route::namespace('FinancialDimensionValues')->group(function() {

            Route::get('financial-dimension-values', 'FinancialDimensionValueController@index')->name('financial-dimension-values.index');
            Route::get('financial-dimension-values/create/{financial_dimension}', 'FinancialDimensionValueController@create')->name('financial-dimension-values.create');
            Route::post('financial-dimension-values/store/', 'FinancialDimensionValueController@store')->name('financial-dimension-values.store');
            Route::get('financial-dimension-values/show/{id}', 'FinancialDimensionValueController@show')->name('financial-dimension-values.show');
            Route::post('financial-dimension-values/update/{id}', 'FinancialDimensionValueController@update')->name('financial-dimension-values.update');
            Route::post('financial-dimension-values/{id}/archive', 'FinancialDimensionValueController@archive')->name('financial-dimension-values.archive');
            Route::post('financial-dimension-values/{id}/restore', 'FinancialDimensionValueController@restore')->name('financial-dimension-values.restore');

            Route::post('financial-dimension-values/fetch', 'FinancialDimensionValueFetchController@fetch')->name('financial-dimension-values.fetch');
            Route::post('financial-dimension-values/fetch?archived=1', 'FinancialDimensionValueFetchController@fetch')->name('financial-dimension-values.fetch-archive');
            Route::post('financial-dimension-values/fetch-item/{id?}', 'FinancialDimensionValueFetchController@fetchView')->name('financial-dimension-values.fetch-item');
            Route::post('financial-dimension-values/fetch-pagination/{id}', 'FinancialDimensionValueFetchController@fetchPagePagination')->name('financial-dimension-values.fetch-pagination');

        });


        Route::namespace('LedgerSetups')->group(function() {
            Route::namespace('DocumentCodeControls')->group(function() {
                Route::get('document-code-controls', 'DocumentCodeControlController@index')->name('document-code-controls.index');
                Route::get('document-code-controls/create', 'DocumentCodeControlController@create')->name('document-code-controls.create');
                Route::post('document-code-controls/store', 'DocumentCodeControlController@store')->name('document-code-controls.store');
                Route::get('document-code-controls/show/{id}', 'DocumentCodeControlController@show')->name('document-code-controls.show');
                Route::post('document-code-controls/update/{id}', 'DocumentCodeControlController@update')->name('document-code-controls.update');
                Route::post('document-code-controls/{id}/archive', 'DocumentCodeControlController@archive')->name('document-code-controls.archive');
                Route::post('document-code-controls/{id}/restore', 'DocumentCodeControlController@restore')->name('document-code-controls.restore');
                Route::post('document-code-controls/set-active', 'DocumentCodeControlController@setActive')->name('document-code-controls.set-active');
                Route::post('document-code-controls/set-inactive', 'DocumentCodeControlController@setInactive')->name('document-code-controls.set-inactive');

                Route::post('document-code-controls/fetch', 'DocumentCodeControlFetchController@fetch')->name('document-code-controls.fetch');
                Route::post('document-code-controls/fetch?archived=1', 'DocumentCodeControlFetchController@fetch')->name('document-code-controls.fetch-archive');
                Route::post('document-code-controls/fetch-item/{id?}', 'DocumentCodeControlFetchController@fetchView')->name('document-code-controls.fetch-item');
                Route::post('document-code-controls/fetch-pagination/{id}', 'DocumentCodeControlFetchController@fetchPagePagination')->name('document-code-controls.fetch-pagination');
            });
        });


        //////////////////////////////
        // General Ledger Controller //
        //////////////////////////////
        Route::namespace('GeneralLedgers')->group(function() {

            Route::get('general-ledgers', 'GeneralLedgerController@index')->name('general-ledgers.index');
            Route::get('general-ledgers/create', 'GeneralLedgerController@create')->name('general-ledgers.create');
            Route::get('general-ledgers/show/{id}', 'GeneralLedgerController@show')->name('general-ledgers.show');
            Route::post('general-ledgers/store', 'GeneralLedgerController@store')->name('general-ledgers.store');
            Route::post('general-ledgers/update/{id}', 'GeneralLedgerController@update')->name('general-ledgers.update');
            Route::post('general-ledgers/{id}/archive', 'GeneralLedgerController@archive')->name('general-ledgers.archive');
            Route::post('general-ledgers/{id}/restore', 'GeneralLedgerController@restore')->name('general-ledgers.restore');
            Route::post('general-ledgers/approve-closing-balance/{id}', 'GeneralLedgerController@approveClosingBalance')->name('general-ledgers.approve-closing-balance');
            Route::post('general-ledgers/fetch', 'GeneralLedgerFetchController@fetch')->name('general-ledgers.fetch');
            Route::post('general-ledgers/fetch?archived=1', 'GeneralLedgerFetchController@fetch')->name('general-ledgers.fetch-archive');
            Route::post('general-ledgers/fetch-item/{id?}', 'GeneralLedgerFetchController@fetchView')->name('general-ledgers.fetch-item');
            Route::post('general-ledgers/closing-authentication/{id?}', 'GeneralLedgerController@authenticateClosingPassword')->name('general-ledgers.closing-authentication');
        
            Route::post('general-ledgers/archive-accounts-recievable/{id?}', 'GeneralLedgerController@ArchiveAccountsReceivable')->name('general-ledgers.archive-accounts-recievable');
            Route::post('general-ledgers/archive-accounts-payable/{id?}', 'GeneralLedgerController@ArchiveAccountsPayable')->name('general-ledgers.archive-accounts-payable');
            Route::post('general-ledgers/archive-inventories/{id?}', 'GeneralLedgerController@ArchiveInventory')->name('general-ledgers.archive-inventories');
            Route::post('general-ledgers/archive-cash-and-bank/{id?}', 'GeneralLedgerController@ArchiveCashAndBank')->name('general-ledgers.archive-cash-and-bank');
            Route::post('general-ledgers/archive-general-ledger/{id?}', 'GeneralLedgerController@ArchiveGeneralLedger')->name('general-ledgers.archive-general-ledger');


            Route::post('general-ledgers/fetch-pagination/{id}', 'GeneralLedgerFetchController@fetchPagePagination')->name('general-ledgers.fetch-pagination');
            Route::post('general-ledgers/generate-closing-transaction/{id}', 'GeneralLedgerController@generateClosingTransaction')->name('general-ledgers-generate-closing-transaction');
            Route::post('general-ledgers/enable-closing-transaction/{id}', 'GeneralLedgerController@enableClosingTransaction')->name('general-ledgers-enable-closing-transaction');
        });

        Route::namespace('GeneralLedgerLines')->group(function() {

            Route::post('general-ledger-lines/general-ledger-summary', 'GeneralLedgerLineFetchController@fetchGeneraLedger')->name('general-ledger-lines.general-ledger-summary');
            Route::post('general-ledger-lines/trial-balance/post-closing', 'GeneralLedgerLineFetchController@fetchPostClosingTrialBalance')->name('general-ledger-lines.post-closing-trial-balance');
            Route::post('general-ledger-lines/trial-balance/unajusted', 'GeneralLedgerLineFetchController@fetchUnadjustedTrialBalance')->name('general-ledger-lines.unadjusted-trial-balance');
            Route::post('general-ledger-lines/trial-balance/adjusted', 'GeneralLedgerLineFetchController@fetchAdjustedTrialBalance')->name('general-ledger-lines.adjusted-trial-balance');
            Route::post('general-ledger-lines/fetch', 'GeneralLedgerLineFetchController@fetch')->name('general-ledger-lines.fetch');
            Route::post('general-ledger-lines/fetch?archived=1', 'GeneralLedgerLineFetchController@fetch')->name('general-ledger-lines.fetch-archive');
            Route::post('general-ledger-lines/fetch-item/{id?}', 'GeneralLedgerLineFetchController@fetchView')->name('general-ledger-lines.fetch-item');
            Route::post('general-ledger-lines/fetch-pagination/{id}', 'GeneralLedgerLineFetchController@fetchPagePagination')->name('general-ledger-lines.fetch-pagination');

        });

        //////////////////////////////
        // Opening Transactions //
        //////////////////////////////
        Route::namespace('OpeningTransactions')->group(function() {

            Route::get('opening-transactions', 'OpeningTransactionController@index')->name('opening-transactions.index');
            Route::get('opening-transactions/create', 'OpeningTransactionController@create')->name('opening-transactions.create');
            Route::post('opening-transactions/store', 'OpeningTransactionController@store')->name('opening-transactions.store');
            Route::get('opening-transactions/show/{id}', 'OpeningTransactionController@show')->name('opening-transactions.show');
            Route::post('opening-transactions/update/{id}', 'OpeningTransactionController@update')->name('opening-transactions.update');
            Route::post('opening-transactions/{id}/archive', 'OpeningTransactionController@archive')->name('opening-transactions.archive');
            Route::post('opening-transactions/{id}/restore', 'OpeningTransactionController@restore')->name('opening-transactions.restore');

            Route::post('opening-transactions/fetch', 'OpeningTransactionFetchController@fetch')->name('opening-transactions.fetch');
            Route::post('opening-transactions/fetch?archived=1', 'OpeningTransactionFetchController@fetch')->name('opening-transactions.fetch-archive');
            Route::post('opening-transactions/fetch-item/{id?}', 'OpeningTransactionFetchController@fetchView')->name('opening-transactions.fetch-item');
            Route::post('opening-transactions/fetch-pagination/{id}', 'OpeningTransactionFetchController@fetchPagePagination')->name('opening-transactions.fetch-pagination');
        });

        Route::namespace('ClosingTransactions')->group(function() {

            Route::get('closing-transactions', 'ClosingTransactionController@index')->name('closing-transactions.index');
            Route::get('closing-transactions/create', 'ClosingTransactionController@create')->name('closing-transactions.create');
            Route::post('closing-transactions/store', 'ClosingTransactionController@store')->name('closing-transactions.store');
            Route::get('closing-transactions/show/{id}', 'ClosingTransactionController@show')->name('closing-transactions.show');
            Route::post('closing-transactions/update/{id}', 'ClosingTransactionController@update')->name('closing-transactions.update');
            Route::post('closing-transactions/{id}/archive', 'ClosingTransactionController@archive')->name('closing-transactions.archive');
            Route::post('closing-transactions/{id}/restore', 'ClosingTransactionController@restore')->name('closing-transactions.restore');

            Route::post('closing-transactions/{id}/approved', 'ClosingTransactionController@markAsApproved')->name('closing-transactions.approved');
            Route::post('closing-transactions/{id}/reviewed', 'ClosingTransactionController@markAsReviewed')->name('closing-transactions.reviewed');
            Route::post('closing-transactions/set-password', 'ClosingTransactionController@setPassword')->name('closing-transactions.set-password');
            Route::post('closing-transactions/can-set-password/{id}', 'ClosingTransactionController@canSetPassword')->name('closing-transactions.can-set-password');

            Route::post('closing-transactions/fetch', 'ClosingTransactionFetchController@fetch')->name('closing-transactions.fetch');
            Route::post('closing-transactions/fetch?archived=1', 'ClosingTransactionFetchController@fetch')->name('closing-transactions.fetch-archive');
            Route::post('closing-transactions/fetch-item/{id?}', 'ClosingTransactionFetchController@fetchView')->name('closing-transactions.fetch-item');
            Route::post('closing-transactions/fetch-pagination/{id}', 'ClosingTransactionFetchController@fetchPagePagination')->name('closing-transactions.fetch-pagination');
        });

        //////////////////////////////
        // Accrual Posting          //
        //////////////////////////////
        Route::namespace('AccrualPostings')->group(function() {
            Route::get('accrual-postings', 'AccrualPostingController@index')->name('accrual-postings.index');
            Route::get('accrual-postings/create', 'AccrualPostingController@create')->name('accrual-postings.create');
            Route::post('accrual-postings/store', 'AccrualPostingController@store')->name('accrual-postings.store');
            Route::get('accrual-postings/show/{id}', 'AccrualPostingController@show')->name('accrual-postings.show');
            Route::post('accrual-postings/update/{id}', 'AccrualPostingController@update')->name('accrual-postings.update');
            Route::post('accrual-postings/{id}/archive', 'AccrualPostingController@archive')->name('accrual-postings.archive');
            Route::post('accrual-postings/{id}/restore', 'AccrualPostingController@restore')->name('accrual-postings.restore');
            Route::post('accrual-postings/{id}/{status}/update-status', 'AccrualPostingController@updateStatusVoucher')->name('accrual-postings.status-update');

            Route::post('accrual-postings/fetch', 'AccrualPostingFetchController@fetch')->name('accrual-postings.fetch');
            Route::post('accrual-postings/fetch?archived=1', 'AccrualPostingFetchController@fetch')->name('accrual-postings.fetch-archive');
            Route::post('accrual-postings/fetch-item/{id?}', 'AccrualPostingFetchController@fetchView')->name('accrual-postings.fetch-item');
            Route::post('accrual-postings/fetch-pagination/{id}', 'AccrualPostingFetchController@fetchPagePagination')->name('accrual-postings.fetch-pagination');
        });

        Route::namespace('AccrualPeriods')->group(function() {
            Route::post('accrual-periods/fetch', 'AccrualPeriodFetchController@fetch')->name('accrual-periods.fetch');
            Route::post('accrual-periods/fetch?archived=1', 'AccrualPeriodFetchController@fetch')->name('accrual-periods.fetch-archive');
            Route::post('accrual-periods/fetch-item/{id?}', 'AccrualPeriodFetchController@fetchView')->name('accrual-periods.fetch-item');
            Route::post('accrual-periods/fetch-pagination/{id}', 'AccrualPeriodFetchController@fetchPagePagination')->name('accrual-periods.fetch-pagination');
        });


        //////////////////////////////
        // General Journal Controller //
        //////////////////////////////
        Route::namespace('GeneralJournals')->group(function() {

            Route::get('general-journal', 'GeneralJournalController@index')->name('general-journal.index');
            Route::get('general-journal/create', 'GeneralJournalController@create')->name('general-journal.create');
            Route::post('general-journal/store', 'GeneralJournalController@store')->name('general-journal.store');
            Route::get('general-journal/show/{id}', 'GeneralJournalController@show')->name('general-journal.show');
            Route::get('general-journal/edit/{id}', 'GeneralJournalController@edit')->name('general-journal.edit');
            Route::post('general-journal/update/{id}', 'GeneralJournalController@update')->name('general-journal.update');
            Route::post('general-journal/{id}/archive', 'GeneralJournalController@archive')->name('general-journal.archive');
            Route::post('general-journal/{id}/restore', 'GeneralJournalController@restore')->name('general-journal.restore');
            Route::post('general-journal/{id}/post', 'GeneralJournalController@post')->name('general-journal.post');
            Route::post('general-journal/{id}/reverse', 'GeneralJournalController@reversal')->name('general-journal.reverse');

            Route::post('general-journal/voucher/create/{id}', 'GeneralJournalController@createVouchers')->name('general-journal.voucher-create');
            Route::post('general-journal/voucher/update/{id}', 'GeneralJournalController@updateVoucher')->name('general-journal.voucher-update');

            Route::post('general-journal/voucher/update-status', 'GeneralJournalController@updateStatusVoucher')->name('general-journal.voucher-status-update');
            Route::post('general-journal/header/update-status', 'GeneralJournalController@updateStatusHeader')->name('general-journal.header-status-update');

            Route::post('general-journal/header-validate/{id}', 'GeneralJournalController@validateJournal')->name('general-journal.validate');
            Route::post('general-journal/validate-voucher', 'GeneralJournalController@validateVoucher')->name('general-journal.validate-voucher');
            
            Route::post('general-journal/generate-accrual', 'GeneralJournalController@generateAccrual')->name('general-journal.generate-accrual');
            
            Route::post('general-journal/fetch', 'GeneralJournalFetchController@fetch')->name('general-journal.fetch');
            Route::post('general-journal/fetch?archived=1', 'GeneralJournalFetchController@fetch')->name('general-journal.fetch-archive');
            Route::post('general-journal/fetch-item/{id?}', 'GeneralJournalFetchController@fetchView')->name('general-journal.fetch-item');
            Route::post('general-journal/fetch-pagination/{id}', 'GeneralJournalFetchController@fetchPagePagination')->name('general-journal.fetch-pagination');

            Route::post('general-journal/fetch-vouchers', 'GeneralJournalVoucherFetchController@fetch')->name('general-journal.fetch-vouchers');
            Route::post('general-journal/fetch-vouchers?archived=1', 'GeneralJournalVoucherFetchController@fetch')->name('general-journal.fetch-vouchers-archive');
            Route::post('general-journal/fetch-vouchers-pagination/{id}', 'GeneralJournalVoucherFetchController@fetchPagePagination')->name('general-journal.fetch-vouchers-pagination');
        });

        //////////////////////////////
        // Cash Discounts Controller //
        //////////////////////////////
        Route::namespace('CashDiscounts')->group(function() {

            Route::get('cash-discounts', 'CashDiscountController@index')->name('cash-discounts.index');
            Route::get('cash-discounts/create', 'CashDiscountController@create')->name('cash-discounts.create');
            Route::post('cash-discounts/store', 'CashDiscountController@store')->name('cash-discounts.store');
            Route::get('cash-discounts/show/{id}', 'CashDiscountController@show')->name('cash-discounts.show');
            Route::post('cash-discounts/update/{id}', 'CashDiscountController@update')->name('cash-discounts.update');
            Route::post('cash-discounts/{id}/archive', 'CashDiscountController@archive')->name('cash-discounts.archive');
            Route::post('cash-discounts/{id}/restore', 'CashDiscountController@restore')->name('cash-discounts.restore');

            Route::post('cash-discounts/fetch', 'CashDiscountFetchController@fetch')->name('cash-discounts.fetch');
            Route::post('cash-discounts/fetch?archived=1', 'CashDiscountFetchController@fetch')->name('cash-discounts.fetch-archive');
            Route::post('cash-discounts/fetch-item/{id?}', 'CashDiscountFetchController@fetchView')->name('cash-discounts.fetch-item');
            Route::post('cash-discounts/fetch-pagination/{id}', 'CashDiscountFetchController@fetchPagePagination')->name('cash-discounts.fetch-pagination');

        });

        Route::namespace('Permissions')->group(function() {

            Route::post('permissions-fetch/{id?}', 'PermissionFetchController@fetch')->name('permissions.fetch');

        });


        // Route::get('/screen-setups', function () {
        //     return view('screen-setups.index');
        // })->name('screen-setups.index');

        //////////////////////////////
        // Ledger Controller //
        //////////////////////////////
        /**
         * 
         */
        
        Route::namespace('Ledgers')->group(function() {

            Route::get('ledgers', 'LedgerController@index')->name('ledgers.index');
            Route::get('ledgers/create/{ledger_code?}', 'LedgerController@create')->name('ledgers.create');
            Route::post('ledgers/store/', 'LedgerController@store')->name('ledgers.store');
            Route::get('ledgers/show/{id}', 'LedgerController@show')->name('ledgers.show');
            Route::post('ledgers/update/{id}', 'LedgerController@update')->name('ledgers.update');
            Route::post('ledgers/{id}/archive', 'LedgerController@archive')->name('ledgers.archive');
            Route::post('ledgers/{id}/restore', 'LedgerController@restore')->name('ledgers.restore');
            
            Route::post('ledgers/fetch', 'LedgerFetchController@fetch')->name('ledgers.fetch');
            Route::post('ledgers/fetch?archived=1', 'LedgerFetchController@fetch')->name('ledgers.fetch-archive');
            Route::post('ledgers/fetch-item/{id?}', 'LedgerFetchController@fetchView')->name('ledgers.fetch-item');
            Route::post('ledgers/fetch-pagination/{id}', 'LedgerFetchController@fetchPagePagination')->name('ledgers.fetch-pagination');

        });

        Route::namespace('LedgerCalendars')->group(function() {

            Route::get('ledger-calendars', 'LedgerCalendarController@index')->name('ledger-calendars.index');
            Route::get('ledger-calendars/create/{ledger_calendar_code?}', 'LedgerCalendarController@create')->name('ledger-calendars.create');
            Route::post('ledger-calendars/store/', 'LedgerCalendarController@store')->name('ledger-calendars.store');
            Route::get('ledger-calendars/show/{id}', 'LedgerCalendarController@show')->name('ledger-calendars.show');
            Route::post('ledger-calendars/update/{id}', 'LedgerCalendarController@update')->name('ledger-calendars.update');
            Route::post('ledger-calendars/{id}/archive', 'LedgerCalendarController@archive')->name('ledger-calendars.archive');
            Route::post('ledger-calendars/{id}/restore', 'LedgerCalendarController@restore')->name('ledger-calendars.restore');
            
            Route::post('ledger-calendars/fetch', 'LedgerCalendarFetchController@fetch')->name('ledger-calendars.fetch');
            Route::post('ledger-calendars/fetch?archived=1', 'LedgerCalendarFetchController@fetch')->name('ledger-calendars.fetch-archive');
            Route::post('ledger-calendars/fetch-item/{id?}', 'LedgerCalendarFetchController@fetchView')->name('ledger-calendars.fetch-item');
            Route::post('ledger-calendars/fetch-pagination/{id}', 'LedgerCalendarFetchController@fetchPagePagination')->name('ledger-calendars.fetch-pagination');

        });

        //////////////////////////////
        // Fixed Assets Controller //
        //////////////////////////////

        Route::namespace('FixedAssets')->group(function() {

            Route::get('fixed-assets', 'FixedAssetController@index')->name('fixed-assets.index');
            Route::get('fixed-assets/create/{asset_id?}', 'FixedAssetController@create')->name('fixed-assets.create');
            Route::post('fixed-assets/store/', 'FixedAssetController@store')->name('fixed-assets.store');
            Route::get('fixed-assets/show/{id}', 'FixedAssetController@show')->name('fixed-assets.show');
            Route::post('fixed-assets/update/{id}', 'FixedAssetController@update')->name('fixed-assets.update');
            Route::post('fixed-assets/{id}/archive', 'FixedAssetController@archive')->name('fixed-assets.archive');
            Route::post('fixed-assets/{id}/restore', 'FixedAssetController@restore')->name('fixed-assets.restore');
            Route::post('fixed-assets/{id}/generate-schedule', 'FixedAssetController@generateSchedule')->name('fixed-assets.generate-schedule');
            Route::post('fixed-assets/{id}/depreciation-lines/{lineId}/post', 'FixedAssetController@postDepreciationLine')->name('fixed-assets.post-depreciation-line');
            Route::post('fixed-assets/{id}/post-all-due', 'FixedAssetController@postAllDue')->name('fixed-assets.post-all-due');
            Route::post('fixed-assets/{id}/dispose', 'FixedAssetController@dispose')->name('fixed-assets.dispose');

            Route::post('fixed-assets/fetch', 'FixedAssetFetchController@fetch')->name('fixed-assets.fetch');
            Route::post('fixed-assets/fetch?archived=1', 'FixedAssetFetchController@fetch')->name('fixed-assets.fetch-archive');
            Route::post('fixed-assets/fetch-item/{id?}', 'FixedAssetFetchController@fetchView')->name('fixed-assets.fetch-item');
            Route::post('fixed-assets/fetch-pagination/{id}', 'FixedAssetFetchController@fetchPagePagination')->name('fixed-assets.fetch-pagination');
        });

        //////////////////////////////
        // Recurring Journal Templates Controller //
        //////////////////////////////
        /**
         *
         */

        Route::namespace('RecurringJournalTemplates')->group(function() {

            Route::get('recurring-journal-templates', 'RecurringJournalTemplateController@index')->name('recurring-journal-templates.index');
            Route::get('recurring-journal-templates/create/{template_id?}', 'RecurringJournalTemplateController@create')->name('recurring-journal-templates.create');
            Route::post('recurring-journal-templates/store/', 'RecurringJournalTemplateController@store')->name('recurring-journal-templates.store');
            Route::get('recurring-journal-templates/show/{id}', 'RecurringJournalTemplateController@show')->name('recurring-journal-templates.show');
            Route::post('recurring-journal-templates/update/{id}', 'RecurringJournalTemplateController@update')->name('recurring-journal-templates.update');
            Route::post('recurring-journal-templates/{id}/archive', 'RecurringJournalTemplateController@archive')->name('recurring-journal-templates.archive');
            Route::post('recurring-journal-templates/{id}/restore', 'RecurringJournalTemplateController@restore')->name('recurring-journal-templates.restore');
            Route::post('recurring-journal-templates/{id}/pause', 'RecurringJournalTemplateController@pause')->name('recurring-journal-templates.pause');
            Route::post('recurring-journal-templates/{id}/resume', 'RecurringJournalTemplateController@resume')->name('recurring-journal-templates.resume');
            Route::post('recurring-journal-templates/{id}/run-now', 'RecurringJournalTemplateController@runNow')->name('recurring-journal-templates.run-now');

            Route::post('recurring-journal-templates/fetch', 'RecurringJournalTemplateFetchController@fetch')->name('recurring-journal-templates.fetch');
            Route::post('recurring-journal-templates/fetch?archived=1', 'RecurringJournalTemplateFetchController@fetch')->name('recurring-journal-templates.fetch-archive');
            Route::post('recurring-journal-templates/fetch-item/{id?}', 'RecurringJournalTemplateFetchController@fetchView')->name('recurring-journal-templates.fetch-item');
            Route::post('recurring-journal-templates/fetch-pagination/{id}', 'RecurringJournalTemplateFetchController@fetchPagePagination')->name('recurring-journal-templates.fetch-pagination');

        });

        //////////////////////////////
        // Budgets Controller //
        //////////////////////////////
        /**
         *
         */

        Route::namespace('Budgets')->group(function() {

            Route::get('budgets', 'BudgetController@index')->name('budgets.index');
            Route::get('budgets/create/{budget_id?}', 'BudgetController@create')->name('budgets.create');
            Route::post('budgets/store/', 'BudgetController@store')->name('budgets.store');
            Route::get('budgets/show/{id}', 'BudgetController@show')->name('budgets.show');
            Route::post('budgets/update/{id}', 'BudgetController@update')->name('budgets.update');
            Route::post('budgets/{id}/archive', 'BudgetController@archive')->name('budgets.archive');
            Route::post('budgets/{id}/restore', 'BudgetController@restore')->name('budgets.restore');
            Route::get('budgets/{id}/variance', 'BudgetController@variance')->name('budgets.variance');

            Route::post('budgets/fetch', 'BudgetFetchController@fetch')->name('budgets.fetch');
            Route::post('budgets/fetch?archived=1', 'BudgetFetchController@fetch')->name('budgets.fetch-archive');
            Route::post('budgets/fetch-item/{id?}', 'BudgetFetchController@fetchView')->name('budgets.fetch-item');
            Route::post('budgets/fetch-pagination/{id}', 'BudgetFetchController@fetchPagePagination')->name('budgets.fetch-pagination');

        });


        //////////////////////////////
        // Account Structures Controller //
        //////////////////////////////
        /**
         * 
         */
        
        Route::namespace('AccountStructures')->group(function() {

            Route::get('account-structures', 'AccountStructureController@index')->name('account-structures.index');
            Route::get('account-structures/create/{account_structure?}', 'AccountStructureController@create')->name('account-structures.create');
            Route::get('account-structures/create/ledgers/{ledger_id}', 'AccountStructureController@create_ledger')->name('account-structures.create-ledger');
            Route::get('account-structures/create/coa/{coa_id}', 'AccountStructureController@create_coa')->name('account-structures.create-coa');                 


            Route::post('account-structures/store/', 'AccountStructureController@store')->name('account-structures.store');
            Route::post('account-structures/store/coa', 'AccountStructureController@store_coa')->name('account-structures.store-coa');

            Route::get('account-structures/show/{id}', 'AccountStructureController@show')->name('account-structures.show');
            Route::get('account-structures-ledger/show/{id}', 'AccountStructureController@showLedger')->name('account-structures.show-ledger');
            Route::get('account-structures-coa/show/{id}', 'AccountStructureController@showCoa')->name('account-structures.show-coa');

            Route::post('account-structures/update/{id}', 'AccountStructureController@update')->name('account-structures.update');
            Route::post('account-structures/{id}/archive', 'AccountStructureController@archive')->name('account-structures.archive');
            Route::post('account-structures/{id}/restore', 'AccountStructureController@restore')->name('account-structures.restore');
            
            Route::post('account-structures/fetch', 'AccountStructureFetchController@fetch')->name('account-structures.fetch');
            Route::post('account-structures-ledger/fetch', 'AccountStructureLedgerFetchController@fetch')->name('account-structures-ledger.fetch');
            Route::post('account-structures-coa/fetch', 'AccountStructureCoaFetchController@fetch')->name('account-structures-coa.fetch');            

            Route::post('account-structures/fetch?archived=1', 'AccountStructureFetchController@fetch')->name('account-structures.fetch-archive');
            Route::post('account-structures/fetch-item/{id?}', 'AccountStructureFetchController@fetchView')->name('account-structures.fetch-item');
            Route::post('account-structures/fetch-pagination/{id}', 'AccountStructureFetchController@fetchPagePagination')->name('account-structures.fetch-pagination');

        });        

        Route::namespace('TrialBalances')->group(function() {
            Route::get('trial-balance/index', 'TrialBalanceController@index')->name('trial-balance.index');
            Route::get('trial-balance/create', 'TrialBalanceController@create')->name('trial-balance.create');
            Route::post('trial-balance/store/', 'TrialBalanceController@store')->name('trial-balance.store');
            Route::get('trial-balance/show/{id}', 'TrialBalanceController@show')->name('trial-balance.show');
            Route::post('trial-balance/update/{id}', 'TrialBalanceController@update')->name('trial-balance.update');
            Route::post('trial-balance/{id}/archive', 'TrialBalanceController@archive')->name('trial-balance.archive');
            Route::post('trial-balance/{id}/restore', 'TrialBalanceController@restore')->name('trial-balance.restore');
            
            Route::post('trial-balances/fetch', 'TrialBalanceFetchController@fetch')->name('trial-balance.fetch');
            Route::post('trial-balances/fetch?archived=1', 'TrialBalanceFetchController@fetch')->name('trial-balance.fetch-archive');
            Route::post('trial-balances/fetch-item/{id?}', 'TrialBalanceFetchController@fetchView')->name('trial-balances.fetch-item');
            Route::post('trial-balances/fetch-pagination/{id}', 'TrialBalanceFetchController@fetchPagePagination')->name('trial-balances.fetch-pagination');
        });    


        //////////////////////////////
        // Main Account Controller //
        //////////////////////////////
        /**
         * 
         */
        Route::namespace('MainAccounts')->group(function() {

            Route::get('main-accounts', 'MainAccountController@index')->name('main-accounts.index');
            Route::get('main-accounts/create/{coa_id?}', 'MainAccountController@create')->name('main-accounts.create');
            Route::get('main-accounts/create/ma/{coa_id}', 'MainAccountController@create_coa')->name('main-accounts.create-coa');            
            Route::post('main-accounts/store/', 'MainAccountController@store')->name('main-accounts.store');
            Route::get('main-accounts/show/{id}', 'MainAccountController@show')->name('main-accounts.show');
            Route::get('main-accounts-coa/show/{id}', 'MainAccountController@showCoa')->name('main-accounts.show-coa');

            Route::post('main-accounts/update/{id}', 'MainAccountController@update')->name('main-accounts.update');
            Route::post('main-accounts/{id}/archive', 'MainAccountController@archive')->name('main-accounts.archive');
            Route::post('main-accounts/{id}/restore', 'MainAccountController@restore')->name('main-accounts.restore');

            Route::post('main-accounts/{id}/linked-main-account-attach/{linked?}', 'MainAccountController@attachToLinkedMainAccount')->name('main-accounts.attach-linked-main-accounts');
            Route::post('main-accounts/{id}/linked-main-account/{linked?}', 'MainAccountController@detachToLinkedMainAccount')->name('main-accounts.detach-linked-main-accounts');

            Route::post('main-accounts-coa/fetch', 'MainAccountCOAFetchController@fetch')->name('main-accounts-coa.fetch');  
            Route::post('main-accounts/fetch', 'MainAccountFetchController@fetch')->name('main-accounts.fetch');
            Route::post('main-accounts/fetch?archived=1', 'MainAccountFetchController@fetch')->name('main-accounts.fetch-archive');
            Route::post('main-accounts/fetch-item/{id?}', 'MainAccountFetchController@fetchView')->name('main-accounts.fetch-item');
            Route::post('main-accounts/fetch-pagination/{id}', 'MainAccountFetchController@fetchPagePagination')->name('main-accounts.fetch-pagination');

        });

        //////////////////////////////
        // Main Account Category Controller //
        //////////////////////////////
        /**
         * 
         */
        Route::namespace('MainAccountCategories')->group(function() {

            Route::get('main-accounts-categories', 'MainAccountCategoryController@index')->name('main-accounts-categories.index');
            Route::get('main-accounts-categories/create/{main_account_category_reference?}', 'MainAccountCategoryController@create')->name('main-accounts-categories.create');
            Route::post('main-accounts-categories/store/', 'MainAccountCategoryController@store')->name('main-accounts-categories.store');
            Route::get('main-accounts-categories/show/{id}', 'MainAccountCategoryController@show')->name('main-accounts-categories.show');
            Route::post('main-accounts-categories/update/{id}', 'MainAccountCategoryController@update')->name('main-accounts-categories.update');
            Route::post('main-accounts-categories/{id}/archive', 'MainAccountCategoryController@archive')->name('main-accounts-categories.archive');
            Route::post('main-accounts-categories/{id}/restore', 'MainAccountCategoryController@restore')->name('main-accounts-categories.restore');
            
            Route::post('main-accounts-categories/fetch', 'MainAccountCategoryFetchController@fetch')->name('main-accounts-categories.fetch');
            Route::post('main-accounts-categories/fetch?archived=1', 'MainAccountCategoryFetchController@fetch')->name('main-accounts-categories.fetch-archive');
            Route::post('main-accounts-categories/fetch-item/{id?}', 'MainAccountCategoryFetchController@fetchView')->name('main-accounts-categories.fetch-item');
            Route::post('main-accounts-categories/fetch-pagination/{id}', 'MainAccountCategoryFetchController@fetchPagePagination')->name('main-accounts-categories.fetch-pagination');

        });

        Route::namespace('LinkedMainAccounts')->group(function() {

            Route::get('linked-main-accounts', 'LinkedMainAccountController@index')->name('linked-main-accounts.index');
            Route::get('linked-main-accounts/create/{linked_main_account_code?}', 'LinkedMainAccountController@create')->name('linked-main-accounts.create');
            Route::post('linked-main-accounts/store/', 'LinkedMainAccountController@store')->name('linked-main-accounts.store');
            Route::get('linked-main-accounts/show/{id}', 'LinkedMainAccountController@show')->name('linked-main-accounts.show');
            Route::post('linked-main-accounts/update/{id}', 'LinkedMainAccountController@update')->name('linked-main-accounts.update');
            Route::post('linked-main-accounts/{id}/archive', 'LinkedMainAccountController@archive')->name('linked-main-accounts.archive');
            Route::post('linked-main-accounts/{id}/restore', 'LinkedMainAccountController@restore')->name('linked-main-accounts.restore');
            
            Route::post('linked-main-accounts/fetch', 'LinkedMainAccountFetchController@fetch')->name('linked-main-accounts.fetch');
            Route::post('linked-main-accounts/fetch?archived=1', 'LinkedMainAccountFetchController@fetch')->name('linked-main-accounts.fetch-archive');
            Route::post('linked-main-accounts/fetch-item/{id?}', 'LinkedMainAccountFetchController@fetchView')->name('linked-main-accounts.fetch-item');
            Route::post('linked-main-accounts/fetch-pagination/{id}', 'LinkedMainAccountFetchController@fetchPagePagination')->name('linked-main-accounts.fetch-pagination');
        });    

        // Route::get('/customer-posting-profiles', function () {
        //     return view('customer-posting-profiles.index');
        // })->name('customer-posting-profiles.index');
        // Route::get('/customer-posting-profiles/create', function () {
        //     return view('customer-posting-profiles.create');
        // })->name('customer-posting-profiles.create');
        // Route::get('/customer-posting-profiles/show/{id}', function () {
        //     return view('customer-posting-profiles.show');
        // })->name('customer-posting-profiles.show');


        // Route::get('/setups', function () {
        //     return view('setups.index');
        // })->name('setups.index');
        // Route::get('/setups/create', function () {
        //     return view('setups.create');
        // })->name('setups.create');
        // Route::get('/setups/show/{id}', function () {
        //     return view('setups.show');
        // })->name('setups.show');


        //////////////////////////////
        // Fiscal Calendar Controller //
        //////////////////////////////
        Route::namespace('FiscalCalendars')->group(function() {

            Route::get('fiscal-calendars', 'FiscalCalendarController@index')->name('fiscal-calendars.index');
            Route::get('fiscal-calendars/create/{coa_id?}', 'FiscalCalendarController@create')->name('fiscal-calendars.create');
            Route::post('fiscal-calendars/store/', 'FiscalCalendarController@store')->name('fiscal-calendars.store');
            Route::get('fiscal-calendars/show/{id}', 'FiscalCalendarController@show')->name('fiscal-calendars.show');
            Route::post('fiscal-calendars/update/{id}', 'FiscalCalendarController@update')->name('fiscal-calendars.update');
            Route::post('fiscal-calendars/{id}/archive', 'FiscalCalendarController@archive')->name('fiscal-calendars.archive');
            Route::post('fiscal-calendars/{id}/restore', 'FiscalCalendarController@restore')->name('fiscal-calendars.restore');

            Route::post('fiscal-calendars/fetch', 'FiscalCalendarFetchController@fetch')->name('fiscal-calendars.fetch');
            Route::post('fiscal-calendars/fetch?archived=1', 'FiscalCalendarFetchController@fetch')->name('fiscal-calendars.fetch-archive');
            Route::post('fiscal-calendars/fetch-item/{id?}', 'FiscalCalendarFetchController@fetchView')->name('fiscal-calendars.fetch-item');
            Route::post('fiscal-calendars/fetch-pagination/{id}', 'FiscalCalendarFetchController@fetchPagePagination')->name('fiscal-calendars.fetch-pagination');

        });

        //////////////////////////////
        // Fiscal Period Controller //
        //////////////////////////////
        Route::namespace('FiscalPeriods')->group(function() {

            Route::get('fiscal-periods', 'FiscalPeriodController@index')->name('fiscal-periods.index');
            Route::get('fiscal-periods/create/{fiscal_id}', 'FiscalPeriodController@create')->name('fiscal-periods.create');
            Route::post('fiscal-periods/store/', 'FiscalPeriodController@store')->name('fiscal-periods.store');
            Route::get('fiscal-periods/show/{id}', 'FiscalPeriodController@show')->name('fiscal-periods.show');
            Route::post('fiscal-periods/update/{id}', 'FiscalPeriodController@update')->name('fiscal-periods.update');
            Route::post('fiscal-periods/{id}/archive', 'FiscalPeriodController@archive')->name('fiscal-periods.archive');
            Route::post('fiscal-periods/{id}/restore', 'FiscalPeriodController@restore')->name('fiscal-periods.restore');

            Route::post('fiscal-periods/fetch', 'FiscalPeriodFetchController@fetch')->name('fiscal-periods.fetch');
            Route::post('fiscal-periods/fetch?archived=1', 'FiscalPeriodFetchController@fetch')->name('fiscal-periods.fetch-archive');
            Route::post('fiscal-periods/fetch-item/{id?}', 'FiscalPeriodFetchController@fetchView')->name('fiscal-periods.fetch-item');
            Route::post('fiscal-periods/fetch-pagination/{id}', 'FiscalPeriodFetchController@fetchPagePagination')->name('fiscal-periods.fetch-pagination');

        });

        //////////////////////////////////////////
        // Bill Of Exchange Controller          //
        /////////////////////////////////////////
        Route::namespace('BillOfExchanges')->group(function() {

            Route::get('bill-of-exchange', 'BillOfExchangeController@index')->name('bill-of-exchanges.index');
            Route::get('bill-of-exchange/create', 'BillOfExchangeController@create')->name('bill-of-exchanges.create');
            Route::post('bill-of-exchange/store/', 'BillOfExchangeController@store')->name('bill-of-exchanges.store');
            Route::get('bill-of-exchange/show/{id}', 'BillOfExchangeController@show')->name('bill-of-exchanges.show');
            Route::get('bill-of-exchange/edit/{id}', 'BillOfExchangeController@edit')->name('bill-of-exchanges.edit');
            Route::get('bill-of-exchange/header/show/{id}', 'BillOfExchangeController@showUpdate')->name('bill-of-exchanges.header-show');
            Route::post('bill-of-exchange/update/{id}', 'BillOfExchangeController@update')->name('bill-of-exchanges.update');
            Route::post('bill-of-exchanges/{id}/archive', 'BillOfExchangeController@archive')->name('bill-of-exchanges.archive');
            Route::post('bill-of-exchanges/{id}/restore', 'BillOfExchangeController@restore')->name('bill-of-exchanges.restore');
            Route::post('bill-of-exchange/{id}/post', 'BillOfExchangeController@post')->name('bill-of-exchanges.post');

            // Route::post('bill-of-exchange/fetch', 'BillOfExchangeController@fetch')->name('bill-of-exchanges.fetch');
            Route::post('bill-of-exchange/voucher/create/{id}', 'BillOfExchangeController@createVouchers')->name('bill-of-exchanges.voucher-create');
            Route::post('bill-of-exchange/voucher/update/{id}', 'BillOfExchangeController@updateVoucher')->name('bill-of-exchanges.voucher-update');

            Route::post('bill-of-exchange/fetch', 'BillOfExchangeFetchController@fetch')->name('bill-of-exchanges.fetch');
            Route::post('bill-of-exchange/fetch?archived=1', 'BillOfExchangeFetchController@fetch')->name('bill-of-exchanges.fetch-archive');
            Route::post('bill-of-exchange/fetch-item/{id?}', 'BillOfExchangeFetchController@fetchView')->name('bill-of-exchanges.fetch-item');
            Route::post('bill-of-exchange/fetch-pagination/{id}', 'BillOfExchangeFetchController@fetchPagePagination')->name('bill-of-exchanges.fetch-pagination');


            Route::post('bill-of-exchange/voucher/update-status', 'BillOfExchangeController@updateStatusVoucher')->name('bill-of-exchanges.voucher-status-update');
            Route::post('bill-of-exchange/header/update-status', 'BillOfExchangeController@updateStatusHeader')->name('bill-of-exchanges.header-status-update');
            

            Route::post('bill-of-exchange/header-validate/{id}', 'BillOfExchangeController@validateJournal')->name('bill-of-exchanges.validate');
            Route::post('bill-of-exchange/validate-voucher', 'BillOfExchangeController@validateVoucher')->name('bill-of-exchanges.validate-voucher');


            Route::post('bill-of-exchange/fetch-vouchers', 'BillOfExchangeVoucherJournalFetchController@fetch')->name('bill-of-exchanges.fetch-vouchers');
            Route::post('bill-of-exchange/fetch-vouchers?archived=1', 'BillOfExchangeVoucherJournalFetchController@fetch')->name('bill-of-exchanges.fetch-vouchers-archive');
            Route::post('bill-of-exchange/fetch-vouchers-pagination/{id}', 'BillOfExchangeVoucherJournalFetchController@fetchPagePagination')->name('bill-of-exchanges.fetch-vouchers-pagination');

        });

        //////////////////////////////////////////
        // Promissory Note Controller           //
        /////////////////////////////////////////
        Route::namespace('PromissoryNotes')->group(function() {

            Route::get('promissory-note', 'PromissoryNoteController@index')->name('promissory-notes.index');
            Route::get('promissory-note/create', 'PromissoryNoteController@create')->name('promissory-notes.create');
            Route::post('promissory-note/store/', 'PromissoryNoteController@store')->name('promissory-notes.store');
            Route::get('promissory-note/show/{id}', 'PromissoryNoteController@show')->name('promissory-notes.show');
            Route::get('promissory-note/edit/{id}', 'PromissoryNoteController@edit')->name('promissory-notes.edit');
            Route::get('promissory-note/header/show/{id}', 'PromissoryNoteController@showUpdate')->name('promissory-notes.header-show');
            Route::post('promissory-note/update/{id}', 'PromissoryNoteController@update')->name('promissory-notes.update');
            Route::post('promissory-notes/{id}/archive', 'PromissoryNoteController@archive')->name('promissory-notes.archive');
            Route::post('promissory-notes/{id}/restore', 'PromissoryNoteController@restore')->name('promissory-notes.restore');
            Route::post('promissory-notes/{id}/post', 'PromissoryNoteController@post')->name('promissory-notes.post');

            // Route::post('promissory-note/fetch', 'PromissoryNoteController@fetch')->name('promissory-notes.fetch');
            Route::post('promissory-note/voucher/create/{id}', 'PromissoryNoteController@createVouchers')->name('promissory-notes.voucher-create');
            Route::post('promissory-note/voucher/update/{id}', 'PromissoryNoteController@updateVoucher')->name('promissory-notes.voucher-update');

            Route::post('promissory-note/fetch', 'PromissoryNoteFetchController@fetch')->name('promissory-notes.fetch');
            Route::post('promissory-note/fetch?archived=1', 'PromissoryNoteFetchController@fetch')->name('promissory-notes.fetch-archive');
            Route::post('promissory-note/fetch-item/{id?}', 'PromissoryNoteFetchController@fetchView')->name('promissory-notes.fetch-item');
            Route::post('promissory-notes/fetch-pagination/{id}', 'PromissoryNoteFetchController@fetchPagePagination')->name('promissory-notes.fetch-pagination');



            // Route::post('promissory-notes/fetch', 'PromissoryNoteController@fetch')->name('promissory-notes.fetch');

            Route::post('promissory-notes/voucher/update-status', 'PromissoryNoteController@updateStatusVoucher')->name('promissory-notes.voucher-status-update');
            Route::post('promissory-notes/header/update-status', 'PromissoryNoteController@updateStatusHeader')->name('promissory-notes.header-status-update');

            Route::post('promissory-notes/header-validate/{id}', 'PromissoryNoteController@validateJournal')->name('promissory-notes.validate');
            Route::post('promissory-notes/validate-voucher', 'PromissoryNoteController@validateVoucher')->name('promissory-notes.validate-voucher');

            Route::post('promissory-notes/fetch-vouchers', 'PromissoryNoteVoucherFetchController@fetch')->name('promissory-notes.fetch-vouchers');
            Route::post('promissory-notes/fetch-vouchers?archived=1', 'PromissoryNoteVoucherFetchController@fetch')->name('promissory-notes.fetch-vouchers-archive');
            Route::post('promissory-notes/fetch-vouchers-pagination/{id}', 'PromissoryNoteVoucherFetchController@fetchPagePagination')->name('promissory-notes.fetch-vouchers-pagination');

        });


        ///////////////////////////////////
        // Inventory Journals Controller //
        //////////////////////////////////
        Route::namespace('InventoryJournals')->group(function() {

            Route::get('inventory-journals', 'InventoryJournalController@index')->name('inventory-journals.index');
            Route::get('inventory-journals/create', 'InventoryJournalController@create')->name('inventory-journals.create');
            Route::post('inventory-journals/store', 'InventoryJournalController@store')->name('inventory-journals.store');
            Route::get('inventory-journals/show/{id}', 'InventoryJournalController@show')->name('inventory-journals.show');
            Route::get('inventory-journals/edit/{id}', 'InventoryJournalController@edit')->name('inventory-journals.edit');
            Route::post('inventory-journals/update/{id}', 'InventoryJournalController@update')->name('inventory-journals.update');
            Route::post('inventory-journals/{id}/archive', 'InventoryJournalController@archive')->name('inventory-journals.archive');
            Route::post('inventory-journals/{id}/restore', 'InventoryJournalController@restore')->name('inventory-journals.restore');
            Route::post('inventory-journals/{id}/post', 'InventoryJournalController@post')->name('inventory-journals.post');

            Route::post('inventory-journals/voucher/create/{id}', 'InventoryJournalController@createVouchers')->name('inventory-journals.voucher-create');
            Route::post('inventory-journals/voucher/update/{id}', 'InventoryJournalController@updateVoucher')->name('inventory-journals.voucher-update');
            
            Route::post('inventory-journals/voucher/update-status', 'InventoryJournalController@updateStatusVoucher')->name('inventory-journals.voucher-status-update');
            Route::post('inventory-journals/header/update-status', 'InventoryJournalController@updateStatusHeader')->name('inventory-journals.header-status-update');

            Route::post('inventory-journals/header-validate/{id}', 'InventoryJournalController@validateJournal')->name('inventory-journals.validate');
            Route::post('inventory-journals/validate-voucher/{id}', 'InventoryJournalController@validateVoucher')->name('inventory-journals.validate-voucher');

            Route::post('inventory-journals/fetch', 'InventoryJournalFetchController@fetch')->name('inventory-journals.fetch');
            Route::post('inventory-journals/fetch?archived=1', 'InventoryJournalFetchController@fetch')->name('inventory-journals.fetch-archive');

            Route::post('inventory-journals/fetch-item/{id?}', 'InventoryJournalFetchController@fetchView')->name('inventory-journals.fetch-item');
            Route::post('inventory-journals/fetch-pagination/{id}', 'InventoryJournalFetchController@fetchPagePagination')->name('inventory-journals.fetch-pagination');

            Route::post('inventory-journals/fetch-vouchers', 'InventoryJournalVoucherFetchController@fetch')->name('inventory-journals.fetch-vouchers');
            Route::post('inventory-journals/fetch-vouchers?archived=1', 'InventoryJournalVoucherFetchController@fetch')->name('inventory-journals.fetch-vouchers-archive');
            Route::post('inventory-journals/fetch-vouchers-pagination/{id}', 'InventoryJournalVoucherFetchController@fetchPagePagination')->name('inventory-journals.fetch-vouchers-pagination');

        });

        //////////////////////////////////////
        // Customer Posting Profile Header Controller//
        /////////////////////////////////////
        Route::namespace('CustomerPostingProfileHeaders')->group(function() {

            Route::get('customer-posting-profile-headers', 'CustomerPostingProfileHeaderController@index')->name('customer-posting-profile-headers.index');
            Route::get('customer-posting-profile-headers/create', 'CustomerPostingProfileHeaderController@create')->name('customer-posting-profile-headers.create');
            Route::post('customer-posting-profile-headers/store', 'CustomerPostingProfileHeaderController@store')->name('customer-posting-profile-headers.store');
            Route::get('customer-posting-profile-headers/show/{id}', 'CustomerPostingProfileHeaderController@show')->name('customer-posting-profile-headers.show');
            Route::post('customer-posting-profile-headers/update/{id}', 'CustomerPostingProfileHeaderController@update')->name('customer-posting-profile-headers.update');
            Route::post('customer-posting-profile-headers/{id}/archive', 'CustomerPostingProfileHeaderController@archive')->name('customer-posting-profile-headers.archive');
            Route::post('customer-posting-profile-headers/{id}/restore', 'CustomerPostingProfileHeaderController@restore')->name('customer-posting-profile-headers.restore');

            Route::post('customer-posting-profile-headers/fetch', 'CPPFetchController@fetch')->name('customer-posting-profile-headers.fetch');
            Route::post('customer-posting-profile-headers/fetch?archived=1', 'CPPFetchController@fetch')->name('customer-posting-profile-headers.fetch-archive');
            Route::post('customer-posting-profile-headers/fetch-item/{id?}', 'CPPFetchController@fetchView')->name('customer-posting-profile-headers.fetch-item');
            Route::post('customer-posting-profile-headers/fetch-pagination/{id}', 'CPPFetchController@fetchPagePagination')->name('customer-posting-profile-headers.fetch-pagination');
        });

        //////////////////////////////////////
        // Vendor Posting Profile Header Controller//
        /////////////////////////////////////
        Route::namespace('VendorPostingProfileHeaders')->group(function() {

            Route::get('vendor-posting-profile-headers', 'VendorPostingProfileHeaderController@index')->name('vendor-posting-profile-headers.index');
            Route::get('vendor-posting-profile-headers/create', 'VendorPostingProfileHeaderController@create')->name('vendor-posting-profile-headers.create');
            Route::post('vendor-posting-profile-headers/store', 'VendorPostingProfileHeaderController@store')->name('vendor-posting-profile-headers.store');
            Route::get('vendor-posting-profile-headers/show/{id}', 'VendorPostingProfileHeaderController@show')->name('vendor-posting-profile-headers.show');
            Route::post('vendor-posting-profile-headers/update/{id}', 'VendorPostingProfileHeaderController@update')->name('vendor-posting-profile-headers.update');
            Route::post('vendor-posting-profile-headers/{id}/archive', 'VendorPostingProfileHeaderController@archive')->name('vendor-posting-profile-headers.archive');
            Route::post('vendor-posting-profile-headers/{id}/restore', 'VendorPostingProfileHeaderController@restore')->name('vendor-posting-profile-headers.restore');

            Route::post('vendor-posting-profile-headers/fetch', 'VPPFetchController@fetch')->name('vendor-posting-profile-headers.fetch');
            Route::post('vendor-posting-profile-headers/fetch?archived=1', 'VPPFetchController@fetch')->name('vendor-posting-profile-headers.fetch-archive');
            Route::post('vendor-posting-profile-headers/fetch-item/{id?}', 'VPPFetchController@fetchView')->name('vendor-posting-profile-headers.fetch-item');
            Route::post('vendor-posting-profile-headers/fetch-pagination/{id}', 'VPPFetchController@fetchPagePagination')->name('vendor-posting-profile-headers.fetch-pagination');
        });

        //////////////////////////////////////
        // Vendor Posting Profile Line Controller//
        /////////////////////////////////////
        Route::namespace('VendorPostingProfiles')->group(function() {

            Route::get('vendor-posting-profiles', 'VendorPostingProfileController@index')->name('vendor-posting-profiles.index');
            Route::get('vendor-posting-profiles/create/{id?}', 'VendorPostingProfileController@create')->name('vendor-posting-profiles.create');
            Route::post('vendor-posting-profiles/store', 'VendorPostingProfileController@store')->name('vendor-posting-profiles.store');
            Route::get('vendor-posting-profiles/show/{id}', 'VendorPostingProfileController@show')->name('vendor-posting-profiles.show');
            Route::post('vendor-posting-profiles/update/{id}', 'VendorPostingProfileController@update')->name('vendor-posting-profiles.update');
            Route::post('vendor-posting-profiles/{id}/archive', 'VendorPostingProfileController@archive')->name('vendor-posting-profiles.archive');
            Route::post('vendor-posting-profiles/{id}/restore', 'VendorPostingProfileController@restore')->name('vendor-posting-profiles.restore');

            Route::post('vendor-posting-profiles/fetch', 'VendorPostingProfileFetchController@fetch')->name('vendor-posting-profiles.fetch');
            Route::post('vendor-posting-profiles/fetch?archived=1', 'VendorPostingProfileFetchController@fetch')->name('vendor-posting-profiles.fetch-archive');
            Route::post('vendor-posting-profiles/fetch-item/{id?}', 'VendorPostingProfileFetchController@fetchView')->name('vendor-posting-profiles.fetch-item');
            Route::post('vendor-posting-profiles/fetch-pagination/{id}', 'VendorPostingProfileFetchController@fetchPagePagination')->name('vendor-posting-profiles.fetch-pagination');
        });
        
         //////////////////////////////////////
        // Transaction Profile Header Controller//
        /////////////////////////////////////
        Route::namespace('TransactionPostingHeaders')->group(function() {

            Route::get('transaction-posting-headers', 'TransactionPostingHeaderController@index')->name('transaction-posting-headers.index');
            Route::get('transaction-posting-headers/create', 'TransactionPostingHeaderController@create')->name('transaction-posting-headers.create');
            Route::post('transaction-posting-headers/store', 'TransactionPostingHeaderController@store')->name('transaction-posting-headers.store');
            Route::get('transaction-posting-headers/show/{id}', 'TransactionPostingHeaderController@show')->name('transaction-posting-headers.show');
            Route::post('transaction-posting-headers/update/{id}', 'TransactionPostingHeaderController@update')->name('transaction-posting-headers.update');
            Route::post('transaction-posting-headers/{id}/archive', 'TransactionPostingHeaderController@archive')->name('transaction-posting-headers.archive');
            Route::post('transaction-posting-headers/{id}/restore', 'TransactionPostingHeaderController@restore')->name('transaction-posting-headers.restore');

            Route::post('transaction-posting-headers/fetch', 'TransactionPostingHeaderFetchController@fetch')->name('transaction-posting-headers.fetch');
            Route::post('transaction-posting-headers/fetch?archived=1', 'TransactionPostingHeaderFetchController@fetch')->name('transaction-posting-headers.fetch-archive');
            Route::post('transaction-posting-headers/fetch-item/{id?}', 'TransactionPostingHeaderFetchController@fetchView')->name('transaction-posting-headers.fetch-item');
            Route::post('transaction-posting-headers/fetch-pagination/{id}', 'TransactionPostingHeaderFetchController@fetchPagePagination')->name('transaction-posting-headers.fetch-pagination');
        });

        //////////////////////////////////////
        // Transaction Posting Line Controller//
        /////////////////////////////////////
        Route::namespace('TransactionPostings')->group(function() {

            Route::get('transaction-postings', 'TransactionPostingController@index')->name('transaction-postings.index');
            Route::get('transaction-postings/create/{id?}', 'TransactionPostingController@create')->name('transaction-postings.create');
            Route::post('transaction-postings/store', 'TransactionPostingController@store')->name('transaction-postings.store');
            Route::get('transaction-postings/show/{id}', 'TransactionPostingController@show')->name('transaction-postings.show');
            Route::post('transaction-postings/update/{id}', 'TransactionPostingController@update')->name('transaction-postings.update');
            Route::post('transaction-postings/{id}/archive', 'TransactionPostingController@archive')->name('transaction-postings.archive');
            Route::post('transaction-postings/{id}/restore', 'TransactionPostingController@restore')->name('transaction-postings.restore');

            Route::post('transaction-postings/fetch', 'TransactionPostingFetchController@fetch')->name('transaction-postings.fetch');
            Route::post('transaction-postings/fetch?archived=1', 'TransactionPostingFetchController@fetch')->name('transaction-postings.fetch-archive');
            Route::post('transaction-postings/fetch-item/{id?}', 'TransactionPostingFetchController@fetchView')->name('transaction-postings.fetch-item');
            Route::post('transaction-postings/fetch-pagination/{id}', 'TransactionPostingFetchController@fetchPagePagination')->name('transaction-postings.fetch-pagination');
        });

        //////////////////////////////////////
        // Customer Posting Profile Controller//
        /////////////////////////////////////
        Route::namespace('CustomerPostingProfiles')->group(function() {

            Route::get('customer-posting-profiles', 'CustomerPostingProfileController@index')->name('customer-posting-profiles.index');
            Route::get('customer-posting-profiles/create/{id}', 'CustomerPostingProfileController@create')->name('customer-posting-profiles.create');
            Route::post('customer-posting-profiles/store/{id}', 'CustomerPostingProfileController@store')->name('customer-posting-profiles.store');
            Route::get('customer-posting-profiles/show/{id}', 'CustomerPostingProfileController@show')->name('customer-posting-profiles.show');
            Route::post('customer-posting-profiles/update/{id}', 'CustomerPostingProfileController@update')->name('customer-posting-profiles.update');
            Route::post('customer-posting-profiles/{id}/archive', 'CustomerPostingProfileController@archive')->name('customer-posting-profiles.archive');
            Route::post('customer-posting-profiles/{id}/restore', 'CustomerPostingProfileController@restore')->name('customer-posting-profiles.restore');

            Route::post('customer-posting-profiles/fetch', 'CustomerPostingProfileFetchController@fetch')->name('customer-posting-profiles.fetch');
            Route::post('customer-posting-profiles/fetch?archived=1', 'CustomerPostingProfileFetchController@fetch')->name('customer-posting-profiles.fetch-archive');
            Route::post('customer-posting-profiles/fetch-item/{id?}', 'CustomerPostingProfileFetchController@fetchView')->name('customer-posting-profiles.fetch-item');
            Route::post('customer-posting-profiles/fetch-pagination/{id}', 'CustomerPostingProfileFetchController@fetchPagePagination')->name('customer-posting-profiles.fetch-pagination');
        });

        ////////////////////////////// 
        // Procurements Controller //
        //////////////////////////////
        Route::namespace('Procurements')->group(function() {

            Route::get('procurements', 'ProcurementController@index')->name('procurements.index');
            Route::get('procurements/create', 'ProcurementController@create')->name('procurements.create');
            Route::post('procurements/store', 'ProcurementController@store')->name('procurements.store');
            Route::get('procurements/show/{id}', 'ProcurementController@show')->name('procurements.show');
            Route::post('procurements/update/{id}', 'ProcurementController@update')->name('procurements.update');
            Route::post('procurements/{id}/archive', 'ProcurementController@archive')->name('procurements.archive');
            Route::post('procurements/{id}/restore', 'ProcurementController@restore')->name('procurements.restore');

            Route::post('procurements/fetch', 'ProcurementFetchController@fetch')->name('procurements.fetch');
            Route::post('procurements/fetch?archived=1', 'ProcurementFetchController@fetch')->name('procurements.fetch-archive');
            Route::post('procurements/fetch-item/{id?}', 'ProcurementFetchController@fetchView')->name('procurements.fetch-item');
            Route::post('procurements/fetch-pagination/{id}', 'ProcurementFetchController@fetchPagePagination')->name('procurements.fetch-pagination');

        });

        
        //////////////////////////////
        // Vendor Controller        //
        //////////////////////////////
        Route::namespace('Services')->group(function() {

            Route::get('services', 'ServiceController@index')->name('services.index');
            Route::get('services/create', 'ServiceController@create')->name('services.create');
            Route::post('services/store', 'ServiceController@store')->name('services.store');
            Route::get('services/show/{id}', 'ServiceController@show')->name('services.show');
            Route::post('services/update/{id}', 'ServiceController@update')->name('services.update');
            Route::post('services/{id}/archive', 'ServiceController@archive')->name('services.archive');
            Route::post('services/{id}/restore', 'ServiceController@restore')->name('services.restore');

            Route::post('services/fetch', 'ServiceFetchController@fetch')->name('services.fetch');
            Route::post('services/fetch?archived=1', 'ServiceFetchController@fetch')->name('services.fetch-archive');
            Route::post('services/fetch-item/{id?}', 'ServiceFetchController@fetchView')->name('services.fetch-item');
            Route::post('services/fetch-pagination/{id}', 'ServiceFetchController@fetchPagePagination')->name('services.fetch-pagination');
        });

        //////////////////////////////
        // Service Task Controller        //
        //////////////////////////////
        Route::namespace('ServiceTasks')->group(function() {

            Route::get('service-tasks', 'ServiceTaskController@index')->name('service-tasks.index');
            Route::get('service-tasks/create/{service_id}', 'ServiceTaskController@create')->name('service-tasks.create');
            Route::post('service-tasks/store', 'ServiceTaskController@store')->name('service-tasks.store');
            Route::get('service-tasks/show/{id}', 'ServiceTaskController@show')->name('service-tasks.show');
            Route::post('service-tasks/update/{id}', 'ServiceTaskController@update')->name('service-tasks.update');
            Route::post('service-tasks/{id}/archive', 'ServiceTaskController@archive')->name('service-tasks.archive');
            Route::post('service-tasks/{id}/restore', 'ServiceTaskController@restore')->name('service-tasks.restore');

            Route::post('service-tasks/fetch', 'ServiceTaskFetchController@fetch')->name('service-tasks.fetch');
            Route::post('service-tasks/fetch?archived=1', 'ServiceTaskFetchController@fetch')->name('service-tasks.fetch-archive');
            Route::post('service-tasks/fetch-item/{id?}', 'ServiceTaskFetchController@fetchView')->name('service-tasks.fetch-item');
            Route::post('service-tasks/fetch-pagination/{id}', 'ServiceTaskFetchController@fetchPagePagination')->name('service-tasks.fetch-pagination');
        });

        Route::namespace('Deposits')->group(function() {
            Route::get('deposits', 'DepositController@index')->name('deposits.index');
            Route::get('deposits/create', 'DepositController@create')->name('deposits.create');
            Route::get('deposits/show/{id}', 'DepositController@show')->name('deposits.show');
            Route::post('deposits/store', 'DepositController@store')->name('deposits.store');
            Route::post('deposits/update/{id}', 'DepositController@update')->name('deposits.update');
            Route::post('deposits/cancel/{id}', 'DepositController@cancel')->name('deposits.cancel');
            Route::post('deposits/approve/{id}', 'DepositController@approve')->name('deposits.approve');

            Route::post('deposits/{id}/archive', 'DepositController@archive')->name('deposits.archive');
            Route::post('deposits/{id}/restore', 'DepositController@restore')->name('deposits.restore');

            Route::post('deposits/fetch', 'DepositFetchController@fetch')->name('deposits.fetch');
            Route::post('deposits/fetch?archived=1', 'DepositFetchController@fetch')->name('deposits.fetch-archive');
            Route::post('deposits/fetch-item/{id?}', 'DepositFetchController@fetchView')->name('deposits.fetch-item');
            Route::post('deposits/fetch-pagination/{id}', 'DepositFetchController@fetchPagePagination')->name('deposits.fetch-pagination');
        });

        Route::namespace('VendorPaymentMethods')->group(function() {
            Route::get('vendor-payment-methods', 'VendorPaymentMethodController@index')->name('vendor-payment-methods.index');
            Route::get('vendor-payment-methods/create', 'VendorPaymentMethodController@create')->name('vendor-payment-methods.create');
            Route::get('vendor-payment-methods/show/{id}', 'VendorPaymentMethodController@show')->name('vendor-payment-methods.show');
            Route::post('vendor-payment-methods/store', 'VendorPaymentMethodController@store')->name('vendor-payment-methods.store');
            Route::post('vendor-payment-methods/update/{id}', 'VendorPaymentMethodController@update')->name('vendor-payment-methods.update');
            Route::post('vendor-payment-methods/cancel/{id}', 'VendorPaymentMethodController@cancel')->name('vendor-payment-methods.cancel');
            Route::post('vendor-payment-methods/approve/{id}', 'VendorPaymentMethodController@approve')->name('vendor-payment-methods.approve');

            Route::post('vendor-payment-methods/{id}/archive', 'VendorPaymentMethodController@archive')->name('vendor-payment-methods.archive');
            Route::post('vendor-payment-methods/{id}/restore', 'VendorPaymentMethodController@restore')->name('vendor-payment-methods.restore');

            Route::post('vendor-payment-methods/fetch', 'VendorPaymentMethodFetchController@fetch')->name('vendor-payment-methods.fetch');
            Route::post('vendor-payment-methods/fetch?archived=1', 'VendorPaymentMethodFetchController@fetch')->name('vendor-payment-methods.fetch-archive');
            Route::post('vendor-payment-methods/fetch-item/{id?}', 'VendorPaymentMethodFetchController@fetchView')->name('vendor-payment-methods.fetch-item');
            Route::post('vendor-payment-methods/fetch-pagination/{id}', 'VendorPaymentMethodFetchController@fetchPagePagination')->name('vendor-payment-methods.fetch-pagination');
        });

        Route::namespace('CustomerPaymentMethods')->group(function() {
            Route::get('customer-payment-methods', 'CustomerPaymentMethodController@index')->name('customer-payment-methods.index');
            Route::get('customer-payment-methods/create', 'CustomerPaymentMethodController@create')->name('customer-payment-methods.create');
            Route::get('customer-payment-methods/show/{id}', 'CustomerPaymentMethodController@show')->name('customer-payment-methods.show');
            Route::post('customer-payment-methods/store', 'CustomerPaymentMethodController@store')->name('customer-payment-methods.store');
            Route::post('customer-payment-methods/update/{id}', 'CustomerPaymentMethodController@update')->name('customer-payment-methods.update');
            Route::post('customer-payment-methods/cancel/{id}', 'CustomerPaymentMethodController@cancel')->name('customer-payment-methods.cancel');
            Route::post('customer-payment-methods/approve/{id}', 'CustomerPaymentMethodController@approve')->name('customer-payment-methods.approve');

            Route::post('customer-payment-methods/{id}/archive', 'CustomerPaymentMethodController@archive')->name('customer-payment-methods.archive');
            Route::post('customer-payment-methods/{id}/restore', 'CustomerPaymentMethodController@restore')->name('customer-payment-methods.restore');

            Route::post('customer-payment-methods/fetch', 'CustomerPaymentMethodFetchController@fetch')->name('customer-payment-methods.fetch');
            Route::post('customer-payment-methods/fetch?archived=1', 'CustomerPaymentMethodFetchController@fetch')->name('customer-payment-methods.fetch-archive');
            Route::post('customer-payment-methods/fetch-item/{id?}', 'CustomerPaymentMethodFetchController@fetchView')->name('customer-payment-methods.fetch-item');
            Route::post('customer-payment-methods/fetch-pagination/{id}', 'CustomerPaymentMethodFetchController@fetchPagePagination')->name('customer-payment-methods.fetch-pagination');
        });

        Route::namespace('Checks')->group(function() {
            Route::get('checks', 'CheckController@index')->name('checks.index');
            Route::get('checks/create', 'CheckController@create')->name('checks.create');
            Route::get('checks/show/{id}', 'CheckController@show')->name('checks.show');
            Route::post('checks/store', 'CheckController@store')->name('checks.store');
            Route::post('checks/update/{id}', 'CheckController@update')->name('checks.update');
            Route::post('checks/cancel/{id}', 'CheckController@cancel')->name('checks.cancel');
            Route::post('checks/approve/{id}', 'CheckController@approve')->name('checks.approve');

            Route::post('checks/{id}/archive', 'CheckController@archive')->name('checks.archive');
            Route::post('checks/{id}/restore', 'CheckController@restore')->name('checks.restore');

            Route::post('checks/fetch', 'CheckFetchController@fetch')->name('checks.fetch');
            Route::post('checks/fetch?archived=1', 'CheckFetchController@fetch')->name('checks.fetch-archive');
            Route::post('checks/fetch-item/{id?}', 'CheckFetchController@fetchView')->name('checks.fetch-item');
            Route::post('checks/fetch-pagination/{id}', 'CheckFetchController@fetchPagePagination')->name('checks.fetch-pagination');
        });

        Route::namespace('LetterCreditPurchases')->group(function() {
            Route::get('letter-credit-purchases', 'LetterCreditPurchaseController@index')->name('letter-credit-purchases.index');
            Route::get('letter-credit-purchases/create', 'LetterCreditPurchaseController@create')->name('letter-credit-purchases.create');
            Route::get('letter-credit-purchases/show/{id}', 'LetterCreditPurchaseController@show')->name('letter-credit-purchases.show');
            Route::post('letter-credit-purchases/store', 'LetterCreditPurchaseController@store')->name('letter-credit-purchases.store');
            Route::post('letter-credit-purchases/update/{id}', 'LetterCreditPurchaseController@update')->name('letter-credit-purchases.update');
            Route::post('letter-credit-purchases/close/{id}', 'LetterCreditPurchaseController@close')->name('letter-credit-purchases.close');
            Route::post('letter-credit-purchases/confirm/{id}', 'LetterCreditPurchaseController@confirm')->name('letter-credit-purchases.confirm');
            Route::post('letter-credit-purchases/amendment/{id}', 'LetterCreditPurchaseController@amendment')->name('letter-credit-purchases.amendment');

            Route::post('letter-credit-purchases/{id}/archive', 'LetterCreditPurchaseController@archive')->name('letter-credit-purchases.archive');
            Route::post('letter-credit-purchases/{id}/restore', 'LetterCreditPurchaseController@restore')->name('letter-credit-purchases.restore');

            Route::post('letter-credit-purchases/fetch', 'LetterCreditPurchaseFetchController@fetch')->name('letter-credit-purchases.fetch');
            Route::post('letter-credit-purchases/fetch?archived=1', 'LetterCreditPurchaseFetchController@fetch')->name('letter-credit-purchases.fetch-archive');
            Route::post('letter-credit-purchases/fetch-item/{id?}', 'LetterCreditPurchaseFetchController@fetchView')->name('letter-credit-purchases.fetch-item');
            Route::post('letter-credit-purchases/fetch-pagination/{id}', 'LetterCreditPurchaseFetchController@fetchPagePagination')->name('letter-credit-purchases.fetch-pagination');
        });

        Route::namespace('LetterCreditSales')->group(function() {
            Route::get('letter-credit-sales', 'LetterCreditSalesController@index')->name('letter-credit-sales.index');
            Route::get('letter-credit-sales/create', 'LetterCreditSalesController@create')->name('letter-credit-sales.create');
            Route::get('letter-credit-sales/show/{id}', 'LetterCreditSalesController@show')->name('letter-credit-sales.show');
            Route::post('letter-credit-sales/store', 'LetterCreditSalesController@store')->name('letter-credit-sales.store');
            Route::post('letter-credit-sales/update/{id}', 'LetterCreditSalesController@update')->name('letter-credit-sales.update');
            Route::post('letter-credit-sales/close/{id}', 'LetterCreditSalesController@close')->name('letter-credit-sales.close');
            Route::post('letter-credit-sales/confirm/{id}', 'LetterCreditSalesController@confirm')->name('letter-credit-sales.confirm');
            Route::post('letter-credit-sales/amendment/{id}', 'LetterCreditSalesController@amendment')->name('letter-credit-sales.amendment');

            Route::post('letter-credit-sales/{id}/archive', 'LetterCreditSalesController@archive')->name('letter-credit-sales.archive');
            Route::post('letter-credit-sales/{id}/restore', 'LetterCreditSalesController@restore')->name('letter-credit-sales.restore');

            Route::post('letter-credit-sales/fetch', 'LetterCreditSalesFetchController@fetch')->name('letter-credit-sales.fetch');
            Route::post('letter-credit-sales/fetch?archived=1', 'LetterCreditSalesFetchController@fetch')->name('letter-credit-sales.fetch-archive');
            Route::post('letter-credit-sales/fetch-item/{id?}', 'LetterCreditSalesFetchController@fetchView')->name('letter-credit-sales.fetch-item');
            Route::post('letter-credit-sales/fetch-pagination/{id}', 'LetterCreditSalesFetchController@fetchPagePagination')->name('letter-credit-sales.fetch-pagination');
        });

        Route::namespace('BankDocuments')->group(function() {
            Route::get('bank-documents', 'BankDocumentController@index')->name('bank-documents.index');
            Route::get('bank-documents/create', 'BankDocumentController@create')->name('bank-documents.create');
            Route::get('bank-documents/show/{id}', 'BankDocumentController@show')->name('bank-documents.show');
            Route::post('bank-documents/store', 'BankDocumentController@store')->name('bank-documents.store');
            Route::post('bank-documents/update/{id}', 'BankDocumentController@update')->name('bank-documents.update');

            Route::post('bank-documents/{id}/archive', 'BankDocumentController@archive')->name('bank-documents.archive');
            Route::post('bank-documents/{id}/restore', 'BankDocumentController@restore')->name('bank-documents.restore');

            Route::post('bank-documents/fetch', 'BankDocumentFetchController@fetch')->name('bank-documents.fetch');
            Route::post('bank-documents/fetch?archived=1', 'BankDocumentFetchController@fetch')->name('bank-documents.fetch-archive');
            Route::post('bank-documents/fetch-item/{id?}', 'BankDocumentFetchController@fetchView')->name('bank-documents.fetch-item');
            Route::post('bank-documents/fetch-pagination/{id}', 'BankDocumentFetchController@fetchPagePagination')->name('bank-documents.fetch-pagination');
        });

        Route::namespace('LetterOfGuarantees')->group(function() {
            Route::get('letter-of-guarantees', 'LetterOfGuaranteeController@index')->name('letter-of-guarantees.index');
            Route::get('letter-of-guarantees/create', 'LetterOfGuaranteeController@create')->name('letter-of-guarantees.create');
            Route::get('letter-of-guarantees/show/{id}', 'LetterOfGuaranteeController@show')->name('letter-of-guarantees.show');
            Route::post('letter-of-guarantees/store', 'LetterOfGuaranteeController@store')->name('letter-of-guarantees.store');
            Route::post('letter-of-guarantees/update/{id}', 'LetterOfGuaranteeController@update')->name('letter-of-guarantees.update');
            Route::post('letter-of-guarantees/liquidate/{id}', 'LetterOfGuaranteeController@liquidate')->name('letter-of-guarantees.liquidate');
            Route::post('letter-of-guarantees/extend/{id}', 'LetterOfGuaranteeController@extend')->name('letter-of-guarantees.extend');
            Route::post('letter-of-guarantees/approve/{id}', 'LetterOfGuaranteeController@approve')->name('letter-of-guarantees.approve');

            Route::post('letter-of-guarantees/{id}/archive', 'LetterOfGuaranteeController@archive')->name('letter-of-guarantees.archive');
            Route::post('letter-of-guarantees/{id}/restore', 'LetterOfGuaranteeController@restore')->name('letter-of-guarantees.restore');

            Route::post('letter-of-guarantees/fetch', 'LetterOfGuaranteeFetchController@fetch')->name('letter-of-guarantees.fetch');
            Route::post('letter-of-guarantees/fetch?archived=1', 'LetterOfGuaranteeFetchController@fetch')->name('letter-of-guarantees.fetch-archive');
            Route::post('letter-of-guarantees/fetch-item/{id?}', 'LetterOfGuaranteeFetchController@fetchView')->name('letter-of-guarantees.fetch-item');
            Route::post('letter-of-guarantees/fetch-pagination/{id}', 'LetterOfGuaranteeFetchController@fetchPagePagination')->name('letter-of-guarantees.fetch-pagination');
        });

        Route::namespace('BankFacilityGroups')->group(function() {
            Route::get('bank-facility-groups', 'BankFacilityGroupController@index')->name('bank-facility-groups.index');
            Route::get('bank-facility-groups/create', 'BankFacilityGroupController@create')->name('bank-facility-groups.create');
            Route::get('bank-facility-groups/show/{id}', 'BankFacilityGroupController@show')->name('bank-facility-groups.show');
            Route::post('bank-facility-groups/store', 'BankFacilityGroupController@store')->name('bank-facility-groups.store');
            Route::post('bank-facility-groups/update/{id}', 'BankFacilityGroupController@update')->name('bank-facility-groups.update');

            Route::post('bank-facility-groups/{id}/archive', 'BankFacilityGroupController@archive')->name('bank-facility-groups.archive');
            Route::post('bank-facility-groups/{id}/restore', 'BankFacilityGroupController@restore')->name('bank-facility-groups.restore');

            Route::post('bank-facility-groups/fetch', 'BankFacilityGroupFetchController@fetch')->name('bank-facility-groups.fetch');
            Route::post('bank-facility-groups/fetch?archived=1', 'BankFacilityGroupFetchController@fetch')->name('bank-facility-groups.fetch-archive');
            Route::post('bank-facility-groups/fetch-item/{id?}', 'BankFacilityGroupFetchController@fetchView')->name('bank-facility-groups.fetch-item');
            Route::post('bank-facility-groups/fetch-pagination/{id}', 'BankFacilityGroupFetchController@fetchPagePagination')->name('bank-facility-groups.fetch-pagination');
        });

        Route::namespace('BankFacilityTypes')->group(function() {
            Route::get('bank-facility-types', 'BankFacilityTypeController@index')->name('bank-facility-types.index');
            Route::get('bank-facility-types/create', 'BankFacilityTypeController@create')->name('bank-facility-types.create');
            Route::get('bank-facility-types/show/{id}', 'BankFacilityTypeController@show')->name('bank-facility-types.show');
            Route::post('bank-facility-types/store', 'BankFacilityTypeController@store')->name('bank-facility-types.store');
            Route::post('bank-facility-types/update/{id}', 'BankFacilityTypeController@update')->name('bank-facility-types.update');

            Route::post('bank-facility-types/{id}/archive', 'BankFacilityTypeController@archive')->name('bank-facility-types.archive');
            Route::post('bank-facility-types/{id}/restore', 'BankFacilityTypeController@restore')->name('bank-facility-types.restore');

            Route::post('bank-facility-types/fetch', 'BankFacilityTypeFetchController@fetch')->name('bank-facility-types.fetch');
            Route::post('bank-facility-types/fetch?archived=1', 'BankFacilityTypeFetchController@fetch')->name('bank-facility-types.fetch-archive');
            Route::post('bank-facility-types/fetch-item/{id?}', 'BankFacilityTypeFetchController@fetchView')->name('bank-facility-types.fetch-item');
            Route::post('bank-facility-types/fetch-pagination/{id}', 'BankFacilityTypeFetchController@fetchPagePagination')->name('bank-facility-types.fetch-pagination');
        });

        Route::namespace('CashflowTransactions')->group(function() {
            Route::get('cash-register-transactions', 'CashflowTransactionController@index')->name('cashflow-transactions.index');
            Route::get('cash-register-transactions/create', 'CashflowTransactionController@create')->name('cashflow-transactions.create');
            Route::get('cash-register-transactions/show/{id}', 'CashflowTransactionController@show')->name('cashflow-transactions.show');
            Route::post('cash-register-transactions/store', 'CashflowTransactionController@store')->name('cashflow-transactions.store');
            Route::post('cash-register-transactions/update/{id}', 'CashflowTransactionController@update')->name('cashflow-transactions.update');
            Route::post('cash-register-transactions/cancel/{id}', 'CashflowTransactionController@cancel')->name('cashflow-transactions.cancel');
            Route::post('cash-register-transactions/approve/{id}', 'CashflowTransactionController@approve')->name('cashflow-transactions.approve');
            Route::post('cash-register-transactions/match/{id}', 'CashflowTransactionController@match')->name('cashflow-transactions.match');

            Route::post('cash-register-transactions/{id}/archive', 'CashflowTransactionController@archive')->name('cashflow-transactions.archive');
            Route::post('cash-register-transactions/{id}/restore', 'CashflowTransactionController@restore')->name('cashflow-transactions.restore');

            Route::post('cash-register-transactions/fetch', 'CashflowTransactionFetchController@fetch')->name('cashflow-transactions.fetch');
            Route::post('cash-register-transactions/fetch?archived=1', 'CashflowTransactionFetchController@fetch')->name('cashflow-transactions.fetch-archive');
            Route::post('cash-register-transactions/fetch-item/{id?}', 'CashflowTransactionFetchController@fetchView')->name('cashflow-transactions.fetch-item');
            Route::post('cash-register-transactions/fetch-pagination/{id}', 'CashflowTransactionFetchController@fetchPagePagination')->name('cashflow-transactions.fetch-pagination');
            
            Route::post('cash-register-transaction-adjustments/approve/{id}', 'CashflowTransactionAdjustmentController@approve')->name('cashflow-transaction-adjustments.approve');
            Route::post('cash-register-transaction-adjustments/adjustment/{id}', 'CashflowTransactionAdjustmentController@adjustment')->name('cashflow-transaction-adjustments.adjustment');
            Route::post('cash-register-transaction-adjustments/update/{id}', 'CashflowTransactionAdjustmentController@update')->name('cashflow-transaction-adjustments.update');
            Route::post('cash-register-transaction-adjustments/fetch', 'CashflowTransactionAdjustmentFetchController@fetch')->name('cashflow-transaction-adjustments.fetch');
            Route::post('cash-register-transaction-adjustments/fetch-item/{id?}', 'CashflowTransactionAdjustmentFetchController@fetchView')->name('cashflow-transaction-adjustments.fetch-item');
        });

        Route::namespace('BankAccountStatements')->group(function() {
            Route::get('bank-account-statements', 'BankAccountStatementController@index')->name('bank-account-statements.index');
            Route::get('bank-account-statements/create', 'BankAccountStatementController@create')->name('bank-account-statements.create');
            Route::get('bank-account-statements/show/{id}', 'BankAccountStatementController@show')->name('bank-account-statements.show');
            Route::post('bank-account-statements/store', 'BankAccountStatementController@store')->name('bank-account-statements.store');
            Route::post('bank-account-statements/update/{id}', 'BankAccountStatementController@update')->name('bank-account-statements.update');
            Route::post('bank-account-statements/cancel/{id}', 'BankAccountStatementController@cancel')->name('bank-account-statements.cancel');
            Route::post('bank-account-statements/approve/{id}', 'BankAccountStatementController@approve')->name('bank-account-statements.approve');

            Route::post('bank-account-statements/{id}/archive', 'BankAccountStatementController@archive')->name('bank-account-statements.archive');
            Route::post('bank-account-statements/{id}/restore', 'BankAccountStatementController@restore')->name('bank-account-statements.restore');

            Route::post('bank-account-statements/fetch', 'BankAccountStatementFetchController@fetch')->name('bank-account-statements.fetch');
            Route::post('bank-account-statements/fetch?archived=1', 'BankAccountStatementFetchController@fetch')->name('bank-account-statements.fetch-archive');
            Route::post('bank-account-statements/fetch-item/{id?}', 'BankAccountStatementFetchController@fetchView')->name('bank-account-statements.fetch-item');
            Route::post('bank-account-statements/fetch-pagination/{id}', 'BankAccountStatementFetchController@fetchPagePagination')->name('bank-account-statements.fetch-pagination');
        });

        Route::namespace('BankAccountStatementLines')->group(function() {
            Route::get('bank-account-statement-lines', 'BankAccountStatementLineController@index')->name('bank-account-statement-lines.index');
            Route::get('bank-account-statement-lines/create/{id}', 'BankAccountStatementLineController@create')->name('bank-account-statement-lines.create');
            Route::get('bank-account-statement-lines/show/{id}', 'BankAccountStatementLineController@show')->name('bank-account-statement-lines.show');
            Route::post('bank-account-statement-lines/store/{id}', 'BankAccountStatementLineController@store')->name('bank-account-statement-lines.store');
            Route::post('bank-account-statement-lines/update/{id}', 'BankAccountStatementLineController@update')->name('bank-account-statement-lines.update');

            Route::post('bank-account-statement-lines/{id}/archive', 'BankAccountStatementLineController@archive')->name('bank-account-statement-lines.archive');
            Route::post('bank-account-statement-lines/{id}/restore', 'BankAccountStatementLineController@restore')->name('bank-account-statement-lines.restore');
            Route::post('bank-account-statement-lines/match/{id}', 'BankAccountStatementLineController@match')->name('bank-account-statement-lines.match');

            Route::post('bank-account-statement-lines/fetch', 'BankAccountStatementLineFetchController@fetch')->name('bank-account-statement-lines.fetch');
            Route::post('bank-account-statement-lines/fetch?archived=1', 'BankAccountStatementLineFetchController@fetch')->name('bank-account-statement-lines.fetch-archive');
            Route::post('bank-account-statement-lines/fetch-item/{id?}', 'BankAccountStatementLineFetchController@fetchView')->name('bank-account-statement-lines.fetch-item');
            Route::post('bank-account-statement-lines/fetch-pagination/{id}', 'BankAccountStatementLineFetchController@fetchPagePagination')->name('bank-account-statement-lines.fetch-pagination');
            
            Route::post('bank-account-statement-line-adjustments/approve/{id}', 'BankAccountStatementLineAdjustmentController@approve')->name('bank-account-statement-line-adjustments.approve');
            Route::post('bank-account-statement-line-adjustments/adjustment/{id}', 'BankAccountStatementLineAdjustmentController@adjustment')->name('bank-account-statement-line-adjustments.adjustment');
            Route::post('bank-account-statement-line-adjustments/update/{id}', 'BankAccountStatementLineAdjustmentController@update')->name('bank-account-statement-line-adjustments.update');
            Route::post('bank-account-statement-line-adjustments/fetch', 'BankAccountStatementLineAdjustmentFetchController@fetch')->name('bank-account-statement-line-adjustments.fetch');
            Route::post('bank-account-statement-line-adjustments/fetch-item/{id?}', 'BankAccountStatementLineAdjustmentFetchController@fetchView')->name('bank-account-statement-line-adjustments.fetch-item');
        });

        Route::namespace('BankAccountTransactions')->group(function() {
            Route::get('bank-account-transactions', 'BankAccountTransactionController@index')->name('bank-account-transactions.index');
            Route::get('bank-account-transactions/create', 'BankAccountTransactionController@create')->name('bank-account-transactions.create');
            Route::get('bank-account-transactions/show/{id}', 'BankAccountTransactionController@show')->name('bank-account-transactions.show');
            Route::post('bank-account-transactions/store', 'BankAccountTransactionController@store')->name('bank-account-transactions.store');
            Route::post('bank-account-transactions/update/{id}', 'BankAccountTransactionController@update')->name('bank-account-transactions.update');
            Route::post('bank-account-transactions/cancel/{id}', 'BankAccountTransactionController@cancel')->name('bank-account-transactions.cancel');
            Route::post('bank-account-transactions/approve/{id}', 'BankAccountTransactionController@approve')->name('bank-account-transactions.approve');

            Route::post('bank-account-transactions/{id}/archive', 'BankAccountTransactionController@archive')->name('bank-account-transactions.archive');
            Route::post('bank-account-transactions/{id}/restore', 'BankAccountTransactionController@restore')->name('bank-account-transactions.restore');

            Route::post('bank-account-transactions/fetch', 'BankAccountTransactionFetchController@fetch')->name('bank-account-transactions.fetch');
            Route::post('bank-account-transactions/fetch?archived=1', 'BankAccountTransactionFetchController@fetch')->name('bank-account-transactions.fetch-archive');
            Route::post('bank-account-transactions/fetch-item/{id?}', 'BankAccountTransactionFetchController@fetchView')->name('bank-account-transactions.fetch-item');
            Route::post('bank-account-transactions/fetch-pagination/{id}', 'BankAccountTransactionFetchController@fetchPagePagination')->name('bank-account-transactions.fetch-pagination');
        });


        Route::namespace('Specifications')->group(function() {
            Route::get('specifications', 'SpecificationController@index')->name('specifications.index');
            Route::get('specifications/create', 'SpecificationController@create')->name('specifications.create');
            Route::post('specifications/store/', 'SpecificationController@store')->name('specifications.store');
            Route::get('specifications/show/{id}', 'SpecificationController@show')->name('specifications.show');
            Route::post('specifications/update/{id}', 'SpecificationController@update')->name('specifications.update');
            Route::post('specifications/{id}/archive', 'SpecificationController@archive')->name('specifications.archive');
            Route::post('specifications/{id}/restore', 'SpecificationController@restore')->name('specifications.restore');
            
            Route::post('specificationss/fetch', 'SpecificationFetchController@fetch')->name('specifications.fetch');
            Route::post('specificationss/fetch?archived=1', 'SpecificationFetchController@fetch')->name('specifications.fetch-archive');
            Route::post('specificationss/fetch-item/{id?}', 'SpecificationFetchController@fetchView')->name('specifications.fetch-item');
            Route::post('specificationss/fetch-pagination/{id}', 'SpecificationFetchController@fetchPagePagination')->name('specifications.fetch-pagination');
        });    

        Route::namespace('AdminSetups')->group(function() {

            Route::namespace('Clients')->group(function() {
                Route::get('clients', 'ClientController@index')->name('clients.index');
                Route::get('clients/create', 'ClientController@create')->name('client.create');
                Route::post('clients/store', 'ClientController@store')->name('clients.store');
                Route::get('clients/show/{id}', 'ClientController@show')->name('clients.show');
                Route::post('clients/update/{id}', 'ClientController@update')->name('clients.update');
                Route::post('clients/{id}/archive', 'ClientController@archive')->name('clients.archive');
                Route::post('clients/{id}/restore', 'ClientController@restore')->name('clients.restore');

                Route::post('clients/{id}/user-attach/{user?}', 'ClientController@attachToUser')->name('clients.attach-user');
                Route::post('clients/{id}/user-detach/{user?}', 'ClientController@detachToUser')->name('clients.detach-user');
    
                Route::post('clients/fetch', 'ClientFetchController@fetch')->name('clients.fetch');
                Route::post('clients/fetch?archived=1', 'ClientFetchController@fetch')->name('clients.fetch-archive');
                Route::post('clients/fetch-item/{id?}', 'ClientFetchController@fetchView')->name('clients.fetch-item');
                Route::post('clients/fetch-pagination/{id}', 'ClientFetchController@fetchPagePagination')->name('client.fetch-pagination');
            });

            Route::namespace('ClientBankAccounts')->group(function() {
                Route::get('client-bank-accounts', 'ClientBankAccountController@index')->name('client-bank-accounts.index');
                Route::get('client-bank-accounts/create', 'ClientBankAccountController@create')->name('client-bank-accounts.create');
                Route::get('client-bank-accounts/show/{id}', 'ClientBankAccountController@show')->name('client-bank-accounts.show');
                Route::post('client-bank-accounts/store', 'ClientBankAccountController@store')->name('client-bank-accounts.store');
                Route::post('client-bank-accounts/update/{id}', 'ClientBankAccountController@update')->name('client-bank-accounts.update');

                Route::post('client-bank-accounts/{id}/archive', 'ClientBankAccountController@archive')->name('client-bank-accounts.archive');
                Route::post('client-bank-accounts/{id}/restore', 'ClientBankAccountController@restore')->name('client-bank-accounts.restore');

                Route::post('client-bank-accounts/fetch', 'ClientBankAccountFetchController@fetch')->name('client-bank-accounts.fetch');
                Route::post('client-bank-accounts/fetch?archived=1', 'ClientBankAccountFetchController@fetch')->name('client-bank-accounts.fetch-archive');
                Route::post('client-bank-accounts/fetch-item/{id?}', 'ClientBankAccountFetchController@fetchView')->name('client-bank-accounts.fetch-item');
                Route::post('client-bank-accounts/fetch-pagination/{id}', 'ClientBankAccountFetchController@fetchPagePagination')->name('client-bank-accounts.fetch-pagination');
            });

            Route::namespace('BankReasons')->group(function() {
                Route::get('bank-reasons', 'BankReasonController@index')->name('bank-reasons.index');
                Route::get('bank-reasons/create', 'BankReasonController@create')->name('bank-reasons.create');
                Route::get('bank-reasons/show/{id}', 'BankReasonController@show')->name('bank-reasons.show');
                Route::post('bank-reasons/store', 'BankReasonController@store')->name('bank-reasons.store');
                Route::post('bank-reasons/update/{id}', 'BankReasonController@update')->name('bank-reasons.update');

                Route::post('bank-reasons/{id}/archive', 'BankReasonController@archive')->name('bank-reasons.archive');
                Route::post('bank-reasons/{id}/restore', 'BankReasonController@restore')->name('bank-reasons.restore');

                Route::post('bank-reasons/fetch', 'BankReasonFetchController@fetch')->name('bank-reasons.fetch');
                Route::post('bank-reasons/fetch?archived=1', 'BankReasonFetchController@fetch')->name('bank-reasons.fetch-archive');
                Route::post('bank-reasons/fetch-item/{id?}', 'BankReasonFetchController@fetchView')->name('bank-reasons.fetch-item');
                Route::post('bank-reasons/fetch-pagination/{id}', 'BankReasonFetchController@fetchPagePagination')->name('bank-reasons.fetch-pagination');
            });

            Route::namespace('Companies')->group(function() {
                Route::get('companies', 'CompanyController@index')->name('companies.index');
                Route::get('companies/create', 'CompanyController@create')->name('companies.create');
                Route::post('companies/store', 'CompanyController@store')->name('companies.store');
                Route::get('companies/show/{id}', 'CompanyController@show')->name('companies.show');
                Route::post('companies/update/{id}', 'CompanyController@update')->name('companies.update');
                Route::post('companies/{id}/archive', 'CompanyController@archive')->name('companies.archive');
                Route::post('companies/{id}/restore', 'CompanyController@restore')->name('companies.restore');
    
                Route::post('companies/fetch', 'CompanyFetchController@fetch')->name('companies.fetch');
                Route::post('companies/fetch?archived=1', 'CompanyFetchController@fetch')->name('companies.fetch-archive');
                Route::post('companies/fetch-item/{id?}', 'CompanyFetchController@fetchView')->name('companies.fetch-item');
                Route::post('companies/fetch-pagination/{id}', 'CompanyFetchController@fetchPagePagination')->name('companies.fetch-pagination');
            });

            Route::namespace('Departments')->group(function() {
                Route::get('departments/create/{company?}', 'DepartmentController@create')->name('departments.create');
                Route::post('departments/store', 'DepartmentController@store')->name('departments.store');
                Route::post('departments/update/{id}', 'DepartmentController@update')->name('departments.update');
                Route::post('departments/{id}/archive', 'DepartmentController@archive')->name('departments.archive');
                Route::post('departments/{id}/restore', 'DepartmentController@restore')->name('departments.restore');
                Route::get('departments/{company?}', 'DepartmentController@index')->name('departments.index');
                Route::get('departments/show/{id}/{company?}', 'DepartmentController@show')->name('departments.show');
                
                Route::post('departments/fetch', 'DepartmentFetchController@fetch')->name('departments.fetch');
                Route::post('departments/fetch?archived=1', 'DepartmentFetchController@fetch')->name('departments.fetch-archive');
                Route::post('departments/fetch-item/{id?}', 'DepartmentFetchController@fetchView')->name('departments.fetch-item');
                Route::post('departments/fetch-pagination/{id}', 'DepartmentFetchController@fetchPagePagination')->name('departments.fetch-pagination');
            });


            Route::namespace('Positions')->group(function() {
                Route::get('positions/create/{company?}', 'PositionController@create')->name('positions.create');      
                Route::post('positions/store', 'PositionController@store')->name('positions.store');
                Route::post('positions/update/{id}', 'PositionController@update')->name('positions.update');
                Route::post('positions/{id}/archive', 'PositionController@archive')->name('positions.archive');
                Route::post('positions/{id}/restore', 'PositionController@restore')->name('positions.restore');
                Route::get('positions/{company?}', 'PositionController@index')->name('positions.index');    
                Route::get('positions/show/{id}/{company?}', 'PositionController@show')->name('positions.show');
    
                Route::post('positions/fetch', 'PositionFetchController@fetch')->name('positions.fetch');
                Route::post('positions/fetch?archived=1', 'PositionFetchController@fetch')->name('positions.fetch-archive');
                Route::post('positions/fetch-item/{id?}', 'PositionFetchController@fetchView')->name('positions.fetch-item');
                Route::post('positions/fetch-pagination/{id}', 'PositionFetchController@fetchPagePagination')->name('positions.fetch-pagination');
            });
    
            Route::namespace('Users')->group(function() {
                Route::get('users/create/{company?}', 'UserController@create')->name('users.create');
                Route::post('users/store', 'UserController@store')->name('users.store');
                Route::post('users/update/{id}', 'UserController@update')->name('users.update');
                Route::post('users/{id}/archive', 'UserController@archive')->name('users.archive');
                Route::post('users/{id}/restore', 'UserController@restore')->name('users.restore');
                Route::get('users/{company?}', 'UserController@index')->name('users.index');
                Route::get('users/show/{id}/{company?}', 'UserController@show')->name('users.show');

                Route::post('users/{id}/update-permission', 'UserController@updatePermissions')->name('users.update-permissions');
                
                Route::post('users/fetch', 'UserFetchController@fetch')->name('users.fetch');
                Route::post('users/fetch?archived=1', 'UserFetchController@fetch')->name('users.fetch-archive');
                Route::post('users/fetch-item/{id?}', 'UserFetchController@fetchView')->name('users.fetch-item');
                Route::post('users/fetch-pagination/{id}', 'UserFetchController@fetchPagePagination')->name('users.fetch-pagination');
            });

            Route::namespace('Admins')->group(function() {
                Route::get('admin-users/create/{type?}', 'AdminUserController@create')->name('admin-users.create');
                Route::post('admin-users/store', 'AdminUserController@store')->name('admin-users.store');
                Route::post('admin-users/update/{id}', 'AdminUserController@update')->name('admin-users.update');
                Route::post('admin-users/{id}/archive', 'AdminUserController@archive')->name('admin-users.archive');
                Route::post('admin-users/{id}/restore', 'AdminUserController@restore')->name('admin-users.restore');
                Route::get('admin-users', 'AdminUserController@index')->name('admin-users.index');
                Route::get('admin-users/show/{id}/{type?}', 'AdminUserController@show')->name('admin-users.show');
            
                
                Route::post('admin-users/fetch', 'AdminUserFetchController@fetch')->name('admin-users.fetch');
                Route::post('admin-users/fetch?archived=1', 'AdminUserFetchController@fetch')->name('admin-users.fetch-archive');
                Route::post('admin-users/fetch-item/{id?}', 'AdminUserFetchController@fetchView')->name('admin-users.fetch-item');
                Route::post('admin-users/fetch-pagination/{id}', 'AdminUserFetchController@fetchPagePagination')->name('admin-users.fetch-pagination');
            });

        });

    });
});