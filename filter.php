<?php

/*
 * Filter one run activity to daily data
 */
$exec_time = microtime(true);
require_once 'config.php';
require_once 'functions.php';
require_once 'namefunc.php';
echo "\n[+] Started\n";

//Read one_run
$one_run = read_db_from_file($db_file);
if ($one_run) {
    $one_run_size = sizeof($one_run);
    echo "[+] Read $one_run_size blocks from $db_file\n";
}
else
    exit('Problem with one_run file');

$html = '<table>';
$html .= '<thead><tr><th>Имя</th><th>Год рождения</th></tr></thead><tbody>';

foreach ($one_run as $data) {
    $ru = name2ru($data['name']);
    $year = $data['year'];
    $html.='<tr><td>' . $ru . '</td><td>' . $year . '</td></tr>';
}


$html .= '</tbody></table>';
echo "\n\n".$html."\n\n";

$exec_time = round(microtime(true) - $exec_time, 2);
echo "[i] Execution time: $exec_time sec.\n";
?>