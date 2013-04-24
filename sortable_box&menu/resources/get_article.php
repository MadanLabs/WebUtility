<?php
error_reporting(E_ERROR | E_WARNING);
function curl_get($url){
    if (!function_exists('curl_init')){
        die('cURL non installato!');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

if(strtoupper($_SERVER['REQUEST_METHOD']) != "POST") {
	echo "Errore 2";
}

$cat = (htmlspecialchars($_POST['category'])!='') ? (htmlspecialchars($_POST['category'])) : false;
$url = (htmlspecialchars($_POST['link'])!='') ? (htmlspecialchars($_POST['link'])) : false;
if(empty($cat) || !in_array($cat, array('vg','music','soccer','tecn')) || empty($url)) {
	echo "Errore";
}

switch($cat) {
	case 'vg':
		$file = curl_get($url);
		preg_match("#<h1 class=\"title\">(.+?)</h1>#s", $file, $title);
		preg_match("#<div class=\"paragraphs\">(.+?)</div>#s", $file, $content);
		$content = (strlen(strip_tags($content[1],'<p><b><i><u><strong><em><span><br><br />')) > 600) ? substr(html_entity_decode(strip_tags($content[1],'<p><b><i><u><strong><em><span><br><br />')), 0, 600)."..." : html_entity_decode(strip_tags($content[1],'<p><a><b><i><u><strong><em><span>'));
		echo "<h3>".html_entity_decode($title[1])."</h3>".$content;
	break;
	case 'music':
		$file = curl_get($url);
		preg_match("#<h1>(.+?)</h1>#s", $file, $title);
		preg_match("#<section id=\"main-article\">(.+?)</section>#s", $file, $content);
		$content = (strlen(strip_tags($content[1],'<p><b><i><u><strong><em><span><br><br />')) > 600) ? substr(strip_tags($content[1],'<p><b><i><u><strong><em><span><br><br />'), 0, 600)."..." : strip_tags($content[1],'<p><a><b><i><u><strong><em><span>');
		echo "<h3>".$title[1]."</h3>".$content;
	break;
	case 'soccer':
		$file = curl_get($url);
		preg_match("#<title>(.+?)</title>#s", $file, $title);
		preg_match("#userAction.setDescription(.+?);#s", $file, $content);
		echo "<h3>".$title[1]."</h3>".str_replace(array('("','")','\\'), "", $content[1]);
	break;
	case 'tecn':
		$file = curl_get($url);
		preg_match("#<h1>(.+?)</h1>#s", $file, $title);
		preg_match("#<div class=\"corpo\" id=\"content-corpo\">(.+?)</div>#s", $file, $content);
		$content = (strlen(strip_tags($content[1],'<p><b><i><u><strong><em><span><br><br />')) > 600) ? substr(strip_tags($content[1],'<p><b><i><u><strong><em><span><br><br />'), 0, 600)."..." : strip_tags($content[1],'<p><a><b><i><u><strong><em><span>');
		echo "<h3>".utf8_encode($title[1])."</h3>".$content;
	break;
}
?>