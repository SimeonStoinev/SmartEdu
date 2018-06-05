<?php

Auth::routes();

Route::match(['get', 'post'], '/', 'WelcomeController@index');

Route::get('/home/{page?}', 'HomeController@index')->name('home');
Route::post('/home/{id?}', 'HomeController@createFolder');
Route::post('/delete/folder/{id?}', 'HomeController@deleteFolder');
Route::match(['get', 'post'], '/home/folder/{id?}', 'HomeController@viewFolder');
Route::get('/confirm-account/{token?}', 'Auth\ConfirmController@index');
Route::get('/must-confirm', 'Auth\ConfirmController@mustConfirm');

Route::get('/poll/create/{id?}', 'Poll\PollController@viewCreatePoll');
Route::post('/poll/create/{id?}', 'Poll\PollController@createPoll');
Route::post('/poll/delete/{id?}', 'Poll\PollController@deletePoll');
Route::get('/poll/mine/{page?}', 'Poll\PollController@myPolls');
Route::get('/poll/{slug?}', 'WelcomeController@viewPoll');
Route::post('/poll/', 'WelcomeController@votePoll');
Route::post('/results/{id?}', 'WelcomeController@resultsPollAjax');

Route::get('/test/create/{id?}', 'Test\TestController@viewCreateTest');
Route::post('/test/create/{id?}', 'Test\TestController@createTest');
Route::get('/test/mine/', 'Test\TestController@myTests');
Route::get('/delete/test/{id?}', 'Test\TestController@viewDeleteTest');
Route::post('/delete/test/{id?}', 'Test\TestController@deleteTest');
Route::post('/activate/test/{id?}', 'Test\TestController@activateTest');
Route::post('/close/test/{id?}', 'Test\TestController@closeTest');
Route::get('/view/test/codes/{slug?}', 'Test\TestController@viewTestCodes');
Route::get('/view/test/{slug?}', 'Test\TestController@viewTest');
Route::post('/view/test/{slug?}', 'Test\TestController@finishTest');
Route::get('/test/verify', 'Test\TestController@testsForVerification');
Route::get('/test/verified', 'Test\TestController@verifiedTests');
Route::match(['get', 'post'], '/verify/test/{id?}', 'Test\TestController@verifyTest');
Route::match(['get', 'post'], '/verified/test/{id?}', 'Test\TestController@verifiedTest');
Route::get('/check/test/{student_link?}', 'Test\TestController@checkTest');
Route::get('/student/link', 'Test\TestController@studentLink');



