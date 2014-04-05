<?php

class Mg_Model_Mapper_Session extends Mg_Model_Mapper_Abstract
{
    protected $_sModel = 'Mg_Model_Session';
    
    public function getDbTable() {
        if (null === $this->_oDbTable) {
            $this->setDbTable('Mg_Model_DbTable_Session');
        }
        return $this->_oDbTable;
    }   
}
