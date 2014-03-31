<?php

class Base_Admin_IblockController extends Mg_Controller_Admin
{
    public function init() {
        parent::init();
        $this->_helper->AjaxContext()->addActionContext('ajaxelementimages', 'json')->initContext('json');
    }
    
    /**
     * List of iblocks
     */
    public function iblockAction() {
        $iPage = $this->_getParam('iPage',1);
        $oIblockMapper = new Mg_Base_Model_Mapper_Iblock();
        $oIblocks = $oIblockMapper->getList(null, array('name ASC'), $iPage, 20);
        $this->view->oIblocks = $oIblocks;
        
        Mg_Common_Helper_Breadcrumbs::setBreadcrumbs(array(
            array('is_mvc' => true, 'route' => 'iblock-list', 'label' => 'Инфоблоки', 'params' => array()),
        ));
    }
    /**
     * Iblock category & elements
     */
    public function iblockcategoryAction() {
        $iPage = $this->_getParam('iPage',1);
        $iIblockId = $this->_getParam('iIblockId',0);
        $iIdCategory = $this->_getParam('iCategoryId',0);
        
        $oIblock = Mg_Base_Helper_Iblock::getIblock($iIblockId);
        $oCategory = ($iIdCategory > 0) ? Mg_Base_Helper_IblockCategory::getIblockCategory($iIdCategory) : new Mg_Base_Model_IblockCategory();
        
        $this->view->iIblockId = $iIblockId;
        $this->view->oCategory = $oCategory;
        $this->view->oCategories = Mg_Base_Helper_IblockCategory::getIblockChildCategories($iIdCategory, $iIblockId);
        //$this->view->oElements = $oElements;
        Mg_Common_Helper_Breadcrumbs::setBreadcrumbs(array(
            array('is_mvc' => true, 'route' => 'iblock-list', 'label' => 'Инфоблоки', 'params' => array()),
            array('is_mvc' => true, 'route' => 'iblock-category', 'label' => $oIblock->name, 'params' => array('iIblockId' => $iIblockId, 'iCategoryId' => 0, 'iPage' => 1,)),
            array('is_mvc' => true, 'route' => 'iblock-category', 'label' => ($oCategory->id_category > 0) ? $oCategory->name : 'Корень', 'params' => array('iIblockId' => $iIblockId, 'iCategoryId' => $oCategory->id_category, 'iPage' => 1,)),
        ));
    }
    
    public function iblockelementsAction() {
        $iPage = $this->_getParam('iPage',1);
        $iIblockId = $this->_getParam('iIblockId',0);
        $iIdCategory = $this->_getParam('iCategoryId',0);
        
        $oIblock = Mg_Base_Helper_Iblock::getIblock($iIblockId);
        $oCategory = ($iIdCategory > 0) ? Mg_Base_Helper_IblockCategory::getIblockCategory($iIdCategory) : new Mg_Base_Model_IblockCategory();
        
        $oIblockElementMapper = new Mg_Base_Model_Mapper_IblockElement();
        $aWhere = array(
            array('id_category = ?', $iIdCategory),
        );
        $oElements = $oIblockElementMapper->getList($aWhere, array('name ASC'), $iPage, 20);
        
        $this->view->iIblockId = $iIblockId;
        $this->view->oCategory = ($iIdCategory > 0) ? Mg_Base_Helper_IblockCategory::getIblockCategory($iIdCategory) : new Mg_Base_Model_IblockCategory();
        $this->view->oElements = $oElements;
        
        Mg_Common_Helper_Breadcrumbs::setBreadcrumbs(array(
            array('is_mvc' => true, 'route' => 'iblock-list', 'label' => 'Инфоблоки', 'params' => array()),
            array('is_mvc' => true, 'route' => 'iblock-category', 'label' => $oIblock->name, 'params' => array('iIblockId' => $iIblockId, 'iCategoryId' => 0, 'iPage' => 1,)),
            array('is_mvc' => true, 'route' => 'iblock-category', 'label' => ($oCategory->id_category > 0) ? $oCategory->name : 'Корень', 'params' => array('iIblockId' => $iIblockId, 'iCategoryId' => $oCategory->id_category, 'iPage' => 1,)),
            array('is_mvc' => true, 'route' => 'iblock-elements', 'label' => 'Элементы', 'params' => array('iIblockId' => $iIblockId, 'iCategoryId' => 0, 'iPage' => 1,)),
        ));
    }
    /**
     * Iblock element editing
     */
    public function iblockelementeditAction() {
        $iElementId = $this->_getParam('iElementId', 0);
        $iCategoryId = $this->_getParam('iCategoryId', 0);
        
        if ( $iElementId > 0 ) {
            $oElement = Mg_Base_Helper_IblockElement::getElement($iElementId);
        } else {
            $oElement = new Mg_Base_Model_IblockElement();
        }
        
        $oCategory = ($oElement->id_category > 0) ? Mg_Base_Helper_IblockCategory::getIblockCategory($oElement->id_category) : Mg_Base_Helper_IblockCategory::getIblockCategory($iCategoryId);
        $oIblock = Mg_Base_Helper_Iblock::getIblock($oCategory->id_iblock);
        
        $aImages = ($oElement->id_element) ? Mg_Base_Helper_Image::getImages($oElement->id_element, Mg_Base_Helper_Image::OBJ_IBLOCK_ELEMENT_IMAGE, 'iblock_element_preview', 1) : array();
        $aImagePreferences = Mg_Base_Helper_Image::getPreferences();
        
        // save
        if ( $this->getRequest()->isPost() ) {
            $aParams = $this->getRequest()->getPost();
            
            if ( isset($aParams['ibelementIdCategory']) ) {
                $oElement->id_category = $aParams['ibelementIdCategory'];
            }
            if ( !empty($aParams['ibelementName']) ) {
                $oElement->name = $aParams['ibelementName'];
            }
            if ( !empty($aParams['ibelementUrl']) ) {
                $oElement->url = $aParams['ibelementUrl'];
            }
            if ( !empty($aParams['ibelementShortDescription']) ) {
                $oElement->short_text = $aParams['ibelementShortDescription'];
            }
            if ( !empty($aParams['ibelementText']) ) {
                $oElement->text = $aParams['ibelementText'];
            }
            if ( !empty($aParams['ibelementTitle']) ) {
                $oElement->title = $aParams['ibelementTitle'];
            }
            if ( !empty($aParams['ibelementKeywords']) ) {
                $oElement->keywords = $aParams['ibelementKeywords'];
            }
            if ( !empty($aParams['ibelementDescription']) ) {
                $oElement->description = $aParams['ibelementDescription'];
            }
            
            if ( !empty($aParams['ibelementDatePublish']) ) {
                $sHours = ( !empty($aParams['ibelementDatePublishHH']) ) ? $aParams['ibelementDatePublishHH'] : '00';
                $sMinutes = ( !empty($aParams['ibelementDatePublishMM']) ) ? $aParams['ibelementDatePublishMM'] : '00';
                $sDate = $aParams['ibelementDatePublish'] . ' ' . $sHours . ':' . $sMinutes . ':00';
                $oElement->date_publish = $sDate;
            } else {
                $oElement->date_publish = date('Y-m-d H:i:s');
            }
            
            $oElement->date_change = date('Y-m-d H:i:s');
            
            if ( $oElement->id_element == 0 ) {
                $oElement->id_user = 1;
                $oElement->id_status = 1;
            }
            
            $oElementMapper = new Mg_Base_Model_Mapper_IblockElement();
            
            $oFileAdapter = new Zend_File_Transfer_Adapter_Http();
            $aUploadFiles = $oFileAdapter->getFileInfo();
            
            if ( $iId = $oElementMapper->save($oElement) || !empty($aUploadFiles) || !empty($aParams['itemImages']) ) {
                if ( 0 == $oElement->id_element ) {
                    $oElement->id_element = $iId;
                }
                
                if ( !empty($aUploadFiles) ) {
                    Mg_Base_Helper_Image::uploadImage($oElement->id_element, Mg_Base_Helper_Image::OBJ_IBLOCK_ELEMENT_IMAGE, $aUploadFiles);
                }
                
                if ( !empty($aParams['itemImages']) ) {
                    $iOrder = 0;
                    foreach ($aParams['itemImages'] as $sImageName) {
                        Mg_Base_Helper_Image::updateOrderByOriginName($oElement->id_element, Mg_Base_Helper_Image::OBJ_IBLOCK_ELEMENT_IMAGE, $sImageName, $iOrder);
                        ++$iOrder;
                    }
                    // reload images
                    $aImages = Mg_Base_Helper_Image::getImages($oElement->id_element, Mg_Base_Helper_Image::OBJ_IBLOCK_ELEMENT_IMAGE, 'iblock_element_preview', 1);
                }
                
                if ( !empty($aParams['return']) ) {
                    $this->redirect($this->view->url(array('iIblockId' => $oCategory->id_iblock, 'iCategoryId' => $oCategory->id_category),'iblock-elements'));
                } else {
                    $this->redirect($this->view->url(array('iIblockId' => $oCategory->id_iblock, 'iCategoryId' => $oCategory->id_category, 'iElementId' => $oElement->id_element),'iblock-editelement'));
                }
            }
            
        }
        
        $this->view->oCategory = $oCategory;
        $this->view->aCategories = Mg_Base_Helper_IblockCategory::getIblockCategoriesFlatTree($this->view->oCategory->id_iblock);
        $this->view->oElement = $oElement;
        $this->view->aImages = $aImages;
        $this->view->aImagePreferences = $aImagePreferences;
        
        Mg_Common_Helper_Breadcrumbs::setBreadcrumbs(array(
            array('is_mvc' => true, 'route' => 'iblock-list', 'label' => 'Инфоблоки', 'params' => array()),
            array('is_mvc' => true, 'route' => 'iblock-category', 'label' => $oIblock->name, 'params' => array('iIblockId' => $oIblock->id_iblock, 'iCategoryId' => 0, 'iPage' => 1,)),
            array('is_mvc' => true, 'route' => 'iblock-category', 'label' => ($oCategory->id_category > 0) ? $oCategory->name : 'Корень', 'params' => array('iIblockId' => $oIblock->id_iblock, 'iCategoryId' => $oCategory->id_category, 'iPage' => 1,)),
            array('is_mvc' => true, 'route' => 'iblock-editelement', 'label' => ($oElement->id_element > 0) ? $oElement->name : 'Новый элемент', 'params' => array('iCategoryId' => 0, 'iElementId' => $oElement->id_element,)),
        ));
    }
    
    
    // ------------------ AJAX ----------------
    public function ajaxelementimagesAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        $aResult = array();
        
        $iElementId = intval($this->_getParam('iElementId'));
        //$oImages = Mg_Base_Helper_Image::getImages($iElementId, Mg_Base_Helper_Image::OBJ_IBLOCK_ELEMENT_IMAGE, 'iblock_element_preview');
        $oImages = Mg_Common_Helper_Image::getImages(1, 1, 'big', 0);
        
        $this->view->result = $oImages;
    }
}
