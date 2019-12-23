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
        $this->view("add-schedule.php", ["freeMovies" => $freeMovies]);
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


    function scheduleInfoPage(){
        if(!isset($_GET['date']) || !preg_match("/^[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $_GET['date'])){
            back("해당 페이지는 존재하지 않습니다.");
        }
        $date = $_GET['date'];

        $innerSQL = "SELECT M.*, U.user_name, U.user_id FROM movies M, users U WHERE M.uid = U.id";

        $movies = DB::fetchAll("SELECT M.*, S.* 
                                FROM ($innerSQL) M, schedules S 
                                WHERE S.mid = M.id 
                                AND date(S.startTime) = date(?)", [$date]);

        $this->view("/schedule-info.php", ["movies" => $movies, "date" => date("Y년 m월 d일", strtotime($date))]);
    }   

    function searchPage(){
        $keyword = isset($_GET['keyword']) && trim($_GET['keyword']) !== "" ? $_GET['keyword'] : "";
        $type = isset($_GET['type']) && trim($_GET['type']) !== "" ? $_GET['type'] : "";

        $sql = "SELECT U.user_name, U.user_id, M.*
                FROM users U, movies M
                WHERE U.id = M.uid";
        $params = [];
        
        if($keyword !== ""){
            $sql .= " AND name LIKE ?";
            array_push($params, "%{$keyword}%");
        }
        if($type !== ""){
            $sql .= " AND type = ?";
            array_push($params, $type);
        }

        $movies = DB::fetchAll($sql, $params);

        $totalCnt = DB::fetch("SELECT COUNT(*) AS cnt FROM movies")->cnt;

        $this->view("search.php", ["movies" => $movies, "totalCnt" => $totalCnt]);
    }

    function joinContestPage(){
        $this->view("join-contest.php");
    }
    function joinContestProcess(){
        $input = file_get_contents("php://input");
        $data = json_decode($input);

        if(!user()) {
            echo json_encode(["message" => "로그인 후 사용가능합니다.", "result" => false]);
            exit;
        }

        $data = [$data->mtype, $data->contents];
        DB::query("INSERT INTO contest(mtype, contents) VALUES (?, ?)", $data);

        echo json_encode(["message" => "참가가 완료되었습니다.", "result" => true]);
    }

    function contestListPage(){
        $scoreSQL = "SELECT cid, SUM(grade) AS total, COUNT(*) AS cnt FROM scores GROUP BY cid";
        $articles = DB::fetchAll("SELECT C.*, IFNULL(S.total, 0) s_total, IFNULL(S.cnt, 0) s_count
                                  FROM contest C
                                  LEFT JOIN ($scoreSQL) S
                                  ON S.cid = C.id");
        $this->view("contest-list.php", ["articles" => $articles]);
    }

    function contestViewPage(){
        if(!isset($_GET['id']) || !is_numeric($_GET['id'])) back("해당 페이지가 존재하지 않습니다.");

        
        $scoreSQL = "SELECT cid, SUM(grade) AS total, COUNT(*) AS cnt FROM scores WHERE cid = ? GROUP BY cid";
        $find = DB::fetch("SELECT C.*, IFNULL(S.total, 0) s_total, IFNULL(S.cnt, 0) s_count
                            FROM contest C
                            LEFT JOIN ($scoreSQL) S
                            ON S.cid = C.id
                            WHERE id = ?", [$_GET['id'], $_GET['id']]);
        if(!$find) back("해당 게시글이 존재하지 않습니다.");

        $scoreData = null;
        if(user()){
            $scoreData = DB::fetch("SELECT * FROM scores WHERE uid = ? AND cid = ?", [user()->id, $_GET['id']]);
        }
        
        $this->view("contest-view.php", ["article" => $find, "scoreData" => $scoreData]);
    }
    function setScore(){
        emptyCheck($_POST);
        extract($_POST);

        if(!user()) back("로그인 후에 이용하실 수 있습니다.");
        
        $exist = DB::fetch("SELECT * FROM scores WHERE uid = ? AND cid = ?", [user()->id, $id]);
        $message = "";
        if($exist){
            DB::query("UPDATE scores SET grade = ? WHERE id = ?", [$score, $exist->id]);
            $message .= "평점 <{$score}점>으로 수정되었습니다.";
        }
        else {
            DB::query("INSERT INTO scores(cid, uid, grade) VALUES (?, ?, ?)", [$id, user()->id, $score]);
            $message .= "평점 <{$score}점>이 등록되었습니다..";
        }
        
        go("/contest/view?id=$id", $message);
    }
}