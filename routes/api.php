<?php

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

Route::namespace('Api')
     ->name('api.')
     ->group(function () {
         Route::namespace('Leads')
              ->name('leads.')
              ->group(function () {
                  Route::post('/campaigns/{campaign}/leads/{lead}/callback', 'LeadCallbackController@store')
                       ->name('callback');
              });
     });

Route::middleware('auth:api')
     ->namespace('Api')
     ->group(function () {
         Route::namespace('CustomFields')
              ->group(function () {
                  Route::post('/custom-field-types', 'CustomFieldTypesController@index');

                  Route::post('/custom-field-groups', 'CustomFieldGroupsController@store');

                  Route::patch(
                      '/custom-field-groups/{customFieldGroup}',
                      'CustomFieldGroupsController@update'
                  )->middleware('can:view,customFieldGroup');

                  Route::delete(
                      '/custom-field-groups/{customFieldGroup}',
                      'CustomFieldGroupsController@destroy'
                  )->middleware('can:view,customFieldGroup');

                  Route::post(
                      '/custom-field-groups/{customFieldGroup}/custom-fields/',
                      'CustomFieldsController@store'
                  )->middleware('can:view,customFieldGroup');

                  Route::patch(
                      '/custom-field-groups/{customFieldGroup}/custom-fields/{customField}',
                      'CustomFieldsController@update'
                  )->middleware('can:view,customFieldGroup');

                  Route::delete(
                      '/custom-field-groups/{customFieldGroup}/custom-fields/{customField}',
                      'CustomFieldsController@destroy'
                  )->middleware('can:view,customFieldGroup');
              });

         Route::namespace('UploadLeads')
              ->group(function () {
                  Route::namespace('Process')
                       ->group(function () {
                           Route::post(
                               '/upload-file/{fileUpload}',
                               'FileUploadController@store'
                           )->middleware('can:view,fileUpload');

                           Route::post(
                               '/import-leads/{fileUpload}/check-status',
                               'ValidateAndImportTemporaryLeadController@store'
                           )->middleware('can:view,fileUpload');

                           Route::post(
                               '/copy-temporary-leads/{fileUpload}/check-status',
                               'ImportLeadController@store'
                           )->middleware('can:view,fileUpload');
                       });

                  Route::post(
                      '/upload-file/',
                      'FileUploadsController@store'
                  );

                  Route::post(
                      '/map-fields/{fileUpload}',
                      'MapFieldsController@store'
                  )->middleware('can:view,fileUpload');

                  Route::post(
                      '/import-leads/{fileUpload}',
                      'ValidateAndImportTemporaryLeadsController@store'
                  )->middleware('can:view,fileUpload');

                  Route::post(
                      '/copy-temporary-leads/{fileUpload}',
                      'ImportLeadsController@store'
                  )->middleware('can:view,fileUpload');

                  Route::post(
                      '/import-leads/{fileUpload}/discard-file',
                      'DiscardLeadsController@store'
                  )->middleware('can:view,fileUpload');
              });

         Route::namespace('Campaigns')
              ->group(function () {
                  Route::post(
                      '/campaigns/{campaign}/selected-custom-groups',
                      'SelectedCustomGroupsController@index'
                  )->middleware('can:view,campaign');

                  Route::post(
                      '/campaigns/{campaign}/selected-custom-groups/store',
                      'SelectedCustomGroupsController@store'
                  )->middleware('can:view,campaign');

                  Route::post(
                      '/campaigns/{campaign}/selected-custom-groups/update ',
                      'SelectedCustomGroupsController@update'
                  )->middleware('can:view,campaign');

                  Route::delete(
                      '/campaigns/{campaign}/selected-custom-groups/{selectedCustomFieldGroup} ',
                      'SelectedCustomGroupsController@destroy'
                  )->middleware('can:view,campaign');

                  Route::post(
                      '/campaigns/{campaign}/scopes/',
                      'ScopesController@store'
                  )->middleware('can:view,campaign');

                  Route::post(
                      '/campaigns/{campaign}/scopes/{scope} ',
                      'ScopesController@update'
                  )->middleware('can:view,campaign');

                  Route::delete(
                      '/campaigns/{campaign}/scopes/{scope} ',
                      'ScopesController@destroy'
                  )->middleware('can:view,campaign');

                  Route::post(
                      '/campaigns/{campaign}/available-records ',
                      'AvailableRecordsController@show'
                  )->middleware('can:view,campaign');

                  Route::post(
                      '/campaigns/{campaign}/users',
                      'SelectedUsersController@store'
                  )->middleware('can:view,campaign');

                  Route::post(
                      '/campaigns/{campaign}/users/{user}',
                      'SelectedUsersController@update'
                  )->middleware('can:view,campaign');

                  Route::delete(
                      '/campaigns/{campaign}/users/{user}',
                      'SelectedUsersController@destroy'
                  )->middleware('can:view,campaign');

                  Route::post(
                      '/campaigns/{campaign}/invalid-reasons',
                      'InvalidReasonsController@store'
                  )->middleware('can:view,campaign');

                  Route::delete(
                      '/campaigns/{campaign}/invalid-reasons/{invalid_reason}',
                      'InvalidReasonsController@destroy'
                  )->middleware('can:view,campaign');
              });

         Route::namespace('Tenant')
              ->group(function () {
                  Route::post(
                      '/ip-restriction-type',
                      'IpRestrictionTypeController@update'
                  );
                  Route::post(
                      '/ip-restrictions',
                      'IpRestrictionController@store'
                  );

                  Route::delete(
                      '/ip-restrictions/{restrictedIp}',
                      'IpRestrictionController@destroy'
                  );
              });
     });
