<?php

/***************************************************************************
 *
 *	OUGC Unquote First Post plugin (/inc/plugins/ougc_unquotefirstpost.php)
 *	Author: Omar Gonzalez
 *	Copyright: © 2014 Omar Gonzalez
 *
 *	Website: http://omarg.me
 *
 *	Disables the quoting of the first post of any thread.
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
	$plugins->add_hook('xmlhttp', 'ougc_unquotefirstpost');
}

// Plugin API
function ougc_unquotefirstpost_info()
{
	return array(
		'name'			=> 'OUGC Unquote First Post',
		'description'	=> 'Disables the quoting of the first post of any thread.',
		'website'		=> 'http://omarg.me',
		'author'		=> 'Omar G.',
		'authorsite'	=> 'http://omarg.me',
		'version'		=> '0.1',
		'versioncode'	=> '0100',
		'compatibility'	=> '16*',
		'guid' 			=> ''
	);
}

// _activate() routine
function ougc_unquotefirstpost_activate()
{
	global $cache;

	// Insert/update version into cache
	$plugins = $cache->read('ougc_plugins');
	if(!$plugins)
	{
		$plugins = array();
	}

	$info = ougc_unquotefirstpost_info();

	if(!isset($plugins['unquotefirstpost']))
	{
		$plugins['unquotefirstpost'] = $info['versioncode'];
	}

	/*~*~* RUN UPDATES START *~*~*/

	/*~*~* RUN UPDATES END *~*~*/

	$plugins['unquotefirstpost'] = $info['versioncode'];
	$cache->update('ougc_plugins', $plugins);
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
	global $cache;

	// Delete version from cache
	$plugins = (array)$cache->read('ougc_plugins');

	if(isset($plugins['unquotefirstpost']))
	{
		unset($plugins['unquotefirstpost']);
	}

	if(!empty($plugins))
	{
		$cache->update('ougc_plugins', $plugins);
	}
	else
	{
		global $db;

		$db->delete_query('datacache', 'title=\'ougc_plugins\'');
		!is_object($cache->handler) or $cache->handler->delete('ougc_plugins');
	}
}

// Remove quote buttons
function ougc_unquotefirstpost_postbit(&$post)
{
	global $plugins, $thread;

	$plugins->remove_hook('postbit', 'ougc_unquotefirstpost_postbit');

	if($thread['firstpost'] == $post['pid'])
	{
		$post['button_multiquote'] = $post['button_quote'] = '';
	}
}

// Do the magic
function ougc_unquotefirstpost()
{
	global $plugins, $mybb;

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
				$string = str_replace(\'WHERE \', \'WHERE p.pid!=t.firstpost AND \', $string);
			}
			return parent::query($string, $hide_errors, $write_query);
		}
');
}

// control_object by Zinga Burga from MyBBHacks ( mybbhacks.zingaburga.com ), 1.62
if(!function_exists('control_object'))
{
	function control_object(&$obj, $code)
	{
		static $cnt = 0;
		$newname = '_objcont_'.(++$cnt);
		$objserial = serialize($obj);
		$classname = get_class($obj);
		$checkstr = 'O:'.strlen($classname).':"'.$classname.'":';
		$checkstr_len = strlen($checkstr);
		if(substr($objserial, 0, $checkstr_len) == $checkstr)
		{
			$vars = array();
			// grab resources/object etc, stripping scope info from keys
			foreach((array)$obj as $k => $v)
			{
				if($p = strrpos($k, "\0"))
				{
					$k = substr($k, $p+1);
				}
				$vars[$k] = $v;
			}
			if(!empty($vars))
			{
				$code .= '
					function ___setvars(&$a) {
						foreach($a as $k => &$v)
							$this->$k = $v;
					}
				';
			}
			eval('class '.$newname.' extends '.$classname.' {'.$code.'}');
			$obj = unserialize('O:'.strlen($newname).':"'.$newname.'":'.substr($objserial, $checkstr_len));
			if(!empty($vars))
			{
				$obj->___setvars($vars);
			}
		}
		// else not a valid object or PHP serialize has changed
	}
}