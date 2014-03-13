<?php

class Mg_Common_Paginator_DbModelSelect extends Zend_Paginator_Adapter_DbSelect
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
        
        $aResult = $this->_select->query()->fetchAll();
        $aItems = array();
        foreach ($aResult as $aItem) {
            $aItems[] = new $this->_sModel($aItem);
        }
        return $aItems;
    }
}