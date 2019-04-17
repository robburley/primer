<?php

Route::middleware('guest')
     ->group(function () {
         Route::get('/login', 'Auth\LoginController@showLoginForm')
              ->name('login');
         Route::post('login', 'Auth\LoginController@login');

         Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
         Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
         Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
         Route::post('password/reset', 'Auth\ResetPasswordController@reset');
     });

Route::middleware('auth', 'restrict-ip', 'allow-if-active')
     ->group(function () {
         Route::get('logout', 'Auth\LoginController@logout')
              ->name('logout');

         Route::get('/', 'DashboardController@index')
              ->name('dashboard');

         Route::redirect('/settings', '/');

         Route::middleware('role:Administrator')
              ->namespace('Settings')
              ->prefix('settings')
              ->name('settings.')
              ->group(function () {
                  Route::prefix('setup')
                       ->name('setup.')
                       ->group(function () {
                           Route::get('/setup', 'SetupController@edit')
                                ->name('edit');
                       });

                  Route::prefix('campaigns')
                       ->name('campaigns.')
                       ->group(function () {
                           Route::get('/', 'CampaignsController@index')
                                ->name('index');
                           Route::get('/create', 'CampaignsController@create')
                                ->name('create');
                           Route::post('', 'CampaignsController@store')
                                ->name('store');
                           Route::get('/{campaign}/edit', 'CampaignsController@edit')
                                ->name('edit')
                                ->middleware('can:view,campaign');
                           Route::patch('/{campaign}', 'CampaignsController@update')
                                ->name('update')
                                ->middleware('can:view,campaign');
                       });

                  Route::prefix('users')
                       ->name('users.')
                       ->group(function () {
                           Route::get('/', 'UsersController@index')
                                ->name('index');
                           Route::get('/create', 'UsersController@create')
                                ->name('create');
                           Route::post('/users', 'UsersController@store')
                                ->name('store');
                           Route::get('/{user}/edit', 'UsersController@edit')
                                ->name('edit')
                                ->middleware('can:view,user');
                           Route::patch('/{user}', 'UsersController@update')
                                ->name('update')
                                ->middleware('can:view,user');
                       });

                  Route::prefix('upload-leads')
                       ->name('upload-leads')
                       ->group(function () {
                           Route::get('/', 'UploadLeadsController@index')
                                ->name('.index');
                           Route::get('/{fileUpload}/invalid-leads', 'InvalidLeadsController@show')
                                ->name('invalid-leads')->middleware('can:view,fileUpload');
                       });

                  Route::get('/custom-fields', 'CustomFieldsController@index')
                       ->name('custom-fields.index');
              });

         Route::namespace('Campaigns')
              ->prefix('campaigns')
              ->name('campaigns.leads.')
              ->group(function () {
                  Route::name('confirmation.')
                       ->group(function () {
                           Route::get('/{campaign}/leads/confirmation/request', 'RequestConfirmationController@store')
                                ->name('store');

                           Route::get('/{campaign}/leads/{lead}/confirmation', 'ConfirmationLeadsController@edit')
                                ->name('edit');

                           Route::post('/{campaign}/leads/{lead}/confirmation', 'ConfirmationLeadsController@update')
                                ->name('update');

                           Route::delete(
                               '/{campaign}/leads/{lead}/confirmation',
                               'ConfirmationLeadsController@destroy'
                           )->name('destroy');
                       });

                  Route::name('notes.')
                       ->group(function () {
                           Route::post('/{campaign}/leads/{lead}/notes', 'LeadNotesController@store')
                                ->name('store');
                       });

                  Route::get('/{campaign}/leads/request', 'RequestLeadController@store')
                       ->name('request');

                  Route::get('/{campaign}/leads/new', 'LeadsController@create')
                       ->name('create');

                  Route::post('/{campaign}/leads/', 'LeadsController@store')
                       ->name('store');

                  Route::get('/{campaign}/leads/{lead}', 'LeadsController@edit')
                       ->name('edit')
                       ->middleware('can:view,lead,campaign');

                  Route::post('/{campaign}/leads/{lead}', 'LeadsController@update')
                       ->name('update')
                       ->middleware('can:view,lead,campaign');
              });

         Route::namespace('Agent')
              ->prefix('agent')
              ->name('agent.')
              ->group(function () {
                  Route::get('/callbacks', 'CallbacksController@index')
                       ->name('callbacks.index');

                  Route::get('/invalid', 'InvalidLeadsController@index')
                       ->name('invalid.index');
              });

         Route::namespace('Supervisor')
              ->prefix('supervisor')
              ->name('supervisor.')
              ->group(function () {
                  Route::post('search/completed', 'SearchCompletedLeadsController@index')
                       ->name('search.completed.index');

                  Route::post('/search/completed/{campaign}/leads/{lead}', 'SearchCompletedLeadsController@store')
                       ->name('search.completed.store')
                       ->middleware('can:view,lead,campaign');

                  Route::namespace('Reports')
                       ->prefix('reports')
                       ->name('reports.')
                       ->group(function () {
                           Route::get('/reports', 'ReportsController@index')
                                ->name('index');

                           Route::get('/reports/agents', 'AgentsReportsController@index')
                                ->name('agents.index');
                       });
              });
     });

Route::get('/files/{tenant}/{leadFile}/{hash}', 'Files\LeadFilesController@show')
     ->name('files.show');
