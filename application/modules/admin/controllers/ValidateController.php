<?php

class Admin_ValidateController extends Mg_Controller_Abstract
{
    protected $oConfig;
    
    public function init() {
        parent::init();
        if (!$this->oAcl->isAllowed($this->oUser, 'cp', 'view') && !in_array($this->getRequest()->getActionName(), array('auth', 'unauth'))) {
            $this->redirect($this->view->url(array(),'auth'), array('exit' => true,));
            throw new Mg_Common_Exception_AccessDenied('No access');
            exit;
        }
        $this->oConfig = Zend_Registry::get('config');
        // Set json context for called action
        $this->_helper->contextSwitch()->addActionContext($this->getRequest()->getActionName(), 'json')->initContext('json');
    }
    
    public function fieldAction() {
        if ( !$this->getRequest()->isPost() ) {
            $this->view->errors = array('Критическая ошибка: неправильный тип запроса.');
            $this->view->result = false;
        } else {
            $aParams = $this->getRequest()->getPost();
            $sModelName = isset($aParams['validate_model']) ? $aParams['validate_model'] : '';
            $sFieldName = isset($aParams['validate_name']) ? $aParams['validate_name'] : '' ;
            if ( empty($sModelName) || empty($sFieldName) || !class_exists($sModelName) ) {
                $this->view->result = false;
                $this->view->errors = array('Критическая ошибка: повреждены данные.');
            } else {
                $aInitData = array(
                    $sFieldName => $aParams['validate_value'],
                );
                $aInitData = array_merge($aInitData, $aParams);
                $oModel = new $sModelName($aInitData);
                $this->view->result = $oModel->validateField($sFieldName, $aParams);
                if (!$this->view->result) {
                    $aErrors = $oModel->getValidationErrors($sFieldName);
                    $this->view->errors = $aErrors;
                }
            }
            $this->view->object = $oModel->getParams();
        }
    }
}
