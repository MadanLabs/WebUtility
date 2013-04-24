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

function isSiteAvailable($host) {
	$site = curl_get("http://www.downforeveryoneorjustme.com/$host");
	if(!preg_match("#It's not just you!#i", $site)) {
		return true;
	}
	return false;
}

if(strtoupper($_SERVER['REQUEST_METHOD']) != "POST") {
	echo "Errore 2";
}

$cat = (htmlspecialchars($_POST['category'])!='') ? (htmlspecialchars($_POST['category'])) : false;
if(empty($cat) || !in_array($cat, array('vg','music','soccer','tecn'))) {
	echo "Errore";
}

switch($cat) {
	case 'vg':
		if(isSiteAvailable("multiplayer.it")) {
			$archive = curl_get("http://multiplayer.it/notizie/");
			preg_match_all("#<a href=\"/notizie/(.*?)\" title#s", $archive, $news);
			echo "<ul>";
			for($i=2; $i<7; $i++) {
				$link = addslashes($news[1][$i]);
				preg_match("#<a href=\"/notizie/$link\" title=\"(.+?)\"#s", $archive, $title);
				$title = (strlen(str_replace(" - Notizia", "", html_entity_decode($title[1]))) > 55) ? (substr(str_replace(" - Notizia", "", html_entity_decode($title[1])), 0, 55)."...") : str_replace(" - Notizia", "", html_entity_decode($title[1]));
				echo "<li>&raquo; <a href=\"javascript:openDialog('http://multiplayer.it/notizie/$link','vg');\">$title</a></li>";
			}
			echo "</ul>";
		} else {
			echo "Non disponibile.";
		}
	break;
	case 'music':
		if(isSiteAvailable("rockol.it")) {
			$archive = curl_get("http://www.rockol.it/news_24h.php");
			preg_match_all("#<h3><a href=\"/news-(.*?)\">#s", $archive, $news);
			echo "<ul>";
			for($i=0; $i<5; $i++) {
				$link = addslashes($news[1][$i]);
				preg_match("#<h3><a href=\"/news-$link\">(.+?)</a></h3>#s", $archive, $title);
				$title = (strlen($title[1]) > 55) ? (substr($title[1], 0, 55)."...") : $title[1];
				echo "<li>&raquo; <a href=\"javascript:openDialog('http://www.rockol.it/news-$link','music');\">$title</a></li>";
			}
			echo "</ul>";
		} else {
			echo "Non disponibile.";
		}
	break;
	case 'soccer':
		if(isSiteAvailable("goal.com")) {
			$archive = curl_get("http://www.goal.com/it/news/archive/1?ICID=TOP_1");
			preg_match_all("#<div><a href=\"(.*?)\">#s", $archive, $news);
			echo "<ul>";
			for($i=0; $i<5; $i++) {
				$link = addslashes($news[1][$i]);
				preg_match("#<div><a href=\"$link\">(.+?)</a>#s", $archive, $title);
				$title = (strlen($title[1]) > 55) ? (substr(html_entity_decode($title[1]), 0, 55)."...") : html_entity_decode($title[1]);
				echo "<li>&raquo; <a href=\"javascript:openDialog('http://www.goal.com$link','soccer')\">$title</a></li>";
			}
			echo "</ul>";
		} else {
			echo "Non disponibile.";
		}
	break;
	case 'tecn':
		if(isSiteAvailable("ansa.it")) {
			$archive = curl_get("http://www.ansa.it/web/notizie/rubriche/tecnologia/tecnologia.shtml");
			preg_match_all("#<h3><a href=\"(.*?)\"#s", $archive, $news);
			echo "<ul>";
			for($i=0; $i<5; $i++) {
				$link = addslashes($news[1][$i]);
				preg_match("#<h3><a href=\"$link\" >(.+?)</a></h3>#s", $archive, $title);
				$title = (strlen($title[1]) > 55) ? (substr($title[1], 0, 55)."...") : $title[1];
				echo "<li>&raquo; <a href=\"javascript:openDialog('http://www.ansa.it$link','tecn');\">$title</a></li>";
			}
			echo "</ul>";
		} else {
			echo "Non disponibile.";
		}
	break;
}
?>