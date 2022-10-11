<?php
// vim: set expandtab tabstop=4 shiftwidth=4 fdm=marker:
// +----------------------------------------------------------------------+
// | menu.php                                                             |
// | Authors: Michiel Roos <mrml@monosock.org>                            |
// | copyright (c) 2004 monosock.org                                      |
// +----------------------------------------------------------------------+
// | GNU!                                                                 |
// |                                                                      |
// | This program is free software; you can redistribute it and/or modify |
// | it under the terms of the GNU General Public License as published by |
// | the Free Software Foundation; either version 2 of the License, or    |
// | (at your option) any later version.                                  |
// |                                                                      |
// | This program is distributed in the hope that it will be useful,      |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
// | GNU General Public License for more details.                         |
// |                                                                      |
// | You should have received a copy of the GNU General Public License    |
// | along with this program; if not, write to the Free Software          |
// | Foundation, Inc., 59 Temple Place, Suite 330,                        |
// |    Boston, MA  02111-1307  USA                                       |
// |                                                                      |
// | The licence can also be viewed @ http://www.gnu.org/licenses/gpl.txt |
// |                                                                      |
// +----------------------------------------------------------------------+
// | Have fun! (That's an order :-])                                      |
// +----------------------------------------------------------------------+
//

// {{{ getMenuItems
function getMenuItems ($docRoot) {
	global $galleryConfigDir;
    global $uploadDir;
	$handle = opendir ($docRoot);
	$ignore = array ('.', '..', 'CVS', 'data', 'images', 'include', $uploadDir);
	while ($dir = readdir($handle))
		if (is_dir("$docRoot/$dir"))
			if (!in_array ($dir, $ignore)) {
				$linkName = str_replace('/', '', $dir);
				$linkName = str_replace('_', '&nbsp;', $linkName);
				$menu[$dir] = $linkName;
			}
	closedir($handle);
	return $menu;
}
// }}}
// {{{ displayMenu
function displayMenu ($docRoot, $menu, $currentPage = '') {
	if ($menu) {
		asort ($menu);

		// The $key value only contains the name of the dir,
		// we need to prepend the path from the root up to that dir. 
		$prepend = str_replace ($_SERVER['DOCUMENT_ROOT'], '', $docRoot);
		
		// Submenu's should be indented, subSubmenu's even more.
		//$indent = substr_count ($prepend, '/');
		//for ($i = 0; $i < $indent; $i++)
		//	$indents .= '&nbsp;&nbsp;';

		// One of the dirs is the current dir, hilight it.
                //  TODO: This used to be ereg_replace, check to see if the expression must change also.
		$hilight = preg_replace ('(^/[^/]*).*', '\\1', $currentPage);

		// Pass on part of the $currentPage var to the submenu.
                //  TODO: used to be ereg_replace, check the expression
		$currentSubPage = preg_replace ('^/[^/]*(.*)', '\\1', $currentPage);
		
		foreach ($menu as $key => $value) {
			$subMenu = getMenuItems ("$docRoot/$key");
			if ($hilight == "/$key") {
				$menuCode .= "<li class=\"menuCurrentLocation\"><a href=\"$prepend/$key\">$value</a></li>\n";
				// Show the submenu, if any, for the selected dir.
				$sub = displayMenu ("$docRoot/$key", $subMenu, $currentSubPage);
				if ($sub != '')
					$menuCode .= '<ul>'."\n".$sub.'</ul>'."\n";
			}
			else
				$menuCode .= "<li><a href=\"$prepend/$key\">$value</a></li>\n";
		}
		return $menuCode;
	}
}
// }}}

?>
