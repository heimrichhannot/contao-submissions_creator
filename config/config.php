<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2016 Heimrich & Hannot GmbH
 *
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

/**
 * Front end modules
 */
array_insert(
	$GLOBALS['FE_MOD'],
	5,
	array
	(
		'submission' => array
		(
			HeimrichHannot\Submissions\Creator\SubmissionCreator::MODULE_SUBMISSION_READER => 'HeimrichHannot\Submissions\Creator\ModuleSubmissionReader',
		),
	)
);

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['loadDataContainer'][] = array('HeimrichHannot\Submissions\Creator\Hooks', 'loadDataContainerHook');
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array('HeimrichHannot\Submissions\Creator\InsertTags', 'replace');

/**
 * Submission Relations
 */
$GLOBALS['SUBMISSION_RELATIONS'] = array
(
	'news' => array
	(
		'table'                 => 'tl_news', // required for invoking tl_submision_relation_spread fields to this datacontainer
		'invokePalettes'        => array('default' => 'addImage'), // invoke tl_submision_relation_spread palette here
		'moduleFields'          => 'news_archives', // tl_module allowed archive field
		'submissionField'       => 'news', // tl_submission field where to store related entity id in
		'request_parameter'     => 'rel', // the related GET request parameter containing the related entity id, it not set, submissionField value will taken as parameter
		'options_callback'      => array('HeimrichHannot\Submissions\Creator\SubmissionCreator', 'getRelatedNews'), // submissionField options_callback --> set options
		'setDefaultFromRequest' => true, // set submissionsField from GET request parameter
		'insertTagLink'         => '{{news_submission_link::PAGE_ID::MODULE_ID::ENTITY_ID}}', // PAGE_ID and ENTITY_ID are available for replacement
		'useAutoItem'           => true,
		'find_entity_callback'  => array('HeimrichHannot\Submissions\Creator\SubmissionCreator', 'findRelatedNewsEntity'),
	),
);
