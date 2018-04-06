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

Route::get('/', function () {
    return redirect('/');
});

Route::get('/p/{hash}/', 'HomeController@view')->name('viewPost');
Route::post('/p/{hash}/', 'HomeController@postApplication')->name('postApplication');
Route::get('/thankyou', 'HomeController@thankyou')->name('thankyou');
Route::get('/verify/{id}/{verification}', 'HomeController@verify')->name('verify');

Route::prefix('pannello')->group(function () {
    Auth::routes();
});

Route::namespace('Panel')->name('panel.')->middleware('auth')->prefix('pannello')->group(function () {
    Route::get('/', 'HomeController@index')->name('index');
    Route::get('/upload', 'HomeController@upload')->name('upload');
    Route::post('/upload', 'HomeController@postUpload')->name('postUpload');

    Route::prefix('profiles')->name('testers.')->group(function () {
        Route::get('/', 'TestersController@index')->name('index');
        Route::get('/view-{profile}', 'TestersController@view')->name('view');
        Route::get('/download', 'TestersController@download')->name('download');
    });
    Route::prefix('posts')->name('forms.')->group(function () {
        Route::get('/', 'FormsController@index')->name('index');
        Route::get('/new', 'FormsController@new')->name('new');
        Route::put('/new', 'FormsController@create')->name('put');
        Route::get('/view-{form}', 'FormsController@view')->name('view');
        Route::get('/edit-{form}', 'FormsController@edit')->name('edit');
        Route::patch('/edit-{form}', 'FormsController@update')->name('update');
        Route::delete('/remove-{form}', 'FormsController@delete')->name('delete');
    });
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', 'CategoriesController@index')->name('index');
        Route::get('/new', 'CategoriesController@create')->name('create');
        Route::put('/new', 'CategoriesController@put')->name('put');
        Route::get('/edit-{cat}', 'CategoriesController@edit')->name('edit');
        Route::patch('/edit-{cat}', 'CategoriesController@update')->name('update');
        Route::delete('/remove-{cat}', 'CategoriesController@delete')->name('delete');
      });
});