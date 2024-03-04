<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\VendorMaterialStock[]|\Cake\Collection\CollectionInterface $vendorMaterialStocks
 */
?>

<style>
    /* .redirect{
        cursor: pointer;
    }
    .table td, .table th{
        padding:0rem
    }
    body{
        font-size:0.9rem
    }
    button, input, optgroup, select, textarea {
        margin:10px
    } */
    /* .vendorMaterialStocks #example1_filter input.form-control.form-control-sm{
        margin-left:-10px !important;
    } */
</style>
<!-- <?= $this->Html->css('cstyle.css') ?> -->
<!-- <?= $this->Html->css('table.css') ?> -->
<!-- <?= $this->Html->css('listing.css') ?> -->
<!-- <?= $this->Html->css('v_vendorCustom') ?> -->
<div class="vendorMaterialStocks index content card">
    <div class="card-header p-2">
        <?= $this->Form->create(null, ['url' => ['action' => 'upload'],'type' => 'file']) ?>
        <fieldset>
            <h5><b>UPLOAD STOCKS</b></h5>
            
            <div class="form-group mb-0">
                <div class="input-group">
                    <div class="custom-file">
                        <?php echo $this->Form->control('Upload Stocks', ['label' => false, 'accept'=>".xls,.xlsx", 'type' => 'file', 'class' => 'custom-file-input', 'id' => 'exampleInputFile']); ?>
                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                    </div>
                   <?= $this->Form->button(__('Upload'), ['class' => 'btn bg-gradient-button mb-0']) ?>
                </div>
            </div>
        </fieldset>
        <?= $this->Form->end() ?>
    </div>

    <!-- <div class="card-header">
        <h5><b>MATERIAL STOCK LIST</b></h5>
    </div> -->

    <div class="card-body p-2">
        <div class="table-responsive">
            <table class="table table-hover" id="example1">
                <thead>
                    <tr>
                        <th>
                            <?= h('Part Code') ?>
                        </th>
                        <th>
                            <?= h('Part Description') ?>
                        </th>
                        <th>
                            <?= h('Current Stock') ?>
                        </th>
                        <th>
                            <?= h('In-Production Stock') ?>
                        </th>
                        <th>
                            <?= h('Added Date') ?>
                        </th>
                        <th>
                            <?= h('Updated Date') ?>
                        </th>
                      
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vendorMaterialStocks as $vendorMaterialStock): ?>
                    <tr>
                        <td>
                            <?= h($vendorMaterialStock->part_code) ?>
                        </td>
                        <td>
                            <?= h($vendorMaterialStock->material_desc) ?>
                        </td>
                        <td>
                            <?= $this->Number->format($vendorMaterialStock->current_stock) ?>
                        </td>
                        <td>
                            <?= $this->Number->format($vendorMaterialStock->production_stock) ?>
                        </td>
                        <td>
                            <?= h($vendorMaterialStock->added_date) ?>
                        </td>
                        <td>
                            <?= h($vendorMaterialStock->updated_date) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        $("#example1").DataTable({
            "paging": true,
            "responsive": false, "lengthChange": false, "autoWidth": false, "searching": true,
            language: {
          search: "_INPUT_",
        searchPlaceholder: "Search..."
    },
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>