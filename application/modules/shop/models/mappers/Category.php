<?php

class Mg_Shop_Model_Mapper_Category extends Mg_Model_Mapper_Abstract
{
    protected $_sModel = 'Mg_Shop_Model_Category';
    
    public function getDbTable() {
        if (null === $this->_oDbTable) {
            $this->setDbTable('Mg_Shop_Model_DbTable_Category');
        }
        return $this->_oDbTable;
    }   
}
