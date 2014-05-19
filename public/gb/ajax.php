<?php

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/plain; charset=utf-8' . "\r\n";

$fType = intval($_POST['ftype']);
$fType = (2 == $fType) ? 2 : 1;

$header = 'Новая заявка с сайта mirglavbuh.ru [' . ((2 == $fType)?'Попробовать':'Прайс-лист') . ']';
$mail = "";
$mail .= "Имя: {$_REQUEST['fname']} \nТелефон: {$_REQUEST['fphone']} \nE-mail: {$_REQUEST['fmail']} \nПродукт: {$_REQUEST['fproduct']} \nТип: " . ((2 == $fType)?'Попробовать бесплатно':'Получить прайс-лист') . "\n";

mail('Antoshka.vasilev.85@mail.ru,rodikov@yandex.ru', $header, $mail, $headers);
echo json_encode(array('success' => 1));
exit;
?>
