<?php
define('OUTPUT_DIR', __DIR__ . '/data');
/*
$aSvc = $aResponse['vpage']['data']['businesses']['GeoObjectCollection']['features'];
*/

// get quest
$iQuestStatus = intval(file_get_contents('./quest.status'));
$quests = file('./quest.txt');
$sCurrentQuest = trim($quests[$iQuestStatus]);
echo "{$sCurrentQuest}";
file_put_contents('./quest.status', $iQuestStatus+1);
if (empty($sCurrentQuest)) {
    die('FIN');
}

$oCurl = curl_init();
curl_setopt($oCurl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($oCurl, CURLOPT_COOKIEFILE, './yacookie.txt');
curl_setopt($oCurl, CURLOPT_COOKIEJAR, './yacookie.txt');
curl_setopt($oCurl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:29.0) Gecko/20100101 Firefox/29.0');
curl_setopt($oCurl, CURLOPT_URL, 'http://maps.yandex.ru');

$res = curl_exec($oCurl);
$sKey = '';
if (preg_match('/key:\'([0-9a-z]+)\'/i', $res, $matches)) {
    $sKey = $matches[1];
}

// construct request
$aParams = array(
    'output' => 'json',
    'serpid' => '',
    'parent_reqid' => '',
    'key' => $sKey,
    'source' => 'form',
    'perm' => '',
    'z' => 16,
    'sspn' => '0.033903%2C0.009872',
    'sll' => urlencode($sCurrentQuest),
    'text' => 'автосервис',
    'results' => 100,
);
$sUrl = 'http://maps.yandex.ru/?';
foreach ($aParams as $k=>$v) {
    $sUrl .= "{$k}={$v}&";
}
curl_setopt($oCurl, CURLOPT_URL, $sUrl);
$res2 = curl_exec($oCurl);

$filename = str_replace(',', '_', $sCurrentQuest);
file_put_contents(OUTPUT_DIR . '/' . $filename . '.json', $res2);
//$aRes = json_decode($res2, true);
//$aSvc = $aRes['vpage']['data']['businesses']['GeoObjectCollection']['features'];
curl_close($oCurl);
?>
<script>
setTimeout('location.reload()', 5000);
</script>