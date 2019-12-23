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


    function schedulePage(){
        $date = isset($_GET['date']) ? $_GET['date'] : date("Y-m", time());
        $split = explode("-", $date);

        $year = $split[0];
        $month = $split[1];

        $this->view("schedule.php", ["year" => $year, "month" => $month]);
    }

    function getSchedules(){
        $date = isset($_GET['date']) ? $_GET['date'] : date("Y-m", time());
        $split = explode("-", $date);

        $year = $split[0];
        $month = $split[1];
        $findFirst = "{$year}-{$month}-1";

        $lastDay = date("t", strtotime($findFirst)); // t: 달의 마지막 날을 가져옴
        $findEnd = "{$year}-{$month}-{$lastDay}";

        $schedules = DB::fetchAll("SELECT S.*, M.name 
                                   FROM schedules S
                                   LEFT JOIN movies M
                                   ON M.id = S.mid
                                   WHERE date(?) <= date(startTime)
                                   AND date(startTime) <= date(?)", [$findFirst, $findEnd]);

        
        header("Content-Type: application/json");
        echo json_encode($schedules, JSON_UNESCAPED_UNICODE);
    }

    function addSchedulePage(){
        $freeMovies = DB::fetchAll("SELECT M.id, M.name, M.duration, S.id AS sid FROM movies M LEFT JOIN schedules S ON M.id = S.mid WHERE S.id IS NULL");
        $this->view("add_schedule.php", ["freeMovies" => $freeMovies]);
    }

    
    function addSchedule(){
        emptyCheck($_POST);
        extract($_POST);

        $scheduleRegex = "/^[0-9]{4}\/[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{1,2}$/";
        if(!preg_match($scheduleRegex, $s_datetime))
            back("상영일정의 양식이 올바르지 않습니다.");

        $split = explode("/", $s_datetime);
        if($split[1] <= 0 || 12 < $split[1]) back("상영 일정의 양식이 올바르지 않습니다.");
        if($split[2] <= 0 || 31 < $split[2]) back("상영 일정의 양식이 올바르지 않습니다.");
        if($split[3] <  0 || 23 < $split[3]) back("상영 일정의 양식이 올바르지 않습니다.");
        if($split[4] <  0 || 59 < $split[4]) back("상영 일정의 양식이 올바르지 않습니다.");

        $startTime = "{$split[0]}-{$split[1]}-{$split[2]} {$split[3]}:{$split[4]}";

        $movie = DB::fetch("SELECT * FROM movies WHERE id = ?", [$mid]);
        if(!$movie) back("해당 영화가 존재하지 않습니다.");

        $endTime = date("Y-m-d H:i", strtotime($startTime) + $movie->duration);
        
        $exist = DB::fetch("SELECT * FROM schedules
                   WHERE (timestamp(?) <= timestamp(startTime)
                   AND timestamp(startTime) <= timestamp(?))
                   OR (timestamp(?) <= timestamp(endTime)
                   AND timestamp(endTime) <= timestamp(?))
                   OR (timestamp(startTime) <= timestamp(?)
                   AND timestamp(?) <= timestamp(endTime))", [$startTime, $endTime, $startTime, $endTime, $startTime, $startTime]);

        if($exist) back("해당 시각의 상영 예정인 영화가 존재합니다.");
        
        $data = [$mid, $startTime, $endTime];
        DB::query("INSERT INTO schedules(mid, startTime, endTime) VALUES (?, ?, ?)", $data);
        
        go("/schedules", "스케줄이 등록되었습니다.");
    }
}
