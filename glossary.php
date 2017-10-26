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
 * $Source: /home/e-smith/files/ibays/cvsroot/files/glossary/glossary.php,v $
 * $Revision: 1.5 $
 * $Date: 2006/06/27 13:38:49 $
 * $Author: duclos $
 */

if (!defined('e107_INIT'))
{
	require_once("../../class2.php");
}

$pref = e107::getPlugConfig('glossary')->getPref();
//these have to be set for the tinymce wysiwyg
$e_wysiwyg	= "word_desc";
$WYSIWYG = true;


//require_once(e_PLUGIN.'glossary/glossary_defines.php');

include_lan(e_PLUGIN."glossary/languages/".e_LANGUAGE."/Lan_".basename(__FILE__));

if(!$sql -> db_Select("plugin", "*", "plugin_path = 'glossary' AND plugin_installflag = '1' "))
{
	require_once(HEADERF);
	$ns -> tablerender("", "<b><u>Glossary:</u> ".LAN_GLOSSARY_GLO_01."</b>");
	require_once(FOOTERF);
	exit;
}

require_once(e_HANDLER."form_handler.php");
$rs = new form;

//require_once(e_PLUGIN.'glossary/glossary_class.php');
//$gc = new glossary_class();
e107::lan('glossary','front',true);
$word_shortcodes = e107::getScBatch('glossary', 'glossary');

$endfile = (deftrue('BOOTSTRAP') === 3)?"bootstrap3":"table";
//var_dump ($endfile);
$glossarytemplate   = e107::getTemplate('glossary', 'glossary'.$endfile );
//var_dump ('glossary'.$endfile);
$word_shortcodes->wrapper('glossary'.$endfile.'/glossary');
//var_dump ($glossarytemplate);


$deltest = array_flip($_POST);

if (e_QUERY)
	list($action, $id) = explode(".", e_QUERY);

if(isset($_POST['action']))
{
	$tmp = array_pop(array_flip($_POST['action']));
	list($action, $id) = explode("_", $tmp);
}

// ################## Start of class cleanup, no need for single used class methods....
//$gc->setPageTitle();
    $pref = e107::getPlugConfig('glossary')->getPref();
		// Show all words
//		if (!$action)
		if (!$action || $action == "s" || $action == "s_direct")
			$page = LAN_GLOSSARY_PAGETITLE_01." / ".LAN_GLOSSARY_PAGETITLE_02;

//		if ($action == "submit" && check_class($pref['glossary_submit_class']))
		if (($action == "submit"  || $action == "createSub" )&& check_class($pref['glossary_submit_class']))
			$page = LAN_GLOSSARY_PAGETITLE_01." / ".LAN_GLOSSARY_PAGETITLE_03;

		define("e_PAGETITLE", $page);
// ################# End of class cleanup...



require_once(HEADERF);

require_once(e_HANDLER."ren_help.php");

if ($action == "createSub")
{
	if (check_class($pref['glossary_submit_class']))
	{
/*
		if (isset($pref['glossary_submit_directpost']) && $pref['glossary_submit_directpost'])
			$gc->createSubWord(1);
		else
			$gc->createSubWord(0);
*/

// ################## Start of class cleanup, no need for single used class methods....
////////			$gc->createDef(isset($pref['glossary_submit_directpost']), 1);

    $id = isset($pref['glossary_submit_directpost']);
    $sub = 1;

///////----		global $sql, $tp, $ns, $rs ;
    $pref = e107::getPlugConfig('glossary')->getPref();
		$username = "";
		$word_link = 0;

		if ($id && !$sub)
		{
			if ($sql->db_Select("glossary", "*", "glo_id='$id'"))
			{
				$row				= $sql->db_Fetch();
				$word_link	= $row['glo_linked'];
				$username		= $row['glo_author'];
				if (e_WYSIWYG)
				{
					$word_name = $tp->toHTML($row['glo_name'], $parseBB = TRUE, "no_hook");
					$word_desc = $tp->toHTML($row['glo_description'], $parseBB = TRUE, "no_hook");
				}
				else
				{
					$word_name = $tp->toFORM($row['glo_name']);
					$word_desc = $tp->toFORM($row['glo_description']);
				}
			}
		}

/*
		if ($username == "")
		{
			if (USER)
				$username = USERID.".".USERNAME;
			else
				$username = "0.".LAN_GLOSSARY_SUBMITWORD_03;
		}
*/		
		$text = "
		<div style='text-align:center'>
		".$rs->form_open("post", e_SELF, "dataform", "", "", "")."
				<table id='createDef'  class='fborder'>
					<tr>
						<td colspan='2' style='width:100%; text-align:center' class='forumheader'><b>";
		
		if (!$id || $sub)
			$text .= LAN_GLOSSARY_CREATEWORD_08;     
		else
			$text .= LAN_GLOSSARY_CREATEWORD_09;
		
		$text .= "</b>
						</td>
					</tr>
					<tr>
						<td style='width:20%' class='forumheader3'>".LAN_GLOSSARY_CREATEWORD_03."</td>
						<td style='width:80%' class='forumheader3'>			
							".$rs->form_text("word_name", 50, $word_name, "50")."
						</td>
					</tr>
					<tr>
						<td style='width:20%' class='forumheader3'>".LAN_GLOSSARY_CREATEWORD_04."</td>
						<td style='width:80%' class='forumheader3'>";

		$word_desc = $tp->toForm($word_desc);

		if (!e_WYSIWYG && $pref['glossary_submit_htmlarea'])
			$text .= $rs->form_textarea("word_desc",
																	80,
																	15,
																	strstr($word_desc, "[img]http") ? $word_desc : str_replace("[img]../", "[img]", $word_desc),
																	" onselect='storeCaret(this);' onclick='storeCaret(this);' onkeyup='storeCaret(this);' ");
		else
			$text .= $rs->form_textarea("word_desc",
																	80,
																	25,
																	strstr($word_desc, "[img]http") ? $word_desc : str_replace("[img]../", "[img]", $word_desc),
																	"",
																	"width: 100%");

		if (!e_WYSIWYG && $pref['glossary_submit_htmlarea'])
			$text .= $rs->form_text("helpb", 100, "", "", "helpbox")."<br />".display_help("helpb");

		$text .= "
						</td>
					</tr>";

		if (!$sub && getperms("0"))
			$text .= "
					<tr>
						<td style='width:20%' class='forumheader3'>".LAN_GLOSSARY_CREATEWORD_07."</td>
						<td style='width:80%' class='forumheader3'>
							".$rs->form_radio("word_link", "1", ($word_link ? "1" : "0"), "", "").LAN_GLOSSARY_OPT_01."
							".$rs->form_radio("word_link", "0", ($word_link ? "0" : "1"), "", "").LAN_GLOSSARY_OPT_02."
						</td>
					</tr>";

		$text .= "
					<tr>
						<td colspan='2' style='text-align:center' class='forumheader'>";

		if ($sub)
			$text .= $rs->form_button("submit", "action[submit]", $id ? LAN_GLOSSARY_CREATEWORD_02 : LAN_GLOSSARY_CREATEWORD_06);
		else if ($id)
			$text .= $rs->form_button("submit", "action[update_{$id}]", LAN_GLOSSARY_CREATEWORD_05);
// Old unused code?
		else
			$text .= $rs->form_button("submit", "action[add]", LAN_GLOSSARY_CREATEWORD_02);
			
//		$text .= $rs->form_hidden("username", $username);
		$text .= $rs->form_hidden("username", (USERID?:0));

		$text .= "
						</td>
					</tr>
				</table>
			".$rs->form_close()."
		</div>";

		if ($sub)
		{
			if ($id)
				$caption = LAN_GLOSSARY_CREATEWORD_02;
			else
				$caption = LAN_GLOSSARY_CREATEWORD_10;
		}
		else if ($id)
			$caption = LAN_GLOSSARY_CREATEWORD_01;
		else
			$caption = LAN_GLOSSARY_CREATEWORD_02;

		$ns -> tablerender($caption, $text);

// ############## End of class cleanup...
















	}
	else
		js_location(e_SELF);
}

// Deprecated, old admin actions...

if ($action == "add" || $action == "submit")
{
	if (check_class($pref['glossary_submit_class']))

    {
// ################## Start of class cleanup, no need for single used class methods....
//		$gc->submitWord();

//	function submitWord()
//	{
//		global $sql, $tp, $e_event, $e107 ;
    $pref = e107::getPlugConfig('glossary')->getPref();
		$word_name = $tp -> toDB($_POST['word_name']);
		$word_desc = $tp -> toDB($_POST['word_desc']);
/*
		if (!isset($_POST['username']))
		{
			if (USER)
//				$username = USERID.".".USERNAME;
				$username = USERID;
			else
//				$username = "0.".LAN_GLOSSARY_SUBMITWORD_03;
				$username = "0";
		}
		else
			$username = $tp -> toDB($_POST['username']);
*/
// Test ternary if
		$username = (!isset($_POST['username'])?(USER?USERID:"0"):$tp -> toDB($_POST['username']));

		$ip = $e107->getip();

		$edata_ls = array(
			"username"				=> $username,
			"ip"							=> $ip,
			"word_name"				=> $word_name,
			"word_desc"				=> $word_desc,
			);

		$fp = new floodprotect;
		if ($fp->flood("glossary", "datestamp") == false && !ADMIN)
		{
			js_location(e_BASE."index.php");
			exit;
		}

/*
		$direct = (isset($pref['glossary_submit_directpost']) && $pref['glossary_submit_directpost']) ? 1 : 0;

		if ($direct)
			$this->addWord(1);
		else
			$this->addWord(0);
*/

// ################## Start of class cleanup, no need for single used class methods....
///////    $this->updateWord(0, isset($pref['glossary_submit_directpost']));

    $id = 0;
    $approved = isset($pref['glossary_submit_directpost'])?:1;
    global $sql, $tp, $e107cache;

		$word_name = $tp -> toDB($_POST['word_name']);
		$word_desc = $tp -> toDB($_POST['word_desc']);
		$username =  $tp -> toDB($_POST['username']);
		if (isset($_POST['word_link']))
			$word_link = $_POST['word_link'];

		switch($id)
		{
			case 0:
//				$create = $sql -> db_Insert("glossary", "'0', '$word_name', '$word_desc', '$username', '".time()."', '$approved'".(isset($word_name) ? ", '$word_link'" : ""));
				$update = $sql -> db_Insert("glossary", "'0', '$word_name', '$word_desc', '$username', '".time()."', '$approved'".(isset($word_name) ? ", '$word_link'" : ""));
//				$gc->admin_update($create, 'insert', LAN_GLOSSARY_SUBMITWORD_01);
				$type = 'insert';
				$success = LAN_GLOSSARY_SUBMITWORD_01;
				break;
			default:
				$update = $sql->db_Update("glossary", "glo_name='$word_name', glo_description='$word_desc', glo_approved='$approved'".(isset($word_name) ? ", glo_linked='$word_link'" : "")." WHERE glo_id='".intval($id)."'");
//				$gc->admin_update($update, 'update', LAN_GLOSSARY_SUBMITWORD_02);
				$type = 'update';
				$success = LAN_GLOSSARY_SUBMITWORD_02;
				break;
		}

//	function admin_update($update, $type, $success)
//	{
//		global $ns;
		
		if (($type == "update" && $update) || ($type == "insert" && $update != false))
		{
			$caption = LAN_UPDATE;
			$text = $success ? $success : LAN_UPDATED;
		}
		else if ($type == "delete" && $update)
		{
			$caption = LAN_DELETE;
			$text = $success ? $success : LAN_DELETED;
		}
		else if (!mysql_errno())
		{
			if ($type == "update")
			{
				$caption = LAN_UPDATED_FAILED;
				$text = LAN_NO_CHANGE."<br />".LAN_TRY_AGAIN;
			}
			else if ($type == "delete")
			{
				$caption = LAN_DELETED_FAILED;
				$text = LAN_DELETED_FAILED."<br />".LAN_TRY_AGAIN;
			}
		}
		else
		{
			$caption = LAN_UPDATED_FAILED;
			$text = $failed ? $failed : LAN_UPDATED_FAILED." - ".LAN_ERROR." ".mysql_errno().": ".mysql_error();
		}
		
//		$gc->message = $text;
//		$gc->caption = $caption;
//	}






		$e107cache->clear();
// ################# End of class cleanup...
    			
		$e_event->trigger("wordsub", $edata_ls);
		
		if ($direct)
			js_location(e_SELF."?s_direct");
		else
			js_location(e_SELF."?s");
//	}
// ################# End of class cleanup...
    }

	else
		js_location(e_SELF);
}

// ################## Start of class cleanup, no need for single used class methods....
$mes = e107::getMessage();
$msgpre  = e107::getParser()->parseTemplate($glossarytemplate['PRINT_MESSAGE_PRE'], FALSE);
$msgpost = e107::getParser()->parseTemplate($glossarytemplate['PRINT_MESSAGE_POST'], FALSE);
    
if ($action == "s" || $action == "s_direct")
{
//if ($action == "s")
//	$gc->show_message(LAN_GLOSSARY_GLO_02, LAN_GLOSSARY_GLO_03);
//	$message=LAN_GLOSSARY_GLO_02;

//if ($action == "s_direct")
//	$gc->show_message(LAN_GLOSSARY_GLO_04, LAN_GLOSSARY_GLO_03);
//	$message=LAN_GLOSSARY_GLO_04;

  $caption=LAN_GLOSSARY_GLO_03;
//  $ns->tablerender($caption, $gc->build_message($message));
//  $ns->tablerender($caption, $gc->build_message($action == "s"?LAN_GLOSSARY_GLO_02:LAN_GLOSSARY_GLO_04));
  $mes->addSuccess($msgpre.$caption.". ".($action == "s"?LAN_GLOSSARY_GLO_02:LAN_GLOSSARY_GLO_04).$msgpost);
/////  $ns->tablerender($caption, $gc->build_message($action == "s"?LAN_GLOSSARY_GLO_02:LAN_GLOSSARY_GLO_04));
}
// ################# End of class cleanup...

//----if (!e_QUERY)
if (!e_QUERY || $action == "s" || $action == "s_direct")
{
// ################## Start of class cleanup, no need for single used class methods....
//	$gc->displayWords($gc->displayNav("page"));

/*
		global $sql, $rs, $ns;
		global $glo_id, $word, $description;
		global $wcar;
*/
		//require_once(e_PLUGIN.'glossary/glossary_shortcodes.php');
		$word_table = "";
		$wall = array();
		$title = e107::getParser()->parseTemplate($glossarytemplate['WORD_PAGE_TITLE'], FALSE, $word_shortcodes);
		$words = $sql->retrieve("glossary", "*", "glo_approved = '1' ORDER BY glo_name ASC" , true);
  
		if ($words)
		{
      $firstword      = TRUE;
      foreach($words as $row)
     	{
        $glo_id       = $row['glo_id'];
        $word         = $row['glo_name'];
        $description  = $row['glo_description'];
        
				if ($wcar <> strtoupper($word{0}))
				{
					$wcar = strtoupper($word{0});
					$wall[$wcar] = 1;  
          if(!$firstword) {  
           $text .= e107::getParser()->parseTemplate($glossarytemplate['WORD_CHAR_END'], FALSE, $word_shortcodes);
          }         
          $text .= e107::getParser()->parseTemplate($glossarytemplate['WORD_CHAR_START'], FALSE, $word_shortcodes);
					$text .= e107::getParser()->parseTemplate($glossarytemplate['WORD_ANCHOR'], FALSE, $word_shortcodes);
          $firstword      = FALSE;
				}
				$text .= e107::getParser()->parseTemplate($glossarytemplate['WORD_BODY_PAGE'], FALSE, $word_shortcodes);
				$text .= e107::getParser()->parseTemplate($glossarytemplate['BACK_TO_TOP'], FALSE, $word_shortcodes);
        
			} 
      $text .= e107::getParser()->parseTemplate($glossarytemplate['WORD_CHAR_END'], FALSE, $word_shortcodes);
		}
 /* Already in browse_letter method
		$ok = 0;
		for($i = 0; $i <= 255; $i++)
		{
			$car = chr($i);
			if ($wall[$car] && (($car < 'A') || ($car > 'Z')))
			{
				$ok =1;
				break;
			}
		}

		$wcar = "0-9";
		if ($ok)
			$text2 .= e107::getParser()->parseTemplate($gc->plugTemplates['WORD_CHAR_LINK'], FALSE, $this->word_shortcodes);
		else
			$text2 .= e107::getParser()->parseTemplate($gc->plugTemplates['WORD_CHAR_NOLINK'], FALSE, $this->word_shortcodes);

		for($i = ord("A"); $i <= ord("Z"); $i++)
		{
			$wcar = chr($i);
			if ($wall[$wcar])
				$text2 .= e107::getParser()->parseTemplate($gc->plugTemplates['WORD_CHAR_LINK'], FALSE, $this->word_shortcodes);
			else
				$text2 .= e107::getParser()->parseTemplate($gc->plugTemplates['WORD_CHAR_NOLINK'], FALSE, $this->word_shortcodes);
		}

		$text2 = e107::getParser()->parseTemplate($gc->plugTemplates['WORD_ALLCHAR_PRE'], FALSE).$text2.e107::getParser()->parseTemplate($gc->plugTemplates['WORD_ALLCHAR_POST'], FALSE);
		$text  = $text2.$text;
*/
//		$text  = $gc->browse_letter().$text;
//print_r ($word_shortcodes->browse_letter());

///////////////		$text  = $word_shortcodes->browse_letter().$text;
		$text  = $word_shortcodes->sc_browse_letter().$text;
    
    $start = e107::getParser()->parseTemplate($glossarytemplate['WORD_PAGE_START']);
    $end   = e107::getParser()->parseTemplate($glossarytemplate['WORD_PAGE_END']);

		if (!$words)
//			$text .= $gc->build_message(LAN_GLOSSARY_DISPLAYWORDS_01);
			$mes->addError($msgpre.LAN_GLOSSARY_DISPLAYWORDS_01.$msgpost);

//		$ns->tablerender($title, $start.$submittext.$text.$end);
// ################## Start of class cleanup, no need for single used class methods....
//		$ns->tablerender($title, $start.$gc->displayNav("page").$text.$end);
		$ns->tablerender($title, $start.$mes->render().$tp->parseTemplate($glossarytemplate['LINK_PAGE_NAVIGATOR'], FALSE, $word_shortcodes).$text.$end);
// ################# End of class cleanup...

}

require_once(FOOTERF);

?>