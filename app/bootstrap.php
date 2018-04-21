<?php
    // load config
    require_once 'config/config.php';

    // autoload core libraries
    // automatically loads all the files in the libraries folder
    // somehow
    spl_autoload_register(function($className){
        require_once 'libraries/' . $className . '.php';
    });
