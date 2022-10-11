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
	$root = $HTTP_SERVER_VARS["DOCUMENT_ROOT"];
	require_once("$root/include/global.php");
	require_once("$root/include/header.php");
	require_once("$root/include/file.php");

    $debug = 0;
	$gift = new Gift ($giftServer, $giftPort);

	echo '<form method="post" enctype="multipart/form-data" action="#searchForm" name="search">';
	$gift->displaySearchForm();
    echo '<input name="userfile" type="file">';


	if ($_POST['action']) {
        $upload = new HTTP_Upload('nl');
        $file = $upload->getFiles('userfile');
        if (PEAR::isError($file)) {
            die ($file->getMessage());
        }
        if ($file->isValid()) {
            $file->setName('uniq');
            createTmpDir ($gift->sessionId);
            $dest_dir = $uploadDir.'/'.$gift->sessionId;
            $dest_name = $file->moveTo($dest_dir);
            createThumbnail ($gift->sessionId, $dest_name);
            if (PEAR::isError($dest_name)) {
                die ($dest_name->getMessage());
            }
        } elseif ($file->isError()) {
            echo $file->errorMsg() . "\n";
        }
        $uploadedFile = $dest_name;

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
		$gift->displayUploadedImages();
    }
	echo '</form>';

	require_once("$root/include/footer.php");
?>
