<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2016 Heimrich & Hannot GmbH
 *
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

$arrDca = &$GLOBALS['TL_DCA']['tl_submission'];

$arrDca['config']['onload_callback'][] = array('HeimrichHannot\Submissions\Creator\Backend\SubmissionBackend', 'modifyPalette', true);
$arrDca['config']['onsubmit_callback'][] = array('HeimrichHannot\Submissions\Creator\Backend\SubmissionBackend', 'setSubmissionArchiveByRelation');
$arrDca['config']['onsubmit_callback'][] = array('HeimrichHannot\Submissions\Creator\Backend\SubmissionBackend', 'sendNotificationsByArchive');

/**
 * Fields
 */
$arrFields = array
(
	'news' => array
	(
		'label'            => &$GLOBALS['TL_LANG']['tl_submission']['news'],
		'inputType'        => 'select',
		'exclude'          => true,
		'options_callback' => array('HeimrichHannot\Submissions\Creator\SubmissionCreator', 'getRelatedNews'),
		'eval'             => array
		(
			'mandatory' => true,
			'optgroup'  => true, // set to false if no optgroups are required
		),
		'sql'              => "int(10) unsigned NOT NULL default '0'",
	),
);

$arrDca['fields'] = array_merge($arrDca['fields'], $arrFields);