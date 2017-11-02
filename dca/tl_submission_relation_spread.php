<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2016 Heimrich & Hannot GmbH
 *
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_DCA']['tl_submission_relation_spread'] =
    [
        'palettes'    =>
            [
                '__selector__' => ['addSubmissionRelation', 'limitSubmissionPeriod'],
                'default'      => '{submission_legend},addSubmissionRelation,limitSubmissionPeriod;',
            ],
        'subpalettes' =>
            [
                'addSubmissionRelation' => 'submissionRelation',
                'limitSubmissionPeriod' => 'submissionStart, submissionStop'
            ],
        'fields'      =>
            [
                'addSubmissionRelation' =>
                    [
                        'label'     => &$GLOBALS['TL_LANG']['tl_submission_relation_spread']['addSubmissionRelation'],
                        'exclude'   => true,
                        'inputType' => 'checkbox',
                        'eval'      => ['submitOnChange' => true],
                        'sql'       => "char(1) NOT NULL default ''",
                    ],
                'submissionRelation'    =>
                    [
                        'label'            => &$GLOBALS['TL_LANG']['tl_submission_relation_spread']['submissionRelation'],
                        'exclude'          => true,
                        'inputType'        => 'select',
                        'options_callback' => ['HeimrichHannot\Submissions\Creator\Backend\SubmissionRelationSpreadBackend', 'getSubmissionArchives'],
                        'eval'             => ['includeBlankOption' => true, 'mandatory' => true],
                        'sql'              => "int(10) unsigned NOT NULL default '0'",
                    ],
                'limitSubmissionPeriod' =>
                    [
                        'label'     => &$GLOBALS['TL_LANG']['tl_submission_relation_spread']['limitSubmissionPeriod'],
                        'exclude'   => true,
                        'inputType' => 'checkbox',
                        'eval'      => ['submitOnChange' => true, 'doNotCopy' => true],
                        'sql'       => "char(1) NOT NULL default ''"
                    ],
                'submissionStart'       =>
                    [
                        'label'     => &$GLOBALS['TL_LANG']['tl_submission_relation_spread']['submissionStart'],
                        'exclude'   => true,
                        'inputType' => 'text',
                        'eval'      => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
                        'sql'       => "varchar(10) NOT NULL default ''"
                    ],
                'submissionStop'        =>
                    [
                        'label'     => &$GLOBALS['TL_LANG']['tl_submission_relation_spread']['submissionStop'],
                        'exclude'   => true,
                        'inputType' => 'text',
                        'eval'      => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
                        'sql'       => "varchar(10) NOT NULL default ''"
                    ]
            ],
    ];