<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



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

define('COUNTER', 10);

Auth::routes(['register' => false]);

Route::group(['middleware' => ['auth']], function () {

    Route::get('/', 'HomeController@index')->name('home');
    Route::get('chart-line', 'HomeController@chart_line')->name('chart.line');
    Route::get('chart-line1', 'HomeController@chart_line1')->name('chart.line1');
    Route::get('chart-pie', 'HomeController@chart_pie')->name('chart.pie');
    Route::get('chart-pie1', 'HomeController@chart_pie1')->name('chart.pie1');
    Route::get('slide', 'HomeController@slide')->name('slide');

    // sale route
    Route::post('sales/pay', 'SaleController@account_pay')->name('sale.account.pay');
    Route::get('sales/{sale}/print', 'SaleController@print')->name('sale.print');
    Route::get('sales/uncash', 'SaleController@uncash')->name('sale.uncash');
    Route::get('sales/{sale}/pay', 'SaleController@pay')->name('sale.pay');
    Route::get('sales/time-out-close', 'SaleController@time_out_close')->name('sale.time_out.close');
    Route::get('sales/time-out-over', 'SaleController@time_out_over')->name('sale.time_out.over');
    Route::get('sales/{sale}/client', 'SaleController@client')->name('sale.client');
    Route::resource('sales', 'SaleController')->except(['edit', 'update', 'destroy']);

    // purchase route
    Route::post('purchase/pay', 'PurchaseController@account_pay')->name('purchase.account.pay');
    Route::get('purchase/{purchase}/print', 'PurchaseController@print')->name('purchase.print');
    Route::get('purchase/uncash', 'PurchaseController@uncash')->name('purchase.uncash');
    Route::get('purchase/{purchase}/pay', 'PurchaseController@pay')->name('purchase.pay');
    Route::get('purchase/time-out-close', 'PurchaseController@time_out_close')->name('purchase.time_out.close');
    Route::get('purchase/time-out-over', 'PurchaseController@time_out_over')->name('purchase.time_out.over');
    Route::get('purchase/{purchase}/supplier', 'PurchaseController@supplier')->name('purchase.supplier');
    Route::resource('purchase', 'PurchaseController')->except(['edit', 'update', 'destroy']);


    //stock route
    Route::resource('stock', 'StockController')->except(['show']);
    Route::get('stock/transform', 'StockController@transform_form')->name('stock.transform.create');
    Route::get('stock/ajax', 'StockController@transform_ajax')->name('stock.transform.ajax');
    Route::get('stock/select', 'StockController@select_qty')->name('stock.select_qty.ajax');
    // Route::get('stock/item', 'StockController@item_qty')->name('stock.item');
    Route::post('stock/transform', 'StockController@transform')->name('stock.transform.store');
    Route::get('stock/inventory', 'StockController@inventory')->name('stock.inventory');
    Route::post('stock/inventory', 'StockController@inventory_store')->name('stock.inventory.store');
    // Route::get('stock/{stock}', 'StockController@show')->name('stock.show');
    Route::get('stock/decrase', 'StockController@decrase')->name('stock.decrase');



    // Supplier route
    Route::resource('supplier', 'SupplierController');

    // Client route
    Route::resource('client', 'ClientController');
    Route::post('client/get_money', 'ClientController@get_money')->name('client.get_money');
    Route::get('client/give_me/{id}', 'ClientController@give_me')->name('client.give_me');
    Route::post('client/give_me', 'ClientController@account_pay')->name('client.account_pay');
    Route::get('client/account/friend/{client}', 'ClientController@account_friend')->name('client.account_friend');


    // Wared route
    Route::resource('wared', 'WaredController')->only(['index', 'store']);

    // Wared route
    Route::resource('monserf', 'MonserfController')->only(['index', 'store']);

    // Report route
    Route::get('report/purchases/today', 'ReportController@purchase')->name('report.purchases.today');
    Route::get('report/purchases/cash', 'ReportController@cash_purchase')->name('report.purchases.cash');
    Route::post('report/purchases/cash', 'ReportController@purchase_search')->name('report.purchase.cash.search');
    Route::get('report/purchases/uncash', 'ReportController@uncash_purchase')->name('report.purchases.uncash');
    Route::get('report/sales/today', 'ReportController@sale')->name('report.sales.today');
    Route::get('report/sales/cash', 'ReportController@cash_sale')->name('report.sales.cash');
    Route::post('report/sales/cash', 'ReportController@sale_search')->name('report.sales.cash.search');
    Route::get('report/sales/uncash', 'ReportController@uncash_sale')->name('report.sales.uncash');
    Route::get('report/clients/money', 'ReportController@clients_to')->name('report.clients.money');
    Route::get('report/client/{id}/money', 'ReportController@client_to')->name('report.client.money');
    Route::get('report/suppliers/money', 'ReportController@suppliers_from')->name('report.suppliers.money');
    Route::get('report/supplier/{id}/money', 'ReportController@supplier_to')->name('report.supplier.money');
    Route::get('report/client/trans', 'ReportController@clients_trans')->name('report.clients.trans');
    Route::post('report/client/trans', 'ReportController@client_trans')->name('report.client.trans');
    Route::get('report/supplier/trans', 'ReportController@suppliers_trans')->name('report.suppliers.trans');
    Route::post('report/supplier/trans', 'ReportController@supplier_trans')->name('report.supplier.trans');
    Route::get('report/save', 'ReportController@save')->name('report.money.save');
    Route::post('report/save', 'ReportController@save_search')->name('report.money.save.search');
    Route::get('report/bank', 'ReportController@bank')->name('report.money.bank');
    Route::post('report/bank', 'ReportController@bank_search')->name('report.money.bank.search');
    Route::get('report/wared', 'ReportController@wared')->name('report.wared');
    Route::post('report/wared', 'ReportController@wared_search')->name('report.wared.search');
    Route::get('report/monserfe', 'ReportController@monserfe')->name('report.monserfe');
    Route::post('report/monserfe', 'ReportController@monserfe_search')->name('report.monserfe.search');

    // setting route
    Route::get('settings', 'SettingController@index')->name('setting');
    Route::get('items/qty', 'SettingController@item_qty')->name('items.qty');
    Route::post('items/qty', 'SettingController@store_item_qty')->name('items.qty.store');
    Route::resource('settings/category', 'CategoryController')->except(['index', 'show']);
    Route::resource('settings/safe', 'SafeController')->except(['index', 'show']);
    Route::resource('settings/bank', 'BankController')->except(['index', 'show']);
    Route::resource('settings/item', 'ItemController')->except(['index', 'show']);
    Route::resource('settings/expense', 'ExpenseController')->except(['index', 'show']);
    Route::get('setting/tab', 'SettingController@tab')->name('setting.tab');
});
