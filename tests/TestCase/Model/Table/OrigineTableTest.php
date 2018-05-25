<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OrigineTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OrigineTable Test Case
 */
class OrigineTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\OrigineTable
     */
    public $Origine;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.origine'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Origine') ? [] : ['className' => OrigineTable::class];
        $this->Origine = TableRegistry::get('Origine', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Origine);

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
