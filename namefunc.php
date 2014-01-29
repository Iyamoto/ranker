<?php

mb_internal_encoding('UTF-8');

function name2ru($name){
	$phon = espeak($name);
	$ru = nametranslit($phon);
	$tmp = explode(' ', $ru);
	$ru1 = mb_ucfirst($tmp[0]);
	if(sizeof($tmp)>1) $ru2 = mb_ucfirst($tmp[1]);
	else $ru2='';
	$ru = $ru1.' '.$ru2;
	$ru = trim($ru);
	return $ru;
}

function espeak($text){
	$path = '"C:\Program Files\eSpeak\command_line\espeak.exe"';
	$cmd = $path.' -q -x "'.$text.'"';
	$out = shell_exec($cmd);
	$out = str_replace("'",'',$out);
	$out = trim($out);
	return $out;
}

function nametranslit($text)    {
//http://espeak.sourceforge.net/phonemes.html
        $rus_b = array('ор', 'ю', 'ар', 'ер', 'и', 'ай', 'ой', 'ау', 'оу', 'дж', 'эй', 'ч', 'п', 'б', 'т', 'д', 'к', 'г', 'ф', 'в', 'т', 'з', 'с', 'з', 'ш', 'ж', 'х', 'н', 'м', 'нг', 'л', 'р', 'й', 'у', 'л', 'а', 'эр', 'е', 'а', 'э', 'и', 'и', 'о', 'у', 'у', 'о', 'а');
        $eng_b = array('o@', 'ju', 'A@', '3:', 'I2', 'aI', 'OI', 'aU', 'oU', 'dZ', 'eI', 'tS', 'p', 'b', 't', 'd', 'k', 'g', 'f','v', 'T', 'D', 's', 'z', 'S', 'Z', 'h', 'n', 'm', 'N', 'l', 'r', 'j', 'w', '@L', '@', '3', 'a', 'A:', 'E', 'I', 'i', '0', 'u', 'U', 'O', 'V');

		$text = trim($text);
        $text = str_replace($eng_b, $rus_b, $text);
		$text = str_replace(',','',$text);
		$text = str_replace(';','',$text);
		$text = str_replace(':','',$text);
		$text = str_replace('-','',$text);
		$text = str_replace('_','',$text);
		$text = str_replace('|','',$text);
                $text = str_replace('#','',$text);
        return $text;
}

function mb_ucfirst($str, $lower_str_end = false, $encoding = "UTF-8") {
	$first_letter = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding);
	$str_end = "";
	if ($lower_str_end) {
		$str_end = mb_strtolower(mb_substr($str, 1, mb_strlen($str, $encoding), $encoding), $encoding);
	}
	else {
		$str_end = mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);
	}
	$str = $first_letter . $str_end;
	return $str;
}

function file2text($filename){
	$tmp = file_get_contents($filename);
	if (strstr($tmp,'﻿')) $tmp = mb_strcut($tmp,3);	
	return $tmp;
}
?>