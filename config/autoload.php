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
ClassLoader::addNamespaces(array
(
	'HeimrichHannot',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Modules
	'HeimrichHannot\Submissions\Creator\ModuleSubmissionReader'                  => 'system/modules/submissions_creator/modules/ModuleSubmissionReader.php',

	// Classes
	'HeimrichHannot\Submissions\Creator\InsertTags'                              => 'system/modules/submissions_creator/classes/InsertTags.php',
	'HeimrichHannot\Submissions\Creator\SubmissionCreator'                       => 'system/modules/submissions_creator/classes/SubmissionCreator.php',
	'HeimrichHannot\Submissions\Creator\Hooks'                                   => 'system/modules/submissions_creator/classes/Hooks.php',
	'HeimrichHannot\Submissions\Creator\Backend\SubmissionRelationSpreadBackend' => 'system/modules/submissions_creator/classes/backend/SubmissionRelationSpreadBackend.php',
	'HeimrichHannot\Submissions\Creator\Backend\ModuleBackend'                   => 'system/modules/submissions_creator/classes/backend/ModuleBackend.php',
	'HeimrichHannot\Submissions\Creator\Backend\SubmissionBackend'               => 'system/modules/submissions_creator/classes/backend/SubmissionBackend.php',
));
