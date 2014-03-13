<?php

class Mg_Cars_Model_Mapper_CarSubmodel extends Mg_Model_Mapper_Abstract
{
    protected $_sModel = 'Mg_Cars_Model_CarSubmodel';
    
    public function getDbTable() {
        if (null === $this->_oDbTable) {
            $this->setDbTable('Mg_Cars_Model_DbTable_CarSubmodel');
        }
        return $this->_oDbTable;
    }   
}
