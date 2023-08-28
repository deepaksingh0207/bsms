<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductionLinesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductionLinesTable Test Case
 */
class ProductionLinesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductionLinesTable
     */
    protected $ProductionLines;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.ProductionLines',
        'app.Materials',
        'app.LineMasters',
        'app.Dailymonitor',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('ProductionLines') ? [] : ['className' => ProductionLinesTable::class];
        $this->ProductionLines = $this->getTableLocator()->get('ProductionLines', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->ProductionLines);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\ProductionLinesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\ProductionLinesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
