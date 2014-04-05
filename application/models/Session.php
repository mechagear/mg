<?php

class Mg_Model_Session extends Mg_Model_Abstract 
{
    protected $_id_session;
    protected $_sess_key;
    protected $_id_user;
    protected $_ip_address;
    protected $_date_create;
    protected $_date_change;
    
    // getters
    
    public function getIdSession() {
        return $this->_id_session;
    }
    
    public function getSessKey() {
        return $this->_sess_key;
    }
    
    public function getIdUser() {
        return $this->_id_user;
    }
    
    public function getIpAddress() {
        return $this->_ip_address;
    }
    
    public function getDateCreate() {
        return $this->_date_create;
    }
    
    public function getDateChange() {
        return $this->_date_change;
    }
    
    // setters
    
    public function setIdSession($iIdSession) {
        $this->_id_session = intval($iIdSession);
    }
    
    public function setSessKey($sKey) {
        $this->_sess_key = $sKey;
    }
    
    public function setIdUser($iIdUser) {
        $this->_id_user = intval($iIdUser);
    }
    
    public function setIpAddress($sIpAddress) {
        $this->_ip_address = $sIpAddress;
    }
    
    public function setDateCreate($sDateCreate) {
        $this->_date_create = $sDateCreate;
    }
    
    public function setDateChange($sDateChange) {
        $this->_date_change = $sDateChange;
    }
}
