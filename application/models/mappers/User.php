<?php

class Mg_Model_Mapper_User extends Mg_Model_Mapper_Abstract
{
    protected $_sModel = 'Mg_Model_User';
    
    public function getDbTable() {
        if (null === $this->_oDbTable) {
            $this->setDbTable('Mg_Model_DbTable_User');
        }
        return $this->_oDbTable;
    }   
}
