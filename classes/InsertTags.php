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

class InsertTags extends \Controller
{
	
	public function replace($strTag, $blnCache, $strCache, $flags, $tags, $arrCache, $index, $count)
	{
		$arrRelations = $GLOBALS['SUBMISSION_RELATIONS'];
		
		if(!is_array($arrRelations))
		{
			return false;
		}
		
		foreach ($arrRelations as $strKey => $arrRelation)
		{
			if(($strReplace = static::replaceRelation($arrRelation, $strTag)) === false)
			{
				continue;
			}
			
			return $strReplace;
		}
		
		return false;
	}
	
	
	public static function replaceRelation(array $arrRelation, $strTag)
	{
		$params = preg_split('/::/', $strTag);
		
		if(!isset($arrRelation['insertTagLink']) || !isset($arrRelation['table']))
		{
			return false;
		}
		
		$relParams = str_replace(array('{', '}'), '', $arrRelation['insertTagLink']);
		$relParams = preg_split('/::/', $relParams);
		
		// check if given relation inserttag is provided
		if ($relParams[0] != $params[0])
		{
			return false;
		}
		
		$pageId = null;
		$moduleId = null;
		$entityId = null;
		
		if(($pageIdx = array_search('PAGE_ID', $relParams)) !== false)
		{
			$pageId = $params[$pageIdx];
		}
		
		if(($entityIdx = array_search('ENTITY_ID', $relParams)) !== false)
		{
			$entityId = $params[$entityIdx];
		}
		
		if(($moduleIdx = array_search('MODULE_ID', $relParams)) !== false)
		{
			$moduleId = $params[$moduleIdx];
		}
		
		if($pageId === null || ($objPage = \PageModel::findPublishedByIdOrAlias($pageId)) === null)
		{
			return false;
		}
		
		
		if($moduleId === null || ($objModule = \ModuleModel::findByPk($moduleId)) === null)
		{
			return false;
		}
		
		if($entityId === null || ($objEntity = SubmissionCreator::findRelatedEntity($entityId, $arrRelation, $objModule->current())) === null)
		{
			return false;
		}
		
		if(StringUtil::endsWith($params[0], '_link'))
		{
			return SubmissionCreator::getRelationLink($objPage->current(), $objEntity->current(), $arrRelation);
		}
		
		return false;
	}
}