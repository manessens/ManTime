<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MatriceTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MatriceTable Test Case
 */
class MatriceTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\MatriceTable
     */
    public $Matrice;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.matrice'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Matrice') ? [] : ['className' => MatriceTable::class];
        $this->Matrice = TableRegistry::get('Matrice', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Matrice);

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
