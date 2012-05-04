<?php

class OsuMirror_Statistics
{
    protected $_file;
    protected $_fileHandle;
    protected static $_instance;
    public $stats;
    
    public function __construct()
    {
        $this->_file = APPLICATION_PATH .'/config/stats.json';
        if(!file_exists($this->_file))
            touch($this->_file);
        $this->_parseStats();
    } 
    
    public static function getInstance()
    {
        if(null === self::$_instance)
            self::$_instance = new self;
        return self::$_instance;
    }
    
    protected function _openFile()
    {
        if(!$this->_fileHandle){
            $this->_fileHandle = fopen($this->_file,'r+b');
        }
    }
        
    protected function _parseStats()
    {
        $this->stats = null;
        $this->_openFile();
        rewind($this->_fileHandle);
        $statsData = '';
        while(!feof($this->_fileHandle)) {
            $statsData .= fread($this->_fileHandle,1024);
        }
        $statsData = mb_convert_encoding($statsData, 'iso-8859-1');
        $this->stats = json_decode($statsData,false);
        if(null === $this->stats)
            $this->stats = new stdClass();
    }
    
    public function trySave()
    {
        $status = false;
        $lockTries = 20;
        while(!$status && $lockTries > 0){
            $status = $this->saveStats();
            // Have to find a better way...
            usleep(20000);
            $lockTries--;
        }
        return $status;
    }
    
    public function saveStats()
    {
        $this->stats->lastModified = time();
        $jsonData = json_encode($this->stats);
        if(flock($this->_fileHandle,LOCK_EX)) {
            rewind($this->_fileHandle);
            ftruncate($this->_fileHandle, 0);
            fwrite($this->_fileHandle,$jsonData,strlen($jsonData));
            flock($this->_fileHandle,LOCK_UN);
            return true;
        }
        return false;
    }
    
    public function getDaily($filter = '')
    {
        $keyToday = strftime('%Y%m%d');
        $output = 0;
        if(isset($this->stats->daily) && isset($this->stats->daily->$keyToday)) {
            foreach($this->stats->daily->$keyToday as $key => $value)
            {
                if($filter == '' || substr($key,0,strlen($filter)) == $filter){
                    $output += $this->stats->daily->$keyToday->$key;
                }
            }
        }
        return $output;
    }
    
    public function getMonthly($filter = '')
    {
        $keyToday = strftime('%Y%m');
        $output = 0;
        if(isset($this->stats->monthly) && isset($this->stats->monthly->$keyToday)) {
            foreach($this->stats->monthly->$keyToday as $key => $value)
            {
                if($filter == '' || substr($key,0,strlen($filter)) == $filter){
                    $output += $this->stats->monthly->$keyToday->$key;
                }
            }
        }
        return $output;
    }
    
    public function getYearly($filter = '')
    {
        $keyToday = strftime('%Y');
        $output = 0;
        if(isset($this->stats->yearly) && isset($this->stats->yearly->$keyToday)) {
            foreach($this->stats->yearly->$keyToday as $key => $value)
            {
                if($filter == '' || substr($key,0,strlen($filter)) == $filter){
                    $output += $this->stats->yearly->$keyToday->$key;
                }
            }
        }
        return $output;
    }
    
    public function add($key,$amount)
    {
        $keyDaily = strftime('%Y%m%d');
        $keyMonthly = strftime('%Y%m');
        $keyYearly = strftime('%Y');
        
        if(!isset($this->stats->daily))
            $this->stats->daily = new stdClass();
        
        if(!isset($this->stats->daily->$keyDaily))
            $this->stats->daily->$keyDaily = new stdClass();
        
        if(!isset($this->stats->monthly))
            $this->stats->monthly = new stdClass();
        
        if(!isset($this->stats->monthly->$keyMonthly))
            $this->stats->monthly->$keyMonthly = new stdClass();
        
        if(!isset($this->stats->yearly))
            $this->stats->yearly = new stdClass();
        
        if(!isset($this->stats->yearly->$keyYearly))
            $this->stats->yearly->$keyYearly = new stdClass();
        
        $this->stats->daily->$keyDaily->$key += $amount;
        $this->stats->monthly->$keyMonthly->$key += $amount;
        $this->stats->yearly->$keyYearly->$key += $amount;
    }
}