<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\VendorTemp $vendorTemp
 * @var \Cake\Collection\CollectionInterface|string[] $purchasingOrganizations
 * @var \Cake\Collection\CollectionInterface|string[] $accountGroups
 * @var \Cake\Collection\CollectionInterface|string[] $schemaGroups
 */
?>

<!-- <?= $this->Html->css('cstyle.css') ?> -->
<?= $this->Html->css('custom') ?>
<?= $this->Html->css('table.css') ?>
<!-- <?= $this->Html->css('listing.css') ?> -->
<!-- <?= $this->Html->css('b_index.css') ?> -->
<?= $this->Html->css('b_vendortemps_add') ?>
<script>
    var addurl = "<?php echo \Cake\Routing\Router::url(array('controller' => '/vendor-temps', 'action' => 'addvendor')); ?>";
</script>

<div class="row">
    <div class="col-12 add-vendor">
        <div class="card m-2 card_box_shadow">
            <div class="card-body fm">
                <?= $this->Form->create(null, ['id' => 'addvendorform']) ?>
                <div class="row">
                    <div class="col-sm-12 col-md-3 col-lg-1 mb-3">
                        <div class="form-group">
                        <?php echo $this->Form->control('title', array('class' => 'form-control', 'options' => $titles, 'required' => 'required', 'empty' => 'Select')); ?>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4 col-lg-3 mb-3">
                        <div class="form-group">
                            <?php
                            echo $this->Form->control('name', [
                                'class' => 'form-control',
                                'label' => 'Company Name',
                                'placeholder' => 'Please Enter Full Company Name'
                            ]);
                            ?>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4 col-lg-3 mb-3">
                        <div class="row p-0">
                            <div class="col-3 pr-0">
                                <div class="form-group">
                                    <?php echo $this->Form->control('country_code', array('label' => 'Code', 'class' => 'form-control tel numberonly', 'type' => 'tel', 'value' => '+91', 'readonly' => 'readonly')); ?>
                                </div>
                            </div>
                            <div class="col-9">
                                <div class="form-group">
                                    <?php echo $this->Form->control('mobile', array('class' => 'form-control tel numberonly', 'minlength' => '10', 'maxlength' => '10', 'pattern' => '[9,8,7,6]{1}[0-9]{9}', 'type' => 'tel', 'placeholder' => 'please enter mobile number')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4 col-lg-2 mb-3">
                        <div class="form-group">
                            <?php echo $this->Form->control('email', array('class' => 'form-control rounded-0', 'placeholder' => 'please enter email id')); ?>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-3 mb-3">
                        <div class="form-group">
                            <?php echo $this->Form->control('company_code_id', array('class' => 'form-control', 'options' => $company_codes, 'empty' => 'Please Select')); ?>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-2 mb-3">
                        <div class="form-group">
                            <?php echo $this->Form->control('purchasing_organization_id', array('class' => 'form-control', 'empty' => 'Please Select')); ?>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-2 mb-3">
                        <div class="form-group">
                            <?php echo $this->Form->control('account_group_id', array('class' => 'form-control', 'empty' => 'Please Select')); ?>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-2 mb-3">
                        <div class="form-group">
                            <?php echo $this->Form->control('reconciliation_account_id', array('class' => 'form-control', 'options' => $reconciliation_account, 'empty' => 'Please Select')); ?>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-3 mb-3">
                        <div class="form-group">
                            <?php echo $this->Form->control('payment_term_id', array('class' => 'form-control', 'options' => $payment_term, 'empty' => 'Please Select')); ?>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-2 mb-3">
                        <div class="form-group">
                            <?php echo $this->Form->control('schema_group_id', array('class' => 'form-control', 'empty' => 'Please Select')); ?>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-2 col-lg-1 mb-3 d-flex align-items-end justify-content-end">
                        <div class="form-group">
                        <?= $this->Form->button(__('Submit'), ['class' => 'btn bg-gradient-submit', 'id' => 'id_addvendor', 'type' => 'button']) ?>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-2 mb-3">
                        <span class="errorm">
                            <?= $this->Flash->render() ?>
                        </span>
                    </div>
                </div>
                <div class="modal fade" id="modal-sm" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-body text-center">
                                <h6>Are you sure you want to add vendor?</h6>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="modal_cancel btn "  data-dismiss="modal">Cancel</button>
                                <button type="submit" class="modal_ok btn " >Ok</button>
                            </div>
                        </div>
                    </div>
                </div>

                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>

    <div class="col-12">
        <?= $this->Form->create(null, ['type' => 'file']); ?>
        <div class="card mx-2 card_box_shadow">
            <div class="card-header p-3">
                <h5>
                    Recently Added Vendor
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-hover dataTable no-footer" id="example1">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Email</th>
                                        <th>Purchasing Organization</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($latestVendors as $vendor) :
                                        switch ($vendor->status) {
                                            case 0:
                                                $status = '<span class="badge lbluebadge" data-toggle="tooltip" data-placement="right" title="Sent to Vendor"><i class="fas fa-people-arrows"></i></span>';
                                                break;
                                            case 1:
                                                $status = '<span class="badge dbluebadge" data-toggle="tooltip" data-placement="right" title="Pending for approval"><i class="fas fa-user-clock"></i></span>';
                                                break;
                                            case 2:
                                                $status = '<span class="badge purplebadge" data-toggle="tooltip" data-placement="right" title="Sent to SAP"><i class="fas fa-user-plus"></i></span>';
                                                break;
                                            case 3:
                                                $status = '<span class="badge lgreenbadge" data-toggle="tooltip" data-placement="right" title="Approved"><i class="fas fa-user-check"></i></span>';
                                                break;
                                            case 4:
                                                $status = '<span class="badge redbadge" data-toggle="tooltip" data-placement="right" title="Rejected"><i class="fas fa-user-slash"></i></span>';
                                                break;
                                            case 5:
                                                $status = '<span class="badge dgreenbadge" data-toggle="tooltip" data-placement="right" id="halfapproved' . $vendor->id . '" title="Approved"><i class="fas fa-user-check"></i></span><span class="badge badge-light sendcred" data-id="' . $vendor->id . '" id="sendcred' . $vendor->id . '" data-toggle="tooltip" data-placement="right" title="Send Credentials"><i class="fas fa-envelope-open-text text-info"></i></span>';
                                                break;
                                        }
                                    ?>
                                        <tr>
                                            <td>
                                                <?php echo $vendor->title ?>
                                            </td>
                                            <td>
                                                <?php echo $vendor->name ?>
                                            </td>
                                            <td>
                                                <?php echo $vendor->mobile ?>
                                            </td>
                                            <td>
                                                <?php echo $vendor->email ?>
                                            </td>
                                            <td>
                                                <?php echo $vendor->purchasing_organization->name ?>
                                            </td>
                                            <td>
                                                <?= $status ?>
                                            </td>

                                        </tr>
                                    <?php endforeach; ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?= $this->Form->end() ?>
    </div>

    <div class="col-12" style="display: none;">
        <?= $this->Form->create(null, ['type' => 'file']); ?>
        <div class="card mx-2">
            <div class="card-header p-3">
                <h5 >
                    Bulk Vendor Import
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-md-4 col-lg-2">
                        <?= $this->Form->control('vendor_code', [
                            'type' => 'file', 'label' => false, 'class' => 'pt-1 rounded-0', 'style' => 'visibility: hidden; position: absolute;', 'div' => 'form-group', 'id' => 'vendorCodeInput'
                        ]); ?>
                        <?= $this->Form->button('Choose File', [
                            'id' => 'OpenImgUpload',
                            'type' => 'button',
                            'class' => 'd-block btn bg-gradient-button btn-block mb-0 file-upld-btn'
                        ]); ?>
                        <span id="filessnames"></span>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-2">
                        <button type="submit" class="btn btn-primary" id="id_exportme">IMPORT FILE</button>
                    </div>
                    <div class="col-12 pt-2">
                        <i style="color: black;">
                            <a href="<?= $this->Url->build('/') ?>webroot/templates/vendor_import_template.xlsx" target="_blank" rel="noopener noreferrer">Sample_Excel_Template.xlsx</a>
                        </i>
                    </div>
                </div>
                <?php if (isset($results)) : ?>
                    <div class="row py-0">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-hover dataTable no-footer" id="example1">
                                    <thead>
                                        <tr style="color: white;">
                                            <td>Name</td>
                                            <td>Mobile</td>
                                            <td>Email</td>
                                            <td>Payment Terms</td>
                                            <td>Purchase Organization</td>
                                            <td>Account Group</td>
                                            <td>Schema Group</td>
                                            <td>Status</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($results as $row) : ?>
                                            <tr>
                                                <td>
                                                    <?= h($row["data"][1]) ?>
                                                </td>
                                                <td>
                                                    <?= h($row["data"][2]) ?>
                                                </td>
                                                <td>
                                                    <?= h($row["data"][3]) ?>
                                                </td>
                                                <td>
                                                    <?= h($row["data"][4]) ?>
                                                </td>
                                                <td>
                                                    <?= h($row["data"][5]) ?>
                                                </td>
                                                <td>
                                                    <?= h($row["data"][6]) ?>
                                                </td>
                                                <td>
                                                    <?= h($row["data"][7]) ?>
                                                </td>
                                                <?php if ($row['status']) : ?>
                                                    <td class="text-success">
                                                        <?= h($row['msg']) ?>
                                                    </td>
                                                <?php else : ?>
                                                    <td class="text-danger">
                                                        <b>
                                                            <?= h($row['msg']) ?>
                                                        </b>
                                                    </td>
                                                <?php endif; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-footer">Note<span class="text-danger">*</span> : <i>Imported Vendor's will receive
                    credentials on mail</i></div>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>
<?= $this->Html->script('b_vendortemps_add') ?>