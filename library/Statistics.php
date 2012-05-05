<?php

class OsuMirror_Statistics
{
    protected $_dbh;
    protected $_file;
    protected static $_instance;
    
    public function __construct()
    {
        $this->_file = APPLICATION_PATH .'/config/stats.db';
        $this->_openDb();
    } 
    
    public static function getInstance()
    {
        if(null === self::$_instance)
            self::$_instance = new self;
        return self::$_instance;
    }
    
    protected function _openDb()
    {
        if(!$this->_dbh) {
            if(!file_exists($this->_file))
                copy(APPLICATION_PATH.'/config/new_stats.db',$this->_file);
            
            try {
                $this->_dbh = new PDO('sqlite:'.$this->_file);
                $this->_dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
    }
    
    public function getDaily($filter = '')
    {
        $output = 0;
        $sqlFilter = (empty($filter) ? '' : "AND valuekey LIKE '".$filter."%'");
        $result = $this->_dbh->query(sprintf("SELECT counter FROM daily WHERE timekey = %s %s",strftime('%Y%m%d'),$sqlFilter));
        foreach($result as $row) {
            $output += $row['counter'];
        }
        return $output;
    }
    
    public function getMonthly($filter = '')
    {
        $output = 0;
        $sqlFilter = (empty($filter) ? '' : "AND valuekey LIKE '".$filter."%'");
        $result = $this->_dbh->query(sprintf("SELECT counter FROM monthly WHERE timekey = %s %s",strftime('%Y%m%d'),$sqlFilter));
        foreach($result as $row) {
            $output += $row['counter'];
        }
        return $output;
    }
    
    public function getYearly($filter = '')
    {
        $output = 0;
        $sqlFilter = (empty($filter) ? '' : "AND valuekey LIKE '".$filter."%'");
        $result = $this->_dbh->query(sprintf("SELECT counter FROM yearly WHERE timekey = %s %s",strftime('%Y%m%d'),$sqlFilter));
        foreach($result as $row) {
            $output += $row['counter'];
        }
        return $output;
    }
    
    public function add($key,$amount)
    {
        $keyDaily = strftime('%Y%m%d');
        $keyMonthly = strftime('%Y%m');
        $keyYearly = strftime('%Y');
                        
        $checkStatement = "SELECT COUNT(*) as count FROM %s WHERE timekey = %s AND valuekey = '%s'";
        $updateStatement = "UPDATE %s SET counter = counter + %s WHERE timekey = %s AND valuekey = '%s'";
        $insertStatement = "INSERT INTO %s (timekey,valuekey,counter) VALUES (%s,'%s',%s)";
        
        $result = $this->_dbh->query(sprintf($checkStatement,'daily',$keyDaily,$key));
        $row = $result->fetch();
        if($row['count'] > 0) {
            $this->_dbh->exec(sprintf($updateStatement,'daily',$amount,$keyDaily,$key));
        } else {
            $this->_dbh->exec(sprintf($insertStatement,'daily',$keyDaily,$key,$amount));
        }
        
        $result = $this->_dbh->query(sprintf($checkStatement,'monthly',$keyMonthly,$key));
        $row = $result->fetch();
        if($row['count'] > 0) {
            $this->_dbh->exec(sprintf($updateStatement,'monthly',$amount,$keyMonthly,$key));
        } else {
            $this->_dbh->exec(sprintf($insertStatement,'monthly',$keyMonthly,$key,$amount));
        }
        
        $result = $this->_dbh->query(sprintf($checkStatement,'yearly',$keyYearly,$key));
        $row = $result->fetch();
        if($row['count'] > 0) {
            $this->_dbh->exec(sprintf($updateStatement,'yearly',$amount,$keyYearly,$key));
        } else {
            $this->_dbh->exec(sprintf($insertStatement,'yearly',$keyYearly,$key,$amount));
        }
    }
}