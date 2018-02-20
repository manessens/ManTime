<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * LignMatFixture
 *
 */
class LignMatFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'lign_mat';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id_ligne' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'idm' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'id_profil' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'heur' => ['type' => 'decimal', 'length' => 5, 'precision' => 2, 'unsigned' => false, 'null' => false, 'default' => '1.00', 'comment' => ''],
        'jour' => ['type' => 'decimal', 'length' => 5, 'precision' => 2, 'unsigned' => false, 'null' => false, 'default' => '8.00', 'comment' => ''],
        '_indexes' => [
            'FK_lign_mat_matrice' => ['type' => 'index', 'columns' => ['idm'], 'length' => []],
            'FK_lign_mat_profil' => ['type' => 'index', 'columns' => ['id_profil'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id_ligne'], 'length' => []],
            'FK_lign_mat_matrice' => ['type' => 'foreign', 'columns' => ['idm'], 'references' => ['matrice', 'idm'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'FK_lign_mat_profil' => ['type' => 'foreign', 'columns' => ['id_profil'], 'references' => ['profil', 'id_profil'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
            'id_ligne' => 1,
            'idm' => 1,
            'id_profil' => 1,
            'heur' => 1.5,
            'jour' => 1.5
        ],
    ];
}
