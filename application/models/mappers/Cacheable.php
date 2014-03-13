<?php

class Mg_Model_Mapper_Cacheable extends Mg_Model_Mapper_Abstract
{
    protected $sCacheName = 'file';
    protected $bUseCache = true;
    protected $oCache = null;
    
    // Some caceing methods
    public function __construct($bUseCache = true) {
        if ( $bUseCache && !empty($this->sCacheName) ) {
            $this->oCache = Mg_Common_Helper_Cache::getCache($this->sCacheName);
            if (!$this->oCache) {
                // Can't create cache obj. Must we write to log something?
                $bUseCache = false;
            }
            $this->setCacheEnabled($bUseCache);
        }
        //parent::__construct();
    }
    
    /**
     * Method sets cache enabled flag
     * @param boolean $bUseCache
     */
    public function setCacheEnabled($bUseCache) {
        $this->bUseCache = ($bUseCache && $this->oCache) ? true : false;
    }
    
    /**
     * Method returns cache enabled flag
     * @return boolean
     */
    public function isCacheEnabled() {
        return $this->bUseCache;
    }
    
    public function getKey() {
        return $this->getDbTable()->getTable();
    }
    
    public function dropCache() {
        if ( $this->isCacheEnabled() ) {
            $this->oCache->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array($this->getKey()));
        }
    }
    
    public function dropItemCache() {
        if ( $this->isCacheEnabled() ) {
            $this->oCache->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array($this->getKey() . '_item'));
        }
    }
    // -----------------------
    public function getItem($iId) {
        $iId = intval($iId);
        $sKey = $this->getKey() . '_item_' . $iId;
        if (!$this->isCacheEnabled() || !$oItem = $this->oCache->load($sKey)) {
            $oItem = parent::getItem($iId);
            if ( $this->isCacheEnabled() ) {
                $this->oCache->save($oItem, $sKey, array($this->getKey(), $this->getKey() . '_item'));
            }
        }
        return $oItem;
    }
    
    public function getItemByField($sField, $sValue) {
        $sField = trim($sField);
        $sValue = trim($sValue);
        $sKey = $this->getKey() . '_item_by_field_' . md5($sField . '_' . $sValue);
        if (!$this->isCacheEnabled() || !$oItem = $this->oCache->load($sKey)) {
            $oItem = parent::getItemByField($sField, $sValue);
            if ( $this->isCacheEnabled() ) {
                $this->oCache->save($oItem, $sKey, array($this->getKey(), $this->getKey() . '_item'));
            }
        }
        return $oItem;
    }
    
    public function getList($aWhere = null, $aOrder = null, $iPage = 0, $iLimit = 0) {
        return parent::getList($aWhere, $aOrder, $iPage, $iLimit);
    }
    
}
