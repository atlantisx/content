<?php


Route::filter('document.auth', function()
{
    if ( ! Sentry::check())
    {
        return Redirect::to( Config::get('admin::admin.page.public') );
    }
});