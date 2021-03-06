<?php

namespace ABC\Abc\Core;

/** 
 * Конфигуратор
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/
 */   
class Configurator
{
    /**
    * @var array
    */ 
    protected $config;
    
    /**
    * Возвращает массив пользовательских настроек 
    *
    * @return array
    */     
    public function getConfig($appConfig, $siteConfig)
    {   
        if (!is_array($appConfig)) {
            trigger_error(ABC_INVALID_ARGUMENT_EX .
                          ABC_INVALID_CONFIGURE,
                          E_USER_WARNING);
        }
        
        if (!is_array($siteConfig)) {
            trigger_error(ABC_INVALID_ARGUMENT_EX .
                          ABC_INVALID_CONFIGURE_SITE,
                          E_USER_WARNING);
        }
     
        $this->config = array_merge($appConfig, $siteConfig);
        $this->config = $this->normaliseConfig($this->config);
        
        return $this->usersSettingsConfig($this->config);
    } 
    
    /**
    * Возвращает массив маршрутов 
    *
    * @return array
    */     
    public function getRoutes()
    { 
        return prepareRoutes();
    }  

    /**
    * Приводим все ключи к нижнему регистру 
    *
    * @return array
    */     
    protected function normaliseConfig($config)
    { 
        $config = array_change_key_case($config); 
        
        foreach ($config as $key => $array) { 
            if (is_array($array)) { 
                $config[$key] = $this->normaliseConfig($array); 
            } 
        } 
        return $config; 
    }
    
    /**
    * И теперь заменяем сеттинги конфигами
    *
    * @return array
    */     
    protected function usersSettingsConfig($config)
    {  
        $settings = \ABC\Abc\Resourses\Settings::get();
        return array_replace_recursive($settings, $config); 
    }
    
    /**
    * Разбирает настройки маршрутов 
    *
    * @return array
    */     
    protected function prepareRoutes()
    { 
        if (!isset($this->config['routes'])) {
            return $this->defaultRoute();
        }
        
        if (is_array($this->config['routes'])) {
            return $this->config['routes'];
        }
        
        if (is_file($this->config['routes'])) {
            return $this->parseConfigRoutes($this->config['routes']);
        }
        
        trigger_error(ABC_BAD_FUNCTION_CALL_EX . 
                      ABC_UNKNOWN_ROUTES,
                      E_USER_WARNING);
    }     
    
    /**
    * Разбирает конфигурационный файл маршрутов и возвращает массив 
    *
    * @param string $file
    *
    * @return array
    */     
    protected function parseConfigRoutes($file)
    { 
        $configRoute = file_get_contents($file);
        // To be continued
        return [];
    } 
    
    /**
    * Устанавливает дефолтные правили маршрутизации 
    *
    * @return array
    */     
    protected function defaultRoute()
    { 
        return $this->defaultRoutes;
    }
}






