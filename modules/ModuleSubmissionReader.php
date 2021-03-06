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
use HeimrichHannot\StatusMessages\StatusMessage;
use HeimrichHannot\Haste\Util\Url;
use HeimrichHannot\Submissions\Creator\Event\ModifyDCEvent;

class ModuleSubmissionReader extends ModuleReader
{
    protected $strFormClass       = 'HeimrichHannot\\Submissions\\Creator\\ReaderForm';

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

        if ($this->addSubmissionRelation) {
            if (($arrRelation = $GLOBALS['SUBMISSION_RELATIONS'][$this->submissionRelation])) {
                if ($arrRelation['setDefaultFromRequest']) {
                    $this->objRelation = SubmissionCreator::getRelationFromRequest($this->objModel, $arrRelation);
                }
            }
        }

        return parent::generate();
    }

    protected function compile()
    {
        $time     = \Date::floorToMinute();
        $intStart = null;
        $intStop  = null;

        // overwrite start from related entity, but only if selected entity period is between
        if ($this->objRelation !== null && $this->objRelation->limitSubmissionPeriod) {
            $intStart = $this->objRelation->submissionStart;
            $intStop  = $this->objRelation->submissionStop;
        }

        if ($this->limitSubmissionPeriod) {
            if ($this->submissionStart != '') {
                $intStart = ($intStart != '' && $intStart >= $this->submissionStart) ? $intStart : $this->submissionStart;
            }

            if ($this->submissionStop != '') {
                $intStop = ($intStop != '' && $intStop <= $this->submissionStop) ? $intStop : $this->submissionStop;
            }
        }

        $blnInPeriod = false;

        if (($intStart == '' || $intStart <= $time) && ($intStop == '' || ($time + 60) <= $intStop)) {
            $blnInPeriod = true;
        }

        // render submission form only within period
        if ($blnInPeriod) {
            return parent::compile();
        }


        if ($this->objRelation !== null && ($arrRelation = $GLOBALS['SUBMISSION_RELATIONS'][$this->submissionRelation])) {
            StatusMessage::addError(sprintf($arrRelation['inactive_message'][0], $this->objRelation->{$arrRelation['inactive_message'][1]}), $this->id);
        }
    }

    public function modifyDC(&$arrDca = null)
    {
        if(false !== strpos($GLOBALS['TL_LANG']['tl_submission']['privacyJumpTo'][0], '%s'))
        {
            $arrDca['fields']['privacyJumpTo']['label'][0] = sprintf($GLOBALS['TL_LANG']['tl_submission']['privacyJumpTo'][0], Url::generateFrontendUrl($this->jumpToPrivacy));
        }

        $event = new ModifyDCEvent($arrDca, $this);
        if (isset($GLOBALS['TL_HOOKS']['modifyDC']) && \is_array($GLOBALS['TL_HOOKS']['modifyDC']))
        {
            foreach ($GLOBALS['TL_HOOKS']['modifyDC'] as $callback)
            {
                $this->import($callback[0]);
                $this->{$callback[0]}->{$callback[1]}($event);
            }
        }

        $arrDca = $event->getDca();
    }
}

