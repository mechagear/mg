<?php

class Mg_Shop_Model_Mapper_Shop extends Mg_Model_Mapper_Abstract
{
    protected $_sModel = 'Mg_Shop_Model_Shop';
    
    public function getDbTable() {
        if (null === $this->_oDbTable) {
            $this->setDbTable('Mg_Shop_Model_DbTable_Shop');
        }
        return $this->_oDbTable;
    }   
}
