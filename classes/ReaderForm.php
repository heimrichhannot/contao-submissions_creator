<?php

namespace HeimrichHannot\Submissions\Creator;

use HeimrichHannot\Haste\Util\Salutations;

class ReaderForm extends \HeimrichHannot\FrontendEdit\ReaderForm
{
    protected function sendSubmissionNotification(\NotificationCenter\Model\Message $objMessage, &$arrSubmissionData, &$arrToken)
    {
        $arrToken['salutation_submission'] = Salutations::createSalutation($GLOBALS['TL_LANGUAGE'], $this->objActiveRecord);

        return true;
    }

    protected function sendConfirmationNotification(\NotificationCenter\Model\Message $objMessage, &$arrSubmissionData, &$arrToken)
    {
        $arrToken['salutation_submission'] = Salutations::createSalutation($GLOBALS['TL_LANGUAGE'], $this->objActiveRecord);

        return true;
    }
}