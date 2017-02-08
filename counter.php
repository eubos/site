<?php

$c = file('counter.txt');

if (isset($_GET['add']))
{
$c[0]++;
$fp = fopen('counter.txt','w');
fputs($fp,$c[0]);
fclose($fp); 
}

echo $c[0];

?>