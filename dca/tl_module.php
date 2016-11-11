<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2016 Heimrich & Hannot GmbH
 *
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

$arrDca = &$GLOBALS['TL_DCA']['tl_module'];

/**
 * Config
 */
$arrDca['config']['onload_callback'][] = array('HeimrichHannot\Submissions\Creator\Backend\ModuleBackend', 'modifyPalette');

/**
 * Palettes
 */
$arrDca['palettes']['__selector__'][] = 'addSubmissionRelation';
$arrDca['palettes']['__selector__'][] = 'limitSubmissionPeriod';
$arrDca['palettes'][HeimrichHannot\Submissions\Creator\SubmissionCreator::MODULE_SUBMISSION_READER] =
	'{title_legend},name,headline,type;' .
	'{entity_legend},formHybridPalette,formHybridEditable,formHybridAddEditableRequired,formHybridAddPermanentFields,formHybridAddDefaultValues,defaultArchive;'.
    '{period_legend},limitSubmissionPeriod;' .
	'{relation_legend},addSubmissionRelation;'.
	'{action_legend},addUpdateConditions,formHybridAllowIdAsGetParameter;' .
	'{redirect_legend},formHybridSingleSubmission,formHybridResetAfterSubmission,formHybridAddFieldDependentRedirect,jumpTo,formHybridJumpToPreserveParams;' .
	'{email_legend},formHybridSubmissionNotification,formHybridConfirmationNotification;' .
	'{misc_legend},formHybridAsync,formHybridSuccessMessage,formHybridSkipScrollingToSuccessMessage,formHybridCustomSubmit,formHybridAddSubmitValues,setPageTitle,addClientsideValidation;'.
	'{template_legend},formHybridTemplate,itemTemplate,customTpl;' .
	'{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

/**
 * Subpalettes
 */
$arrDca['subpalettes']['addSubmissionRelation'] = 'submissionRelation';
$arrDca['subpalettes']['limitSubmissionPeriod'] = 'submissionStart,submissionStop';

/**
 * Fields
 */
$arrFields = array
(
	'addSubmissionRelation' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_module']['addSubmissionRelation'],
		'exclude'                 => true,
		'inputType'               => 'checkbox',
		'eval'                    => array('submitOnChange'=>true),
		'sql'                     => "char(1) NOT NULL default ''"
	),
	'submissionRelation' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_module']['submissionRelation'],
		'exclude'                 => true,
		'inputType'               => 'select',
		'options'				 => array_keys($GLOBALS['SUBMISSION_RELATIONS']),
		'reference'				 => $GLOBALS['TL_LANG']['MOD'],
		'sql'                     => "varchar(32) NOT NULL default ''",
		'eval'                    => array('includeBlankOption'=>true, 'submitOnChange'=>true),
	),
    'limitSubmissionPeriod' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['limitSubmissionPeriod'],
        'exclude'                 => true,
        'inputType'               => 'checkbox',
        'eval'                    => array('submitOnChange'=>true, 'doNotCopy'=>true),
        'sql'                     => "char(1) NOT NULL default ''"
    ),
    'submissionStart' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['submissionStart'],
        'exclude'                 => true,
        'inputType'               => 'text',
        'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
        'sql'                     => "varchar(10) NOT NULL default ''"
    ),
    'submissionStop' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['submissionStop'],
        'exclude'                 => true,
        'inputType'               => 'text',
        'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
        'sql'                     => "varchar(10) NOT NULL default ''"
    )
);

$arrDca['fields'] = array_merge($arrDca['fields'], $arrFields);
