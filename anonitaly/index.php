<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
<meta name="description" content="AnonItaly - Il portale italiano sugli anonymous" />
<meta name="keywords" content="anonitaly, AnonItaly, anonymous italia" />
<link href="http://mybeat.it/anonitaly/style.css" rel="stylesheet" type="text/css" />
<link href="http://mybeat.it/anonitaly/images/favicon.ico" rel="shortcut icon" />
<title>AnonItaly</title>
<link rel="alternate" type="application/rss+xml" title="RSS" href="http://mybeat.it/AnonItFeed">
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
</head>
<body>
<div id="header"><a href="http://mybeat.it/AnonItaly">AnonItaly - Il portale italiano sugli Anonymous</a></div>
<div id="container">
<?php

include('connessione.php');

$select = mysql_query("SELECT * FROM ai_news ORDER BY id DESC");
if(mysql_num_rows($select) == 0) {
	echo 'Nessuna news trovata.';
} else {
	while($ref = mysql_fetch_assoc($select)) {
?>
<div class="newstitle"><span class="title"><a name="<?php echo $ref['titolo']; ?>" href="#<?php echo $ref['titolo']; ?>"><?php echo $ref['titolo']; ?></a></span></div>
<div class="newstext"><?php echo $ref['testo']; ?></div>
<?php
	}
}
?>

</div>
<div id="cp">Copyright <?php echo date('Y'); ?> &copy;. All rights reserved to zypp0. <?php if($_COOKIE['Accesso'] == "zypp0") {?><a href="http://mybeat.it/AnonManage">AnonManage</a> <?php } ?></div>
<div id="mini">Desideri essere sempre al corrente sulle news di AnonItaly?<br />
Iscriviti ai nostri <em>Feed RSS</em>!<br /><br />
Clicca sulla seguente immagine e una volta nella pagina, clicca su "Abbonati adesso". Così facendo apparirà il segnalibro di AnonItaly nel tuo browser, sempre a portata di mano!<br /><br />
<a href="http://mybeat.it/AnonItFeed"><img src="http://mybeat.it/altro/RSS.PNG" /></a>
</div>
</body>
</html>