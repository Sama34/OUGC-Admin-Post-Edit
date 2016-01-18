<?php

/***************************************************************************
 *
 *	OUGC Admin Post Edit plugin (/inc/plugins/ougc_adminpostedit.php)
 *	Author: Omar Gonzalez
 *	Copyright: © 2015 - 2016 Omar Gonzalez
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

// Cache template
if(THIS_SCRIPT == 'editpost.php')
{
	global $templatelist;

	if(!isset($templatelist))
	{
		$templatelist = '';
	}

	$templatelist .= ',ougcadminpostedit';
}

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
	global $adminpostedit;

	return $adminpostedit->_uninstall();
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

		$PL->templates('ougcadminpostedit', 'OUGC Admin Post Edit', array(
			''	=> '<tr>
<td class="tcat" colspan="2"><strong>{$lang->ougc_adminpostedit_post}</strong></td>
</tr>
<tr>
<td class="trow2" valign="top"><strong>{$lang->ougc_adminpostedit_post_time}</strong></td>
<td class="trow2">
	<input type="text" class="textbox" name="ougc_adminpostedit[timestamp]" style="width: 8em;" value="{$timestamp}" size="14" maxlength="10" />
</td>
</tr>
<tr>
<td class="trow2" valign="top"><strong>{$lang->ougc_adminpostedit_post_author}</strong></td>
<td class="trow2">
	<div style="width: 16em;">
		<input type="text" class="textbox" name="ougc_adminpostedit[username]" id="username" style="width: 16em;" value="{$search_username}" size="28" />
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
<td class="trow2"><input type="text" class="textbox" name="ougc_adminpostedit[ipaddress]" style="width: 8em;" value="{$p[\'ipaddress\']}" size="14" maxlength="16" /></td>
</tr>
<tr>
<td class="trow2" colspan="2"><span class="smalltext"><label><input type="checkbox" class="checkbox" name="ougc_adminpostedit[silent]" value="1" {$p[\'silent\']} /> {$lang->ougc_adminpostedit_post_silentedit}</label></span>
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

		$ougc_adminpostedit = '';

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

		$timestamp = (int)$p['dateline'];

		$search_username = htmlspecialchars_uni(trim($p['username']));

		if($mybb->request_method == 'post')
		{
			$input = $mybb->get_input('ougc_adminpostedit', 2);

			$timestamp = (int)$input['timestamp'];

			$search_username = htmlspecialchars_uni(trim($input['username']));

			$post_update_data = array();

			if($p['dateline'] != $input['timestamp'] && TIME_NOW >= $input['timestamp'])
			{
				$p['dateline'] = $post_update_data['dateline'] = (int)$input['timestamp'];
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
				if(preg_match('#^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$#', $input['ipaddress']))
				{
					$ipaddress = array_map('intval', explode('.', $input['ipaddress']));
					if(!($ipaddress[0] > 255 || $ipaddress[1] > 255 || $ipaddress[2] > 255 || $ipaddress[3] > 255))
					{
						$p['ipaddress'] = implode('.', $ipaddress);
						$post_update_data['ipaddress'] = $db->escape_binary(my_inet_pton($p['ipaddress']));
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
					global $plugins;

					$forum = get_forum($dh->data['fid']);
					$thread = get_thread($dh->data['tid']);

					$user = get_user($dh->post_update_data['uid']);

					$update_query = array();
					if($thread['dateline'] > $user['lastpost'])
					{
						$update_query['lastpost'] = "'{$thread['dateline']}'";
					}
					if($forum['usepostcounts'])
					{
						$update_query['postnum'] = 'postnum+1';
					}
					if($forum['usethreadcounts'])
					{
						$update_query['threadnum'] = 'threadnum+1';
					}

					if(!empty($update_query))
					{
						$db->update_query('users', $update_query, "uid='{$user['uid']}'", 1, true);
					}

					$user = get_user($dh->data['uid']);

					$update_query = array();
					if($thread['dateline'] < $user['lastpost'])
					{
						$update_query['lastpost'] = "'{$thread['dateline']}'";
					}
					if($forum['usepostcounts'])
					{
						$update_query['postnum'] = 'postnum-1';
					}
					if($forum['usethreadcounts'])
					{
						$update_query['threadnum'] = 'threadnum-1';
					}

					if(!empty($update_query))
					{
						$db->update_query('users', $update_query, "uid='{$user['uid']}'", 1, true);
					}

					if($thread['firstpost'] == $post['pid'])
					{
						$thread_update = array(
							'uid'		=> $dh->post_update_data['uid'],
							'username'	=> $dh->post_update_data['username'],
							'dateline'	=> $dh->post_update_data['dateline']
						);

						$db->update_query('threads', $thread_update, "tid='{$thread['tid']}'");
					}

					$plugins->add_hook('datahandler_post_update_end', array($this, 'hook_datahandler_post_update_end'));
				}

				return;
			}
		}

		$ougc_adminpostedit = eval($templates->render('ougcadminpostedit'));
	}

	// Hook: editpost_end/datahandler_post_update
	function hook_datahandler_post_update_end(&$dh)
	{
		// pid
		// uid
		// edit_uid
		update_last_post($dh->data['tid']);
		update_forum_lastpost($dh->data['fid']);
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

//_dump(get_thread(9));
//_dump(get_post(9));

global $adminpostedit;

$adminpostedit = new ougc_adminpostedit;