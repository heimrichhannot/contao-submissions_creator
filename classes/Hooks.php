<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2016 Heimrich & Hannot GmbH
 *
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\Submissions\Creator;


use HeimrichHannot\Haste\Util\StringUtil;

class Hooks
{
	const SUBMISSION_RELATION_SPREAD_DCA = 'tl_submission_relation_spread';
	
	/**
	 * Spread Fields to existing DataContainers
	 * @param string $strName
	 * @return boolean false if Datacontainer not supported
	 */
	public function loadDataContainerHook($strName)
	{
		if (!is_array($GLOBALS['SUBMISSION_RELATIONS']))
		{
			return false;
		}
		
		\Controller::loadDataContainer(static::SUBMISSION_RELATION_SPREAD_DCA);
		
		$arrSpreadDca = $GLOBALS['TL_DCA'][static::SUBMISSION_RELATION_SPREAD_DCA];
		$strSpreadPalette = $arrSpreadDca['palettes']['default'];
		
		foreach ($GLOBALS['SUBMISSION_RELATIONS'] as $strKey => $arrRelation)
		{
			if($arrRelation['table'] != $strName)
			{
				continue;
			}
			
			if(!is_array($arrRelation['invokePalettes']))
			{
				continue;
			}
			
			\Controller::loadDataContainer($strName);
			
			$arrDca = &$GLOBALS['TL_DCA'][$strName];
			
			$arrDca['fields'] = array_merge($arrDca['fields'], $arrSpreadDca['fields']);
			
			\System::loadLanguageFile(static::SUBMISSION_RELATION_SPREAD_DCA);
			
			// add language to TL_LANG palette
			$GLOBALS['TL_LANG'][$strName] = array_merge($GLOBALS['TL_LANG'][$strName], $GLOBALS['TL_LANG'][static::SUBMISSION_RELATION_SPREAD_DCA]);
			
			foreach ($arrRelation['invokePalettes'] as $strPalette => $strSearch)
			{
				if(!isset($arrDca['palettes'][$strPalette]))
				{
					continue;
				}
				
				$strReplace = $strSearch . ',' . $strSpreadPalette;
				
				if(StringUtil::startsWith($strSpreadPalette, '{') && !StringUtil::endsWith($strSearch, ';'))
				{
					$strReplace = $strSearch . ';' . $strSpreadPalette;
				}
				else if(StringUtil::startsWith($strSpreadPalette, ',') || StringUtil::startsWith($strSpreadPalette, ';'))
				{
					$strReplace = $strSearch . $strSpreadPalette;
				}
				
				$arrDca['palettes'][$strPalette] = str_replace($strSearch, $strReplace, $arrDca['palettes'][$strPalette]);
				$arrDca['palettes']['__selector__'] = array_merge($arrDca['palettes']['__selector__'], $arrSpreadDca['palettes']['__selector__']);
				$arrDca['subpalettes'] = array_merge($arrDca['subpalettes'], $arrSpreadDca['subpalettes']);
			}
		}
		
		
	}
		
}