<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PoHeader[]|\Cake\Collection\CollectionInterface $poHeaders
 */
?>
<style>
.table td, .table th{
        padding:0rem;
        font-size:small;
        

    }
    .card-body{
        padding:0.5rem
    }
    .btn
    {
        padding:0.1rem;
        font-size:0.7rem;
        border:1px;
        border-color:black
    }
    

    </style>

<div class="poHeaders index content card">
    <!-- <div class="card-header"style="
    background-color: #0095ff;
">
        <h3 style="color:WHITE">
            <b>
                <?= __('PURCHASE ORDER LISTS') ?>
            </b>
        </h3>
    </div> -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover" id="example1">
                <thead>
                    <tr style="background-color: #d3d3d36e;">
                        <th>
                            <?= h('Vendor Code') ?>
                        </th>
                        <th>
                            <?= h('PO No.') ?>
                        </th>
                        <th>
                            <?= h('Document Type') ?>
                        </th>
                        <th>
                            <?= h('Created On') ?>
                        </th>
                        <th>
                            <?= h('Created By') ?>
                        </th>
                        <th>
                            <?= h('Pay Terms') ?>
                        </th>
                        <th>
                            <?= h('Currency') ?>
                        </th>
                        <th>
                            <?= h('Exchange Rate') ?>
                        </th>
                        <th>
                            <?= h('Release Status') ?>
                        </th>
                        <th>
                            <?= h('Added Date') ?>
                        </th>
                        <th>
                            <?= h('Updated Date') ?>
                        </th>
                        <!-- <th class="actions">
                            <?= __('Actions') ?> -->
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($poHeaders as $poHeader): ?>
                    <tr>
                    
                    <td class="redirect"  data-href="<?= $this->Url->build('/') ?>buyer/purchase-orders/view/<?= $poHeader->id ?>"><?= h($poHeader->sap_vendor_code) ?></td>
                        
                        <td class="redirect"  data-href="<?= $this->Url->build('/') ?>buyer/purchase-orders/view/<?= $poHeader->id ?>">
                            <?= h($poHeader->po_no) ?>
                        </td>
                        <td class="redirect"  data-href="<?= $this->Url->build('/') ?>buyer/purchase-orders/view/<?= $poHeader->id ?>">
                            <?= h($poHeader->document_type) ?>
                        </td>
                        <td class="redirect"  data-href="<?= $this->Url->build('/') ?>buyer/purchase-orders/view/<?= $poHeader->id ?>">
                            <?= h($poHeader->created_on) ?>
                        </td>
                        <td class="redirect"  data-href="<?= $this->Url->build('/') ?>buyer/purchase-orders/view/<?= $poHeader->id ?>">
                            <?= h($poHeader->created_by) ?>
                        </td>
                        <td class="redirect"  data-href="<?= $this->Url->build('/') ?>buyer/purchase-orders/view/<?= $poHeader->id ?>">
                            <?= h($poHeader->pay_terms) ?>
                        </td>
                        <td class="redirect"  data-href="<?= $this->Url->build('/') ?>buyer/purchase-orders/view/<?= $poHeader->id ?>">
                            <?= h($poHeader->currency) ?>
                        </td>
                        <td class="redirect"  data-href="<?= $this->Url->build('/') ?>buyer/purchase-orders/view/<?= $poHeader->id ?>">
                            <?= $this->Number->format($poHeader->exchange_rate) ?>
                        </td>
                        <td class="redirect"  data-href="<?= $this->Url->build('/') ?>buyer/purchase-orders/view/<?= $poHeader->id ?>">
                            <?= h($poHeader->release_status) ?>
                        </td>
                        <td class="redirect"  data-href="<?= $this->Url->build('/') ?>buyer/purchase-orders/view/<?= $poHeader->id ?>">
                            <?= h($poHeader->added_date) ?>
                        </td>
                        <td class="redirect"  data-href="<?= $this->Url->build('/') ?>buyer/purchase-orders/view/<?= $poHeader->id ?>">
                            <?= h($poHeader->updated_date) ?>
                        </td>
                        
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div> -->
</div>


<script>
        $(document).on("click", ".redirect", function () {
        window.location.href = $(this).data("href");
    });
    $(document).ready(function () {
        $("#example1").DataTable({
            "responsive": {"details": {"type": none}},
            "paging": true,
            "responsive": true, "lengthChange": false, "autoWidth": false, "searching": true,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>