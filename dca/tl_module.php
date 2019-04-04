<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2016 Heimrich & Hannot GmbH
 *
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

$dca = &$GLOBALS['TL_DCA']['tl_module'];

/**
 * Config
 */
$dca['config']['onload_callback'][]   = ['HeimrichHannot\Submissions\Creator\Backend\Module', 'modifyPalette'];
$dca['config']['onsubmit_callback'][] = ['HeimrichHannot\Submissions\Creator\Backend\Module', 'setFormHybridEditable'];

/**
 * Palettes
 */
$dca['palettes']['__selector__'][]                                                               = 'addSubmissionRelation';
$dca['palettes']['__selector__'][]                                                               = 'limitSubmissionPeriod';
$dca['palettes'][HeimrichHannot\Submissions\Creator\SubmissionCreator::MODULE_SUBMISSION_READER] =
    '{title_legend},name,headline,type;' .
    '{entity_legend},defaultArchive,formHybridEditable,formHybridAddEditableRequired,formHybridAddPermanentFields,formHybridAddDefaultValues,formHybridAddGetParameter;' .
    '{period_legend},limitSubmissionPeriod;' .
    '{relation_legend},addSubmissionRelation;' .
    '{action_legend},addUpdateConditions,formHybridAllowIdAsGetParameter,disableSessionCheck,disableAuthorCheck;' .
    '{redirect_legend},formHybridSingleSubmission,formHybridResetAfterSubmission,formHybridAddFieldDependentRedirect,jumpTo,jumpToPrivacy,formHybridAddHashToAction,formHybridJumpToPreserveParams;' .
    '{email_legend},formHybridSubmissionNotification,formHybridConfirmationNotification;' .
    '{misc_legend},formHybridAsync,formHybridSuccessMessage,formHybridSkipScrollingToSuccessMessage,formHybridCustomSubmit,formHybridAddSubmitValues,setPageTitle,addClientsideValidation;' .
    '{template_legend},formHybridTemplate,itemTemplate,customTpl;' .
    '{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

/**
 * Subpalettes
 */
$dca['subpalettes']['addSubmissionRelation'] = 'submissionRelation';
$dca['subpalettes']['limitSubmissionPeriod'] = 'submissionStart,submissionStop';

/**
 * Fields
 */
$arrFields = [
    'addSubmissionRelation' => [
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['addSubmissionRelation'],
        'exclude'   => true,
        'inputType' => 'checkbox',
        'eval'      => ['submitOnChange' => true],
        'sql'       => "char(1) NOT NULL default ''"
    ],
    'submissionRelation'    => [
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['submissionRelation'],
        'exclude'   => true,
        'inputType' => 'select',
        'options'   => array_keys($GLOBALS['SUBMISSION_RELATIONS']),
        'reference' => $GLOBALS['TL_LANG']['MOD'],
        'sql'       => "varchar(32) NOT NULL default ''",
        'eval'      => ['includeBlankOption' => true, 'submitOnChange' => true],
    ],
    'limitSubmissionPeriod' => [
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['limitSubmissionPeriod'],
        'exclude'   => true,
        'inputType' => 'checkbox',
        'eval'      => ['submitOnChange' => true, 'doNotCopy' => true],
        'sql'       => "char(1) NOT NULL default ''"
    ],
    'submissionStart'       => [
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['submissionStart'],
        'exclude'   => true,
        'inputType' => 'text',
        'eval'      => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
        'sql'       => "varchar(10) NOT NULL default ''"
    ],
    'submissionStop'        => [
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['submissionStop'],
        'exclude'   => true,
        'inputType' => 'text',
        'eval'      => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
        'sql'       => "varchar(10) NOT NULL default ''"
    ],
  'jumpToPrivacy' => [
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['jumpToPrivacy'],
        'exclude'                 => true,
        'inputType'               => 'pageTree',
        'foreignKey'              => 'tl_page.title',
        'eval'                    => ['fieldType'=>'radio', 'tl_class'=>'clr'],
        'sql'                     => "int(10) unsigned NOT NULL default '0'",
        'relation'                => ['type'=>'hasOne', 'load'=>'eager']
    ]
];

$dca['fields'] = array_merge($dca['fields'], $arrFields);
