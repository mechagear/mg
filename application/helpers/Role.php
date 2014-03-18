<?php

class Mg_Common_Helper_Role
{
    static protected $_roles = array(
        'guest' => 'Гость',
        'member' => 'Участник',
        'owner' => 'Представитель техцентра',
        'admin' => 'Администратор',
        'blocked' => 'Заблокирован',
    );
    
    public static function getRoleName($sRoleId) {
        return ( isset(self::$_roles[$sRoleId]) ) ? self::$_roles[$sRoleId] : 'unknown';
    }
    
    public static function getRoles() {
        return self::$_roles;
    }
}
