<?php

class OsuMirror_Config
{
    private static $_instance;
    
    protected $_config;
    protected $_separator = '.';
    
    public function __construct($parseConfig = true)
    {
        $params = array();
        if(true == $parseConfig)
            $params = parse_ini_file(APPLICATION_PATH . '/config/config.ini', true);
        $this->setParams($params);
    }
    
    public function setParams($params)
    {
        foreach($params as $key => $value){
            if(is_array($value)) {
                $this->$key = new $this(false);
                foreach($value as $subKey => $subValue) {
                    $this->$key->$subKey = $subValue;
                }
            } else {
                $this->_key = $value;
            }
        }
    }
    
    public function __get($name)
    {
        if(isset($this->_config[$name]))
            return $this->_config[$name];
        return null;
    }
    
    public function __set($name,$value)
    {
        $this->_config[$name] = $value;
        return $this;
    }
    
    public static function getInstance()
    {
        if(null == self::$_instance)
            self::$_instance = new self(true);
        return self::$_instance;
    }
}

?>