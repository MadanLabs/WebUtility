<?php
if($_COOKIE['Accesso'] != "zypp0") {
header ('Location: anonitaly/index.php');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
<meta name="description" content="AnonItaly - Il portale italiano sugli anonymous" />
<meta name="keywords" content="anonitaly, AnonItaly, anonymous italia" />
<link href="http://mybeat.it/anonitaly/style.css" rel="stylesheet" type="text/css" />
<link href="http://mybeat.it/anonitaly/images/favicon.ico" rel="shortcut icon" />
<!-- jQuery -->
<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.2.min.js"></script>
<!-- markItUp! -->
<script type="text/javascript" src="http://mybeat.it/anonitaly/markitup/jquery.markitup.js"></script>
<!-- markItUp! toolbar settings -->
<script type="text/javascript" src="http://mybeat.it/anonitaly/markitup/sets/default/set.js"></script>
<!-- markItUp! skin -->
<link rel="stylesheet" type="text/css" href="http://mybeat.it/anonitaly/markitup/skins/markitup/style.css" />
<!--  markItUp! toolbar skin -->
<link rel="stylesheet" type="text/css" href="http://mybeat.it/anonitaly/markitup/sets/default/style.css" />
<title>AnonItaly</title>
</head>
<body>
<script type="text/javascript">
<!--
$(document).ready(function()	{
	// Add markItUp! to your textarea in one line
	// $('textarea').markItUp( { Settings }, { OptionalExtraSettings } );
	$('#markItUp').markItUp(mySettings);
	
	// You can add content from anywhere in your page
	// $.markItUp( { Settings } );	
	$('.add').click(function() {
 		$.markItUp( { 	openWith:'<opening tag>',
						closeWith:'<\/closing tag>',
						placeHolder:"New content"
					}
				);
 		return false;
	});
	
	// And you can add/remove markItUp! whenever you want
	// $(textarea).markItUpRemove();
	$('.toggle').click(function() {
		if ($("#markItUp.markItUpEditor").length === 1) {
 			$("#markItUp").markItUpRemove();
			$("span", this).text("get markItUp! back");
		} else {
			$('#markItUp').markItUp(mySettings);
			$("span", this).text("remove markItUp!");
		}
 		return false;
	});
});
-->
</script>
<div id="header"><a href="http://mybeat.it/anonitaly/index.php">AnonItaly - Il portale italiano sugli Anonymous</a></div>
<div id="container">
<div class="newstext">
<?php

include('connessione.php');

$azione = ($_GET['azione']) ? $_GET['azione'] : 'home';
switch($azione) {
case 'home':
?>
Cosa desideri fare?<br /><br />
<a href="?azione=crea">>> Crea una news</a><br /><br />
<a href="http://mybeat.it/AnonItaly">>> Torna ad AnonItaly</a>
<?php
break;
case 'crea':
?>
<form action="?azione=crea" method="POST">
<label for="titolo">Titolo</label><br />
<input type="text" name="titolo" style="width: 350px;" id="titolo" /><br /><br />
<label for="markItUp">Testo</label><br />
<textarea id="markItUp" cols="80" rows="20" name="testo"></textarea><br /><br />
<input type="submit" value="Crea" name="crea" /></form><br />
<?php
	if(isset($_POST['crea'])) {
		$titolo = $_POST['titolo'];
		$testo = nl2br($_POST['testo']);
		mysql_query("INSERT INTO ai_news(titolo,testo) VALUES ('$titolo','$testo')");
$from = <<<EOT
<?xml version="1.0" encoding="ISO-8859-1"?>
<rss version="2.0">
    <channel>
        <title>AnonItaly</title>
        <description>AnonItaly - Portale italiano sugli Anonymous</description>
        <link>http://www.mybeat.it/AnonItaly</link>
        <lastBuildDate>Tue, 22 Nov 2005 19:52:44 +0100</lastBuildDate>
        <generator>Nerd Herd Inc.</generator>		
EOT;

$arrfrom = array('<?xml version="1.0" encoding="ISO-8859-1"?>','<rss version="2.0">','<channel>','<title>AnonItaly</title>','<description>AnonItaly - Portale italiano sugli Anonymous</description>','<link>http://www.mybeat.it/AnonItaly</link>','<lastBuildDate>Tue, 22 Nov 2005 19:52:44 +0100</lastBuildDate>','<generator>Nerd Herd Inc.</generator>');
$arrto = array('','','','','','','','');
		$rss = str_replace($arrfrom, $arrto, file_get_contents('news.xml'));
$new = <<<EOT
	        <item>
            <title>{$titolo}</title>
            <link>http://www.mybeat.it/AnonItaly#{$titolo}</link>
            <description></description>
            <author>zypp0</author>
			</item>	
EOT;
		$fp = fopen('news.xml','w+');
		fwrite($fp, $from.$new.$rss);
		fclose($fp);
		
		echo 'News aggiunta correttamente. <meta http-equiv="refresh" content="2; URL=http://mybeat.it/AnonManage" />';
	}
break;
}
?>
</div>
</div>
<div id="cp">Copyright <?php echo date('Y'); ?> &copy;. All rights reserved to zypp0.</div>
</body>
</html>