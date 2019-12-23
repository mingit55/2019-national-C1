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
    Route::set("GET", "/schedules", "MainController@schedulePage");
    Route::set("GET", "/schedules/add", "MainController@addSchedulePage");
    Route::set("POST", "/schedules/add", "MainController@addSchedule");
    Route::set("POST", "/schedules/get", "MainController@getSchedules");

    Route::set("GET", "/schedules/info", "MainController@scheduleInfoPage");

    Route::set("GET", "/search", "MainController@searchPage");

    Route::set("GET", "/contest/list", "MainController@contestListPage");
    Route::set("GET", "/contest/view", "MainController@contestViewPage");
    Route::set("POST", "/contest/score", "MainController@setScore");

    Route::set("GET", "/contest/join", "MainController@joinContestPage");
    Route::set("POST", "/contest/join", "MainController@joinContestProcess");

    

    Route::connect();