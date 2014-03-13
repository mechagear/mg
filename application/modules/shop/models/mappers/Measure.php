<?php

class Mg_Shop_Model_Mapper_Measure extends Mg_Model_Mapper_Abstract
{
    protected $_sModel = 'Mg_Shop_Model_Measure';
    
    protected $_aFieldModifiers = array(
        'forms' => array(
            'type' => 'array',
        ), 
    );
    
    public function getDbTable() {
        if (null === $this->_oDbTable) {
            $this->setDbTable('Mg_Shop_Model_DbTable_Measure');
        }
        return $this->_oDbTable;
    }   
}
