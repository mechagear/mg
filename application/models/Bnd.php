<?php

class Mg_Model_Bnd extends Mg_Model_Abstract
{
    protected $_id_parent;
    protected $_id_child;
    
    // getters
    
    public function getIdParent() {
        return $this->_id_parent; 
    }
    
    public function getIdChild() {
        return $this->_id_child;
    }
    
    // setters
    
    public function setIdParent($iIdParent) {
        $this->_id_parent = intval($iIdParent);
    }
    
    public function setIdChild($iIdChild) {
        $this->_id_child = intval($iIdChild);
    }
}
