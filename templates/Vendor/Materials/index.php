<?php

/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\VendorMaterial> $vendorMaterial
 */
?>
<style>
    .hide {
        display: none;
    }
</style>
<?= $this->Html->css('bootstrap-multiselect') ?>
<?= $this->Html->script('bootstrap-multiselect') ?>
<?= $this->Html->css('dropdown-filter') ?>
<?= $this->Html->css('select2.min.css') ?>
<?= $this->Html->script('select2.js') ?>
<!-- <?= $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css') ?>
<?= $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js') ?> -->
<div class="row">
    <div class="col-12">
        <div class="card mb-2">
            <div class="card-header"><div class="row"><div class="col-sm-12 col-md-12">VENDOR MATERIALS</div></div></div>
            <div class="card-body pt-1">
                <?= $this->Form->create(null, ['id' => 'addvendorform']) ?>
                <div class="row">
                    <div class="col-2">
                        <label for="id_segment">Segment</label><br>
                        <select name="segment[]" id="id_segment" multiple="multiple" class="form-control chosen">
                            <?php if (isset($segment)) : ?>
                            <?php foreach ($segment as $mat) : ?>
                            <option value="<?= h($mat->segment) ?>" data-select="<?= h($mat->segment) ?>">
                                <?= h($mat->segment) ?>
                            </option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-3">
                        <label for="id_material">Material</label><br>
                        <select name="material[]" id="id_material" multiple="multiple" class="form-control chosen">
                            <?php if (isset($materials)) : ?>
                            <?php foreach ($materials as $mat) : ?>
                            <option value="<?= h($mat->id) ?>" data-select="<?= h($mat->code) ?>">
                                <?= h($mat->code) ?> -
                                <?= h($mat->description) ?>
                            </option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-2">
                        <label for="id_vendortype">Type</label><br>
                        <select name="vendortype[]" id="id_vendortype" multiple="multiple" class="form-control chosen">
                            <?php if (isset($vendortype)) : ?>
                            <?php foreach ($vendortype as $mat) : ?>
                            <option value="<?= h($mat->type) ?>"> <?= h($mat->type) ?> </option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-1 mt-4 pt-2">
                        <button class="btn bg-gradient-button" type="submit" id="id_sub">Search</button>
                    </div>
                    <!-- <div class="col-6 mt-4 pt-2">
                    </div> -->
                </div>
                <?= $this->Form->end() ?>
            </div>
            <hr class="m-0">
            
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body buyer_material">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered" id="example1">
                            <thead>
                                <tr>
                                    <th>Vendor Code</th>
                                    <th>Material Code</th>
                                    <th>Description</th>
                                    <th>Type</th>
                                    <th>Segment</th>
                                    <th>Minimum Stock</th>
                                    <th>UOM Code</th>
                                </tr>
                            </thead>
                            <tbody>
                            
                            </tbody>
                        </table>
                    </div>
                </div>
</div>

<script>
    var materiallist_url = `<?php echo \Cake\Routing\Router::url(array('controller' => '/materials', 'action' => 'materiallist')); ?>`;
</script>
<?= $this->Html->script('a_vekpro/buyer/b_material_index') ?>