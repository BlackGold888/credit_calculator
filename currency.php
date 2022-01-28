<?php
$url = 'https://www.bank.lv/vk/ecb.xml?date=20050323';
$xml=simplexml_load_file($url) or die("Error: Cannot create object");
$states = [];
foreach($xml->children() as $state)
{
    $states[]= $state[0];
}
echo json_encode($states);
