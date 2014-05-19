<?php

echo 'Generating quest<br/>';

$quest = '';
for ($x = 37.36; $x <= 37.90; $x+=0.01) {
    for ($y = 55.89; $y >= 55.55; $y-=0.01) {
        $quest .= "{$x},{$y}\n";
    }
}
file_put_contents('./quest.txt', $quest);
?>
