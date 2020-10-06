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

Route::get('allPost','AllPostController@getPost')->name('getPost');
// Route::post('allPost','AllPostController@addPost')->name('addPost');
Route::put('isLiked','AllPostController@isLiked')->name('isliked');

Route::get('contact','UserController@readContact')->name('readContact');

Route::get('questionnaireLists','QuestionnaireController@getLists')->name('getQuestionnaireLists');

//video uploading//
Route::post('/video/upload', 'VideoUploadController@uploadVideo')->name('createUploadedVideo');

Route::post('/video', 'VideoUploadController@store')->name('uploadVideo');
Route::get('/video','VideoUploadController@index')->name('indexVideo');

//chat
Route::get('/chat/userList', 'ChatController@getContactList')->name('getContactList');
Route::get('/messages', 'MessageController@index')->name('getMessages');
Route::post('/messages', 'MessageController@store')->name('saveMessages');
    //send image
Route::post('/messages/image', 'MessageController@storeChatSendImage')->name('chatSendImageStore');
    //send video
Route::post('/messages/video', 'MessageController@storeChatSendVideo')->name('chatSendVideoStore');
    //send file
Route::post('/messages/file', 'MessageController@storeChatSendFile')->name('chatSendFileStore');
    //send audio
Route::post('/messages/voice', 'MessageController@storeChatSendVoice')->name('chatSendVoiceStore');
    //send map
Route::post('/messages/map', 'MessageController@storeChatSendMap')->name('chatSendMapStore');

//Live Video Streaming//
Route::post('/liveLecture', 'LiveLectureController@registerLecture')->name('registerLecture');
Route::get('/liveLecture', 'LiveLectureController@getLecture')->name('getLecture');
Route::delete('/liveLecture', 'LiveLectureController@deleteLecture')->name('deleteLecture');
Route::put('/liveLecture', 'LiveLectureController@updateLecture')->name('updateLecture');

//user register live lecture//
Route::delete('/user/livelecture', 'RegisterLiveLectureController@unRegisterFromLecture')->name('unRegisterFromLecture');
Route::post('/user/livelecture', 'RegisterLiveLectureController@registerToLecture')->name('registerToLecture');


Route::get('grade','MemberController@getGrade')->name('getGrade');
// Route::get('grade','MemberController@getGrade')->name('getGrade');

Route::get('gradeClass','MemberController@getGradeClass')->name('getGradeClass');

Route::get('fence','FenceController@getFence')->name('getFence');
Route::post('fence','FenceController@storeFence')->name('storeFence');
Route::put('fence','FenceController@updateFence')->name('updateFence');
Route::delete('fence','FenceController@removeFence')->name('removeFence');

Route::get('school','SchoolController@getSchool')->name('getSchool');
Route::post('school','SchoolController@storeSchool')->name('storeSchool');
Route::put('school','SchoolController@updateSchool')->name('updateSchool');
Route::delete('school','SchoolController@removeSchool')->name('removeSchool');

Route::get('grade','GradeController@getGrade')->name('getGrade');
Route::post('grade','GradeController@storeGrade')->name('storeGrade');
Route::put('grade','GradeController@updateGrade')->name('updateGrade');
Route::delete('grade','GradeController@removeGrade')->name('removeGrade');

Route::get('lesson','LessonController@getLesson')->name('getLesson');
Route::post('lesson','LessonController@storeLesson')->name('storeLesson');
Route::put('lesson','LessonController@updateLesson')->name('updateLesson');
Route::delete('lesson','Lessoncontroller@removeGrade')->name('removeGrade');

Route::get('getGrade','MemberController@getGrade')->name('getGrade');
Route::get('getLesson','MemberController@getLesson')->name('getLesson');

Route::get('allLesson','LessonController@getAllLesson')->name('getAllLesson');

Route::get('surveySchool','MemberController@getSurveySchool');
Route::get('surveyGrade','MemberController@getSurveyGrade');
Route::get('surveyLesson','MemberController@getSurveyLesson');

Route::post('fileUpload/image','AppTemplateController@imageUpload');
Route::post('fileUpload/other','AppTemplateController@otherUpload');
Route::post('fileUpload/video','AppTemplateController@videoUpload');
Route::delete('fileUpload/file','AppTemplateController@deleteFile');

Route::get('lessonMember','MemberController@getLessonMember');

Route::post('templateContent','TemplateDetailsController@storeSingleContent');

Route::get('template','AppTemplateController@getTemplate');
Route::post('template','AppTemplateController@storeTemplate');

Route::post('questionnaire','BulletinBoardController@addQuestionnaire')->name('addQuestionnaire');
Route::get('questionnaire','BulletinBoardController@getQuestionnaire')->name('getQuestionnaire');

Route::post('answerBulletin','AnswerBulletinController@addAnswerBulletin')->name('addAnswerBulletin');
Route::get('answerBulletin','AnswerBulletinController@getAnswerBulletin')->name('getAnswerBulletin');

Route::post('comment','CommentController@addComment')->name('addComment');
Route::get('comment','CommentController@getComment')->name('getComment');
Route::delete('comment','CommentController@deleteComment')->name('deleteComment');

Route::post('like','LikeUsersController@addIsLiked');
Route::get('like','LikeUsersController@getIsLiked');
Route::delete('like','LikeUsersController@delIsLiked');

Route::get('applicationLists','BulletinBoardController@getApplicationLists');

Route::put('profile','UserController@updateProfile');