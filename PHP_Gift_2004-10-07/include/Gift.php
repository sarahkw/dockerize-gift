<?php
// vim: set expandtab tabstop=4 shiftwidth=4 fdm=marker:
// +----------------------------------------------------------------------+
// | PHP/Gift interface class                                             |
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

#require_once "XML/MRML.php";
#require_once "include/MRML.php";

/**
* GIFT server control class.
*
* This class is a parser for the GNU Image Finding Tool (GIFT). For more
* information on GIFT see the website of the GIFT working group
* (http://www.gnu.ort/software/gift).
*
* @author Michiel Roos <mrml@monosock.org>
* @version $Revision: 1.7 $
* @access  public
*/
class Gift
{
    // {{{ properties

    /**
     * @var string
     */
    var $port;

    /**
     * @var string
     */
    var $requestHeader;

    /**
     * @var string
     */
    var $resultSize;

    /**
     * @var string
     */
    var $server;

    /**
     * @var string
     */
    var $sessionId;

    /**
     * @var array
     */
    var $collections = array();

    /**
     * @var array
     */
    var $algorithms = array();

    /**
     * @var array
     */
    var $queryImages = array();

    // }}}
    // {{{ Constructor

    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    function Gift($server = '127.0.0.1', $port = 12789)
    {
        $this->requestHeader = '<?xml version="1.0" standalone="no" ?>'.
            '<!DOCTYPE mrml SYSTEM "http://www.mrml.net/specification/v1_0/MRML_v10.dtd">';
        $this->server = $server;
        $this->port = $port;
        $this->mrml = new XML_MRML ();
        $this->collections = $this->getCollections();

        // FIXME: Retrieval of the algorithms should happen based on a
        // collection-id. Now it uses the first collection in the
        // $this->collections array. This will do for now.
        $this->algorithms = $this->getAlgorithms();

        if (!$_POST['sessionId'])
            $this->sessionId = $this->getSessionId();
        else
            $this->sessionId = $_POST['sessionId'];
            
        if (!$_POST['result-size'])
            $this->resultSize = 24;
        else
            $this->resultSize = $_POST['result-size'];
    }

    // }}}
    // {{{ debug()

    /**
     * debug
     *
     * @access public
     * @return void
     */
    function debug ($data)
    {
        print ('<textarea rows="25" cols="100" wrap="virtual">'.$data.'</textarea>');
    }

    // }}}
    // {{{ displayAlgorithmList()

    /**
     * display list of available algorithms
     *
     * @param  string Collection id
     * @access public
     * @return array  Available algorithms
     */
    function displayAlgorithmList($collection = '')
    {
        if (!$collection)
            $collection = current(reset($this->collections));
        $algorithms = $this->getAlgorithms($collection);
        echo "algorithm: \n";
        echo '<select name="algorithmId">'."\n";
        foreach ($algorithms as $id => $algorithm) {
            echo '<option value="'.$algorithm['algorithm-id'].'"';
            if ($_POST['algorithmId'] == $algorithm['algorithm-id'])
                echo ' selected="selected"';
            echo '>'.$algorithm['algorithm-name']."</option>\n";
        }
        echo '</select>'."\n";
    }
    // }}}
    // {{{ displayCollectionList()

    /**
     * display list of available collections
     *
     * @param  array  Available collections
     * @access public
     * @return void
     */
    function displayCollectionList()
    {
        echo "collection: \n";
        echo '<select name="collectionId" onchange="this.form.submit()">'."\n";
        foreach ($this->collections as $id => $collection) {
            echo '<option value="'.$collection['collection-id'].'"';
            if ($_POST['collectionId'] == $collection['collection-id'])
                echo ' selected="selected"';
            echo '>'.$collection['collection-name'].
               ' ('.$collection['cui-number-of-images'].
               ' images)</option>'."\n";
        }
        echo '</select>'."\n";
    }
    // }}}
    // {{{ displayImages()

    /**
     * display images
     *
     * @param  array  images
     * @access public
     * @return void
     */
    function displayImages($images, $set = 'result')
    {
        $this->getQueryImages();
        $columns = 6;
        $index   = 0;
        if ($set == 'query')
            echo '<div class="header1"><a name="'.$set.'">'.$set.'</a></div>';
        else
            echo '<a name="'.$set.'">&nbsp;</a>';
        echo '<table class="centeredTable imageTable">';
        foreach ($images as $id => $image) {
            $positive ='';
            $negative ='';
            $neutral  ='';

            $imageLocation = $image['image-location'];

            /*
             * The two lines below are optional. They are there to change the
             * image-location supplied by the gift to point to the right place
             * in my gallery. Change this to another location or just comment
             * it out to use the image-location supplied by the gift.
             *
             * In my case, I want:
             * http://www.monosock.org/gift/images/travels/new_zealand/crd3_P1010087.Xx320.jpg
             * to change to:
             * http://imagery.monosock.org/travels/new_zealand/?img=crd3_P1010088.Xx320.jpg&p=9#img
             */
/* TODO: Blindly commenting this out
            preg ("http://www.monosock.org/gift/images(.*)/([^/]*)$",
               $imageLocation, $regs);
            $imageLocation = 'http://imagery.monosock.org'.$regs[1].
               '/?img='.$regs[2].'#img';
*/
            /*
             * the two lines above
             */
            
            $size = @getImageSize ($image['thumbnail-location']);
            if ($columnCount == 0)
                echo "\n<tr>\n";
            $columnCount++;
            echo "<td>\n".'
    <div class="imageBorder imageBg"><a href="'.$imageLocation.'" target="gift_gallery"><img border="0" src="'.$image['thumbnail-location'].'" '.$size[3].' alt="" title="weight:&nbsp;'.$image['calculated-similarity'].'"/></a></div>';
            if ($set == 'query' or !array_key_exists ($image['image-location'], $this->queryImages)) {
                global $relevanceLayout;
                $rel = $this->queryImages[$image['image-location']]['relevance'];
                if ($rel == '1')
                    $positive = ' checked="checked"';
                elseif ($rel == '-1')
                    $negative = ' checked="checked"';
                else
                    $neutral  = ' checked="checked"';
                if ($relevanceLayout == 'np')
                    echo '
    <div class="relevance">
        <span class="irrelevant">-</span>
        <input name="img_rel_'.$index.'" type="radio" value="-1"'.$negative.'/>
        <input name="img_rel_'.$index.'" type="radio" value="0"'.$neutral.'/>
        <input name="img_rel_'.$index.'" type="radio" value="1"'.$positive.'/>
        <span class="relevant">+</span>';
                else
                    echo '
    <div class="relevance">
        <span class="relevant">+</span>
        <input name="img_rel_'.$index.'" type="radio" value="1"'.$positive.'/>
        <input name="img_rel_'.$index.'" type="radio" value="0"'.$neutral.'/>
        <input name="img_rel_'.$index.'" type="radio" value="-1"'.$negative.'/>
        <span class="irrelevant">-</span>';
                echo '
        <input type="hidden" name="img_loc_'.$index.'" value="'.$image['image-location'].'"/>
        <input type="hidden" name="img_thumb_'.$index.'" value="'.$image['thumbnail-location'].'"/>
        <input type="hidden" name="img_sim_'.$index.'" value="'.$image['calculated-similarity'].'"/>
    </div>';
            }
            echo '</td>';
            if ($columnCount == $columns OR ($index + 1) == $this->resultSize) {
                echo "\n</tr>\n";
                $columnCount = 0;
            }
            $index++;
        }
        echo "</table>\n";
    }
    // }}}
    // {{{ displaySearchForm()

    /**
     * display search form elements
     *
     * @access public
     * @return void
     */
    function displaySearchForm()
    {
        echo '<div class="searchBar"><a name="searchForm">&nbsp;</a>';
        $this->displayCollectionList();
        $this->displayAlgorithmList();
        ?>
            results: 
            <select name="result-size">
            <option value="12" <?php if ($this->resultSize == 12) echo ' selected="selected"'; ?>>12</option>
            <option value="24" <?php if ($this->resultSize == 24) echo ' selected="selected"'; ?>>24</option>
            <option value="60" <?php if ($this->resultSize == 60) echo ' selected="selected"'; ?>>60</option>
            <option value="120" <?php if ($this->resultSize == 120) echo ' selected="selected"'; ?>>120</option>
            </select>
            &nbsp;
            <input type="submit" name="action" value="random"/>
            &nbsp;
            <input type="submit" name="action" value="query"/>
        </div>
        <?php
        echo '<input type="hidden" name="sessionId" value="'.$this->sessionId.'"/>';
    }
    // }}}
    // {{{ displaySearchButtons()

    /**
     * display search buttons
     *
     * This function was implemented to enable the user to submit the form
     * wherever he is on the page. We cannot use the whole search form because
     * the whole page is a form, this means that the collection and algorithm
     * from the last displayed search bar would be posted. This could be solved
     * with javascript, or just by showing the buttons. I dislike javascript.
     *
     * @access public
     * @return void
     */
    function displaySearchButtons()
    {
        ?>
        <div class="buttonBar">
            <input type="submit" name="action" value="refine"/>
        </div>
        <?php
    }
    // }}}
    // {{{ displayUploadedImages
    function displayUploadedImages () {
        global $uploadDir;
        $columns = 6;
        $index   = 0;
        echo '<div class="header1"><a name="upload">uploaded images</a></div>';
        echo '<table class="centeredTable imageTable">';
        $images = $this->getSessionImages();
        if ($images)
        foreach ($images as $id => $image) {
            $positive ='';
            $negative ='';
            $neutral  ='';

            $imageLocation = $image['image-location'];
            
            $size = @getImageSize ($image['thumbnail-location']);
            if ($columnCount == 0)
                echo "\n<tr>\n";
            $columnCount++;
            echo "<td>\n".'
    <div class="imageBorder imageBg"><a href="'.$uploadDir.'/'.$this->sessionId.'/'.$imageLocation.'" target="gift_gallery"><img border="0" src="'.$uploadDir.'/'.$this->sessionId.'/'.$image['thumbnail-location'].'" '.$size[3].' alt="" title="weight:&nbsp;'.$image['calculated-similarity'].'"/></a></div>';
                if ($relevanceLayout == 'np')
                    echo '
    <div class="relevance">
        <span class="irrelevant">-</span>
        <input name="user_img_rel_'.$index.'" type="radio" value="-1"'.$negative.'/>
        <input name="user_img_rel_'.$index.'" type="radio" value="0"'.$neutral.'/>
        <input name="user_img_rel_'.$index.'" type="radio" value="1"'.$positive.'/>
        <span class="relevant">+</span>';
                else
                    echo '
    <div class="relevance">
        <span class="relevant">+</span>
        <input name="user_img_rel_'.$index.'" type="radio" value="1"'.$positive.'/>
        <input name="user_img_rel_'.$index.'" type="radio" value="0"'.$neutral.'/>
        <input name="user_img_rel_'.$index.'" type="radio" value="-1"'.$negative.'/>
        <span class="irrelevant">-</span>';
                echo '
        <input type="hidden" name="user_img_loc_'.$index.'" value="'.$image['image-location'].'"/>
        <input type="hidden" name="user_img_thumb_'.$index.'" value="'.$image['thumbnail-location'].'"/>
        <input type="hidden" name="user_img_sim_'.$index.'" value="'.$image['calculated-similarity'].'"/>
    </div>';
            echo '</td>';
            if ($columnCount == $columns OR ($index + 1) == $this->resultSize) {
                echo "\n</tr>\n";
                $columnCount = 0;
            }
            $index++;
        }
        echo "</table>\n";
    }
    // }}}
    // {{{ getAlgorithms()

    /**
     * Get algorithms
     *
     * This function returns an array containing the set of algorithms
     * that are available on the current gift socket.
     *
     * @param  string Collection id
     * @access public
     * @return array
     */
    function getAlgorithms($collection = '')
    {
        $request = $this->requestHeader.
            '<mrml>'.
            '    <get-algorithms collection-id="'.$collection.'"/>'.
            '</mrml>';
        $answer = $this->request($request);
        $this->mrmlParse($answer);
        return $this->mrml->getAlgorithms();
    }

    // }}}
    // {{{ getCollections()

    /**
     * Get collections
     *
     * This function returns an array containing the set of collections
     * that are available on the current gift socket.
     *
     * @access public
     * @return array
     */
    function getCollections()
    {
        $request = $this->requestHeader.
            '<mrml>'.
            '    <get-collections/>'.
            '</mrml>';
        $answer = $this->request($request);
        $this->mrmlParse($answer);
        return $this->mrml->getCollections();
    }

    // }}}
    // {{{ getImageSet()

    /**
     * Get image set
     *
     * This function returns an array containing a set of images.
     *
     * @access public
     * @return array
     */
    function getImageSet()
    {
        global $uploadedFile;
        global $uploadDir;
        global $debug;
        $request = $this->requestHeader.
            '<mrml session-id="'.$this->sessionId.'">
                <configure-session session-id="'.$this->sessionId.'">
                    <algorithm algorithm-id="'.$_POST['algorithmId'].'"
                        algorithm-type="'.$this->algorithms[$_POST['algorithmId']]['algorithm-type'].'"
                        collection-id="'.$_POST['collectionId'].'" >
                    </algorithm>
                </configure-session>
                <query-step  session-id="'.$this->sessionId.'"
                    result-size="'.$this->resultSize.'"
                    algorithm-id="'.$_POST['algorithmId'].'"
                    collection="'.$_POST['collectionId'].'"';
        if ($_POST['action'] == 'random')
            $request .= "/>\n</mrml>";
        else {
            $request .= ">\n<user-relevance-element-list>\n";
            for ($i = 0; $i < $this->resultSize; $i++)
               if ($_POST['img_rel_'.$i] == -1 or $_POST['img_rel_'.$i] == 1)
                   $request .= '<user-relevance-element image-location="'.$_POST['img_loc_'.$i].'" user-relevance="'.$_POST['img_rel_'.$i].'"/>'."\n";
            if ($uploadedFile)
                $request .= '<user-relevance-element image-location="http://gift.monosock.org/'.$uploadDir.'/'.$this->sessionId.'/'.$uploadedFile.'" user-relevance="1"/>'."\n";
            foreach ($_POST as $postName => $value)
               if (strstr ($postName, 'user_img_rel')) {
                   ereg ('user_img_rel_(.*)', $postName, $regs);
                   $i = $regs[1];
                   $request .= '<user-relevance-element image-location="http://gift.monosock.org/'.$uploadDir.'/'.$this->sessionId.'/'.$_POST['user_img_loc_'.$i].'" user-relevance="'.$_POST['user_img_rel_'.$i].'"/>'."\n";
               }
            $request .= "</user-relevance-element-list>\n</query-step>\n</mrml>";
        }
        if ($debug)
            $this->debug($request);
        $answer = $this->request($request);
        if ($debug)
            $this->debug($answer);
        $this->mrmlParse($answer);
        return $this->mrml->getImages();
    }

    // }}}
    // {{{ getQueryImages()

    /**
     * Get queryImages
     *
     * @access public
     * @return array
     */
    function getQueryImages()
    {
        if (empty($this->queryImages))
            for ($i = 0; $i < $this->resultSize; $i++)
                if ($_POST['img_rel_'.$i] == '-1' or $_POST['img_rel_'.$i] == 1) {
                    $this->queryImages[$_POST['img_loc_'.$i]]['relevance'] =
                        $_POST['img_rel_'.$i];
                    $this->queryImages[$_POST['img_loc_'.$i]]['image-location'] =
                        $_POST['img_loc_'.$i];
                    $this->queryImages[$_POST['img_loc_'.$i]]['thumbnail-location'] =
                        $_POST['img_thumb_'.$i];
                    $this->queryImages[$_POST['img_loc_'.$i]]['calculated-similarity'] =
                        $_POST['img_sim_'.$i];
                }
    }

    // }}}
    // {{{ getRandomImageSet()

    /**
     * Get random image set
     *
     * This function returns an array containing a random set of images.
     *
     * @access public
     * @return array
     */
    function getRandomImageSet()
    {
        $request = $this->requestHeader.
            '<mrml session-id="'.$this->sessionId.'">
                <configure-session session-id="'.$this->sessionId.'">
                    <algorithm algorithm-id="'.$_POST['algorithmId'].'"
                        algorithm-type="'.$this->algorithms[$_POST['algorithmId']]['algorithm-type'].'"
                        collection-id="'.$_POST['collectionId'].'" >
                    </algorithm>
                </configure-session>
                <query-step  session-id="'.$this->sessionId.'"
                    result-size="'.$this->resultSize.'"
                    algorithm-id="'.$_POST['algorithmId'].'"
                    collection="'.$_POST['collectionId'].'"/>
            </mrml>';
        $answer = $this->request($request);
        $this->mrmlParse($answer);
        return $this->mrml->getImages();
    }

    // }}}
    // {{{ getSessionId()

    /**
     * Get sessionId
     *
     * @access public
     * @return string
     */
    function getSessionId()
    {
        $request = $this->requestHeader.
            '<mrml session-id="dummy_session_identifier">
                <open-session user-name="anonymous"
                    session-name="monosock_default_session" />
            </mrml>';
        $answer = $this->request($request);
        $this->mrmlParse($answer);
        return $this->mrml->getSessionId();
    }

    // }}}
    // {{{ getSessionImages
    function  getSessionImages() {
        global $uploadDir;
        $handle = opendir ("$uploadDir/".$this->sessionId);
        while ($file = readdir($handle)) {
            if (is_file($uploadDir.'/'.$this->sessionId.'/'.$file))
                if (ereg('_thumb', $file)) {
                    $images[$i]['image-location'] = ereg_replace ('_thumb.*$', '', $file);
                    $images[$i]['thumbnail-location'] = $file;
                }
            $i++;
        }
        closedir($handle);
        return $images;
    }
    // }}}
    // {{{ mrmlParse()

    /**
     * process MRML data
     *
     * @param  string  XML_MRML request
     * @access public
     * @return void
     */
    function mrmlParse($mrml)
    {
//        $this->mrml->reset();
        $this->mrml->setInput($mrml);
        $this->mrml->parse();
    }
        
    // }}}
    // {{{ request()

    /**
     * send request to GIFT server
     *
     * @access private
     * @param  string XML-MRML request
     * @return string XML-MRML answer
     */
    function request($request) {
        $socket = @fsockopen($this->server, $this->port, $errno, $errstr, 30);
        if (!$socket)
            die("Unable to connect to gift server: $errno - $errstr<br/>\n");
        fputs($socket, $request);
        while (!feof($socket))
            $answer .= fgets($socket, 1024);
        fclose($socket);
        return $answer;
    }

    // }}}
}
?>
