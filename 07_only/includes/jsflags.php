<?PHP
  /**********************************************************************************\
  * Project:   GuildRequest2 for EQdkp-Plus                                          *
  * Licence:   Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported *
  * Link:      http://creativecommons.org/licenses/by-nc-sa/3.0/                     *
  *----------------------------------------------------------------------------------*
  * Rewrite:	 15.08.2009                                                            *
  *                                                                                  *
  * Author:    BadTwin                                                               *
  * Copyright: 2009 Andreas (BadTwin) Schrottenbaum                                  *
  * Link:      http://badtwin.dyndns.org                                             *
  * Package: Guildrequest                                                            *
  \**********************************************************************************/

if (!defined('EQDKP_INC')) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

global $bbcode, $tpl, $db, $eqdkp_root_path;

if ($user->data['user_id'] != ANONYMOUS) {
	$poll_query = $db->query("SELECT * FROM __guildrequest_config WHERE config_name = 'gr_poll_activated'");
  $poll = $db->fetch_record($poll_query);

	if ($poll['config_value'] == 'Y'){
		if ($user->check_auth('u_guildrequest_view', false)){
			$request_query = $db->query("SELECT * FROM __guildrequest_users WHERE activated='Y' AND closed='N'");
			while ($request = $db->fetch_record($request_query)) {
				$vote_query = $db->query("SELECT * FROM __guildrequest_poll WHERE query_id = '".$request['id']."' AND user_id ='".$user->data['user_id']."'");
				$vote = $db->fetch_record($vote_query);
				if (!isset($vote['id'])){
					$request_text = '<a href="'.$eqdkp_root_path.'plugins/guildrequest/viewrequest.php?request_id='.$request['id'].'"><b>'.$user->lang['gr_vr_view'].'</b></a>';
					$request_from = '<a href="'.$eqdkp_root_path.'plugins/guildrequest/viewrequest.php?request_id='.$request['id'].'">'.$user->lang['gr_pu_new_query'].'<i>'.$request['username'].'</i></a>';
					System_Message($request_text, $request_from, 'default');
				}
			}
		}
	}

	$db->free_result($poll_query);
	$db->free_result($request_query);
	$db->free_result($votecheck_query); 

	$popup_query = $db->query("SELECT * FROM __guildrequest_config WHERE config_name = 'gr_popup_activated'");
	$popup = $db->fetch_record($popup_query);

	if ($popup['config_value'] == 'Y'){
		if ($user->check_auth('a_guildrequest_manage', false)){
			$opopup_query = $db->query("SELECT * FROM __guildrequest WHERE activated='N'");
			while($opopup = $db->fetch_record($opopup_query)){
				$opopup_text = '<a href="'.$eqdkp_root_path.'plugins/guildrequest/viewrequest.php">'.$user->lang['gr_ad_notactivated_popup'].'</a>';
				$opopup_from = '<a href="'.$eqdkp_root_path.'plugins/guildrequest/viewrequest.php">'.$opopup['username'].'</a>';
				System_Message($opopup_text, $opopup_from, 'red');
			}
		}
	}
}
?>