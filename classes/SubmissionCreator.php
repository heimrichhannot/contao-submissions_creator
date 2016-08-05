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

use HeimrichHannot\Haste\DateUtil;
use HeimrichHannot\Haste\Util\Url;
use HeimrichHannot\Submissions\SubmissionArchiveModel;

class SubmissionCreator extends \Controller
{
	const MODULE_SUBMISSION_READER = 'submission_reader';
	
	public function addEventTokens($objItem, $arrTokens, $arrRelation, $objNotification, $strLanguage, $objGatewayModel)
	{
		// date and time
		$arrTokens['event_date'] = DateUtil::getNumericDateInterval($objItem->startDate, $objItem->endDate);
		
		if ($objItem->addTime)
		{
			$arrTokens['event_datime'] = DateUtil::getNumericDateTimeInterval($objItem->startTime, $objItem->endTime);
		} else {
			$arrTokens['event_datime'] = $arrTokens['event_date'];
		}
		
		return $arrTokens;
	}
	
	/**
	 * Generate a link to the relation submission form page
	 *
	 * @param \PageModel $objPage
	 * @param \Model     $objEntity
	 * @param array      $arrRelation
	 *
	 * @return string The url to the relation submission page
	 */
	public function getRelationLink(\PageModel $objPage, \Model $objEntity, array $arrRelation)
	{
		$objPage = \PageModel::findWithDetails($objPage->id);
		
		$strParam = $arrRelation['request_parameter'] ?: $arrRelation['submissionField'];
		
		if (\Config::get('useAutoItem') && $arrRelation['useAutoItem']) {
			$varPath = !\Config::get('disableAlias') ? '/' : '/' . $strParam . '/';
			
			$varValue = (!\Config::get('disableAlias') && $objEntity->alias) ? $objEntity->alias : $objEntity->id;
			
			return \Controller::generateFrontendUrl($objPage->row(), $varPath . $varValue, $objPage->language, true);
		}
		
		$strQuery = $strParam . '=' . (!\Config::get('disableAlias') && $objEntity->alias ? $objEntity->alias : $objEntity->id);
		
		return Url::addQueryString($strQuery, \Controller::generateFrontendUrl($objPage->row(), null, $objPage->language, true));
	}
	
	/**
	 * Set the default relation entity within submission entity
	 *
	 * @param \DataContainer                              $dc
	 * @param \HeimrichHannot\Submissions\SubmissionModel $objSubmission
	 * @param array                                       $arrRelation
	 */
	public static function setDefaultRelation(\DataContainer $dc, \HeimrichHannot\Submissions\SubmissionModel &$objSubmission, array $arrRelation)
	{
		$strParam = $arrRelation['request_parameter'] ?: $arrRelation['submissionField'];
		
		// Set the item from the auto_item parameter
		if (!isset($_GET[$strParam]) && \Config::get('useAutoItem') && isset($_GET['auto_item'])) {
			\Input::setGet($strParam, \Input::get('auto_item'));
		}
		
		if (!$strParam || !\Input::get($strParam)) {
			return;
		}
		
		$varId = \Input::get($strParam);
		
		if (($objEntity = static::findRelatedEntity($varId, $arrRelation, $dc->objModule)) === null) {
			return;
		}
		
		$objSubmission->{$arrRelation['submissionField']} = $objEntity->id;
		$objSubmission->save();
		
		return;
	}
	
	public static function findRelatedEntity($varId, array $arrRelation, \ModuleModel $objModule = null)
	{
		// Options callback
		if (is_array($arrRelation['find_entity_callback'])) {
			$arrCallback = $arrRelation['find_entity_callback'];
			
			return static::importStatic($arrCallback[0])->$arrCallback[1]($varId, $arrRelation, $objModule);
		} elseif (is_callable($arrRelation['find_entity_callback'])) {
			$arrRelation['find_entity_callback']($varId, $arrRelation, $objModule);
		}
		
		return null;
	}
	
	public static function findRelatedEventEntity($varId, array $arrRelation, \ModuleModel $objModule = null)
	{
		if (!isset($arrRelation['table'])) {
			return null;
		}
		
		$strModelClass = \Model::getClassFromTable($arrRelation['table']);
		
		if (!class_exists($strModelClass)) {
			return null;
		}
		
		if ($objModule === null) {
			return \CalendarEventsModel::findByPk($varId);
		}
		
		return \CalendarEventsModel::findPublishedByParentAndIdOrAlias($varId, deserialize($objModule->cal_calendar));
	}
	
	public static function getRelatedEvents(\DataContainer $dc, array $arrRelation = array())
	{
		$arrOptionGroups = array();
		$arrOptions      = array();
		
		// front end mode with module & relation context
		if (!empty($arrRelation)) {
			$objEvents = \CalendarEventsModel::findUpcomingByPids(deserialize($dc->objModule->cal_calendar, true), 0, array('order' => 'title'));
		} // back end mode without module & relation context
		else {
			$objEvents = \CalendarEventsModel::findAll();
		}
		
		if ($objEvents === null) {
			return $arrOptions;
		}
		
		while ($objEvents->next()) {
			// return as optgroup if more than 1 $arrPids
			if (($objArchive = $objEvents->getRelated('pid')) === null) {
				continue;
			}
			
			$arrOptionGroups[$objArchive->title][$objEvents->id] = $objEvents->title;
			$arrOptions[$objEvents->id]                          = $objEvents->title;
		}
		
		$arrDca = &$GLOBALS['TL_DCA']['tl_submission'];
		
		// remove optgroups if not wanted, or less than 2 optgroups
		if (count($arrOptionGroups) == 1 || $arrDca['fields'][$arrRelation['submissionField']]['eval']['optgroup'] === false) {
			return $arrOptions;
		}
		
		return $arrOptionGroups;
	}
	
	public static function findRelatedNewsEntity($varId, array $arrRelation, \ModuleModel $objModule = null)
	{
		if (!isset($arrRelation['table'])) {
			return null;
		}
		
		$strModelClass = \Model::getClassFromTable($arrRelation['table']);
		
		if (!class_exists($strModelClass)) {
			return null;
		}
		
		if ($objModule === null) {
			return \NewsModel::findByPk($varId);
		}
		
		return \NewsModel::findPublishedByParentAndIdOrAlias($varId, deserialize($objModule->news_archives));
	}
	
	public static function getRelatedNews(\DataContainer $dc, array $arrRelation = array())
	{
		$arrOptionGroups = array();
		$arrOptions      = array();
		
		// front end mode with module & relation context
		if (!empty($arrRelation)) {
			$objNews = \NewsModel::findPublishedByPids(deserialize($dc->objModule->news_archives, true), null, 0, 0, array('order' => 'headline'));
		} // back end mode without module & relation context
		else {
			$objNews = \NewsModel::findAll();
		}
		
		if ($objNews === null) {
			return $arrOptions;
		}
		
		while ($objNews->next()) {
			// return as optgroup if more than 1 $arrPids
			if (($objArchive = $objNews->getRelated('pid')) === null) {
				continue;
			}
			
			$arrOptionGroups[$objArchive->title][$objNews->id] = $objNews->headline;
			$arrOptions[$objNews->id]                          = $objNews->headline;
		}
		
		$arrDca = &$GLOBALS['TL_DCA']['tl_submission'];
		
		// remove optgroups if not wanted, or less than 2 optgroups
		if (count($arrOptionGroups) == 1 || $arrDca['fields'][$arrRelation['submissionField']]['eval']['optgroup'] === false) {
			return $arrOptions;
		}
		
		return $arrOptionGroups;
		
	}
}