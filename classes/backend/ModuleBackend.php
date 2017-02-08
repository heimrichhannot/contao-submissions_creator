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


use HeimrichHannot\Submissions\SubmissionArchiveModel;

class ModuleBackend extends \Backend
{
	public function getSubmissionArchives(\DataContainer $dc)
	{
		$arrOptions = array();
		
		if(($objArchives = SubmissionArchiveModel::findAll()) !== null)
		{
			$arrOptions = $objArchives->fetchEach('title');
		}
		
		return $arrOptions;
	}
	
	public static function modifyPalette(\DataContainer $dc, $blnFrontend = false)
	{
		$id = strlen(\Input::get('id')) ? \Input::get('id') : CURRENT_ID;
		
		$objModule = \ModuleModel::findByPk($id);
		$arrDca    = &$GLOBALS['TL_DCA']['tl_module'];
		
		$type = \Input::post('type') ?: $objModule->type;
		
		if (\HeimrichHannot\Haste\Util\Module::isSubModuleOf($type, 'HeimrichHannot\Submissions\Creator\ModuleSubmissionReader'))
		{
			$objModule->formHybridDataContainer = 'tl_submission';
			$objModule->formHybridPalette = 'default';
			$objModule->noIdBehavior = $objModule->noIdBehavior ?: 'create';
			$objModule->allowDelete = '';
			$objModule->deactivateTokens = true;
			
			$arrDca['fields']['defaultArchive']['eval']['mandatory'] = true;
			$arrDca['fields']['defaultArchive']['eval']['tl_class'] = 'clr';
			
			if($objModule->addSubmissionRelation)
			{
				if(($arrRelation = $GLOBALS['SUBMISSION_RELATIONS'][$objModule->submissionRelation]))
				{
					if($arrRelation['moduleFields'])
					{
						$arrDca['subpalettes']['addSubmissionRelation'] = str_replace(
							'submissionRelation',
							'submissionRelation,' . $arrRelation['moduleFields'],
							$arrDca['subpalettes']['addSubmissionRelation']
						);
					}
				}
			}
			
			
			$objModule->save();
		}
		
	}
}
