<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Stockupload $stockupload
 */

use PhpOffice\PhpSpreadsheet\Calculation\Information\Value;

?>
<?= $this->Html->css('cstyle.css') ?>
<?= $this->Html->css('custom') ?>
<?= $this->Html->css('table.css') ?>
<?= $this->Html->css('listing.css') ?>
<?= $this->Html->css('v_index.css') ?>
<?= $this->Form->create($stockupload, ['id' => 'stockuploadForm']) ?>
<div class="card">
    <div class="card-header pb-1 pt-2">
        <div class="row">
            <div class="col-lg-6 d-flex justify-content-start">
                <h5><b>Add Stock Upload</b></h5>
            </div>
            <!-- <div class="col-lg-6 d-flex justify-content-end text-align-end">
                <p><a href="#">List Stock Upload</a></p>
            </div> -->
        </div>
    </div>
    <div class="card-body invoice-details p-0">
        <div class="row dgf m-0">
            <div class="col-sm-8 col-md-3">
                <div class="form-group">
                    <?php echo $this->Form->control('code', array('class' => 'form-control w-100', 'options' => $vendor_mateial, 'id' => 'descripe', 'style' => "height: unset !important;", 'value' => $this->getRequest()->getData('vendor_material_code'), 'empty' => 'Please Select', 'label' => 'Material Code')); ?>
                </div>
            </div>

            <div class="col-sm-8 col-md-3">
                <div class="form-group">
                    <?php echo $this->Form->control('description', array('type' => 'text','class' => 'form-control rounded-0 w-100', 'style' => "height: unset !important;", 'div' => 'form-group', 'required', 'label' => 'Material Description', 'readonly')); ?>
                </div>
            </div>
            <div class="col-sm-8 col-md-3">
                <div class="form-group">
                    <?php echo $this->Form->control('uom', array('type' => 'text', 'class' => 'form-control rounded-0 w-100', 'style' => "height: unset !important;", 'div' => 'form-group',  'label' => 'Unit Of Measurement', 'readonly')); ?>
                </div>
            </div>


            <div class="col-sm-8 col-md-3">
                <div class="form-group">
                    <?php echo $this->Form->control('opening_stock', array('type' => 'number', 'class' => 'form-control rounded-0 w-100', 'style' => "height: unset !important;", 'div' => 'form-group')); ?>
                </div>
            </div>
            <div class="col-sm-8 col-md-3 d-flex justify-content-start align-items-end">
                <button type="button" class="btn btn-custom" data-toggle="modal" data-target="#modal-sm" id="stockClick">Submit</button>
                <button type="submit" style="display: none;" id="stockInputSubmit">Submit</button>
            </div>
        </div>
    </div>
</div>

<?= $this->Form->end() ?>
<div class="modal fade" id="modal-sm" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h6>Are you sure you want to Add?</h6>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn" style="border:1px solid #6610f2" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn addSubmit" style="border:1px solid #28a745">Ok</button>
            </div>
        </div>
    </div>
</div>



<div class="card">
    <div class="card-header pb-1 pt-2">
        <div class="row">
            <div class="col-lg-6 d-flex justify-content-start">
                <h5><b>UPLOAD STOCKS</b></h5>
            </div>
        </div>
    </div>

    <?= $this->Form->create(null, ['id' => 'formUpload', 'url' => ['controller' => '/stock-uploads', 'action' => 'upload']]) ?>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-2 col-md-2 mt-3">
            <?= $this->Form->control('upload_file', ['id' => 'bulk_file','type' => 'file']); ?>
                
                <span id="filessnames"></span>
            </div>
            <div class="col-sm-2 col-md-2 mt-3 d-flex justify-content-start align-items-baseline">
                <button class="btn btn-custom" id="id_import" type="button">
                    Submit
                </button>
            </div>
            <div class="col-sm-12 col-md-12 mt-3">
                <i style="color: black;">
                    <a href="<?= $this->Url->build('/') ?>webroot/templates/material_stock_upload.xlsx" download>stock_upload_template</a>
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
                    <th>Opening Stock</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($stockuploadData)) : ?>

                    <?php foreach ($stockuploadData as $stockuploads) :  ?>
                        <?php if ($stockuploads['status']) : ?>

                            <tr>
                                <td>
                                    <?= h($stockuploads['data']['desc']) ?>
                                </td>
                                <td>
                                    <?= h($stockuploads['data']['material_code']) ?>
                                </td>
                                <td>
                                    <?= h($stockuploads['data']['uoms']) ?>
                                </td>
                                <td>
                                    <?= h($stockuploads['data']["opening_stock"]) ?>
                                </td>
                                <td>
                                    <?= h($stockuploads["msg"]) ?>
                                </td>
                            </tr>
                        <?php else : ?>
                            <tr>
                                <td>
                                    <?= h($stockuploads['data']['desc']) ?>
                                </td>
                                <td>
                                    <?= h($stockuploads['data']['material_code']) ?>
                                </td>
                                <td colspan="2"></td>
                                <td class="text-danger text-left">
                                    <?= h($stockuploads["msg"]) ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>




<script>

    
   
    $('#stockClick').click(function() {
        submitStatus = true
    });

    $('#sapvendorcode').click(function() {
        submitStatus = false
        console.log(submitStatus);
    });


    $('.addSubmit').click(function() {
        if (submitStatus) {
            $("#stockInputSubmit").trigger('click');   
        }
        else{
            $("#stockFileSubmit").trigger('click');  
        }
    });


    $(document).ready(function() {

        $('#OpenImgUpload').click(function() {
            $('#vendorCodeInput').trigger('click');
        });
        $('#vendorCodeInput').change(function() {
            var file = $(this).prop('files')[0].name;
            $("#filessnames").append(file);
        });

        setTimeout(function() {
            $('.success').fadeOut('slow');
        }, 2000);

        $("#descripe").change(function() {
            var vendorId = $(this).val();
            if (vendorId != "") {
                $.ajax({
                    type: "get",
                    url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/stock-uploads', 'action' => 'material')); ?>/" + vendorId,
                    dataType: "json",
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader(
                            "Content-type",
                            "application/x-www-form-urlencoded"
                        );
                    },
                    success: function(response) {
                        if (response.status == "1") {
                            $("#description").val(response.data.description);
                            $("#uom").val(response.data.uom);
                        }
                    },
                    error: function(e) {
                        alert("An error occurred: " + e.responseText.message);
                        console.log(e);
                    },
                });
            }
        });


    });

    $("#id_import").click(function() {
        var fd = new FormData($('#formUpload')[0]);

        $.ajax({
            url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/stock-uploads', 'action' => 'upload')); ?>",
            type: "post",
            dataType: 'json',
            processData: false, // important
            contentType: false, // important
            data: fd,
            success: function(response) {
                if(response.status) {
                    Toast.fire({
                    icon: 'success',
                    title: response.message
                });

                //setTimeout(function() {history.go(-1);}, 1000);
                
                } else {
                    Toast.fire({
                    icon: 'error',
                    title: response.message
                });
                }
            },
            error: function() {
                Toast.fire({
                    icon: 'error',
                    title: 'An error occured, please try again.'
                });
            }
        });
    });
</script>