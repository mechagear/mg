<?php

class Mg_Model_DbTable_Abstract extends Zend_Db_Table_Abstract
{
    protected $_name = '';
    protected $_primary = '';
    
    public function getTable() {
        return $this->_name;
    }
    
    public function getPrimary() {
        if ( !is_array($this->_primary) ) {
            return $this->_primary;
        } elseif ( is_array($this->_primary) && 1 == count($this->_primary) ) {
            return current($this->_primary);
        } else {
            return $this->_primary;
        }
    }
}
