<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap 
{
    
    protected function _initRedis() {
        /*$oRedis = new Redis();
        if ($oRedis->connect('127.0.0.1', 6379, 2)) {
            Zend_Registry::set('redis', $oRedis);
        }*/
        //$oRedis->close();
        //Zend_Registry::set('redis', new Redis());
    }
    
    protected function _initRoutes() {
        $this->bootstrap('frontController');
        $oFront = $this->getResource('frontController');
        $oRouter = new Zend_Controller_Router_Rewrite();
        
        $sPathToRoutes = __DIR__ . '/routes.xml';
        if (defined('ROUTES_PATH')) {
            $sPathToRoutes = ROUTES_PATH;
        }
        
        $oRoutes = new Zend_Config_Xml($sPathToRoutes);
        $oRouter->addConfig($oRoutes);
        $oRouter->removeDefaultRoutes();
        $oFront->setRouter($oRouter);
    }
    
    protected function _initLoader() {
        // Small lifehack to use resources plugins while bootstrapping
        $oFront = Zend_Controller_Front::getInstance();
        $oFront->setParam('bootstrap', $this);
        
        $oLoader = $this->getResourceLoader();
        // Common helpers
        $oLoader->addResourceType('common_helper', 'helpers', 'Common_Helper');
        // Common exception
        $oLoader->addResourceType('common_exception', 'exceptions', 'Common_Exception');
        // Common roles
        $oLoader->addResourceType('common_role', 'roles', 'Common_Role');
        // Common paginator
        $oLoader->addResourceType('common_paginator', 'models/paginators', 'Common_Paginator');
        // Common model
        $oLoader->addResourceType('common_model', 'models', 'Model');
        // Common Controller
        $oLoader->addResourceType('common_controller', 'controllers', 'Controller');
        // Common addons
        $oLoader->addResourceType('common_addon', 'addons', 'Addon');
    }
    
    protected function _initModules() {
        $oLoader = $this->getResourceLoader();
        $aModules = Mg_Common_Helper_Module::getModules();
        foreach ($aModules as $aModule) {
            $sPrefix = $aModule['filename'];
            $oLoader->addResourceType($sPrefix . '_admin_controller', 'modules/' . $sPrefix . '/controllers/admin', ucfirst($sPrefix) . '_Admin');
            $oLoader->addResourceType($sPrefix . '_helper', 'modules/' . $sPrefix . '/helpers', ucfirst($sPrefix) . '_Helper');
            $oLoader->addResourceType($sPrefix . '_model', 'modules/' . $sPrefix . '/models', ucfirst($sPrefix) . '_Model');
            $oLoader->addResourceType($sPrefix . '_model_mapper', 'modules/' . $sPrefix . '/models/mappers', ucfirst($sPrefix) . '_Model_Mapper');
            $oLoader->addResourceType($sPrefix . '_exception', 'modules/' . $sPrefix . '/exceptions', ucfirst($sPrefix) . '_Exception');
            $oLoader->addResourceType($sPrefix . '_role', 'modules/' . $sPrefix . '/roles', ucfirst($sPrefix) . '_Role');
        }
    }
    
    protected function _initDoctype() {
        $this->bootstrap('view');
        $oView = $this->getResource('view');
        $oView->doctype('HTML5');
    }
    
    protected function _initCss() {
        $oView = $this->getResource('view');
        // append stylesheets
        $aCss = $this->getOption('css');
        if ( count($aCss) ) {
            foreach ($aCss as $sCss) {
                $oView->headLink()->appendStylesheet($sCss);
            }
        }
        // append less stylesheets
        $aLess = $this->getOption('less');
        if ( count($aLess) ) {
            foreach ($aLess as $sLess) {
                $oView->headLink()->appendStylesheet($sLess, 'screen', false, array('rel' => 'stylesheet/less',));
            }
        }
    }
    
    protected function _initJs() {
        $oView = $this->getResource('view');
        $aJs = $this->getOption('js');
        if ( !empty($aJs) ) {
            foreach ($aJs as $sKey => $sJs) {
                if ( 'extend' == $sKey && is_array($sJs) ) {
                    foreach ($sJs as $aExt) {
                        $sSrc = $aExt['src'];
                        $sType = !empty($aExt['type']) ? $aExt['type'] : 'text/javascript';
                        $oView->headScript()->appendFile($sSrc, $sType, $aExt);
                    }
                    continue;
                }
                $oView->headScript()->appendFile($sJs);
            }
        }
    }
    
    protected function _initMeta() {
        $oView = $this->getResource('view');
        $sEncoding = $this->getOption('encoding');
        $oView->headMeta()->appendHttpEquiv('Content-Type' ,'text/html; charset=' . $sEncoding);
        
        $aMeta = $this->getOption('meta');
        if ( !empty($aMeta) ) {
            foreach ($aMeta as $sName => $sValue) {
                $oView->headMeta()->appendName($sName, $sValue);
            }
        }
    }
    
    protected function _initTitle() {
        $oView = $this->getResource('view');
        $sTitle = $this->getOption('title');
        $oView->headTitle()->append($sTitle);
    }
    
    protected function _initNavigation() {
        $oNavXml = new Zend_Config_Xml(DOMAIN_PATH . '/navigation.xml');
        $oNavigation = new Zend_Navigation($oNavXml);
        Zend_Registry::set('Zend_Navigation', $oNavigation);
    }
    
    protected function _initSession() {
        Zend_Session::start();
    }
    
    protected function _initAcl() {
        $oAcl = new Zend_Acl();
        
        $oAcl->addRole('blocked');
        $oAcl->addRole('guest');
        $oAcl->addRole('member', 'guest');
        $oAcl->addRole('owner', 'member');
        $oAcl->addRole('admin', array('owner'));
        
        $oAcl->addResource('user');
        $oAcl->addResource('cp'); 
        
        $oAcl->allow('member', 'user', 'view');
        $oAcl->allow('member', 'user', 'edit');
        
        $oAcl->allow('admin', 'cp', 'view');
        
        Zend_Registry::set('acl', $oAcl);
    }
    
    protected function _initConfig() {
        $oConfig = $this->getOptions();
        Zend_Registry::set('config', $oConfig);
    }
    
}
