<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PoHeader[]|\Cake\Collection\CollectionInterface $poHeaders
 */
?>
<!-- <?= $this->Html->css('cstyle.css') ?> -->
<?= $this->Html->css('dropdown-filter') ?>
<?= $this->Html->css('custom') ?>
<?= $this->Html->css('table.css') ?>
<?= $this->Html->css('listing.css') ?>
<?= $this->Html->css('b_index.css') ?>
<?= $this->Html->css('select2.min.css') ?>
<?= $this->Html->script('select2.js') ?>
<!-- <?= $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css') ?> -->
<!-- <?= $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js') ?> -->
<style>
    .hide {
        display: none;
    }
</style>
<?= $this->Html->css('bootstrap-multiselect') ?>
<?= $this->Html->script('bootstrap-multiselect') ?>
<div class="card mb-2">
    <div class="card-header">PURCHASE ORDER TRACKING REPORT</div>
    

</div>
<div class="card mb-2">
    <div class="card-header">SEARCH</div>
    <div class="card-body">
        <?= $this->Form->create(null, ['id' => 'addvendorform']) ?>
        <div class="row">
            <div class="col-sm-12 col-md-3 col-lg-2">
                <label for="id_po_no">PO No</label><br>
                <select name="po_no[]" id="id_po_no" multiple="multiple" class="form-control chosen">
                    <?php if (isset($poList)) : ?>
                    <?php foreach ($poList as $mat) : ?>
                    <option value="<?= h($mat->po_no) ?>" data-select="<?= h($mat->po_no) ?>">
                        <?= h($mat->po_no) ?>
                    </option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-sm-12 col-md-3 col-lg-2">
                <label for="id_po_no">PO Date</label><br>
                <input class="form-control" type="date" name="po_no_date" id="id_po_no_date">
            </div>
            <div class="col-sm-12 col-md-3 col-lg-2">
                <label for="id_dd">Delivery Date</label><br>
                <input class="form-control" type="date" name="delivery_date" id="id_dd">
            </div>
            <div class="col-sm-12 col-md-3 col-lg-2">
                <label for="id_segment">Segment</label><br>
                <select name="segment[]" id="id_segment" multiple="multiple" class="form-control chosen">
                    <?php if (isset($segment)) : ?>
                    <?php foreach ($segment as $mat) : ?>
                    <option value="<?= h($mat->segment) ?>" data-select="<?= h($mat->segment) ?>">
                        <?= h($mat->segment) ?>
                    </option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-sm-12 col-md-3 col-lg-2">
                <label for="id_status">Status</label><br>
                <select name="status[]" id="id_status" multiple="multiple" class="form-control chosen">
                    <option value="Scheduled">Scheduled</option>
                    <option value="ASN created">ASN created</option>
                    <option value="Partial ASN created">Partial ASN created</option>
                    <option value="In-Transit">In-Transit</option>
                    <option value="Received">Received</option>
                </select>
            </div>
            <div class="col-sm-12 col-md-3 col-lg-2">
                <label for="id_vendortype">Type</label><br>
                <select name="vendortype[]" id="id_vendortype" multiple="multiple" class="form-control chosen">
                    <?php if (isset($vendortype)) : ?>
                    <?php foreach ($vendortype as $mat) : ?>
                    <option value="<?= h($mat->type) ?>"> <?= h($mat->type) ?> </option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-sm-12 col-md-3 col-lg-3">
                <label for="id_material">Material</label><br>
                <select name="material[]" id="id_material" multiple="multiple" class="form-control chosen">
                    <?php if (isset($materialList)) : ?>
                    <?php foreach ($materialList as $mat) : ?>
                    <option value="<?= h($mat->code) ?>" data-select="<?= h($mat->code) ?>">
                        <?= h($mat->code) ?>
                        <?= h($mat->description) ?>
                    </option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            
            <div class="col-sm-12 col-md-3 col-lg-1">
                <div class="form-group mt-4 pt-2">
                    <?= $this->Form->button(__('Search'), ['class' => 'btn bg-gradient-submit', 'id' => 'id_sub', 'type' => 'submit']) ?>
                </div>
            </div>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>
<div class="card mb-2">
<div class="card-body buyer_material">
        <table class="table table-hover table-responsive" id="example1">
            <thead>
                <tr>
                    <th>Vendor Code</th>
                    <th>PO</th>
                    <th>Item</th>
                    <th>Type</th>
                    <th>Segment</th>
                    <th>Material</th>
                    <th style="min-width: 400px;">Description</th>
                    <th>PO Qty</th>
                    <th>Grn Qty</th>
                    <th>Pending Qty</th>
                    <th>Order Unit</th>
                    <th>Net Price</th>
                    <th>Price Unit</th>
                    <th>Net Value</th>
                    <th>Gross Value</th>
                    <th>Schedule Qty</th>
                    <th>ASN Qty</th>
                    <th>ASN No</th>
                    <th style="min-width: 100px;">Delivery Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script>
    // $(".chosen").multiselect({
    //     enableClickableOptGroups: false,
    //     enableCollapsibleOptGroups: false,
    //     enableFiltering: true,
    //     includeSelectAllOption: false,
    //     buttonText: function (options, select) {
    //         if (options.length === 0) { return 'Select'; }
    //         // else if (options.length > 1) { return options.length + 'Filter'; }
    //         else {
    //             var labels = [];
    //             options.each(function () {
    //                 if ($(this).attr('data-select') !== undefined) { labels.push($(this).attr('data-select')); }
    //                 else { labels.push($(this).html()); }
    //             });
    //             return labels.join(', ');
    //         }
    //     }
    // });
    $('.chosen').select2({
        closeOnSelect : false,
        placeholder: 'Select',
        allowClear: true,
        tags: false,
        tokenSeparators: [','],
        templateSelection: function(selection) {
            if (selection.element && $(selection.element).attr('data-select') !== undefined) {
                return $(selection.element).attr('data-select');
            } else {
                return selection.text;
            }
        }
    });

    var datatable = $("#example1").DataTable({
        "paging": true,
        "responsive": false,
        "lengthChange": false,
        "autoWidth": false,
        "searching": true,
        "ordering": true,
        "destroy": true,
        dom: 'Blfrtip',
        buttons: [{ extend: 'copy' }, { extend: 'excelHtml5', text: 'Export', title:'' }]
    });

    $("#addvendorform").validate({
        rules: { vendor_code: { required: false } },
        messages: { vendor_code: { required: "Please enter a first name" } },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            element.closest(".form-group").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
        submitHandler: function (form) {
            $.ajax({
                type: "POST",
                url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/purchase-orders', 'action' => 'purchaseorderlist')); ?>",
                data: $("#addvendorform").serialize(),
                dataType: "json",
                beforeSend: function () { $("#gif_loader").show(); },
                success: function (response) {
                    console.log(response);
                    if (response.status) {
                        datatable.clear().draw();
                        datatable.rows.add(response.data).draw(); // Add new data
                        datatable.columns.adjust().draw();
                    } else {
                        datatable.clear().draw();
                    }
                },
                complete: function () { $("#gif_loader").hide(); }
            });
        },
    });

    $("#id_sub").trigger("click");
</script>