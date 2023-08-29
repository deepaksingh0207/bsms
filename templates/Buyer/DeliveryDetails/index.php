<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DeliveryDetail[]|\Cake\Collection\CollectionInterface $deliveryDetails
 */
?>

<div class="deliveryDetails index content card">
    <div class="card-body table-responsive">
        <table class="table table-hover" id="example1">
            <thead>
                <tr>

                    <th>Asn No</th>
                    <th>Vendor Code</th>
                    <th>Purchase Order</th>
                    <th>invoice No</th>
                    <th>invoice value</th>
                    <th>Status</th>

                </tr>
            </thead>
            <tbody>
          

                <?php foreach ($deliveryDetails as $deliveryDetail) : 
                     switch ($deliveryDetail->status) {
                        case 2:
                            $status = '<a class="btn btn-light text-primary"><i class="buyer-status-icon fas fa-truck" data-toggle="tooltip" title="In Transit" data-widget="chat-pane-toggle"></i></a>';
                            break;
                        case 3:
                            $status = '<a class="btn btn-light text-success"><i class="buyer-status-icon fas fa-truck-loading" data-toggle="tooltip" title="Received" data-widget="chat-pane-toggle"></i></a>';
                            break;

                        } ?>
                    <tr onclick="window.location.href = '<?= $this->Url->build(['controller' => 'asn', 'action' => 'view', $deliveryDetail->id]) ?>';">

                        <td>
                            <?= $deliveryDetail->asn_no ?>
                        </td>
                        <td>
                            <?=  $deliveryDetail->po_header->sap_vendor_code   ?>
                        </td>
                        <td>
                            <?= $deliveryDetail->has('po_header') ? $deliveryDetail->po_header->po_no : '' ?>
                        </td>


                        <td>
                            <?= h($deliveryDetail->invoice_no) ?>
                        </td>


                        <td>
                            <?= h($deliveryDetail->invoice_value) . ' ' . h($deliveryDetail->po_header->currency) ?>
                        </td>
                        <td>
                            <?= $status ?>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        var table = $("#example1").DataTable({
            "paging": true,
            "responsive": false,
            "lengthChange": false,
            "autoWidth": false,
            "searching": true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search..."
            },
        });
    });
</script>