<?php

class Mg_Common_Role_Guest extends Zend_Acl_Role_Interface {
    
    protected $_aclRoleId = null;
    
    public function getRoleId() {
        if ($this->_aclRoleId == null) {
            return 'guest';
        }
        return $this->_aclRoleId;
    }
    
}