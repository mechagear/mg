<?php

class Mg_Shop_Model_Mapper_Item extends Mg_Model_Mapper_Abstract
{
    protected $_sModel = 'Mg_Shop_Model_Item';
    
    public function getDbTable() {
        if (null === $this->_oDbTable) {
            $this->setDbTable('Mg_Shop_Model_DbTable_Item');
        }
        return $this->_oDbTable;
    }   
}
