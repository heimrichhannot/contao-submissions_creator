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

use HeimrichHannot\FrontendEdit\ModuleReader;

class ModuleSubmissionReader extends ModuleReader
{
    /**
     * @var \Model
     */
    protected $objRelation;

	public function generate()
	{
		if (TL_MODE == 'BE') {
			$objTemplate           = new \BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD'][$this->type][0]) . ' ###';
			$objTemplate->title    = $this->headline;
			$objTemplate->id       = $this->id;
			$objTemplate->link     = $this->name;
			$objTemplate->href     = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;
			
			return $objTemplate->parse();
		}

        if($this->addSubmissionRelation)
        {
            if(($arrRelation = $GLOBALS['SUBMISSION_RELATIONS'][$this->submissionRelation]))
            {
                if($arrRelation['setDefaultFromRequest'])
                {
                    $this->objRelation = SubmissionCreator::getRelationFromRequest($this->objModel, $arrRelation);
                }
            }
        }

		return parent::generate();
	}

	protected function compile()
    {
        $time = \Date::floorToMinute();
        $intStart = null;
        $intStop = null;

        // overwrite start from related entity, but only if selected entity period is between
        if($this->objRelation !== null && $this->objRelation->limitSubmissionPeriod)
        {
            $intStart = $this->objRelation->submissionStart;
            $intStop = $this->objRelation->submissionStop;
        }

        if($this->limitSubmissionPeriod)
        {
            if($this->submissionStart != '')
            {
                $intStart = ($intStart != '' && $intStart >= $this->submissionStart) ? $intStart : $this->submissionStart;
            }

            if($this->submissionStop != '')
            {
                $intStop = ($intStop != '' && $intStop <= $this->submissionStop) ? $intStop : $this->submissionStop;
            }
        }

        $blnInPeriod = false;

        if(($intStart == '' || $intStart <= $time) && ($intStop == '' || ($time + 60) <= $intStop))
        {
            $blnInPeriod = true;
        }

        // render submission form only within period
        if($blnInPeriod)
        {
            return parent::compile();
        }

    }
}
