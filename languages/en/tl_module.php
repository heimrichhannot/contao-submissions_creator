<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2016 Heimrich & Hannot GmbH
 *
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

$arrLang = $GLOBALS['TL_LANG']['tl_module'];

/**
 * Fields
 */
$arrLang['addSubmissionRelation'] = ['Link submissions', 'Link submissions with other entities such as contao news.'];
$arrLang['submissionRelation']    = ['Type of link', 'Select the type of link. Submissions are then linked to the corresponding entities. (see $ GLOBALS [ \'SUBMISSION_RELATIONS\'])'];
$arrLang['limitSubmissionPeriod'] = ['Limit submit period', 'Limit the submit submissions period.'];
$arrLang['submissionStart']       = ['Submit from', 'Do not allow submit of submissions before this day.'];
$arrLang['submissionStop']        = ['Submit until', 'Do not allow submit of submissions after this day.'];

/**
 * Legends
 */
$arrLang['relation_legend'] = 'Link-settings';
$arrLang['period_legend']   = 'Period-settings';