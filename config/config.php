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
$GLOBALS['TL_HOOKS']['loadDataContainer'][]       = array('HeimrichHannot\Submissions\Creator\Hooks', 'loadDataContainerHook');
$GLOBALS['TL_HOOKS']['replaceInsertTags'][]       = array('HeimrichHannot\Submissions\Creator\InsertTags', 'replace');
$GLOBALS['TL_HOOKS']['sendNotificationMessage'][] = array('HeimrichHannot\Submissions\Creator\Hooks', 'addTokens');
$GLOBALS['TL_HOOKS']['initializeSystem'][] = array('HeimrichHannot\Submissions\Creator\Hooks', 'initializeSystemHook');

/**
 * Submission Relations
 */
$GLOBALS['SUBMISSION_RELATIONS'] = array
(
	'news'     => array
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
		'addTokens_callback'    => array(), // add custom tokens for notification here
		'entity_tokens'   => array
		(
			\HeimrichHannot\Submissions\Submissions::NOTIFICATION_TYPE_FORM_SUBMISSION => array
			(
				'recipients' => array('news_*'),
				'email_subject' => array('news_*'),
				'email_text' => array('news_*'),
				'email_html' => array('news_*'),
				'attachment_tokens' => array('news_*'),
			),
			\HeimrichHannot\Submissions\Submissions::NOTIFICATION_TYPE_CONFIRMATION => array
			(
				'recipients' => array('news_*'),
				'email_subject' => array('news_*'),
				'email_text' => array('news_*'),
				'email_html' => array('news_*'),
				'attachment_tokens' => array('news_*'),
			)
		),
	),
	'calendar' => array
	(
		'table'                 => 'tl_calendar_events', // required for invoking tl_submision_relation_spread fields to this datacontainer
		'invokePalettes'        => array('default' => 'addImage'), // invoke tl_submision_relation_spread palette here
		'moduleFields'          => 'cal_calendar', // tl_module allowed archive field
		'submissionField'       => 'event', // tl_submission field where to store related entity id in
		'request_parameter'     => 'rel', // the related GET request parameter containing the related entity id, it not set, submissionField value will taken as parameter
		'options_callback'      => array('HeimrichHannot\Submissions\Creator\SubmissionCreator', 'getRelatedEvents'), // submissionField options_callback --> set options
		'setDefaultFromRequest' => true, // set submissionsField from GET request parameter
		'insertTagLink'         => '{{event_submission_link::PAGE_ID::MODULE_ID::ENTITY_ID}}', // PAGE_ID and ENTITY_ID are available for replacement
		'useAutoItem'           => true,
		'find_entity_callback'  => array('HeimrichHannot\Submissions\Creator\SubmissionCreator', 'findRelatedEventEntity'),
		'addTokens_callback'    => array('HeimrichHannot\Submissions\Creator\SubmissionCreator', 'addEventTokens'), // add custom tokens for notification here
		'entity_tokens'   => array
		(
			\HeimrichHannot\Submissions\Submissions::NOTIFICATION_TYPE_FORM_SUBMISSION => array
			(
				'recipients' => array('event_*'),
				'email_subject' => array('event_*', 'event_date', 'event_datime'),
				'email_text' => array('event_*', 'event_date', 'event_datime'),
				'email_html' => array('event_*', 'event_date', 'event_datime'),
				'attachment_tokens' => array('event_*'),
			),
			\HeimrichHannot\Submissions\Submissions::NOTIFICATION_TYPE_CONFIRMATION => array
			(
				'recipients' => array('event_*'),
				'email_subject' => array('event_*', 'event_date', 'event_datime'),
				'email_text' => array('event_*', 'event_date', 'event_datime'),
				'email_html' => array('event_*', 'event_date', 'event_datime'),
				'attachment_tokens' => array('event_*'),
			)
		),
	),
);