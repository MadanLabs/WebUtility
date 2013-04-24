<?php
error_reporting(E_ALL || E_WARNING);
if(!isset($_COOKIE['color'])) {
	setcookie("color", "black", strtotime("+1 year"), "/");
	$color = "black";
} else {
	$color = $_COOKIE['color'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="it" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<meta name="Description" content="Teenspace - Il portale dedicato ai giovani per restar aggiornati sulle principali news!" />
	<meta name="Keywords" content="teenspace, news, news videogiochi, news calcio, news musica, news tecnologia, portale, portale giovani" />
	<title>Teenspace</title>
	<script src="scripts/jquery-1.9.0.min.js" type="text/javascript"></script>
	<script src="scripts/jquery-ui-1.10.1.min.js" type="text/javascript"></script>
	<script src="scripts/jquery.nivo.slider.js" type="text/javascript"></script>
	<link rel="stylesheet" href="css/bar.css" type="text/css" />
    <link rel="stylesheet" href="css/nivo-slider.css" type="text/css" />
    <link rel="stylesheet" href="css/style.css" type="text/css" />
	<link rel="stylesheet" href="css/colors.css" type="text/css" />
	<script src="scripts/functions.js" type="text/javascript"></script>
	<!--[if !IE]> -->
	<script src="scripts/animations.js" type="text/javascript"></script>
	<!-- <![endif]-->
	<script type="text/javascript">
	var page = "home";
	$(function() {
		$('#slider').nivoSlider();
	});
	</script>
	<script src="scripts/menu.js" type="text/javascript"></script>
</head>
<body>
<div id="colorpicker">
	<div id="picker" style="border: 1px solid <?php echo $color; ?>; border-left: none;">
		<div class="color <?php if($color == "black") { echo "active";} ?>" rel="black" style="background: black;"></div><div class="color <?php if($color == "red") { echo "active";} ?>" style="background: red;" rel="red"></div><div class="color <?php if($color == "blue") { echo "active";} ?>" style="background: blue;" rel="blue"></div><div class="color <?php if($color == "green") { echo "active";} ?>" style="background: green;" rel="green"></div><div class="color <?php if($color == "violet") { echo "active";} ?>" style="background: violet;" rel="violet"></div>
		<div class="clear"></div>
	</div>
	<div id="icon" style="background: <?php echo $color; ?>"><img src="images/colorpicker.png" /></div>
	<div class="clear"></div>
</div>
	<div id="container">
		<div id="logo"></div>
		<div id="menu" class="<?php echo $color; ?>"><!--[if IE]><div id="cont"><![endif]--><a href="javascript:void(0);" class="tp" id="home">Home</a><a href="videogames.php" id="vgLink">Videogiochi</a><a href="music.php" id="musicLink">Musica</a><a href="soccer.php" id="soccerLink">Calcio</a><a href="technology.php" id="tecnLink">Tecnologia</a><!--[if IE]></div><![endif]--></div>
		<div id="wrapper">
			<div class="slider-wrapper theme-bar">
				<div id="slider" class="nivoSlider">
					<img src="images/soccer.jpg" alt="" />
					<img src="images/videogames.jpg" alt="" title="Seconda" />
					<img src="images/music.jpg" alt="" />
					<img src="images/technology.jpg" alt="" />
				</div>
			</div>
		</div>
		<div id="boxes">
		<?php
			if(isset($_COOKIE['boxs_order'])) {
				$boxs_cookie = explode(";", $_COOKIE['boxs_order']);
				$boxs = array();
				foreach($boxs_cookie as $box) {
					$tmp_box = explode("=", $box);
					$boxs[$tmp_box[1]] = $tmp_box[0];
				}
				ksort($boxs);
			} else {
				$boxs = array("vg","music","soccer","tecn");
			}
				foreach($boxs as $newbox) {
					switch($newbox) {
						case 'vg':
						?>
						<div class="box <?php echo $color; ?>" id="vg">
							<div class="title <?php echo $color; ?>">videogiochi</div>
							<div class="content"><div style="margin-top: 100px; text-align: center;"><img src="images/loader.gif" /></div></div>
						</div>
						<?php
						break;
						case 'music':
						?>
						<div class="box <?php echo $color; ?>" id="music">
							<div class="title <?php echo $color; ?>">musica</div>
							<div class="content"><div style="margin-top: 100px; text-align: center;"><img src="images/loader.gif" /></div></div>
						</div>
						<?php
						break;
						case 'soccer':
						?>
						<div class="box <?php echo $color; ?>" id="soccer">
							<div class="title <?php echo $color; ?>">calcio</div>
							<div class="content"><div style="margin-top: 100px; text-align: center;"><img src="images/loader.gif" /></div></div>
						</div>
						<?php
						break;
						case 'tecn':
						?>
						<div class="box <?php echo $color; ?>" id="tecn">
							<div class="title <?php echo $color; ?>">tecnologia</div>
							<div class="content"><div style="margin-top: 100px; text-align: center;"><img src="images/loader.gif" /></div></div>
						</div>
						<?php
						break;
					}
				}
			?>
		</div>
	</div>
</body>
</html>