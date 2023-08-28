<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\LineMaster $lineMaster
 */
?>
<?= $this->Html->css('v_index.css') ?>
<?= $this->Html->css('custom_table') ?>
<?= $this->Html->css('v_linemasters_add') ?>
<div class="row">
    <div class="col-12">
        <?= $this->Form->create($lineMaster) ?>
        <div class="card">
            <div class="card-header">
                <h5>Line Master</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 col-md-3 col-lg-3">
                        <?php echo $this->Form->control('vendor_factory_id', array('class' => 'form-control w-100', 'options' => $factory, 'style' => "height: unset !important;", 'empty' => 'Please Select', 'label' => 'Factories')); ?>
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-3">
                        <?php echo $this->Form->control('name', ['class' => 'form-control', 'label' => 'Line Name']); ?>
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-3">
                        <?php echo $this->Form->control('capacity', ['class' => 'form-control']); ?>
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-3">
                        <?php echo $this->Form->control('uom', array('class' => 'form-control w-100', 'options' => $uom, 'style' => "height: unset !important;", 'empty' => 'Please Select', 'label' => 'Unit Of Measurement')); ?>
                        <?php echo $this->Form->control('status', ['value' => 1, 'style' => 'visibility: hidden; position: absolute;', 'label' => false]); ?>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <?= $this->Form->button(__('Submit'), ['class' => 'btn bg-gradient-submit']) ?>
                <?= $this->Html->link(__('Cancel'), ['action' => 'index'], ['class' => 'btn bg-gradient-cancel']) ?>
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
        <div class="row">
            <div class="col-sm-6 col-md-4 col-lg-2">

                <?= $this->Form->control('upload_file', ['type' => 'file', 'label' => false, 'class' => 'pt-1 rounded-0', 'style' => 'visibility: hidden; position: absolute;', 'div' => 'form-group', 'id' => 'bulk_file']); ?>
                <?= $this->Form->button('Upload File', ['id' => 'OpenImgUpload', 'type' =>
                'button', 'label' => 'Upload File', 'class' => 'd-block btn btn-block bg-gradient-button mb-0 file-upld-btn']); ?>
                <span id="filessnames"></span>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-2">
                <button type="button" class="btn bg-gradient-submit" id="id_exportme">IMPORT FILE</button>
            </div>
            <div class="col-12 pt-2">
                <i style="color: black;">
                    <a href="<?= $this->Url->build('/') ?>webroot/templates/line_master_upload.xlsx" target="_blank" rel="noopener noreferrer">Master Template.xlsx</a>
                </i>
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
    $('#bulk_file').change(function() {
        var file = $(this).prop('files')[0].name;
        $("#filessnames").append(file);
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
            }
        });
    });
</script>