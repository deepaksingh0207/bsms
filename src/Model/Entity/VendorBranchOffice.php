<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VendorBranchOffice Entity
 *
 * @property int $id
 * @property int|null $vendor_temp_id
 * @property string|null $address
 * @property string|null $address_2
 * @property string|null $pincode
 * @property string|null $city
 * @property string|null $country
 * @property string|null $state
 * @property string|null $telephone
 * @property string|null $registration_year
 * @property string|null $registration_no
 * @property string|null $registration_certificate
 * @property \Cake\I18n\FrozenTime|null $added_date
 * @property \Cake\I18n\FrozenTime|null $updated_date
 *
 * @property \App\Model\Entity\VendorTemp $vendor_temp
 */
class VendorBranchOffice extends Entity
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
        'address' => true,
        'address_2' => true,
        'pincode' => true,
        'city' => true,
        'country' => true,
        'state' => true,
        'telephone' => true,
        'registration_year' => true,
        'registration_no' => true,
        'registration_certificate' => true,
        'added_date' => true,
        'updated_date' => true,
        'vendor_temp' => true,
    ];
}
