<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\VendorTemp[]|\Cake\Collection\CollectionInterface $vendorTemps
 */
?>

<?= $this->Html->css('custom') ?>
<?= $this->Html->css('table.css') ?>
<?= $this->Html->css('listing.css') ?>
<?= $this->Html->css('b_index.css') ?>
<?= $this->Html->css('b_vendortemps_index') ?>
<?= $this->Html->css('admincss') ?>

<div class="vendorTemps index content">
    <!-- <?= $this->Html->link(__('New Vendor'), ['action' => 'add'], ['class' => 'new_vendor_btn button float-right']) ?> -->
    <div class="card">
        <div class="card-header">VENDORS</div>
        <div class="card-body">
        <div class="table-responsive">
        <table class="table table-hover table-responsive dataTable no-footer" id="tb_pg" style="width: 100%;">
            <thead>
                <tr>
                    <th>Purchase Organisation</th>
                    <th>Account Group</th>
                    <th>Schema Group</th>
                    <th>Name & Address</th>
                    <th>Contact</th>
                    <th>Order Currency</th>
                    <th>Contact Person (Mobile) <br> Email</th>
                    <th>Status</th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vendorTemps as $vendorTemp): 
                    
                    switch($vendorTemp->status) {
                        case 0 : $status = 'Sent to Vendor'; break;
                        case 1 : $status = 'Pending for approval'; break;
                        case 2 : $status = 'approved'; break;
                        case 3 : $status = 'approved'; break;
                        case 4 : $status = 'approved'; break;
                        case 5 : $status = 'approved'; break;
                        case 6 : $status = 'approved'; break;
                        case 7 : $status = 'approved'; break;
                        case 8 : $status = 'approved'; break;
                    }
                    ?>
                <tr>
                    <td>
                        <?= $vendorTemp->has('purchasing_organization') ? $this->Html->link($vendorTemp->purchasing_organization->name, ['controller' => 'PurchasingOrganizations', 'action' => 'view', $vendorTemp->purchasing_organization->id]) : '' ?>
                    </td>
                    <td>
                        <?= $vendorTemp->has('account_group') ? $this->Html->link($vendorTemp->account_group->name, ['controller' => 'AccountGroups', 'action' => 'view', $vendorTemp->account_group->id]) : '' ?>
                    </td>
                    <td>
                        <?= $vendorTemp->has('schema_group') ? $this->Html->link($vendorTemp->schema_group->name, ['controller' => 'SchemaGroups', 'action' => 'view', $vendorTemp->schema_group->id]) : '' ?>
                    </td>
                    <td>
                        <?= h($vendorTemp->name) ?><br>
                        <?= h($vendorTemp->address) ?>
                        <?= h($vendorTemp->city) ?>
                        <?= h($vendorTemp->pincode) ?>
                        <?= h($vendorTemp->country) ?>
                    </td>
                    <td>
                        <?= h($vendorTemp->email) ?><br>
                        <?= h($vendorTemp->mobile) ?>
                    </td>
                    <td>
                        <?= h($vendorTemp->order_currency) ?>
                    </td>
                    <td>
                        <?= h($vendorTemp->contact_person) ?> ( <?= h($vendorTemp->contact_mobile) ?> )<br>
                        <?= h($vendorTemp->contact_email) ?><br>
                        
                    </td>
                    <td>
                        <?= h($status) ?>
                    </td>
                    <td class="actions">
                        <?php if($vendorTemp->status == 1) : ?>
                            <?= $this->Html->link(__('Approve'), ['action' => 'approve-vendor', $vendorTemp->id, 'app'], ['class' => 'btn btn-primary']) ?>
                        <?php endif; ?>
                        <?= $this->Html->link(__('View'), ['action' => 'view', $vendorTemp->id], ['class' => 'btn btn-primary', 'title'=>'Click to view']) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
        </div>
    </div>
    
    <!-- <div class="paginator">
    <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        
    </div> -->
</div>
<script>
    $(document).ready(function () {
        $("#tb_pg").DataTable({
            pagination: true,
            ordering: true,
            searching: false,
            lengthChange: false
        });
    });
</script>