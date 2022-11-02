<?php ?>
<section id="content">
    <div class="container clearfix">
        <div class="row my-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                    <h3>BUY MATERIAL</h3>
                        <?= $this->Flash->render('auth') ?>
                        <?= $this->Form->create(null, array('type' => 'file')) ?>
                        <div class="row">
                            <div class="col-3">
                            <?= $this->Form->control('product_id', [
                                                'required' => true,
                                                'type' => 'select',
                                                'options' => $products,
                                                'empty' => 'Select',
                                                'id' => 'product',
                                                'label' => 'Category',
                                                'class' => 'form-control',
                                            ]) ?>
                            </div>
                            <div class="col-3">
                            <?= $this->Form->control('product_sub_category_id', array('required' => true, 'type' => 'select','options' => array(), 'empty' => 'Select', 'id' => 'product_sub_category_id', 'class' => 'form-control','label' => 'Sub Category')); ?>
                            </div>
                            <div class="col-3">
                            <?= $this->Form->control('part_name', ['required' => true, 'class' => 'form-control','maxlength' => 100]); ?>
                            </div>
                            <div class="col-3">
                                <?= $this->Form->control('qty', ['required' => true, 'class' => 'form-control', 'type' => 'number' ]); ?>
                            </div>
                            <div class="col-3">
                            <?= $this->Form->control('uom_code', array('required' => true, 'class' => 'form-control','type' => 'select','options' => $uoms,'empty' => 'Select', 'id' => 'uom', 'label' =>'UOM')); ?>
                            </div>
                            <div class="col-3">
                                <?= $this->Form->control('make', ['required' => true, 'class' => 'form-control','maxlength' => 100]); ?>
                            </div>
                            <div class="col-3">
                                <?= $this->Form->control('remarks', ['type' => 'textarea', 'class' => 'form-control','required' => true, 'escape' => false, 'rows' => '1', 'cols' => '5', 'maxlength' => 200]); ?>
                            </div>
                            <div class="col-3">
                                <label>Attachment</label>
                                <?= $this->Form->control('files[]', ['type' => 'file', 'class' => 'form-control','multiple' => 'multiple', 'label' => false]); ?>
                            </div>
                            <div class="col-1 mt-2">
                            <?= $this->Form->button(__('Save'), [
                                            'label' => 'Save',
                                            'class' => 'btn btn-danger w-100',
                                        ]); ?>
                            </div>
                        </div>
                        <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>