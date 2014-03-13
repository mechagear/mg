<?php

class Mg_Model_User extends Mg_Model_Abstract implements Zend_Acl_Resource_Interface,Zend_Acl_Role_Interface
{
    protected $_id_user;
    protected $_email;
    protected $_phone;
    protected $_password;
    protected $_date_create;
    protected $_date_change;
    protected $_nickname;
    protected $_role;
    protected $_id_status;
    
    public function getRoleId() {
        return ( !empty($this->_role) ) ? $this->_role : 'guest';
    }
    
    public function getResourceId() {
        return 'user';
    }
    // getters
    public function getIdUser() {
        return $this->_id_user;
    }
    
    public function getEmail() {
        return $this->_email;
    }
    
    public function getPhone() {
        return $this->_phone;
    }
    
    public function getPassword() {
        return $this->_password;
    }
    
    public function getDateCreate() {
        return $this->_date_create;
    }
    
    public function getDateChange() {
        return $this->_date_change;
    }
    
    public function getNickname() {
        return $this->_nickname;
    }
    
    public function getRole() {
        return $this->_role;
    }
    
    public function getIdStatus() {
        return $this->_id_status;
    }
    
    // setters
    public function setIdUser($iIdUser) {
        $this->_id_user = intval($iIdUser);
    }
    
    public function setEmail($sEmail) {
        $this->_email = $sEmail;
    }
    
    public function setPhone($sPhone) {
        $this->_phone = Mg_Common_Helper_Identity::formatPhone($sPhone);
    }
    
    public function setPassword($sPassword) {
        $this->_password = $sPassword;
    }
    
    public function setDateCreate($sDateCreate) {
        $this->_date_create = $sDateCreate;
    }
    
    public function setDateChange($sDateChange) {
        $this->_date_change = $sDateChange;
    }
    
    public function setNickname($sNickname) {
        $this->_nickname = $sNickname;
    }
    
    public function setRole($sRole) {
        $this->_role = $sRole;
    }
    
    public function setIdStatus($iIdStatus) {
        $this->_id_status = intval($iIdStatus);
    }
    
    
}
