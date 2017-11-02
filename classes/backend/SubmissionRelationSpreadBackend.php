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

class SubmissionRelationSpreadBackend extends \Backend
{

    public function getSubmissionArchives(\DataContainer $dc)
    {
        $arrOptions = [];

        $objArchives = SubmissionArchiveModel::findAll();

        if ($objArchives === null) {
            return $arrOptions;
        }

        return $objArchives->fetchEach('title');
    }

}