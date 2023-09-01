<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DeliveryDetail[]|\Cake\Collection\CollectionInterface $deliveryDetails
 */
?>
<!-- <?= $this->Html->css('cstyle.css') ?> -->
<!-- <?= $this->Html->css('table.css') ?> -->
<!-- <?= $this->Html->css('listing.css') ?> -->
<!-- <?= $this->Html->css('v_vendorCustom') ?> -->

<div class="row">
    <div class="col-12">
        <div class="deliveryDetails index content card intransit">
            <div class="card-header p-2">
                <h5 class="mb-0">
                    <b>
                        <?= __('DELIVERY DETAILS') ?>
                    </b>
                </h5>
            </div>
            <div class="card-body p-2">
                <table class="table table-striped table-bordered table-hover" id="example1">
                    <thead>
                        <tr>

                            <th>Asn No</th>
                            <th>Purchase Order</th>
                            <th>Invoice No</th>
                            <th>Invoice value</th>
                            <th>Status</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($deliveryDetails as $deliveryDetail):
                            switch ($deliveryDetail->status) {
                                case 2:
                                    $status = '<a class="btn btn-light text-primary pb-0 pt-0" style="border: 1px solid lightblue;"><i class="fas fa-truck" data-toggle="tooltip" title="In Transit" data-widget="chat-pane-toggle"></i></a>';
                                    break;
                                case 3:
                                    $status = '<a class="btn btn-light text-success" style="border: 1px solid lightblue;"><i class="fas fa-truck-loading" data-toggle="tooltip" title="Received" data-widget="chat-pane-toggle"></i></a>';
                                    break;

                        } ?>
                        <tr>

                            <td>
                                <?=  $deliveryDetail->asn_no ?>
                            </td>
                            <td>
                                <?= $deliveryDetail->has('po_header') ? $deliveryDetail->po_header->po_no : '' ?>
                            </td>
                            <td>
                                <?= h($deliveryDetail->invoice_no) ?>
                            </td>
                            <td>
                                <?= h($deliveryDetail->invoice_value).' '. h($deliveryDetail->po_header->currency)?>
                            </td>
                            <td>
                                <?= $status  ?>     
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
            "responsive": false, "lengthChange": false, "autoWidth": false, "searching": true,
            "ordering": true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search..."
            },
        });
    });

</script>