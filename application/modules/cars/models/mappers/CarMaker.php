<?php

class Mg_Cars_Model_Mapper_CarMaker extends Mg_Model_Mapper_Cacheable
{
    protected $_sModel = 'Mg_Cars_Model_CarMaker';
    
    public function getDbTable() {
        if (null === $this->_oDbTable) {
            $this->setDbTable('Mg_Cars_Model_DbTable_CarMaker');
        }
        return $this->_oDbTable;
    }   
}
