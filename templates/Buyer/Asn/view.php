<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DeliveryDetail $deliveryDetail
 */
?>
<style>
    th,
    td {
        padding: 10px !important;
    }

    label.error {
        color: red !important;
    }
</style>
<!-- <?= $this->Html->css('cstyle.css') ?> -->
<?= $this->Html->css('custom') ?>
<?= $this->Html->css('table.css') ?>
<?= $this->Html->css('listing.css') ?>
<?= $this->Html->css('b_index.css') ?>
<div class="row content card gate-entry">
    <div class="column-responsive column-80">
        <div class="card-header">
            <div class="d-flex justify-content-between">

                <div class="head-t">
                    DETAILS
                </div>
                <div class="actionbtn">
                    <?php
                    $files = json_decode($deliveryDetails->toArray()[0]->invoice_path, true);

                    if (!empty($files) && isset($files[0])) {
                        echo $this->Html->link('View invoice', '/' . $files[0], ['target' => '_blank', 'class' => 'btn mb-1 view-invoice btn-custom-2']);
                    }
                    ?>
                    <?php if ($deliveryDetails->toArray()[0]->status == 2) { ?>

                    <!-- <button  class="btn btn-custom mrk mb-1" data-toggle="modal" data-target="#modal-confirm">Mark Entry</button> -->

                    <!-- modal -->
                    <div class="modal fade" id="modal-confirm" style="display: none;" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-body text-center">
                                    <h6>Are you sure you want to mark entry ?</h6>
                                </div>
                                <div class="modal-footer p-1 justify-content-between">
                                    <button type="button" class="mark_entry_cancel addCancel btn btn-sm btn-link"
                                        data-dismiss="modal">Cancel</button>
                                    <button class="mark_entry_ok addSubmit btn btn-success btnOk mark_entry btn-sm mb-0"
                                        data-id="<?= h($deliveryDetails->toArray()[0]->id) ?>">OK</button>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- end modal -->
                    <?php } ?>
                </div>

            </div>

        </div>
        <div class="deliveryDetails view content pl-3 pr-3 mt-3">

            <div class="">

                <div class="card-body gateentry-asn" style="background-color: #f4f6f9 !important;">
                    <div class="row">
                        <div class="col-md-2">
                            <label>ASN No. :</label>
                            <p><b>
                                    <?= h($deliveryDetails->toArray()[0]->asn_no) ?>
                                </b></p>
                        </div>
                        <div class="col-md-2">
                            <label>PO No. :</label>
                            <p><b>
                                    <?= h($deliveryDetails->toArray()[0]->PoHeaders['po_no']) ?>
                                </b></p>
                        </div>
                        <div class="col-md-2">
                            <label>Invoice No :</label>
                            <p><b>
                                    <?= h($deliveryDetails->toArray()[0]->invoice_no) ?>
                                </b></p>
                        </div>
                        <div class="col-md-2">
                            <label> Invoice Date :</label>
                            <p><b>
                                    <?= h($deliveryDetails->toArray()[0]->invoice_date->i18nFormat('dd-MM-YYYY')) ?>
                                </b></p>
                        </div>
                        <div class="col-md-2">
                            <label> Invoice Value :</label>
                            <p><b>
                                    <?= h($deliveryDetails->toArray()[0]->invoice_value) ?>
                                </b></p>
                        </div>
                        <div class="col-md-2">
                            <label> Status :</label>
                            </td>
                            <p>
                                <?= $deliveryDetails->toArray()[0]->status == 2 ? '<span class="badge bg-success asnstatus">In Transit</span>' : '<span class="badge bg-warning">Received</span>' ?>
                            </p>
                            </td>
                        </div>
                        <div class="col-md-2">
                            <label> Gate Out :</label>
                            <p>
                                <b>
                                    <?= h($deliveryDetails->toArray()[0]->gateout_date->i18nFormat('dd-MM-YYYY')) ?>
                                </b>
                            </p>
                        </div>
                    </div>
                    <?= $this->Form->create(null, ['id' => 'id_msl']) ?>
                    <?= $this->Html->meta('csrfToken', $this->request->getAttribute('csrfToken')); ?>
                    <div class="row">
                        <div class="col-md-2">
                            <?php echo $this->Form->control('vehicle_no', array('class' => 'form-control rounded-0', 'maxlength'=>'12', 'div' => 'form-group', 'required', 'value' => $deliveryDetails->toArray()[0]->vehicle_no)); ?>
                        </div>
                        <div class="col-md-2">
                            <?php echo $this->Form->control('driver_name', array('class' => 'form-control rounded-0', 'div' => 'form-group', 'required', 'maxlength'=>'15', 'value' => $deliveryDetails->toArray()[0]->driver_name)); ?>
                        </div>
                        <div class="col-md-2">
                            <?php echo $this->Form->control('driver_contact', array('type' => 'mobile', 'class' => 'form-control numberonly rounded-0', 'div' => 'form-group', 'maxlength'=>'10', 'required', 'value' => $deliveryDetails->toArray()[0]->driver_contact)); ?>
                        </div>
                        <div class="col-md-2 mt-3 pt-3">
                            <button type="submit" class="btn bg-gradient-submit">Update</button>
                        </div>
                    </div>
                    <?= $this->Form->end() ?>
                </div>
            </div>

            
        </div>
    </div>
</div>

<div class="card">
<div class="card-body">
                <table class="table table-bordered delivery-dt-tbl mb-2">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Material</th>
                            <th>UOM</th>
                            <th>Qty</th>
                            <th>Schedule Qty</th>
                            <th>Schedule Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($deliveryDetails as $deliveryDetail): ?>
                        <tr>
                            <td>
                                <?= $deliveryDetail->has('PoFooters') ? $deliveryDetail->PoFooters['item'] : '' ?>
                            </td>
                            <td>
                                <?= $deliveryDetail->has('PoFooters') ? $deliveryDetail->PoFooters['material'] : '' ?>
                            </td>
                            <td>
                                <?= $deliveryDetail->has('PoFooters') ? $deliveryDetail->PoFooters['order_unit'] : '' ?>
                            </td>
                            <td>
                                <?= $deliveryDetail->has('AsnFooters') ? $deliveryDetail->AsnFooters['qty'] : '' ?>
                            </td>
                            <td>
                                <?= $deliveryDetail->has('PoItemSchedules') ? $deliveryDetail->PoItemSchedules['actual_qty'] : '' ?>
                            </td>
                            <td>
                                <?= $deliveryDetail->has('PoItemSchedules') ? date('d-m-Y', strtotime($deliveryDetail->PoItemSchedules['delivery_date'])) : '' ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
</div>

<script>
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
    });

    $("#id_msl").validate({
        // Specify validation rules
        rules: {
            vehicle_no: "required",
            driver_name: "required",
            driver_contact: {
                required: true,
                number: true,
                maxlength: 10,
                minlength: 10
                //pattern: /^\d{10}$/,
                },
        },
        // Specify validation error messages
        messages: {
            vehicle_no: "required",
            driver_name: "required",
            driver_contact: "required",
        },
        submitHandler: function (form) {
            $.ajax({
                type: "POST",
                url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/asn', 'action' => 'view')); ?>/<?php echo $deliveryDetails->toArray()[0]->id ?>",
                contentType: "application/x-www-form-urlencoded; charset=utf-8",
                dataType: "json",
                data: $("#id_msl").serialize(),
                headers: { 'X-CSRF-Token': $('meta[name="csrfToken"]').attr('content') },
                beforeSend: function () { $("#gif_loader").show(); },
                success: function (response) {
                    if (response.status) {
                        $("#modal-confirm").modal('hide');
                        $(".mrk").hide();
                        Toast.fire({
                        icon: 'success',
                        title: response.message
                    });
                    } else { 
                        Toast.fire({
                        icon: 'error',
                        title: response.message
                    });
                     }
                },
                complete: function () { $("#gif_loader").hide(); }
            });
        }
    });

    $('.mark_entry').click(function () {



    });
</script>