<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TitlesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TitlesTable Test Case
 */
class TitlesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TitlesTable
     */
    protected $Titles;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Titles',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Titles') ? [] : ['className' => TitlesTable::class];
        $this->Titles = $this->getTableLocator()->get('Titles', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Titles);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\TitlesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
