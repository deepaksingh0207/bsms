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
                    <div class="col-sm-12 col-md-12">
                        PRODUCTION PLAN vs ACTUAL PLAN
                    </div>
                </div>
            </div>
            
            <hr class="m-0">
            
        </div>
    </div>
</div>

<div class="card mb-2">
    <div class="card-header">SEARCH</div>
    <div class="card-body">
                <?= $this->Form->create(null, ['id' => 'addvendorform']) ?>
                <div class="row">
                    <div class="col-2">
                        <label for="id_from">Date From</label>
                        <input type="date" name="from" class="form-control" id="id_from">
                    </div>
                    <div class="col-2">
                        <label for="id_to">Date To</label>
                        <input type="date" name="till" class="form-control" id="id_to">
                    </div>
                    <div class="col-3">
                        <label for="id_vendor">Vendor</label><br>
                        <select name="vendor[]" id="id_vendor" class="chosen" multiple="multiple" style="width: 100%;">
                            <?php if (isset($vendor)) : ?>
                            <?php foreach ($vendor as $mat) : ?>
                            <option value="<?= h($mat->sap_vendor_code) ?>" data-select="<?= h($mat->sap_vendor_code) ?>">
                                <?= h($mat->sap_vendor_code) ?> - 
                                <?= h($mat->name) ?>
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
                            <option value="<?= h($mat->code) ?>" data-select="<?= h($mat->code) ?>">
                                <?= h($mat->code) ?> -
                                <?= h($mat->description) ?>
                            </option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-2 ">
                        <label for="id_vendortype">Type</label><br>
                        <select name="vendortype[]" id="id_vendortype" multiple="multiple" class="form-control chosen">
                            <?php if (isset($vendortype)) : ?>
                            <?php foreach ($vendortype as $mat) : ?>
                            <option value="<?= h($mat->type) ?>"> <?= h($mat->type) ?> </option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-2 mt-1">
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
                    <div class="col-2 mt-4 pt-3">
                        <button class="btn bg-gradient-button" type="submit" id="id_sub">Search</button>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
</div>

<div class="card">
<div class="card-body buyer_material">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered" id="example1">
                        <thead>
                            <tr>
                                <th style="width:70px;">Plan Date</th>
                                <th>Confirmation Date</th>
                                <th>Vendor</th>
                                <th>Vendor Name Type</th>
                                <th>Segment</th>
                                <th>Production Line</th>
                                <th>Material</th>
                                <th>Production Plan</th>
                                <th>Confirmed Production</th>
                                <th>Status</th>
                                <th>Closing Stock</th>
                                <th>No. of Stock Aging Days</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
</div>

<script>
    var materiallist_url = `<?php echo \Cake\Routing\Router::url(array('controller' => '/purchase-orders', 'action' => 'ppalist')); ?>`;
</script>
<?= $this->Html->script('a_vekpro/buyer/b_purchaseorder_ppa') ?>