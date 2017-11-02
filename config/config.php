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
    [
        'submission' =>
            [
                HeimrichHannot\Submissions\Creator\SubmissionCreator::MODULE_SUBMISSION_READER => 'HeimrichHannot\Submissions\Creator\ModuleSubmissionReader',
            ],
    ]
);

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['loadDataContainer'][]       = ['HeimrichHannot\Submissions\Creator\Hooks', 'loadDataContainerHook'];
$GLOBALS['TL_HOOKS']['replaceInsertTags'][]       = ['HeimrichHannot\Submissions\Creator\InsertTags', 'replace'];
$GLOBALS['TL_HOOKS']['sendNotificationMessage'][] = ['HeimrichHannot\Submissions\Creator\Hooks', 'addTokens'];
$GLOBALS['TL_HOOKS']['initializeSystem'][]        = ['HeimrichHannot\Submissions\Creator\Hooks', 'initializeSystemHook'];

/**
 * Submission Relations
 */
$GLOBALS['SUBMISSION_RELATIONS'] =
    [
        'news'     =>
            [
                'table'                 => 'tl_news', // required for invoking tl_submision_relation_spread fields to this datacontainer
                'invokePalettes'        => ['default' => 'addImage;'], // invoke tl_submision_relation_spread palette here
                'moduleFields'          => 'news_archives', // tl_module allowed archive field
                'submissionField'       => 'news', // tl_submission field where to store related entity id in
                'request_parameter'     => 'rel', // the related GET request parameter containing the related entity id, it not set, submissionField value will taken as parameter
                'options_callback'      => ['HeimrichHannot\Submissions\Creator\SubmissionCreator', 'getRelatedNews'], // submissionField options_callback --> set options
                'setDefaultFromRequest' => true, // set submissionsField from GET request parameter
                'insertTagLink'         => '{{news_submission_link::PAGE_ID::MODULE_ID::ENTITY_ID}}', // MODULE_ID, PAGE_ID and ENTITY_ID are available for replacement
                'insertTagActive'       => '{{news_submission_active::MODULE_ID::ENTITY_ID}}', // MODULE_ID and ENTITY_ID are available for replacement
                'useAutoItem'           => true,
                'find_entity_callback'  => ['HeimrichHannot\Submissions\Creator\SubmissionCreator', 'findRelatedNewsEntity'],
                'addTokens_callback'    => [], // add custom tokens for notification here
                'inactive_message'      => [&$GLOBALS['TL_LANG']['MSC']['submission_news_inactive'], 'headline'],
                'entity_tokens'         =>
                    [
                        \HeimrichHannot\Submissions\Submissions::NOTIFICATION_TYPE_FORM_SUBMISSION =>
                            [
                                'recipients'        => ['news_*'],
                                'email_subject'     => ['news_*'],
                                'email_text'        => ['news_*'],
                                'email_html'        => ['news_*'],
                                'attachment_tokens' => ['news_*'],
                            ],
                        \HeimrichHannot\Submissions\Submissions::NOTIFICATION_TYPE_CONFIRMATION    =>
                            [
                                'recipients'        => ['news_*'],
                                'email_subject'     => ['news_*'],
                                'email_text'        => ['news_*'],
                                'email_html'        => ['news_*'],
                                'attachment_tokens' => ['news_*'],
                            ]
                    ],
            ],
        'calendar' =>
            [
                'table'                 => 'tl_calendar_events', // required for invoking tl_submision_relation_spread fields to this datacontainer
                'invokePalettes'        => ['default' => 'addImage'], // invoke tl_submision_relation_spread palette here
                'moduleFields'          => 'cal_calendar', // tl_module allowed archive field
                'submissionField'       => 'event', // tl_submission field where to store related entity id in
                'request_parameter'     => 'rel', // the related GET request parameter containing the related entity id, it not set, submissionField value will taken as parameter
                'options_callback'      => ['HeimrichHannot\Submissions\Creator\SubmissionCreator', 'getRelatedEvents'], // submissionField options_callback --> set options
                'setDefaultFromRequest' => true, // set submissionsField from GET request parameter
                'insertTagLink'         => '{{event_submission_link::PAGE_ID::MODULE_ID::ENTITY_ID}}', // MODULE_ID, PAGE_ID and ENTITY_ID are available for replacement
                'insertTagActive'       => '{{event_submission_active::MODULE_ID::ENTITY_ID}}', // MODULE_ID AND ENTITY_ID are available for replacement
                'useAutoItem'           => true,
                'find_entity_callback'  => ['HeimrichHannot\Submissions\Creator\SubmissionCreator', 'findRelatedEventEntity'],
                'addTokens_callback'    => ['HeimrichHannot\Submissions\Creator\SubmissionCreator', 'addEventTokens'], // add custom tokens for notification here
                'inactive_message'      => [&$GLOBALS['TL_LANG']['MSC']['submission_event_inactive'], 'title'],
                'entity_tokens'         =>
                    [
                        \HeimrichHannot\Submissions\Submissions::NOTIFICATION_TYPE_FORM_SUBMISSION =>
                            [
                                'recipients'        => ['event_*'],
                                'email_subject'     => ['event_*', 'event_date', 'event_datime'],
                                'email_text'        => ['event_*', 'event_date', 'event_datime'],
                                'email_html'        => ['event_*', 'event_date', 'event_datime'],
                                'attachment_tokens' => ['event_*'],
                            ],
                        \HeimrichHannot\Submissions\Submissions::NOTIFICATION_TYPE_CONFIRMATION    =>
                            [
                                'recipients'        => ['event_*'],
                                'email_subject'     => ['event_*', 'event_date', 'event_datime'],
                                'email_text'        => ['event_*', 'event_date', 'event_datime'],
                                'email_html'        => ['event_*', 'event_date', 'event_datime'],
                                'attachment_tokens' => ['event_*'],
                            ]
                    ],
            ],
    ];