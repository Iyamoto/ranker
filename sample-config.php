<?php

/*
 * Example config file for the project
 * Rename to config.php
 */

date_default_timezone_set('America/Los_Angeles');

//$emails and $netflow_base_dir must be set 
$emails[] = 'name@domain.zone'; //Whom to report
$web_dir = '/var/www/ranker';
$tmp_dir = '/tmp/ranker';
$tpl_dir = 'tpl';
$today = date("Y-m-d");
if (!is_dir($tmp_dir))
    mkdir($tmp_dir);
$db_file = $tmp_dir . DIRECTORY_SEPARATOR . 'onerun.gz';
$global_db_file = $tmp_dir . DIRECTORY_SEPARATOR . 'global.gz';

//Marks for blocks
$block_marks[] = '';
$block_marks[] = '';

//What are we looking for?
$marks[] = '1';
$marks[] = '2';

$debug = false;

$test_results = '';
$test_results2 = '';
?>
