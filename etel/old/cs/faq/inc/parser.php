<?php
/**
* $Id: parser.php,v 1.8.2.3.2.1 2006/03/08 21:09:24 thorstenr Exp $
*
* phpmyfaqTemplate
*
* The phpmyfaqTemplate class provides methods and functions for the
* template parser
*
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @author       Eden Akhavi <eden.akhavi@ltt.com>
* @package      phpmyfaqTemplate
* @since        2002-08-22
*
* Copyright:    (c) 2002-2006 phpMyFAQ Team
*
* The contents of this file are subject to the Mozilla Public License
* Version 1.1 (the "License"); you may not use this file except in
* compliance with the License. You may obtain a copy of the License at
* http://www.mozilla.org/MPL/
*
* Software distributed under the License is distributed on an "AS IS"
* basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
* License for the specific language governing rights and limitations
* under the License.
*/

class phpmyfaqTemplate
{
    /**
     * The template array
     *
     * @var   mixed
     * @see   __construct(), processTemplate()
     */
    var $templates = array();
    
    /**
     * The output array
     *
     * @var   mixed
     * @see   includeTemplate(), processTemplate(), printTemplate(), addTemplate()
     */
	var $outputs = array();
	
    /**
    * Constructor
    *
    * Combine all template files into the main templates array
    *
    * @param    array
    * @access   public
    */
	function phpmyfaqTemplate($myTemplate)
    {
        return $this->__construct($myTemplate);
	}
    function __construct($myTemplate)
    {
		foreach ($myTemplate as $templateName => $filename) {
            $this->templates[$templateName] = $this->readTemplate($filename);
        }
    }
	
    /**
    * This function merges two templates
    *
    * @param    string
    * @param    string
    * @access   public
    */
    function includeTemplate($name, $toname)
    {
        $this->outputs[$toname] = str_replace('{'.$name.'}', $this->outputs[$name], $this->outputs[$toname]);
		$this->outputs[$name] = '';
	}
	
    /**
    *
    * @param    string
    * @param    array
    * @access   public
    */
	function processTemplate($templateName, $myTemplate)
    {
		global $PMF_CONF;
        
        $tmp = $this->templates[$templateName];

        // Security measure: avoid the injection of php/shell-code        	
        $search  = array('#<\?php#i', '#\{$\{#', '#<\?#', '#<\%#', '#`#', '#<script[^>]+php#mi');
        $phppattern1 = "&lt;?php";
        $phppattern2 = "&lt;?";
        if (isset($PMF_CONF['parse_php']) && $PMF_CONF['parse_php'] == true) {
            $phppattern1 = "<?php";
            $phppattern2 = "<?";
        }
        $replace = array($phppattern1, '', $phppattern2, '', '' );

        // Hack: Backtick Fix
        $myTemplate = str_replace('`', '&acute;', $myTemplate);
        
        foreach ($myTemplate as $var => $val) {
            $val = preg_replace($search, $replace, $val);
            $tmp = str_replace('{'.$var.'}', $val, $tmp);
        }
                  
        if (isset($PMF_CONF['parse_php']) && $PMF_CONF['parse_php'] == 'TRUE') {
            
        	$phpstart = '<?php';
        	$phpstop = '?>';
            
            while ($strstart = strpos($tmp, $phpstart)) {
                $substr = substr($tmp, $strstart + strlen($phpstart));
            	$strstop = strpos($substr, $phpstop);
            	$phpcode = substr($substr,0,$strstop);
                
                ob_start();
                eval($phpcode);
                $phpcodecontent = ob_get_contents();
                ob_end_clean();
                
            	$output = substr($tmp, 0, $strstart).$phpcodecontent.substr($tmp, $strstart + strlen($phpstart) + $strstop + strlen($strstop));
        	    $tmp = $output;
            }
        }
        // Hack: Backtick Fix
        $tmp = str_replace('&acute;', '`', $tmp);
        
		if (isset($this->outputs[$templateName])) {
			$this->outputs[$templateName] .= $tmp;
        } else {
			$this->outputs[$templateName] = $tmp;
        }
    }
	
    /**
    * This function prints the whole parsed template file.
    *
    * @access   public
    */
	function printTemplate()
    {
		foreach ($this->outputs as $val) {
            print str_replace("\n\n", "\n", $val);
            }
	}
    
    /**
    * This function adds two template outputs.
    *
    * @param    array
    * @param    array
    * @access   public
    */
	function addTemplate($name, $toname)
    {
		$this->outputs[$toname] .= $this->outputs[$name];
		$this->outputs[$name] = '';
	}
	
    /**
    * This function reads a template file.
    *
    * @param    string;
    * @return   array
    * @access   private
    */
	function readTemplate($filename)
    {
		if (file_exists($filename)) {
			$res = implode("\n", file($filename));
			return $res;
        } else {
            die('<p><span style="color: red;">Error:</span> Cannot open the file '.$filename.'.</p>');
        }
	}
    
    /**
    * Destructor
    *
    * @access   private
    */
    function __destruct()
    {
    }
}