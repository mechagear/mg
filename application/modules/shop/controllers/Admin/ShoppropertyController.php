<?php
class Shop_Admin_ShoppropertyController extends Mg_Controller_Admin
{
    public function init() {
        parent::init();
    }
    
    /**
     * Properties list
     */
    public function propertiesAction() {
        $iShopId = intval($this->_getParam('iShopId', 0));
        $iPage = $this->_getParam('iPage', 1);
        
        $oShop = Mg_Shop_Helper_Shop::getShop($iShopId);
        
        $oShopPropertyMapper = new Mg_Shop_Model_Mapper_ShopProperty();
        $aWhere = array(
            array('id_shop = ?', $iShopId),
        );
        $oShopProperties = $oShopPropertyMapper->getList($aWhere, null, $iPage, 20);
        $aShopPropertyTypes = Mg_Shop_Helper_ShopProperty::getPropertyTypes();
        $aShopPropertyViewTypes = Mg_Shop_Helper_ShopProperty::getPropertyViewTypes();
        
        $this->view->aShopPropertyTypes = $aShopPropertyTypes;
        $this->view->aShopPropertyViewTypes = $aShopPropertyViewTypes;
        $this->view->oShopProperties = $oShopProperties;
        $this->view->oShop = $oShop; 
        
        Mg_Common_Helper_Breadcrumbs::setBreadcrumbs(array(
            array('is_mvc' => true, 'route' => 'shop-list', 'label' => 'Магазины', 'params' => array()),
            array('is_mvc' => true, 'route' => 'shop-categories-list', 'label' => $oShop->name, 'params' => array('iShopId' => $oShop->id_shop)),
            array('is_mvc' => true, 'route' => 'shop-categories-properties-list', 'label' => 'Свойства', 'params' => array('iShopId' => $oShop->id_shop)),
        ));
    }
    
    public function propertyeditAction() {
        $iShopId = $this->_getParam('iShopId', 0);
        $iPropertyId = intval($this->_getParam('iPropertyId', 0));
        
        $oShop = Mg_Shop_Helper_Shop::getShop($iShopId);
        if ( $iPropertyId > 0 ) {
            $oProperty = Mg_Shop_Helper_ShopProperty::getProperty($iPropertyId);
        } else {
            $oProperty = new Mg_Shop_Model_ShopProperty();
        }
        
        $aShopPropertyTypes = Mg_Shop_Helper_ShopProperty::getPropertyTypes();
        $aShopPropertyViewTypes = Mg_Shop_Helper_ShopProperty::getPropertyViewTypes();
        $oMeasures = Mg_Shop_Helper_Measure::getMeasureByShop($iShopId);
        
        if ( $this->getRequest()->isPost() ) {
            $aParams = $this->getRequest()->getPost();
            
            if ( !empty($aParams['propertyType']) ) {
                $oProperty->type = $aParams['propertyType'];
            }
            if ( !empty($aParams['propertyViewType']) ) {
                $oProperty->view_type = $aParams['propertyViewType'];
            }
            if ( !empty($aParams['propertyName']) ) {
                $oProperty->name = $aParams['propertyName'];
            }
            if ( !empty($aParams['propertyType']) ) {
                $oProperty->id_measure = $aParams['propertyMeasure'];
            }
            
            if ( $oProperty->id_property <= 0 ) {
                $oProperty->id_shop = $oShop->id_shop;
            }
            
            $oPropertyMapper = new Mg_Shop_Model_Mapper_ShopProperty();
            
            if ( $iId = $oPropertyMapper->save($oProperty) ) {
                $this->redirect($this->view->url(array('iShopId'=>$oShop->id_shop, 'iPage' => 1,),'shop-categories-properties-list'));
                exit;
            }
            
        }
        
        $this->view->oMeasures = $oMeasures;
        $this->view->aShopPropertyTypes = $aShopPropertyTypes;
        $this->view->aShopPropertyViewTypes = $aShopPropertyViewTypes;
        $this->view->oShopProperty = $oProperty;
        
        Mg_Common_Helper_Breadcrumbs::setBreadcrumbs(array(
            array('is_mvc' => true, 'route' => 'shop-list', 'label' => 'Магазины', 'params' => array()),
            array('is_mvc' => true, 'route' => 'shop-categories-list', 'label' => $oShop->name, 'params' => array('iShopId' => $oShop->id_shop)),
            array('is_mvc' => true, 'route' => 'shop-categories-properties-list', 'label' => 'Свойства', 'params' => array('iShopId' => $oShop->id_shop)),
            array('is_mvc' => false, 'uri' => '/', 'label' => 'Редактор')
        ));
        
    }
    
}