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
                        SECONDARY AGEING REPORT
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
                    <div class="col-2 mt-2">
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
                    <div class="col-2 mt-2">
                        <label for="id_pack_size">Pack Size</label><br>
                        <select name="pack_size[]" id="id_pack_size" multiple="multiple" class="form-control chosen">
                            <?php if (isset($pack_size)) : ?>
                            <?php foreach ($pack_size as $mat) : ?>
                            <option value="<?= h($mat->pack_size) ?>"> <?= h($mat->pack_size) ?> </option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-2 mt-2">
                        <label for="id_pack_uom">Pack UOM</label><br>
                        <select name="pack_uom[]" id="id_pack_uom" multiple="multiple" class="form-control chosen">
                            <?php if (isset($pack_uom)) : ?>
                            <?php foreach ($pack_uom as $mat) : ?>
                            <option value="<?= h($mat->pack_uom) ?>"> <?= h($mat->pack_uom) ?> </option>
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
                <div class="card card-primary card-outline card-tabs">
                    <div class="card-header p-0 pt-1 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill"
                                    href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home"
                                    aria-selected="true">SECONDARY AGEING REPORT</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-three-home-tab" data-toggle="pill"
                                    href="#custom-another-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home"
                                    aria-selected="true">ANOTHER AGEING REPORT</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill"
                                    href="#custom-tabs-three-profile" role="tab"
                                    aria-controls="custom-tabs-three-profile" aria-selected="false">SUMMARY</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-three-tabContent">
                            <div class="tab-pane fade show active" id="custom-tabs-three-home" role="tabpanel"
                                aria-labelledby="custom-tabs-three-home-tab">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped table-bordered" id="example1"
                                        style="font-size: smaller;">
                                        <thead>
                                            <tr>
                                                <th>Actual Indent Date</th>
                                                <th>Actual delivery date</th>
                                                <th>PM TYPE</th>
                                                <th>Segment</th>
                                                <th>Material</th>
                                                <th>Material Description</th>
                                                <th>Pack Size</th>
                                                <th>UOM</th>
                                                <th>Indent Qty</th>
                                                <th>Receipt</th>
                                                <th>Pending</th>
                                                <th>Vendor Name</th>
                                                <th>No of Days</th>
                                                <th>Aging Bucket</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="custom-another-tabs-three-home" role="tabpanel"
                                aria-labelledby="custom-another-tabs-three-home-tab">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped table-bordered" id="example3"
                                        style="font-size: smaller;">
                                        <thead>
                                            <tr>
                                                <th>Actual Indent Date</th>
                                                <th>Actual delivery date</th>
                                                <th>PM TYPE</th>
                                                <th>Segment</th>
                                                <th>Material</th>
                                                <th>Material Description</th>
                                                <th>Pack Size</th>
                                                <th>UOM</th>
                                                <th>Indent Qty</th>
                                                <th>Receipt</th>
                                                <th>Pending</th>
                                                <th>Vendor Name</th>
                                                <th>No of Days</th>
                                                <th>Aging Bucket</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="custom-tabs-three-profile" role="tabpanel"
                                aria-labelledby="custom-tabs-three-profile-tab">
                                <div class="table-responsive">
                                <table class="table table-hover table-striped table-bordered" id="example2">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Within 7 days</th>
                                            <th>7 to 15 days</th>
                                            <th>Greater than 15 days</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</div>


<script>
    var materiallist_url = `<?php echo \Cake\Routing\Router::url(array('controller' => '/purchase-orders', 'action' => 'sarlist')); ?>`;
</script>
<?= $this->Html->script('a_vekpro/buyer/b_purchaseorder_secondaryAgeingReport') ?>