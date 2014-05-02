<?php

#i: Authorized Only
Route::group(array('before'=>'document.auth'), function(){
    Route::controller('documents','Atlantis\Document\DocumentController');
});


#i: API
Route::group(array('prefix'=>'api/v1'), function(){
    ## Messages API
});