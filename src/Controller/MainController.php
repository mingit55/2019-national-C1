<?php
namespace controller;

use app\DB;

class MainController extends Controller {
    function homePage(){
        $this->view("index.php");
    }

    function introPage(){
        $this->view("intro.php");
    }

    function infoPage(){
        $this->view("info.php");
    }

    function entryPage(){
        $this->view("entry.php");
    }

    function schedulePage(){
        $date = isset($_GET['date']) ? $_GET['date'] : date("Y-m", time());
        $split = explode("-", $date);

        $year = $split[0];
        $month = $split[1];

        $this->view("schedule.php", ["year" => $year, "month" => $month]);
    }

    function addSchedulePage(){
        $freeMovies = DB::fetchAll("SELECT M.id, M.name, M.duration, S.id AS sid FROM movies M LEFT JOIN schedules S ON M.id = S.mid WHERE S.id IS NULL");
        $this->view("add_schedule.php", ["freeMovies" => $freeMovies]);
    }

    function addMovie(){
        emptyCheck($_POST);
        extract($_POST);
        
        $yearRegex = "/^[0-9]{4}$/";
        if($duration <= 0) back("재생 시간은 최소 1초는 넘어야 합니다.");
        if(!preg_match($yearRegex, $c_year)) back("제작년도는 [2019]와 같은 형식으로 입력해 주십시오.");

        $data = [user()->id, $name, $duration, $c_year, $type];

        DB::query("INSERT INTO movies(uid, name, duration, c_year, type) VALUES (?, ?, ?, ?, ?)", $data);
        go("/", "출품신청이 완료되었습니다.");
    }

    function addSchedule(){
        emptyCheck($_POST);
        extract($_POST);

        $data = [$mid, $s_datetime];

        $scheduleRegex = "/^[0-9]{4}\/[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{1,2}$/";
        if(!preg_match($scheduleRegex, $s_datetime))
            back("상영일정의 양식이 올바르지 않습니다.");

        $split = explode("/", $s_datetime);
        if($split[1] <= 0 || 12 < $split[1]) back("상영 일정의 양식이 올바르지 않습니다.");
        if($split[2] <= 0 || 31 < $split[2]) back("상영 일정의 양식이 올바르지 않습니다.");
        if($split[3] <  0 || 23 < $split[3]) back("상영 일정의 양식이 올바르지 않습니다.");
        if($split[4] <  0 || 59 < $split[4]) back("상영 일정의 양식이 올바르지 않습니다.");
        
        
        
        DB::query("INSERT INTO schedules(mid, datetime) VALUES (?, ?)", $data);
        
        go("/schedules", "스케줄이 등록되었습니다.");
    }
}
