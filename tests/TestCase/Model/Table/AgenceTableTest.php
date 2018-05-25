<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AgenceTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AgenceTable Test Case
 */
class AgenceTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AgenceTable
     */
    public $Agence;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.agence'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Agence') ? [] : ['className' => AgenceTable::class];
        $this->Agence = TableRegistry::get('Agence', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Agence);

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
