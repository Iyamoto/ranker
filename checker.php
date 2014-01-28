<?php

/*
 * Project description
 */
$exec_time = microtime(true);
require_once 'config.php';
require_once 'functions.php';
echo "\n[+] Started\n";

$filename = 'urls.txt';
$list = file_get_contents($filename);
$urls = list2lines($list);
$urls = array_unique($urls);
if (!$urls)
    exit('[-] Cant load topics');
//shuffle($urls);

$ref_url = 'http://google.com/'; //FIXME get better ref url
foreach ($urls as $url) {
    $url = trim($url);
    echo "[+] Processing url: $url\n";
    $hash_url = md5($url);
    $debug_file = $tmp_dir . DIRECTORY_SEPARATOR . $hash_url . '.html'; //cache for debug
    $in = http_get_debug($url, $debug_file, $ref_url);
    if (!$in)
        exit('[-] Cant load html');
    $ref_url = $url;

    //Clear a bit
    $tidy = tidy_html($in['FILE']);

    //Base Url 
    $base_url = get_base_page_address($in['STATUS']['url']);
    //echo "[+] Base url: $base_url\n";
    //Get blocks from html
    $html_blocks = get_blocks($tidy, $block_marks);
    if (!$html_blocks) {
        echo "[-] No good blocks\n";
        continue;
    }
    var_dump($html_blocks);
    exit;
    $corrupt_blocks = 0;
    $hashes = '1';
    //Blocks to elements
    for ($i = 0; $i < count($html_blocks); $i++) {
        $fill = 0;
        $raw_text = strip_tags($html_blocks[$i]);
        $blocks[$i]['clear_text'] = clear_text($raw_text);
        if (strlen($blocks[$i]['clear_text']) > 0)
            $fill++;

        $blocks[$i]['hash'] = md5($blocks[$i]['clear_text']);
        if (stristr($hashes, $blocks[$i]['hash'])) {
            echo "[i] Found duplicated block\n";
            $corrupt_blocks++;
            continue;
        }
        $hashes.= $blocks[$i]['hash'] . "\n";

        $blocks[$i]['date'] = $today;

        $blocks[$i]['name'] = get_name($html_blocks[$i]);
        if (strlen($blocks[$i]['name']) > 4)
            $fill++;
        else
            echo "[-] Short name\n";

        $blocks[$i]['links'] = get_links($html_blocks[$i], $base_url);
        if (sizeof($blocks[$i]['links']) > 0)
            $fill++;


        $blocks[$i]['magnet'] = get_magnet($html_blocks[$i]);
        if (strlen($blocks[$i]['magnet']) > 0)
            $fill++;

        if ($fill < 4) {
            echo "[-] Corrupted block: $i\n";
            $corrupt_blocks++;
        }


        $global_blocks[] = $blocks[$i];
    }

    echo "[i] Corrupted blocks: $corrupt_blocks\n";

    unset($blocks);
    //break;
}

$global_size = sizeof($global_blocks);
echo "[+] $global_size global blocks found\n";


//Save suspects to json
if (save_json($db_file, $global_blocks))
    echo "[+] Saved to $db_file\n";

$exec_time = round(microtime(true) - $exec_time, 2);
echo "[i] Execution time: $exec_time sec.\n";
?>
