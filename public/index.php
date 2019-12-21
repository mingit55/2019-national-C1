<?php
    session_start();

    define("ROOT", dirname(__DIR__));
    define("SRC", ROOT."/src");
    define("VIEW", SRC."/Views");

    include "../autoload.php";
    include "../helper.php";
    include "../web.php";