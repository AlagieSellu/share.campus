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
    if (Auth::check()){
        return redirect(route('home'));
    }
    return view('welcome');
});

Route::post('/register/user', 'OpenController@register')->name('register.user');

Auth::routes();

Route::get('/home', 'HomeController@home')->name('home');

Route::post('/admin/search', 'AdminController@search')->name('admin.search');
Route::post('/admin', 'AdminController@store')->name('admin.store');
Route::get('/admin', 'AdminController@index')->name('admin.index');

Route::get('/profile', 'HomeController@index')->name('profile');
Route::post('/profile/rename', 'HomeController@rename')->name('profile.rename');
Route::post('/profile/password', 'HomeController@password')->name('profile.password');

Route::get('/folders/{id}/delete', 'FolderController@destroy')->name('folders.destroy');
Route::get('/folders/home', 'FolderController@home')->name('folders.home');
Route::resource('folders', 'FolderController', [
    'except' => ['destroy',],
]);

Route::get('/files/{id}/edit', 'FileController@editor')->name('files.editor');
Route::get('/files/{id}/delete', 'FileController@destroy')->name('files.destroy');
Route::get('/files/{id}/download', 'FileController@download')->name('files.download');
Route::post('/files/{id}/create', 'FileController@create')->name('files.create');
Route::post('/files/{id}/store/doc', 'FileController@store_doc')->name('files.store.doc');
Route::resource('files', 'FileController', [
    'except' => ['destroy', 'create'],
]);

Route::get('/boxes/{id}/download', 'BoxController@download')->name('boxes.download');
Route::get('/boxes/{id}/delete', 'BoxController@destroy')->name('boxes.destroy');
Route::post('/boxes/search', 'BoxController@search')->name('boxes.search');
Route::resource('boxes', 'BoxController', [
    'except' => ['destroy',],
]);


Route::get('/shares/{id}/delete', 'ShareController@destroy')->name('shares.destroy');
Route::get('/shares/{id}/file/{file}', 'ShareController@file')->name('shares.file');
Route::resource('shares', 'ShareController', [
    'except' => ['destroy',],
]);
