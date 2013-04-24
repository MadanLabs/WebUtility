<?php
/*
Plugin Name: Classifica Squadre
Plugin URI: http://www.slimmer.it
Description: Gestisci la classifica delle squadre
Version: 1.0
Author: Madan Labs.
Author URI: http://www.slimmer.it
 */
define('CLSA_URL',get_option('siteurl').'/wp-content/plugins/classifica/');
$clsa_default = array(
	array('Squadra 1', 0,0,0,0,0,0,0,0),
	array('Squadra 2', 0,0,0,0,0,0,0,0),
	array('Squadra 3', 0,0,0,0,0,0,0,0),
	array('Squadra 4', 0,0,0,0,0,0,0,0),
	array('Squadra 5', 0,0,0,0,0,0,0,0),
	array('Squadra 6', 0,0,0,0,0,0,0,0),
	array('Squadra 7', 0,0,0,0,0,0,0,0),
	array('Squadra 8', 0,0,0,0,0,0,0,0),
	array('Squadra 9', 0,0,0,0,0,0,0,0),
	array('Squadra 10', 0,0,0,0,0,0,0,0),
	array('Squadra 11', 0,0,0,0,0,0,0,0),
	array('Squadra 12', 0,0,0,0,0,0,0,0),
	array('Squadra 13', 0,0,0,0,0,0,0,0),
	array('Squadra 14', 0,0,0,0,0,0,0,0),
	array('Squadra 15', 0,0,0,0,0,0,0,0),
	array('Squadra 16', 0,0,0,0,0,0,0,0)
);

$clsa_options = array(
	'Goal' => 0,
	'Goal Subiti' => 0,
	'Diff. Reti' => 0,
	'Vittorie' => 0,
	'Pareggi' => 0,
	'Sconfitte' => 0,
);

add_option('clsa_teams', $clsa_default);
add_option('clsa_options', $clsa_options);

function clsa_menu() {
	add_menu_page('Classifica', 'Classifica', 'administrator', 'classifica', 'c_form', CLSA_URL."images/menu.png");
	add_submenu_page('classifica', 'Impostazioni Classifica', 'Impostazioni', 'administrator', 'clsa_settings', 'clsa_settings');
}
add_action('admin_menu', 'clsa_menu');

function clsa_settings() {
	?>
	<div class="wrap"><div style="margin-bottom: 30px;"></div>
    <div class="icon32" style="background: url(<?php echo CLSA_URL; ?>images/icon.png) no-repeat; width: 32px; height: 32px;"><br /></div>
    <h2>Impostazioni Classifica</h2>
    <p>&nbsp;</p>
	<form action="?page=clsa_settings" method="POST">
	<p>Scegli i valori da mostrare nella classifica.</p>
	<label><input type="checkbox" name="" />
	</form>
	</div>
	<?php
}

function removeLast($arr) {
	$newarray = array();
	for($x=0;$x<count($arr)-1;$x++) {
		$newarray[] = $arr[$x];
	}
	return $newarray;
}

function newTeam($arr) {
	$newarray = array();
	foreach($arr as $a) {
		$newarray[] = ucfirst(strtolower($a[0]));
	}
	for($i = 1; ;$i++) {
		if(!in_array("Squadra $i", $newarray)) {
				$newname = "Squadra $i";
			break;
		}
	}
	return $newname;
}

function SlimClear($var) {
	return trim(addslashes(htmlspecialchars($var)));
}

global $currentTeams;
$currentTeams = get_option('clsa_teams');

function c_form() {
	global $currentTeams;
	$teamsIdArr = array();
	$selectTeams = mysql_query("SELECT * FROM classifica ORDER BY id ASC");
    ?>
	<style type="text/css">
	html, body {
		margin: 0px!important;
		padding: 0px!important;
	}
	
	thead {
		font-weight: bold;
		font-size: 14px;
	}
	
	.wrap img {
		margin-right: 10px;
	}
	
	.close_match {
		float: right;
		cursor: pointer;
		opacity: 0.6;
		margin-top: 5px;
		margin-right: 5px;
	}
	
	#overall {
		position: absolute;
		height: 100%;
		width: 100%;
		background: url(<?php echo CLSA_URL; ?>/images/bg.png);
		z-index: 100000;
	}
	
	#innerel {
		width: 300px;
		height: auto;
		min-height: 200px;
		background: #fff;
		margin: 0px auto;
		border-radius: 8px;
		-moz-border-radius: 8px;
		-webkit-border-radius: 8px;
		-o-border-radius: 8px;
		position: fixed;
		font: 12px Verdana;
	}
	
	#innerel #text {
		padding: 15px;
	}
	
	#innerel #bar {
		background: -moz-linear-gradient(100% 100% 90deg, #777, #444);
		background: -webkit-gradient(linear, right top, left bottom, from(#777), to(#444));
		height: 32px;
		border-radius: 7px 7px 5px 5px;
		-moz-border-radius: 7px 7px 5px 5px;
		-webkit-border-radius: 7px 7px 5px 5px;
		cursor: move;
	}
	</style>
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>
	<script type="text/javascript">
	function checkCan(key) {
		if($("#add" + key).attr("disabled") != "disabled") {
			return true;
		} else {
			return false;
		}		
	}
	
	$(function() {
		var margin_h = ($(window).height() - 200)/2;
		var margin_w = ($(window).width() - 300)/2;
		$(".add_match").click(function() {
			if($(this).attr("disabled") != "disabled") {
				var addMatchImg = $(this);
				var kID = $(this).attr("data-key");
				var eID = "";
				var nomeSquadra = $("input[name='" + kID + "team']").val();
				var selectSq = [];
				var squadreS = "<select id='enemy' style='font-style: italic;'><option value='default' style='font-style: italic;'>Scegli una squadra..&nbsp;&nbsp;</option>";
				<?php
				foreach($currentTeams as $ksps => $sps) {
					?>
				selectSq[<?php echo $ksps; ?>] = "<?php echo $sps[0]; ?>";
					<?php
				}
				?>
				$.each(selectSq, function(key, value) {
					if(value != nomeSquadra) {
						if(checkCan(key)) {
							squadreS = squadreS + "<option value='" + key + "' style='font-style: normal;'>" + value + "</option>";
						}
					}
				});
				squadreS = squadreS + "</select>";
				$("html").prepend("<div id='overall'><div id='innerel'><div id='bar'><img src='<?php echo CLSA_URL; ?>/images/close.png' class='close_match' title='Chiudi' /></div><div style='clear: both;'></div><div id='text'><strong>" + nomeSquadra + "</strong> <span style='margin-left: 5px; margin-right: 5px;'>VS</span> " + squadreS + "<br /><br /><div style='display: none' id='risultato'>" + nomeSquadra + " <input type='text' name='first_ris' style='width: 40px;' /> - <input type='text' name='second_ris' style='width: 40px;' /> <span id='avversari'></span><br /><br /><br /><button style='float: right;' class='button-primary' id='submitMatch'>Fatto!</button><div style='clear: both;'></div></div></div></div></div>");
				$("#innerel").css("top", margin_h);
				$("#innerel").css("left", margin_w);
				$("#innerel").draggable({ handle: "#bar" });
				$(".close_match").on({
					mouseenter: function() {
						$(this).animate({
							opacity: 0.9
						},"fast");
					},
					mouseleave: function() {
						$(this).animate({
							opacity: 0.6
						});
					},
					click: function() {
						$("#overall").fadeOut(300);
						$("#risultato").hide();
					}
				});
				$("#enemy").on("change", function() {
					if($(this).val() != "default") {
						$("#enemy").css("font-style","normal");
						eID = $("#enemy option:selected").val();
						$("#avversari").html($("#enemy option:selected").text());
						$("#risultato").fadeIn(150);
					} else {
						$("#enemy").css("font-style","italic");
						$("#risultato").hide();
					}
				});
				$("#submitMatch").on("click", function() {
					var squadraPunti = parseInt($("#innerel #text").find("input[name='first_ris']").val());
					var avversariPunti = parseInt($("#innerel #text").find("input[name='second_ris']").val());
					if(squadraPunti == "" || avversariPunti == "") {
						alert("Inserisci un risultato valido.");
					} else if($("#enemy option:selected").val() == "default") {
						alert("Scegli una squadra valida.");
					} else {
						if(squadraPunti > avversariPunti) {
							var diffRetiSq = squadraPunti - avversariPunti;
							var diffRetiAvv = avversariPunti - squadraPunti;
							var puntiSq = parseInt($("input[name='" + kID + "punti']").val()) + 3;
							var puntiAvv = parseInt($("input[name='" + eID + "punti']").val());
							var vittSquadra = 1;
							var vittAvv = 0;
							var sconSquadra = 0;
							var sconAvv = 1;
							var parSquadra = 0;
							var parAvv = 0;
						} else if(squadraPunti < avversariPunti) {
							var diffRetiSq = squadraPunti - avversariPunti;
							var diffRetiAvv = avversariPunti - squadraPunti;	
							var puntiSq = parseInt($("input[name='" + kID + "punti']").val());
							var puntiAvv = parseInt($("input[name='" + eID + "punti']").val()) + 3;
							var vittSquadra = 0;
							var vittAvv =1;
							var sconSquadra = 1;
							var sconAvv = 0;
							var parSquadra = 0;
							var parAvv = 0;						
						} else if(squadraPunti == avversariPunti) {
							var diffRetiAvv = 0;
							var diffRetiSq = 0;
							var vittSquadra = 0;
							var vittAvv = 0;
							var sconSquadra = 0;
							var sconAvv = 0;
							var parSquadra = 1;
							var parAvv = 1;
							var puntiSq = parseInt($("input[name='" + kID + "punti']").val()) + 1;
							var puntiAvv = parseInt($("input[name='" + eID + "punti']").val()) + 1;
						}
						$("input[name='" + eID + "punti']").val(puntiAvv);
						$("input[name='" + kID + "punti']").val(puntiSq);
						$("input[name='" + eID + "partite']").val(parseInt($("input[name='" + eID + "partite']").val()) + 1);
						$("input[name='" + kID + "partite']").val(parseInt($("input[name='" + kID + "partite']").val()) + 1);
						$("input[name='" + eID + "diff']").val(parseInt($("input[name='" + eID + "diff']").val()) + diffRetiAvv);
						$("input[name='" + kID + "diff']").val(parseInt($("input[name='" + kID + "diff']").val()) + diffRetiSq);
						$("input[name='" + eID + "fatti']").val(parseInt($("input[name='" + eID + "fatti']").val()) + avversariPunti);
						$("input[name='" + kID + "fatti']").val(parseInt($("input[name='" + kID + "fatti']").val()) + squadraPunti);
						$("input[name='" + eID + "subiti']").val(parseInt($("input[name='" + eID + "subiti']").val()) + squadraPunti);
						$("input[name='" + kID + "subiti']").val(parseInt($("input[name='" + kID + "subiti']").val()) + avversariPunti);
						$("input[name='" + eID + "vittorie']").val(parseInt($("input[name='" + eID + "vittorie']").val()) + vittAvv);
						$("input[name='" + kID + "vittorie']").val(parseInt($("input[name='" + kID + "vittorie']").val()) + vittSquadra);
						$("input[name='" + eID + "pareggi']").val(parseInt($("input[name='" + eID + "pareggi']").val()) + parAvv);
						$("input[name='" + kID + "pareggi']").val(parseInt($("input[name='" + kID + "pareggi']").val()) + parSquadra);
						$("input[name='" + eID + "sconfitte']").val(parseInt($("input[name='" + eID + "sconfitte']").val()) + sconAvv);
						$("input[name='" + kID + "sconfitte']").val(parseInt($("input[name='" + kID + "sconfitte']").val()) + sconSquadra);
						addMatchImg.attr({ disabled: "disabled", title: "Modifica - Hai già aggiunto un match!", src: "<?php echo CLSA_URL; ?>/images/edit_dis.png" });
						addMatchImg.css("cursor","not-allowed");
						$(".add_match").each(function() {
							if($(this).attr("data-key") == eID) {
								$(this).attr({ disabled: "disabled", title: "Modifica - Hai gia' aggiunto un match!", src: "<?php echo CLSA_URL; ?>/images/edit_dis.png" });
								$(this).css("cursor","not-allowed");						
							}
						});
						$("#overall").fadeOut(300);
						$("#risultato").hide();
					}
				});
			}
		});
	});	
	</script>
    <div class="wrap"><div style="margin-bottom: 30px;"></div>
        <div class="icon32" style="background: url(<?php echo CLSA_URL; ?>images/icon.png) no-repeat; width: 32px; height: 32px;"><br /></div>
        <h2>Classifica Squadre</h2>
        <p>&nbsp;</p>
	     <form method="post" action="?page=classifica">
            <table width="55%" id="tabella_squadre">
				<thead><tr><td width="15%">Squadra</td><td width="7%">Punti</td><td width="8%">Partite</td><td width="7%">Fatti</td><td width="8%">Subiti</td><td width="8%">D. Reti</td><td width="8%">Vittorie</td><td width="8%">Pareggi</td><td width="10%">Sconfitte</td><td width="15%">Azioni</td></tr></thead>
                <tbody>
				<tr><td>&nbsp;</td></tr>
				<?php
					foreach($currentTeams as $kID => $squadra) {
				?>
                    <tr>
						<td width="15%"><input type="hidden" name="kID" value="<?php echo $kID; ?>" />
							<input type="text" value="<?php echo $squadra[0]; ?>" name="<?php echo $kID; ?>team" />
						</td>
                        <td width="8%">
                            <input type="text" style="width: 40px;" value="<?php echo $squadra[1]; ?>" name="<?php echo $kID; ?>punti" />
                        </td>
                        <td width="8%">
                            <input type="text" style="width: 40px;" value="<?php echo $squadra[2]; ?>" name="<?php echo $kID; ?>partite" />
                        </td>
						<td width="8%">
							<input type="text" style="width: 40px;" value="<?php echo $squadra[3]; ?>" name="<?php echo $kID; ?>fatti" />
						</td>
						<td width="8%">
							<input type="text" style="width: 40px;" value="<?php echo $squadra[4]; ?>" name="<?php echo $kID; ?>subiti" />
						</td>
						<td width="8%">
							<input type="text" style="width: 40px;" value="<?php echo $squadra[5]; ?>" name="<?php echo $kID; ?>diff" />
						</td>
						<td width="8%">
							<input type="text" style="width: 40px;" value="<?php echo $squadra[6]; ?>" name="<?php echo $kID; ?>vittorie" />
						</td>
						<td width="8%">
							<input type="text" style="width: 40px;" value="<?php echo $squadra[7]; ?>" name="<?php echo $kID; ?>pareggi" />
						</td>
						<td width="8%">
							<input type="text" style="width: 40px;" value="<?php echo $squadra[8]; ?>" name="<?php echo $kID; ?>sconfitte" />
						</td>
						<td width="15%">
							<img src="<?php echo CLSA_URL; ?>images/edit.png" style="cursor: pointer;" class="add_match" id="add<?php echo $kID; ?>" title="Modifica - Aggiungi nuova partita" data-key="<?php echo $kID; ?>" />
						</td>
                    </tr>
				<?php } ?>
				</tbody>
			</table><br />
              <p style="float: left; margin-right: 15px;"><input type="submit" class="button-primary" id="submit" name="submitC" value="<?php _e('Save Changes') ?>" /> </p>
        </form><div style="margin-top: 14px;"><form action="" method="POST" style="float: left; margin-right: 1px;"><input type="hidden" name="action" value="remove" /><input type="image" src="<?php echo CLSA_URL; ?>images/remove.png" width="16" height="16" title="Rimuovi squadra" /></form><form action="" method="POST" style="float: left; margin-right: 5px;"><input type="hidden" name="action" value="add" /><input type="image" src="<?php echo CLSA_URL; ?>images/add.png" width="16" height="16" title="Aggiungi squadra" /></form><p style="line-height: 25px;">Rimuovi/Aggiungi Squadra</p></div><div style="clear: both;"></div>
    <?php
	if(isset($_POST['action'])) {
		switch($_POST['action']) {
			case 'add':
				$currentTeams[] = array(newTeam($currentTeams), 0, 0, 0, 0, 0, 0, 0, 0);
				update_option('clsa_teams', $currentTeams);
				?>
				<script type="text/javascript">
					window.location='?page=classifica';
				</script><noscript><meta http-equiv="refresh" content="0; URL=?page=classifica" /></noscript>
				<?php
			break;
			case 'remove':
				$updatedTeams = removeLast($currentTeams);
				update_option('clsa_teams', $updatedTeams);
				?>
				<script type="text/javascript">
					window.location='?page=classifica';
				</script><noscript><meta http-equiv="refresh" content="0; URL=?page=classifica" /></noscript>
				<?php
			break;
		}
	}
	if(isset($_POST['submitC'])) {
		$tErrors = 0;
		foreach($currentTeams as $tID => $tInfo) {
			$tName = (SlimClear($_POST[$tID."team"])!='') ? (SlimClear($_POST[$tID."team"])) : "Null";
			$tPunti = (SlimClear($_POST[$tID."punti"])!='') ? (SlimClear($_POST[$tID."punti"])) : 0;
			$tPartite = (SlimClear($_POST[$tID."partite"])!='') ? (SlimClear($_POST[$tID."partite"])) : 0;
			$tFatti = (SlimClear($_POST[$tID."fatti"])!='') ? (SlimClear($_POST[$tID."fatti"])) : 0;
			$tSubiti = (SlimClear($_POST[$tID."subiti"])!='') ? (SlimClear($_POST[$tID."subiti"])) : 0;
			$tDiff = (SlimClear($_POST[$tID."diff"])!='') ? (SlimClear($_POST[$tID."diff"])) : 0;
			$tVittorie = (SlimClear($_POST[$tID."vittorie"])!='') ? (SlimClear($_POST[$tID."vittorie"])) : 0;
			$tPareggi = (SlimClear($_POST[$tID."pareggi"])!='') ? (SlimClear($_POST[$tID."pareggi"])) : 0;
			$tSconfitte = (SlimClear($_POST[$tID."sconfitte"])!='') ? (SlimClear($_POST[$tID."sconfitte"])) : 0;
			$currentTeams[$tID] = array($tName, $tPunti, $tPartite, $tFatti, $tSubiti, $tDiff, $tVittorie, $tPareggi, $tSconfitte);
			update_option('clsa_teams', $currentTeams);
		}
		?>
		<script type="text/javascript">
			window.location='?page=classifica';
		</script><noscript><meta http-equiv="refresh" content="0; URL=?page=classifica" /></noscript>
		<?php
	}
	?>
	   </div>
	<?php
}

function clsa_output() {
	global $currentTeams;
		$str = '<div id="clsa_div"><table><thead><tr><td width="5%">Pos</td><td width="75%">Squadra</td><td width="10%">Partite</td><td width="10%">Punti</td></tr></thead><tr><td></td></tr><tbody>';
		mysql_query("CREATE TEMPORARY TABLE IF NOT EXISTS tmp_clsa (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, squadra TEXT NOT NULL, punti INT NOT NULL, partite INT NOT NULL, fatti INT NOT NULL, subiti INT NOT NULL, diff_reti INT NOT NULL, vittorie INT NOT NULL, pareggi INT NOT NULL, sconfitte INT NOT NULL");
		foreach($currentTeams as $ctQuery) {
			mysql_query("INSERT INTO tmp_clsa (squadra, punti, partite, fatti, subiti, diff_reti, vittorie, pareggi, sconfitte) VALUES ('$ctQuery[0]','$ctQuery[1]','$ctQuery[2]','$ctQuery[3]','$ctQuery[4]','$ctQuery[5]','$ctQuery[6]','$ctQuery[7]','$ctQuery[8]')");
		}
		$sC = mysql_query("SELECT * FROM classifica ORDER BY punti DESC, squadra ASC");
		$countT = 0;
		while($tC = mysql_fetch_assoc($sC)) {
			$countT++;
			if($countT == 1) {
				$str .= '<tr style="font-weight: bold;">';
			} else {
				$str .= '<tr>';
			}
			$str .= '<td width="5%">'.$countT.'.</td><td width="75%">'.$tC["squadra"].'</td><td width="10%" style="text-align: center;">'.$tC["partite"].'</td><td width="10%" style="text-align: center;">'.$tC["punti"].'</td></tr>';
		}
		$str .= '</tbody></table></div>';
		echo $str;
}
?>