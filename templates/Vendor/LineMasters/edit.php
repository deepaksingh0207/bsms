<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\LineMaster $lineMaster
 */
?>
<?= $this->Html->css('v_index.css') ?>
<?= $this->Html->css('custom_table') ?>
<?= $this->Html->css('v_linemasters_edit') ?>
<div class="row">
    <div class="col-12">
        <?= $this->Form->create($lineMaster) ?>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><b>Add Line Master</b></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 col-md-3 col-lg-3">
                        <?php
                    echo $this->Form->control('name', ['class'=> 'form-control']);
                    ?>
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-3">
                        <?php echo $this->Form->control('vendor_factory_id', array('class' => 'form-control w-100', 'options' => $factory, 'style' => "height: unset !important;", 'empty' => 'Please Select','label'=>'Factories')); ?>
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-3">
                        <?php
                        echo $this->Form->control('capacity', ['class'=> 'form-control']);
                        ?>
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-3">
                        <?php
                        echo $this->Form->control('uom', ['class'=> 'form-control']);
                        ?>
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-3">
                        <?php
                    echo $this->Form->control('sap_vendor_code', ['class'=> 'form-control', 'maxlength'=>'10', 'style' => 'visibility: hidden; position: absolute;','label' => false]);
                ?>
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-3">
                        <?php
                    echo $this->Form->control('status', ['class'=> 'form-control', 'style' => 'visibility: hidden; position: absolute;','label' => false]);
                ?>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <?= $this->Form->button(__('Submit'), ['class'=> 'btn bg-gradient-submit mr-1']) ?>
                <?= $this->Html->link(__('Cancel'), ['action' => 'index'], ['class' => 'btn bg-gradient-cancel']) ?>
            </div>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>
