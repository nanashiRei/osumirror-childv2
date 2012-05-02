<?php

class OsuMirror_Encryption
{
    protected static $_instance;
     
    protected $_iv;
    protected $_key;
    
    public function __construct($iv = null,$key = null)
    {
        $config = OsuMirror_Config::getInstance();
        if(is_string($iv)){
            $this->_iv = $iv;
        }else{
            $this->_iv = $config->mirror->secret;
        }
        if(is_string($key)){
            $this->_key = $key;
        }else{
            $this->_key = $config->child->encryptionKey;
        }
        
    }
    
    public static function getInstance()
    {
        if(null === self::$_instance)
            self::$_instance = new self;
        return self::$_instance;
    }
    
    public function encrypt($data)
    {
        $cipherText = mcrypt_encrypt(MCRYPT_BLOWFISH,$this->_iv,$data,MCRYPT_MODE_CBC,$this->_key);
        return $cipherText;
    }
    
    public function decrypt($data)
    {
        $cipherText = mcrypt_decrypt(MCRYPT_BLOWFISH,$this->_iv,$data,MCRYPT_MODE_CBC,$this->_key);
        return $cipherText;
    }
}

?>