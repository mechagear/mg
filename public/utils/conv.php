<?php
define('OUTPUT_DIR', __DIR__ . '/data');
$quests = file('./quest.txt');

$iQuestStatus = intval(file_get_contents('./quest.status'));
$sCurrentQuest = trim($quests[$iQuestStatus]);
echo '<h1>' . $sCurrentQuest . ' (' . $iQuestStatus . ') </h1>';
if (empty($sCurrentQuest)) {
    die('FIN');
}

mysql_connect('127.0.0.1', 'servicearea', 'servicearea');
mysql_select_db('servicearea');
mysql_set_charset('UTF8');
//mysql_query("SET NAMES 'UTF8'");

$filename = str_replace(',', '_', $sCurrentQuest);
$sContent = file_get_contents(OUTPUT_DIR . '/' . $filename . '.json');

$aContent = json_decode($sContent, true);
$aSvc = $aContent['vpage']['data']['businesses']['GeoObjectCollection']['features'];

foreach ($aSvc as $aService) {
    if ('Feature' != $aService['type']) {
        echo "Skipping block<br/>";
        continue;
    }
    $aService = (!empty($aService['properties']['CompanyMetaData'])) ? $aService['properties']['CompanyMetaData'] : $aService['properties']['PSearchObjectMetaData'];
    
    $iYaId = intval($aService['id']);
    $sName = !empty($aService['name'])?$aService['name']:'-автосервис-';
    $sUrl = !empty($aService['url'])?$aService['url']:'';
    
    $sSql = "SELECT COUNT(*) AS `cnt` FROM tmp_ya_services WHERE ya_id = '{$iYaId}'";
    $res = mysql_query($sSql);
    $v = mysql_fetch_array($res);
    $v = intval(array_shift($v));
    if ($v > 0) {
        echo "ERR: -DOUBLE- {$iYaId} {$sName} {$sUrl}<br/>";
        continue;
    }
    
    $sSql = "INSERT INTO tmp_ya_services (ya_id, name, url, parent_id) VALUES ('{$iYaId}', '{$sName}', '{$sUrl}', 0)";
    mysql_query($sSql);
    echo "ADD: {$iYaId} {$sName} {$sUrl}<br/>";
}

file_put_contents('./quest.status', $iQuestStatus+1);
?>
<script>
setTimeout('location.reload()', 500);
</script>