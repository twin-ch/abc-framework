<?php

namespace ABC\Abc\Core\Debugger;

use ABC\Abc\Core\Debugger\Php\PhpHandler;
use ABC\Abc\Core\Debugger\Php\TraceClass;
use ABC\Abc\Core\Debugger\Php\TraceObject;
use ABC\Abc\Core\Debugger\Php\TraceContainer;
use ABC\Abc\Core\Debugger\Php\TraceVariable;

/** 
 * Класс Dbg
 * Трассировка скрипта.
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015 
 * @license http://www.wtfpl.net/  
 */   

class Dbg extends PhpHandler
{

    public $container = 'ABC\abc\components\Dic\DiC';
    
    /**
    * @var TraceClass|TraceContainer|TraceObject|TraceVariable 
    */    
    protected $tracer;
    
    protected $trace = true;
    protected $reflection = false;
    protected $errorLevel = E_USER_ERROR;

    /**
    * Конструктор
    *
    * @param mixed $var
    * @param mixed $no
    */    
    public function __construct($var = 'stop')
    {
        parent::__construct();
        $this->tracersSelector($var);
    }

    /**
    * Выбор трассировщика в зависимости от типа данных
    *
    * @param mixed $var
    *
    * @return void
    */     
    protected function tracersSelector($var) 
    {

        if (is_string($var) && class_exists($var)) {
            $this->tracer = new TraceClass($this->painter, $this->view);
            $this->reflection = true;  
        } elseif (is_object($var)) {
         
            if ($this->container === get_class($var)) {
                $this->tracer = new TraceContainer($this->painter, $this->view);
                $this->tracer->container = $this->container;
                $this->reflection = true;            
                $var = $this->tracer->getValue();        
            } else {
                $this->tracer = new TraceObject($this->painter, $this->view);            
            }
            
        } else {
            $this->tracer = new TraceVariable($this->painter, $this->view);
        }
        
        $this->traceProcessor($var);
    }     
 
    /**
    * Запускает трассировку
    *
    * @param mixed $var
    *
    * @return void
    */      
    protected function traceProcessor($var) 
    {
        $trace = debug_backtrace();
        $this->backTrace = $this->prepareTrace($trace); 
        
        if (!$this->reflection) {
            $var = $this->prepareValue($var);        
        } 
        
        $location = $this->getLocation();
        $listing  = $this->tracer->getListing($var);
        $this->render($location, $listing);
    } 

    /**
    * Возвращает файл и линию трассировки
    *
    * @return void
    */        
    protected function getLocation() 
    { 
        $blocs = [];
        
        foreach ($this->backTrace as $block) {
            $block = $this->normaliseBlock($block);    
            
            if (empty($block)) {
                continue;
            }
            
            $blocs[] = $block;
        }
     
        return $blocs[0];
    }
    
    /**
    * Рендер 
    *
    * @param array $location
    * @param string $listing
    *
    * @return void
    */    
    protected function render($location, $listing) 
    { 
        $this->data = ['message'  => $this->tracer->message,
                       'adds'     => $this->tracer->adds,
                       'level'    => $this->lewelMessage($this->errorLevel),
                       'listing'  => $listing,                       
                       'file'     => $location['file'],
                       'line'     => $location['line'],                       
                       'stack'    => $this->getStack(),
        ];
        
        $this->action();
        die;
    }  
}
