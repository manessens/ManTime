<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TempsFixture
 *
 */
class TempsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'idt' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'date' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => 'current_timestamp()', 'comment' => '', 'precision' => null],
        'time' => ['type' => 'decimal', 'length' => 5, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'n_ligne' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'lock' => ['type' => 'integer', 'length' => 1, 'unsigned' => true, 'null' => true, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'idu' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'idp' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'id_profil' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'ida' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'FK_temps_participant' => ['type' => 'index', 'columns' => ['idu'], 'length' => []],
            'FK_temps_profil' => ['type' => 'index', 'columns' => ['id_profil'], 'length' => []],
            'FK_temps_activities' => ['type' => 'index', 'columns' => ['ida'], 'length' => []],
            'FK_temps_projet' => ['type' => 'index', 'columns' => ['idp'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['idt'], 'length' => []],
            'FK_temps_activities' => ['type' => 'foreign', 'columns' => ['ida'], 'references' => ['activities', 'ida'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'FK_temps_participant' => ['type' => 'foreign', 'columns' => ['idu'], 'references' => ['participant', 'idu'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'FK_temps_profil' => ['type' => 'foreign', 'columns' => ['id_profil'], 'references' => ['profil', 'id_profil'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'FK_temps_projet' => ['type' => 'foreign', 'columns' => ['idp'], 'references' => ['participant', 'idp'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
            'idt' => 1,
            'date' => 1519722325,
            'time' => 1.5,
            'n_ligne' => 1,
            'idu' => 1,
            'idp' => 1,
            'id_profil' => 1,
            'ida' => 1
        ],
    ];
}
