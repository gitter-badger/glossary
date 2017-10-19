<?php
/**
 * Glossary by Shirka (www.shirka.org)
 *
 * A plugin for the e107 Website System (http://e107.org)
 *
 * ©Andre DUCLOS 2006
 * http://www.shirka.org
 * duclos@shirka.org
 *
 * Released under the terms and conditions of the
 * GNU General Public License (http://gnu.org).
 *
 * $Source: /home/e-smith/files/ibays/cvsroot/files/glossary/glossary_shortcodes.php,v $
 * $Revision: 1.7 $
 * $Date: 2006/06/28 01:16:10 $
 * $Author: duclos $
 */

if (!defined('e107_INIT')) { exit; }

class plugin_glossary_glossary_shortcodes extends e_shortcode
{

	function __construct()
	{
//				$this->sql = e107::getDb('sc_sql'); 
// Globals are evil.....
				$this->tp = e107::getParser();
//        $this->template = e107::getTemplate('euser', 'whatsnew_menu');
// Globals are evil.....
        $this->pref = e107::getPlugPref("glossary");

//        $this->styledata = $this->sql->retrieve("philalb_genre as testeconstr", "genre_id,genre_name,genre_icon", "", true);

//        $this->userData = e107::getSystemUser($this->var['book_author'])->getData();
//        $sysuser = e107::getSystemUser($this->var['book_author'], false);
//		    $this->userData = e107::getSystemUser(intval($this->var['book_author']));
//        $this->userData = e107::getSystemUser($this->var['book_author']);
       
//    		$this->sql->select("euser", "*", "user_id='".$this->var['user_id']."' ");
//    		$this->euser_data = $this->sql->fetch();
	}


 
	public function sc_word_name()  {
// Globals are evil..... this needs rewrite to use sc vars
//		global $glo_id, $word, $tp;
		global $glo_id, $word;
//Wheres is this $parm fetched???
		if ($parm == "page")
			return "<a id='word_id_".$glo_id."'>".$this->tp->toHTML($word, TRUE)."</a>";
		else
//			return $tp->toHTML($word, TRUE);
			return $this->tp->toHTML($word, TRUE);
	}
	
	public function sc_word_description()  {
// Globals are evil..... this needs rewrite to use sc vars
//		global $description, $tp;
//		return $tp->toHTML($description, TRUE);
		global $description;
		return $this->tp->toHTML($description, TRUE);
	}
	
	public function sc_link_to_top() {
		return "<a href='".e_SELF."#top' title='".LAN_GLOSSARY_LINK_TOP_02."'>".LAN_GLOSSARY_LINK_TOP_01."</a>";
	}
	
	public function sc_word_page_title() {
// Globals are evil.....
//		global $pref;   
//		return "<a id='top'>".$pref['glossary_page_title']."</a>";
// Why a url tag without href????
		return "<a id='top'>".$this->pref['glossary_page_title']."</a>";
	}
	
	public function sc_word_menu_title() {
// Globals are evil.....
/*
		global $pref;
		if ($pref['glossary_menu_lastword'])
			return LAN_GLOSSARY_MENU_TITLE_01;
		else
			return LAN_GLOSSARY_MENU_TITLE_02;
*/
			return ($this->pref['glossary_menu_lastword']?LAN_GLOSSARY_MENU_TITLE_01:LAN_GLOSSARY_MENU_TITLE_02);
	}
	
	public function sc_word_anchor($parm = NULL) {
// Globals are evil..... this needs rewrite to use sc vars
		global $wcar;
    $parms = eHelper::scParams($parm);
    $tag = varset($parms['tag'], 'a');
		return "<{$tag} id='".$wcar."'>".$wcar."</{$tag}>";
	}
	
	public function sc_word_char_link($parm = NULL) {
// Globals are evil..... this needs rewrite to use sc vars
		global $wcar;
		$parms = eHelper::scParams($parm);
 
		$class = varset($parms['class'], 'word_char_link');
		if ($parms['link'] == "link")
			return "<a href='".e_SELF."#".$wcar."' class='" . $class . "'  title='".LAN_GLOSSARY_LINK_LETTER_01." &lt;".$wcar."&gt;"."'>".$wcar."</a>";
		else
			return $wcar;
	}
  /* reason: to use shortcode wrapper */ 
	public function sc_word_char($parm = NULL) {
// Globals are evil..... this needs rewrite to use sc vars
		global $wcar;
		return $wcar;
	}  
	
	public function sc_adminoptions() {
// Globals are evil..... this needs rewrite to use sc vars
		global $glo_id;
		if (ADMIN && getperms("P"))
		{                              
			$adop_icon = (file_exists(THEME."generic/newsedit.png") ? "<img src='".THEME."generic/newsedit.png"."'  alt='' style='border:0' />"
       : "<icon class='fa fa-edit'></icon>");
//			return " <a href='".e_PLUGIN."glossary/admin_config_old.php?edit.".$glo_id."' target='_blank' title='".LAN_GLOSSARY_ADMINOPTIONS_01."'>".$adop_icon."</a>\n";
			return " <a href='".e_PLUGIN."glossary/admin_config.php?mode=main&action=edit&id=".$glo_id."' target='_blank' title='".LAN_GLOSSARY_ADMINOPTIONS_01."'>".$adop_icon."</a>\n";
		}
		else
			return "";
	}
	
	public function sc_emailitem() {
// Globals are evil..... this needs rewrite to use sc vars
//		global $glo_id, $tp, $pref;
//		if (isset($pref['glossary_emailprint']) && $pref['glossary_emailprint'])
			return $this->tp->parseTemplate("{EMAIL_ITEM=".LAN_GLOSSARY_EMAILPRINT_01."^plugin:glossary.{$glo_id}}");
// ???? This below is not run, because the return is above...
		global $glo_id;
		if (isset($this->pref['glossary_emailprint']) && $this->pref['glossary_emailprint'])
			return $this->tp->parseTemplate("{EMAIL_ITEM=".LAN_GLOSSARY_EMAILPRINT_01."^plugin:glossary.{$glo_id}}");
	}
	
	public function sc_printitem() {
// Globals are evil..... this needs rewrite to use sc vars
//		global $glo_id, $tp;
		global $glo_id;
/*
    $pref = e107::getPlugConfig('glossary')->getPref();
		if (isset($pref['glossary_emailprint']) && $pref['glossary_emailprint'])
			return $tp->parseTemplate("{PRINT_ITEM=".LAN_GLOSSARY_EMAILPRINT_02."^plugin:glossary.{$glo_id}}");
*/
		if (!empty($this->pref['glossary_emailprint']))
			return $this->tp->parseTemplate("{PRINT_ITEM=".LAN_GLOSSARY_EMAILPRINT_02."^plugin:glossary.{$glo_id}}");
	}
	
	public function sc_pdfitem() {
// Globals are evil..... this needs rewrite to use sc vars
//		global $glo_id, $tp;
		global $glo_id;
/*
    $pref = e107::getPlugConfig('glossary')->getPref();
		if (isset($pref['glossary_emailprint']) && $pref['glossary_emailprint'])
			return $tp->parseTemplate("{PDF=".LAN_GLOSSARY_EMAILPRINT_03."^plugin:glossary.{$glo_id}}");
*/
		if (!empty($this->pref['glossary_emailprint']))
			return $this->tp->parseTemplate("{PDF=".LAN_GLOSSARY_EMAILPRINT_03."^plugin:glossary.{$glo_id}}");
	}
	
	public function sc_link_page_navigator() {
// Globals are evil..... this needs rewrite to use sc vars
		global $LINK_PAGE_NAVIGATOR, $rs;
//    $pref = e107::getPlugConfig('glossary')->getPref();
		$text = "";
		$mains = "";
		$baseurl = e_PLUGIN."glossary/glossaire.php";
//		if(isset($pref['glossary_page_link_submit']) && $pref['glossary_page_link_submit'] && isset($pref['glossary_submit']) && $pref['glossary_submit'] && check_class($pref['glossary_submit_class']))
		if(!empty($this->pref['glossary_page_link_submit']) && !empty($this->pref['glossary_submit']) && check_class($this->pref['glossary_submit_class']))
///////		if(isset($pref['glossary_page_link_submit']) && $pref['glossary_page_link_submit'] && $pref['glossary_submit'] && check_class($pref['glossary_submit_class']))
		{
/*
			$direct = (isset($pref['glossary_submit_directpost']) && $pref['glossary_submit_directpost']) ? 1 : 0;
			if(isset($pref['glossary_page_link_rendertype']) && $pref['glossary_page_link_rendertype'] == "1")
*/
			$direct = empty($this->pref['glossary_submit_directpost'])? LAN_GLOSSARY_GLO_05 : LAN_GLOSSARY_GLO_06;
/*
			if(isset($this->pref['glossary_page_link_rendertype']) && $pref['glossary_page_link_rendertype'] == "1")
				$mains = $rs->form_option($direct ? LAN_GLOSSARY_GLO_06 : LAN_GLOSSARY_GLO_05, "0", $baseurl."?createSub", "");
			else
				$mains = "<a href='".$baseurl."?createSub'>".($direct ? LAN_GLOSSARY_GLO_06 : LAN_GLOSSARY_GLO_05)."</a>";
*/
				$mains = (isset($this->pref['glossary_page_link_rendertype']) && $pref['glossary_page_link_rendertype'] == "1")?$rs->form_option($direct , "0", $baseurl."?createSub", ""):"<a href='".$baseurl."?createSub'>".$direct."</a>";
		}
		
		if($mains)
		{
/*
			$cap = (isset($pref['glossary_page_caption_nav']) && $pref['glossary_page_caption_nav'] ? $pref['glossary_page_caption_nav'] : LAN_GLOSSARY_GLO_07);
			if(isset($pref['glossary_page_link_rendertype']) && $pref['glossary_page_link_rendertype'] == "1")
*/
			$cap = (empty($this->pref['glossary_page_caption_nav'])? LAN_GLOSSARY_GLO_07 : $this->pref['glossary_page_caption_nav']);
			if(isset($this->pref['glossary_page_link_rendertype']) && $this->pref['glossary_page_link_rendertype'] == "1")
			{
				$selectjs = "style='width:100%;' onchange=\"if(this.options[this.selectedIndex].value != ''){ return document.location=this.options[this.selectedIndex].value; }\" ";
				$text .= $rs->form_select_open("navigator", $selectjs);
				$text .= $rs->form_option($cap, "0", "", "");
				$text .= $rs->form_option("", "0", "", "");
				$text .= $mains;
				$text .= $rs->form_select_close();
				$text .= "<br />";
			}
			else
			{
				$text .= $cap."<br />";
				$text .= $mains;
			}
		}
		return $text;
	}
	
	public function sc_link_menu_navigator()  {
// Globals are evil..... this needs rewrite to use sc vars
		global $LINK_MENU_NAVIGATOR, $rs ;
//    $pref = e107::getPlugConfig('glossary')->getPref();
		$text = "";
		$mains = "";
		$baseurl = e_PLUGIN."glossary/glossaire.php";
		$bullet = defined("BULLET") ? "<img src='".THEME_ABS."images/".BULLET."' alt='' style='vertical-align: middle; border: 0;' />" : "<img src='".e_PLUGIN."glossary/images/bullet2.gif' alt='bullet' style='vertical-align: middle; border: 0;' />";
/*
		if(isset($pref['glossary_menu_link_frontpage']) && $pref['glossary_menu_link_frontpage'])
		{
			if(isset($pref['glossary_menu_link_rendertype']) && $pref['glossary_menu_link_rendertype'] == "1")
*/
		if(!empty($this->pref['glossary_menu_link_frontpage']))
		{
/*
			if(isset($this->pref['glossary_menu_link_rendertype']) && $this->pref['glossary_menu_link_rendertype'] == "1")
				$mains .= $rs->form_option(LAN_GLOSSARY_BLMENU_02, "0", $baseurl, "");
			else
				$mains .= $bullet."&nbsp;<a href='".$baseurl."'>".LAN_GLOSSARY_BLMENU_02."</a>";
*/
				$mains .= (isset($this->pref['glossary_menu_link_rendertype']) && $this->pref['glossary_menu_link_rendertype'] == "1")?$rs->form_option(LAN_GLOSSARY_BLMENU_02, "0", $baseurl, ""):$bullet."&nbsp;<a href='".$baseurl."'>".LAN_GLOSSARY_BLMENU_02."</a>";
		}
		
//		if(isset($pref['glossary_menu_link_submit']) && $pref['glossary_menu_link_submit'] && isset($pref['glossary_submit']) && $pref['glossary_submit'] && check_class($pref['glossary_submit_class']))
		if(!empty($this->pref['glossary_menu_link_submit']) && !empty($this->pref['glossary_submit']) && check_class($this->pref['glossary_submit_class']))
////		if(isset($pref['glossary_menu_link_submit']) && $pref['glossary_menu_link_submit'] && $pref['glossary_submit'] && check_class($pref['glossary_submit_class']))
		{
/*
			$direct = (isset($pref['glossary_submit_directpost']) && $pref['glossary_submit_directpost']) ? 1 : 0;
			if(isset($pref['glossary_menu_link_rendertype']) && $pref['glossary_menu_link_rendertype'] == "1")
*/
			$direct = empty($this->pref['glossary_submit_directpost'])? LAN_GLOSSARY_BLMENU_03 : LAN_GLOSSARY_BLMENU_06;
/*
			if(isset($pref['glossary_menu_link_rendertype']) && $pref['glossary_menu_link_rendertype'] == "1")
				$mains .= $rs->form_option($direct ? LAN_GLOSSARY_BLMENU_06 : LAN_GLOSSARY_BLMENU_03, "0", $baseurl."?createSub", "");
			else
				$mains .= ($mains ? "<br />" : "").$bullet."&nbsp;<a href='".$baseurl."?createSub'>".($direct ? LAN_GLOSSARY_BLMENU_06 : LAN_GLOSSARY_BLMENU_03)."</a>";
*/
				$mains .= (isset($this->pref['glossary_menu_link_rendertype']) && $this->pref['glossary_menu_link_rendertype'] == "1")?$rs->form_option($direct, "0", $baseurl."?createSub", ""):($mains ? "<br />" : "").$bullet."&nbsp;<a href='".$baseurl."?createSub'>".$direct."</a>";
		}
		
		if($mains)
		{
//			$cap = (isset($pref['glossary_menu_caption_nav']) && $pref['glossary_menu_caption_nav'] ? $pref['glossary_menu_caption_nav'] : LAN_GLOSSARY_BLMENU_04);
			$cap = (empty($this->pref['glossary_menu_caption_nav']) ? LAN_GLOSSARY_BLMENU_04 : $this->pref['glossary_menu_caption_nav']);
			if(isset($this->pref['glossary_menu_link_rendertype']) && $this->pref['glossary_menu_link_rendertype'] == "1")
			{
				$selectjs = "style='width:100%;' onchange=\"if(this.options[this.selectedIndex].value != ''){ return document.location=this.options[this.selectedIndex].value; }\" ";
				$text .= $rs->form_select_open("navigator", $selectjs);
				$text .= $rs->form_option($cap, "0", "", "");
				$text .= $rs->form_option("", "0", "", "");
				$text .= $mains;
				$text .= $rs->form_select_close();
			}
			else
			{
				$text .= $cap."<br />";
				$text .= $mains;
			}
		}
		return $text;
	}

} 

?>