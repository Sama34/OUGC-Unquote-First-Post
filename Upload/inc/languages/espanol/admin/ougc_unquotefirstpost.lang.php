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
$l['ougc_unquotefirstpost_desc'] = 'Desabilita la opcion de citar los temas, mensajes, o ambos.';

// PluginLibrary
$l['ougc_unquotefirstpost_pluginlibrary_error'] = 'Este plugin require <a href="{1}">PluginLibrary</a> version {2} o mas. Por favor sube los arhivos necesarios.';

// Settings
$l['setting_group_ougc_unquotefirstpost'] = $l['ougc_unquotefirstpost'];
$l['setting_group_ougc_unquotefirstpost_desc'] = $l['ougc_unquotefirstpost_desc'];
$l['setting_ougc_ougc_unquotefirstpost_type'] = 'Tipo de Ocultamiento';
$l['setting_ougc_ougc_unquotefirstpost_type_desc'] = 'Puedes desabilitar la opcion de citar el primer mensaje (temas), las respuestas (todos los mensajes excepto el primero), o todos los mensajes. El predeterminado es "Primer Mensaje".';
$l['setting_ougc_ougc_unquotefirstpost_type_threads'] = 'Primer mensaje';
$l['setting_ougc_ougc_unquotefirstpost_type_replies'] = 'Respuestas';
$l['setting_ougc_ougc_unquotefirstpost_type_all'] = 'Todos los mensajes';
$l['setting_ougc_ougc_unquotefirstpost_groups'] = 'Aplicar a Grupos';
$l['setting_ougc_ougc_unquotefirstpost_groups_desc'] = 'Puedes desabilitar la opcion de citar a ciertos grupos.';
$l['setting_ougc_ougc_unquotefirstpost_forums'] = 'Aplicar a Foros';
$l['setting_ougc_ougc_unquotefirstpost_forums_desc'] = 'Puedes desabilitar la opcion de citar a ciertos foros.';
