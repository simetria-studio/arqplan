<?php

use App\Http\Controllers\CategoriasController;
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

Auth::routes(['register' => false]);

Route::get('/', 'HomeController@index')->name('home');
Route::get('/template', 'HomeController@template')->name('template');
Route::get('/meus-dados', 'HomeController@profile')->name('profile');
Route::post('/meus-dados', 'HomeController@profile_post')->name('profile.edit');
Route::get('/logout', 'HomeController@logout')->name('logout');

// company
Route::get('/empresa', 'CompanyController@index')->name('company');
Route::post('/empresa', 'CompanyController@updatePost')->name('company.update');
Route::get('/empresa/logo', 'CompanyController@logo')->name('company.logo');
Route::get('/empresa/images/{name}', 'CompanyController@images')->name('company.image');
Route::post('/empresa/upload', 'CompanyController@logoUpload')->name('company.logo.upload');

Route::get('/usuarios', 'CompanyUserController@index')->name('company.users');
Route::get('/usuarios/novo', 'CompanyUserController@new')->name('company.users.new');
Route::post('/usuarios/novo', 'CompanyUserController@newPost')->name('company.users.create');
Route::get('/usuarios/{id}/editar', 'CompanyUserController@edit')->name('company.users.show');
Route::post('/usuarios/{id}/editar', 'CompanyUserController@editPost')->name('company.users.edit');
Route::get('/usuarios/{id}/remover', 'CompanyUserController@delete')->name('company.users.delete');
Route::post('/usuarios/{id}/remover', 'CompanyUserController@deletePost')->name('company.users.remove');

// calendar
Route::get('/agenda', 'CalendarController@index')->name('calendar');

// task
Route::get('/atividades', 'TaskController@index')->name('task');
Route::get('/atividades/kanban', 'TaskController@kanban')->name('task.kanban');
Route::get('/atividades/kanban/{project_id}', 'TaskController@kanbanOfProject')->name('task.kanban.project');

//projects
Route::get('/projetos', 'ProjectController@index')->name('project');
Route::get('/projetos/novo', 'ProjectController@new')->name('project.new');
Route::post('/projetos/novo', 'ProjectController@newPost')->name('project.create');
Route::get('/projetos/{code}/ver', 'ProjectController@show')->name('project.show');
Route::get('/projetos/{code}/editar', 'ProjectController@edit')->name('project.edit');
Route::post('/projetos/{code}/editar', 'ProjectController@editPost')->name('project.edit');
Route::get('/projetos/{code}/remover', 'ProjectController@delete')->name('project.delete');
Route::post('/projetos/{code}/remover', 'ProjectController@deletePost')->name('project.remove');

//projectcategories
Route::get('/projetos/categoria', 'ProjectController@category_index')->name('project.category');
Route::get('/projetos/categoria/novo', 'ProjectController@category_new')->name('project.category.new');
Route::post('/projetos/categoria/novo', 'ProjectController@category_newPost')->name('project.category.create');
Route::get('/projetos/categoria/{id}/editar', 'ProjectController@category_edit')->name('project.category.show');
Route::post('/projetos/categoria/{id}/editar', 'ProjectController@category_editPost')->name('project.category.edit');
Route::get('/projetos/categoria/{id}/remover', 'ProjectController@category_delete')->name('project.category.delete');
Route::post('/projetos/categoria/{id}/remover', 'ProjectController@category_deletePost')->name('project.category.remove');

//projectstatus
Route::get('/projetos/status', 'ProjectController@status_index')->name('project.status');
Route::get('/projetos/status/novo', 'ProjectController@status_new')->name('project.status.new');
Route::post('/projetos/status/novo', 'ProjectController@status_newPost')->name('project.status.create');
Route::get('/projetos/status/{id}/editar', 'ProjectController@status_edit')->name('project.status.show');
Route::post('/projetos/status/{id}/editar', 'ProjectController@status_editPost')->name('project.status.edit');
Route::get('/projetos/status/{id}/remover', 'ProjectController@status_delete')->name('project.status.delete');
Route::post('/projetos/status/{id}/remover', 'ProjectController@status_deletePost')->name('project.status.remove');

//projectstep
Route::get('/projetos/etapas', 'ProjectController@step_index')->name('project.step');
Route::get('/projetos/etapas/novo', 'ProjectController@step_new')->name('project.step.new');
Route::post('/projetos/etapas/novo', 'ProjectController@step_newPost')->name('project.step.create');
Route::get('/projetos/etapas/{id}/editar', 'ProjectController@step_edit')->name('project.step.show');
Route::post('/projetos/etapas/{id}/editar', 'ProjectController@step_editPost')->name('project.step.edit');
Route::get('/projetos/etapas/{id}/remover', 'ProjectController@step_delete')->name('project.step.delete');
Route::post('/projetos/etapas/{id}/remover', 'ProjectController@step_deletePost')->name('project.step.remove');


//projectfiles
Route::get('/projetos/{code}/arquivos/{file_id}/open', 'ProjectController@files_open')->name('project.files.open');
Route::get('/projetos/{code}/arquivos/{file_id}/download', 'ProjectController@files_download')->name('project.files.download');
Route::get('/projetos/{code}/arquivos/{file_id}/remove', 'ProjectController@files_remove')->name('project.files.remove');

//clients
Route::get('/clientes', 'ClientController@index')->name('client');
Route::get('/clientes/novo', 'ClientController@new')->name('client.new');
Route::post('/clientes/novo', 'ClientController@newPost')->name('client.create');
Route::get('/clientes/{id}/ver', 'ClientController@show')->name('client.show');
Route::get('/clientes/{id}/editar', 'ClientController@edit')->name('client.edit');
Route::post('/clientes/{id}/editar', 'ClientController@editPost')->name('client.edit');
Route::get('/clientes/{id}/remover', 'ClientController@delete')->name('client.delete');
Route::post('/clientes/{id}/remover', 'ClientController@deletePost')->name('client.remove');

//providers
Route::get('/fornecedores', 'ProviderController@index')->name('provider');
Route::get('/fornecedores/novo', 'ProviderController@new')->name('provider.new');
Route::post('/fornecedores/novo', 'ProviderController@newPost')->name('provider.create');
Route::get('/fornecedores/{id}/ver', 'ProviderController@show')->name('provider.show');
Route::get('/fornecedores/{id}/editar', 'ProviderController@edit')->name('provider.edit');
Route::post('/fornecedores/{id}/editar', 'ProviderController@editPost')->name('provider.edit');
Route::get('/fornecedores/{id}/remover', 'ProviderController@delete')->name('provider.delete');
Route::post('/fornecedores/{id}/remover', 'ProviderController@deletePost')->name('provider.remove');

//reports
Route::get('/relatorios', 'ReportController@index')->name('report');
Route::get('/relatorios/financeiro/contas-a-pagar', 'ReportController@finance_topay')->name('report.finance.topay');
Route::get('/relatorios/financeiro/contas-a-pagar/exportar', 'ReportController@finance_topay_pdf')->name('report.finance.topay.pdf');

Route::get('/relatorios/financeiro/contas-a-receber', 'ReportController@finance_toreceive')->name('report.finance.toreceive');

Route::get('/relatorios/projetos', 'ReportController@projects')->name('report.projects');

Route::get('/relatorios/calendario', 'ReportController@calendar')->name('report.calendar');

//finance
Route::get('/financeiro', 'FinanceController@index')->name('finance');
Route::get('/financeiro/conta/{id}/ver', 'FinanceController@show')->name('finance.show');

//financecategories
Route::get('/financeiro/categoria', 'FinanceController@category_index')->name('finance.category');
Route::get('/financeiro/categoria/novo', 'FinanceController@category_new')->name('finance.category.new');
Route::post('/financeiro/categoria/novo', 'FinanceController@category_newPost')->name('finance.category.create');
Route::get('/financeiro/categoria/{id}/editar', 'FinanceController@category_edit')->name('finance.category.show');
Route::post('/financeiro/categoria/{id}/editar', 'FinanceController@category_editPost')->name('finance.category.edit');
Route::get('/financeiro/categoria/{id}/remover', 'FinanceController@category_delete')->name('finance.category.delete');
Route::post('/financeiro/categoria/{id}/remover', 'FinanceController@category_deletePost')->name('finance.category.remove');


//produtos
Route::get('/produtos', 'ProdutosController@index')->name('products')->middleware('auth');
Route::get('/produtos/create', 'ProdutosController@create')->name('products.create')->middleware('auth');
Route::post('/produtos/store', 'ProdutosController@store')->name('products.store')->middleware('auth');
Route::any('/produtos/delete/{id}', 'ProdutosController@destroy')->name('products.delete')->middleware('auth');
Route::get('/produtos/ver/{id}', 'ProdutosController@show')->name('products.show')->middleware('auth');
Route::post('/produtos/update/{id}', 'ProdutosController@update')->name('products.update')->middleware('auth');


Route::get('/categorias', 'CategoriasController@index')->name('categories')->middleware('auth');
Route::get('/categorias/create', 'CategoriasController@create')->name('categories.create')->middleware('auth');
Route::post('/categorias/store', 'CategoriasController@store')->name('categories.store')->middleware('auth');

Route::post('/product/add', 'ProdutosToProjectsController@store')->name('product.project')->middleware('auth');
Route::post('/product/update', 'ProdutosToProjectsController@update')->name('product.project.update')->middleware('auth');
Route::any('/product/delete/{id}', 'ProdutosToProjectsController@destroy')->name('product.project.delete')->middleware('auth');
Route::post('/produtos/cpe', 'ProdutosToProjectsController@cpe')->name('products.update.cpe')->middleware('auth');
