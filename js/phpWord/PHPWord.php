<?php
/**
 * PHPWord
 *
 * Copyright (c) 2011 PHPWord
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPWord
 * @package    PHPWord
 * @copyright  Copyright (c) 010 PHPWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    Beta 0.6.3, 08.07.2011
 */

/**
 * PHPWORD_BASE_PATH
 */
if (! defined ( 'PHPWORD_BASE_PATH' )) {
	define ( 'PHPWORD_BASE_PATH', dirname ( __FILE__ ) . '/' );
	require PHPWORD_BASE_PATH . 'PHPWord/Autoloader.php';
	PHPWord_Autoloader::Register ();
}

/**
 * PHPWord
 *
 * @category PHPWord
 * @package PHPWord
 * @copyright Copyright (c) 2011 PHPWord
 */
class PHPWord {
	
	var $docFile = "";
	var $title = "";
	var $htmlHead = "";
	var $htmlBody = "";
	/**
	 * Document properties
	 *
	 * @var PHPWord_DocumentProperties
	 */
	private $_properties;
	
	/**
	 * Default Font Name
	 *
	 * @var string
	 */
	private $_defaultFontName;
	
	/**
	 * Default Font Size
	 *
	 * @var int
	 */
	private $_defaultFontSize;
	
	/**
	 * Collection of section elements
	 *
	 * @var array
	 */
	private $_sectionCollection = array ();
	
	/**
	 * Create a new PHPWord Document
	 */
	public function __construct() {
		$this->_properties = new PHPWord_DocumentProperties ();
		$this->_defaultFontName = 'Arial';
		$this->_defaultFontSize = 20;
	}
	
	/**
	 * Get properties
	 * 
	 * @return PHPWord_DocumentProperties
	 */
	public function getProperties() {
		return $this->_properties;
	}
	
	/**
	 * Set properties
	 *
	 * @param PHPWord_DocumentProperties $value        	
	 * @return PHPWord
	 */
	public function setProperties(PHPWord_DocumentProperties $value) {
		$this->_properties = $value;
		return $this;
	}
	
	/**
	 * Create a new Section
	 *
	 * @param PHPWord_Section_Settings $settings        	
	 * @return PHPWord_Section
	 */
	public function createSection($settings = null) {
		$sectionCount = $this->_countSections () + 1;
		
		$section = new PHPWord_Section ( $sectionCount, $settings );
		$this->_sectionCollection [] = $section;
		return $section;
	}
	
	/**
	 * Get default Font name
	 * 
	 * @return string
	 */
	public function getDefaultFontName() {
		return $this->_defaultFontName;
	}
	
	/**
	 * Set default Font name
	 * 
	 * @param string $pValue        	
	 */
	public function setDefaultFontName($pValue) {
		$this->_defaultFontName = $pValue;
	}
	
	/**
	 * Get default Font size
	 * 
	 * @return string
	 */
	public function getDefaultFontSize() {
		return $this->_defaultFontSize;
	}
	
	/**
	 * Set default Font size
	 * 
	 * @param int $pValue        	
	 */
	public function setDefaultFontSize($pValue) {
		$pValue = $pValue * 2;
		$this->_defaultFontSize = $pValue;
	}
	
	/**
	 * Adds a paragraph style definition to styles.xml
	 *
	 * @param $styleName string        	
	 * @param $styles array        	
	 */
	public function addParagraphStyle($styleName, $styles) {
		PHPWord_Style::addParagraphStyle ( $styleName, $styles );
	}
	
	/**
	 * Adds a font style definition to styles.xml
	 *
	 * @param $styleName string        	
	 * @param $styles array        	
	 */
	public function addFontStyle($styleName, $styleFont, $styleParagraph = null) {
		PHPWord_Style::addFontStyle ( $styleName, $styleFont, $styleParagraph );
	}
	
	/**
	 * Adds a table style definition to styles.xml
	 *
	 * @param $styleName string        	
	 * @param $styles array        	
	 */
	public function addTableStyle($styleName, $styleTable, $styleFirstRow = null) {
		PHPWord_Style::addTableStyle ( $styleName, $styleTable, $styleFirstRow );
	}
	
	/**
	 * Adds a heading style definition to styles.xml
	 *
	 * @param $titleCount int        	
	 * @param $styles array        	
	 */
	public function addTitleStyle($titleCount, $styleFont, $styleParagraph = null) {
		PHPWord_Style::addTitleStyle ( $titleCount, $styleFont, $styleParagraph );
	}
	
	/**
	 * Adds a hyperlink style to styles.xml
	 *
	 * @param $styleName string        	
	 * @param $styles array        	
	 */
	public function addLinkStyle($styleName, $styles) {
		PHPWord_Style::addLinkStyle ( $styleName, $styles );
	}
	
	/**
	 * Get sections
	 * 
	 * @return PHPWord_Section[]
	 */
	public function getSections() {
		return $this->_sectionCollection;
	}
	
	/**
	 * Get section count
	 * 
	 * @return int
	 */
	private function _countSections() {
		return count ( $this->_sectionCollection );
	}
	
	/**
	 * Load a Template File
	 *
	 * @param string $strFilename        	
	 * @return PHPWord_Template
	 */
	public function loadTemplate($strFilename) {
		if (file_exists ( $strFilename )) {
			$template = new PHPWord_Template ( $strFilename );
			return $template;
		} else {
			trigger_error ( 'Template file ' . $strFilename . ' not found.', E_ERROR );
		}
	}
	
	/**
	 * Constructor
	 *
	 * @return void
	 */
	function HTML_TO_DOC() {
		$this->title = "Untitled Document";
		$this->htmlHead = "";
		$this->htmlBody = "";
	}
	
	/**
	 * Set the document file name
	 *
	 * @param String $docfile        	
	 */
	function setDocFileName($docfile) {
		$this->docFile = $docfile;
		if (! preg_match ( "/\.doc$/i", $this->docFile ))
			$this->docFile .= ".doc";
		return;
	}
	function setTitle($title) {
		$this->title = $title;
	}
	
	/**
	 * Return header of MS Doc
	 *
	 * @return String
	 */
	function getHeader() {
		
		$return = <<<EOH
			 <html xmlns:v="urn:schemas-microsoft-com:vml"
			xmlns:o="urn:schemas-microsoft-com:office:office"
			xmlns:w="urn:schemas-microsoft-com:office:word"
			xmlns="http://www.w3.org/TR/REC-html40">
		
			<head>
			<meta http-equiv=Content-Type content="text/html; charset=utf-8">
			<meta name=ProgId content=Word.Document>
			<meta name=Generator content="Microsoft Word 9">
			<meta name=Originator content="Microsoft Word 9">
			<!--[if !mso]>
			<style>
			v\:* {behavior:url(#default#VML);}
			o\:* {behavior:url(#default#VML);}
			w\:* {behavior:url(#default#VML);}
			.shape {behavior:url(#default#VML);}
			</style>
			<![endif]-->
			<title>$this->title</title>
			<!--[if gte mso 9]><xml>
			 <w:WordDocument>
			  <w:View>Print</w:View>
			  <w:DoNotHyphenateCaps/>
			  <w:PunctuationKerning/>
			  <w:DrawingGridHorizontalSpacing>9.35 pt</w:DrawingGridHorizontalSpacing>
			  <w:DrawingGridVerticalSpacing>9.35 pt</w:DrawingGridVerticalSpacing>
			 </w:WordDocument>
			</xml><![endif]-->
			<style>
			<!--
			 /* Font Definitions */
			@font-face
				{font-family:Verdana;
				panose-1:2 11 6 4 3 5 4 4 2 4;
				mso-font-charset:0;
				mso-generic-font-family:swiss;
				mso-font-pitch:variable;
				mso-font-signature:536871559 0 0 0 415 0;}
			 /* Style Definitions */
			p.MsoNormal, li.MsoNormal, div.MsoNormal
				{mso-style-parent:"";
				margin:0in;
				margin-bottom:.0001pt;
				mso-pagination:widow-orphan;
				font-size:7.5pt;
			        mso-bidi-font-size:8.0pt;
				font-family:"Verdana";
				mso-fareast-font-family:"Verdana";}
			p.small
				{mso-style-parent:"";
				margin:0in;
				margin-bottom:.0001pt;
				mso-pagination:widow-orphan;
				font-size:1.0pt;
			        mso-bidi-font-size:1.0pt;
				font-family:"Verdana";
				mso-fareast-font-family:"Verdana";}
			@page Section1
				{size:8.5in 11.0in;
				margin:1.0in 1.25in 1.0in 1.25in;
				mso-header-margin:.5in;
				mso-footer-margin:.5in;
				mso-paper-source:0;}
			div.Section1
				{page:Section1;}
			-->
			</style>
			<!--[if gte mso 9]><xml>
			 <o:shapedefaults v:ext="edit" spidmax="1032">
			  <o:colormenu v:ext="edit" strokecolor="none"/>
			 </o:shapedefaults></xml><![endif]--><!--[if gte mso 9]><xml>
			 <o:shapelayout v:ext="edit">
			  <o:idmap v:ext="edit" data="1"/>
			 </o:shapelayout></xml><![endif]-->
			 $this->htmlHead
			</head>
			<body>
		
EOH;
		return $return;
	}
	
	/**
	 * Return Document footer
	 *
	 * @return String
	 */
	function getFotter() {
		return "</body></html>";
	}
	
	/**
	 * Create The MS Word Document from given HTML
	 *
	 * @param String $html
	 *        	:: URL Name like http://www.example.com
	 * @param String $file
	 *        	:: Document File Name
	 * @param Boolean $download
	 *        	:: Wheather to download the file or save the file
	 * @return boolean
	 */
	function createDocFromURL($url, $file, $download = false) {
		if (! preg_match ( "/^http:/", $url ))
			$url = "http://" . $url;
		$html = @file_get_contents ( $url );
		return $this->createDoc ( $html, $file, $download );
	}
	
	/**
	 * Create The MS Word Document from given HTML
	 *
	 * @param String $html
	 *        	:: HTML Content or HTML File Name like path/to/html/file.html
	 * @param String $file
	 *        	:: Document File Name
	 * @param Boolean $download
	 *        	:: Wheather to download the file or save the file
	 * @return boolean
	 */
	function createDoc($html, $file, $download = false) {
		if (is_file ( $html ))
			$html = @file_get_contents ( $html );
		
		$this->_parseHtml ( $html );
		$this->setDocFileName ( $file );
		$doc = $this->getHeader ();
		$doc .= $this->htmlBody;
		$doc .= $this->getFotter ();
		$doc . $this->write_file ( $this->docFile, D );
		return $doc;
	}
	
	/**
	 * Parse the html and remove <head></head> part if present into html
	 *
	 * @param String $html        	
	 * @return void
	 * @access Private
	 */
	function _parseHtml($html) {
		$html = preg_replace ( "/<!DOCTYPE((.|\n)*?)>/ims", "", $html );
		$html = preg_replace ( "/<script((.|\n)*?)>((.|\n)*?)<\/script>/ims", "", $html );
		preg_match ( "/<head>((.|\n)*?)<\/head>/ims", $html, $matches );
		$head = $matches [1];
		preg_match ( "/<title>((.|\n)*?)<\/title>/ims", $head, $matches );
		$this->title = $matches [1];
		$html = preg_replace ( "/<head>((.|\n)*?)<\/head>/ims", "", $html );
		$head = preg_replace ( "/<title>((.|\n)*?)<\/title>/ims", "", $head );
		$head = preg_replace ( "/<\/?head>/ims", "", $head );
		$html = preg_replace ( "/<\/?body((.|\n)*?)>/ims", "", $html );
		$this->htmlHead = $head;
		$this->htmlBody = $html;
		return;
	}
	
	/**
	 * Write the content int file
	 *
	 * @param String $file
	 *        	:: File name to be save
	 * @param String $content
	 *        	:: Content to be write
	 * @param
	 *        	[Optional] String $mode :: Write Mode
	 * @return void
	 * @access boolean True on success else false
	 */
	function write_file($file, $content, $mode = "w") {
		$fp = @fopen ( $file, $mode );
		if (! is_resource ( $fp ))
			return false;
		fwrite ( $fp, $content );
		fclose ( $fp );
		return true;
	}
}
?>