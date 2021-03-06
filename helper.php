<?php
    function dump(){
        foreach(func_get_args() as $arg){
            echo "<pre>";
            var_dump($arg);
            echo "</pre>";
        }
    }

    function dd(){
        call_user_func_array("dump", func_get_args());
        exit;
    }

    function go($url, $msg = ""){
        echo "<script>";
        echo "location.assign('$url');";
        if($msg !== "") echo "alert('$msg');";
        echo "</script>";

        exit;
    }

    function back($msg = ""){
        echo "<script>";
        echo "history.back();";
        if($msg !== "") echo "alert('$msg');";
        echo "</script>";

        exit;
    }

    function user(){
        return isset($_SESSION['user']) ? $_SESSION['user'] : false;
    }

    function emptyCheck($target){
        foreach($target as $item) {
            if($item === "") back("모든 정보를 기재해 주십시오");
        }
    }

    function parseTime($integer){
        $hour = (int)($integer / 3600);
        $min  = (int)($integer % 3600 / 60);
        return  "{$hour}시간 {$min}분";
    }

    function parseTime2($str){
        return date("H시 i분", strtotime($str));
    }

    function getMName($id){
        if($id == 1)  return "기생충";
        if($id == 2)  return "극한직업";
        if($id == 3)  return "롱리브더킹";
        if($id == 4)  return "나랏말싸미";
    }