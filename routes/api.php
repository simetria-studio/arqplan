<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::post('/login', 'auth\ApiTokenController@login');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->get('/calendar/events', 'CalendarController@api_get_events');
Route::middleware('auth:api')->get('/calendar/lastevents', 'CalendarController@api_get_last_events');
Route::middleware('auth:api')->post('/calendar/new', 'CalendarController@api_create_event');
Route::middleware('auth:api')->post('/calendar/update', 'CalendarController@api_update_event');
Route::middleware('auth:api')->post('/calendar/remove', 'CalendarController@api_remove_event');
Route::middleware('auth:api')->post('/calendar/report', 'CalendarController@api_get_report_data');

Route::middleware('auth:api')->get('/tasks', 'TaskController@api_get_tasks');
Route::middleware('auth:api')->get('/project/{project_id}/tasks', 'TaskController@api_get_tasks_of_project');
Route::middleware('auth:api')->post('/tasks/new', 'TaskController@api_add_task');
Route::middleware('auth:api')->put('/tasks/{taskId}', 'TaskController@api_update_task');
Route::middleware('auth:api')->put('/tasks/{taskId}/status', 'TaskController@api_update_task_status');
Route::middleware('auth:api')->delete('/tasks/{taskId}', 'TaskController@api_delete_task');


Route::middleware('auth:api')->get('/finance/accounts', 'FinanceController@api_get_accounts');
Route::middleware('auth:api')->get('/finance/account/{id}/transactions', 'FinanceController@api_get_transactions');
Route::middleware('auth:api')->post('/finance/account/new', 'FinanceController@api_account_create');
Route::middleware('auth:api')->put('/finance/account/{id}', 'FinanceController@api_account_update');
Route::middleware('auth:api')->delete('/finance/account/{id}', 'FinanceController@api_account_remove');
Route::middleware('auth:api')->get('/finance/last_transactions', 'FinanceController@api_get_last_transactions');
Route::middleware('auth:api')->get('/finance/topay/{range}', 'FinanceController@api_get_to_pay_range');
Route::middleware('auth:api')->get('/finance/toreceive/{range}', 'FinanceController@api_get_to_receive_range');
Route::middleware('auth:api')->post('/finance/account/{id}/transactions/new', 'FinanceController@api_add_transaction');
Route::middleware('auth:api')->put('/finance/account/{id}/transactions/{transactionId}', 'FinanceController@api_update_transaction');
Route::middleware('auth:api')->delete('/finance/account/{id}/transactions/{transactionId}/{recurrentMode}', 'FinanceController@api_delete_transaction');


Route::middleware('auth:api')->post('/finance/report', 'FinanceController@api_get_report_data');

Route::middleware('auth:api')->post('/projetos/{code}/arquivos/upload', 'ProjectController@files_upload')->name('project.files.upload');
Route::middleware('auth:api')->post('/projects/report', 'ProjectController@api_get_report_data');
Route::middleware('auth:api')->get('/projects/count', 'ProjectController@api_get_active_projects');

Route::middleware('auth:api')->get('/clients/count', 'ClientController@api_get_active_clients');

Route::middleware('auth:api')->get('/providers/count', 'ProviderController@api_get_active_providers');

Route::middleware('cors')->post('/new_demonstration_contact', 'DemonstrationRequestController@new_contact');
Route::middleware('cors')->post('/subscribe', 'SubscriberController@subscribe');
