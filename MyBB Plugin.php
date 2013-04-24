<?php
/**
 * MyBB 1.6
 * Copyright © 2006 MyBB Group, All Rights Reserved
 *
 * Website: http://www.mybboard.net
 * License: http://www.mybboard.net/eula.html
 *
 *
 */

if(!defined("IN_MYBB")) {
    die("Non si pu&ograve; utilizzare questo file direttamente.<br /><br />assicurati che IN_MYBB sia definita.");
}

$plugins->add_hook("newthread_start", "give_rights_newthread_display");
$plugins->add_hook("editpost_action_start", "give_rights_editpost_display");
$plugins->add_hook("datahandler_post_insert_thread", "give_rights_newthread");
$plugins->add_hook("editpost_do_editpost_start", "give_rights_editpost");
$plugins->add_hook("postbit", "give_rights_canedit");

function give_rights_info() {
    return array(
        "name"            => "Dai diritti",
        "description"    => "Permetti ad altri utenti di modificare il tuo thread",
        "website"        => "http://www.slimmer.it",
        "version"        => "1.0",
        "author"        => "Kaito",
        "authorsite"    => "http://www.slimmer.it",
        "compatibility"  => "*",
		"guid" 			=> "6b3475140c3da4cc3dc2c0a32fa4a0c5"
    );
}

function give_rights_install() {
	global $db;
	$db->query("ALTER TABLE ".TABLE_PREFIX."threads ADD urights TEXT NOT NULL");
	$template_ur = array(
		"title"		=> "give_rights_temp",
		"template"	=> '<tr>
<td class="trow2" valign="top"><strong>Diritti</strong><br /><span class="smalltext">Scrivi gli UID degli utenti che possono modificare il thread separati da una virgola</span></td>
<td class="trow2" valign="top"><span class="smalltext"><label for="usersrights">Utenti con diritti</label><br /><input class="textbox" size="10" type="text" id="usersrights" name="usersrights" style="width: 200px;" value=\"{\$usersrighted}\" /></span></td>
</tr>',
		"sid"		=> -1
	);
	$db->insert_query("templates", $template_ur);
}

function give_rights_is_installed() {
	global $db;	
	if($db->field_exists("urights", "threads"))	{
		return true;
	}
	return false;
}

function give_rights_activate() {
    global $mybb;
    require_once MYBB_ROOT."/inc/adminfunctions_templates.php";
	find_replace_templatesets("newthread", '#{\$modoptions}#', "{\$modoptions}{\$giverights}");
	find_replace_templatesets("editpost", '#{\$disablesmilies}#', "{\$disablesmilies}{\$giverights}");
	find_replace_templatesets("postbit", '#<div class="post_management_buttons float_right">#', "<div class=\"post_management_buttons float_right\">{\$post['giverights']}");
	find_replace_templatesets("postbit_classic", '#<div class="post_management_buttons float_right">#', "<div class=\"post_management_buttons float_right\"><!-- giverights -->");
	$fp_ep = fopen(MYBB_ROOT.'/editpost.php', 'r');
	$cont_edst = fread($fp_ep, filesize(MYBB_ROOT.'/editpost.php'));
	fclose($fp_ep);
	$arr_cparts = explode("if(\$mybb->user['uid'] != \$post['uid'])", $cont_edst);
	$fp_n = fopen(MYBB_ROOT.'/editpost.php', 'w+');
	fwrite($fp_n, str_replace($arr_cparts[0]."if(\$mybb->user['uid'] != \$post['uid'])".$arr_cparts[1]."if(\$mybb->user['uid'] != \$post['uid'])", $arr_cparts[0]."if(\$mybb->user['uid'] != \$post['uid'])".$arr_cparts[1]."\$query_ur = \$db->simple_select('threads', 'urights', \"tid='\$tid'\"); \$ur = \$db->fetch_field(\$query_ur, 'urights'); if(!preg_match('#,#', \$ur)) { \$pl_urights = array(\$ur); } else { \$pl_urights = explode(',', \$ur); } if(\$mybb->user['uid'] != \$post['uid'] && !in_array(\$mybb->user['uid'], \$pl_urights))", $cont_edst));
	fclose($fp_n);
}

function give_rights_deactivate() {
    global $mybb;
    require_once MYBB_ROOT."/inc/adminfunctions_templates.php";
	find_replace_templatesets("newthread", '#'.preg_quote('{$giverights}').'#', '',0);
	find_replace_templatesets("editpost", '#'.preg_quote('{$giverights}').'#', '',0);
	find_replace_templatesets("postbit", '#'.preg_quote('{$post[\'giverights\']}').'#', '',0);
	find_replace_templatesets("postbit_classic", '#'.preg_quote('<!-- giverights -->').'#', '',0);
	$fp_ep = fopen(MYBB_ROOT.'/editpost.php', 'r');
	$cont_edst = fread($fp_ep, filesize(MYBB_ROOT.'/editpost.php'));
	fclose($fp_ep);
	$fp_n = fopen(MYBB_ROOT.'/editpost.php', 'w+');
	fwrite($fp_n, str_replace("\$query_ur = \$db->simple_select('threads', 'urights', \"tid='\$tid'\"); \$ur = \$db->fetch_field(\$query_ur, 'urights'); if(!preg_match('#,#', \$ur)) { \$pl_urights = array(\$ur); } else { \$pl_urights = explode(',', \$ur); } if(\$mybb->user['uid'] != \$post['uid'] && !in_array(\$mybb->user['uid'], \$pl_urights))", "if(\$mybb->user['uid'] != \$post['uid'])", $cont_edst));
	fclose($fp_n);
}

function give_rights_uninstall() {
    global $db;
	$db->query("ALTER TABLE ".TABLE_PREFIX."threads DROP `urights`");	
	$db->delete_query("templates","title = 'give_rights_temp'");
}

function give_rights_newthread_display() {
    global $mybb, $db, $templates, $give_rights_temp, $giverights;
	eval("\$giverights = \"".$templates->get("give_rights_temp")."\";");
}

function give_rights_editpost_display() {
    global $mybb, $templates, $db, $post, $give_rights_temp, $giverights, $usersrighted;	
	$query = $db->query("SELECT tid, urights, firstpost, uid FROM ".TABLE_PREFIX."threads where tid='".$post['tid']."'");
	while($do = $db->fetch_array($query)) {
		$firstpost = $do['firstpost'];
		$usersrighted = $do['urights'];
		$threadowner = $do['uid'];
	}
	if($firstpost == $post['pid'] && $mybb->user['uid'] == $threadowner) {
		eval("\$giverights = \"".$templates->get("give_rights_temp")."\";");
    }
}

function give_rights_newthread(&$dh) {
    global $mybb, $db;
	if($dh->action == 'thread' && $dh->method == 'insert') {
		$dh->thread_insert_data['urights'] = $db->escape_string($mybb->input['usersrights']);
	}
}

function give_rights_editpost(&$dh) {
	global $mybb, $db, $thread;
	$tid = $thread['tid'];
	$query_ur = $db->simple_select('threads', 'uid', "tid='$tid'");
	$threadowner = $db->fetch_field($query_ur, 'uid');
	if($mybb->user['uid'] == $threadowner) {
		$update_array = array(
			'urights' => $db->escape_string($mybb->input['usersrights'])
		);
		$db->update_query("threads", $update_array, "tid='$tid'");
	}
}

function give_rights_canedit(&$post) {
	global $mybb, $db, $thread, $templates, $theme, $lang;
	$tid = $thread['tid'];
	$pid = $post['pid'];
	$query = $db->query("SELECT urights, firstpost, uid FROM ".TABLE_PREFIX."threads where tid='$tid'");
	while($do = $db->fetch_array($query)) {
		$firstpost = $do['firstpost'];
		$usersrighted = $do['urights'];
		$threadowner = $do['uid'];
	}
	if(!preg_match('#,#', $usersrighted)) $pl_urights = array($usersrighted); else $pl_urights = explode(',', $usersrighted);
	if($firstpost == $post['pid'] && $mybb->user['uid'] != $threadowner && in_array($mybb->user['uid'], $pl_urights)) {
		eval("\$post['giverights'] = \"".$templates->get("postbit_edit")."\";");
	}
}

?>