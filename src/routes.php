<?php

/**
 * API Group
 */
Route::group(array('prefix'=>'api/v1'), function(){
    /** Documents API */
    Route::resource('documents','\\Atlantis\\Content\\Api\\V1\\DocumentController');
});