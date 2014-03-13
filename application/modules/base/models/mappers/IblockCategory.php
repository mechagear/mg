<?php

class Mg_Base_Model_Mapper_IblockCategory extends Mg_Model_Mapper_Abstract
{
    protected $_sModel = 'Mg_Base_Model_IblockCategory';
    
    public function getDbTable() {
        if (null === $this->_oDbTable) {
            $this->setDbTable('Mg_Base_Model_DbTable_IblockCategory');
        }
        return $this->_oDbTable;
    }   
}
