<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PoHeader $poHeader
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
            <?= $this->Html->link(__('List Po Headers'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="poHeaders form content">
            <?= $this->Form->create($poHeader) ?>
            <fieldset>
                <legend><?= __('Add Po Header') ?></legend>
                <?php
                    echo $this->Form->control('sap_vendor_code', array('maxlength'=>'10'));
                    echo $this->Form->control('po_no');
                    echo $this->Form->control('document_type');
                    echo $this->Form->control('created_on');
                    echo $this->Form->control('created_by');
                    echo $this->Form->control('pay_terms');
                    echo $this->Form->control('currency');
                    echo $this->Form->control('exchange_rate');
                    echo $this->Form->control('release_status');
                    echo $this->Form->control('added_date');
                    echo $this->Form->control('updated_date');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
