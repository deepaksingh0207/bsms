<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * VendorStatusFixture
 */
class VendorStatusFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'vendor_status';
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'status' => 1,
                'description' => 'Lorem ipsum dolor sit amet',
            ],
        ];
        parent::init();
    }
}
