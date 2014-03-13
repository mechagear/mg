<?php

class Mg_Base_Model_Mapper_Page extends Mg_Model_Mapper_Cacheable
{
    protected $_sModel = 'Mg_Base_Model_Page';
    
    public function getDbTable() {
        if (null === $this->_oDbTable) {
            $this->setDbTable('Mg_Base_Model_DbTable_Page');
        }
        return $this->_oDbTable;
    }   
}
