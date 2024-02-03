<?php

/*
 * WEB
 */

Route::group([
    "prefix" => ADMIN . "/auth",
    "middleware" => "web",
    "namespace" => "Benaaacademy\\Auth\\Controllers"
], function ($route) {
    $route->get('/login', ["as" => "admin.auth.login", "middleware" => "guest", "uses" => "AuthController@login"]);
    $route->post('/login', ["middleware" => "guest", "uses" => "AuthController@login"]);
    $route->get('/forget', ["as" => "admin.auth.forget", "uses" => "AuthController@forget"]);
    $route->post('/forget', ["uses" => "AuthController@forget"]);
    $route->get('/reset/{code}/{reseted?}', ["as" => "admin.auth.reset", "uses" => "AuthController@reset"]);
    $route->post('/reset/{code}/{reseted?}', ["uses" => "AuthController@reset"]);
    $route->get('/logout', ["as" => "admin.auth.logout", "uses" => "AuthController@logout"]);
});


/*
 * API
 */

Route::group([
    "prefix" => API,
    "namespace" => "Benaaacademy\\Auth\\Controllers"
], function ($route) {
    $route->post("/auth/access", "AuthApiController@access");
    $route->post("/auth/revoke", "AuthApiController@revoke");
});



