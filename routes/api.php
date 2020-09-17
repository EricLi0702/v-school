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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('tag','TagController@addTag')->name('addTag');
Route::get('tag','TagController@getTag')->name('getTag');
Route::put('tag','TagController@editTag')->name('editTag');
Route::delete('tag','TagController@deleteTag')->name('deleteTag');


Route::post('category/upload','CategoryController@uploadFile')->name('uploadFile');
Route::delete('category/upload','CategoryController@deleteFile')->name('deleteFile');

Route::post('/category','CategoryController@addCategory')->name('addCategory');
Route::get('/category','CategoryController@getCategory')->name('addCategory');
Route::put('/category','CategoryController@editCategory')->name('addCategory');
Route::delete('/category','CategoryController@deleteCategory')->name('addCategory');

Route::post('users','UserController@createUser')->name('createUser');
Route::get('users','UserController@readUser')->name('readUser');
Route::put('users','UserController@updateUser')->name('updateUser');
Route::delete('users','UserController@deleteUser')->name('deleteUser');

Route::post('login', 'UserController@login')->name('login');

Route::post('role','UserRoleController@createRole')->name('createRole');
Route::get('role','UserRoleController@readRole')->name('readRole');
Route::put('role','UserRoleController@updateRole')->name('updateRole');
Route::delete('role','UserRoleController@deleteRole')->name('deleteRole');


Route::post('assignRoles','UserRoleController@assignRole')->name('assignRole');

Route::get('allPost','AllPostController@getPost')->name('readRole');
Route::put('isLiked','AllPostController@isLiked')->name('isliked');

Route::get('contact','UserController@readContact')->name('readContact');

Route::get('questionnaireLists','QuestionnaireController@getLists')->name('getQuestionnaireLists');

//video uploading//
Route::post('/video/upload', 'VideoUploadController@uploadVideo')->name('createUploadedVideo');

Route::post('/video', 'VideoUploadController@store')->name('uploadVideo');
Route::get('/video','VideoUploadController@index')->name('indexVideo');

//Live Video Streaming//
Route::post('/liveLecture', 'LiveLectureController@registerLecture')->name('registerLecture');
Route::get('/liveLecture', 'LiveLectureController@getLecture')->name('getLecture');
Route::delete('/liveLecture', 'LiveLectureController@deleteLecture')->name('deleteLecture');
Route::put('/liveLecture', 'LiveLectureController@updateLecture')->name('updateLecture');

//user register live lecture//
Route::delete('/user/livelecture', 'RegisterLiveLectureController@unRegisterFromLecture')->name('unRegisterFromLecture');
Route::post('/user/livelecture', 'RegisterLiveLectureController@registerToLecture')->name('registerToLecture');


Route::get('grade','MemberController@getGrade')->name('getGrade');

Route::get('gradeClass','MemberController@getGradeClass')->name('getGradeClass');

Route::get('fence','FenceController@getFence')->name('getFence');
Route::post('fence','FenceController@storeFence')->name('storeFence');
