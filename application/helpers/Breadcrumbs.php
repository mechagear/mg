<?php

class Mg_Common_Helper_Breadcrumbs 
{
    protected static $_oNavigation = false;
    
    protected static function getNavigation() {
        if (!self::$_oNavigation) {
            self::$_oNavigation = new Zend_Navigation();
        }
        return self::$_oNavigation;
    }
    
    public static function setBreadcrumbs($aPages = array()) {
        $oFront = Zend_Controller_Front::getInstance();
        //var_dump($oFront->getRequest()->);
        
        $oRootRoute = $oFront->getRouter()->getRoute('index');
        
        $oNavigation = self::getNavigation();
        // Add index page
        $oCurPage = new Zend_Navigation_Page_Mvc();
        $oCurPage->setLabel($oRootRoute->getDefault('title'));
        $oCurPage->setRoute('index');
        $oCurPage->setActive(true);
        $oNavigation->addPage($oCurPage);
        
        if ( !empty($aPages) ) {
            foreach ($aPages as $aPage) {
                $oCurPage->setActive(false);
                if ($aPage['is_mvc']) {
                    $oPage = new Zend_Navigation_Page_Mvc();
                    $oPage->setRoute($aPage['route']);
                } else { 
                    $oPage = new Zend_Navigation_Page_Uri();
                    $oPage->setUri($aPage['uri']);
                }
                if ( !empty($aPage['params']) ) {
                    $oPage->setParams($aPage['params']);
                }
                $oPage->setLabel($aPage['label']);
                $oPage->setActive(true);
                $oPage->setParent($oCurPage);
                $oCurPage = $oPage;
            }
        }
        Zend_Registry::set('Zend_Navigation', $oNavigation);
    }
    
}
