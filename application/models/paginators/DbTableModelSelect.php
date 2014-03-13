<?php

class Mg_Common_Paginator_DbTableModelSelect extends Zend_Paginator_Adapter_DbSelect
{
    public $_sModel = '';
    
    public function __construct(Zend_Db_Select $select, $sModel) {
        parent::__construct($select);
        if ( empty($sModel) ) {
            throw new Exception('Empty model name passed to paginator.');
        }
        $this->_sModel = $sModel;
    }
    
    
    public function getItems($offset, $itemCountPerPage)
    {
        $this->_select->limit($itemCountPerPage, $offset);
        
        $oResult = $this->_select->getTable()->fetchAll($this->_select);
        
        $aResult = array();
        foreach ($oResult as $oItem) {
            $aResult[] = new $this->_sModel($oItem->toArray());
        }
        return $aResult;
    }
}