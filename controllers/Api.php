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
            $packed = pack('H*',$this->_route->getRoute()->singleValue);
            $decrypted = $this->_encryption->decrypt($packed);
            $data = gzinflate($decrypted);
            $dataArray = json_decode($data,true);
            
            //DEBUG: print_r($dataArray);
            
            if(isset($dataArray['file_type']) 
            && isset($dataArray['filename']) 
            && isset($dataArray['ip'])
            && isset($dataArray['timestamp'])) {
                if($dataArray['ip'] == $_SERVER['REMOTE_ADDR']) {
                    if(time() - $dataArray['timestamp'] < 60) { 
                        $this->view->setDispatched(true);
                        $absPath = $this->_config->mirror->homePath;
                        $absPath .= '/' . $dataArray['file_type'] . 's';
                        if($dataArray['file_type'] == 'pack') {
                            $theme = ($dataArray['theme'] == 'Beatmap Pack' ? 'default' : $dataArray['theme']);
                            $absPath .= '/' . $theme . '/' . $dataArray['filename'];
                        } else {
                            $absPath .= '/' . $dataArray['filename'];
                        }
                        $absPath = realpath($absPath);
                        $fileSize = filesize($absPath);
                        
                        $this->_stats->add('apiDownload'.ucfirst($dataArray['file_type']), 1);
                        $this->_stats->add('traffic'.ucfirst($dataArray['file_type']).'s', $fileSize);
                    } else {
                        $this->view->setDispatched(false);
                        $this->view->apiData = array('ERROR' => 'Your download timed out. Valid until: ' . strftime('%Y/%m/%d - %H:%M:%S %Z',$dataArray['timestamp'] + 60));
                    }
                } else {
                    $this->view->apiData = array('ERROR' => 'IP mismatch, this is not your download ticket!');
                }
            } else {
                $this->view->apiData = array('ERROR' => 'Invalid request data!');
            }
        }
    }
    
    public function jsonstatsAction()
    {
        $this->view->setView('jsonstats.phtml');
        $this->view->stats = $this->_stats->stats;
    }
}

?>