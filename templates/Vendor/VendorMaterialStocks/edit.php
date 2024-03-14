<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\VendorMaterialStock $vendorMaterialStock
 */
?>
<?= $this->Html->css('cstyle.css') ?>
<?= $this->Html->css('table.css') ?>
<?= $this->Html->css('listing.css') ?>
<?= $this->Html->css('v_index.css') ?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $vendorMaterialStock->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $vendorMaterialStock->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Vendor Material Stocks'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="vendorMaterialStocks form content">
            <?= $this->Form->create($vendorMaterialStock) ?>
            <fieldset>
                <legend><?= __('Edit Vendor Material Stock') ?></legend>
                <?php
                    echo $this->Form->control('sap_vendor_code', array('maxlength'=>'10'));
                    echo $this->Form->control('material');
                    echo $this->Form->control('part_code');
                    echo $this->Form->control('material_desc');
                    echo $this->Form->control('current_stock');
                    echo $this->Form->control('production_stock');
                    echo $this->Form->control('status');
                    echo $this->Form->control('added_date');
                    echo $this->Form->control('updated_date');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
