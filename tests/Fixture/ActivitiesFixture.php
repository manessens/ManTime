<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ActivitiesFixture
 *
 */
class ActivitiesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'ida' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'idp' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'FK_activities_projet' => ['type' => 'index', 'columns' => ['idp'], 'length' => []],
            'FK_activities_activitie' => ['type' => 'index', 'columns' => ['ida'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['ida', 'idp'], 'length' => []],
            'FK_activities_activitie' => ['type' => 'foreign', 'columns' => ['ida'], 'references' => ['activitie', 'ida'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'FK_activities_projet' => ['type' => 'foreign', 'columns' => ['idp'], 'references' => ['projet', 'idp'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'ida' => 1,
            'idp' => 1
        ],
    ];
}
