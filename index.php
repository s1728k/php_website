<?php

// Include router class
include('Route.php');
// Add base route (startpage)
Route::add('/kreditbee/',function(){
    include('home.php');
});
// Simple test route that simulates static html file
Route::add('/kreditbee/test',function(){
    echo 'Hello from test.html';
});
// Post route example
Route::add('/kreditbee/contact-form',function(){
    echo '<form method="post"><input type="text" name="test" /><input type="submit" value="send" /></form>';
},'get');
// Post route example
Route::add('/kreditbee/contact-form',function(){
    echo 'Hey! The form has been sent:<br/>';
    print_r($_POST);
},'post');
// Accept only numbers as parameter. Other characters will result in a 404 error
Route::add('/kreditbee/foo/([0-9]*)/bar',function($var1){
    echo $var1.' is a great number!';
});
Route::run('/');
