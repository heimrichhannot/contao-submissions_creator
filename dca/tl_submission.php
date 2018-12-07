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

$arrDca['config']['onload_callback'][]   = ['HeimrichHannot\Submissions\Creator\Backend\SubmissionBackend', 'modifyPalette', true];
$arrDca['config']['onsubmit_callback'][] = ['HeimrichHannot\Submissions\Creator\Backend\SubmissionBackend', 'setSubmissionArchiveByRelation'];
$arrDca['config']['onsubmit_callback'][] = ['HeimrichHannot\Submissions\Creator\Backend\SubmissionBackend', 'sendNotificationsByArchive'];

/**
 * Fields
 */
$arrFields =
    [
        'news'          =>
            [
                'label'            => &$GLOBALS['TL_LANG']['tl_submission']['news'],
                'inputType'        => 'select',
                'exclude'          => true,
                'filter'           => true,
                'search'           => true,
                'options_callback' => ['HeimrichHannot\Submissions\Creator\SubmissionCreator', 'getRelatedNews'],
                'eval'             =>
                    [
                        'mandatory' => true,
                        'optgroup'  => true, // set to false if no optgroups are required
                    ],
                'sql'              => "int(10) unsigned NOT NULL default '0'",
            ],
        'event'         =>
            [
                'label'            => &$GLOBALS['TL_LANG']['tl_submission']['event'],
                'inputType'        => 'select',
                'filter'           => true,
                'exclude'          => true,
                'search'           => true,
                'options_callback' => ['HeimrichHannot\Submissions\Creator\SubmissionCreator', 'getRelatedEvents'],
                'eval'             =>
                    [
                        'mandatory' => true,
                        'optgroup'  => true, // set to false if no optgroups are required
                    ],
                'sql'              => "int(10) unsigned NOT NULL default '0'",
            ],
        'privacyJumpTo' =>
            [
                'label'     => &$GLOBALS['TL_LANG']['tl_submission']['privacyJumpTo'],
                'exclude'   => true,
                'filter'    => true,
                'inputType' => 'checkbox',
                'eval'      => ['mandatory' => true, 'tl_class' => 'w50', 'doNotCopy' => true, 'hideLabel' => true],
                'sql'       => "char(1) NOT NULL default ''",
            ]
    ];

$arrDca['fields'] = array_merge($arrDca['fields'], $arrFields);

$arrDca['palettes']['default'] = str_replace('privacy,', 'privacy,privacyJumpTo,', $arrDca['palettes']['default']);


\HeimrichHannot\Haste\Dca\General::addSessionIDFieldAndCallback('tl_submission');
