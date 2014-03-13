<?php

class Mg_Shop_Model_Shop extends Mg_Model_Abstract 
{
    protected $_id_shop;
    protected $_code;
    protected $_name;
    protected $_id_user;
    protected $_id_status;
    
    // getters
    
    public function getIdShop() {
        return $this->_id_shop;
    }
    
    public function getCode() {
        return $this->_code;
    }
    
    public function getName() {
        return $this->_name;
    }
    
    public function getIdUser() {
        return $this->_id_user;
    }
    
    public function getIdStatus() {
        return $this->_id_status;
    }
    
    // setters
    
   public function setIdShop($iIdShop) {
       $this->_id_shop = intval($iIdShop);
   } 
   
   public function setCode($sCode) {
       $this->_code = $sCode;
   }
   
   public function setName($sName) {
       $this->_name = $sName;
   }
   
   public function setIdUser($iIdUser) {
       $this->_id_user = intval($iIdUser);
   }
   
   public function setIdStatus($iIdStatus) {
       $this->_id_status = intval($iIdStatus);
   }
}
