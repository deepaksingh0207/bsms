<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\VendorMaterial $vendorMaterial
 */
?>
<?= $this->Html->css('cstyle.css') ?>
<?= $this->Html->css('custom') ?>
<?= $this->Html->css('table.css') ?>
<?= $this->Html->css('listing.css') ?>
<?= $this->Html->css('v_index.css') ?>

<?= $this->Form->create($vendorMaterial, ['id' => 'vendormaterialform']) ?>
<?= $this->Form->control('vendorMaterial_id', array( 'type' => 'hidden', 'value' => $vendorMaterial)); ?>
<div class="card">
    <div class="card-header pb-1 pt-2">
        <div class="row">
            <div class="col-lg-6 d-flex justify-content-start">
                <h5><b>Add Vendor Materials</b></h5>
            </div>
            <!-- <div class="col-lg-6 d-flex justify-content-end text-align-end">
                <p><a href="#">Vendor Material List</a></p>
            </div> -->
        </div>
    </div>
    <div class="card-body invoice-details p-0">
        <div class="row dgf m-0">
            <div class="col-sm-8 col-md-3">
                <div class="form-group">
                    <?php echo $this->Form->control('vendor_material_code', array('type' => 'number', 'class' => 'form-control rounded-0 w-100', 'style' => "height: unset !important;", 'div' => 'form-group', 'label' => 'Material Code', 'required')); ?>
                </div>
            </div>
            <div class="col-sm-8 col-md-3">
                <div class="form-group">
                    <?php echo $this->Form->control('description', array('type' => 'text', 'class' => 'form-control rounded-0 w-100', 'style' => "height: unset !important;", 'div' => 'form-group', 'label' => 'Material Description', 'required')); ?>
                </div>
            </div>

            <div class="col-sm-8 col-md-3">
                <div class="form-group">
                    <?php echo $this->Form->control('minimum_stock', array('type' => 'number', 'class' => 'form-control rounded-0 w-100', 'style' => "height: unset !important;", 'div' => 'form-group', 'required')); ?>
                </div>
            </div>
            <div class="col-sm-8 col-md-3">
                <!-- <div class="form-group">
                    <?php echo $this->Form->control('uom', [
                        'class' => 'form-control w-100',
                        'options' => $uom,
                        'style' => 'height: unset !important;',
                        'empty' => 'Please Select',
                        'value' => '',
                        'required'
                    ]); ?>
                </div> -->

                <?php echo $this->Form->control('uom', ['class' => 'selectpicker form-control my-select w-100', 'options' => $uom, 'style' => 'height: unset !important;','data-live-search' => 'true',  'empty' => 'Please Select','title' => 'Select Uom']); ?>
            </div>

            <div class="col-sm-8 col-md-3 d-flex justify-content-start align-items-end">
                <button type="button" class="btn btn-custom" onclick="showConfirmationModal()">Submit</button>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="modal-sm" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h6>Are you sure you want to Add Vendor Material?</h6>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn" style="border:1px solid #6610f2" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn" style="border:1px solid #28a745">Ok</button>
            </div>
        </div>
    </div>
</div>

<?= $this->Form->end() ?>



<div class="card">
    <div class="card-header pb-1 pt-2">
        <div class="row">
            <div class="col-lg-6 d-flex justify-content-start">
                <h5><b>Upload Vendor Material</b></h5>
            </div>
        </div>
    </div>

    <?= $this->Form->create(null, ['type' => 'file', 'id' => 'sapvendorcodeform']); ?>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-2 col-md-2 mt-3">
                <?= $this->Form->control('vendor_code', ['type' => 'file', 'label' =>
                false, 'class' => 'pt-1 rounded-0', 'style' => 'visibility: hidden;
                        position: absolute;', 'div' => 'form-group', 'id' => 'vendorCodeInput']);
                ?>
                <?= $this->Form->button('Upload File', ['id' => 'OpenImgUpload', 'type' =>
                'button', 'label' => 'Upload File', 'class' => 'd-block btn bg-gradient-button mb-0 file-upld-btn']); ?>
                <span id="filessnames"></span>
            </div>
            <div class="col-sm-2 col-md-2 mt-3 d-flex justify-content-start align-items-baseline">
                <button class="btn btn-custom" id="sapvendorcode" type="submit">
                    Submit
                </button>
            </div>

            <div class="col-sm-12 col-md-12 mt-3">
                <i style="color: black;">
                    <a href="<?= $this->Url->build('/') ?>webroot/templates/vendor_material.xlsx" download>vendor_material_template</a>
                </i>
            </div>
        </div>
    </div>
    <?= $this->Form->end() ?>
</div>

<div class="card">
    <div class="card-header p-0 mb-0 mt-3 ml-3 mr-3" id="id_pohead">
        <table class="table table-hover" id="example1">
            <thead>
                <tr>
                    <th>Material Description</th>
                    <th>Material Code</th>
                    <th>Unit Of Measurement</th>
                    <th>Minimum Stock</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($vendorMaterialData)) : ?>
                    <?php foreach ($vendorMaterialData as $vendorMaterialDatas) :  ?>
                        <?php if ($vendorMaterialDatas['status']) : ?>
                            <tr>
                                <td>
                                    <?= h($vendorMaterialDatas['data'][1]) ?>
                                </td>
                                <td>
                                    <?= h($vendorMaterialDatas['data'][0]) ?>
                                </td>
                                <td>
                                    <?= h($vendorMaterialDatas['data'][4]) ?>
                                </td>
                                <td>
                                    <?= h($vendorMaterialDatas['data'][2]) ?>
                                </td>
                                <td>
                                    <?= h($vendorMaterialDatas["msg"]) ?>
                                </td>
                            </tr>
                        <?php else : ?>
                            <tr>
                                <td>
                                    <?= h($vendorMaterialDatas['data'][1]) ?>
                                </td>
                                <td>
                                    <?= h($vendorMaterialDatas['data'][0]) ?>
                                </td>
                                <td colspan="2"></td>
                                <td class="text-danger text-left">
                                    <?= h($vendorMaterialDatas["msg"]) ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            </tbody>
        </table>
    </div>
</div>
<script>
    function showConfirmationModal() {
        $('#modal-sm').modal('show');
    }

    // $(window).load(function() {
    //     alert("dsf");
    //     $('#vendormaterialform')[0].reset();
    // });

    $(function() {
        $('.my-select').selectpicker();
    });

    $(document).ready(function() {

        $('#OpenImgUpload').click(function() {
        $('#bulk_file').trigger('click');
        });
        $('#bulk_file').change(function () {
            var file = $(this).prop('files')[0];
            var fileName = file ? file.name : '';

            $('#OpenImgUpload').text(fileName ?  fileName : 'Choose File');
        });

        setTimeout(function() {
            $('.success').fadeOut('slow');
        }, 2000);

    });
</script>