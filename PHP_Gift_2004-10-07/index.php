<?php
// vim: set expandtab tabstop=4 shiftwidth=4 fdm=marker:
// +----------------------------------------------------------------------+
// | index.php                                                            |
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
	$root = $_SERVER["DOCUMENT_ROOT"];
	require_once("$root/include/global.php");
	require_once("$root/include/header.php");

	$gift = new Gift ($giftServer, $giftPort);

	echo '<form method="post" action="#searchForm" name="search">';
	$gift->displaySearchForm();

	if ($_POST['action'])
		switch ($_POST['action']) {
			case 'random':
				$gift->displayImages($gift->getImageSet());
				$gift->displaySearchButtons();
				break;
			default:
				$gift->displayImages($gift->getImageSet());
				$gift->displaySearchButtons();
				$gift->displayImages($gift->queryImages, 'query');
				$gift->displaySearchButtons();
				break;
		}
	echo '</form>';

	require_once("$root/include/footer.php");
?>
