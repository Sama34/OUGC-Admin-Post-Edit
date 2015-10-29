<?php

/***************************************************************************
 *
 *	OUGC Admin Post Edit plugin (/inc/plugins/ougc_adminpostedit.php)
 *	Author: Omar Gonzalez
 *	Copyright: © 2015 Omar Gonzalez
 *
 *	Website: http://omarg.me
 *
 *	Allows administrators to edit additional post data.
 *
 ***************************************************************************

****************************************************************************
	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
****************************************************************************/

// Die if IN_MYBB is not defined, for security reasons.
defined('IN_MYBB') or die('Direct initialization of this file is not allowed.');

// PLUGINLIBRARY
defined('PLUGINLIBRARY') or define('PLUGINLIBRARY', MYBB_ROOT.'inc/plugins/pluginlibrary.php');

// Plugin API
function ougc_adminpostedit_info()
{
	global $adminpostedit;

	return $adminpostedit->_info();
}

// _activate() routine
function ougc_adminpostedit_activate()
{
	global $adminpostedit;

	return $adminpostedit->_activate();
}

// _deactivate() routine
function ougc_adminpostedit_deactivate()
{
	global $adminpostedit;

	return $adminpostedit->_deactivate();
}

// _install() routine
function ougc_adminpostedit_install()
{
}

// _is_installed() routine
function ougc_adminpostedit_is_installed()
{
	global $adminpostedit;

	return $adminpostedit->_is_installed();
}

// _uninstall() routine
function ougc_adminpostedit_uninstall()
{
}

// Plugin class
class ougc_adminpostedit
{
	function __construct()
	{
		global $plugins;

		// Tell MyBB when to run the hook
		if(defined('IN_ADMINCP'))
		{
			$plugins->add_hook('admin_style_templates_set', array($this, 'load_language'));
		}
		else
		{
			$plugins->add_hook('editpost_end', array($this, 'hook_editpost_end'));
			$plugins->add_hook('datahandler_post_update', array($this, 'hook_editpost_end'));
			$plugins->add_hook('editpost_do_editpost_start', array($this, 'hook_editpost_do_editpost_start'));
		}
	}

	// Plugin API:_info() routine
	function _info()
	{
		global $lang;

		$this->load_language();

		return array(
			'name'					=> 'OUGC Admin Post Edit',
			'description'			=> $lang->setting_group_ougc_adminpostedit_desc,
			'website'				=> 'http://omarg.me',
			'author'				=> 'Omar G.',
			'authorsite'			=> 'http://omarg.me',
			'version'				=> '1.0',
			'versioncode'			=> 1000,
			'compatibility'			=> '18*',
			'codename'				=> 'ougc_adminpostedit',
			'pl'			=> array(
				'version'	=> 12,
				'url'		=> 'http://mods.mybb.com/view/pluginlibrary'
			)
		);
	}

	// Plugin API:_activate() routine
	function _activate()
	{
		global $PL, $lang, $mybb;
		$this->load_pluginlibrary();

		$PL->templates('ougcadminpostedit', '<lang:setting_group_ougc_adminpostedit>', array(
			''	=> '<tr>
<td class="tcat" colspan="2"><strong>{$lang->ougc_adminpostedit_post}</strong></td>
</tr>
<tr>
<td class="trow2" valign="top"><strong>{$lang->ougc_adminpostedit_post_time}</strong></td>
<td class="trow2">
	<select name="ougc_adminpostedit[day]">
		{$startdateday}
	</select>
	&nbsp;
	<select name="ougc_adminpostedit[month]">
		{$startdatemonth}
	</select>
	&nbsp;
	<input type="text" name="ougc_adminpostedit[year]" value="{$startdateyear}" size="4" maxlength="4" class="textbox" />
	- {$lang->ougc_adminpostedit_post_time} <input type="text" name="ougc_adminpostedit[time]" value="{$starttime_time}" size="10" class="textbox" />
</td>
</tr>
<tr>
<td class="trow2" valign="top"><strong>{$lang->ougc_adminpostedit_post_author}</strong></td>
<td class="trow2">
	<div style="width: 28em;">
		<input type="text" class="textbox" name="ougc_adminpostedit[username]" id="username" style="width: 28em;" value="{$search_username}" />
	

<link rel="stylesheet" href="{$mybb->asset_url}/jscripts/select2/select2.css">
<script type="text/javascript" src="{$mybb->asset_url}/jscripts/select2/select2.min.js?ver=1804"></script>
<script type="text/javascript">
<!--
if(use_xmlhttprequest == "1")
{
	MyBB.select2();
	$("#username").select2({
		placeholder: "{$lang->search_user}",
		minimumInputLength: 3,
		maximumSelectionSize: 3,
		multiple: false,
		ajax: {
			url: "xmlhttp.php?action=get_users",
			dataType: \'json\',
			data: function (term, page) {
				return {
					query: term,
				};
			},
			results: function (data, page) {
				return {results: data};
			}
		},
		initSelection: function(element, callback) {
			var value = $(element).val();
			if (value !== "") {
				callback({
					id: value,
					text: value
				});
			}
		},
       // Allow the user entered text to be selected as well
       createSearchChoice:function(term, data) {
			if ( $(data).filter( function() {
				return this.text.localeCompare(term)===0;
			}).length===0) {
				return {id:term, text:term};
			}
		},
	});

  	$(\'[for=username]\').click(function(){
		$("#username").select2(\'open\');
		return false;
	});
}
// -->
</script>
	</div>
</td>
</tr>
<tr>
<td class="trow2" valign="top"><strong>{$lang->ougc_adminpostedit_post_ip}</strong></td>
<td class="trow2"><input type="text" class="textbox" name="ougc_adminpostedit[ip]" size="40" maxlength="20" value="{$p[\'ipaddress\']}" tabindex="9" /></td>
</tr>
<tr>
<td class="trow2" colspan="2"><span class="smalltext"><label><input type="checkbox" class="checkbox" name="ougc_adminpostedit[silent]" value="1" tabindex="10"{$p[\'silent\']} /> {$lang->ougc_adminpostedit_post_silentedit}</label></span>
</td>
</tr>',
		));

		require_once MYBB_ROOT.'inc/adminfunctions_templates.php';
		find_replace_templatesets('editpost', '#'.preg_quote('{$pollbox}').'#i', '{$pollbox}{$ougc_adminpostedit}');

		// Insert/update version into cache
		$plugins = $mybb->cache->read('ougc_plugins');
		if(!$plugins)
		{
			$plugins = array();
		}

		$this->load_plugin_info();

		if(!isset($plugins['adminpostedit']))
		{
			$plugins['adminpostedit'] = $this->plugin_info['versioncode'];
		}

		/*~*~* RUN UPDATES START *~*~*/

		/*~*~* RUN UPDATES END *~*~*/

		$plugins['adminpostedit'] = $this->plugin_info['versioncode'];
		$mybb->cache->update('ougc_plugins', $plugins);
	}

	// Plugin API:_deactivate() routine
	function _deactivate()
	{
		require_once MYBB_ROOT.'inc/adminfunctions_templates.php';
		find_replace_templatesets('editpost', '#'.preg_quote('{$ougc_adminpostedit}').'#i', '', 0);
	}

	// Plugin API:_is_installed() routine
	function _is_installed()
	{
		global $cache;

		$plugins = $cache->read('ougc_plugins');

		return isset($plugins['adminpostedit']);
	}

	// Plugin API:_uninstall() routine
	function _uninstall()
	{
		global $PL, $cache;
		$this->load_pluginlibrary();

		// Delete settings
		$PL->templates_delete('ougc_adminpostedit');

		// Delete version from cache
		$plugins = (array)$cache->read('ougc_plugins');

		if(isset($plugins['adminpostedit']))
		{
			unset($plugins['adminpostedit']);
		}

		if(!empty($plugins))
		{
			$cache->update('ougc_plugins', $plugins);
		}
		else
		{
			$PL->cache_delete('ougc_plugins');
		}
	}

	// Load language file
	function load_language()
	{
		global $lang;

		isset($lang->setting_group_ougc_adminpostedit) or $lang->load('ougc_adminpostedit', true);
	}

	// Build plugin info
	function load_plugin_info()
	{
		$this->plugin_info = ougc_adminpostedit_info();
	}

	// PluginLibrary requirement check
	function load_pluginlibrary()
	{
		global $lang;
		$this->load_plugin_info();
		$this->load_language();

		if(!file_exists(PLUGINLIBRARY))
		{
			flash_message($lang->sprintf($lang->ougc_adminpostedit_pluginlibrary_required, $this->plugin_info['pl']['ulr'], $this->plugin_info['pl']['version']), 'error');
			admin_redirect('index.php?module=config-plugins');
		}

		global $PL;
		$PL or require_once PLUGINLIBRARY;

		if($PL->version < $this->plugin_info['pl']['version'])
		{
			global $lang;

			flash_message($lang->sprintf($lang->ougc_adminpostedit_pluginlibrary_old, $PL->version, $this->plugin_info['pl']['version'], $this->plugin_info['pl']['ulr']), 'error');
			admin_redirect('index.php?module=config-plugins');
		}
	}

	// Hook: editpost_end/datahandler_post_update
	function hook_editpost_end(&$dh)
	{
		global $fid, $ougc_adminpostedit;

		$ougc_adminpostedit = 'asd';

		if(!is_moderator($fid, 'caneditposts'))
		{
			return;
		}

		global $lang, $mybb, $templates, $pid, $db;

		$this->load_language();

		$post = get_post($pid);

		$p = array(
			'dateline'		=> $post['dateline'],
			'uid'			=> $post['uid'],
			'username'		=> $post['username'],
			'ipaddress'		=> my_inet_ntop($db->unescape_binary($post['ipaddress'])),
			'silent'		=> ''
		);

		if($mybb->request_method == 'post')
		{
			$post_update_data = array();

			$input = $mybb->get_input('ougc_adminpostedit', 2);

			$input['time'] = (string)$input['time'];
			$date = explode(' ', $input['time']);
			$date = explode(':', $date[0]);

			if(stristr($input['time'], 'pm'))
			{
				$date[0] = 12+$date[0];
				if($date[0] >= 24)
				{
					$date[0] = '00';
				}
			}

			$input['month'] = (int)$input['month'];
			$input['day'] = (int)$input['day'];
			$input['year'] = (int)$input['year'];
			$months = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
			if(!in_array($input['month'], $months))
			{
				$input['month'] = '01';
			}

			$date = gmmktime((int)$date[0], (int)$date[1], 0, $input['month'], $input['day'], $input['year']);
			if(!(!checkdate($input['month'], $input['day'], $input['year']) || $date < 0 || $date == false))
			{
				$p['dateline'] = $post_update_data['dateline'] = (int)$date;
			}

			if($p['username'] != $input['username'])
			{
				if($user = get_user_by_username($input['username'], array('fields' => array('username'))))
				{
					$p['uid'] = $post_update_data['uid'] = (int)$user['uid'];
					$p['username'] = $user['username'];
					$post_update_data['username'] = $db->escape_string($p['username']);

					$update = true;
				}
			}

			if($p['ipaddress'] != $input['ipaddress'])
			{
				if(preg_match('#^[0-9]{1,3}\:[0-9]{1,3}\.[0-9]{1,3}\:[0-9]{1,3}$#', $input['ipaddress']))
				{
					$postip = array_map('intval', explode(':', $input['ipaddress']));
					if(!($postip[0] > 255 || $postip[1] > 255 || $postip[2] > 255 || $postip[3] > 255))
					{
						$p['ipaddress'] = implode(':', $postip);
						$post_update_data['ipaddress'] = $db->escape_binary($p['ipaddress']);
					}
				}
			}

			if($input['silent'])
			{
				$p['silent'] = ' checked="checked"';
			}

			if($dh instanceof PostDataHandler)
			{
				$dh->post_update_data = array_merge($dh->post_update_data, $post_update_data);

				if(isset($update))
				{
					$forum = get_forum($dh->data['fid']);
					$thread = get_thread($dh->data['tid']);

					$update_array = array(
						'lastpost' => "'".TIME_NOW."'"
					);

					if($forum['usepostcounts'])
					{
						$update_array['postnum'] = 'postnum+1';

						$db->update_query('users', array('postnum' => 'postnum-1'), "uid='{$dh->data['uid']}'", 1, true);
					}

					$db->update_query('users', $update_array, "uid='{$dh->post_update_data['uid']}'", 1, true);

					if($thread['firstpost'] == $post['pid'])
					{
						$thread_update = array(
							'uid'		=> $dh->post_update_data['uid'],
							'username'	=> $dh->post_update_data['username'],
							'dateline'	=> $dh->post_update_data['dateline']
						);

						$db->update_query('threads', $thread_update, "tid='{$thread['tid']}'");

						if($forum['usethreadcounts'])
						{
							$db->update_query('users', array('threadnum' => 'postnum-1'), "uid='{$dh->data['uid']}'", 1, true);
							$db->update_query('users', array('threadnum' => 'postnum+1'), "uid='{$dh->post_update_data['uid']}'", 1, true);
						}
					}

					update_last_post($thread['tid']);
					update_forum_lastpost($forum['fid']);
				}

				return;
			}
		}

		$search_username = htmlspecialchars_uni(trim($p['username']));

		// Note: dates are in GMT timezone
		$starttime_time = gmdate('g:i a', $p['dateline']);
		$startday = gmdate('j', $p['dateline']);
		$startmonth = gmdate('m', $p['dateline']);
		$startdateyear = gmdate('Y', $p['dateline']);

		// Generate form elements
		$startdateday = '';
		for($day = 1; $day <= 31; ++$day)
		{
			$selected = $startday == $day ? ' selected="selected"' : '';
			$startdateday .= eval($templates->render('modcp_announcements_day'));
		}

		$startmonthsel = array();
		foreach(array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12') as $month)
		{
			$startmonthsel[$month] = '';
		}
		$startmonthsel[$startmonth] = ' selected="selected"';

		$startdatemonth = eval($templates->render('modcp_announcements_month_start'));

		$ougc_adminpostedit = eval($templates->render('ougcadminpostedit'));
	}

	// Hook: editpost_do_editpost_start
	function hook_editpost_do_editpost_start()
	{
		global $mybb;

		$input = $mybb->get_input('ougc_adminpostedit', 2);
		if($input['silent'])
		{
			$mybb->settings['showeditedbyadmin'] = 0;
		}
	}
}

global $adminpostedit;

$adminpostedit = new ougc_adminpostedit;