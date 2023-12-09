<?php

$forge = [];

$config_files = glob(__DIR__.'/forge-web-*');

foreach ($config_files as $config_file){
    $forge = array_merge($forge,require $config_file);
}

return $forge;

