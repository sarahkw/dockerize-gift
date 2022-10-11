<?php
// vim: set expandtab tabstop=4 shiftwidth=4 fdm=marker:
// +----------------------------------------------------------------------+
// | config.php                                                           |
// |                                                                      |
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

$version    = '0.1';

$giftServer = '127.0.0.1';
$giftPort	= 12789;

/**
 * directory where users may upload images to. Should be relative to site root.
**/
$uploadDir  = 'tmp';

/**
 * Change the layout of the radio buttons for setting the image relevance. You
 * can either enter;
 * - 'pn' (positive neutral negative)
 * - 'np' (negative neutral positive)
 * Anyone with a better variable name or idea to tackle this issue?
 *
**/
$relevanceLayout = 'pn';

/**
 * Absolute path to ImageMagic's convert.
**/
$convert = '/usr/bin/convert';
?>
