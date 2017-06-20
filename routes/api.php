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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(["prefix" => "v1" , "middleware" => "auth:api"] , function (){
    Route::post("delete_field" , "Api\TableControllerApi@DeleteField")->name("delete_field_api");
    Route::post("restore_field" , "Api\TableControllerApi@RestoreField")->name("restore_field");
    Route::post("storeotion/{table_id}","Api\TableControllerApi@StoreOption")->name("storeotion");
    Route::post("storerender/{table_id}" , "Api\TableControllerApi@StoreFieldRender");
    Route::delete("delete_relationship/{relation_table}/{child_table_id}/{relation_id}" , "Api\TableControllerApi@DeleteRelationship")->name("delete_relationship_api");
    Route::post("table_add/data" , "Api\TableControllerApi@GetRequireData");
});