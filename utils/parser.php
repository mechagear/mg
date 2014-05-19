<?php
$address = 'http://maps.yandex.ru/?text=%D0%90%D0%B2%D1%82%D0%BE%D1%81%D0%B5%D1%80%D0%B2%D0%B8%D1%81&sll=37.61767117499998%2C55.75576817496109&sspn=2.169800%2C0.426712&z=10&type=&perm=&source=form&parent_reqid=139963381826559329&serpid=139963381826559329&key=uf2f5fb0c8685d7e34fbe0c4c4753ee9e&output=json';
$content = file_get_contents($address);
file_put_contents('./svc.txt', $content);
?>
