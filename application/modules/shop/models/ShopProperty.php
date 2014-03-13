<?php

class Mg_Shop_Model_ShopProperty extends Mg_Model_Abstract 
{
    protected $_id_property;
    protected $_name;
    protected $_type;
    protected $_view_type;
    protected $_id_measure;
    protected $_id_shop;
    
    
    // getters
    
    public function getIdProperty() {
        return $this->_id_property;
    }
    
    public function getName() {
        return $this->_name;
    }
    
    public function getType() {
        return $this->_type;
    }
    
    public function getViewType() {
        return $this->_view_type;
    }
    
    public function getIdMeasure() {
        return $this->_id_measure;
    }
    
    public function getIdShop() {
        return $this->_id_shop;
    }
    
    // setters
    
    public function setIdProperty($iIdProperty) {
        $this->_id_property = intval($iIdProperty);
    }
    
    public function setName($sName) {
        $this->_name = $sName;
    }
    
    public function setType($sType) {
        $this->_type = $sType;
    }
    
    public function setViewType($sViewType) {
        $this->_view_type = $sViewType;
    }
    
    public function setIdMeasure($iIdMeasure) {
        $this->_id_measure = intval($iIdMeasure);
    }
    
    public function setIdShop($iIdShop) {
        $this->_id_shop = intval($iIdShop);
    }
    
}