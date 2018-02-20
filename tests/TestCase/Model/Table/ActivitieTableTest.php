<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ActivitieTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ActivitieTable Test Case
 */
class ActivitieTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ActivitieTable
     */
    public $Activitie;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.activitie'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Activitie') ? [] : ['className' => ActivitieTable::class];
        $this->Activitie = TableRegistry::get('Activitie', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Activitie);

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
