<?php

class Controller_Index extends OsuMirror_ControllerAbstract
{   
    protected function _init()
    {
        
    }
    
    public function indexAction()
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
    }
}

?>