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

//Route::resource('testing', 'TestingController');

Auth::routes();
Route::get('infophp','TestingController@infophp');
Route::get('adminer','TestingController@adminer');
Route::get('printdot','TestingController@printDM');
Route::get('imp','TestingController@importFileIntoDB');
Route::get('send','TestingController@send');
Route::get('convertbca','AccountingController@convertBCA');
Route::post('convertbca','MakeExcelController@convertBCA');
Route::get('ambildata','TestinganController@krtUjian');

Route::get('mpdf','AccountingController@mPDFGen');

Route::resource('testing','TestingController');
Route::resource('testingan','TestinganController');

Route::get('/', 'HomeController@index')->name('home');

Route::get('search/autocomplete','AutoController@autocomplete');

Route::get('search/autocomplete2','AutoController@autocomplete2');

Route::get('searchkaskecil','AutoController@searchkaskecil');

Route::get('search/jurnalautocomplete','AutoController@autosearch');

Route::get('searchtransaction','KaskecilController@searchtransaction');

Route::get('ja/edit', 'JurnalController@edittransaction');

Route::get('search/transaction', 'JurnalController@search');

Route::get('reqreimburse/{mode}','KaskecilController@requestreimburse');

Route::get('getreimburse','DatareimController@funcsdr');

Route::get('inventory/genDep','InventoryController@genDepreciation');

Route::get('inventory/{idinv}/lock','InventoryController@lockinven');

Route::get('invoices/{invid}/print','InvoicesController@printing');

Route::get('downloadbpb','MakeExcelController@downloadbpb');

Route::get('recalculate','AccountingController@recalculate');

Route::get('getSAA','AccountingController@getSAA');

Route::get('boa','AccountingController@boa');

Route::get('ja/{chequeid}', 'JurnalController@exportJA');

Route::resource('kaskecil', 'KaskecilController');

Route::resource('datareim','DatareimController');

Route::resource('cheque','ChequeController');

Route::resource('inventory','InventoryController');

Route::resource('jurnaladmin','JurnalController');

Route::resource('invoices','InvoicesController');

Route::resource('invoicesdetail','InvoicesDetailController');

Route::resource('main','MainController');

Route::resource('reg','RegistrationController');

Route::get('invoicecancel/{invno}','InvoicesController@cancel');

Route::get('checkInvNo/{invno}','InvoicesController@checkInvNo');

Route::get('cheque/{userid}/cancel','ChequeController@cancel');

Route::get('exportExcel/{checkid}','MakeExcelController@exportExcel');

Route::get('inv/{invno}','InventoryController@setInv');

Route::get('noinv/{invno}','InventoryController@setNoInv');
 
Route::get('checking', 'AlumniController@checking');

Route::get('barcode', 'AlumniController@test');

Route::get('qrLogin', ['uses' => 'AlumniController@qrLogin']);

Route::get('qrRead', 'AlumniController@checkUser');

Route::get('qrsimple','AlumniController@qrsimple');

Route::get('qrsimple2','AlumniController@qrsimple2');

Route::get('mpdf/{mode}','KaskecilController@mPDFGen');

Route::get('mR','TestingController@makeReport');