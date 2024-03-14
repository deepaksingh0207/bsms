<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\LineMaster $lineMaster
 */
?>
<?= $this->Html->css('v_index.css') ?>
<?= $this->Html->css('custom_table') ?>
<?= $this->Html->css('v_linemasters_add') ?>'
<style>
    label.error{
        color: red !important;
    }
</style>
<div class="row">
    <div class="col-12">
        <?= $this->Form->create($lineMaster, ['id' => 'linemasterform']) ?>
        <div class="card">
            <div class="card-header">
                <h5><b>Line Master</b></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 col-md-3 col-lg-3">
                        <?php echo $this->Form->control('vendor_factory_id', array('class' => 'form-control w-100', 'options' => $factory, 'empty' => 'Please Select', 'label' => 'Factories')); ?>
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-3">
                        <?php echo $this->Form->control('name', ['class' => 'form-control', 'maxlength'=>'20', 'label' => 'Line Name']); ?>
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-2">
                        <?php echo $this->Form->control('capacity', ['type'=>'text', 'class' => 'form-control numberonly', 'maxlength'=>'10']); ?>
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-2">
                        <?php echo $this->Form->control('uom', array('class' => 'form-control w-100', 'options' => $uom, 'empty' => 'Please Select', 'maxlength'=>'3', 'label' => 'Unit Of Measurement')); ?>
                        <?php echo $this->Form->control('status', ['value' => 1, 'style' => 'visibility: hidden; position: absolute;', 'label' => false]); ?>
                    </div>
                    <div class="pl-1 pr-1 mt-4 pt-2">
                        <?= $this->Form->button(__('Submit'), ['type'=> 'submit', 'class' => 'btn bg-gradient-submit']) ?>
                    </div>
                    <div class="mt-4 pt-2" style="display:none;">
                        <?= $this->Html->link(__('Cancel'), ['action' => 'index'], ['class' => 'btn bg-gradient-cancel ml-1']) ?>
                    </div>
                </div>
            </div>
        </div>
        <?= $this->Form->end() ?>
    </div>

</div>

<?= $this->Form->create(null, ['id' => 'formUpload', 'url' => ['controller' => '/line-masters', 'action' => 'upload']]) ?>
<div class="card">
    <div class="card-header">
        <h5><b>Bulk upload Line Master</b></h5>
    </div>
    <div class="card-body">
        <div class="row pl-3">
            <div class="template_file_vendorline mt-2" data-toggle="tooltip" data-original-title="Download Template" data-placement="bottom">
            <a href="<?= $this->Url->build('/') ?>webroot/templates/line_master_upload.xlsx" target="_blank"
                    rel="noopener noreferrer" class="bulk_upload"><i class="fa fa-solid fa-file-download"></i>
                </a>
            </div>
            <div class="pl-2 mt-2">
                <?= $this->Form->control('upload_file', ['type' => 'file', 'label' => false, 'class' => 'pt-1 rounded-0', 'style' => 'visibility: hidden; position: absolute;', 'div' => 'form-group', 'id' => 'bulk_file']); ?>
                <?= $this->Form->button('Upload File', ['id' => 'OpenImgUpload', 'type' =>
                'button', 'label' => 'Upload File', 'class' => 'd-block btn btn-block bg-gradient-button mb-0 file-upld-btn']); ?>
                <!-- <span id="filessnames"></span> -->
            </div>
            <div class="col-sm-6 col-md-4 col-lg-2 mt-2">
                <button type="button" class="btn bg-gradient-submit" id="id_exportme">IMPORT FILE</button>
            </div>
            
        </div>
    </div>

</div>
<?= $this->Form->end() ?>

<div class="card-footer" id="id_pohead">
    <table class="table table-hover" id="example1">
        <thead>
            <tr>
                
                <th>Factory Code</th>
                <th>Line Name</th>
                <th>Opening Stock</th>
                <th>Uom</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<script>
    $('#OpenImgUpload').click(function() {
        $('#bulk_file').trigger('click');
    });
    $('#bulk_file').change(function () {
        var file = $(this).prop('files')[0];
        var fileName = file ? file.name : '';

        $('#OpenImgUpload').text(fileName ?  fileName : 'Choose File');
    });

    $("#linemasterform").validate({
        // Specify validation rules
        rules: {
            vendor_factory_id: "required",
            name: "required",
            capacity: "required",
            uom: "required",
        },
        // Specify validation error messages
        messages: {
            vendor_factory_id: "Please select factories",
            name: "Please enter line Items",
            capacity: "Please enter capacity",
            uom: "Please select UOM"
        },
        submitHandler: function (form) {
            form.submit()
        }
    });

    $("#id_exportme").click(function() {
        var fd = new FormData($('#formUpload')[0]);

        $.ajax({
            url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/line-masters', 'action' => 'upload')); ?>",
            type: "post",
            dataType: 'json',
            processData: false, // important
            contentType: false, // important
            data: fd,
            beforeSend: function () { $("#gif_loader").show(); },
            success: function(response) {
                if (response.status) {
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    });

                    $("#example1 tbody").empty();

                    // Loop through the response data and build the table rows dynamically
                    $.each(response.data, function (key, val) { 
                        var rowHtml = `<tr>
                        <td> `+ val.factory_code + `</td>
                        <td> `+ val.name +`</td>
                        <td> `+ val.capacity + `</td>
                        <td> `+ val.uom + `</td>
                        <td> `+ val.error + `</td>
                        </tr>`;
                        $("#example1 tbody").append(rowHtml);
                    });

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
            },
            complete: function () { $("#gif_loader").hide(); }
        });
    });
</script>