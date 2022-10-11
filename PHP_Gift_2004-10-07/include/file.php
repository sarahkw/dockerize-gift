<?php
// vim: set expandtab tabstop=4 shiftwidth=4 fdm=marker:
// +----------------------------------------------------------------------+
// | file.php                                                             |
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

require_once ('HTTP/Upload.php');

// {{{ createTmpDir
function createTmpDir ($dir) {
    global $uploadDir;
    if (file_exists("$uploadDir/$dir"))
        return false;
    else
        if (mkdir ("$uploadDir/$dir", 0770))
            chmod ("$uploadDir/$dir", 0770);
        else
            return false;
    return true;
}
// }}}
// {{{ createThumbnail
function createThumbnail ($sessionId, $file) {
    global $uploadDir;
    global $convert;
    $ext = array( 1=>'gif', 2=>'jpg', 3=>'png', 4=>'swf', 5=>'psd', 6=>'bmp',
        7=>'tif', 8=>'tif', 9=>'jpc', 10=>'jp2', 11=>'jpx', 12=>'jb2', 13=>'swc',
        14=>'iff', 15=>'wbmp', 16=>'xbm');
    $img = "$uploadDir/$sessionId/$file";
    list($width, $height, $type) = getimagesize($img);
    $thumb = $img.'_thumb.'.$ext[$type];
    $thumbHeight = 75;
    if (file_exists($img))
        system ("$convert -quality 90 -size x$thumbHeight $img -unsharp 1x1.2 -scale x$thumbHeight $thumb");
}
// }}}
?>
