<?php

class Mg_Helper_Role
{
    static protected $_roles = array(
        'guest' => 'Гость',
        'member' => 'Участник',
        'owner' => 'Представитель техцентра',
        'admin' => 'Администрация',
        'blocked' => 'Изгой',
    );
    
    public static function getRoleName($sRoleId) {
        return ( isset(self::$_roles[$sRoleId]) ) ? self::$_roles[$sRoleId] : 'unknown';
    }
}
