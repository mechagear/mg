<?php

class Mg_Base_Model_Mapper_IblockElement extends Mg_Model_Mapper_Abstract
{
    
    protected $_sModel = 'Mg_Base_Model_IblockElement';
    
    public function getDbTable() {
        if (null === $this->_oDbTable) {
            $this->setDbTable('Mg_Base_Model_DbTable_IblockElement');
        }
        return $this->_oDbTable;
    }   
}
