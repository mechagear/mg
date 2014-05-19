<?php
define('OUTPUT_DIR', __DIR__ . '/data');

$file = $_GET['file'];
$f = file_get_contents(OUTPUT_DIR . '/' . $file);
$aResponse = json_decode($f, true);


$aSvc = $aResponse['vpage']['data']['businesses']['GeoObjectCollection']['features'];
?>
<table cellspacing="0" cellpadding="2" border="1">
    <tr>
        <th>yaid</th>
        <th>Название</th>
        <th>Адрес</th>
        <th>Телефоны</th>
        <th>Координаты</th>
    </tr>
<?
foreach ($aSvc as $aCompany) {
    
    //die(var_dump($aCompany['properties']['CompanyMetaData']));
    if ( empty($aCompany['properties']['CompanyMetaData']) ) {
        //die(var_dump($aCompany));
    }
    
    $aService = array(
        'name' => $aCompany['properties']['CompanyMetaData']['name'],
        'address' => $aCompany['properties']['CompanyMetaData']['address'],
        'geo' => array(
            'precision' => $aCompany['properties']['CompanyMetaData']['Geo']['precision'],
            'boundedBy' => $aCompany['boundedBy']
        ),
        'phones' => $aCompany['properties']['CompanyMetaData']['Phones'],
    );
    
    $aService = (!empty($aCompany['properties']['CompanyMetaData'])) ? $aCompany['properties']['CompanyMetaData'] : $aCompany['properties']['PSearchObjectMetaData'];
    
    ?>
    <tr>
        <td><?=$aService['id']?></td>
        <td><?=$aService['name']?></td>
        <td><?=$aService['address']?></td>
        <td><?=print_r($aService['Phones'], true)?></td>
        <td><?=print_r($aCompany['properties']['boundedBy'], true)?></td>
    </tr>
    <?
}
?>
</table>