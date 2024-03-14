<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\AccountGroup[]|\Cake\Collection\CollectionInterface $accountGroups
 */
?>
<div class="admin-edit padd_ing accountGroups index content">
    <?= $this->Html->link(__('New Account Group'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Account Groups') ?></h3>
    <div class="col-lg-12">
        <table class="table table-responsive dataTable no-footer" id="tb_pg">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('status') ?></th>
                    <th><?= $this->Paginator->sort('added_date') ?></th>
                    <th><?= $this->Paginator->sort('updated_date') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($accountGroups as $accountGroup): ?>
                <tr>
                    <td><?= $this->Number->format($accountGroup->id) ?></td>
                    <td><?= h($accountGroup->name) ?></td>
                    <td><?= $this->Number->format($accountGroup->status) ?></td>
                    <td><?= h($accountGroup->added_date) ?></td>
                    <td><?= h($accountGroup->updated_date) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $accountGroup->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $accountGroup->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $accountGroup->id], ['confirm' => __('Are you sure you want to delete # {0}?', $accountGroup->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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
    $(document).ready(function () {
        $("#tb_pg").DataTable({
            pagination: true,
            searching: false,
            lengthChange: false
        });
    });
</script>