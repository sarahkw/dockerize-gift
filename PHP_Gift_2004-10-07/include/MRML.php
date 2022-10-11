<?php
// vim: set expandtab tabstop=4 shiftwidth=4 fdm=marker:
// +----------------------------------------------------------------------+
// | PHP/MRML interface class                                             |
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

require_once 'XML/Parser/Simple.php';

/**
* MRML parser class.
*
* This class is a parser for Multimedia Retrieval Markup Language 
* (MRML) documents. For more information on MRML see the
* website of the MRML working group (http://www.mrml.net).
*
* @author Michiel Roos <mrml@monosock.org>
* @version $Revision: 1.1.1.1 $
* @access  public
*/
class XML_MRML extends XML_Parser_Simple
{
    // {{{ properties

    /**
     * @var string
     */
    var $sessionId;

    /**
     * @var string
     */
    var $sessionName;

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
    var $images = array();

    // }}}
    // {{{ Constructor

    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    function XML_MRML()
    {
        parent::XML_Parser_Simple();
    }

    // }}}
    // {{{ setInput

    /**
     * setInput
     *
     * @access public
     * @param  handle
     * @return void
     */
    function setInput($handle = '')
    {
        if (@is_file($handle))
            $this->setInputFile($handle);
        elseif (is_string($handle))
            $this->setInputString($handle);
        elseif ($handle == '')
            $this->raiseError('No data passed.');
    }

    // }}}
    // {{{ handleElement()

    /**
     * Start element handler for XML parser
     *
     * @access private
     * @param  string XML element
     * @param  array  Attributes of XML tag
     * @param  string XML character data
     * @return void
     */
    function handleElement($element, $attribs, $data)
    {
        switch ($element) {
            case 'ACKNOWLEDGE-SESSION-OP':
                $this->sessionId   = $attribs['SESSION-ID'];
                $this->sessionName = $attribs['SESSION-NAME'];
                break;
            case 'ALGORITHM':
                // For now, we only want to get the top algorithm level (2).  I
                // have to check how to do recursion and get the
                // query-paradigm-list and property-sheet.
                if ($this->getCurrentDepth() == 2) {
                    $id = $attribs['ALGORITHM-ID'];
                    foreach ($attribs as $name => $value)
                        if (!empty($attribs[$name]))
                            $this->algorithms[$id][strtolower($name)] = $value;
                }
                break;
            case 'COLLECTION':
                $id = $attribs['COLLECTION-ID'];
                foreach ($attribs as $name => $value)
                    if (!empty($attribs[$name]))
                        $this->collections[$id][strtolower($name)] = $value;
                break;
            case 'QUERY-RESULT-ELEMENT':
                $id = $attribs['IMAGE-LOCATION'];
                foreach ($attribs as $name => $value)
                    if (!empty($attribs[$name]))
                        $this->images[$id][strtolower($name)] = $value;
                break;
        }
    }

    // }}}
    // {{{ getAlgorithms()

    /**
     * Get algorithms from MRML file
     *
     * This function returns an array containing the set of algorithms
     * that are provided by the MRML file.
     *
     * @access public
     * @return array
     */
    function getAlgorithms()
    {
        return (array)$this->algorithms;
    }

    // }}}
    // {{{ getCollections()

    /**
     * Get collections from MRML file
     *
     * This function returns an array containing the set of collections
     * that are provided by the MRML file.
     *
     * @access public
     * @return array
     */
    function getCollections()
    {
        return (array)$this->collections;
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
        return $this->sessionId;
    }

    // }}}
    // {{{ getSessionName()

    /**
     * Get sessionName
     *
     * @access public
     * @return string
     */
    function getSessionName()
    {
        return $this->sessionName;
    }

    // }}}
    // {{{ getImages()

    /**
     * Return image set 
     *
     * @access public
     * @return array
     */
    function getImages()
    {
        return (array)$this->images;
    }

    // }}}
}
?>
