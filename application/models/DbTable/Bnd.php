<?php

class Mg_Model_DbTable_Bnd extends Mg_Model_DbTable_Abstract
{
    protected $_name = '';
    protected $_primary = array('id_parent', 'id_child');
    
    public function setName($sName) {
        $this->_name = $sName;
    }
}

