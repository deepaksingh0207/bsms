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
</style>
<?= $this->Html->css('cstyle.css') ?>
  <?= $this->Html->css('custom') ?>
  <?= $this->Html->css('table.css') ?>
  <?= $this->Html->css('listing.css') ?>
  <?= $this->Html->css('b_index.css') ?>
<div class="row content card gate-entry">
    <div class="column-responsive column-80">
        <div class="card-header">
            <div class="d-flex justify-content-between">

                <div class="head-t">
                    <h5 class="text-info pt-2"><b>Details</b></h5>
                </div>
                <div class="actionbtn">
                    <?php
                    $files = json_decode($deliveryDetails->toArray()[0]->invoice_path, true);

                    if (!empty($files) && isset($files[0])) {
                        echo $this->Html->link('View invoice', '/' . $files[0], ['target' => '_blank', 'class' => 'btn mb-1 view-invoice btn-custom-2']);
                    }
                    ?>
                    <?php if ($deliveryDetails->toArray()[0]->status == 2) { ?>

                        <button  class="btn btn-custom mrk mb-1" data-toggle="modal" data-target="#modal-confirm">Mark Entry</button>

                        <!-- modal -->
                        <div class="modal fade" id="modal-confirm" style="display: none;" aria-hidden="true">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-body text-center">
                                        <h6>Are you sure you want to mark entry ?</h6>
                                    </div>
                                    <div class="modal-footer p-1 justify-content-between">
                                        <button type="button" class="mark_entry_cancel addCancel btn btn-sm btn-link" data-dismiss="modal">Cancel</button>
                                        <button class="mark_entry_ok addSubmit btn btn-success btnOk mark_entry btn-sm mb-0" data-id="<?= h($deliveryDetails->toArray()[0]->id) ?>">OK</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- end modal -->
                    <?php } ?>
                </div>

            </div>

        </div>
        <div class="deliveryDetails view content">

            <div class="">

                <div class="card-body gateentry-asn" style="background-color: #f5f7fd !important;">
                    <div class="row">
                        <div class="col-md-2">
                            <label>ASN No.</label>
                            <p><b>
                                    <?= h($deliveryDetails->toArray()[0]->asn_no) ?>
                                </b></p>
                        </div>
                        <div class="col-md-2">
                            <label>PO No.</label>
                            <p><b>
                                    <?= h($deliveryDetails->toArray()[0]->PoHeaders['po_no']) ?>
                                </b></p>
                        </div>
                        <div class="col-md-2">
                            <label>Invoice No</label>
                            <p><b>
                                    <?= h($deliveryDetails->toArray()[0]->invoice_no) ?>
                                </b></p>
                        </div>
                        <div class="col-md-2">
                            <label> Invoice Date :</label>
                            <p><b>
                                    <?= h($deliveryDetails->toArray()[0]->invoice_date) ?>
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
                                <b> <?= h($deliveryDetails->toArray()[0]->gateout_date) ?> </b>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <?php echo $this->Form->control('vehicle_no :', array('class' => 'form-control rounded-0', 'div' => 'form-group', 'required', 'value' => $deliveryDetails->toArray()[0]->vehicle_no)); ?>
                        </div>
                        <div class="col-md-2">
                            <?php echo $this->Form->control('driver_name', array('class' => 'form-control rounded-0', 'div' => 'form-group', 'required', 'value' => $deliveryDetails->toArray()[0]->driver_name)); ?>
                        </div>
                        <div class="col-md-2">
                            <?php echo $this->Form->control('driver_contact', array('type' => 'mobile', 'class' => 'form-control rounded-0', 'div' => 'form-group', 'required', 'value' => $deliveryDetails->toArray()[0]->driver_contact)); ?>
                        </div>

                    </div>

                </div>
            </div>

            <div class="card-body mt-3">
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
                                    <?= $deliveryDetail->has('PoItemSchedules') ? $deliveryDetail->PoItemSchedules['delivery_date'] : '' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
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

    $('.mark_entry').click(function () {
        var dataId = $('.btnOk').data('id');

        $.ajax({
            type: "GET",
            url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/asn', 'action' => 'update')); ?>/" + dataId,
            contentType: "application/x-www-form-urlencoded; charset=utf-8",
            dataType: "json",
            // async: false,
            beforeSend: function () {
                $("#loaderss").show();
            },
            success: function (response) {
                if (response.status == 'success') {

                    $("#modal-confirm").modal('hide');
                    $(".mrk").hide();
                    $(".asnstatus").html('Received');
                } else {
                    alert('Please try again...');
                }
            },
            complete: function () {
                $("#loaderss").hide();
            }
        });
    });
</script>