<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ParticipantFixture
 *
 */
class ParticipantFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'participant';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'idu' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'idp' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'FK_participant_projet' => ['type' => 'index', 'columns' => ['idp'], 'length' => []],
            'FK_participant_users' => ['type' => 'index', 'columns' => ['idu'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['idu', 'idp'], 'length' => []],
            'FK_participant_projet' => ['type' => 'foreign', 'columns' => ['idp'], 'references' => ['projet', 'idp'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'FK_participant_users' => ['type' => 'foreign', 'columns' => ['idu'], 'references' => ['users', 'idu'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
            'idu' => 1,
            'idp' => 1
        ],
    ];
}
