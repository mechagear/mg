<?php

class Base_IndexController extends Mg_Controller_Abstract 
{
    public function init() {
        parent::init();
        $this->_helper->AjaxContext()->addActionContext('feedback', 'json')->initContext('json');
        
        $aCars = array();
        $aCars[] = new Mg_Cars_Model_CarModel(array('name' => 'Camry'));
        $aCars[] = new Mg_Cars_Model_CarModel(array('name' => 'Avensis'));
        $aCars[] = new Mg_Cars_Model_CarModel(array('name' => 'Corolla'));
        $aCars[] = new Mg_Cars_Model_CarModel(array('name' => 'Другая модель'));
        $this->view->aCars = $aCars;
    }
    
    public function indexAction() {
        
    }
    
    public function contactAction() {
        
    }
    
    public function feedbackAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        $aParams = $this->getRequest()->getPost();
        if ( empty($aParams['phone']) ) {
            $this->view->result = false;
            $this->view->error = 'Введите номер телефона';
            return;
        }
        
        $oMail = new Zend_Mail('UTF8');
        $oMail->addTo('rodikov@yandex.ru');
        $oMail->addTo('9698114@mail.ru');
        
        $oMail->setBodyText(implode("\n", $aParams));
        $oMail->setSubject('Запись на обслуживание [' . $_SERVER['HTTP_HOST'] . ']');
        //$oMail->setHeaderEncoding('UTF8');
        $oMail->send();
        
        $this->view->result = true;
    }
}
