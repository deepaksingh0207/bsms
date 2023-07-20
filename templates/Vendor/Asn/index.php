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
<?= $this->Html->css('v_asn_index') ?>
<div class="row">
    <div class="col-12">
        <div class="deliveryDetails index content card">
            <!-- <div class="card-header">
                <h5>
                    <b>
                        <?= __('ASN LIST') ?>
                    </b>
                </h5>
            </div> -->
            <div class="card-body">
                <table class="table table-hover" id="example1"
                    style="border-left: .5px solid lightgray;border-right: .5px solid lightgray; border-bottom: .5px solid lightgray;">
                    <thead>
                        <tr style="background-color: #527195;">
                            <th>ASN NO</th>
                            <th>Purchase Order</th>
                            <th>Invoice No</th>
                            <th>Invoice Date</th>
                            <th>States</th>
        
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($deliveryDetails as $deliveryDetail): ?>
                        <tr class="redirect"
                            data-href="<?= $this->Url->build('/') ?>vendor/asn/view/<?= $deliveryDetail->id ?>">
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
                                <?= h($deliveryDetail->invoice_date) ?>
                            </td>
        
                            <td>
        
                                <?= $deliveryDetail->status == 2 ? '<span class="badge bg-success">In Transit</span>' : ($deliveryDetail->status == 3 ? '<span class="badge bg-warning">Received</span>' : '<span class="badge bg-info">Created</span>') ?>
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
        setTimeout(function () { $('.success').fadeOut('slow'); }, 2000);

        $(document).on("click", ".redirect", function () { window.location.href = $(this).data("href"); });

        var table = $("#example1").DataTable({
            "paging": true,
            "responsive": false, "lengthChange": false, "autoWidth": false, "searching": true,
            "ordering": true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search..."
            },
        });
        $('input[type=search]').attr('class', 'search-bar');
    });

</script>