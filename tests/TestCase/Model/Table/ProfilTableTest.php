<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProfilTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProfilTable Test Case
 */
class ProfilTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProfilTable
     */
    public $Profil;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.profil'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Profil') ? [] : ['className' => ProfilTable::class];
        $this->Profil = TableRegistry::get('Profil', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Profil);

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
