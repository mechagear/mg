<?php

class Mg_Base_Model_Mapper_Iblock extends Mg_Model_Mapper_Abstract
{
    protected $_sModel = 'Mg_Base_Model_Iblock';
    
    public function getDbTable() {
        if (null === $this->_oDbTable) {
            $this->setDbTable('Mg_Base_Model_DbTable_Iblock');
        }
        return $this->_oDbTable;
    }   
}
