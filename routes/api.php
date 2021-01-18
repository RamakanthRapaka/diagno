<?php

use Illuminate\Http\Request;

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

Route::group(['middleware' => 'cors', 'prefix' => '/v1'], function () {

    Route::post('/login', 'UserController@authenticate');

    Route::post('/register', 'UserController@register');

    Route::get('/logout/{api_token}', 'UserController@logout');

    Route::get('/articles', 'ArticleController@index');

    Route::get('/articles/{id}', 'ArticleController@show');

    Route::post('/articles/save', 'ArticleController@store');

    Route::post('/articles/update', 'ArticleController@update');

    Route::get('/articles/delete/{id}/{api_token}', 'ArticleController@delete');
    
    Route::post('sendotp', 'UserController@sendotp');
    
    Route::post('uploaddoc', 'UserController@uploadDoc');
    
    Route::post('getuserreports', 'UserController@GetUserDocs');
    
    Route::post('getuserprescriptionreports', 'UserController@GetUserDocsById');
    
    Route::post('getuserprescriptions', 'UserController@getUserPrescriptions');
    
    Route::post('saveorders', 'UserController@SaveOrders');
    
    Route::post('tests', 'UserController@Tests');
    
    Route::post('saveorupdateaddress', 'UserController@SaveOrUpdateAddress');
    
    Route::post('getuseraddress', 'UserController@GetAddress');
    
    Route::post('updateuser', 'UserController@UserUpdate');
    
    Route::post('getservices', 'UserController@GetServices');
    
    Route::post('getprofiles', 'UserController@GetProfiles');
    
    Route::post('getprofilesservices', 'UserController@GetProfilesServices');
    
    Route::post('getpackages', 'UserController@GetPackages');
    
    Route::post('allpackages', 'UserController@AllPackages');
    
    Route::post('generateorder', 'UserController@GenerateOrder');

    Route::post('saveorupdatepatient', 'UserController@SaveOrUpdatePatient');
    
    Route::post('getallpatients', 'UserController@GetAllPatients');

    Route::post('getuserorders', 'UserController@GetOrdersByUser');
    
    Route::post('getuserorderreports', 'UserController@GetOrderReportsByUser');

    Route::post('deleteaddress', 'UserController@DeleteUserAddress');
    
    Route::post('wallet', 'UserController@GetWallerBalance');

});


