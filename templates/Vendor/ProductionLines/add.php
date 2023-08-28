<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Productionline $productionline
 */
?>
<!-- <?= $this->Html->css('cstyle.css') ?> -->
<!-- <?= $this->Html->css('custom') ?> -->
<!-- <?= $this->Html->css('table.css') ?> -->
<!-- <?= $this->Html->css('listing.css') ?> -->
<?= $this->Form->create($productionline, ['id'=>'productionLineForm']) ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-6 d-flex justify-content-start">
                        <h5><b>Add Production Line</b></h5>
                    </div>
                </div>
            </div>
            <div class="card-body invoice-details">
                <div class="row">

                    <div class="col-sm-12 col-md-3 col-lg-3">
                        <div class="form-group">
                            <?php echo $this->Form->control('vendor_factory_id', array('class' => 'form-control w-100', 'options' => $factory, 'style' => "height: unset !important;", 'empty' => 'Please Select', 'label' => 'Factory', 'required')); ?>

                        </div>
                    </div>
                    <div class="col-sm-8 col-md-3">
                        <div class="form-group">
                            <?php echo $this->Form->control('line_master_id', array('class' => 'form-control w-100', 'options' => $lineMasterList, 'empty' => 'Please Select', 'label' => 'Line')); ?>
                        </div>
                    </div>


                    <div class="col-sm-8 col-md-3">
                        <div class="form-group">
                            <?php echo $this->Form->control('material_id', array('class' => 'form-control w-100', 'options' => $vendor_mateial, 'empty' => 'Please Select', 'label' => 'Material')); ?>
                        </div>
                    </div>
  
                    <div class="col-sm-8 col-md-2">
                        <div class="form-group">
                            <?php echo $this->Form->control('capacity', array('type' => 'number', 'class' => 'form-control rounded-0 w-100', 'div' => 'form-group', 'required', 'label' => 'Capacity (Per Day)')); ?>
                        </div>
                    </div>

                    <div class="col-sm-8 col-md-1 d-flex justify-content-end align-items-end">
                        <div class="form-group">
                        <button type="button" class="btn bg-gradient-submit"
                            onclick="showConfirmationModal()" id="submit-btn">Submit</button>
                        </div>
                    </div>

                    <div id="line-capacity-view" style="display:none;">Capacity : <span id="line-capacity"></span>
                        &nbsp; &nbsp;
                        Total : <span id="total"></span> &nbsp; &nbsp;
                        Balance : <span id="balance"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-sm" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h6>Are you sure you want to Add?</h6>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn" style="border:1px solid #6610f2"
                            data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn" style="border:1px solid #28a745">Ok</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->Form->end() ?>

<?= $this->Form->create(null, ['id' => 'formUpload', 'url' => ['controller' => '/production-lines', 'action' => 'upload']]) ?>
<div class="card">
    <div class="card-header">
        <h5><b>Bulk upload Production Line</b></h5>
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
                    <a href="<?= $this->Url->build('/') ?>webroot/templates/production_line_upload.xlsx" target="_blank" rel="noopener noreferrer">Master Template.xlsx</a>
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
                <th>Material</th>
                <th>Capacity</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<script>
    function showConfirmationModal() {
        if ($('#productionLineForm').valid()) {
            checkRecordExists();
        }
    }


    $("#descripe").change(function () {
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

    var capacity = 0;
    var capacityBal = 0;
    var total = 0;

    $("#line-master-id").change(function () {
        var lineId = $(this).val();
        $("#line-capacity-view").hide();
        capacity = 0;
        capacityBal = 0;
        total = 0;

        if (lineId != "") {
            $.ajax({
                type: "get",
                url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/line-masters', 'action' => 'get-detail')); ?>/" + lineId,
                dataType: "json",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader(
                        "Content-type",
                        "application/x-www-form-urlencoded"
                    );
                },
                success: function (response) {
                    if (response.status) {
                        capacity = response.data.capacity;
                        total = response.data.total;
                        capacityBal = response.data.balance;

                        $("#line-capacity-view").show();
                        $("#line-capacity").text(capacity);
                        $("#total").text(total);
                        $("#balance").text(capacityBal);
                        if (capacityBal <= 0) {
                            $("#submit-btn").attr('disabled', 'disabled');
                            $("#capacity").attr('disabled', 'disabled');
                        } else {
                            $("#submit-btn").removeAttr('disabled');
                            $("#capacity").removeAttr('disabled');
                        }

                    }
                },
                error: function (e) {
                    alert("An error occurred: " + e.responseText.message);
                    console.log(e);
                },
            });
        }
    });

    $("#capacity").keyup(function () {
        var val = $(this).val();
        console.log(val);
        if (val > capacityBal) {
            $(this).val(capacityBal);
        }
    });

    $("#vendor-factory-id").change(function () {
        var lineId = $(this).val();
        $("#line-master-id").empty().append("<option value=''>Please Select</option>");
        $("#material-id").empty().append("<option value=''>Please Select</option>");

        if (lineId != "") {
            $.ajax({
                type: "get",
                url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/line-masters', 'action' => 'get-factory-lines')); ?>/" + lineId,
                dataType: "json",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader(
                        "Content-type",
                        "application/x-www-form-urlencoded"
                    );
                },
                success: function (response) {
                    if (response.status) {
                        $.each(response.data.lines, function (key, val) { 
                             $("#line-master-id").append("<option value='"+val.id+"'>"+val.name+"</option>");
                        });
                        $.each(response.data.materials, function (key, val) { 
                             $("#material-id").append("<option value='"+val.id+"'>"+val.description+"</option>");
                        });
                    }
                },
                error: function (e) {
                    alert("An error occurred: " + e.responseText.message);
                    console.log(e);
                },
            });
        }
    });

    
    function checkRecordExists() {

        var factory = $("#vendor-factory-id").val();
        var line = $("#line-master-id").val();
        var material = $("#material-id").val()

        $.ajax({
            type: "post",
            url: "<?php echo \Cake\Routing\Router::url(array('action' => 'check-record-exists')); ?>",
            dataType: "json",
            data: {line: line, material:material},
            beforeSend: function (xhr) {
                xhr.setRequestHeader(
                    "Content-type",
                    "application/x-www-form-urlencoded"
                );
                xhr.setRequestHeader(
                    'X-CSRF-Token',
                    <?= json_encode($this->request->getAttribute('csrfToken')); ?>
                );
                
            },
            success: function (response) {
                if (response.status) {
                    $('#modal-sm').modal('show');
                } else {
                    Toast.fire({
                    icon: 'error',
                    title: response.message
                    });
                }
            },
            error: function (e) {
                alert("An error occurred: " + e.responseText.message);
                console.log(e);
            },
        });
    }

</script>

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
            url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/production-lines', 'action' => 'upload')); ?>",
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
                        <td> `+ val.line +`</td>
                        <td> `+ val.material + `</td>
                        <td> `+ val.capacity + `</td>
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