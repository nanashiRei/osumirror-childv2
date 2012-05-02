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
    
    public function storesAction()
    {
        if(in_array('map',$this->_route->getKeys())) {
            if(file_exists($this->_config->mirror->homePath . '/maps/' . $this->_route->getRoute()->map)) {
                $this->view->apiData = array('OK' => $this->_route->getRoute()->map);
            } else {
                $this->view->apiData = array('ERROR' => $this->_route->getRoute()->map);
            }
            $this->_stats->add('apiStoresMap',1);
        } elseif(in_array('pack',$this->_route->getKeys())) {
            $theme = 'default';
            if(!empty($this->_route->getRoute()->theme)) {
                $theme = $this->_route->getRoute()->theme;
            }
            if(file_exists($this->_config->mirror->homePath . '/packs/' . $theme . '/' . $this->_route->getRoute()->pack)) {
                $this->view->apiData = array('OK' => $this->_route->getRoute()->pack);
            } else {
                $this->view->apiData = array('ERROR' => $this->_route->getRoute()->pack);
            }
            $this->_stats->add('apiStoresPack',1);
        } else {
            $this->view->apiData = array(
                    'error' => 'You need to specify a type and a file!');
        }
    }
    
    public function downloadAction()
    {
        if(!empty($this->_route->getRoute()->singleValue))
        {
            $decrypted = $this->_encryption->decrypt($this->_route->getRoute()->singleValue);
            $data = gzinflate($decrypted);
            echo $data;
        }
    }
    
    public function jsonstatsAction()
    {
        $this->view->setView('jsonstats.phtml');
        $this->view->stats = $this->_stats->stats;
    }
}

?>