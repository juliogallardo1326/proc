<?php
/**
* $Id: pdf.php,v 1.15.2.10.2.1 2006/05/05 16:58:58 thorstenr Exp $
*
* Main PDF class for phpMyFAQ based on FPDF by Olivier Plathey
*
* @package      phpmyfaq
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @author       Peter Beauvain <pbeauvain@web.de>
* @author       Olivier Plathey <olivier@fpdf.org>
* @author       Krzysztof Kruszynski <thywolf@wolf.homelinux.net>
* @since        2004-11-21
* @license      Mozilla Public License 1.1
* @copyright    Copyright (c) 2004-2006 phpMyFAQ Team
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

define('FPDF_FONTPATH', dirname(dirname(__FILE__)).'/font/');
require_once('fpdf.php');

class PDF extends FPDF
{
    /**
    * <b> and <strong> for bold strings
    *
    * @var      string
    * @access   private
    * @see
    */
    var $B;

    /**
    * <i> and <em> for italic strings
    *
    * @var      string
    * @access   private
    * @see
    */
    var $I;

    /**
    * <u> for underlined strings
    *
    * @var      string
    * @access   private
    * @see
    */
    var $U;

    /**
    * The "src" attribute inside (X)HTML tags
    *
    * @var      string
    * @access   private
    * @see
    */
    var $SRC;

    /**
    * The "href" attribute inside (X)HTML tags
    *
    * @var      string
    * @access   private
    * @see
    */
    var $HREF;

    /**
    * <pre> for code examples
    *
    * @var      string
    * @access   private
    * @see
    */
    var $PRE;

    /**
    * <div align="center"> for centering text
    *
    * @var      string
    * @access   private
    * @see
    */
    var $CENTER;

    /**
    * The border of a table
    *
    * @var      int
    * @access   private
    * @see
    */
    var $tableborder;

    /**
    * The begin of a table
    *
    * @var      int
    * @access   private
    * @see
    */
    var $tdbegin;

    /**
    * The width of a table
    *
    * @var      int
    * @access   private
    * @see
    */
    var $tdwidth;

    /**
    * The heightof a table
    *
    * @var      int
    * @access   private
    * @see
    */
    var $tdheight;

    /**
    * The alignment of a table
    *
    * @var      int
    * @access   private
    * @see
    */
    var $tdalign;

    /**
    * The background color of a table
    *
    * @var      int
    * @access   private
    * @see
    */
    var $tdbgcolor;

    /**
    * With or without bookmarks
    *
    * @var      boolean
    * access    public
    * @see      Bookmartk()
    */
    var $enableBookmarks = false;

    /**
    * Array with titles
    * @var      array
    * @access   private
    * @see
    */
    var $outlines = array();

    /**
    * Outline root
    * @var      string
    * @access   private
    * @see
    */
    var $OutlineRoot;

    /**
    * Supported MIME types
    *
    */
    var $mimetypes = array("2" => "jpg", "3" => "png");

    /**
     * Category ID
     *
     * @var integer
     */
    var $category = null;

    /**
    * Constructor
    *
    * @param    int     The category ID
    * @param    string  The title of the FAQ record
    * @param    array   The array with all category names
    * @param    string  The orientation of the created PDF file
    * @param    string  The unit of the created PDF file
    * @param    string  The format of the created PDF file
    * @return   void
    * @access   private
    */
    function PDF($category = '', $thema = '', $categories = '', $orientation = 'P', $unit = 'mm', $format = 'A4')
    {
        $this->category = $category;
        $this->thema = $thema;
        $this->categories = $categories;
        $this->FPDF($orientation, $unit, $format);
        $this->B = 0;
        $this->I = 0;
        $this->U = 0;
        $this->PRE = 0;
        $this->CENTER = 0;
        $this->SRC = '';
        $this->HREF = '';
        $this->tableborder = 0;
        $this->tdbegin = false;
        $this->tdwidth = 0;
        $this->tdheight = 0;
        $this->tdalign = 'L';
        $this->tdbgcolor = false;
    }

    // PUBLIC

    /**
    * The main (X)HTML parser
    *
    * @param    string
    * @access   public
    * @return   void
    */
	function WriteHTML($html)
    {
        // save (X)HTML and XML code ...
        $htmlSearch = array('&quot;', '&lt;', '&gt;', '&nbsp;', '&amp;', '\n');
        $htmlReplace = array('"', '�', '�', ' ', '&', '<br />');
        $html = str_replace($htmlSearch, $htmlReplace, $html);

        $a = preg_split("/<(.*)>/U", $html, -1, PREG_SPLIT_DELIM_CAPTURE);
        foreach($a as $i => $e) {
            if ($i % 2 == 0) {
                if ($this->HREF) {
                    $this->PutLink($this->HREF,$e);
                } elseif ($this->SRC) {
                    $this->AddImage($this->SRC);
                    $this->SRC = "";
                } elseif ($this->CENTER) {
                    $this->MultiCell(0, 1, $e, 0, "L");
                } elseif ($this->tdbegin) {
                    if (trim($e) != '' && $e != "&nbsp;") {
                        $this->Cell($this->tdwidth, $this->tdheight, $e, $this->tableborder, '', $this->tdalign, $this->tdbgcolor);
                    } elseif ($e == "&nbsp;") {
                        $this->Cell($this->tdwidth, $this->tdheight, '', $this->tableborder, '' ,$this->tdalign, $this->tdbgcolor);
                    }
                } else {
                    $this->Write(5,$e);
                }
            } else {
                if ($e{0} == "/") {
                    $this->CloseTag(strtoupper(substr($e,1)));
                } else {
                    $a2 = explode(" ",$e);
                    $tag = strtoupper(array_shift($a2));
                    $attr = array();
                    foreach ($a2 as $v) {
                        if (ereg('^([^=]*)=["\']?([^"\']*)["\']?$',$v,$a3)) {
                            $attr[strtoupper($a3[1])]=$a3[2];
                        }
                    }
                    $this->OpenTag($tag,$attr);
                }
            }
        }
    }

    /**
    * Combines the PDF bookmarks
    *
    * @param    string
    * @param    int
    * @param    int
    * @return   void
    * @access   public
    */
    function Bookmark($txt, $level = 0, $y = 0)
    {
        if ($y == -1) {
            $y = $this->GetY();
        }
        // Add a bookmark entry once per faq, even if the faq is taking more than 1 page
        if ((0 == count($this->outlines)) || ($this->outlines[count($this->outlines)-1]["t"] != $txt)) {
            $this->outlines[] = array("t" => $txt, "l" => $level, "y" => $y, "p" => $this->PageNo());
        }
    }

    // PRIVATE

    /**
    * The header of the PDF file
    *
    * @return   void
    * @access   private
    */
	function Header()
    {
        $title = $this->categories[$this->category]["name"].": ".$this->thema;
        $currentTextColor = $this->TextColor;
        $this->SetTextColor(0,0,0);
        $this->SetFont("Arial", "I", 18);
        $this->MultiCell(0, 9, $title, 1, 1, "C", 1);
        $this->Ln(8);
        if ($this->enableBookmarks == true) {
            $this->Bookmark(makeShorterText($this->thema, 5));
        }
        $this->TextColor = $currentTextColor;
	}

	/**
    * The footer of the PDF file
    *
    * @return   void
    * @access   private
    */
	function Footer() {
	    global $cat, $PMF_CONF, $PMF_LANG;
        $currentTextColor = $this->TextColor;
        $this->SetTextColor(0,0,0);
	    $this->SetY(-25);
	    $this->SetFont("Arial", "I", 10);
	    $this->Cell(0, 10, $PMF_LANG["ad_gen_page"]." ".$this->PageNo()."/{nb}",0,0,"C");
	    $this->SetY(-20);
	    $this->SetFont("Arial", "B", 8);
	    $this->Cell(0, 10, "(c) ".date("Y")." ".$PMF_CONF["metaPublisher"]." <".$PMF_CONF["adminmail"].">",0,1,"C");
	    if ($this->enableBookmarks == false) {
	        $this->SetY(-15);
	        $this->SetFont("Arial", "", 8);
            $_url = "http".(isset($_SERVER["HTTPS"]) ? "s" : "")."://".$_SERVER["HTTP_HOST"].str_replace("pdf.php", "index.php?action=artikel&cat=".$this->categories[$this->category]["id"]."&id=".$_REQUEST["id"]."&artlang=".$_REQUEST["lang"], $_SERVER["PHP_SELF"]);
	        $this->Cell(0, 10, "URL: ".$_url, 0, 1, "C", 0, $_url);
	    }
        $this->TextColor = $currentTextColor;
	}

    /**
    * Locate the supported tags and set, what to do next
    *
    * @param    string
    * @param    array
    * @return   void
    * @access   private
    */
	function OpenTag($tag, $attr)
    {
        switch ($tag) {
            case "STRONG":
            case "B":       $this->SetStyle('B', true);
                            break;
            case "EM":
            case "I":       $this->SetStyle('I', true);
                            break;
            case "U":       $this->SetStyle('U', true);
                            break;
            case "CODE":
            case "PRE":     $this->SetFont("Courier", "", 10);
                            $this->SetTextColor(0,0,255);
                            break;
            case "A":       if (isset($attr["HREF"])) {
                                $this->HREF = $attr["HREF"];
                            }
                            break;
            case "IMG":     $this->SRC = $attr["SRC"];
                            $this->Ln();
                            break;
    	    case "DIV":     if (isset($attr['ALIGN']) && $attr["ALIGN"] != "justify") {
                                $this->CENTER = $attr["ALIGN"];
                            }
                            break;
            case 'OL':
            case 'UL':      $this->SetLeftMargin($this->lMargin + 10);
                            break;
            case 'LI':      $this->SetX($this->GetX() - 10);
                            $this->Cell(10, 5, chr(149), 0, 0, 'C');
                            break;
            case "P":
            case "BR":      $this->Ln(5);
    			            break;
            case "TABLE":   if (isset($attr['BORDER']) && $attr['BORDER'] != "") {
                                $this->tableborder = $attr['BORDER'];
                            } else {
                                $this->tableborder = 0;
                            }
                            break;
            case "TD":      if (isset($attr['WIDTH']) && $attr['WIDTH'] != "") {
                                $this->tdwidth = ($attr['WIDTH'] / 4);
                            } else {
                                $this->tdwidth = 40;
                            }
                            if (isset($attr['HEIGHT']) && $attr['HEIGHT'] != "") {
                                $this->tdheight = ($attr['HEIGHT'] / 6);
                            } else {
                                $this->tdheight = 6;
                            }
                            if (isset($attr['ALIGN']) && $attr['ALIGN'] != "") {
                                $align = $attr['ALIGN'];
                                if ($align == "LEFT") {
                                    $this->tdalign = "L";
                                }
                                if ($align == "CENTER") {
                                    $this->tdalign = "C";
                                }
                                if ($align == "RIGHT") {
                                    $this->tdalign = "R";
                                }
                            } else {
                                $this->tdalign = "L";
                            }
                            if (isset($attr['BGCOLOR']) && $attr['BGCOLOR'] != "") {
                                $color = $this->hex2dec($attr['BGCOLOR']);
                                $this->SetFillColor($color['R'], $color['G'], $color['B']);
                                $this->tdbgcolor = true;
                            }
                            $this->tdbegin = true;
                            break;
            case "HR":      $this->Ln(2);
                            $this->Line($this->GetX(), $this->GetY(), $this->GetX() + 187, $this->GetY());
                            $this->Ln(3);
                            break;
            case "SUP":     $this->subWrite(true, 3);
                            break;
            case "SUB":     $this->subWrite(true, -3);
                            break;
            default:        break;
        }
    }

    /**
    * Finish what to do with a (X)HTML tag
    *
    * @param    string
    * @return   void
    * @access   private
    */
    function CloseTag($tag)
    {
        switch ($tag) {

            case "B":
            case "I":
            case "U":       $this->SetStyle($tag, false);
                            break;
            case "STRONG":  $this->SetStyle("B", false);
                            break;
            case "EM":      $this->SetStyle("I", false);
                            break;
            case "CODE":
            case "PRE":     $this->SetFont("Arial", "", 12);
                            $this->SetTextColor(0,0,0);
                            break;
            case "A":       $this->HREF = "";
                            break;
            case "DIV":     $this->CENTER = "";
                            break;
            case 'OL':
            case 'UL':      $this->SetLeftMargin($this->lMargin - 10);
                            $this->Ln();
                            break;
            case 'LI':      $this->Ln();
                            break;
            case "TD":      $this->tdbegin = false;
                            $this->tdwidth = 0;
                            $this->tdheight = 0;
                            $this->tdalign = "L";
                            $this->tdbgcolor = false;
                            break;
            case "TR";      $this->Ln();
                            break;
            case "TABLE";   $this->tableborder = 0;
                            break;
            case "P":       $this->Ln(5);
                            break;
            case "SUP":     $this->subWrite(false, 3);
                            break;
            case "SUB":     $this->subWrite(false, -3);
                            break;
            default:        break;
        }
    }

    /**
    * Set the specific style according to the (X)HTML tag
    *
    * @param    string
    * @param    boolean
    * @return   void
    * @access   private
    */
    function SetStyle($tag, $enable)
    {
		$this->$tag += ($enable ? 1 : -1);
		$style = "";
		foreach (array("B", "I", "U") as $s) {
			if ($this->$s > 0) {
				$style .= $s;
            }
        }
		$this->SetFont("", $style);
    }

    /**
    * Sets a link to an URL
    *
    * @param    string  URL
    * @param    string  the title of the link
    * @return   void
    * @access   private
    */
    function PutLink($URL, $txt)
    {
		$this->SetTextColor(0, 0, 255);
		$this->SetStyle("U", true);
		$this->Write(5, $txt, $URL);
		$this->SetStyle("U", false);
		$this->SetTextColor(0);
    }

    /**
    * Adds a image
    *
    * @param    string  path to the image
    * @return   void
    * @access   private
    */
    function AddImage($image)
    {

        // Check, if image is stored locally or not
        if ("http" != substr($image, 0, 4)) {
            // Please note that the image must be accessible by HTTP NOT ONLY by HTTPS
            $image = "http://".$_SERVER["SERVER_NAME"].$image;
        } else {
            $image = $image;
        }
        if (!$info = GetImageSize($image)) {
            return;
        }

        if ($info[0] > 555 ) {
            $w = $info[0] / 144 * 25.4;
            $h = $info[1] / 144 * 25.4;
        } else {
            $w = $info[0] / 72 * 25.4;
            $h = $info[1] / 72 * 25.4;
        }

        // Check for the fpdf image type support
        if (isset($this->mimetypes[$info[2]])) {
            $type = $this->mimetypes[$info[2]];
        } else {
            return;
        }

        $hw_ratio = $h / $w;
        $this->Write(5,' ');

        if ($info[0] > $this->wPt) {
            $info[0] = $this->wPt - $this->lMargin - $this->rMargin;
            if ($w > $this->w) {
                $w = $this->w - $this->lMargin - $this->rMargin;
                $h = $w*$hw_ratio;
            }
        }

        $x = $this->GetX();

        if ($this->GetY() + $h > $this->h) {
            $this->AddPage();
        }

        $y = $this->GetY();
        $this->Image($image, $x, $y, $w, $h, $type);
        $this->Write(5,' ');
        $y = $this->GetY();
        $this->Image($image, $x, $y, $w, $h, $type);

        if ($y + $h > $this->hPt) {
            $this->AddPage();
        } else {
            if ($info[1] > 20 ) {
                $this->SetY($y+$h);
            }
            $this->SetX($x+$w);
        }
    }

    /**
    * Place a string at a superscripted or subscripted position.
    *
    * @param    boolean
    * @param    int         superscripted or subscripted position
    * @return   void
    * @access   private
    */
    function subWrite($replace = false, $offset = 0)
    {
        if ($replace == true) {
            $this->SetFontSize(6);
            $offset = (((-6) / $this->k) * 0.3) + ($offset / $this->k);
            $subX = $this->x;
            $subY = $this->y;
            $this->SetXY($subX, $subY - $offset);
        } elseif ($replace == false) {
            $subX = $this->x;
            $subY = $this->y;
            $this->SetXY($subX, $subY + $offset);
            $this->SetFontSize(12);
        }
    }

    /**
    *
    *
    * @return   void
    * @access   private
    */
    function _putbookmarks()
    {
        $nb = count($this->outlines);
        if ($nb == 0) {
            return;
        }
        $lru = array();
        $level = 0;
        foreach ($this->outlines as $i=>$o) {
            if ($o['l'] > 0) {
                $parent = $lru[$o['l']-1];
                $this->outlines[$i]['parent'] = $parent;
                $this->outlines[$parent]['last'] = $i;
                if ($o['l'] > $level) {
                    $this->outlines[$parent]['first'] = $i;
                }
            } else {
                $this->outlines[$i]['parent'] = $nb;
            }
            if($o['l'] <= $level and $i > 0) {
                //Set prev and next pointers
                $prev = $lru[$o['l']];
                $this->outlines[$prev]['next'] = $i;
                $this->outlines[$i]['prev'] = $prev;
            }
            $lru[$o['l']] = $i;
            $level = $o['l'];
        }

        //Outline items
        $n = $this->n + 1;
        foreach($this->outlines as $i=>$o) {
            $this->_newobj();
            $this->_out('<</Title '.$this->_textstring($o['t']));
            $this->_out('/Parent '.($n+$o['parent']).' 0 R');
            if (isset($o['prev'])) {
                $this->_out('/Prev '.($n+$o['prev']).' 0 R');
            }
            if (isset($o['next'])) {
                $this->_out('/Next '.($n+$o['next']).' 0 R');
            }
            if (isset($o['first'])) {
                $this->_out('/First '.($n+$o['first']).' 0 R');
            }
            if (isset($o['last'])) {
                $this->_out('/Last '.($n+$o['last']).' 0 R');
            }
            $this->_out(sprintf('/Dest [%d 0 R /XYZ 0 %.2f null]', 1 + 2 * $o['p'], $this->h * $this->k));
            $this->_out('/Count 0>>');
            $this->_out('endobj');
        }

        //Outline root
        $this->_newobj();
        $this->OutlineRoot = $this->n;
        $this->_out('<</Type /Outlines /First '.$n.' 0 R');
        $this->_out('/Last '.($n + $lru[0]).' 0 R>>');
        $this->_out('endobj');
    }

    /**
    *
    *
    * @return   void
    * @access   private
    */
    function _putresources()
    {
        parent::_putresources();
        $this->_putbookmarks();
    }

    /**
    *
    *
    * @return   void
    * @access   private
    */
    function _putcatalog()
    {
        parent::_putcatalog();
        if(count($this->outlines) > 0) {
            $this->_out('/Outlines '.$this->OutlineRoot.' 0 R');
            $this->_out('/PageMode /UseOutlines');
        }
    }

    /**
    * Converts hex colors to decimal rgb numbers
    *
    * @param    string
    * @return   array
    * @access   private
    */
    function hex2dec($color = "#000000")
    {
        $R = substr($color, 1, 2);
        $red = hexdec($R);
        $G = substr($color, 3, 2);
        $green = hexdec($G);
        $B = substr($color, 5, 2);
        $blue = hexdec($B);
        $tbl_color = array();
        $tbl_color['R'] = $red;
        $tbl_color['G'] = $green;
        $tbl_color['B'] = $blue;
        return $tbl_color;
    }

}
?>