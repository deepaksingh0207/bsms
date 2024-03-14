<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Stockupload $stockupload
 */

use PhpOffice\PhpSpreadsheet\Calculation\Information\Value;

?>
<?= $this->Html->css('select2.min.css') ?>
<?= $this->Html->script('select2.js') ?>
<?= $this->Html->css('bootstrap-multiselect') ?>
<?= $this->Html->css('dropdown-filter') ?>
<?= $this->Html->script('bootstrap-multiselect') ?>
<?= $this->Html->script('FilterMultiSelect') ?>

<div class="row">
    <div class="col-12 d-none">
        <?= $this->Form->create($stockupload, ['id' => 'stockuploadForm']) ?>
        <div class="card mb-2">
            <div class="card-header pb-1 pt-2">
                <div class="row">
                    <div class="col-lg-6 d-flex justify-content-start">
                        <h5 class="mb-0"><b>Add Stock Upload</b></h5>
                    </div>
                </div>
            </div>
            <div class="card-body invoice-details">
                <div class="row dgf m-0">
                    <div class="col-sm-8 col-md-3">
                        <div class="form-group">
                            <?php echo $this->Form->control('code', array('class' => 'form-control w-100', 'options' => $vendor_mateial, 'id' => 'descripe', 'style' => "height: unset !important;", 'value' => $this->getRequest()->getData('vendor_material_code'), 'empty' => 'Please Select', 'label' => 'Material Code')); ?>
                        </div>
                    </div>

                    <div class="col-sm-8 col-md-3">
                        <div class="form-group">
                            <?php echo $this->Form->control('description', array('type' => 'text', 'class' => 'form-control rounded-0 w-100', 'style' => "height: unset !important;", 'div' => 'form-group', 'required', 'label' => 'Material Description', 'readonly')); ?>
                        </div>
                    </div>
                    <div class="col-sm-8 col-md-3">
                        <div class="form-group">
                            <?php echo $this->Form->control('uom', array('type' => 'text', 'class' => 'form-control rounded-0 w-100', 'style' => "height: unset !important;", 'div' => 'form-group',  'label' => 'Unit Of Measurement', 'readonly')); ?>
                        </div>
                    </div>

                    <div class="col-sm-8 col-md-3">
                        <div class="form-group">
                            <?php echo $this->Form->control('opening_stock', array('type' => 'number', 'class' => 'form-control rounded-0 w-100', 'min' => "1", 'style' => "height: unset !important;", 'div' => 'form-group')); ?>
                        </div>
                    </div>
                    <div class="col-sm-8 col-md-3 d-flex justify-content-start align-items-end">
                        <button type="button" class="btn bg-gradient-submit" id="stockClick">Submit</button>
                        <button type="submit" style="display: none;" id="stockInputSubmit">Submit</button>
                    </div>
                </div>
            </div>
        </div>
        <?= $this->Form->end() ?>
    </div>
    <div class="col-12">
        <div class="card mb-2">
            <div class="card-header">
                <div class="row col-lg-12 pr-0 d-flex justify-content-between align-items-center">
                    <div class="col-sm-6 col-lg-6 pl-0">
                        UPLOAD STOCKS
                    </div>
                    <div class="stock_fileupload col-sm-6 col-lg-6 pr-0">
                        <?= $this->Form->create(null, ['id' => 'formUpload', 'url' => ['controller' => '/stock-uploads', 'action' => 'upload']]) ?>
                        <div class="row justify-content-end align-items-center">
                            <div class="d-flex justify-content-end">

                                <a href="<?= $this->Url->build('/') ?>webroot/templates/material_stock_upload_buyer.xlsx"
                                    download class="material_stock_file" data-toggle="tooltip"
                                    data-original-title="Download Template" data-placement="left"><i
                                        class="fa fa-solid fa-file-download"></i></a>

                            </div>
                            <div class="pl-2 pr-0">
                                <?= $this->Form->control('upload_file', [
                                'type' => 'file', 'label' => false, 'class' => 'pt-1 rounded-0', 'style' => 'visibility: hidden; position: absolute;', 'div' => 'form-group', 'id' => 'bulk_file']); ?>
                                <?= $this->Form->button('Choose File', ['id' => 'OpenImgUpload','type' => 'button','class' => 'd-block btn bg-gradient-button btn-block mb-0 file-upld-btn' ]); ?>
                                <!-- <span id="filessnames"></span> -->
                            </div>
                            <div class="pl-2 pr-1">
                                <button class="btn bg-gradient-submit" id="id_import" type="button">
                                    Upload
                                </button>
                            </div>

                        </div>
                        <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>

            

            

        </div>
    </div>
</div>

<div class="card mb-2">
    <div class="card-header">SEARCH</div>
    <div class="card-body">
                <form method="post">
                    <?= $this->Html->meta('csrfToken', $this->request->getAttribute('csrfToken')); ?>
                    <div class="row">
                        <div class="col-sm-12 col-md-3 col-lg-2">
                            <label for="">Vendor</label>
                            <select class="form-control" name="sap_vendor_code" required
                                id="id_sap_vendor_code"></select>
                        </div>
                        <div class="col-sm-12 col-md-3 col-lg-2">
                            <label for="">Factory Code</label>
                            <select class="form-control" name="vendor_factory_id" required
                                id="id_vendor_factory_id"></select>
                        </div>
                        <div class="col-sm-12 col-md-3 col-lg-2">
                            <label for="">Material</label>
                            <select class="form-control chosen" name="material_id" required
                                id="id_material_id"></select>
                        </div>
                        <div class="col-sm-12 col-md-3 col-lg-2">
                            <label for="">Opening Stock</label>
                            <input class="form-control numberonly" maxlength="10" type="text" required name="opening_stock" id="id_opening_stock">
                        </div>
                        <div class="col-sm-12 col-md-3 col-lg-2 mt-3 pt-2">
                            <button type="button" class="btn bg-gradient-submit mt-2" id="id_mslsubmit">Add</button>
                        </div>
                    </div>
                </form>
            </div>
</div>

<div class="card mb-2">
<div class="card-body" id="id_pohead">
                <div class="table-responsive">
                    <table class="table table-hover" id="example1">
                        <thead>
                            <tr>
                                <th>Sap Vendor Code</th>
                                <th>Factories Code</th>
                                <th>Po No</th>
                                <th>Material Code</th>
                                <th>Line Item</th>
                                <th>Material Description</th>
                                <th>Opening Stock</th>
                                <th>Uom</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>

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
                        </tbody>
                    </table>
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
                <button type="button" class="btn" style="border:1px solid #6610f2" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn addSubmit" style="border:1px solid #28a745">Ok</button>
            </div>
        </div>
    </div>
</div>


<script>
    $('.chosen').select2({
        closeOnSelect: false,
        placeholder: 'Select',
        allowClear: true,
        tags: false,
        tokenSeparators: [','],
        templateSelection: function (selection) {
            if (selection.element && $(selection.element).attr('data-select') !== undefined) {
                return $(selection.element).attr('data-select');
            } else { return selection.text; }
        }
    });
    $("#stockuploadForm").validate({
        rules: {
            code: {
                required: true
            },
            description: {
                required: true
            },
            uom: {
                required: true
            },
            opening_stock: {
                required: true,
                number: true
            }
        },
        messages: {
            code: {
                required: "Please select a material code"
            },
            description: {
                required: "Please enter a material description"
            },
            uom: {
                required: "Please enter a unit of measurement"
            },
            opening_stock: {
                required: "Please enter the opening stock quantity",
                number: "Please enter a valid number"
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function (form, event) {
            event.preventDefault();
            $('#modal-confirm').modal('show');
            return false;
        }
    });


    $('#stockClick').click(function () {
        if ($('#stockuploadForm').valid()) {
            $('#modal-sm').modal('show');
            submitStatus = true
        }
    });

    $('#stockFileSubmit').click(function () {
        $('#modal-sm').modal('show');
        submitStatus = false
    });


    $('.addSubmit').click(function () {
        if (submitStatus && $('#stockuploadForm').valid()) {
            $('#stockuploadForm')[0].submit();
        } else {
            $('#id_import').trigger('click');
        }
        $('#modal-sm').modal('hide');

    });

    $.ajax({
        url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/materials', 'action' => 'getvendor')); ?>",
        type: "get",
        dataType: 'json',
        processData: false, // important
        contentType: false, // important
        // data: fd,
        beforeSend: function () { $("#gif_loader").show(); },
        success: function (r) {
            if (r.status == 1) {
                // Toast.fire({ icon: 'success', title: r.message });
                console.log(r.data);
                $('#id_sap_vendor_code').append($('<option value=""></option>').text('Select Vendor'));
                $.each(r.data, function (key, value) {
                    $('#id_sap_vendor_code')
                        .append($("<option></option>")
                            .attr("value", value['sap_vendor_code'])
                            .text(value['sap_vendor_code'] + " - " + value['name']));
                });
            } else {
                //  Toast.fire({ icon: 'error', title: r.message }); 
            }
        },
        error: function () {
            Toast.fire({ icon: 'error', title: 'An error occured, please try again.' });
        },
        complete: function () { $("#gif_loader").hide(); }
    });

    $(document).on("change", "#id_sap_vendor_code", function () {
        $.ajax({
            url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/stock-uploads', 'action' => 'getvendorfactory')); ?>/" + $("#id_sap_vendor_code").val() + "/",
            type: "get",
            dataType: 'json',
            processData: false, // important
            contentType: false, // important
            beforeSend: function () { $("#gif_loader").show(); },
            success: function (r) {
                $('#id_vendor_factory_id').empty();
                $.each(r.data, function (key, value) {
                    $('#id_vendor_factory_id')
                        .append($("<option></option>")
                            .attr("value", value['id'])
                            .text(value['factory_code']));
                });
            },
            error: function () {
                Toast.fire({ icon: 'error', title: 'An error occured, please try again.' });
            },
            complete: function () { $("#gif_loader").hide(); }
        });

        $.ajax({
            url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/materials', 'action' => 'getvendorsmaterial')); ?>/" + $("#id_sap_vendor_code").val() + "/",
            type: "get",
            dataType: 'json',
            processData: false, // important
            contentType: false, // important
            beforeSend: function () { $("#gif_loader").show(); },
            success: function (r) {
                $('#id_material_id').empty();
                $.each(r.data, function (key, value) {
                    $('#id_material_id')
                        .append($("<option></option>")
                            .attr("value", value['id'])
                            .text(value['code'] + " - " + value['description']));
                });
            },
            error: function () {
                Toast.fire({ icon: 'error', title: 'An error occured, please try again.' });
            },
            complete: function () { $("#gif_loader").hide(); }
        });
    });

    $(document).on("click", "#id_mslsubmit", function () {
        if (!$("#id_sap_vendor_code").val()) {
            Toast.fire({ icon: 'error', title: 'Vendor Mandatory' });
        } else if (!$("#id_vendor_factory_id").val() ) {
            Toast.fire({ icon: 'error', title: 'Factory Mandatory' });
        } else if (!$("#id_material_id").val() ) {
            Toast.fire({ icon: 'error', title: 'Material Mandatory' });
        } else if (parseFloat($("#id_opening_stock").val()) >= parseFloat(0)) {
            $.ajax({
                url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/stock-uploads', 'action' => 'poststockupload')); ?>",
                type: "post",
                data: {
                    sap_vendor_code: $("#id_sap_vendor_code").val(),
                    vendor_factory_id: $("#id_vendor_factory_id").val(),
                    material_id: $("#id_material_id").val(),
                    opening_stock: $("#id_opening_stock").val(),
                },
                dataType: 'json',
                headers: { 'X-CSRF-Token': $('meta[name="csrfToken"]').attr('content') },
                success: function (r) {
                    if (r.status == 1) {
                        Toast.fire({ icon: 'success', title: r.msg });
                        location.reload();
                    }
                    else { Toast.fire({ icon: 'error', title: r.msg }); }
                },
                error: function () {
                    Toast.fire({ icon: 'error', title: 'An error occured, please try again.' });
                },
            });
        } else {
            Toast.fire({ icon: 'error', title: 'Opening Stock Mandatory' });
        }
    });


    $(document).ready(function () {

        var table = $("#example1").DataTable({
            "paging": true,
            "responsive": false,
            "lengthChange": false,
            "autoWidth": false,
            "searching": false,
            "sorting": false,
            "ordering": true,
        });

        var submitStatus;

        var csrf = $("input[name=_csrfToken]").val();
        $("input").val("");
        $('select').prop('selectedIndex', 0);
        $("input[name=_csrfToken]").val(csrf);

        $('#OpenImgUpload').click(function () {
            $('#bulk_file').trigger('click');
        });
        $('#bulk_file').change(function () {
            var file = $(this).prop('files')[0];
            var fileName = file ? file.name : '';

            $('#OpenImgUpload').text(fileName ? fileName : 'Choose File');
        });

        setTimeout(function () {
            $('.success').fadeOut('slow');
        }, 2000);

        $("#descripe").change(function () {
            var vendorId = $(this).val();
            if (vendorId != "") {
                $.ajax({
                    type: "get",
                    url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/stock-uploads', 'action' => 'material')); ?>/" + vendorId,
                    dataType: "json",
                    beforeSend: function (xhr) {
                        $("#gif_loader").show();
                        xhr.setRequestHeader(
                            "Content-type",
                            "application/x-www-form-urlencoded"
                        );
                    },
                    success: function (response) {
                        if (response.status == "1") {
                            $("#description").val(response.data.description);
                            $("#uom").val(response.data.uom);
                        }
                    },
                    error: function (e) {
                        alert("An error occurred: " + e.responseText.message);
                        console.log(e);
                    },
                    complete: function () { $("#gif_loader").hide(); }
                });
            }
        });
    });

    $("#id_import").click(function () {
        var fd = new FormData($('#formUpload')[0]);

        $.ajax({
            url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/stock-uploads', 'action' => 'upload')); ?>",
            type: "post",
            dataType: 'json',
            processData: false, // important
            contentType: false, // important
            data: fd,
            beforeSend: function () { $("#gif_loader").show(); },
            success: function (response) {
                if (response.status == 1) {
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    });


                    $("#example1 tbody").empty();

                    // Loop through the response data and build the table rows dynamically
                    $.each(response.data, function (key, val) {
                        var rowHtml = `<tr>
                        <td>` + val.sap_vendor_code + `</td>
                        <td> `+ val.factory_code + `</td>
                        <td> `+ val.po_no + ` </td>
                        <td> `+ val.material + ` </td>
                        <td> `+ val.line_item + `</td>
                        <td> `+ val.description + `</td>
                        <td> `+ val.opening_stock + `</td>
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
                $("#formUpload")[0].reset();
            },
            error: function () {
                Toast.fire({
                    icon: 'error',
                    title: 'An error occured, please try again.'
                });
            },
            complete: function () { $("#gif_loader").hide(); }
        });
    });
</script>