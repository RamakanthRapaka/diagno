<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/users', 'HomeController@users')->name('users');
Route::get('/profiles', 'HomeController@profiles')->name('profiles');
Route::get('/services', 'HomeController@services')->name('services');
Route::get('/orders', 'HomeController@orders')->name('orders');
Route::get('/vieworders/{id}', 'HomeController@vieworders')->name('vieworders');
Route::get('/viewtests/{id}', 'HomeController@viewtests')->name('viewtests');


Route::get('/deleteuser/{id}', 'HomeController@deleteuser')->name('deleteuser');
Route::get('/profile', 'HomeController@profile')->name('profile');
Route::post('/updateuser', 'HomeController@updateUser')->name('updateuser');




Route::post('/deleteorderfile', 'HomeController@deleteorderfile')->name('deleteorderfile');
Route::post('/deleteordertestfile', 'HomeController@deleteordertestfile')->name('deleteordertestfile');
Route::post('/changestatus', 'HomeController@changestatus')->name('changestatus');
Route::get('/categories', 'HomeController@categories')->name('categories');
Route::get('/editcategories/{id}', 'HomeController@editcategories')->name('editcategories');
Route::get('/deletecategories/{id}', 'HomeController@deletecategories')->name('deletecategories');
Route::get('/packages', 'HomeController@packages')->name('packages');

Route::get('/usertests', 'HomeController@usertests')->name('usertests');
Route::get('/editservices/{id}', 'HomeController@editservices')->name('editservices');
Route::get('/deleteservices/{id}', 'HomeController@deleteservices')->name('deleteservices');
Route::post('/saveservices', 'HomeController@saveservices')->name('saveservices');
Route::get('/addservices', 'HomeController@addservices')->name('addservices');

Route::get('/editprofiles/{id}', 'HomeController@editprofiles')->name('editprofiles');
Route::get('/deleteprofiles/{id}', 'HomeController@deleteprofiles')->name('deleteprofiles');
Route::post('/saveprofiles', 'HomeController@saveprofiles')->name('saveprofiles');
Route::get('/addprofiles', 'HomeController@addprofiles')->name('addprofiles');

Route::get('/createpackages', 'HomeController@createpackages')->name('createpackages');
Route::get('/editpackages/{id}', 'HomeController@editpackages')->name('editpackages');
Route::get('/deletepackage/{id}', 'HomeController@deletepackages')->name('deletepackages');
Route::get('/createcategories', 'HomeController@createcategories')->name('createcategories');
Route::post('/saveorupdatecategories', 'HomeController@SaveOrUpdateCategories')->name('saveorupdatecategories');
Route::post('/saveorupdatepackages', 'HomeController@SaveOrUpdatePackages')->name('saveorupdatepackages');
Route::post('/updateorders', 'HomeController@Updateorders')->name('updateorders');
Route::post('/changeuserteststatus', 'HomeController@changeUsertestsstatus')->name('changeuserteststatus');
Route::post('/updateusertests', 'HomeController@UpdateUsertests')->name('updateusertests');

Route::get('/pendingorders', 'HomeController@PendingOrders')->name('pendingorders');
Route::post('/getpendingorders', 'HomeController@GetPendingOrders')->name('getpendingorders');

Route::post('/saveusertestdetails', 'HomeController@saveusertestdetails')->name('saveusertestdetails');
Route::post('/deleteusertestdetails', 'HomeController@deleteusertestdetails')->name('deleteusertestdetails');
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    // return what you want
});









