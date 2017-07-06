<?php

require_once 'database/connection.php';

spl_autoload_register(function($class){
    require_once "classes/{$class}.php";
});

// spl_autoload_register — Register given function as __autoload() implementation

?>