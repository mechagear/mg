<?php

class ErrorController extends Zend_Controller_Action
{
    public function errorAction() {
        $oError = $this->_getParam('error_handler');
        switch ($oError->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
 
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Страница не найдена';
                break;
            default:
                if ( !isset($oError->exception->type) ) {
                    $oError->exception->type = 0;
                }
                switch ($oError->exception->type) {
                    case Mg_Common_Exception_AccessDenied::EXCEPTION_ACCESS_DENIED:
                        $this->getResponse()->setHttpResponseCode(403);
                        $this->view->message = 'Доступ запрещен. Если не секрет, как вы нашли эту страницу?';
                        break;
                    case Mg_Common_Exception_NotFound::EXCEPTION_NOT_FOUND:
                        $this->getResponse()->setHttpResponseCode(404);
                        $this->view->message = 'Страница не найдена.';
                        break;
                    default:
                        // application error
                        $this->getResponse()->setHttpResponseCode(500);
                        $this->view->message = 'Джамшут в сервернама пащёляма, клющ пафирнуляма, кешельбе-мешельбе, вушельбекельме… польный пизнес, насяйника!';
                        break;
                }
                break;
        }
        $this->view->exception = $oError->exception;
        $this->view->request   = $oError->request;
        $this->view->env = APPLICATION_ENV;
    }
}
