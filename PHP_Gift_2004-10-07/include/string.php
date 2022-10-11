<?php
// vim: set expandtab tabstop=4 shiftwidth=4 fdm=marker:
// +----------------------------------------------------------------------+
// | string.php                                                           |
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

// {{{ humanReadable
function humanReadable ($bytes, $base10=false, $round=0,
    $labels=array('bytes', 'Kb', 'Mb', 'Gb')) {

    if (($bytes <= 0) || (! is_array($labels)) || (count($labels) <= 0))
        return null;

    $step = $base10 ? 3 : 10 ;
    $base = $base10 ? 10 : 2;

    $log = (int)(log10($bytes)/log10($base));

    krsort($labels);

    foreach ($labels as $p=>$lab) {
        $pow = $p * $step;
        if ($log < $pow) continue;
        $size = round($bytes/pow($base,$pow),$round) . $lab;
        break;
    }
    return $size;
}
// }}}
?>
