<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\PluginBundle\MigrationMoodle\Task;

use Chamilo\PluginBundle\MigrationMoodle\Extractor\LoadedUsersFilterExtractor;
use Chamilo\PluginBundle\MigrationMoodle\Loader\TrackLoginLoader;
use Chamilo\PluginBundle\MigrationMoodle\Transformer\BaseTransformer;
use Chamilo\PluginBundle\MigrationMoodle\Transformer\Property\DateTimeObject;
use Chamilo\PluginBundle\MigrationMoodle\Transformer\Property\LoadedUserLookup;

/**
 * Class TrackLoginTask.
 *
 * @package Chamilo\PluginBundle\MigrationMoodle\Task
 */
class TrackLoginTask extends BaseTask
{
    /**
     * @inheritDoc
     */
    public function getExtractConfiguration()
    {
        return [
            'class' => LoadedUsersFilterExtractor::class,
            'query' => 'SELECT id, firstaccess, lastaccess FROM mdl_user WHERE firstaccess != 0',
        ];
    }

    /**
     * @inheritDoc
     */
    public function getTransformConfiguration()
    {
        return [
            'class' => BaseTransformer::class,
            'map' => [
                'login_user_id' => [
                    'class' => LoadedUserLookup::class,
                    'properties' => ['id'],
                ],
                'login_date' => [
                    'class' => DateTimeObject::class,
                    'properties' => ['firstaccess'],
                ],
                'logout_date' => [
                    'class' => DateTimeObject::class,
                    'properties' => ['lastaccess'],
                ],
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function getLoadConfiguration()
    {
        return [
            'class' => TrackLoginLoader::class,
        ];
    }
}