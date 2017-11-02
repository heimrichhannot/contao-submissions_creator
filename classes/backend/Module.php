<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2016 Heimrich & Hannot GmbH
 *
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\Submissions\Creator\Backend;


use HeimrichHannot\Submissions\SubmissionArchiveModel;

class Module extends \Backend
{
    public function getSubmissionArchives(\DataContainer $dc)
    {
        $arrOptions = [];

        if (($objArchives = SubmissionArchiveModel::findAll()) !== null) {
            $arrOptions = $objArchives->fetchEach('title');
        }

        return $arrOptions;
    }

    public static function modifyPalette(\DataContainer $dc)
    {
        if (($objModule = \ModuleModel::findByPk($dc->id ?: CURRENT_ID)) === null) {
            return;
        }

        $arrDca = &$GLOBALS['TL_DCA']['tl_module'];

        if (\HeimrichHannot\Haste\Util\Module::isSubModuleOf($objModule->type, 'HeimrichHannot\Submissions\Creator\ModuleSubmissionReader')) {
            $objModule->formHybridDataContainer = 'tl_submission';
            $objModule->formHybridPalette       = 'default';
            $objModule->noIdBehavior            = $objModule->noIdBehavior ?: 'create';
            $objModule->allowDelete             = '';
            $objModule->deactivateTokens        = true;

            $arrDca['fields']['defaultArchive']['eval']['mandatory'] = true;
            $arrDca['fields']['defaultArchive']['eval']['tl_class']  = 'clr';
            $arrDca['fields']['defaultArchive']['eval']['submitOnChange']  = true;

            if ($objModule->addSubmissionRelation) {
                if (($arrRelation = $GLOBALS['SUBMISSION_RELATIONS'][$objModule->submissionRelation])) {
                    if ($arrRelation['moduleFields']) {
                        $arrDca['subpalettes']['addSubmissionRelation'] = str_replace(
                            'submissionRelation',
                            'submissionRelation,' . $arrRelation['moduleFields'],
                            $arrDca['subpalettes']['addSubmissionRelation']
                        );
                    }
                }
            }

            $objModule->save();
        }

    }

    public static function setFormHybridEditable(\DataContainer $dc)
    {
        if (($objModule = \ModuleModel::findByPk($dc->id ?: CURRENT_ID)) === null) {
            return;
        }

        if (\HeimrichHannot\Haste\Util\Module::isSubModuleOf($objModule->type, 'HeimrichHannot\Submissions\Creator\ModuleSubmissionReader')) {
            if (!$objModule->formHybridEditable && $objModule->defaultArchive &&
                ($submissionArchive = SubmissionArchiveModel::findByPk($objModule->defaultArchive)) !== null)
            {
                $fields = deserialize($submissionArchive->submissionFields, true);

                if (!empty($fields))
                {
                    $objModule->formHybridEditable = serialize($fields);
                    $objModule->save();
                }
            }
        }
    }
}