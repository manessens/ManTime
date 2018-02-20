<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LignMatTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LignMatTable Test Case
 */
class LignMatTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\LignMatTable
     */
    public $LignMat;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.lign_mat'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('LignMat') ? [] : ['className' => LignMatTable::class];
        $this->LignMat = TableRegistry::get('LignMat', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->LignMat);

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
