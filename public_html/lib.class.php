<?php
/**
 *
 * Lib - czyli biblioteka przydatnych funkcji (helper)
 *
 *
 * @author Jakub Luczynski jakub.luczynski@gmail.com
 *
 * @version 2.0
 * @copyright (c) 2012 - 2013 CreoLink, http://www.creolink.pl/
 *
 */
?>
<?php
class Lib extends Singleton
{
	/*
	// Deklaracje pol klasy
	*/


	private static $_bPreparedMetaTitle = FALSE;		// czy przygotowane sa metatagi 'title'
	private static $_bPreparedMetaDescription = FALSE;	// czy przygotowane sa metatagi 'description'
	private static $_bPreparedMetaKeywords = FALSE;		// czy przygotowane sa metatagi 'keywords'
	
	private static $_aFilePointer = array();			// pointery do tworzonych plikow w stout


	/*
	// Konstruktor i destruktor
	*/


	protected function __construct() {}

	public function __destruct() {}


	/*
	// Metody prywatne, protected
	*/

	



	/*
	// Metody publiczne
	*/

	
	public static function removeSigns($p_sString, $p_bClearSpaces = TRUE, array $p_aOtherSigns = NULL, array $aReplacement = NULL)
	{
		$sString = ((string)($p_sString));
		$bClearSpaces = ((bool)($p_bClearSpaces));
		$aOtherSigns = ((array)($p_aOtherSigns));

		$aReplaceFrom = array(
			"/[\\\]+/isx",
			"/(&gt;)+/isx",
			"/(&lt;)+/isx",
			"/(&quot;)+/isx",
			"/(&[#]039;)+/isx",
			"/(&nbsp;)+/isx",
			"/(&amp;)+/isx",
			"/<[^>]*>/isx",
			"/(>)+|(<)+|(\\\)+|(')+|(`)+|(\")+/isx" //"/[\>]+|[\<]+|[\\]+|[\']+|[\"]+|/isx"
			);

		$aReplaceTo = array(
			"",
			">",
			"<",
			"\"",
			"'",
			" ",
			"&",
			"",
			""
			);

		$sString = strip_tags(preg_replace($aReplaceFrom, $aReplaceTo, stripslashes($sString)));

		if ($bClearSpaces === TRUE) { $sString = preg_replace("/\s+/isx", " ", $sString); }
		
		if (sizeof($aOtherSigns) > 0) { $sString = str_replace($aOtherSigns, '', $sString); }
		
		if (is_array($aReplacement))
		{
			$sString = str_replace($aReplacement[0], $aReplacement[1], $sString);
		}

		return (trim($sString));
	}
	
	public static function check_utf8($str) { 
		$len = strlen($str); 
		for($i = 0; $i < $len; $i++){
			$c = ord($str[$i]); 
			if ($c > 128) { 
				if (($c > 247)) return false; 
				elseif ($c > 239) $bytes = 4; 
				elseif ($c > 223) $bytes = 3; 
				elseif ($c > 191) $bytes = 2; 
				else return false; 
				if (($i + $bytes) > $len) return false; 
				while ($bytes > 1) { 
					$i++; 
					$b = ord($str[$i]); 
					if ($b < 128 || $b > 191) return false; 
					$bytes--; 
				}
			}
		} 
		return true; 
	}

	public static function clearUrl($p_sUrl = '')
	{
		//echo 'url = '.htmlspecialchars($sUrl).' , '.$sUrl.'<br>';
		
		$sUrl = trim((string)($p_sUrl));
		
		$sUrl = self::removeSigns($sUrl);
		
		$sUrl = preg_replace('~\%[0-9a-fA-F]{2}~', '', $sUrl);
		$sUrl = preg_replace('~\/+~', '', $sUrl);
		//$sUrl = preg_replace('~\%2F~', '', $sUrl);
		
		/*
		for($i=0; $i<255; $i++)
		{
			echo $i.', '.chr($i).' = '.urlencode(chr($i)).'<br>';
		}
		*/
		
		return ($sUrl);
	}
	
	/*
	public static function stripAllSlaches($p_sString = '')
	{
		$sString = ((string)($p_sString));
		
		$sString = preg_replace('~\\\+~', '', $sString);
		
		return ($sString);
	}
	*/
	
	public static function disableMagicQuotes($p_sString = '') // uzywac tylko do $_GET, $_POST and $_COOKIE
	{
		$sString = ((string)($p_sString));
		
		if (TRUE == function_exists('get_magic_quotes_gpc') && 1 == get_magic_quotes_gpc())
		{
			$mqs = strtolower(ini_get('magic_quotes_sybase'));

			if (TRUE == empty($mqs) || 'off' == $mqs)
			{
				// stripslashes $_GET, $_POST and $_COOKIE
				$sString = stripslashes($sString);
			}
			else
			{
				// str_replace("''", "'", ...) $_GET, $_POST, $_COOKIE
				$sString = str_replace("''", "'", $sString);
			}
		}
		
		// nie robimy nic
		return ($sString);
	}
	
	public static function stripAllSlashes($p_sText)
	{
		while(strchr($p_sText,'\\'))
		{
			//$p_sText = preg_replace(array('/\x5C(?!\x5C)/u', '/\x5C\x5C/u'), array('','\\'), $p_sText);
			$p_sText = stripslashes($p_sText);
		}
		
		return ($p_sText);
	}

	public static function sizer($p_iValue, $p_sEnforce = NULL)
	{
		$iValue = ((int)($p_iValue));
		$sEnforce = trim((string)($p_sEnforce));

		$aSizes = array('b', 'KB', 'MB', 'GB', 'TB', 'PB');

		$iSizeIndex = 0; $iEnforcedIndex = 0;

		if ($sEnforce != 'b')
		{
			if ($sEnforce)
			{
				$iEnforcedIndex = array_search($sEnforce, $aSizes);
			}

			while ($iValue > 1024 || $iSizeIndex < $iEnforcedIndex)
			{
				if (round(($iValue / 1024), 2) <= round(0, 2)) { break; }
				$iValue = $iValue / 1024;
				$iSizeIndex++;
			}
		}
		
		//echo '$iValue = '.$iValue.'<br>';
		
		return (sprintf("%.2f ".$aSizes[$iSizeIndex], $iValue));
	}

	public static function winiso($p_sText)
	{
		return(strtr($p_sText, "\xA5\x8C\x8F\xB9\x9C\x9F", "\xA1\xA6\xAC\xB1\xB6\xBC"));
	}

	public static function plUCFirst($p_sStr = '', $p_sEnc = NULL)
	{
		if($p_sEnc === NULL) { $p_sEnc = mb_internal_encoding(); }
		return (mb_strtoupper(mb_substr($p_sStr, 0, 1, $p_sEnc), $p_sEnc).mb_substr($p_sStr, 1, (mb_strlen($p_sStr, $p_sEnc)-1), $p_sEnc));
	}

	public static function plLCFirst($p_sStr = '', $p_sEnc = NULL)
	{
		if($p_sEnc === NULL) { $p_sEnc = mb_internal_encoding(); }
		return (mb_strtolower(mb_substr($p_sStr, 0, 1, $p_sEnc), $p_sEnc).mb_substr($p_sStr, 1, (mb_strlen($p_sStr, $p_sEnc)-1), $p_sEnc));
	}

	public static function strToUpperPl($p_sStr = '', $p_sEnc = NULL)
	{
		if($p_sEnc === NULL) { $p_sEnc = mb_internal_encoding(); }
		$sStr = mb_strtoupper($p_sStr, $p_sEnc);
		return($sStr);
	}

	public static function strToLowerPl($p_sStr = '', $p_sEnc = NULL)
	{
		if($p_sEnc === NULL) { $p_sEnc = mb_internal_encoding(); }
		$p_sStr = mb_strtolower($p_sStr, $p_sEnc);
		return($p_sStr);
	}

	public static function removePl($p_sStr = '', $p_sEnc = NULL)
	{
		if($p_sEnc === NULL) { $p_sEnc = mb_internal_encoding(); }
		return(str_replace(array("Ę","Ó","Ą","Ś","Ł","Ż","Ź","Ć","Ń","ę","ó","ą","ś","ł","ż","ź","ć","ń"), array("E","O","A","S","L","Z","Z","C","N","e","o","a","s","l","z","z","c","n"), $p_sStr));
	}

	public static function getSubString($p_sString, $p_iStrLenght, $p_sToAdd = '', $p_sSign = ' ')
	{
		// usuwamy wszystkie powielone spacje
		$sString = trim(preg_replace("/[ ]+/isx", " ", $p_sString));
		$iStrLenght = ((int)($p_iStrLenght));
		
		if (mb_strlen($sString) > $iStrLenght)
		{
			// obcinamy string do jego najkrotszej wymaganej dlugosci
			$sString = trim(trim(mb_substr($sString, 0, $iStrLenght)), $p_sSign);

			if (mb_strlen($sString) == $iStrLenght)
			{
				// obcinamy string do jego ostatnio znalezionej spacji (slowa)
				//  - jesli nie znaleziono spacji zwracamy obciety standardowo string substring-iem
				if (($iLastChar = mb_strrpos($sString, $p_sSign)) !== false)
				{
					// obcinamy string do jego ostatniej spacji
					$sString = trim(mb_substr($sString, 0, $iLastChar));
				}
			}
			
			// zwracamy string tylko jak ma wiecej niz 2 znaki
			if (mb_strlen($sString) > 2)
			{
				$sString.=$p_sToAdd;
			}
			else
			{
				$sString = '';
			}
		}
		return ($sString);
	}

	public static function prepareMetaDescription($p_sValue = '')
	{
		if (!self::$_bPreparedMetaDescription)
		{
			//$sMetaDescription = trim(trim(preg_replace("/(^.{1,2}[ ])|([ ].{1,2}$)/", " ",self::removeSigns(self::getSubString($p_sValue, 200))),','));
			
			$sValue = trim($p_sValue);
			$sValue = trim($sValue, ',');
			$sValue = trim($sValue, '.');
			$sValue = preg_replace("/(^.{1,2}[ ])|([ ].{1,2}$)/", " ", $sValue); // usuwamy wszystkie slowa 1 i 2 literowe z poczatku i konca
			$sValue = trim($sValue, ',');
			$sValue = trim($sValue, '.');

			$MetaDescription = self::removeSigns(self::getSubString($sValue, 250));
			$MetaDescription = str_replace(array('"',"'"), '', $MetaDescription);
			$MetaDescription = trim($MetaDescription);
			
			self::$_bPreparedMetaDescription = TRUE;

			return ($MetaDescription);
		}
	}

	public static function prepareMetaTitle($p_sValue = '', $p_sPortalName = '', $p_sText = '', $p_bShort = FALSE)
	{
		if (!self::$_bPreparedMetaTitle)
		{
			$sText = trim($p_sText);
			$sMetaTitle = trim($p_sValue);
			$sPortalName = trim($p_sPortalName);
			$bShort = ((bool)($p_bShort));

			$sMetaTitle = trim(self::removeSigns($sMetaTitle));

			if ($bShort == TRUE) { $sMetaTitle = trim(self::getSubString($sMetaTitle, 80)); }
			//$sMetaTitle = trim(trim(preg_replace("/[,]+/", ",", preg_replace("/\s+((.{1,2}))$/", ",", $sMetaTitle)), ','));
			$sMetaTitle = trim(trim(preg_replace("/[,]+/", ",", preg_replace("/\s+((\d{1,2})|(w)|(na)|(do)|(od)|(a)|(u)|(o)|(i)|(z))$/", ",", $sMetaTitle)), ','));
			//$sMetaTitle = trim(trim(preg_replace("/[,]+/", ",", preg_replace("/\s+((.{1,2}))$/", ",", $sMetaTitle)), ','));
			$sMetaTitle = str_replace(array('"',"'"), '', $sMetaTitle);
			$sMetaTitle = trim($sMetaTitle);

			// obsluga sortowania, stronicowania, dodatkowych parametrow tytulu
			$sMetaTitle .= ((strlen($sText) > 0) ? ' '.$sText : '');
			
			// dodajemy nazwe portalu
			if (strlen($sPortalName) > 0 && strpos($sMetaTitle, $sPortalName) === FALSE && strlen($sMetaTitle) < 80)
			{
				$sMetaTitle = trim($sMetaTitle, '.');
				$sMetaTitle = $sMetaTitle.' &#187; '.self::removeSigns($sPortalName);
			}

			//$sMetaTitle = self::plUCFirst($sMetaTitle); // nie moze byc tego ze wzgledu na 'eWydania'

			self::$_bPreparedMetaTitle = TRUE;
			
			return ($sMetaTitle);
		}
	}

	public static function prepareMetaKeywords($p_sValue = '', $p_sPortalName = '')
	{
		if (!self::$_bPreparedMetaKeywords)
		{
			$sKeywords = trim($p_sValue);
			$sPortalName = trim($p_sPortalName);

			if (strlen($sPortalName) > 0)
			{
				$sKeywords = $sKeywords.','.preg_replace("/\s+/", " ", $sPortalName);
			}

			//$aSearch = array("!","@","#","$","%","^","&","*","(",")","-","_","+","=","{","}","[","]","<",">","?","/","\\","~");
			//$sKeywords = str_replace($_asearch, "", $sKeywords);

			$sKeywords = preg_replace("/[\.]+|[-]+/", ",", $sKeywords);
			$sKeywords = preg_replace("~[^a-zA-Z0-9,ęóąśłżźćńĘÓĄŚŁŻŹĆŃ\s]+~", "", $sKeywords);

			$sKeywords = preg_replace("/\s+/", " ", $sKeywords);
			$sKeywords = preg_replace("/[,]+/", ",", $sKeywords);
			$sKeywords = preg_replace("/(^.{1,2}[,])|([,].{1,2}[,])|([,].{1,2}$)/", ",", $sKeywords);
			$sKeywords = trim(trim($sKeywords), ',');
			$sKeywords = implode(",", array_unique(explode(",", self::strToLowerPl($sKeywords, 'utf-8')), SORT_STRING));
			
			$sKeywords = self::getSubString($sKeywords, 200, ',');

			self::$_bPreparedMetaKeywords = TRUE;

			return ($sKeywords);
		}
	}
	
	public static function prepareMetaTags($p_sValue = '')
	{
		$sValue = trim((string)($p_sValue));
		
		$sMetaTag = trim(self::removeSigns($sValue));
		$sMetaTag = trim(self::getSubString($sMetaTag, 80));
		$sMetaTag = trim(str_replace(array('"',"'"), '', $sMetaTag));
		
		return($sMetaTag);
	}

	public static function prepareNBValue($p_fValue, $p_iVatRate, $p_iPercentRabat = 0, $p_sNettoBrutto = 'brutto', $p_fMultiplier = 1)
	{
		$iVatRate = round(((int)($p_iVatRate)), 0);
		$fValue = round($p_fValue, 2);
		$fMultiplier = ((real)($p_fMultiplier));
		$aPrices = array();
		$aPrices['netto'] = $aPrices['vatvalue'] = $aPrices['brutto'] = 0;

		switch($p_sNettoBrutto)
		{
			case 'netto':	//od netto (np dla firm - podmiot gospodarczy) - podana wartosc realValue jest wartoscia netto
					$aPrices['netto'] = $fValue;

					//uwzgledniamy procentowe rabaty (jesli sa)
					$aPrices['netto'] = round(($aPrices['netto'] - ($aPrices['netto'] * $p_iPercentRabat / 100)), 2);
					$aPrices['netto'] = round(($aPrices['netto'] * $fMultiplier), 2);

					//obliczamy vat i brutto
					$aPrices['vatvalue'] = round(($aPrices['netto'] * ($iVatRate / 100)), 2);
					$aPrices['brutto'] = round(($aPrices['netto'] + $aPrices['vatvalue']), 2);
					break;
				
			case 'brutto':	//od brutto (np prywatny - kontrahent finalny) - podana wartosc realValue jest wartoscia brutto
			default:		//od brutto defaultowo liczymy
					$aPrices['brutto'] = $fValue;

					//uwzgledniamy procentowe rabaty (jesli sa)
					$aPrices['brutto'] = round(($aPrices['brutto'] - ($aPrices['brutto'] * $p_iPercentRabat / 100)), 2);
					$aPrices['brutto'] = round(($aPrices['brutto'] * $fMultiplier), 2);

					//obliczamy wartosc vat i netto
					$aPrices['netto'] = round(($aPrices['brutto'] / (1 + ($iVatRate / 100))), 2);
					$aPrices['vatvalue'] = round(($aPrices['brutto'] - $aPrices['netto']), 2);
					break;
		}

		return ($aPrices);
	}
	
	// metoda liczy rabat wartosciowy od kwoty brutto / netto z uwzglednieniem roznych stawek vat
	//  (np wartosc razem netto/vat/brutto w zamowieniu po odliczeniu bonu platniczego)
	public static function getDiscount($p_fNetto, $p_fBrutto, $p_fDiscount = 0, $p_sNettoBrutto = 'brutto')
	{
		$fNetto = ((real)($p_fNetto));
		$fBrutto = ((real)($p_fBrutto));
		$fDiscount = ((real)($p_fDiscount));
		
		$aPrices['netto'] = $fNetto;
		$aPrices['brutto'] = $fBrutto;
		$aPrices['vatvalue'] = 0;
		
		switch($p_sNettoBrutto)
		{
			case 'netto':	//od netto (np dla firm - podmiot gospodarczy) - podana wartosc realValue jest wartoscia netto
				$fPercent = 0;
				if ($fDiscount > 0)
				{
					$fPercent = 100 * $fDiscount / $aPrices['brutto'];
					$aPrices['netto'] = round(($aPrices['netto'] - $fDiscount), 2);
					if ($fPercent > 100) { $fPercent = 100; }
					if ($fPercent < 0) { $fPercent = 0; }
				}
				
				if ($aPrices['netto'] < 0) { $aPrices['netto'] = 0; }

				$aPrices['brutto'] = round(($aPrices['brutto'] - ($aPrices['brutto'] * 1 / $fPercent / 100)), 2);
				
				if ($aPrices['brutto'] < 0) { $aPrices['brutto'] = 0; }
				
				$aPrices['vatvalue'] = round(($aPrices['brutto'] - $aPrices['netto']), 2);
				break;
				
			case 'brutto':	//od brutto (np prywatny - kontrahent finalny) - podana wartosc realValue jest wartoscia brutto
			default:		//od brutto defaultowo liczymy
				$fPercent = 0;
				if ($fDiscount > 0)
				{
					$fPercent = 100 * $fDiscount / $aPrices['brutto'];
					$aPrices['brutto'] = round(($aPrices['brutto'] - $fDiscount), 2);
					if ($fPercent > 100) { $fPercent = 100; }
					if ($fPercent < 0) { $fPercent = 0; }
				}
				
				if ($aPrices['brutto'] < 0) { $aPrices['brutto'] = 0; }
				
				$aPrices['netto'] = round(($aPrices['netto'] - ($aPrices['netto'] * $fPercent / 100)), 2);
				
				if ($aPrices['netto'] < 0) { $aPrices['netto'] = 0; }
				
				$aPrices['vatvalue'] = round(($aPrices['brutto'] - $aPrices['netto']), 2);
				break;
		}
		
		return ($aPrices);
	}
	
	public static function prepareValue($p_rValue, $p_sCurrency = '', $p_bChangeDot = FALSE)
	{
		$sCurrency = trim((string)($p_sCurrency));
		$bChangeDot = ((bool)($p_bChangeDot));
		
		$sValue = sprintf("%01.2f", ((real)($p_rValue)));
		if ($bChangeDot == TRUE) { $sValue = str_replace('.', ',', $sValue); }
		$sValue .= (($sCurrency) ? ' '.$sCurrency : '');
		
		return ($sValue);
	}

	public static function preparePhoneNumber($p_sPhoneNumber)
	{
		$sPhoneNumber = preg_replace(array("~(^00)~", "~(^0+)~", "~(^[\+]+)~", "~\D+~"), array('+', '', '00', ''), trim($p_sPhoneNumber));
		
		if(strlen($sPhoneNumber) > 0 && substr($sPhoneNumber, 0, 1) !== '0')
		{
			$sPhoneNumber = '0'.$sPhoneNumber;
		}
		
		if (strlen($sPhoneNumber) <= 6) { $sPhoneNumber = ''; }

		return($sPhoneNumber);
	}

	public static function prepareNIP($p_sNIP)
	{
		$sNIP = trim(preg_replace("~\D+~", "", ((string)($p_sNIP))));
		$sNIP = preg_replace("~(\d{3})(\d{3})(\d{2})(\d{2})~","\\1-\\2-\\3-\\4", substr($sNIP, 0, 10));
		return ($sNIP);
	}
	
	public static function prepareZipPL($p_sZip)
	{
		$sZip = trim(preg_replace("~\D+~", "", ((string)($p_sZip))));
		$sZip = preg_replace("~(\d{2})(\d{3})~","\\1-\\2", substr($sZip, 0, 5));
		return ($sZip);
	}
	
	// metoda przygotowuje tekst:
	// - laczy krotkie slowa w/na/do/etc etc z nastepnym wyrazem, tak aby zawijalo tekst poprawnie
	public static function prepareText($p_sText = '')
	{
		$sText = trim((string)($p_sText));
		
		if (strlen($sText) > 0)
		{
			$aRegexp = array(
								array('from' => "~\s+(w|na|do|od|i|o|)\s+(.+?)~isx", "to" => " $1&nbsp;$2")
						);
			
			$sText = preg_replace($aRegexp[0]['from'], $aRegexp[0]['to'], $sText);
		}
		
		return ($sText);
	}
	
	// metoda przygotowuje wyliczanke z tekstu z przecinkami zastepujac ostatni na lub/i
	// np: ala,ola,ela,ania => ala, ola, ela lub/i ania
	public static function prepareTextOrAnd($p_sText = '', $p_sOrAndText = 'lub')
	{
		$sText = trim((string)($p_sText));
		$sOrAndText = ' '.trim((string)($p_sOrAndText)).' ';
		
		if (strlen($sText) > 0 && strlen($sOrAndText) > 0)
		{
			if (($iSign = strrpos($sText, ',', -1)) !== FALSE)
			{
				$sText = substr_replace($sText, $sOrAndText, $iSign, 1);
				$sText = str_replace(',', ', ', $sText);
			}
		}
		
		return ($sText);
	}

	public static function validEmail($p_sEmail)
	{
		$sEmail = trim((string)($p_sEmail));

		$bIsValid = TRUE;
		$iAtIndex = strrpos($sEmail, "@");
		
		if (is_bool($iAtIndex) && !$iAtIndex)
		{
			$bIsValid = FALSE;
		}
		else
		{
			$sDomain = substr($sEmail, ($iAtIndex + 1));
			$sLocal = substr($sEmail, 0, $iAtIndex);
			$iLocalLen = strlen($sLocal);
			$iDomainLen = strlen($sDomain);
			
			if ($iLocalLen < 1 || $iLocalLen > 64)
			{
				// local part length exceeded
				$bIsValid = FALSE;
			}
			else if ($iDomainLen < 1 || $iDomainLen > 255)
			{
				// domain part length exceeded
				$bIsValid = FALSE;
			}
			else if ($sLocal[0] == '.' || $sLocal[($iLocalLen - 1)] == '.')
			{
				// local part starts or ends with '.'
				$bIsValid = FALSE;
			}
			else if (preg_match('/\\.\\./', $sLocal))
			{
				// local part has two consecutive dots
				$bIsValid = FALSE;
			}
			else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $sDomain))
			{
				// character not valid in domain part
				$bIsValid = FALSE;
			}
			else if (preg_match('/\\.\\./', $sDomain))
			{
				// domain part has two consecutive dots
				$bIsValid = FALSE;
			}
			else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\", "", $sLocal)))
			{
				// character not valid in local part unless
				// local part is quoted
				if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\", "", $sLocal)))
				{
					$bIsValid = FALSE;
				}
			}

			/*
			if ($bIsValid && !(checkdnsrr($sDomain, "MX") || checkdnsrr($sDomain, "A")))
			{
				// domain not found in DNS
				$bIsValid = FALSE;
			}
			*/

			/*
			if(eregi("^[a-zA-Z0-9_]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$]", $sEmail))
			{
				$bIsValid = FALSE;
			}
			*/
			
			if(preg_match("~^[a-zA-Z0-9_]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$]~", $sEmail))
			{
				$bIsValid = FALSE;
			}

			/*
			list($Username, $sDomain) = split("@", $sEmail);

			if(@fsockopen($sDomain, 25, $errno, $errstr, 30))
			{
				$bIsValid = TRUE;
			}
			else
			{
				$bIsValid = FALSE;
			}
			*/

			/*
			if(getmxrr($sDomain, $MXHost))
			{
				$bIsValid = TRUE;
			}
			else
			{
				if(@fsockopen($sDomain, 25, $errno, $errstr, 30))
				{
					$bIsValid = TRUE;
				}
				else
				{
					$bIsValid = FALSE;
				}
			}
			*/

			$bIsValid = (($bIsValid) ? self::checkEmail($sEmail) : $bIsValid);
		}
		
		return ($bIsValid);
	}

	public static function checkEmail($p_sEmail)
	{
		$sEmail = trim((string)($p_sEmail));
		
		//$a = eregi("@", $sEmail);
		//$b = eregi(".", $sEmail); $ile_d = 0; $poz_e = 0;
		
		$a = preg_match("~@~", $sEmail);
		$b = preg_match("~\.~", $sEmail); $ile_d = 0; $poz_e = 0;


		for($i = 0; $i < strlen($sEmail); $i++)
		{
			$c = substr($sEmail, $i, 1);
			if ($c == "@") { $poz_d = $i; $ile_d += 1; }
			if ($c == ".") { $poz_e = $i; }
		}

		$c = strlen($sEmail);

		if ($poz_d < strlen($sEmail) && $poz_d > 0) { $f = 1; } else { $f = 0; }
		if ($poz_e < strlen($sEmail) && $poz_e > $poz_d) { $g = 1; } else { $g = 0; }
		if (($poz_e - $poz_d) > 1) { $i = 1; } else { $i = 0; }

		$pl = substr($sEmail, (strlen($sEmail) - 3), 3);

		if ($ile_d == 1) { $j = 1; } else { $j = 0; }
		
		if ($a && $b && $c && $f && $g && $i && $j && !preg_match("~\s+~", $sEmail) && !preg_match('~(@\.)~', $sEmail))
		{
			return TRUE;
		}

		return FALSE;
	}
	
	public static function checkNIP($p_sNip)
	{
		$sNip = trim(preg_replace("~\D+~", "", $p_sNip));

		if (strlen($sNip) != 10)
		{
			return false;
		}

		$aSteps = array(6, 5, 7, 2, 3, 4, 5, 6, 7);

		$iSum = 0;

		for ($iI = 0; $iI < 9; $iI++)
		{
			$iSum += $aSteps[$iI] * $sNip[$iI];
		}

		$iModulo = $iSum % 11;

		$iControlNr = ($iModulo == 10) ? 0 : $iModulo;
		
		if ($iControlNr == $sNip[9])
		{
			return true;
		}

		return false;
	}

	public static function getHeaders($p_iHeaderCode = 301)
	{
		$aHeaders = array (
							100 => "HTTP/1.1 100 Continue",
							101 => "HTTP/1.1 101 Switching Protocols",
							200 => "HTTP/1.1 200 OK",
							201 => "HTTP/1.1 201 Created",
							202 => "HTTP/1.1 202 Accepted",
							203 => "HTTP/1.1 203 Non-Authoritative Information",
							204 => "HTTP/1.1 204 No Content",
							205 => "HTTP/1.1 205 Reset Content",
							206 => "HTTP/1.1 206 Partial Content",
							300 => "HTTP/1.1 300 Multiple Choices",
							301 => "HTTP/1.1 301 Moved Permanently",
							302 => "HTTP/1.1 302 Found",
							303 => "HTTP/1.1 303 See Other",
							304 => "HTTP/1.1 304 Not Modified",
							305 => "HTTP/1.1 305 Use Proxy",
							307 => "HTTP/1.1 307 Temporary Redirect",
							400 => "HTTP/1.1 400 Bad Request",
							401 => "HTTP/1.1 401 Unauthorized",
							402 => "HTTP/1.1 402 Payment Required",
							403 => "HTTP/1.1 403 Forbidden",
							404 => "HTTP/1.1 404 Not Found",
							405 => "HTTP/1.1 405 Method Not Allowed",
							406 => "HTTP/1.1 406 Not Acceptable",
							407 => "HTTP/1.1 407 Proxy Authentication Required",
							408 => "HTTP/1.1 408 Request Time-out",
							409 => "HTTP/1.1 409 Conflict",
							410 => "HTTP/1.1 410 Gone",
							411 => "HTTP/1.1 411 Length Required",
							412 => "HTTP/1.1 412 Precondition Failed",
							413 => "HTTP/1.1 413 Request Entity Too Large",
							414 => "HTTP/1.1 414 Request-URI Too Large",
							415 => "HTTP/1.1 415 Unsupported Media Type",
							416 => "HTTP/1.1 416 Requested range not satisfiable",
							417 => "HTTP/1.1 417 Expectation Failed",
							500 => "HTTP/1.1 500 Internal Server Error",
							501 => "HTTP/1.1 501 Not Implemented",
							502 => "HTTP/1.1 502 Bad Gateway",
							503 => "HTTP/1.1 503 Service Unavailable",
							504 => "HTTP/1.1 504 Gateway Time-out"
							);

		return ((isset($aHeaders[$p_iHeaderCode]) ? $aHeaders[$p_iHeaderCode] : $aHeaders[301]));
	}

	public static function clean($p_sStream = '', array $p_aOptions = NULL)
	{
		/* OPCJE METODY
		 *
		 * NOPHP         : domyslnie TRUE : usuwanie skryptow php
		 * NOCOMMENTS    : domyslnie TRUE : usuwanie komentarzy html / php / js / css
		 * NONEWLINES    : domyslnie TRUE : usuwanie lamania lini
		 * NOTABS        : domyslnie TRUE : usuwanie tabulatorow
		 * NOSPACES      : domyslnie TRUE : usuwanie spacji
		 *
		 * SIGNCOMMENTS  : domyslnie ''   : znak zamiany komentarzy
		 * SIGNTAB       : domyslnie ' '  : znak zamiany tabulatorow
		 * SIGNNEWLINE   : domyslnie ' '  : znak zamiany nowych linii
		 * 
		 * INSERTNEWLINE : domyslnie ''   : znaki po ktorych wymuszamy wstawienie nowej linii
		 *
		 */

		//echo 'aaaa = '.$p_sStream; die();

		$aOptions['SIGNCOMMENTS'] = ((isset($p_aOptions['SIGNCOMMENTS'])) ? $p_aOptions['SIGNCOMMENTS'] : '');
		$aOptions['SIGNTAB'] = ((isset($p_aOptions['SIGNTAB'])) ? $p_aOptions['SIGNTAB'] : ' ');
		$aOptions['SIGNNEWLINE'] = ((isset($p_aOptions['SIGNNEWLINE'])) ? $p_aOptions['SIGNNEWLINE'] : ' ');
		
		// usuwamy wszystkie komentarze i niechciane tagi
		$aRegexpFrom = array(
			'~\<\!.*-\>~s',            // komentarze html
			'~\<\?(php|=)*.*\?\>~s',   // wszystkie skrypty php
			'~^[^\<\!:.+]\/\/.*?$~ms', // wszystkie komentarze liniowe php, js (uwaga! bardzo bardzo obciaza)
			'~/\*.*?\*/~s',            // wszystkie komentarze php, js
			'~(\\r+|\\n+)~',           // wszystkie lamiania linii oraz wciecia
			'~\\t+~',                  // wszystkie wciecia
			'~\s{2,}~'                 // wszystkie powielone spacje
		);

		$aRegexpTo = array(
			$aOptions['SIGNCOMMENTS'],
			' ',
			$aOptions['SIGNCOMMENTS'],
			$aOptions['SIGNCOMMENTS'],
			$aOptions['SIGNNEWLINE'],
			$aOptions['SIGNTAB'],
			' '
		);

		if (isset($p_aOptions['NOPHP']) && $p_aOptions['NOPHP'] === FALSE)
		{
			unset($aRegexpFrom[1], $aRegexpTo[1]);
		}

		if (isset($p_aOptions['NOCOMMENTS']) && $p_aOptions['NOCOMMENTS'] === FALSE)
		{
			unset($aRegexpFrom[0], $aRegexpTo[0], $aRegexpFrom[2], $aRegexpTo[2], $aRegexpFrom[3], $aRegexpTo[3]);
		}

		if (isset($p_aOptions['NONEWLINES']) && $p_aOptions['NONEWLINES'] === FALSE)
		{
			unset($aRegexpFrom[4], $aRegexpTo[4]);
		}

		if (isset($p_aOptions['NOTABS']) && $p_aOptions['NOTABS'] === FALSE)
		{
			unset($aRegexpFrom[5], $aRegexpTo[5]);
		}

		if (isset($p_aOptions['NOSPACES']) && $p_aOptions['NOSPACES'] === FALSE)
		{
			unset($aRegexpFrom[6], $aRegexpTo[6]);
		}

		$sString = trim(preg_replace($aRegexpFrom, $aRegexpTo, $p_sStream));
		
		if (isset($p_aOptions['INSERTNEWLINE']))
		{
			$p_aOptions['INSERTNEWLINE'] = trim($p_aOptions['INSERTNEWLINE']);
			
			if (strlen($p_aOptions['INSERTNEWLINE']) > 0)
			{
				$sString = str_replace($p_aOptions['INSERTNEWLINE'], $p_aOptions['INSERTNEWLINE']."\r\n", $sString);
			}
		}

		//$sString = trim($p_sStream);

		//echo 'aaaa = '.$sString; die();

		return ($sString);
	}
	
	public static function getArrayByIndex(array $p_aData = NULL, $p_sTargetIndex = '')
	{
		$aData = ((is_array($p_aData)) ? $p_aData : NULL);
		$sTargetIndex = trim((string)($p_sTargetIndex));

		if (is_array($aData) && sizeof($aData) > 0)
		{
			$aTargetArray = array();
			
			foreach($aData as $aDataRow)
			{
				$aTargetArray[] = $aDataRow[$sTargetIndex];
			}
			
			return ($aTargetArray);
		}
		
		return NULL;
	}
	
	public static function getMimeContentType($p_sExtension = NULL, $p_sMimeType = NULL)
	{
		$aMimeTypes = array
						(
							"ai" => "application/postscript",
							"aif" => "audio/x-aiff",
							"aifc" => "audio/x-aiff",
							"aiff" => "audio/x-aiff",
							"asc" => "text/plain",
							"au" => "audio/basic",
							"avi" => "video/x-msvideo",
							"bcpio" => "application/x-bcpio",
							"bin" => "application/octet-stream",
							"c" => "text/plain",
							"cc" => "text/plain",
							"ccad" => "application/clariscad",
							"cdf" => "application/x-netcdf",
							"class" => "application/octet-stream",
							"cpio" => "application/x-cpio",
							"cpt" => "application/mac-compactpro",
							"csh" => "application/x-csh",
							"css" => "text/css",
							"csv" => "text/csv",
							"dcr" => "application/x-director",
							"dir" => "application/x-director",
							"dms" => "application/octet-stream",
							"doc" => "application/msword",
							"drw" => "application/drafting",
							"dvi" => "application/x-dvi",
							"dwg" => "application/acad",
							"dxf" => "application/dxf",
							"dxr" => "application/x-director",
							"eps" => "application/postscript",
							"etx" => "text/x-setext",
							"exe" => "application/octet-stream",
							"ez" => "application/andrew-inset",
							"f" => "text/plain",
							"f90" => "text/plain",
							"fli" => "video/x-fli",
							"gif" => "image/gif",
							"gtar" => "application/x-gtar",
							"gz" => "application/x-gzip",
							"h" => "text/plain",
							"hdf" => "application/x-hdf",
							"hh" => "text/plain",
							"hqx" => "application/mac-binhex40",
							"htm" => "text/html",
							"html" => "text/html",
							"ice" => "x-conference/x-cooltalk",
							"ief" => "image/ief",
							"iges" => "model/iges",
							"igs" => "model/iges",
							"ips" => "application/x-ipscript",
							"ipx" => "application/x-ipix",
							"jpe" => "image/jpeg",
							"jpeg" => "image/jpeg",
							"jpg" => "image/jpeg",
							"js" => "application/x-javascript",
							"json" => "application/json",
							"kar" => "audio/midi",
							"latex" => "application/x-latex",
							"lha" => "application/octet-stream",
							"lsp" => "application/x-lisp",
							"lzh" => "application/octet-stream",
							"m" => "text/plain",
							"man" => "application/x-troff-man",
							"me" => "application/x-troff-me",
							"mesh" => "model/mesh",
							"mid" => "audio/midi",
							"midi" => "audio/midi",
							"mif" => "application/vnd.mif",
							"mime" => "www/mime",
							"mov" => "video/quicktime",
							"movie" => "video/x-sgi-movie",
							"mp2" => "audio/mpeg",
							"mp3" => "audio/mpeg",
							"mpe" => "video/mpeg",
							"mpeg" => "video/mpeg",
							"mpg" => "video/mpeg",
							"mpga" => "audio/mpeg",
							"ms" => "application/x-troff-ms",
							"msh" => "model/mesh",
							"nc" => "application/x-netcdf",
							"oda" => "application/oda",
							"pbm" => "image/x-portable-bitmap",
							"pdb" => "chemical/x-pdb",
							"pdf" => "application/pdf",
							"pgm" => "image/x-portable-graymap",
							"pgn" => "application/x-chess-pgn",
							"png" => "image/png",
							"pnm" => "image/x-portable-anymap",
							"pot" => "application/mspowerpoint",
							"ppm" => "image/x-portable-pixmap",
							"pps" => "application/mspowerpoint",
							"ppt" => "application/mspowerpoint",
							"ppz" => "application/mspowerpoint",
							"pre" => "application/x-freelance",
							"prt" => "application/pro_eng",
							"ps" => "application/postscript",
							"qt" => "video/quicktime",
							"ra" => "audio/x-realaudio",
							"ram" => "audio/x-pn-realaudio",
							"ras" => "image/cmu-raster",
							"rgb" => "image/x-rgb",
							"rm" => "audio/x-pn-realaudio",
							"roff" => "application/x-troff",
							"rpm" => "audio/x-pn-realaudio-plugin",
							"rtf" => "text/rtf",
							"rtx" => "text/richtext",
							"scm" => "application/x-lotusscreencam",
							"set" => "application/set",
							"sgm" => "text/sgml",
							"sgml" => "text/sgml",
							"sh" => "application/x-sh",
							"shar" => "application/x-shar",
							"silo" => "model/mesh",
							"sit" => "application/x-stuffit",
							"skd" => "application/x-koan",
							"skm" => "application/x-koan",
							"skp" => "application/x-koan",
							"skt" => "application/x-koan",
							"smi" => "application/smil",
							"smil" => "application/smil",
							"snd" => "audio/basic",
							"sol" => "application/solids",
							"spl" => "application/x-futuresplash",
							"src" => "application/x-wais-source",
							"step" => "application/STEP",
							"stl" => "application/SLA",
							"stp" => "application/STEP",
							"sv4cpio" => "application/x-sv4cpio",
							"sv4crc" => "application/x-sv4crc",
							"swf" => "application/x-shockwave-flash",
							"t" => "application/x-troff",
							"tar" => "application/x-tar",
							"tcl" => "application/x-tcl",
							"tex" => "application/x-tex",
							"texi" => "application/x-texinfo",
							"texinfo" => "application/x-texinfo",
							"tif" => "image/tiff",
							"tiff" => "image/tiff",
							"tr" => "application/x-troff",
							"tsi" => "audio/TSP-audio",
							"tsp" => "application/dsptype",
							"tsv" => "text/tab-separated-values",
							"txt" => "text/plain",
							"unv" => "application/i-deas",
							"ustar" => "application/x-ustar",
							"vcd" => "application/x-cdlink",
							"vda" => "application/vda",
							"viv" => "video/vnd.vivo",
							"vivo" => "video/vnd.vivo",
							"vrml" => "model/vrml",
							"wav" => "audio/x-wav",
							"wrl" => "model/vrml",
							"xbm" => "image/x-xbitmap",
							"xlc" => "application/vnd.ms-excel",
							"xll" => "application/vnd.ms-excel",
							"xlm" => "application/vnd.ms-excel",
							"xls" => "application/vnd.ms-excel",
							"xlw" => "application/vnd.ms-excel",
							"xml" => "text/xml",
							"xpm" => "image/x-xpixmap",
							"xwd" => "image/x-xwindowdump",
							"xyz" => "chemical/x-pdb",
							"zip" => "application/zip"
						);
						
		if (isset($p_sExtension) && ($sMimeType = trim($aMimeTypes[$p_sExtension])))
		{
			return ($sMimeType);
		}
		
		if (isset($p_sMimeType) && ($sExtension = array_search($p_sMimeType, $aMimeTypes)))
		{
			return ($sExtension);
		}
		
		return('application/octet-stream');
	}
	
	public static function getFileExtension($p_sPathFile)
	{
		$aPathInfo = pathinfo($p_sPathFile);
		return (((isset($aPathInfo['extension']) ? $aPathInfo['extension'] : '')));
	}
	
	public static function checkFile($p_sPath = '', $p_sFile = '', $p_sExtension = '')
	{
		$sPath = trim((string)($p_sPath));				// sciezka do pliku do sprawdzenia
		$sFile = trim((string)($p_sFile));				// nazwa pliku bez rozszerzenia
		$sExtension = trim((string)($p_sExtension));	// rozszerzenie, wymuszenie sprawdzenia
		
		//echo 'aa = '.$sPath.$sFile.'.'.$sExtension.'<br>';
		
		$aTypes = array('png', 'jpg', 'gif', 'flv', 'swf', 'mp3', 'epub');
		
		if (strlen($sExtension) > 0)
		{
			$aTypes = array($sExtension);
		}
		
		foreach($aTypes as $sType)
		{
			if (file_exists($sPath.$sFile.'.'.$sType))
			{
				//echo 'aa = '.$sPath.$sFile.'.'.$sType.'<br>';
				return (array($sFile.'.'.$sType, $sType, $sPath.$sFile.'.'.$sType));
			}
		}
		
		return FALSE;
	}
	
	public static function parseText()
	{
		// $aArgs[0] : tekst do przygotowania (z miejscami do podmiany {$})
		// $aArgs[1] i nastepne : teksty do zastapienia w kolejnych punktach {$}
		
		if (is_array($aArgs = func_get_args()) && sizeof($aArgs) && isset($aArgs[0]) && strlen(($sText = trim($aArgs[0]))) > 0)
		{
			if (isset($aArgs[1]) && strlen($aArgs[1]) > 0)
			{
				array_shift($aArgs); // usuwamy z tablicy pierwszy rekord, ktory jest tekstem
				
				$sText = preg_replace("~\{\$\}~", $aArgs, $sText);
			}
			
			return ($sText);
		}
		
		return NULL;
	}
	
	public static function flexionVariety($p_iNumber = NULL, $p_sWord = NULL, $p_aEnds = array('ę','e','i'))
	{
		//echo '<pre>'.print_r(array($p_iNumber, $p_sWord, $p_aEnds), TRUE).'<pre>';
		
		if (isset($p_iNumber) && $p_sWord)
		{
			$iLastNum = ((int)(substr($p_iNumber, (strlen($p_iNumber)-1), 1)));
			$iLast2Num = ((int)(substr($p_iNumber, (strlen($p_iNumber)-2), 2)));
			
			switch (TRUE)
			{
				case ($p_iNumber == 1): // np: znaleziono 1 pozycj[ę], w koszyku 1 produkt
					$p_sWord .= $p_aEnds[0];
					break;

				case ($iLastNum >= 2 && $iLastNum <=4 && !in_array($iLast2Num, array(12, 13, 14))):  // np: znaleziono 2 pozycj[e], znaleziono 53 pozycj[e], w koszyku 3 produkty
					$p_sWord .= $p_aEnds[1];
					break;
					
				default:  // np: znaleziono 14 pozycj[i], w koszyku 13 produktow
					$p_sWord .= $p_aEnds[2];
					break;
			}
		}

		return ($p_sWord);
	}
	
	public static function arrayToJson(array $p_aData = NULL)
	{
		$aData = ((is_array($p_aData)) ? $p_aData : NULL);
		
		if (is_array($aData))
		{
			$iAssociative = count( array_diff( array_keys($aData), array_keys( array_keys( $aData )) ));
			
			$aConstruct = array();
			
			if ($iAssociative)
			{
				foreach($aData as $mKey => $mValue)
				{
					if (is_numeric($mKey))
					{
						$mKey = "key_$mKey";
					}
					
					$mKey = "\"".addslashes($mKey)."\"";

					if (is_array($mValue))
					{
						$mValue = self::arrayToJson($mValue);
					}
					else if (!is_numeric($mValue) || is_string($mValue))
					{
						$mValue = "\"".addslashes($mValue)."\"";
					}

					$aConstruct[] = "$mKey: $mValue";
				}

				$sResult = "{ " . implode(", ", $aConstruct) . " }";
			}
			else
			{
				foreach($aData as $mValue)
				{
					if (is_array($mValue))
					{
						$mValue = self::arrayToJson($mValue);
					}
					else if (!is_numeric($mValue) || is_string($mValue))
					{
						$mValue = "'".addslashes($mValue)."'";
					}

					$aConstruct[] = $mValue;
				}

				$sResult = "[ " . implode(", ", $aConstruct) . " ]";
			}

			return $sResult;
		}
	}
	
	public static function sortByLength($a,$b)
	{
		if($a == $b) return 0;
		return (strlen($a) > strlen($b) ? -1 : 1);
	}
	
	public static function BCBin2Dec($p_sInput='')
	{
		$sInput = trim((string)($p_sInput));
		
		$sOutput='0';
		
		if(preg_match("/^[01]+$/",$sInput))
		{
			for($i=0;$i<strlen($sInput);$i++)
				$sOutput=BCAdd(BCMul($sOutput,'2'),$sInput{$i});
		}
		
		$sOutput = ((string)($sOutput));
		
		//echo '$Output = '.$Output.'<br>';
		
		if (strpos($sOutput, '.') !== FALSE)
		{
			$sOutput = substr($sOutput, 0, strpos($sOutput, '.'));
		}
		
		return($sOutput);
	}
	
	public static function BCDec2Bin($p_iInput='')
	{
		$iInput = ((int)($p_iInput));
		
		$sOutput='';
		
		if(preg_match("/^\d+$/", $iInput))
		{
			while($iInput!='0')
			{
				$sOutput.=chr(48+($iInput{strlen($iInput)-1}%2));
				$iInput=BCDiv($iInput,'2');
			}
			
			$sOutput=strrev($sOutput);
		}
		
		return(($sOutput!='')?$sOutput:'0');
	}
	
	public static function stout($p_sFileName, $p_sPath, $p_sString, $p_blClose = FALSE)
	{
		if (!isset(self::$_aFilePointer[$p_sFileName]))
		{
			self::$_aFilePointer[$p_sFileName] = @fopen($p_sPath.$p_sFileName, "ab+");
		}

		if (!isset(self::$_aFilePointer[$p_sFileName])) { die('nie mozna utworzyc pliku: '.$p_sPath.$p_sFileName); }

		if (self::$_aFilePointer[$p_sFileName])
		{
			@fputs(self::$_aFilePointer[$p_sFileName], $p_sString, strlen($p_sString));

			if ($p_blClose == TRUE)
			{
				@fclose(self::$_aFilePointer[$p_sFileName]);
			}
		}
	}
	
	/**
	* version of sprintf for cases where named arguments are desired (php syntax)
	*
	* with sprintf: sprintf('second: %2$s ; first: %1$s', '1st', '2nd');
	*
	* with sprintfn: sprintfn('second: %second$s ; first: %first$s', array(
	*  'first' => '1st',
	*  'second'=> '2nd'
	* ));
	*
	* @param string $format sprintf format string, with any number of named arguments
	* @param array $args array of [ 'arg_name' => 'arg value', ... ] replacements to be made
	* @return string|false result of sprintf call, or bool false on error
	*/
	public static function sprintfn($format, array $args = array())
	{
		// map of argument names to their corresponding sprintf numeric argument value
		$arg_nums = array_slice(array_flip(array_keys(array(0 => 0) + $args)), 1);

		// find the next named argument. each search starts at the end of the previous replacement.
		for ($pos = 0; preg_match('/(?<=%)([a-zA-Z_]\w*)(?=\$)/', $format, $match, PREG_OFFSET_CAPTURE, $pos);)
		{
			$arg_pos = $match[0][1];
			$arg_len = strlen($match[0][0]);
			$arg_key = $match[1][0];

			// programmer did not supply a value for the named argument found in the format string
			if (! array_key_exists($arg_key, $arg_nums))
			{
				user_error("sprintfn(): Missing argument '${arg_key}'", E_USER_WARNING);
				return false;
			}

			// replace the named argument with the corresponding numeric one
			$format = substr_replace($format, $replace = $arg_nums[$arg_key], $arg_pos, $arg_len);
			$pos = $arg_pos + strlen($replace); // skip to end of replacement for next iteration
		}

		return vsprintf($format, array_values($args));
	}
	
	/**
	* version of sprintf for cases where named arguments are desired (python syntax)
	*
	* with sprintf: sprintf('second: %2$s ; first: %1$s', '1st', '2nd');
	*
	* with sprintfn: sprintfn('second: %(second)s ; first: %(first)s', array(
	*  'first' => '1st',
	*  'second'=> '2nd'
	* ));
	*
	* @param string $format sprintf format string, with any number of named arguments
	* @param array $args array of [ 'arg_name' => 'arg value', ... ] replacements to be made
	* @return string|false result of sprintf call, or bool false on error
	*/
	public static function sprintfnpy($format, array $args = array())
	{
		// map of argument names to their corresponding sprintf numeric argument value
		$arg_nums = array_slice(array_flip(array_keys(array(0 => 0) + $args)), 1);

		// find the next named argument. each search starts at the end of the previous replacement.
		for ($pos = 0; preg_match('/(?<=%)\(([a-zA-Z_]\w*)\)/', $format, $match, PREG_OFFSET_CAPTURE, $pos);)
		{
			$arg_pos = $match[0][1];
			$arg_len = strlen($match[0][0]);
			$arg_key = $match[1][0];

			// programmer did not supply a value for the named argument found in the format string
			if (! array_key_exists($arg_key, $arg_nums))
			{
				user_error("sprintfn(): Missing argument '${arg_key}'", E_USER_WARNING);
				return false;
			}

			// replace the named argument with the corresponding numeric one
			$format = substr_replace($format, $replace = $arg_nums[$arg_key] . '$', $arg_pos, $arg_len);
			$pos = $arg_pos + strlen($replace); // skip to end of replacement for next iteration
		}

		return vsprintf($format, array_values($args));
	}
	
	public static function getWeekStartDate($p_iYear, $p_iWeek, $p_iMoveDays = 0)
	{
		$iWeek = ((int)($p_iWeek));
		$iYear = ((int)($p_iYear));
		$iMoveDays = ((int)($p_iMoveDays));
		
		$iJan1 = mktime(0, 0, 0, 1, 1, $iYear);
		$iMondayOffset = (11-date('w', $iJan1)) % 7 - 3;
		$iDesiredMonday = strtotime(($iWeek-1).' weeks '.$iMondayOffset.' days', $iJan1) - ($iMoveDays * 3600 * 24);
		return $iDesiredMonday;
	}
	
	public static function getWeeks($p_iYear = 0, $p_iWeek = 0, $p_iMoveDays = 0)
	{
		$iYear = ((int)($p_iYear)); if ($iYear <= 0) { $iYear = date("Y", time()); }
		$iWeek = ((int)($p_iWeek));
		$iMoveDays = ((int)($p_iMoveDays));
		
		$iWeekEnd = (($iWeek > 0) ? $iWeek : 53);
		
		$aWeeks = array();
		
		for ($iWeekTmp = $iWeek; $iWeekTmp <= $iWeekEnd; $iWeekTmp++)
		{
			$iTimeStart = self::getWeekStartDate($iYear, $iWeekTmp, $iMoveDays);
			$iTimeEnd = strtotime('+7 days', ($iTimeStart - 1));
			$aWeeks[$iWeekTmp]['StartTime'] = $iTimeStart;
			$aWeeks[$iWeekTmp]['StartDate'] = date("d/m/Y", $iTimeStart);
			$aWeeks[$iWeekTmp]['EndTime'] = $iTimeEnd;
			$aWeeks[$iWeekTmp]['EndDate'] = date("d/m/Y", $iTimeEnd);
			$aWeeks[$iWeekTmp]['Week'] = $aWeeks[$iWeekTmp]['StartDate'].' - '.$aWeeks[$iWeekTmp]['EndDate'];
			$aWeeks[$iWeekTmp]['WeekNo'] = $iWeekTmp;
		}
		
		//echo '<pre>'.print_r(array(date("W", time()), $aWeeks), TRUE).'</pre>'; die();
		
		return ((($iWeek) ? $aWeeks[$iWeek] : $aWeeks));
	}
	
	// metoda tworzy timestamp z daty typu '02032010' lub '02/03/2010' (z separatorem) - schemat wejsciowy 'dd.mm.yyyy'
	public static function strDateToTime($p_sDate, $p_sSeparator)
	{
		$sDate = trim((string)($p_sDate));
		$sSeparator = trim((string)($p_sSeparator));
		
		if (strlen($sDate) > 0)
		{
			 if (strlen($sSeparator) > 0)
			 {
				 $aDate = explode($sSeparator, $sDate);
			 }
			 else
			 {
				 preg_match("~([0-9]{2})([0-9]{2})([0-9]{4})~", $sDate, $aDate);
				 array_shift($aDate);
			 }
			 
			 $iDay = ((int)($aDate[0]));
			 $iMonth = ((int)($aDate[1]));
			 $iYear = ((int)($aDate[2]));
			 
			 $iTime = mktime(0, 0, 0, $iMonth, $iDay, $iYear);
			 
			 return ($iTime);
		}
		
		return ($sDate);
	}
	
	public static function convertIsoUtf()
	{
		// uwaga, plik musi byc zapisany w utf do tego
		$aFromIso = array(
			iconv('utf-8', 'iso-8859-2', 'ę'),
			iconv('utf-8', 'iso-8859-2', 'ó'),
			iconv('utf-8', 'iso-8859-2', 'ą'),
			iconv('utf-8', 'iso-8859-2', 'ś'),
			iconv('utf-8', 'iso-8859-2', 'ł'),
			iconv('utf-8', 'iso-8859-2', 'ż'),
			iconv('utf-8', 'iso-8859-2', 'ź'),
			iconv('utf-8', 'iso-8859-2', 'ć'),
			iconv('utf-8', 'iso-8859-2', 'ń'),
			iconv('utf-8', 'iso-8859-2', 'Ę'),
			iconv('utf-8', 'iso-8859-2', 'Ó'),
			iconv('utf-8', 'iso-8859-2', 'Ą'),
			iconv('utf-8', 'iso-8859-2', 'Ś'),
			iconv('utf-8', 'iso-8859-2', 'Ł'),
			iconv('utf-8', 'iso-8859-2', 'Ż'),
			iconv('utf-8', 'iso-8859-2', 'Ź'),
			iconv('utf-8', 'iso-8859-2', 'Ć'),
			iconv('utf-8', 'iso-8859-2', 'Ń')
			);
		
		$aToUtf = array('ę', 'ó', 'ą', 'ś', 'ł', 'ż', 'ź', 'ć', 'ń', 'Ę', 'Ó', 'Ą', 'Ś', 'Ł', 'Ż', 'Ź', 'Ć', 'Ń');
	}
	
	public static function jednosci($wartosc)
	{
		$jednosci = '';
		
		switch($wartosc)
		{
			case 0: $jednosci='zero'; break;
			case 1: $jednosci='jeden'; break;
			case 2: $jednosci='dwa'; break;
			case 3: $jednosci='trzy'; break;
			case 4: $jednosci='cztery'; break;
			case 5: $jednosci='pięć'; break;
			case 6: $jednosci='sześć'; break;
			case 7: $jednosci='siedem'; break;
			case 8: $jednosci='osiem'; break;
			case 9: $jednosci='dziewięć'; break;
			case 10: $jednosci='dziesięć'; break;
			case 11: $jednosci='jedenaście'; break;
			case 12: $jednosci='dwanaście'; break;
			case 13: $jednosci='trzynaście'; break;
			case 14: $jednosci='czternaście'; break;
			case 15: $jednosci='piętnaście'; break;
			case 16: $jednosci='szesnaście'; break;
			case 17: $jednosci='siedemnaście'; break;
			case 18: $jednosci='osiemnaście'; break;
			case 19: $jednosci='dziewiętnaście'; break;
		}
		
		return($jednosci);
	}

	public static function dziesiatki($wartosc)
	{
		$dziesiatki = '';
		
		switch($wartosc)
		{
			case 1: $dziesiatki='dziesięć'; break;
			case 2: $dziesiatki='dwadzieścia'; break;
			case 3: $dziesiatki='trzydzieści'; break;
			case 4: $dziesiatki='czterdzieści'; break;
			case 5: $dziesiatki='pięćdziesiąt'; break;
			case 6: $dziesiatki='sześćdziesiąt'; break;
			case 7: $dziesiatki='siedemdziesiąt'; break;
			case 8: $dziesiatki='osiemdziesiąt'; break;
			case 9: $dziesiatki='dziewięćdziesiąt'; break;
		}
		
		return($dziesiatki);
	}

	public static function setki($wartosc)
	{
		$setki = '';
		
		switch($wartosc)
		{
			case 1: $setki='sto'; break;
			case 2: $setki='dwieście'; break;
			case 3: $setki='trzysta'; break;
			case 4: $setki='czterysta'; break;
			case 5: $setki='pięćset'; break;
			case 6: $setki='sześćset'; break;
			case 7: $setki='siedemset'; break;
			case 8: $setki='osiemset'; break;
			case 9: $setki='dziewięćset'; break;
		}
		
		return($setki);
	}

	public static function setkix($wartosc)
	{
		$ile=strlen($wartosc);
		if (substr($wartosc, 2, 1)=='0' && substr($wartosc, 1, 1)=='0') { return(self::setki(substr($wartosc, 0, 1))); }
		if (substr($wartosc, 1, 2)<=19) { return(self::setki(substr($wartosc, 0, 1)).' '.self::jednosci(substr($wartosc, 1, 2))); }
		if (substr($wartosc, 1, 2)>19 && substr($wartosc, 1, 2)<=99)
		{
			//echo 'substr($wartosc, ($ile-2), 2)='.substr($wartosc, ($ile-2), 2).'<br>';
			return(self::setki(substr($wartosc, 0, 1)).' '.self::dziesiatkix(substr($wartosc, ($ile-2), 2)));
		}
	}

	public static function dziesiatkix($wartosc)
	{
		if (substr($wartosc, 1, 1)!='0') { return(self::dziesiatki(substr($wartosc, 0, 1)).' '.self::jednosci(substr($wartosc, 1, 1))); }
		if (substr($wartosc, 1, 1)=='0') { return(self::dziesiatki(substr($wartosc, 0, 1))); }
	}

	public static function tekstliczby($wartosc)
	{
		$ile=strlen($wartosc);
		$a=$wartosc;
		
		if ($wartosc<=19) { return(self::jednosci($wartosc)); }
		
		if ($wartosc>19 && $wartosc<=99)
		{
			return(self::dziesiatkix($wartosc));
		}
	
		if ($wartosc>99 && $wartosc<=999)
		{
			//echo 'substr($wartosc, 2, 1)='.substr($wartosc, 2, 1).'<br>';
			//echo 'substr($wartosc, 1, 1)='.substr($wartosc, 1, 1).'<br>';
			return(self::setkix($wartosc));
		}
	
		if ($wartosc>999 && $wartosc<=999999)
		{
			if (substr($wartosc, ($ile-4), 1)>1 && substr($wartosc, ($ile-4), 1)<5 && ($ile==4 || ($ile==6 && substr($wartosc, ($ile-5), 1)==0) || ($ile>4 && substr($wartosc, ($ile-5), 2)>21))) { $tysiace='tysiące'; } else { $tysiace='tysięcy'; } 
			if ($ile==4 && substr($wartosc, 0, 1)=='1') { $tysiace='tysiąc'; }
			
			if (($ile==4 && substr($wartosc, 0, 1)<=9) || ($ile==5 && substr($wartosc, 0, 2)<=19))
			{
				//echo '$ile='.$ile.'<br>';
				$text=self::jednosci(substr($wartosc, 0, (($ile==4) ? 1 : 2))).' '.$tysiace;
				if (substr($wartosc, ($ile-3), 3)<=999 && substr($wartosc, ($ile-2)>99))
				{
					$text.=' '.self::setkix(substr($wartosc, ($ile-3), 3));
				}
			}
			if ($ile==5 && substr($wartosc, 0, 2)>19 && substr($wartosc, 0, 2)<=99)
			{
	
				$text=self::dziesiatkix(substr($wartosc, ($ile-5), 2)).' '.$tysiace;
				if (substr($wartosc, ($ile-3), 3)<=999 && substr($wartosc, ($ile-2)>99))
				{
					$text.=' '.self::setkix(substr($wartosc, ($ile-3), 3));
				}
			}
			
			if ($ile==6 && substr($wartosc, 0, 3)>99 && substr($wartosc, 0, 3)<=999)
			{
				$text=self::setkix(substr($wartosc, ($ile-6), 3)).' '.$tysiace;
				if (substr($wartosc, ($ile-3), 3)<=999 && substr($wartosc, ($ile-2)>99))
				{
					$text.=' '.self::setkix(substr($wartosc, ($ile-3), 3));
				}
			}
			return($text);
		}
	}

	public static function tekstwaluty($wartosc, $groszeslownie=FALSE)
	{
		$wartosc = sprintf("%01.2f", ((real)($wartosc)));
		
		$wart=explode(".", $wartosc);
		
		//return ($wartosc);
		
		$wart[0] = ((isset($wart[0])) ? $wart[0] : '0');
		$wart[1] = ((isset($wart[1])) ? $wart[1] : '0');
		
		$ile1=strlen($wart[0]);
		$ile2=strlen($wart[1]);
		if (substr($wart[1], ($ile2-1), 1)>1 && substr($wart[1], ($ile2-1), 1)<5 && (substr($wart[1], ($ile2-2), 2)>21 || substr($wart[1], ($ile2-2), 2)<10)) { $grosze='grosze'; } else { $grosze='groszy'; } 
		if (substr($wart[1], 0, 2)=='01') { $grosze='grosz'; }
	
		if (substr($wart[0], ($ile1-1), 1)>1 && substr($wart[0], ($ile1-1), 1)<5 && (substr($wart[0], ($ile1-2), 2)>21 || substr($wart[0], ($ile1-2), 2)<10)) { $zlote='złote'; } else { $zlote='złotych'; } 
		if ($ile1==1 && substr($wart[0], 0, 1)==1) { $zlote='złoty'; }
		
		//$groszeslownie = FALSE;

		$zlote='zł';
		return(self::tekstliczby($wart[0]).' '.$zlote.(($groszeslownie) ? ', '.self::tekstliczby($wart[1]).' '.$grosze : (' '.((!$wart[1]) ? 0 : $wart[1]).'/100')));
	}
	
	/*
	public static function makeSelectDataFromArray($p_aData)
	{
		if(is_array($p_aData))
		{
			$aSelectData = array();
			foreach($p_aData as $sKey => $sValue)
			{
				$aSelectData[] = array('k' => $sValue, 'v' => $sKey); 
			}
			return $aSelectData;
		}
		return array();
	}
	*/
	
	public static function prepareLink($p_sContent)
	{
		$sContent = trim((string)($p_sContent));
		
		if (strlen($sContent) > 0)
		{
			$sContent = preg_replace("!(((file|gopher|news|nntp|telnet|http|ftp|https|ftps|sftp)[\:][\/][\/])(www\.)*(([a-zA-Z0-9\._-]+[a-zA-Z0-9\._-]*\.[a-zA-Z]{2,6})|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})))([\/][a-zA-Z0-9\&amp;%_\.\/-~-]*)?!isx", " <a href=\"$0\" target=\"_blank\">$0</a> ", $sContent);
		}
		
		return ($sContent);
	}
	
	public static function __OBSOLETE_arrayToCols($p_array, $cols=1)
	{
		if (is_array($p_array) && $cols>1)
		{
			$newarray=array();
			$total=sizeof($p_array);
			$tmax_rows_in_cols=ceil(($total/$cols));
			$nrows_in_col=floor($total/$cols); $rest=$restfromrest=$total-($nrows_in_col*$cols);
			$col=0; $tmax_rows_in_col=array();
			$totaltmp=$total;
			
			for ($col=0; $col<$cols; $col++)
			{
				$rows_in_col[$col]=$nrows_in_col+(($restfromrest>0) ? 1 : 0);
				$restfromrest--;
			}
			
			$colindex=0;
			for ($row=0; $row<$tmax_rows_in_cols; $row++)
			{
				for ($col=0; $col<$cols; $col++)
				{
					if ($col==0) { $colindex=$row; }
					$colindex=$colindex+(($col>0) ? $rows_in_col[($col-1)] : 0);
					$newarray[]=((isset($p_array[$colindex])) ? $p_array[$colindex] : ''); $p_array[$colindex]=NULL;
				}
			}

			return ($newarray);
		}
		return ($p_array);
		
		/*
		| 0 | 1 | 2 | 3 | 4 | 5 | 6 | 7 | 8 | 9 | 10 |
		
		
		| 0 | 6 |
		| 1 | 7 |
		| 2 | 8 |
		| 3 | 9 |
		| 4 | 10|
		| 5 |
		-----------------
		| 0 | 6 | 1 | 7 | 2 | 8 | 3 | 9 | 4 | 10 | 5 |   |
		
		
			
		| 0 | 4 | 8 |
		| 1 | 5 | 9 |
		| 2 | 6 | 10|
		| 3 | 7 |
		----------------------
		| 0 | 4 | 8 | 1 | 5 | 9 | 2 | 6 | 10 | 3 | 7 |   |

		
		| 0 | 3 | 6 | 9 |
		| 1 | 4 | 7 | 10|
		| 2 | 5 | 8 |
		--------------------
		| 0 | 3 | 6 | 9 | 1 | 4 | 7 | 10 | 2 | 5 | 8 |   |
		
		
		
		| 0 | 3 | 5 | 7 | 9 |
		| 1 | 4 | 6 | 8 | 10|
		| 2 |
		------------------------
		| 0 | 3 | 5 | 7 | 9 | 1 | 4 | 6 | 8 | 10 | 2 |   |   |   |   |


		----------------------------------------------------

		| 0 | 1 | 2 | 3 | 4 | 5 | 6 | 7 | 8 | 9 |
		
		
		| 0 | 5 |
		| 1 | 6 |
		| 2 | 7 |
		| 3 | 8 |
		| 4 | 9 |
		-----------------
		| 0 | 5 | 1 | 6 | 2 | 7 | 3 | 8 | 4 | 9 |
		
		
			
		| 0 | 4 | 8 |
		| 1 | 5 | 9 |
		| 2 | 6 |
		| 3 | 7 |
		----------------------
		| 0 | 4 | 8 | 1 | 5 | 9 | 2 | 6 |   | 3 | 7 |   |

		
		| 0 | 3 | 6 | 9 |
		| 1 | 4 | 7 |
		| 2 | 5 | 8 |
		--------------------
		| 0 | 3 | 6 | 9 | 1 | 4 | 7 |   | 2 | 5 | 8 |   |
		
		
		
		| 0 | 2 | 4 | 6 | 8 |
		| 1 | 3 | 5 | 7 | 9 |
		------------------------
		| 0 | 2 | 4 | 6 | 8 | 1 | 3 | 5 | 7 | 9 |
		*/
	}
	
	public static function arrayToCols($p_aData, $p_iCols = 1)
	{
		$aData = ((array)($p_aData));
		$iCols = ((int)($p_iCols));
		
		if (is_array($aData) && ($iTotal = sizeof($aData)) > 0)
		{
			if ($iCols > 1)
			{
				$iMaxRows = ceil($iTotal / $iCols); // liczymy ile bedzie w kolumnie max wierszy
				$iMinRows = floor($iTotal / $iCols); // ile bedzie minimum wierszy
				$iRestRows = $iTotal - ($iMinRows * $iCols);
				$aResult = array();

				foreach ($aData as $mIndex => $aRow)
				{
					$aIndex[] = $mIndex;
				}
				
				//echo '$iMaxRows = '.$iMaxRows.', $iMinRows = '.$iMinRows.', $iRestRows = '.$iRestRows.'<br>';
				
				/*
				| 0 | 3 | 5 | 7 | 9 |
				| 1 | 4 | 6 | 8 | 10|
				| 2 |
				------------------------
				| 0 | 3 | 5 | 7 | 9 | 1 | 4 | 6 | 8 | 10 | 2 |   |   |   |   |

				| 0 | 3 | 7 | 10 | 13 | 16 |
				| 1 | 4 | 8 | 11 | 14 | 17 |
				| 2 | 6 | 9 | 12 | 15 | 
				------------------------
				| 0 | 3 | 7 | 10 | 13 | 16 | 1 | 4 | 8 | 11 | 14 | 17 | 2 | 6 | 9 | 12 | 15 |
				 */
				
				$iIndex = 0;
				for ($iRow = 0; $iRow < $iMaxRows; $iRow++)
				{
					for ($iCol = 0; $iCol < $iCols; $iCol++)
					{
						if ($iCol == 0)
						{
							$iIndex = $iRow;
						}
						else
						{
							$iIndex = $iIndex + $iMaxRows; //$aRowsInCol[($iCol - 1)];
						}
						
						$mIndex = ((isset($aIndex[$iIndex])) ? $aIndex[$iIndex] : $iCol.','.$iRow);
						
						$aResult[$mIndex] = ((isset($mIndex) && isset($aData[$mIndex])) ? $aData[$mIndex] : NULL); //$aData[$mIndex] = NULL;
						//$aResult[$mIndex]['Col'] = $iCol;
						//$aResult[$mIndex]['Row'] = $iRow;
						
						//echo '$iIndex = '.$iIndex.', $mIndex = '.$mIndex.'<br>';
						//echo '<pre>'.print_r(array($iCol, $iRow, $iIndex, $mIndex, $aData[$mIndex]), TRUE).'</pre>';
					}
				}
				
				return ($aResult);
			}
			else
			{
				return ($aData);
			}
		}
		
		return NULL;
	}
	
	// zapisanie pliku w katalogu tymczasowym
	public static function saveFile($p_sSourcePath, $p_sSourceFile, $p_sDestPath, $p_sDestFile, $p_bUnlinkSrc = TRUE)
	{
		$sSourcePath = trim((string)($p_sSourcePath));
		$sSourceFile = trim((string)($p_sSourceFile));
		$sDestPath = trim((string)($p_sDestPath));
		$sDestFile = trim((string)($p_sDestFile));
		$bUnlinkSrc = ((bool)($p_bUnlinkSrc));
		
		if (!$sDestFile) { $sDestFile = $sSourceFile; }
		
		if (file_exists($sSourcePath.$sSourceFile) && strlen($sDestFile) > 0)
		{
			if (!is_dir($sDestPath)) { mkdir($sDestPath, 0777, TRUE); }
			if (file_exists($sDestPath.$sDestFile)) { unlink($sDestPath.$sDestFile); }
			
			copy($sSourcePath.$sSourceFile, $sDestPath.$sDestFile);
			if ($bUnlinkSrc == TRUE) { unlink($sSourcePath.$sSourceFile); }
			
			return TRUE;
		}
		
		return FALSE;
	}
	
	// czytanie plikow z katalogu $p_sSourcePath
	public static function readFiles($p_sSourcePath)
	{
		$sSourcePath = trim((string)($p_sSourcePath));
		
		$aFiles = array();
		
		if (is_dir($sSourcePath) && ($oDir = dir($sSourcePath)))
		{
			while (false !== ($sFile = $oDir->read()))
			{
				if ($sFile != '.' && $sFile != '..')
				{
					$aFiles[] = $sFile;
				}
			}
			
			$oDir->close();
		}
		
		return ($aFiles);
	}
	
	// metoda usuwa katalog i wszystko co w nim sie znajduje
	public static function delTree($p_sSourcePath)
	{
		$sSourcePath = trim((string)($p_sSourcePath));
		
		if (!is_dir($sSourcePath)) { return FALSE; }
		
		$aFiles = array_diff(scandir($sSourcePath), array('.','..'));
		
		foreach ($aFiles as $sFile)
		{
			$sPath = $sSourcePath.'/'.$sFile;
			
			(is_dir($sPath)) ? self::delTree($sPath) : unlink($sPath);
		}
		
		return rmdir($sSourcePath);
	}
	
	public static function sendFile($p_arrDownloadData)
	{
		//w tablicy musza byc zapisane dane - zmienna $p_arrDownloadData:
		//	filename	:	nazwa pliku	na serwerze	:	obowiazkowo
		//	filerealname:	nazwa pliku	do wyslania	:	opcja
		//	folder		:	folder pliku			:	opcja
		//	disposition	:	wyslanie pliku			:	opcja - domyslnie ustawione bedzie na attachment
		//	type		:	typ pliku				:	opcja - domyslnie ustawione zostanie na plain/text
		//	stream		:	zawartosc pliku			:	opcja - wartosc oznacza, ze nie bedzie czytany plik z serwera
		//	delete		:	flaga usuniecia pliku po jego wyslaniu (opcja) - przyjmuje wartosc tylko TRUE lub NULL
		
		//echo '<pre>'.print_r($p_arrDownloadData, TRUE).'</pre>'; // die();
		
		if ($strFileName = $p_arrDownloadData['filename'])
		{
			//$strFolder = $this->_strFilesFolder.(($p_arrDownloadData['folder']) ? $p_arrDownloadData['folder'] : '');
			$strFolder = ((isset($p_arrDownloadData['folder']) && $p_arrDownloadData['folder']) ? $p_arrDownloadData['folder'] : '');
			$strFolderFile = $strFolder.$strFileName;
			
			//echo '$strFolderFile = '.$strFolderFile.', file_exists($strFolderFile) = '.file_exists($strFolderFile).', is_file($strFolderFile) = '.is_file($strFolderFile).'<hr/>';
			if (file_exists($strFolderFile) && is_file($strFolderFile))
			{
				@chmod($strFolderFile, 0777);
			}
			
			if ((isset($p_arrDownloadData['stream']) && $p_arrDownloadData['stream']) || (file_exists($strFolderFile) && is_file($strFolderFile) && ($intFP = fopen($strFolderFile, 'rb+')))) //plik musi istniec na serwerze
			{
				$intSize=((isset($p_arrDownloadData['stream']) && $p_arrDownloadData['stream']) ? strlen($p_arrDownloadData['stream']) : filesize($strFolderFile));
				
				//echo '$intSize = '.$intSize.'<br\>';

				if (isset($p_arrDownloadData['stream']) && $p_arrDownloadData['stream'])
				{
					$strStream = $p_arrDownloadData['stream'];
				}
				else
				{
					$strStream = @fread($intFP, $intSize);
					fclose($intFP);
					
					if (isset($p_arrDownloadData['delete']) && $p_arrDownloadData['delete'] === TRUE)
					{
						if (file_exists($strFolderFile)) { @unlink($strFolderFile); }
					}
				}
				
				$strDisposition=((isset($p_arrDownloadData['disposition']) && $p_arrDownloadData['disposition']) ? $p_arrDownloadData['disposition'] : 'attachment'); //inline
				$strType = ((isset($p_arrDownloadData['type']) && $p_arrDownloadData['type']) ? $p_arrDownloadData['type'] : (($p_arrDownloadData['stream']) ? 'plain/text' : self::getMimeContentType(self::getFileExtension($strFolderFile))));
				
				header('Pragma: private');
				header('Cache-control: private, must-revalidate');
				
				$strFileRealName = ((isset($p_arrDownloadData['filerealname']) && strlen($p_arrDownloadData['filerealname']) > 0) ? $p_arrDownloadData['filerealname'] : $strFileName);
				
				header("Content-Type: ".$strType."; name=\"".$strFileRealName."\"\r\n");
				header("Content-Length: ".$intSize."\r\n");
				header("Content-Disposition: ".$strDisposition."; filename=\"".$strFileRealName."\"\r\n");
			
				echo $strStream; die();
				return TRUE;
			}
		}
		return FALSE;
	}
}
?>
