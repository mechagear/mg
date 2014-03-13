<?php

class Mg_Shop_Model_Mapper_ItemDescription extends Mg_Model_Mapper_Abstract
{
    protected $_sModel = 'Mg_Shop_Model_ItemDescription';
    
    public function getDbTable() {
        if (null === $this->_oDbTable) {
            $this->setDbTable('Mg_Shop_Model_DbTable_ItemDescription');
        }
        return $this->_oDbTable;
    }   
}
