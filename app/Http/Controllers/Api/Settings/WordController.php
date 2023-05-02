<?php

namespace App\Http\Controllers\Api\Settings;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WordController 
{ 
    var $docFile  = ''; 
    var $title    = ''; 
    var $htmlHead = ''; 
    var $htmlBody = ''; 
     
    /** 
     * Constructor 
     * 
     * @return void 
     */ 
    function __construct(){ 
        $this->title = ''; 
        $this->htmlHead = ''; 
        $this->htmlBody = ''; 
    } 
     
    /** 
     * Set the document file name 
     * 
     * @param String $docfile  
     */ 
    function setDocFileName($docfile){ 
        $this->docFile = $docfile; 
        if(!preg_match("/\.doc$/i",$this->docFile) && !preg_match("/\.docx$/i",$this->docFile)){ 
            $this->docFile .= '.doc'; 
        } 
        return;  
    } 
     
    /** 
     * Set the document title 
     * 
     * @param String $title  
     */ 
    function setTitle($title){ 
        $this->title = $title; 
    } 
     
    /** 
     * Return header of MS Doc 
     * 
     * @return String 
     */ 
    function getHeaderPortrait($orientation){ 
        $return = 
        '<html xmlns:v="urn:schemas-microsoft-com:vml" 
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
        <title>'.$this->title.'</title> 
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
            .rotatedText
            {
                mso-rotate:-90;
            }
        </style>
        <!--[if gte mso 9]><xml> 
         <o:shapedefaults v:ext="edit" spidmax="1032"> 
          <o:colormenu v:ext="edit" strokecolor="none"/> 
         </o:shapedefaults></xml><![endif]--><!--[if gte mso 9]><xml> 
         <o:shapelayout v:ext="edit"> 
          <o:idmap v:ext="edit" data="1"/> 
         </o:shapelayout></xml><![endif]--> '.
         $this->htmlHead.'
        </head> 
        <body>
        <div class=section>';
        return $return; 
    } 
     
    function getHeaderLandscape($orientation){ 
        $return = 
        '<html xmlns:v="urn:schemas-microsoft-com:vml" 
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
        <title>'.$this->title.'</title> 
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
            @media print{@page {size: '.$orientation.'}}
            @media print{@page {mso-page-orientation:'.$orientation.'}}
            @page {size: '.$orientation.'}
            @page {mso-page-orientation:'.$orientation.'}
            @page section {size:841.7pt 595.45pt;mso-page-orientation:'.$orientation.';margin:1.25in 1.0in 1.25in 1.0in;mso-header-margin:.5in;mso-footer-margin:.5in;mso-paper-source:0;}
            div.section {page:section;}
            .rotatedText
            {
                mso-rotate:-90;
            }
        </style>
        <!--[if gte mso 9]><xml> 
         <o:shapedefaults v:ext="edit" spidmax="1032"> 
          <o:colormenu v:ext="edit" strokecolor="none"/> 
         </o:shapedefaults></xml><![endif]--><!--[if gte mso 9]><xml> 
         <o:shapelayout v:ext="edit"> 
          <o:idmap v:ext="edit" data="1"/> 
         </o:shapelayout></xml><![endif]--> '.
         $this->htmlHead.'
        </head> 
        <body>
        <div class=section>';
        return $return; 
    } 
    /** 
     * Return Document footer 
     * 
     * @return String 
     */ 
    function getFotter(){ 
        return "</div></body></html>"; 
    } 
 
    /** 
     * Create The MS Word Document from given HTML 
     * 
     * @param String $html :: HTML Content or HTML File Name like path/to/html/file.html 
     * @param String $file :: Document File Name 
     * @param Boolean $download :: Wheather to download the file or save the file 
     * @return boolean  
     */ 
    function createDoc($html, $file, $orientation, $download = false){ 
        if(is_file($html)){ 
            $html = @file_get_contents($html); 
        } 
         
        $this->_parseHtml($html); 
        $this->setDocFileName($file); 
        if($orientation == "landscape"){
            $doc = $this->getHeaderLandscape($orientation); 
        }elseif ($orientation == "portrait") {
            $doc = $this->getHeaderPortrait($orientation);
        }
        $doc .= $this->htmlBody; 
        $doc .= $this->getFotter(); 

        if($download){ 
            @header("Cache-Control: ");// leave blank to avoid IE errors 
            @header("Pragma: ");// leave blank to avoid IE errors 
            @header("Content-type: application/octet-stream"); 
            @header("Content-Disposition: attachment; filename=\"$this->docFile\""); 
            echo $doc; 
            return true; 
        }else {
            return $this->write_file($this->docFile, $doc); 
        } 
    } 
     
    /** 
     * Parse the html and remove <head></head> part if present into html 
     * 
     * @param String $html 
     * @return void 
     */ 
    function _parseHtml($html){ 
        $html = preg_replace("/<!DOCTYPE((.|\n)*?)>/ims", "", $html); 
        $html = preg_replace("/<script((.|\n)*?)>((.|\n)*?)<\/script>/ims", "", $html); 
        preg_match("/<head>((.|\n)*?)<\/head>/ims", $html, $matches); 
        $head = !empty($matches[1])?$matches[1]:''; 
        preg_match("/<title>((.|\n)*?)<\/title>/ims", $head, $matches); 
        $this->title = !empty($matches[1])?$matches[1]:''; 
        $html = preg_replace("/<head>((.|\n)*?)<\/head>/ims", "", $html); 
        $head = preg_replace("/<title>((.|\n)*?)<\/title>/ims", "", $head); 
        $head = preg_replace("/<\/?head>/ims", "", $head); 
        $html = preg_replace("/<\/?body((.|\n)*?)>/ims", "", $html); 
        $this->htmlHead = $head; 
        $this->htmlBody = $html; 
        return; 
    } 
     
    /** 
     * Write the content in the file 
     * 
     * @param String $file :: File name to be save 
     * @param String $content :: Content to be write 
     * @param [Optional] String $mode :: Write Mode 
     * @return void 
     */ 
    function write_file($file, $content, $mode = "w+"){ 
        $fp = @fopen($file, $mode); 
        if(!is_resource($fp)){ 
            return false; 
        } 
        fwrite($fp, $content); 
        fclose($fp);
        chmod($fp, 0777); 
        return true; 
    } 
}
