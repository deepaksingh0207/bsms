<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DeliveryDetail $deliveryDetail
 */

//echo '<pre>'; print_r($deliveryDetails); exit;
?>
<!-- <?= $this->Html->css('cstyle.css') ?> -->
<!-- <?= $this->Html->css('table.css') ?> -->
<!-- <?= $this->Html->css('listing.css') ?> -->
<!-- <?= $this->Html->css('v_vendorCustom') ?> -->
<?= $this->Html->css('v_asn_view') ?>
<div class="row">
    <div class="col-12">
        <div class="card p-1">
            <div class="card m-1">
                <div class="card-header asn_header_bg">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-sm-12 col-lg-2">
                            <span class="text_light">Factory :</span> <b>
                                <?= h($deliveryDetails[0]->VendorFactories['factory_code']) ?>
                            </b>
                        </div>
                        <div class="col-sm-12 col-lg-2">
                        <span class="text_light">ASN No :</span> <b>
                                <?= h($deliveryDetails[0]->asn_no) ?>
                            </b>
                        </div>
                        <div class="col-sm-12 col-lg-2">
                        <span class="text_light">PO No : </span><b>
                                <?= h($deliveryDetails[0]->PoHeaders['po_no']) ?>
                            </b>
                        </div>
                        <div class="col-sm-12 col-lg-2">
                        <span class="text_light">Status:</span> <span class='asnstatus'>
                                <?php
                                    if ($deliveryDetails[0]->status == '1') { echo 'Created'; }
                                    else if ($deliveryDetails[0]->status == '2') { echo 'In Transit'; }
                                    else if ($deliveryDetails[0]->status == '3') { echo 'Received'; }
                                ?>
                            </span>
                        </div>
                        <?php
                        if ($deliveryDetails[0]->status == '1') { ?>
                        <div class="col-sm-2 col-lg-2">
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-custom-2 mb-0 mrk" data-toggle="modal"
                                    data-target="#modal-confirm">Mark Dispatched</button>
                            </div>
                        </div>
                        <!-- modal -->
                        <div class="modal fade" id="modal-confirm" style="display: none;" aria-hidden="true">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-body text-center">
                                        <h6>Are you sure you want to mark dispatched ?</h6>
                                    </div>
                                    <div class="modal-footer justify-content-between p-1">
                                        <button type="button" class="cancel_btn btn btn-sm btn-link"
                                            data-dismiss="modal">Cancel</button>
                                        <button class="btn btn-success mark_delivered btn-sm mb-0">OK</button>
                                        <!-- <?= $this->Html->link(__('Ok'), ['class' => 'btn btn-success mark_delivered btn-sm mb-0']) ?> -->
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- end modal -->
                        <?php } ?>

                        <div class="col-sm-12 col-lg-6">
                            <div class="d-flex justify-content-start mt-4 align-items-center">
                                <?php $files = json_decode($deliveryDetails[0]->invoice_path, true);

                                    if (!empty($files)) {
                                        foreach ($files as $key => $file) {
                                            echo '<i class="fa fa-download asn_download_icon"></i>';
                                            echo $this->Html->link(' ' .$key, '/' . $file, ['style' => 'display:block;', 'target' => '_blank', 'class' => 'asn_files mb-0 invoicefiles']);
                                        }
                                    }


                                ?>
                                
                            </div>
                        </div>

                    </div>

                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <h5 class="tracking-det"><b><?= __('Tracking Details') ?></b></h5>
                        </div>
                    </div>
                    <div class="row tracking-dt">
                        <div class="col-sm-12 col-lg-2 mt-2">
                            <span class="tracking_details">Invoice No. :</span><br>
                            <b><?= h($deliveryDetails[0]->invoice_no) ?></b>
                        </div>
                        <div class="col-sm-12 col-lg-2 mt-2">
                        <span class="tracking_details">Invoice Date :</span><br>
                            <b><?= h($deliveryDetails[0]->invoice_date) ?></b>
                        </div>
                        <div class="col-sm-12 col-lg-2 mt-2">
                        <span class="tracking_details">Invoice Value :</span><br>
                            <b><?= h($deliveryDetails[0]->invoice_value) ?></b>
                        </div>
                        <div class="col-sm-12 col-lg-2 mt-2">
                        <span class="tracking_details">Vehicle No. :</span><br>
                            <b> <?= h($deliveryDetails[0]->vehicle_no) ?> </b>
                        </div>
                        <div class="col-sm-12 col-lg-2 mt-2">
                        <span class="tracking_details">Driver Name :</span><br>
                            <b><?= h($deliveryDetails[0]->driver_name) ?></b>
                        </div>
                        <div class="col-sm-12 col-lg-2 mt-2">
                        <span class="tracking_details">Driver Contact :</span><br>
                            <b><?= h($deliveryDetails[0]->driver_contact) ?></b>
                        </div>

                        <!-- <div class="col-sm-12 col-lg-2">
                            <?php $files = json_decode($deliveryDetails[0]->invoice_path, true);

                            if (!empty($files)) {
                                echo $this->Html->link('View invoice', '/' . $files[0], ['target' => '_blank', 'class' => 'btn btn-custom mt-2 mb-0']);
                            }
                            ?>
                        </div> -->
                    </div>


                </div>
            </div>
            <div class="card-body p-1">
                <table class="table" id="example1">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Material</th>
                            <th>UOM</th>
                            <th>ASN Qty</th>
                            <th>Schedule Qty</th>
                            <th>Schedule Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($deliveryDetails as $deliveryDetail) : ?>
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
            "ordering": false,
            "searching": false,
            "sorting": false,
        });

        $('.invoiceButton').click(function () {
            $('.invoicefiles').each(function () {
                var fileUrl = $(this).attr('href');
                window.open(fileUrl, '_blank');
            });
        });
        $(".mark_delivered").on('click', function () {

            $.ajax({
                type: "GET",
                url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/asn', 'action' => 'mark-delivered', $deliveryDetails[0]->id)); ?>",
                contentType: "application/x-www-form-urlencoded; charset=utf-8",
                dataType: "json",
                // async: false,
                beforeSend: function () { $("#gif_loader").show(); },
                success: function (response) {
                    if (response.status) {
                        //location.reload(true);
                        $("#modal-confirm").modal('hide');
                        $(".mrk").hide();
                        $(".asnstatus").html('In-Transit');
                        Toast.fire({
                            icon: "success",
                            title: response.message,
                        });
                    } else {
                        Toast.fire({
                            icon: "error",
                            title: response.message,
                        });
                    }
                },
                complete: function () { $("#gif_loader").hide(); }
            });
        });



    });
</script>