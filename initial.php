<?php

require_once 'src/connect.php';

spl_autoload_register(function($class){
    require_once "src/{$class}.php";
});

// spl_autoload_register — Register given function as __autoload() implementation

?>