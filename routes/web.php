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
Route::get('cobacoba','TestinganController@cobacoba');
Route::get('imp','TestingController@importFileIntoDB');
Route::get('send','TestingController@send');
Route::get('cobagendep','TestinganController@cobagendep');
Route::get('convertbca','AccountingController@convertBCA');
Route::post('convertbca','MakeExcelController@convertBCA');
Route::get('testbpb','InvoicesController@testinput');
Route::get('getInvNo','InvoicesController@getinvno');
Route::get('getInvPemb','PembelianController@getinvno');
Route::get('getBpenb','TestinganController@bpenb');
Route::get('search/barang','AutoController@searchbarang');
Route::get('pembelian/{id}/lock','PembelianController@lockpembelian');
Route::get('laporanka','KaskecilController@laporanKasAdmin');
Route::get('laporanka/mpdf','KaskecilController@laporankampdf');
Route::get('lappembelian','PembelianController@laporan');
Route::get('lappenjualan','PenjualanController@laporan');
Route::get('cancelprint/{id}','InvoicesController@cancelprint');
Route::get('getRD/{tblview}','DatareimController@getReimburseDetail');
Route::get('pdfReport','PembelianController@pdfReport');
Route::get('mutasibank','AccountingController@mutasi');
Route::post('mutasibank','AccountingController@uploadFile');
Route::get('sendKA','AccountingController@sendKA');
Route::get('transKA','KaskecilController@transactions');
Route::get('getAllTrans','AccountingController@getAllTransactions');
//Route::get('ambildata','TestinganController@getSecret');
Route::get('bagi','PembagiController@index');
Route::get('adjust','InventoryController@adjustment');
Route::post('adjust','InventoryController@setAdjustment');
Route::get('search/inven','InventoryController@searchInven');
Route::get('testsidebar','TestingController@sidebartest');

Route::get('mpdf','AccountingController@mPDFGen');

Route::resource('testing','TestingController');
//Route::get('testsaa','AccountingController@getSAA');
Route::resource('testingan','TestinganController');

Route::post('krtUjian','TestinganController@krtUjian');
Route::post('ambildata','TestinganController@getSecret');

Route::get('/', 'HomeController@index')->name('home');

Route::get('search/autocomplete','AutoController@autocomplete');

Route::get('search/autocompleteBOA','AutoController@autocompleteBOA');

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

Route::get('bpenb/{id}/print','BpenbController@print');

Route::get('downloadbpb','MakeExcelController@downloadbpb');

Route::get('recalculate','AccountingController@recalculate');

Route::get('getSAAlama','AccountingController@getSAA');

Route::get('getSAAbaru','AccountingController@getSAA');

Route::get('boa','AccountingController@boa');

Route::get('ja/{chequeid}', 'JurnalController@exportJA');

Route::resource('kaskecil', 'KaskecilController');

Route::resource('datareim','DatareimController');

Route::resource('cheque','ChequeController');

Route::resource('inventory','InventoryController');

Route::resource('jurnaladmin','JurnalController');

Route::resource('invoices','InvoicesController');

Route::resource('bpenb','BpenbController');

Route::resource('invoicesdetail','InvoicesDetailController');

Route::resource('bpenbdetail','BpenbDetailController');

Route::resource('main','MainController');

Route::resource('reg','RegistrationController');

Route::resource('uangmuka','UMController');

Route::resource('kantin','KantinController');

Route::resource('pembelian','PembelianController');

Route::resource('penjualan','PenjualanController');

Route::resource('transferstok','TransferController');

Route::resource('pembeliandetail','PembelianDetailController');

Route::resource('penjualandetail','PenjualanDetailController');

Route::resource('penerimaan','PenerimaanController');

Route::resource('mattest','MattestController');

Route::resource('elisa','ElisaController');

Route::get('invoicecancel/{invno}','InvoicesController@cancel');

Route::get('bpenbcancel/{invno}','BpenbController@cancel');

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