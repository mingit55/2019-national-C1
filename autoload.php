<?php
    function classLoader($c){
        require ROOT."/src/". $c. ".php";
    }

    spl_autoload_register("classLoader");