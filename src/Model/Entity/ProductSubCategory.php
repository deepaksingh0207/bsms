<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProductSubCategory Entity
 *
 * @property int $id
 * @property int $product_id
 * @property string $name
 * @property string $description
 * @property int $status
 * @property \Cake\I18n\FrozenTime $added_date
 * @property \Cake\I18n\FrozenTime $updated_date
 *
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\ProductAttribute[] $product_attributes
 */
class ProductSubCategory extends Entity
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
        'product_id' => true,
        'name' => true,
        'description' => true,
        'status' => true,
        'added_date' => true,
        'updated_date' => true,
        'product' => true,
        'product_attributes' => true,
    ];
}
