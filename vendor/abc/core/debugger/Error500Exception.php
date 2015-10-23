<?php

namespace ABC\abc\core\debugger;

use Exception;
use ABC\abc\core\debugger\loger\Loger;
/** 
 * Класс DebugException 
 * Адаптирует trigger_error к Exception
 * для корректного выброса исключения
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/ 
 */  

class Error500Exception extends Exception 
{

    /**
    * Меняет местами порядок аргументов, передаваемых trigger_error
    * для корректного выброса исключения
    *
    * @param string $message
    * @param string $code
    * @param string $file 
    * @param string $line 
    *
    * @return void
    */     
    public function __construct($message, $code, $file, $line) 
    {
        header("HTTP/1.1 500 Internal Server Error");
        exit();
    }
}  