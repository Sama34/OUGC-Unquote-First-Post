<?php

/***************************************************************************
 *
 *	OUGC Unquote First Post plugin (/inc/languages/english/admin/ougc_unquotefirstpost.lang.php)
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
 
// Plugin API
$l['ougc_unquotefirstpost'] = 'OUGC Unquote First Post';
$l['ougc_unquotefirstpost_desc'] = 'Disable the quote button for threads or all posts.';

// PluginLibrary
$l['ougc_unquotefirstpost_pluginlibrary_error'] = 'This plugin requires <a href="{1}">PluginLibrary</a> version {2} or later to be uploaded to your forum. Please upload the necessary plugin version files.';

// Settings
$l['setting_group_ougc_unquotefirstpost'] = $l['ougc_unquotefirstpost'];
$l['setting_group_ougc_unquotefirstpost_desc'] = $l['ougc_unquotefirstpost_desc'];
$l['setting_ougc_ougc_unquotefirstpost_type'] = 'Hide Type';
$l['setting_ougc_ougc_unquotefirstpost_type_desc'] = 'You can disable the quote feature for fist posts (threads), replies (all posts except fist post), or all posts. Default is "First post".';
$l['setting_ougc_ougc_unquotefirstpost_type_threads'] = 'First Post';
$l['setting_ougc_ougc_unquotefirstpost_type_replies'] = 'Replies';
$l['setting_ougc_ougc_unquotefirstpost_type_all'] = 'All posts';
$l['setting_ougc_ougc_unquotefirstpost_groups'] = 'Apply to Groups';
$l['setting_ougc_ougc_unquotefirstpost_groups_desc'] = 'You can disable this features for specific groups.';
$l['setting_ougc_ougc_unquotefirstpost_forums'] = 'Apply to Forums';
$l['setting_ougc_ougc_unquotefirstpost_forums_desc'] = 'You can disable this features for specific forums.';
