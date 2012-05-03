<?php

class Controller_Index extends OsuMirror_ControllerAbstract
{   
    protected function _init() {}
    
    public function indexAction()
    {
        //Do nothing
    }
    
    public function statsAction()
    {
        $this->view->files = array();
        $this->view->files['maps'] = array();
        $this->view->files['packs'] = array();
        $this->view->mapsTotalSize = 0;
        $this->view->packsTotalSize = 0;
        $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($this->_config->mirror->homePath,FilesystemIterator::SKIP_DOTS),
                RecursiveIteratorIterator::LEAVES_ONLY,
                RecursiveIteratorIterator::CATCH_GET_CHILD);
        foreach($iterator as $file){
            if($file->isReadable() && $file->isFile()) {
                if(preg_match('/maps\/.*\.(osz|zip)$/i',$file->getPathname())) {
                    $this->view->files['maps'][] = $file->getFilename();
                    $this->view->mapsTotalSize += filesize($file->getPathname());
                } elseif(preg_match('/packs\/[^\/]+\/.*\.(zip|rar)$/i', $file->getPathname())) {
                    $this->view->files['packs'][] = $file->getFilename();
                    $this->view->packsTotalSize += filesize($file->getPathname());
                }
            }
        }
        
        $this->view->dailyMapRequests = $this->_stats->getDaily('apiStoresMap');
        $this->view->dailyMapDownloads = $this->_stats->getDaily('apiDownloadMap');
        $this->view->dailyPackRequests = $this->_stats->getDaily('apiStoresPack');
        $this->view->dailyPackDownloads = $this->_stats->getDaily('apiDownloadPack');
        $this->view->dailyRequests = $this->_stats->getDaily('api');
        $this->view->dailyErrors = $this->_stats->getDaily('error');
        
        $this->view->monthlyMapTraffic = sprintf('%0.3f GB', $this->_stats->getMonthly('trafficMaps')/pow(1024,3) );
        $this->view->monthlyPackTraffic = sprintf('%0.3f GB', $this->_stats->getMonthly('trafficPacks')/pow(1024,3) );
    }
}

?>