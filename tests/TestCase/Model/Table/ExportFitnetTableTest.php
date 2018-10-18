<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ExportFitnetTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ExportFitnetTable Test Case
 */
class ExportFitnetTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ExportFitnetTable
     */
    public $ExportFitnet;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.export_fitnet'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ExportFitnet') ? [] : ['className' => ExportFitnetTable::class];
        $this->ExportFitnet = TableRegistry::get('ExportFitnet', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ExportFitnet);

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
