<?php

abstract class Mg_Addon_MediaConvert 
{
    protected $sConvertationPath = '';
    
    protected $aOptions = array();
    
    public function __construct($aOptions = array()) {
        $this->setOptions($aOptions);
    }
    
    
    protected function setOptions($aOptions) {
         if ( !empty($aOptions['convertation_path']) ) {
             $this->setConvertationPath($aOptions['convertation_path']);
         }
         $this->aOptions = $aOptions;
    }
    
    public function setConvertationPath($sPath) {
        $this->sConvertationPath = $sPath;
    }
}
