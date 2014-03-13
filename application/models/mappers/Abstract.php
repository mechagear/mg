<?php

abstract class Mg_Model_Mapper_Abstract
{
    protected $_oDbTable;
    protected $_aFieldModifiers = array();
    protected $_sModel = '';
    
    public function setDbTable($oDbTable) {
        if (is_string($oDbTable) ) {
            $oDbTable = new $oDbTable();
        }
        if ( ! $oDbTable instanceof Mg_Model_DbTable_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_oDbTable = $oDbTable;
        return $this;
    }
    
    public function getDbTable() {
        return $this->_oDbTable;
    }
    /**
     * Return single item by PKEY
     * @param type $iId
     * @return type
     */
    public function getItem($iId) {
        $oDbTable = $this->getDbTable();
        
        if ( !empty($this->_aFieldModifiers) ) {
            $oSelect = new Zend_Db_Table_Select($oDbTable);
            $aColumns = array('*');
            foreach ($this->_aFieldModifiers as $sField => $aModifier) {
                switch ($aModifier['type']) {
                    case 'point':
                        $aColumns[] = new Zend_Db_Expr("X(`{$sField}`) AS `x`, Y(`{$sField}`) AS `y`");
                        break;
                    default:
                        $aColumns[] = $sField;
                        break;
                }
            }
            $oSelect->from($oDbTable->getTable());
            $oSelect->columns($aColumns);
            // TODO: primary is array?
            $oSelect->where( $oDbTable->getPrimary() . ' = ?', $iId);
            $oSelect->limit(1);
            $oResult = $oDbTable->fetchAll($oSelect)->current();
        } else {
            if ( is_array($oDbTable->getPrimary()) && is_array($iId) ) {
                $oResult = call_user_func_array(array($oDbTable, 'find'), $iId);
                $oResult = $oResult->current();
            } else {
                $oResult = $oDbTable->find($iId)->current();
            }
        }
        
        if ( !empty($this->_sModel) ) {
            $oModel = new $this->_sModel();
            if ($oResult) {
                $aResult = $oResult->toArray();
                if ( !empty($this->_aFieldModifiers) ) {
                    foreach ($this->_aFieldModifiers as $sField => $aModifier) {
                        switch ($aModifier['type']) {
                            case 'array':
                                $aResult[$sField] = json_decode($aResult[$sField], true);
                                break;
                            default: 
                                // nothing
                                break;
                        }
                    }
                }
                $oModel->setParams($aResult);
            }
            return $oModel;
        }
        
        return $oResult;
    }
    
    public function getItemByField($sField, $sValue) {
        $oDbTable = $this->getDbTable();
        
        $oSelect = new Zend_Db_Table_Select($oDbTable);
        $aColumns = array('*');
        foreach ($this->_aFieldModifiers as $sField => $aModifier) {
            switch ($aModifier['type']) {
                case 'point':
                    $aColumns[] = new Zend_Db_Expr("X(`{$sField}`) AS `x`, Y(`{$sField}`) AS `y`");
                    break;
                default:
                    $aColumns[] = $sField;
                    break;
            }
        }
        $oSelect->from($oDbTable->getTable());
        $oSelect->columns($aColumns);
        $oSelect->where( $sField . ' = ?', $sValue);
        $oSelect->limit(1);
        $oResult = $oDbTable->fetchAll($oSelect)->current();
        if ( !empty($this->_sModel) ) {
            $oModel = new $this->_sModel();
            if ($oResult) {
                $aResult = $oResult->toArray();
                if ( !empty($this->_aFieldModifiers) ) {
                    foreach ($this->_aFieldModifiers as $sField => $aModifier) {
                        switch ($aModifier['type']) {
                            case 'array':
                                $aResult[$sField] = json_decode($aResult[$sField], true);
                                break;
                            default: 
                                // nothing
                                break;
                        }
                    }
                }
                $oModel->setParams($aResult);
            }
            return $oModel;
        }
        return $oResult;
    }
    
    public function getList($aWhere = null, $aOrder = null, $iPage = 0, $iLimit = 0) {
        $oDbTable = $this->getDbTable();
        // Constructing Select
        $oSelect = new Zend_Db_Table_Select($oDbTable);
        $oSelect->from($this->getDbTable()->getTable());
        if ( !empty($this->_aFieldModifiers) ) {
            $aColumns = array('*');
            foreach ($this->_aFieldModifiers as $sField => $aModifier) {
                switch ($aModifier['type']) {
                    case 'point':
                        $aColumns[] = new Zend_Db_Expr("X(`{$sField}`) AS `x`, Y(`{$sField}`) AS `y`");
                        break;
                    default:
                        $aColumns[] = $sField;
                        break;
                }
            }
            $oSelect->columns($aColumns);
        }
        
        if ( !empty($aWhere) && is_array($aWhere) ) {
            foreach ($aWhere as $sKey => $sValue) {
                if ( is_array($sValue) ) {
                    if ( isset($sValue[2]) && $sValue[2] == 'OR' ) {
                        $oSelect = $oSelect->orWhere($sValue[0], $sValue[1]);
                    } else {
                        $oSelect = $oSelect->where($sValue[0], $sValue[1]);
                    }
                } else {
                    $oSelect = $oSelect->where($sValue);
                }
            }
        }
        $oSelect = $oSelect->order($aOrder);
        
        
        // Init paginator options
        $oPaginatorSelect = new Mg_Common_Paginator_DbTableModelSelect($oSelect, $this->_sModel);
        $oPaginator = new Zend_Paginator($oPaginatorSelect);
        if ( $iPage <= 0 || $iLimit <= 0 ) {
            $iLimit = -1;
            $iPage = 1;
        }
        $oPaginator->setItemCountPerPage($iLimit);
        $oPaginator->setCurrentPageNumber($iPage);
        return $oPaginator;
    }
    
    public function getListExt(Zend_Db_Select $oSelect, $iPage = 0, $iLimit = 0) {
        $oPaginatorSelect = new Mg_Common_Paginator_DbModelSelect($oSelect, $this->_sModel);
        $oPaginator = new Zend_Paginator($oPaginatorSelect);
        if ( $iPage <= 0 || $iLimit <= 0 ) {
            $iLimit = -1;
            $iPage = 1;
        }
        $oPaginator->setItemCountPerPage($iLimit);
        $oPaginator->setCurrentPageNumber($iPage);
        return $oPaginator;
    }
    
    public function save(Mg_Model_Abstract $oModel) {
        $aParams = $oModel->getParams();
        $sPrimary = $this->getDbTable()->getPrimary();
        if ( !is_array($sPrimary) ) {
            $iId = $aParams[$sPrimary];
            unset($aParams[$sPrimary]);
        }
        
        if ( !empty($this->_aFieldModifiers) ) {
            foreach ( $this->_aFieldModifiers as $sField => $aModifier ) {
                if ( empty($aParams[$sField]) ) {
                    continue;
                }
                switch ($aModifier['type']) {
                    case 'point':
                        $aParams[$sField] = new Zend_Db_Expr("POINT({$aParams[$sField]['x']},{$aParams[$sField]['y']})");
                        break;
                    case 'array':
                        $aParams[$sField] = json_encode($aParams[$sField]);
                        break;
                }
            }
        }
        
        if ( empty($iId) ) {
            $iRet = intval($this->getDbTable()->insert($aParams));
        } else {
            $iRet = $this->getDbTable()->update($aParams, array($sPrimary . ' = ?' => $iId));
            if ( $iRet > 0 ) {
                $iRet = intval($iId);
            } else {
                $iRet = 0;
            }
        }
        return $iRet;
    }
    
    public function delete($iId) {
        $oDbTable = $this->getDbTable();
        $aConditions = array();
        $primaryKey = array_values($oDbTable->getPrimary());
        if ( !is_array($primaryKey) ) {
            $aConditions[] = $oDbTable->getAdapter()->quoteInto($primaryKey.' = ?', $iId);
        } else {
            foreach ($primaryKey as $iKey => $pkeyPart) {
                if ( isset($iId[$iKey]) ) {
                    $aConditions[] = $oDbTable->getAdapter()->quoteInto($pkeyPart.' = ?', $iId[$iKey]);
                }
            }
        }
        $iDeletedCount = $oDbTable->delete($aConditions);
        return $iDeletedCount;
    }
    
}
