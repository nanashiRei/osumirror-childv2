<?php

class Controller_Api extends OsuMirror_ControllerAbstract
{
    protected function _init()
    {
        $this->view->setView('api-message.phtml');
        $this->view->addHeaders(array(
                'content-type' => 'text/plain'));
    }
    
    public function indexAction()
    {
        $this->view->apiData = array(
                'error' => 'You need to ask me something... DUH!');
    }
    
    public function versionAction()
    {
        $this->view->apiData = array(
                'version' => '2.0');
    }
}

?>