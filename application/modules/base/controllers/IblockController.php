<?php

class Base_IblockController extends Mg_Controller_Abstract
{
    
    public function init() {
        $iIblockId = $this->_getParam('iIblockId', 0);
        $sIblockCode = $this->_getParam('sIblockCode', '');
        
        if ( !$iIblockId && empty($sIblockCode) ) {
            throw new Mg_Common_Exception_NotFound();
        }
        
        $oIblock = new Mg_Base_Model_Iblock();
        $oIblock = ( !empty($sIblockCode) ) ? Mg_Base_Helper_Iblock::getIblockByCode($sIblockCode) : Mg_Base_Helper_Iblock::getIblock($iIblockId);
        
        // Define root route
        $aRoute = explode('-' , $this->getFrontController()->getRouter()->getCurrentRouteName());
        $this->view->sRootRoute = array_shift($aRoute);
        $this->view->oIblock = $oIblock;
    }
    
    public function indexAction() {
        $oIblockElementMapper = new Mg_Base_Model_Mapper_IblockElement();
        $aWhere = array();
        $oElements = $oIblockElementMapper->getList($aWhere, array('name ASC'), 1, 20);
        
        $this->view->oElements = $oElements;
    }
    
    public function elementAction() {
        $sCategoryUrl = $this->_getParam('sCategoryUrl', '');
        $sElementUrl = $this->_getParam('sElementUrl', '');
        
        $oCategory = Mg_Base_Helper_IblockCategory::getCategoryByUrl($sCategoryUrl, $this->view->oIblock->id_iblock);
        $oElement = Mg_Base_Helper_IblockElement::getElementByUrl($sElementUrl, $oCategory->id_category);
        
        $this->view->oCategory = $oCategory;
        $this->view->oElement = $oElement;
    }
    
    
    public function listwidgetAction() {
        $sRoute = $this->_getParam('route', '');
        $sIblockCode = $this->_getParam('sIblockCode', '');
        $oIblock = Mg_Base_Helper_Iblock::getIblockByCode($sIblockCode);
        if ( !$oIblock ) {
            return false;
        }
        
        $oCategories = Mg_Base_Helper_IblockCategory::getIblockCategoriesFlatTree($oIblock->id_iblock);
        $aCategories = array();
        foreach ($oCategories as $aCategory) {
            $aCategories[] = $aCategory['id_category'];
        }
        
        $oIblockElementMapper = new Mg_Base_Model_Mapper_IblockElement();
        $aWhere = array();
        if ( !empty($aCategories) ) {
            $aWhere[] = array('id_category IN (' . implode(',', $aCategories) . ')', '');
        }
        $oElements = $oIblockElementMapper->getList($aWhere, array('date_publish DESC', 'id_element DESC'), 1, 3);
        
        $this->view->oElements = $oElements;
        $this->view->oIblock = $oIblock;
        $this->view->sRoute = $sRoute;
    }
}