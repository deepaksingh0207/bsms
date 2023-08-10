<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VendorIncometax Entity
 *
 * @property int $id
 * @property int $vendor_temp_id
 * @property string|null $certificate_no
 * @property \Cake\I18n\FrozenDate|null $certificate_date
 * @property string|null $certificate_file
 * @property string|null $balance_sheet_file
 * @property \Cake\I18n\FrozenTime|null $added_date
 * @property \Cake\I18n\FrozenTime|null $updated_date
 *
 * @property \App\Model\Entity\VendorTemp $vendor_temp
 */
class VendorIncometax extends Entity
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
        'vendor_temp_id' => true,
        'certificate_no' => true,
        'certificate_date' => true,
        'certificate_file' => true,
        'balance_sheet_file' => true,
        'added_date' => true,
        'updated_date' => true,
        'vendor_temp' => true,
    ];
}
