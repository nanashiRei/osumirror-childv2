<?php

class OsuMirror_View extends ArrayObject
{
    public $config;
    public $encryption;
    public $route;
    
    protected $_viewScript;
    protected $_dispatched = false;
    protected $_headers;
    
    protected $_assignedValues;
    
    public function __construct ()
    {
        $this->config = OsuMirror_Config::getInstance();
        $this->encryption = OsuMirror_Encryption::getInstance();
        $this->route = OsuMirror_Route::getInstance();
        
        $this->_viewScript = APPLICATION_PATH . '/views/' . $this->route->getRoute()->controller . '/' . $this->route->getRoute()->action . '.phtml';
        $this->_headers = array();
        
        parent::__construct(array(), ArrayObject::ARRAY_AS_PROPS);
    }
    
    public function __get($name)
    {
        if (isset($this->_assignedValues[$name])) {
            return $this->_assignedValues[$name];
        } else {
            return null;
        }
    }
    
    public function __set($name,$value)
    {
        $this->_assignedValues[$name] = $value;
        return $this;
    }
    
    /**
     * @return the $_headers
     */
    public function getHeaders ()
    {
        return $this->_headers;
    }

	/**
     * @param multitype: $_headers
     */
    public function setHeaders ($_headers)
    {
        $this->_headers = array();
        foreach($_headers as $header => $content) {
            $this->_headers[strtolower($header)] = $content;
        }
    }
    
    /**
     * @param multitype: $_headers
     */
    public function addHeaders ($_headers)
    {
        foreach($_headers as $header => $content) {
            $this->_headers[strtolower($header)] = $content;
        }
    }

    public function sendHeaders()
    {
        foreach($this->_headers as $header => $content){
            header($header . ': ' . $content);
        }
    }
    
	public function dispatch()
    {
        if($this->_dispatched == false) {
            if(file_exists($this->_viewScript)) {
                $this->sendHeaders();
                include $this->_viewScript;
            } else {
                throw new Exception('View script for '.$this->route->getRoute()->action.' \''.$this->_viewScript.'\' could not be loaded!');
            }
            $this->_dispatched = true;
        }
    }
    
    public function setView($name,$controller = null)
    {
        $this->_viewScript = APPLICATION_PATH . '/views';
        if(null === $controller) {
            $this->_viewScript .= '/'.$this->route->getRoute()->controller;
        } else {
            $this->_viewScript .= '/'.strtolower($controller);
        }
        $this->_viewScript .= '/' . $name;
        return $this;
    }
    
	/**
     * @return the $_viewScript
     */
    public function getViewScript ()
    {
        return $this->_viewScript;
    }

	/**
     * @param string $_viewScript
     */
    public function setViewScript ($_viewScript)
    {
        $this->_viewScript = $_viewScript;
        return $this;
    }

	/**
     * @return the $_dispatched
     */
    public function getDispatched ()
    {
        return $this->_dispatched;
    }

	/**
     * @param boolean $_dispatched
     */
    public function setDispatched ($_dispatched)
    {
        $this->_dispatched = $_dispatched;
        return $this;
    }

}

?>