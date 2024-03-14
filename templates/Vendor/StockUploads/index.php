<?php

/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Stockupload> $stockupload
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
<!-- <?= $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap4-theme/1.5.4/select2-bootstrap4.min.css') ?> -->
<!-- <?= $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css') ?>
<?= $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js') ?> -->
<?= $this->Html->css('select2.min.css') ?>
<?= $this->Html->script('select2.js') ?>
<div class="row">
    <div class="col-12">
        <div class="card mb-2">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-12">
                        STOCK UPLOAD
                    </div>
                    <!-- <div class="col-lg-6 d-flex justify-content-end text-align-end">
                        <a href="<?= $this->Url->build('/') ?>buyer/stock-uploads/add"><button type="button" id="continueSub" class="btn bg-gradient-button mb-0 continue_btn">Add Stock</button></a>
                    </div> -->
                </div>
            </div>
            <div class="card-body">
                <?= $this->Form->create(null, ['id' => 'addvendorform']) ?>
                <div class="row">
                    <div class="col-2">
                        <label for="id_segment">Segment</label><br>
                        <select name="segment[]" id="id_segment" multiple="multiple" class="form-control chosen">
                            <?php if (isset($segment)) : ?>
                            <?php foreach ($segment as $mat) : ?>
                            <option value="<?= h($mat->segment) ?>">
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
            
        </div>
    </div>
</div>
<div class="card">
<div class="card-body" id="id_pohead">
                <table class="table table-bordered table-striped table-hover" id="example1">
                    <thead>
                        <tr>
                            <th>Vendor Code</th>
                            <th>Factory Code</th>
                            <th>PO No</th>
                            <th>Vendor Type</th>
                            <th>Segment</th>
                            <th>Line Item</th>
                            <th>Material Code</th>
                            <th>Material Description</th>
                            <th>Opening Stock</th>
                            <th>UOM</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
</div>

<script>
    var stocklist_url = `<?php echo \Cake\Routing\Router::url(array('controller' => '/stock-uploads', 'action' => 'stocklist')); ?>`;
</script>
<?= $this->Html->script('a_vekpro/buyer/b_stock_index') ?>