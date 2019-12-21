<?php
    use app\Route;

    // Main
    Route::set("GET", "/", "MainController@homePage");

    // 부산국제영화제 BIFF
    Route::set("GET", "/biff/intro", "MainController@introPage");
    Route::set("GET", "/biff/info", "MainController@infoPage");

    // 유저 관련 Users
    Route::set("GET", "/users/login", "UserController@loginPage", "guest");
    Route::set("POST", "/users/login", "UserController@loginProcess", "guest");

    Route::set("GET", "/users/register", "UserController@registerPage", "guest");
    Route::set("POST", "/users/register", "UserController@registerProcess", "guest");

    Route::set("GET", "/users/logout", "UserController@logout", "user");

    // 출품신청 Entry
    Route::set("GET", "/entry", "MainController@entryPage", "user");
    Route::set("POST", "/entry", "MainController@addMovie");

    // 상영일정
    Route::set("GET", "/schedule", "MainController@schedulePage");
    Route::set("GET", "/schedule/add", "MainController@addSchedulePage");
    Route::set("POST", "/schedule/add", "MainController@addSchedulePage");

    Route::connect();