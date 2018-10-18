<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ExportFitnetFixture
 *
 */
class ExportFitnetFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'export_fitnet';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id_fit' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'date_debut' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => 'current_timestamp()', 'comment' => '', 'precision' => null],
        'date_fin' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => '0000-00-00 00:00:00', 'comment' => '', 'precision' => null],
        'idc' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'idu' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'etat' => ['type' => 'string', 'length' => 10, 'null' => false, 'default' => 'En attente', 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'date_create' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => 'current_timestamp()', 'comment' => '', 'precision' => null],
        'date_update' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => 'current_timestamp()', 'comment' => '', 'precision' => null],
        '_indexes' => [
            'FK_export_fitnet_client' => ['type' => 'index', 'columns' => ['idc'], 'length' => []],
            'FK_export_fitnet_users' => ['type' => 'index', 'columns' => ['idu'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id_fit'], 'length' => []],
            'FK_export_fitnet_client' => ['type' => 'foreign', 'columns' => ['idc'], 'references' => ['client', 'idc'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'FK_export_fitnet_users' => ['type' => 'foreign', 'columns' => ['idu'], 'references' => ['users', 'idu'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
            'id_fit' => 1,
            'date_debut' => 1533738165,
            'date_fin' => 1533738165,
            'idc' => 1,
            'idu' => 1,
            'etat' => 'Lorem ip',
            'date_create' => 1533738165,
            'date_update' => 1533738165
        ],
    ];
}
