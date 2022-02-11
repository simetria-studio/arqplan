<?php

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

//user
Route::post('/admin/spylogin', 'UserController@admin_spylogin')->name('spylogin');

Route::get('/admin/usuarios', 'UserController@admin_index')->name('user');
Route::get('/admin/usuarios/novo', 'UserController@admin_new')->name('user.new');
Route::post('/admin/usuarios/novo', 'UserController@admin_newPost')->name('user.create');
Route::get('/admin/usuarios/{id}/editar', 'UserController@admin_edit')->name('user.show');
Route::post('/admin/usuarios/{id}/editar', 'UserController@admin_editPost')->name('user.edit');
Route::get('/admin/usuarios/{id}/remover', 'UserController@admin_delete')->name('user.delete');
Route::post('/admin/usuarios/{id}/remover', 'UserController@admin_deletePost')->name('user.remove'); 
Route::get('/admin/usuarios/{id}/reset_password', 'UserController@admin_reset_password')->name('user.reset_password');


//user
Route::get('/admin/empresas', 'CompanyController@admin_index')->name('company');
Route::get('/admin/empresas/novo', 'CompanyController@admin_new')->name('company.new');
Route::post('/admin/empresas/novo', 'CompanyController@admin_newPost')->name('company.create');
Route::get('/admin/empresas/{id}/editar', 'CompanyController@admin_edit')->name('company.show');
Route::post('/admin/empresas/{id}/editar', 'CompanyController@admin_editPost')->name('company.edit');
Route::get('/admin/empresas/{id}/remover', 'CompanyController@admin_delete')->name('company.delete');
Route::post('/admin/empresas/{id}/remover', 'CompanyController@admin_deletePost')->name('company.remove'); 
Route::get('/admin/empresas/{id}/logo', 'CompanyController@admin_logo')->name('company.logo');
Route::post('/admin/empresas/{id}/logoupload', 'CompanyController@admin_logoUpload')->name('company.logo.upload');


//subscribers
Route::get('/admin/inscritos', 'SubscriberController@admin_index')->name('subscribers');


//demonstration requests
Route::get('/admin/solicitacoes_de_demonstracao', 'DemonstrationRequestController@admin_index')->name('demonstration_requests');

//DEV
Route::get('/cache', function() {
    Artisan::call('optimize:clear');
    echo "<link href='/css/app.css' rel='stylesheet'><p style='font-size:10'>".nl2br(Artisan::output())."</p>";
})->name('clear-cache');

Route::get('/migrate', function() {
    Artisan::call('migrate');
    echo "<link href='/css/app.css' rel='stylesheet'><p style='font-size:10'>".nl2br(Artisan::output())."</p>"; 
})->name('migrate');

Route::get('/migrate-refresh', function() {
    Artisan::call('migrate:refresh --seed');
    echo "<link href='/css/app.css' rel='stylesheet'><p style='font-size:10'>".nl2br(Artisan::output())."</p>"; 
})->name('migrate:refresh');

Route::get('/dump', function() {
    shell_exec('dump-autoload');
    echo "<link href='/css/app.css' rel='stylesheet'><p style='font-size:10'>".nl2br("Composer dump-autoload done")."</p>"; 
})->name('dump');
