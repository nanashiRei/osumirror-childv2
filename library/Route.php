<?php

class OsuMirror_Route
{
    protected static $_instance;
    protected $_route = null;
    protected $_keys = array();
    
    public function __construct($path = null)
    {
        $this->_route = new stdClass();
        $default = array('controller' => 'index','action' => 'index');
        $config = OsuMirror_Config::getInstance();
        
        $apiPath = (null == $path ? preg_replace('/^'.str_replace('/','\\/', $config->mirror->basePath).'/i', '', $_SERVER['REQUEST_URI']) : $path);
        $params = explode('/',ltrim($apiPath,'/\\'));
        
        $i = 0;
        foreach($default as $k => $v){
            if(empty($params[$i])){
                $this->_route->$k = $v;
            } else {
                $this->_route->$k = $params[$i];
            }
            $i++;
        }
        
        $key = '';
        for ($i = count($default); $i < count($params); $i ++) {
            if (empty($key)) {
                $key = strtolower(urldecode($params[$i]));
                $this->_keys[] = urldecode($key);
            } else {
                $this->_route->$key = urldecode($params[$i]);
                $key = '';
            }
        }
        if(!empty($key))
            $this->_route->singleValue = urldecode($key);
    }
    
    /**
     * @return the $_keys
     */
    public function getKeys ()
    {
        return $this->_keys;
    }

	public static function getInstance()
    {
        if(null === self::$_instance)
            self::$_instance = new self;
        return self::$_instance;
    }
    
	/**
     * @return the $_route
     */
    public function getRoute ()
    {
        return $this->_route;
    }

	/**
     * @param NULL $_route
     */
    public function setRoute ($_route)
    {
        $this->_route = $_route;
        return $this;
    }

    public function run()
    {
        $controllerName = 'Controller_' . ucfirst($this->_route->controller);
        try {
            $controller = new $controllerName;
            $controller->call($this);
        } catch (Exception $e) {
            throw new BadMethodCallException($controllerName.'::call could not be called');
        }
    }
}

?>