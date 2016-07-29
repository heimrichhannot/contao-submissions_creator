<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2016 Heimrich & Hannot GmbH
 *
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\Submissions\Creator\Backend;


use HeimrichHannot\Submissions\Creator\SubmissionCreator;
use HeimrichHannot\Submissions\SubmissionModel;
use HeimrichHannot\Submissions\SubmissionArchiveModel;

class SubmissionBackend extends \Backend
{
	public function sendNotificationsByArchive(\DataContainer &$dc)
	{
		if($dc->objModule === null || $dc->objModule->type != \HeimrichHannot\Submissions\Creator\SubmissionCreator::MODULE_SUBMISSION_READER)
		{
			return;
		}
		
		$objSubmission = SubmissionModel::findByPk($dc->id);
		
		if($objSubmission === null)
		{
			return;
		}
		
		// send notifications
		SubmissionModel::sendConfirmationNotification($objSubmission->id);
		SubmissionModel::sendSubmissionNotification($objSubmission->id);
	}
	
	public function setSubmissionArchiveByRelation(\DataContainer &$dc)
	{
		if($dc->objModule === null || !$dc->objModule->addSubmissionRelation || $dc->objModule->type != \HeimrichHannot\Submissions\Creator\SubmissionCreator::MODULE_SUBMISSION_READER)
		{
			return;
		}
		
		$objSubmission = SubmissionModel::findByPk($dc->id);
		
		if($objSubmission === null)
		{
			return;
		}
		
		$arrRelation = $GLOBALS['SUBMISSION_RELATIONS'][$dc->objModule->submissionRelation];
		
		if(!is_array($arrRelation))
		{
			return;
		}
		
		if(!$arrRelation['table'] || !$arrRelation['submissionField'])
		{
			return;
		}
		
		if(($objRelatedItem = SubmissionCreator::findRelatedEntity($objSubmission->{$arrRelation['submissionField']}, $arrRelation, $dc->objModule)) === null)
		{
			return;
		}
		
		if(!$objRelatedItem->addSubmissionRelation)
		{
			return;
		}
		
		if(($objArchive = SubmissionArchiveModel::findByPk($objRelatedItem->submissionRelation)) === null)
		{
			return;
		}
		
		// overwrite submission archive by related item submission archive
		$objSubmission->pid = $objArchive->id;
		$objSubmission->save();
	}

	public function modifyPalette(\DataContainer $dc, $blnFrontend = false)
	{
		$arrDca = &$GLOBALS['TL_DCA']['tl_submission'];
		$objSubmission = SubmissionModel::findByPk($dc->id);
			
		
		if($dc->objModule !== null && $dc->objModule->addSubmissionRelation)
		{
			if(($arrRelation = $GLOBALS['SUBMISSION_RELATIONS'][$dc->objModule->submissionRelation]))
			{
				if($arrDca['fields'][$arrRelation['submissionField']])
				{
					// Options callback
					if (is_array($arrRelation['options_callback']))
					{
						$arrCallback = $arrRelation['options_callback'];
						unset($arrDca['fields'][$arrRelation['submissionField']]['options_callback']);
						$arrDca['fields'][$arrRelation['submissionField']]['options'] = static::importStatic($arrCallback[0])->$arrCallback[1]($dc, $arrRelation);
					}
					elseif (is_callable($arrRelation['options_callback']))
					{
						unset($arrDca['fields'][$arrRelation['submissionField']]['options_callback']);
						$arrDca['fields'][$arrRelation['submissionField']]['options'] = $arrRelation['options_callback']($dc, $arrRelation);
					}
					
					// set default value from request
					if($objSubmission !== null && $arrRelation['setDefaultFromRequest'])
					{
						SubmissionCreator::setDefaultRelation($dc, $objSubmission, $arrRelation);
					}
				}
			}
		}
		
		
	}
	
}