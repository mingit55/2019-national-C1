<?php
namespace controller;

class Controller {
    function view($page, $data = [], $insert = null){
        extract($data);

        include VIEW. "/template/header.php";
        include VIEW. "/". $page;
        include VIEW. "/template/footer.php";
    }
}