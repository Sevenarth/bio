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

Route::prefix('pannello')->group(function () {
    Auth::routes();
});

Route::namespace('Panel')->name('panel.')->middleware('auth')->prefix('pannello')->group(function () {
    Route::get('/', 'HomeController@index')->name('index');
    Route::get('/upload', 'HomeController@uploadForm')->name('upload');
    
    Route::prefix('profiles')->name('testers.')->group(function () {
        Route::get('/', 'TestersController@index')->name('index');
    });
    Route::prefix('posts')->name('forms.')->group(function () {
        Route::get('/', 'FormsController@index')->name('index');
        Route::get('/new', 'FormsController@new')->name('new');
    });
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', 'CategoriesController@index')->name('index');
    });
});