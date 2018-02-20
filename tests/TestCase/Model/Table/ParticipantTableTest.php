<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ParticipantTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ParticipantTable Test Case
 */
class ParticipantTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ParticipantTable
     */
    public $Participant;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.participant'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Participant') ? [] : ['className' => ParticipantTable::class];
        $this->Participant = TableRegistry::get('Participant', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Participant);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
