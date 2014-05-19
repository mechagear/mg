<?php
class Shop_Admin_ShopmeasureController extends Mg_Controller_Admin
{
    public function init() {
        parent::init();
    }
    
    /**
     * Measures list
     */
    public function measuresAction() {
        $iShopId = intval($this->_getParam('iShopId', 0));
        $iPage = $this->_getParam('iPage', 1);
        
        $oShop = Mg_Shop_Helper_Shop::getShop($iShopId);
        
        
        $oShopMeasureMapper = new Mg_Shop_Model_Mapper_Measure();
        $aWhere = array(
            array('id_shop = ?', $iShopId),
        );
        $oShopMeasures = $oShopMeasureMapper->getList($aWhere, null, $iPage, 20);
        
        $this->view->oShopMeasures = $oShopMeasures;
        $this->view->oShop = $oShop;
        
        Mg_Common_Helper_Breadcrumbs::setBreadcrumbs(array(
            array('is_mvc' => true, 'route' => 'shop-list', 'label' => 'Магазины', 'params' => array()),
            array('is_mvc' => true, 'route' => 'shop-categories-list', 'label' => $oShop->name, 'params' => array('iShopId' => $oShop->id_shop)),
            array('is_mvc' => true, 'route' => 'shop-categories-measures-list', 'label' => 'Единицы измерения', 'params' => array('iShopId' => $oShop->id_shop)),
        ));
    }
    
    public function measureeditAction() {
        $iShopId = intval($this->_getParam('iShopId', 0));
        $iMeasureId = intval($this->_getParam('iMeasureId', 0));
        
        $oShop = Mg_Shop_Helper_Shop::getShop($iShopId);
        if ( $iMeasureId > 0 ) {
            $oMeasure = Mg_Shop_Helper_Measure::getMeasure($iMeasureId);
        } else {
            $oMeasure = new Mg_Shop_Model_Measure();
        }
        
        if ( $this->getRequest()->isPost() ) {
            $aParams = $this->getRequest()->getPost();
            
            if ( !empty($aParams['measureNameFull']) ) {
                $oMeasure->name_full = $aParams['measureNameFull'];
            }
            if ( !empty($aParams['measureNameShort']) ) {
                $oMeasure->name_short = $aParams['measureNameShort'];
            }
            if ( !empty($aParams['measureForms']) ) {
                $oMeasure->forms = array_slice($aParams['measureForms'], 0, 3);
            }
            
            if ( $oMeasure->id_measure <= 0 ) {
                $oMeasure->id_shop = $oShop->id_shop;
            }
            
            $oMeasureMapper = new Mg_Shop_Model_Mapper_Measure();
            
            if ( $iId = $oMeasureMapper->save($oMeasure) ) {
                $this->redirect($this->view->url(array('iShopId'=>$oShop->id_shop, 'iPage' => 1,),'shop-categories-measures-list'));
                exit;
            }
        }
        
        $this->view->oShop = $oShop;
        $this->view->oMeasure = $oMeasure;
        
        Mg_Common_Helper_Breadcrumbs::setBreadcrumbs(array(
            array('is_mvc' => true, 'route' => 'shop-list', 'label' => 'Магазины', 'params' => array()),
            array('is_mvc' => true, 'route' => 'shop-categories-list', 'label' => $oShop->name, 'params' => array('iShopId' => $oShop->id_shop)),
            array('is_mvc' => true, 'route' => 'shop-categories-measures-list', 'label' => 'Единицы измерения', 'params' => array('iShopId' => $oShop->id_shop)),
            array('is_mvc' => false, 'uri' => '/', 'label' => 'Редактор')
        ));
    }
}