<?php
namespace app;

class Route {
    static $get = [];
    static $post = [];

    static function set($httpMethod, $url, $action, $permission = null){
        $split = explode("@", $action);
        $controller = $split[0];
        $method = $split[1];

        array_push(self::${strtolower($httpMethod)}, (object)["url" => $url, "controller" => $controller, "method" => $method, "permission" => $permission]);
    }
    static function getURL() {
        $url = isset($_GET['url']) ? rtrim($_GET['url']) : "";
        $url = filter_var($url, FILTER_SANITIZE_URL);
        return "/". $url;
    }
    static function connect(){
        $current_url = self::getURL();
        foreach(self::${strtolower($_SERVER['REQUEST_METHOD'])} as $page){
            if($page->url === $current_url) {
                if($page->permission == "guest" && user()) back("로그인 후엔 사용하실 수 없습니다.");
                if($page->permission == "user" && !user()) go("/users/login", "로그인 후 사용하실 수 있습니다.");

                $conName = "controller\\".$page->controller;
                $controller = new $conName();
                $controller->{$page->method}();
                exit;
            }
        }

        back("존재하지 않는 페이지 입니다!");
    }
}