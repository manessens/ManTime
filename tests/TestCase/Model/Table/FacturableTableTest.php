<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FacturableTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FacturableTable Test Case
 */
class FacturableTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\FacturableTable
     */
    public $Facturable;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.facturable'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Facturable') ? [] : ['className' => FacturableTable::class];
        $this->Facturable = TableRegistry::get('Facturable', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Facturable);

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
