<?php

namespace ABC;

    require_once __DIR__ .'/../vendor/abc/abc.php'; 
 
    Abc::createNewAbc(['debug_mod' => 'abc']);
// Демонстрация дебаггера 
    (new \ABC\app\DebugingDemo);
 