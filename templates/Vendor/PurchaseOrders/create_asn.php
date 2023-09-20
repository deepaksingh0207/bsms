<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PoHeader[]|\Cake\Collection\CollectionInterface $poHeaders
 */
?>

<?= $this->Html->css('custom') ?>
<?= $this->Html->css('v_purchaseorder_createasn') ?>
<?= $this->Form->create(null, ['action' => 'asn-materials', 'id' => 'asnForm']) ?>
<?= $this->form->control('po_header_id', ['id' => 'po_header_id', 'label' => false, 'type' => 'hidden', 'value' => '']) ?>

<div class="poHeaders index content card create-asn">
    <div class="card-body">
        <div class="content-d">
            <div class="t1 pt-1">
                <div class="row align-items-center">
                    <div class="col-md-6 pt-2 pb-1">
                        <div class="search-bar d-flex mb-2">
                            <input type="search" placeholder="Search all orders, materials.." class="form-control search-box">
                            <!-- <button type="button" class="btn-go searchgo ">GO</button> -->
                        </div>
                    </div>
                    <div class="col-md-6 pb-1">
                        <div class="action-btn d-flex justify-content-end">
                            <!-- <input type="file" id="imgupload" style="display:none" />
                            <button id="OpenImgUpload" type="button" class="btn bg-gradient-button mr-2">
                                <i class="fa fa-solid fa-file-import"></i>
                                Upload ASN File
                            </button> -->
                            <button type="button" id="continueSub" class="btn bg-gradient-cancel continue_btn" disabled>Continue</button>
                        </div>
                    </div>
                </div>
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
            </div>
            <div class="card-header pr-0 pl-0" id="id_pohead"></div>
            <div class="t2 mt-2 mb-1">
                <div class="right-side">
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
                                <th>Pending Qty</th>
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
    </div>
</div>
<?= $this->form->end() ?>

<script>
    var get_poDetails = `<?php echo \Cake\Routing\Router::url(array('controller' => '/purchase-orders', 'action' => 'poDetails')); ?>`;
    var get_po_for_asn = `<?php echo \Cake\Routing\Router::url(array('controller' => '/purchase-orders', 'action' => 'po-for-asn')); ?>`;
    var get_po_data = `<?php echo \Cake\Routing\Router::url(array('controller' => '/purchase-orders', 'action' => 'get-po-headers-with-items')); ?>`;
</script>

<?= $this->Html->script('a_vekpro/vendor/v_purchaseorders_create_asn') ?>