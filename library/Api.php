<?php

class OsuMirror_Api
{
    const STATE_ERROR = 'error';
    const STATE_SUCCESS = 'success';
    const STATE_WARNING = 'warning';
    
    protected $_state;
    protected $_response;
    protected $_responseIdentifiers;
    
    public function __construct($inputData = null)
    {
        $this->_state == $this::STATE_WARNING;
        $this->_response = array();
        $this->_responseIdentifiers = array();
        if(null !== $inputData) {
            if(is_string($inputData)) {
                $this->_inputArray(json_decode($inputData,true));
            } else {
                $this->_inputArray($inputData);
            }
        }
    }
    
    private function _inputArray($responseArray)
    {
        foreach($responseArray as $key => $data) {
            if($key == 'result') {
                $this->setState($data);
            } elseif($key == 'response') {
                if(is_array($data) && count($data) > 0) {
                    foreach($data as $response) {
                        $responseObject = new OsuMirror_Api_Response($response);
                        $this->addResponse($responseObject);
                    }
                }
            }
        }
    }
    
    public function addResponse(OsuMirror_Api_Response $resp)
    {
        if(!in_array($resp->getIdentifier(), $this->_responseIdentifiers)) {
            $this->_response[$resp->getIdentifier()] = $resp;
            $this->_responseIdentifiers[] = $resp->getIdentifier();
        }
        return $this;
    }
    
    public function setState($state = self::STATE_SUCCESS)
    {
        $this->_state = $state;
        return $this;
    }
    
    public function getIdentifiers()
    {
        return $this->_responseIdentifiers;
    }
    
    public function getResponse($identifier)
    {
        if(isset($this->_response[$identifier])) {
            return $this->_response[$identifier];
        }
        return null;
    }
    
    public function getResponseJson()
    {
        $output = array('result' => $this->_state);
        foreach($this->_response as $identifier => $response) {
            $output['response'][] = array(
                    'type' => $response->getType(),
                    'content' => $response->getContent(),
                    'model' => $response->getModel());
        }
        $output['responseLength'] = count($output['response']);
        if($output['responseLength'] === 0)
            $output['result'] = $this::STATE_WARNING;
        $output['apiVersion'] = file_get_contents(APPLICATION_PATH.'/config/version');
        $output['timestamp'] = time();
        return json_encode($output);
    }
}