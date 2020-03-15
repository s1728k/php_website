<?php

include('env.php');
include('route.php');

Route::add('/',function()use($app_key){
    include($app_key.'/view/home.php');
},'get','user');
Route::add('/login_form',function()use($app_key){
    include($app_key.'/view/login.php');
},'get');

//==============================End OF Routes===================================
Route::pathNotFound(function($path)use($app_key){
    include($app_key.'/include/404.php');
});

Route::methodNotAllowed(function($path, $method)use($app_key){
    include($app_key.'/include/405.php');
});

Route::runMiddleware(function($route)use($app_key){
    include($app_key.'/middleware/middlewares.php');
});

Route::run('/');
