<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\VendorMaterial $vendorMaterial
 */
?>
<?= $this->Html->css('custom') ?>
<?= $this->Html->css('table.css') ?>
<?= $this->Html->css('listing.css') ?>
<?= $this->Html->css('v_index.css') ?>
<style>
    label.error {
        color: red !important;
    }
</style>
<div class="card mb-2">
    <div class="upld-material-head card-header d-flex justify-content-between align-items-center">
        <div class=" col-sm-6 col-lg-6 pl-0">
            UPLOAD MATERIAL MASTER
        </div>
        <div class="material_fileupld col-sm-6 col-lg-6 form-content d-flex justify-content-end pr-0 pl-0">
            <?= $this->Form->create($materials, ['id' => 'formUpload', 'url' => ['controller' => '/materials', 'action' => 'upload']]) ?>
            <div class="row align-items-center">
                <div class="col-auto pr-1" data-toggle="tooltip" data-original-title="Download Template"
                    data-placement="left">
                    <a href="<?= $this->Url->build('/') ?>webroot/templates/material_master.xlsx" class="material_file"
                        download target="_blank" rel="noopener noreferrer">
                        <i class="fa fa-solid fa-file-download"></i></a>
                </div>
                <div class="col-auto pr-1">
                    <?= $this->Form->control('upload_file', [
                    'type' => 'file',
                    'label' => false,
                    'class' => 'pt-1 rounded-0',
                    'style' => 'visibility: hidden; position: absolute;',
                    'div' => 'form-group',
                    'id' => 'bulk_file'
                ]); ?>
                    <?= $this->Form->button('Upload File', [
                    'id' => 'OpenImgUpload',
                    'type' => 'button',
                    'label' => 'Upload File',
                    'class' => 'upload_file d-block btn bg-gradient-button mb-0 file-upld-btn'
                ]); ?>
                </div>
                <div class="col-auto pr-0">
                    <button class="btn bg-gradient-submit" id="id_import" type="button">Submit</button>
                </div>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<div class="card mb-2">
    <div class="card-header">SEARCH</div>
        <div class="card-body">
        <form id="id_msl" method="post">
            <?= $this->Html->meta('csrfToken', $this->request->getAttribute('csrfToken')); ?>
            <div class="row">
                <div class="col-sm-12 col-md-4 col-lg-3">
                    <label for="id_sap_vendor_code">Vendor</label>
                    <select name="sap_vendor_code" required maxlength="10" class="form-control" id="id_sap_vendor_code">
                    </select>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-3">
                    <label for="id_code">Material Code</label>
                    <!-- <select name="code" class="form-control" required id="id_code"></select> -->
                    <input type="text" class="form-control caps" required name="code" maxlength="20" id="id_code">
                </div>
                <div class="col-sm-12 col-md-2 col-lg-2">
                    <label for="id_minimum_stock">Minimum Stock</label>
                    <input type="text" name="minimum_stock" maxlength="10" required class="form-control numberonly" id="id_minimum_stock">
                </div>
                <div class="col-sm-12 col-md-2 col-lg-1 mt-3 pt-3">
                    <button type="submit" class="btn bg-gradient-submit" id="id_mslsubmit">Submit</button>
                </div>
            </div>
        </form>
        </div>
    
</div>

<div class="card mb-2">
    <div class="card-header p-0 mb-0 mt-3 ml-3 mr-3" id="id_pohead">
        <div class="table-responsive">
            <table class="table table-hover" id="example1">
                <thead>
                    <tr>
                        <th>Vendor Code</th>
                        <th>Material Code</th>
                        <th>Material Description</th>
                        <th>Minimum Stock</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
<script>
    function showConfirmationModal() { $('#modal-sm').modal('show'); }

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
                // console.log(r.data);
                $('#id_sap_vendor_code').empty();
                $('#id_sap_vendor_code').append($("<option value=''></option>").text('Select Vendor'));
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

    // $(document).on("change", "#id_sap_vendor_code", function () {
    //     $.ajax({
    //         url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/materials', 'action' => 'getvendormaterial')); ?>/" + $("#id_sap_vendor_code").val() + "/",
    //         type: "get",
    //         dataType: 'json',
    //         processData: false, // important
    //         contentType: false, // important
    //         beforeSend: function () { $("#gif_loader").show(); },
    //         success: function (r) {
    //             $('#id_code').empty();
    //             $.each(r.data, function (key, value) {
    //                 $('#id_code')
    //                     .append($("<option></option>")
    //                         .attr("value", value['code'])
    //                         .text(value['code'] + " - " + value['description']));
    //             });
    //         },
    //         error: function () {
    //             Toast.fire({ icon: 'error', title: 'An error occured, please try again.' });
    //         },
    //         complete: function () { $("#gif_loader").hide(); }
    //     });
    // });

    // $(document).on("click", "#id_mslsubmit", function () {
    //     if("#id_msl")


    $("#id_msl").validate({
        // Specify validation rules
        rules: {
            sap_vendor_code: "required",
            code: "required",
            minimum_stock: "required",
        },
        // Specify validation error messages
        messages: {
            sap_vendor_code: "Please select vendor",
            code: "Please enter material",
            minimum_stock: "Please enter minimum stock"
        },
        submitHandler: function (form) {
            $.ajax({
                url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/materials', 'action' => 'postmsl')); ?>",
                type: "post",
                data: {
                    sap_vendor_code: $("#id_sap_vendor_code").val(),
                    code: $("#id_code").val(),
                    minimum_stock: $("#id_minimum_stock").val()
                },
                dataType: 'json',
                headers: { 'X-CSRF-Token': $('meta[name="csrfToken"]').attr('content') },
                success: function (r) {
                    if (r.status == 1) {
                        Toast.fire({ icon: 'success', title: r.data });
                        location.reload();
                    }
                    else { Toast.fire({ icon: 'error', title: r.data }); }
                },
                error: function () {
                    Toast.fire({ icon: 'error', title: 'An error occured, please try again.' });
                },
            });
        }
    });

    // $("#id_msl").on('submit', function (e) {
        // e.preventDefault();

        
        // Toast.fire({ icon: 'error', title: 'Blah Blah' });
    // });

    $(document).ready(function () {
        $('.addSubmit').click(function () {
            if (submitStatus && $('#stockuploadForm').valid()) { $('#stockuploadForm')[0].submit(); }
            else { $('#id_import').trigger('click'); }
            $('#modal-sm').modal('hide');
        });

        $('#OpenImgUpload').click(function () { $('#bulk_file').trigger('click'); });

        $('#bulk_file').change(function () {
            var file = $(this).prop('files')[0];
            var fileName = file ? file.name : '';
            $('#OpenImgUpload').text(fileName ? fileName : 'Choose File');
        });

        setTimeout(function () { $('.success').fadeOut('slow'); }, 2000);

        $("#id_import").click(function () {
            var fd = new FormData($('#formUpload')[0]);

            $.ajax({
                url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/materials', 'action' => 'upload')); ?>",
                type: "post",
                dataType: 'json',
                processData: false, // important
                contentType: false, // important
                data: fd,
                beforeSend: function () { $("#gif_loader").show(); },
                success: function (response) {
                    if (response.status == 1) {
                        Toast.fire({ icon: 'success', title: response.message });
                        $("#example1 tbody").empty();

                        // Loop through the response data and build the table rows dynamically
                        $.each(response.data, function (key, val) {
                            // Format val.minimum_stock to always show two decimal places
                            var formattedMinimumStock = parseFloat(val.minimum_stock).toFixed(2);
                            var rowHtml = `<tr>
                            <td>` + val.sap_vendor_code + `</td>
                            <td> `+ val.material_code + `</td>
                            <td> `+ val.description + ` </td>
                            <td> `+ formattedMinimumStock + ` </td>
                            <td> `+ val.uom + `</td>
                            <td> `+ val.error + `</td>
                            </tr>`;
                            $("#example1 tbody").append(rowHtml);
                        });
                    } else { Toast.fire({ icon: 'error', title: response.message }); }
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
    });
</script>