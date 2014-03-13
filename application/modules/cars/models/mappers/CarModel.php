<?php

class Mg_Cars_Model_Mapper_CarModel extends Mg_Model_Mapper_Abstract
{
    protected $_sModel = 'Mg_Cars_Model_CarModel';
    
    public function getDbTable() {
        if (null === $this->_oDbTable) {
            $this->setDbTable('Mg_Cars_Model_DbTable_CarModel');
        }
        return $this->_oDbTable;
    }   
}
