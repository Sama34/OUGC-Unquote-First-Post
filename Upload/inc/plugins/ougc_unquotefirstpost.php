<?php

/***************************************************************************
 *
 *	OUGC Unquote First Post plugin (/inc/plugins/ougc_unquotefirstpost.php)
 *	Author: Omar Gonzalez
 *	Copyright: © 2012 - 2020 Omar Gonzalez
 *
 *	Website: https://ougc.network
 *
 *	Disable the quote button for threads or all posts.
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

// Run/Add Hooks
if(!defined('IN_ADMINCP'))
{
	$plugins->add_hook('postbit', 'ougc_unquotefirstpost_postbit');
	$plugins->add_hook('newthread_start', 'ougc_unquotefirstpost');
	$plugins->add_hook('newreply_start', 'ougc_unquotefirstpost');
	$plugins->add_hook('xmlhttp_get_multiquoted_intermediate', 'ougc_unquotefirstpost');
}

// Plugin API
function ougc_unquotefirstpost_info()
{
	global $lang;

	isset($lang->ougc_unquotefirstpost) || $lang->load('ougc_unquotefirstpost');

	return array(
		'name'          => 'OUGC Unquote First Post',
		'description'   => $lang->ougc_unquotefirstpost_desc,
		'website'		=> 'https://ougc.network',
		'author'		=> 'Omar G.',
		'authorsite'	=> 'https://ougc.network',
		'version'		=> '1.8.22',
		'versioncode'	=> 1822,
		'compatibility'	=> '18*',
		'codename'		=> 'ougc_unquotefirstpost',
		'pl'			=> array(
			'version'	=> 13,
			'url'		=> 'https://community.mybb.com/mods.php?action=view&pid=573'
		)
	);
}

// _activate() routine
function ougc_unquotefirstpost_activate()
{
	global $cache, $lang, $PL;

	ougc_unquotefirstpost_deactivate();

	// Add our settings
	$PL->settings('ougc_unquotefirstpost', $lang->setting_group_ougc_unquotefirstpost, $lang->setting_group_ougc_unquotefirstpost_desc, array(
		'type'	=> array(
			'title'			=> $lang->setting_ougc_ougc_unquotefirstpost_type,
			'description'	=> $lang->setting_ougc_ougc_unquotefirstpost_type_desc,
			'optionscode'	=> "radio
-1={$lang->setting_ougc_ougc_unquotefirstpost_type_threads}
0={$lang->setting_ougc_ougc_unquotefirstpost_type_replies}
1={$lang->setting_ougc_ougc_unquotefirstpost_type_all} ",
			'value'			=> -1,
		),
		'groups'	=> array(
			'title'			=> $lang->setting_ougc_ougc_unquotefirstpost_groups,
			'description'	=> $lang->setting_ougc_ougc_unquotefirstpost_groups_desc,
			'optionscode'	=> 'groupselect',
			'value'			=> -1,
		),
		'forums'	=> array(
			'title'			=> $lang->setting_ougc_ougc_unquotefirstpost_forums,
			'description'	=> $lang->setting_ougc_ougc_unquotefirstpost_forums_desc,
			'optionscode'	=> 'forumselect',
			'value'			=> -1,
		)
	));

	// Insert version code into cache
	$plugins = $cache->read('ougc_plugins');
	if(!$plugins)
	{
		$plugins = array();
	}

	$info = ougc_unquotefirstpost_info();
	if(isset($plugins['unquotefirstpost']))
	{
	}
	$plugins['unquotefirstpost'] = $info['versioncode'];
	$cache->update('ougc_plugins', $plugins);
}

// _deactivate() routine
function ougc_unquotefirstpost_deactivate()
{
	global $PL, $lang;

	$PL or require_once PLUGINLIBRARY;

	$info = ougc_unquotefirstpost_info();

	if(file_exists(PLUGINLIBRARY) && !empty($PL->version) && $PL->version >= $info['pl']['version'])
	{
		return true;
	}

	flash_message($lang->sprintf($lang->ougc_unquotefirstpost_pluginlibrary_error, $info['pl']['url'], $info['pl']['version']), 'error');
	admin_redirect('index.php?module=config-plugins');
}

// _is_installed() routine
function ougc_unquotefirstpost_is_installed()
{
	global $cache;

	$plugins = (array)$cache->read('ougc_plugins');

	return !empty($plugins['unquotefirstpost']);
}

// _uninstall() routine
function ougc_unquotefirstpost_uninstall()
{
	global $cache, $PL;

	// Delete settings
	$PL->settings_delete('ougc_customrep');

	// Remove version code from cache
	$plugins = (array)$cache->read('ougc_plugins');

	if(isset($plugins['unquotefirstpost']))
	{
		unset($plugins['unquotefirstpost']);
	}

	if($plugins)
	{
		$cache->update('ougc_plugins', $plugins);
	}
	else
	{
		$PL->cache_delete('ougc_plugins');
	}
}

// Remove quote buttons
function ougc_unquotefirstpost_postbit(&$post)
{
	global $thread, $mybb, $plugins, $thread;

	if(!is_member($mybb->settings['ougc_unquotefirstpost_groups']) || !is_member($mybb->settings['ougc_unquotefirstpost_forums'], array('usergroup' => $thread['fid'])))
	{
		$plugins->remove_hook('postbit', 'ougc_unquotefirstpost_postbit');
		return;
	}

	switch((int)$mybb->settings['ougc_unquotefirstpost_type'])
	{
		case 0:

			if($thread['firstpost'] != $post['pid'])
			{
				$post['button_multiquote'] = $post['button_quote'] = '';
			}

			break;
		case 1:

			$post['button_multiquote'] = $post['button_quote'] = '';

			break;
		default:

			if($thread['firstpost'] == $post['pid'])
			{
				$plugins->remove_hook('postbit', 'ougc_unquotefirstpost_postbit');

				$post['button_multiquote'] = $post['button_quote'] = '';
			}

			break;
	}
}

// Do the magic
function ougc_unquotefirstpost()
{
	global $plugins, $mybb, $fid, $thread, $post, $pid, $tid, $forum;

	if(!is_member($mybb->settings['ougc_unquotefirstpost_groups']) || !(int)$mybb->settings['ougc_unquotefirstpost_forums'])
	{
		return;
	}

	switch((int)$mybb->settings['ougc_unquotefirstpost_type'])
	{
		case 0:
			$where = '=t.firstpost';
			break;
		case 1:
			$where = '<0';
			break;
		default:
			$where = '!=t.firstpost';
			break;
	}

	if((int)$mybb->settings['ougc_unquotefirstpost_forums'] != -1)
	{
		$where .= " AND t.fid NOT IN (".implode(",", array_map('intval', explode(',', $mybb->settings['ougc_unquotefirstpost_forums']))).")";
	}

	if($plugins->current_hook == 'newthread_start' && (int)$mybb->input['load_all_quotes'] != 1):
		$match = 'COUNT(*) AS quotes';
	else:
		$match = 'p.subject, p.message, p.pid, p.tid, p.username, p.dateline';
	endif;

	control_object($GLOBALS['db'], '
		function query($string, $hide_errors=0, $write_query=0)
		{
			if(!$write_query && my_strpos($string, \''.$match.'\'))
			{
				$string = str_replace(\'WHERE \', \'WHERE p.pid'.$where.' AND \', $string);
			}
			return parent::query($string, $hide_errors, $write_query);
		}
');
}

// control_object by Zinga Burga from MyBBHacks ( mybbhacks.zingaburga.com ), 1.68
if(!function_exists('control_object')) {
	function control_object(&$obj, $code) {
		static $cnt = 0;
		$newname = '_objcont_'.(++$cnt);
		$objserial = serialize($obj);
		$classname = get_class($obj);
		$checkstr = 'O:'.strlen($classname).':"'.$classname.'":';
		$checkstr_len = strlen($checkstr);
		if(substr($objserial, 0, $checkstr_len) == $checkstr) {
			$vars = array();
			// grab resources/object etc, stripping scope info from keys
			foreach((array)$obj as $k => $v) {
				if($p = strrpos($k, "\0"))
					$k = substr($k, $p+1);
				$vars[$k] = $v;
			}
			if(!empty($vars))
				$code .= '
					function ___setvars(&$a) {
						foreach($a as $k => &$v)
							$this->$k = $v;
					}
				';
			eval('class '.$newname.' extends '.$classname.' {'.$code.'}');
			$obj = unserialize('O:'.strlen($newname).':"'.$newname.'":'.substr($objserial, $checkstr_len));
			if(!empty($vars))
				$obj->___setvars($vars);
		}
		// else not a valid object or PHP serialize has changed
	}
}
