<?php
namespace controller;

use app\DB;

class UserController extends Controller {
    function loginPage(){
        $this->view("login.php");
    }

    function loginProcess(){
        emptyCheck($_POST);
        extract($_POST);

            $find = DB::fetch("SELECT * FROM users WHERE user_id = ?", [$user_id]);

            if(!$find) back("해당 유저가 존재하지 않습니다.");
            if($find->password !== hash("sha256", $password)) back("비밀번호가 일치하지 않습니다.");

            $_SESSION['permission'] = $find->user_id === "admin";
            $_SESSION['user'] = $find;

        go("/", "로그인 되었습니다.");
    }

    function registerPage(){
        $this->view("register.php");
    }

    function registerProcess(){
        emptyCheck($_POST);
        extract($_POST);

        $all_number = "/^([0-9]+)$/";
        $mix_numstr = "/^([0-9a-zA-Z]+)$/";
        $korean = "/^([ㄱ-ㅎㅏ-ㅣ가-힣]+)$/";

        if(preg_match($all_number, $user_id) || !preg_match($mix_numstr, $user_id)) 
            back("아이디는 [영문, 영문숫자조합] 으로만 구성되어야 합니다.");

        if(mb_strlen($password) < 8) 
            back("비밀번호는 [8자리 이상] 으로만 구성되어야 합니다.");
        
        if(mb_strlen($user_name) > 4 || !preg_match($korean, $user_name)) 
            back("이름은 [한글 4글자 이하] 로만 구성되어야 합니다");

        $exist = DB::fetch("SELECT * FROM users WHERE user_id = ?", [$user_id]);
        if($exist) back("이미 해당 아이디의 회원이 존재합니다.");

        $password = hash("sha256", $password);
        
        DB::query("INSERT INTO users(user_id, user_name, password) VALUES (?, ?, ?)", [$user_id, $user_name, $password]);

        go("/users/login", "회원가입 되었습니다.");
    }


    function logout(){
        unset($_SESSION['user']);
        unset($_SESSION['permission']);

        go("/", "로그아웃 되었습니다.");
    }
}