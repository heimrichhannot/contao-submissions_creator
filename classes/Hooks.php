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


use HeimrichHannot\FormHybrid\DC_Hybrid;
use HeimrichHannot\Haste\Util\StringUtil;
use HeimrichHannot\Submissions\SubmissionModel;
use NotificationCenter\Model\Message;

class Hooks
{
    const SUBMISSION_RELATION_SPREAD_DCA = 'tl_submission_relation_spread';

    public function initializeSystemHook()
    {
        if (!is_array($GLOBALS['SUBMISSION_RELATIONS'])) {
            return;
        }

        // add notification entity tokens recursive
        foreach ($GLOBALS['SUBMISSION_RELATIONS'] as $strKey => $arrRelation) {
            $arrEntityTokens = $arrRelation['entity_tokens'];

            if (!is_array($arrEntityTokens)) {
                continue;
            }

            $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE'] = array_merge_recursive(
                (array)$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE'],
                [\HeimrichHannot\Submissions\Submissions::NOTIFICATION_TYPE_SUBMISSIONS => $arrRelation['entity_tokens']]
            );
        }

    }


    public function addTokens(Message $objMessage, &$arrTokens, $strLanguage, $objGatewayModel)
    {
        if (($objNotification = $objMessage->getRelated('pid')) === null || !$objNotification->type) {
            return true;
        }

        // check for submission id
        if (!is_numeric($arrTokens['tl_submission'] ?? null)) {
            return true;
        }

        if (($objSubmission = SubmissionModel::findByPk($arrTokens['tl_submission'])) === null) {
            return true;
        }

        if (!is_array($GLOBALS['SUBMISSION_RELATIONS'] ?? null)) {
            return true;
        }

        foreach ($GLOBALS['SUBMISSION_RELATIONS'] as $strKey => $arrRelation) {
            if (empty($arrRelation['table']) || empty($arrRelation['submissionField'])) {
                continue;
            }

            if ($objSubmission->{$arrRelation['submissionField']} < 1) {
                continue;
            }

            if (($objItem = SubmissionCreator::findRelatedEntity($objSubmission->{$arrRelation['submissionField']}, $arrRelation)) === null) {
                continue;
            }

            \Controller::loadDataContainer($arrRelation['table']);
            $objDc               = new DC_Hybrid($arrRelation['table']);
            $objDc->activeRecord = $objItem;

            $arrData   = SubmissionModel::prepareData($objItem, $arrRelation['table'], $GLOBALS['TL_DCA'][$arrRelation['table']], $objDc);
            $arrTokens = array_merge($arrTokens, SubmissionModel::tokenizeData($arrData, 'event'));

            // Options callback
            if (is_array($arrRelation['addTokens_callback']) && isset($arrCallback[0]) && class_exists($arrCallback[0])) {
                $arrCallback = $arrRelation['addTokens_callback'];
                $arrTokens   = \Controller::importStatic($arrCallback[0])->{$arrCallback[1]}($objItem, $arrTokens, $arrRelation, $objNotification, $strLanguage, $objGatewayModel);
            } elseif (is_callable($arrRelation['addTokens_callback'])) {
                $arrTokens = $arrRelation['addTokens_callback']($objItem, $arrTokens, $arrRelation, $objNotification, $strLanguage, $objGatewayModel);
            }
        }

        return true;
    }

    /**
     * Spread Fields to existing DataContainers
     *
     * @param string $strName
     *
     * @return boolean false if Datacontainer not supported
     */
    public function loadDataContainerHook($strName)
    {
        if (!is_array($GLOBALS['SUBMISSION_RELATIONS'])) {
            return false;
        }

        \Controller::loadDataContainer(static::SUBMISSION_RELATION_SPREAD_DCA);

        $arrSpreadDca     = $GLOBALS['TL_DCA'][static::SUBMISSION_RELATION_SPREAD_DCA];
        $strSpreadPalette = $arrSpreadDca['palettes']['default'];

        if (!is_array($GLOBALS['SUBMISSION_RELATIONS'])) {
            return true;
        }

        foreach ($GLOBALS['SUBMISSION_RELATIONS'] as $strKey => $arrRelation) {
            if ($arrRelation['table'] != $strName) {
                continue;
            }

            if (!is_array($arrRelation['invokePalettes'])) {
                continue;
            }

            \Controller::loadDataContainer($strName);

            $arrDca = &$GLOBALS['TL_DCA'][$strName];

            // do not add fields twice, otherwise "exclude" will be reset to true
            if (!array_intersect_key($arrSpreadDca['fields'], $arrDca['fields'])) {
                $arrDca['fields'] = array_merge($arrDca['fields'], $arrSpreadDca['fields']);
            }

            \System::loadLanguageFile($strName);
            \System::loadLanguageFile(static::SUBMISSION_RELATION_SPREAD_DCA);

            // add language to TL_LANG palette
            $GLOBALS['TL_LANG'][$strName] = array_merge(
                !empty($GLOBALS['TL_LANG'][$strName]) ? $GLOBALS['TL_LANG'][$strName] : [],
                $GLOBALS['TL_LANG'][static::SUBMISSION_RELATION_SPREAD_DCA]
            );

            foreach ($arrRelation['invokePalettes'] as $strPaletteName => $strSearch) {
                if (!isset($arrDca['palettes'][$strPaletteName])) {
                    continue;
                }

                $strPalette = $arrDca['palettes'][$strPaletteName];

                if (strpos($strPalette, $strSearch) === false) {
                    continue;
                }

                if (strpos($strPalette, $strSpreadPalette) !== false) {
                    continue;
                }

                $pos = strpos($strPalette, $strSearch);
                $end = $pos + strlen($strSearch);

                $strNext = substr($strPalette, $end, 1);

                switch ($strNext) {
                    case ';':
                        $strPalette = substr_replace($strPalette, $strSpreadPalette, $end + 1, 0);
                        break;
                    case ',':
                    case '{':
                        $strPalette = substr_replace($strPalette, $strSpreadPalette, $end, 0);
                        break;

                }

                $arrDca['palettes'][$strPaletteName] = $strPalette;

                $arrDca['palettes']['__selector__'] = array_merge($arrDca['palettes']['__selector__'], $arrSpreadDca['palettes']['__selector__']);
                $arrDca['subpalettes']              = array_merge($arrDca['subpalettes'], $arrSpreadDca['subpalettes']);
            }
        }


    }

}
