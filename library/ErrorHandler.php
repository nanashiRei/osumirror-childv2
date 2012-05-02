<?php

class OsuMirror_ErrorHandler
{
    protected static $_instance;
    
    public function __construct()
    {
        set_error_handler(array(get_class($this),'errorHandler'));
        set_exception_handler(array(get_class($this),'exceptionHandler'));
    }
    
    public static function getInstance()
    {
        if(null === self::$_instance)
            self::$_instance = new self;
        return self::$_instance;
    }
    
    public static function exceptionHandler(Exception $e) 
    {
        $stats = OsuMirror_Statistics::getInstance();
        $stats->add('error'.$e->getCode(),1);
        $stats->trySave();
        exit('ERROR "'.$e->getMessage().' [#'.$e->getLine().'] '.$e->getFile().'"');
    }

    public static function errorHandler($no,$str,$file,$line) 
    {
        $e = new ErrorException($str,$no,0,$file,$line);
        self::exceptionHandler($e);
    }
}