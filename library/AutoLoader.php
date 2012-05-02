<?php

class OsuMirror_AutoLoader
{    
    private static $_instance;
    
    protected $_separator = '_';
    protected $_map = null;
    
    public function __construct()
    {
        $this->_map = array();
        $mapConfig = parse_ini_file(APPLICATION_PATH . '/config/map.ini',true);
        foreach($mapConfig['classmaps'] as $class => $path)
        {
            $this->_map[$class] = realpath(APPLICATION_PATH . $path);
        } 
    }
    
    public static function getInstance()
    {
        if(null === self::$_instance)
            self::$_instance = new self;
        return self::$_instance;
    }
    
    private function _mapSegment($segment)
    {
        if(isset($this->_map[$segment]))
            return $this->_map[$segment];
        return $segment;
    }
    
    public function makePath($class)
    {
        $pathElements = explode($this->_separator,$class);
        $classFilePath = '';
        foreach($pathElements as $pathSegment){
            $segment = $this->_mapSegment($pathSegment);
            $classFilePath .= $segment . '/';
        }
        $classFilePath = rtrim($classFilePath,'/\\');
        $classFilePath .= '.php';
        return $classFilePath;
    }
    
    public static function loadClass($class)
    {
        $classFile = self::getInstance()->makePath($class);
        
        if(!file_exists($classFile))
            throw new Exception($class . ' could not be loaded!');
        
        include $classFile;
    }
    
    public function registerAutoLoader()
    {
        spl_autoload_register('OsuMirror_AutoLoader::loadClass');
        return $this;
    }
}

?>