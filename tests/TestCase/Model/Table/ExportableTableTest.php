<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ExportableTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ExportableTable Test Case
 */
class ExportableTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ExportableTable
     */
    public $Exportable;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.exportable'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Exportable') ? [] : ['className' => ExportableTable::class];
        $this->Exportable = TableRegistry::get('Exportable', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Exportable);

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
