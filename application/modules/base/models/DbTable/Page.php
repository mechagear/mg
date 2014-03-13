<?php

class Mg_Base_Model_DbTable_Page extends Mg_Model_DbTable_Abstract
{
    protected $_name = 'tbl_page';
    protected $_primary = 'id_page';
    protected $_referenceMap = array(
        'Mg_Model_DbTable_Status' => array(
            'columns' => array('id_status',),
            'refTableClass' => 'Mg_Model_DbTable_Status',
            'refColumns' => array('id_status'),
        ),
    );
}
