<?php

class Mg_Shop_Model_Mapper_ShopProperty extends Mg_Model_Mapper_Abstract
{
    protected $_sModel = 'Mg_Shop_Model_ShopProperty';
    
    public function getDbTable() {
        if (null === $this->_oDbTable) {
            $this->setDbTable('Mg_Shop_Model_DbTable_ShopProperty');
        }
        return $this->_oDbTable;
    }   
}
