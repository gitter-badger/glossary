<?php
if (!defined('e107_INIT')) { exit; }

trait GlossaryTrait {
	function browse_letter($approved = 1)
	{
		$distinctwords = e107::getDb()->retrieve("glossary", " DISTINCT(glo_name) ", "glo_approved = '$approved' ORDER BY glo_name ASC", "default");
//    var_dump ($approved);
		foreach($distinctwords AS $row) 
		{
//			$arrletters[] = $this->first_car($row['glo_name']);
			$arrletters[] = strtoupper(mb_substr($row['glo_name'], 0, 1, 'utf-8'));
		}
		$distinctfirstletter = count($distinctwords);	 
		$arrletters = array_unique($arrletters);
//    var_dump ($arrletters);
//		$arrletters = array_values($arrletters);
		$arrletters = array_combine($arrletters, $arrletters);
//    var_dump ($arrletters);
		asort($arrletters); // Unnecessary, already sorted from mysql....
//    var_dump ($arrletters);
		$text = "";
		if($distinctfirstletter != 1)
		{
//      var_dump(USER_AREA === true);
		  if (USER_AREA === true){

		$word_shortcodes = e107::getScBatch('glossary', 'glossary');

		$ok = 0;
		for($i = 0; $i <= 255; $i++)
		{
			$car = chr($i);
//			if ($wall[$car] && (($car < 'A') || ($car > 'Z')))
			if ($arrletters[$car] && (($car < 'A') || ($car > 'Z')))
			{
				$ok =1;
				break;
			}
		}

//    var_dump ($arrletters);
    global $wcar;
		$wcar = "0-9";
/*
		if ($ok)
			$text .= e107::getParser()->parseTemplate($this->plugTemplates['WORD_CHAR_LINK'], FALSE, $word_shortcodes);
		else
			$text .= e107::getParser()->parseTemplate($this->plugTemplates['WORD_CHAR_NOLINK'], FALSE, $word_shortcodes);
*/
			$text .= e107::getParser()->parseTemplate($this->plugTemplates[($ok?'WORD_CHAR_LINK':'WORD_CHAR_NOLINK')], FALSE, $word_shortcodes);

		for($i = ord("A"); $i <= ord("Z"); $i++)
		{
			$wcar = chr($i);
//      echo " : ";
//      var_dump ($arrletters[$wcar]);
//      echo "<hr>";
/*
			if ($arrletters[$wcar])
				$text .= e107::getParser()->parseTemplate($this->plugTemplates['WORD_CHAR_LINK'], FALSE, $word_shortcodes);
			else
				$text .= e107::getParser()->parseTemplate($this->plugTemplates['WORD_CHAR_NOLINK'], FALSE, $word_shortcodes);
		}
*/
				$text .= e107::getParser()->parseTemplate($this->plugTemplates[($arrletters[$wcar]?'WORD_CHAR_LINK':'WORD_CHAR_NOLINK')], FALSE, $word_shortcodes);
		}
		$text = e107::getParser()->parseTemplate($this->plugTemplates['WORD_ALLCHAR_PRE'], FALSE).$text.e107::getParser()->parseTemplate($this->plugTemplates['WORD_ALLCHAR_POST'], FALSE);
		}
    else
    {
    	
      $text .= "</fieldset></form><div class='e-container'>";  
			//$text .= $rs->form_open("post", e_SELF . ($approved ? "" : "?displaySubmitted"), "letters")."			
			$text .= e107::getForm()->open("letters", "get", e_SELF . ($approved ? "?mode=main" : "?mode=submitted"))."
				<table id='show_letter' style='".ADMIN_WIDTH."' class='table adminlist table-striped'>
					<thead><tr class='even'>
						<td colspan='2' class='fcaption'>".LAN_GLOSSARY_SHOWLETT_01."</td>
					</tr></thead>
				<tr>
					<td colspan='2' class='forumheader3' style='text-align: center;'>";

/*
			for($i = 0; $i < count($arrletters); $i++)
			{
				if($arrletters[$i]!= "")
*/
			foreach($arrletters as $i)
			{
//        var_dump ($i);
//				if($arrletters[$i]!= "")
				if($i!= "")
					//$text .= $rs->form_button("submit", "letter", strtoupper($arrletters[$i]), "", "", LAN_GLOSSARY_SHOWLETT_03);
          // form button only allows 5 options.... not 6... No tooltip available there..

//var_dump ($_GET['letter'] == $arrletters[$i]);          
//var_dump ($_GET['letter'] == $arrletters[$i]);          
//					$text .= e107::getForm()->button("letter", strtoupper($arrletters[$i]), "submit", "", ($_GET['letter']==$arrletters[$i]?['class'=>'active', 'disabled'=>'disabled',]:''));
					$text .= e107::getForm()->button("letter", strtoupper($i), "submit", "", ($_GET['letter']==$i?['class'=>'active', 'disabled'=>'disabled',]:''));
			}

			//$text .= "&nbsp;".$rs->form_button("submit", "letter", LAN_GLOSSARY_SHOWLETT_02, "", "", LAN_GLOSSARY_SHOWLETT_04);
//			$text .= "&nbsp;".e107::getForm()->button( "letter", LAN_GLOSSARY_SHOWLETT_02, "submit", "", LAN_GLOSSARY_SHOWLETT_04);
          // form button only allows 5 options.... not 6... No tooltip available there..

			$text .= "&nbsp;".e107::getForm()->button( "letter", LAN_GLOSSARY_SHOWLETT_02, "submit", "", (($_GET['letter']=='All' || !$_GET['letter'])?['class'=>'active', 'disabled'=>'disabled',]:''));
			$text .= "</td></tr></table>".e107::getForm()->close()."</div><form><fieldset>";
    }
    
    }
		return $text;
	}
}

?>