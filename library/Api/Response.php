<?php

class OsuMirror_Api_Response
{
    const TYPE_ARRAY = 'array';
    const TYPE_STRING = 'string';
    const TYPE_BOOL = 'bool';
    const TYPE_NUMERIC = 'numeric';
    const TYPE_MODEL = 'model';
    
    protected $_type;
    protected $_content;
    protected $_model;
    
    public function __construct($content=null,$type=null,$model=null)
    {
        if(is_array($content) && $type != $this::TYPE_ARRAY) {
            $this->_type = $content['type'];
            $this->_content = $content['content'];
            $this->_model = $content['model'];
        } elseif (is_object($content) && $content instanceof OsuMirror_Api_Response) {
            $this->_type = $content->getType();
            $this->_content = $content->getContent();
            $this->_model = $content->getModel();
        /*} elseif (is_object($content) && $content instanceof OsuMirror_Api_ResponseModel) {
            */
        } else {
            $this->_content = $content;
            $this->_model = $model;
            $this->_type = $type;
        }
    }
    
    public function getIdentifier()
    {
        return 'RID'.sprintf('%\'08X',crc32(print_r($this,true)));
    }
    
    public function addData($data)
    {
        switch($this->_type) {
            case $this::TYPE_ARRAY:
                if(is_array($data)) {
                    foreach($data as $dataKey => $dataValue) {
                        if(!is_numeric($dataKey)) {
                            $this->_content[$dataKey] = $dataValue;
                        } else {
                            $this->_content[] = $dataValue;
                        }
                    }
                } else {
                    $this->_content[] = $data;
                }
                break;
            case $this::TYPE_BOOL:
                $this->_content = (bool) $data;
                break;
            case $this::TYPE_MODEL:
                //TODO: Needs model support
                break;
            case $this::TYPE_NUMERIC:
                $this->_content = $this->_content + $data;
                break;
            case $this::TYPE_STRING:
            default:
                $this->_content .= $data;
                break;
        }
    }
    
	/**
     * @return the $_type
     */
    public function getType ()
    {
        return $this->_type;
    }

	/**
     * @param field_type $_type
     */
    public function setType ($_type)
    {
        $this->_type = $_type;
        return $this;
    }

	/**
     * @return the $_content
     */
    public function getContent ()
    {
        return $this->_content;
    }

	/**
     * @param field_type $_content
     */
    public function setContent ($_content)
    {
        $this->_content = $_content;
        return $this;
    }

	/**
     * @return the $_model
     */
    public function getModel ()
    {
        return $this->_model;
    }

	/**
     * @param field_type $_model
     */
    public function setModel ($_model)
    {
        $this->_model = $_model;
        return $this;
    }


}

?>