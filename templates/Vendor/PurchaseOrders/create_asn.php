<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PoHeader[]|\Cake\Collection\CollectionInterface $poHeaders
 */
?>

<?= $this->Html->css('custom') ?>
<?= $this->Html->css('v_purchaseorder_createasn') ?>
<?= $this->Html->css('bootstrap-multiselect') ?>
<?= $this->Html->script('bootstrap-multiselect') ?>
<?= $this->Html->css('dropdown-filter') ?>
<?= $this->Html->css('select2.min.css') ?>
<?= $this->Html->script('select2.js') ?>


<div class="card mb-2">
    <div class="card-header">CREATE ASN</div>
    <div class="card-header">
        <div class="row">
            <div class="col-sm-12 col-md-11">
                <?= $this->Form->create(null, ['id' => 'addvendorform']) ?>
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-lg-4">
                        <label for="id_material">Material</label><br>
                        
                        <select name="material[]" id="id_material" multiple="multiple" class="badla form-control chosen">
                            <?php if (isset($materials)) : ?>
                            <?php foreach ($materials as $mat) : ?>
                                <?= h($mat) ?>
                            <option value="<?= h($mat[0]) ?>" data-select="<?= h($mat[0]) ?>">
                            <?= h($mat[0]) ?> - <?= h($mat[1]) ?>
                            </option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-lg-2">
                        <label for="id_schqty">Schedule Qty</label><br>
                        <select name="schqty[]" id="id_schqty" multiple="multiple" class="badla form-control chosen">
                            <?php if (isset($scheduleqty)) : ?>
                            <?php foreach ($scheduleqty as $mat) : ?>
                            <option value="<?= h($mat) ?>" data-select="<?= h($mat) ?>">
                                <?= h($mat) ?>
                            </option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-2 col-lg-2">
                        <label for="id_delfrom">Delivery Date From</label>
                        <input type="date" class="form-control badla" name="delfrom" id="id_delfrom">
                    </div>
                    <div class="col-sm-12 col-md-2 col-lg-2">
                        <label for="id_deltill">Delivery Date Till</label>
                        <input type="date" class="form-control badla" name="deltill" id="id_deltill">
                    </div>
                </div>
                <?= $this->form->end() ?>
            </div>
            <div class="mt-3 pt-3">
                <button type="button" id="continueSub" class="btn bg-gradient-cancel continue_btn" disabled>Create ASN</button>
            </div>
        </div>
    </div>
</div>

<div class="card">
    
    <div class="card-body">
        <?= $this->Form->create(null, ['action' => 'asn-materials', 'id' => 'asnForm']) ?>
        <?= $this->form->control('po_header_id', ['id' => 'po_header_id', 'label' => false, 'type' => 'hidden', 'value' => '']) ?>
        <?= $this->form->control('vendor_factory_id', ['id' => 'vendor_factory_id', 'label' => false, 'type' => 'hidden', 'value' => '']) ?>
        <div class="poHeaders index content create-asn">
            <div class="scrollable-div mb-1">
                <!-- <div class="search-bar mb-2">
                    <input type="search" placeholder="Search all orders, meterials" class="form-control search-box">
                </div> -->
                <div class="polist">
                    <div class="d-flex" id="poItemss">
                        <!-- <?php foreach ($poHeaders as $poHeader) : ?>
                            <div class="po-box details-control  ponum" header-id="<?= $poHeader->id ?>">
                                <p class="po-no mb-0">PO No</p>
                                <b class="text-info">
                                    <?= h($poHeader->po_no) ?>
                                </b>
                            </div>
                            <?php endforeach; ?> -->
                    </div>
                </div>
            </div>
            <div class="table-responsive card-header pr-0 pl-0" id="id_pohead"></div>
            <div class="t2 mt-2 mb-1">
                <div class="table-responsive right-side">
                    <table class="table table-bordered material-list" id="example2">
                        <thead>
                            <tr>
                                <th class="py-0">
                                    <input type="checkbox" class="form-control form-control-sm mb-1" style="max-width: 20px;" id="ckbCheckAll">
                                </th>
                                <th>Item</th>
                                <th>Material</th>
                                <th>Delivery Date</th>
                                <th>Short Text</th>
                                <th>Schedule Qty</th>
                                <th>Set Delivery Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="7" class="text-center">No data found !</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?= $this->form->end() ?>
    </div>
</div>


<button type="button" id="id_select_factory" style="display: none;" data-toggle="modal" data-target="#select_factory"></button>

<div class="modal fade" id="select_factory">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Factories</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php foreach ($factoryset as $item): ?>
                    <div class="col-4">
                        <button class="btn btn-app p-3 fc_btn" data-fc_id="<?= h($item["id"]) ?>" id="fc_id<?= h($item["id"]) ?>" style="height:
                            auto;">
                            <i class="fas fa-industry"></i>&nbsp;
                            <?= h($item["factory_code"]) ?>
                            <br>
                            <?= h($item["address"]." ".$item["address_2"]) ?><br>
                            <?= h($item["city"]." ".$item["state"]) ?><br><?= h($item["country"]) ?><br>
                            <?= h($item["pincode"]) ?>
                        </button>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var factory_list = [
        <?php foreach ($factoryset as $item): ?>
            {
                "id": <?= h($item["id"]) ?>,
                "code": `<?= h($item["factory_code"]) ?>`,
                "address" : `<?= h($item["address"]." ".$item["address_2"]) ?><br><?= h($item["city"]." ".$item["state"]) ?><br><?= h($item["country"]) ?><br><?= h($item["pincode"]) ?>`,
            },
        <?php endforeach; ?>
    ]
    var active_po_header_id;
    var get_po_for_asn = `<?php echo \Cake\Routing\Router::url(array('controller' => '/purchase-orders', 'action' => 'asntocreate')); ?>`;
    var get_po_data = `<?php echo \Cake\Routing\Router::url(array('controller' => '/purchase-orders', 'action' => 'get-po-headers-with-items')); ?>`;
    $(document).on('click', '.fc_btn', function () {
        $('div.details-control').removeClass('active');
        $("#vendor_factory_id").val($(this).data('fc_id'));
        format(active_po_header_id, $(this).data('fc_id'));
        $(".close").trigger("click");
        $(".high"+active_po_header_id).addClass('active');
    });
</script>

<?= $this->Html->script('a_vekpro/vendor/v_purchaseorders_create_asn') ?>

