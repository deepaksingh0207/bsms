<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProductionLine Entity
 *
 * @property int $id
 * @property string $sap_vendor_code
 * @property int $vendor_factory_id
 * @property int $line_master_id
 * @property int $material_id
 * @property string $capacity
 * @property bool $status
 * @property \Cake\I18n\FrozenTime|null $added_date
 * @property \Cake\I18n\FrozenTime|null $updated_date
 *
 * @property \App\Model\Entity\Material $material
 * @property \App\Model\Entity\LineMaster $line_master
 * @property \App\Model\Entity\Dailymonitor[] $dailymonitor
 */
class ProductionLine extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'sap_vendor_code' => true,
        'vendor_factory_id' => true,
        'line_master_id' => true,
        'material_id' => true,
        'capacity' => true,
        'status' => true,
        'added_date' => true,
        'updated_date' => true,
        'material' => true,
        'line_master' => true,
        'dailymonitor' => true,
    ];
}
