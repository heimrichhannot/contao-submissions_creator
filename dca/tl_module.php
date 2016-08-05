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
$arrDca['palettes'][HeimrichHannot\Submissions\Creator\SubmissionCreator::MODULE_SUBMISSION_READER] =
	'{title_legend},name,headline,type;' .
	'{entity_legend},formHybridPalette,formHybridEditable,formHybridAddEditableRequired,formHybridAddPermanentFields,formHybridAddDefaultValues,defaultArchive;'.
	'{relation_legend},addSubmissionRelation;'.
	'{action_legend},addUpdateConditions,allowIdAsGetParameter;' .
	'{redirect_legend},formHybridResetAfterSubmission,formHybridAddFieldDependentRedirect,jumpTo,formHybridJumpToPreserveParams;' .
	'{misc_legend},formHybridAsync,formHybridSuccessMessage,formHybridAddDefaultValues,formHybridAddSubmitValues,setPageTitle,addClientsideValidation;'.
	'{template_legend},formHybridTemplate,itemTemplate,customTpl;' .
	'{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

/**
 * Subpalettes
 */
$arrDca['subpalettes']['addSubmissionRelation'] = 'submissionRelation';

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
	)
);

$arrDca['fields'] = array_merge($arrDca['fields'], $arrFields);