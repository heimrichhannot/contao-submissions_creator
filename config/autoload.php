<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(
    [
	'HeimrichHannot',
    ]);


/**
 * Register the classes
 */
ClassLoader::addClasses(
    [
	// Modules
        'HeimrichHannot\Submissions\Creator\ModuleSubmissionReader'                  => 'system/modules/submissions_creator/modules/ModuleSubmissionReader.php',

	// Classes
        'HeimrichHannot\Submissions\Creator\InsertTags'                              => 'system/modules/submissions_creator/classes/InsertTags.php',
        'HeimrichHannot\Submissions\Creator\SubmissionCreator'                       => 'system/modules/submissions_creator/classes/SubmissionCreator.php',
        'HeimrichHannot\Submissions\Creator\Hooks'                                   => 'system/modules/submissions_creator/classes/Hooks.php',
        'HeimrichHannot\Submissions\Creator\Backend\SubmissionRelationSpreadBackend' => 'system/modules/submissions_creator/classes/backend/SubmissionRelationSpreadBackend.php',
        'HeimrichHannot\Submissions\Creator\Backend\Module'                          => 'system/modules/submissions_creator/classes/backend/Module.php',
        'HeimrichHannot\Submissions\Creator\Backend\SubmissionBackend'               => 'system/modules/submissions_creator/classes/backend/SubmissionBackend.php',
        'HeimrichHannot\Submissions\Creator\Event\ModifyDCEvent'                     => 'system/modules/submissions_creator/classes/Event/ModifyDCEvent.php',
    ]);
