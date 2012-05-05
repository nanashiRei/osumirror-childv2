<?php

abstract class OsuMirror_ControllerAbstract
{
    protected $_route;
    protected $_config;
    protected $_encryption;
    protected $_stats;
    public $view;
    
    public function __construct()
    {
        $this->_config = OsuMirror_Config::getInstance();
        $this->_encryption = OsuMirror_Encryption::getInstance();
        $this->_stats = OsuMirror_Statistics::getInstance();
        $this->view = new OsuMirror_View();
        $this->_init();
    }
    
    abstract protected function _init(); 
    
    public function call(OsuMirror_Route $route = null)
    {
        $this->_route = $route;
        if(null === $route)
            $this->_route = OsuMirror_Route::getInstance();
        $actionName = $this->_route->getRoute()->action . 'Action';
        if (is_callable(array($this,$actionName))) {
            $this->$actionName();
        } elseif (is_callable(array($this,'indexAction'))) {
            $this->indexAction();
        } else {
            throw new Exception('Your call to '.get_class($this).'::'.$actionName.' could not be handled!');
        }
        $this->view->dispatch();
    }
    
	/**
     * @return the $_route
     */
    public function getRoute ()
    {
        return $this->_route;
    }

	/**
     * @param field_type $_route
     */
    public function setRoute ($_route)
    {
        $this->_route = $_route;
        return $this;
    }

	/**
     * @return the $_config
     */
    public function getConfig ()
    {
        return $this->_config;
    }

	/**
     * @param field_type $_config
     */
    public function setConfig ($_config)
    {
        $this->_config = $_config;
        return $this;
    }

	/**
     * @return the $_encryption
     */
    public function getEncryption ()
    {
        return $this->_encryption;
    }

	/**
     * @param field_type $_encryption
     */
    public function setEncryption ($_encryption)
    {
        $this->_encryption = $_encryption;
        return $this;
    }

}

?>