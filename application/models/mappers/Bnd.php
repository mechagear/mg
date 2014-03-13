<?php

class Mg_Model_Mapper_Bnd extends Mg_Model_Mapper_Abstract
{
    protected $sParentTable = '';
    protected $_sModel = 'Mg_Model_Bnd';
    
    public function __construct($sParentTable) {
        $aParts = explode('_', $sParentTable);
        $aParts[0] = 'bnd';
        $sParentTable = implode('_', $aParts);
        $this->sParentTable = $sParentTable;
    }


    public function setDbTable($oDbTable) {
        if (is_string($oDbTable) ) {
            $oDbTable = new $oDbTable();
        }
        if ( ! $oDbTable instanceof Mg_Model_DbTable_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $oDbTable->setName($this->sParentTable);
        $this->_oDbTable = $oDbTable;
        return $this;
    }
    
    public function getDbTable() {
        if (null === $this->_oDbTable) {
            $this->setDbTable('Mg_Model_DbTable_Bnd');
        }
        return $this->_oDbTable;
    }   
}
