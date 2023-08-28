<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PoHeader[]|\Cake\Collection\CollectionInterface $poHeaders
 */
?>

<!-- <?= $this->Html->css('cstyle.css') ?> -->
<?= $this->Html->css('custom') ?>
<!-- <?= $this->Html->css('table.css') ?> -->
<!-- <?= $this->Html->css('listing.css') ?> -->
<!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" /> -->

<?= $this->Html->css('v_purchaseorder_createasn') ?>
<?= $this->Form->create(null, ['action' => 'asn-materials', 'id' => 'asnForm']) ?>
<?= $this->form->control('po_header_id', ['id' => 'po_header_id', 'label' => false, 'type' => 'hidden', 'value' => '']) ?>

<div class="poHeaders index content card create-asn">
    <div class="card-body">
        <div class="content-d">
            <div class="t1 pt-1">
                <div class="row">
                    <div class="col-md-6 pt-2">
                        <div class="search-bar d-flex mb-2">
                            <input type="search" placeholder="Search all orders, meterials.."
                                class="form-control search-box">
                            <!-- <button type="button" class="btn-go searchgo ">GO</button> -->
                        </div>
                    </div>
                    <div class="col-md-6 pb-2">
                        <div class="action-btn d-flex justify-content-end">
                            <input type="file" id="imgupload" style="display:none" />
                            <button id="OpenImgUpload" type="button" class="btn bg-gradient-button mr-2"><i
                                    class="fa fa-solid fa-file-import"></i> Upload ASN File</button>
                            <!-- <a href="#" class="btn btn-info mb-0 mr-1"><i class="fa fa-solid fa-file-import"></i> Upload ASN File</a> -->
                            <button type="button" id="continueSub" class="btn bg-gradient-cancel continue_btn"
                                disabled>Continue</button>
                        </div>
                    </div>
                </div>
                <div class="scrollable-div">
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
            <div class="card-header p-2" id="id_pohead"></div>
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
<?= $this->form->end() ?>

<script>
    // file upload button
    $('#OpenImgUpload').click(function () { $('#imgupload').trigger('click'); });

    $(document).ready(function () {
        var table = $("#example1").DataTable({
            "paging": false,
            "responsive": false,
            "lengthChange": false,
            "autoWidth": false,
            "ordering": false,
            "searching": true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search..."
            },
        });

        $('#continueSub').on("click", function () {
            $('.checkBoxClass').each(function () { if ($(this).is(':checked')) { $("#qty" + $(this).data("id")).attr("name", "footer_id_qty[]") } });
            $('#asnForm').submit();
        })

        $(document).on("click", ".flu", function () {
            if ($(this).data('alt') == '+') { $(this).data('alt', '-').empty().append('Remove'); }
            else { $(this).data('alt', '+').empty().append('add'); }
        });

        $(document).on('click', 'div.details-control', function () {
            $('div.details-control').removeClass('active');
            $(this).addClass('active');
            $(".continue_btn").removeClass('btn-success').attr('disabled', 'disabled');
            $(".right-side").html(format($(this).attr('header-id')));
            $("#po_header_id").val($(this).attr('header-id'));
            poHeaders($(this).attr('header-id'));
        });


        function poHeaders(rowDatas) {
    console.log("ID-" + rowDatas);
    $.ajax({
      type: "GET",
      url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/purchase-orders', 'action' => 'poDetails')); ?>/" + rowDatas,
      contentType: "application/x-www-form-urlencoded; charset=utf-8",
      dataType: "json",
      beforeSend: function () { $("#loaderss").show(); },
      success: function (response) {
        if (response.status) { 
          $(".card-header").html(response.html);
         }
      },
      complete: function () { $("#loaderss").hide(); }
    });
  }

        function format(rowData) {
            var div = $('<div/>').addClass('loading').text('Loading...');
            if (!rowData) { rowData = $('div.details-control:first').attr('header-id'); }

            $.ajax({
                type: "GET",
                url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/purchase-orders', 'action' => 'get-items')); ?>/" + rowData,
                contentType: "application/x-www-form-urlencoded; charset=utf-8",
                dataType: "json",
                beforeSend: function () { $("#loaderss").show(); },
                success: function (response) {
                    if (response.status == 'success') { div.html(response.html).removeClass('loading'); }
                    else { div.html(response.message).removeClass('loading'); }
                },
                complete: function () { $("#loaderss").hide(); }
            });

            return div;
        }

        $('.search-box').on('keypress', function (event) {
            if (event.which === 13) {
                var searchName = $(this).closest('.search-bar').find('.search-box').val();
                $(".right-side tbody:first").empty().hide().append(`<tr><td colspan="6" class="text-center"><p>No data found !</p></td></tr>`);
                poform(searchName);
                return false;
            }
        });

        $('.search-box').on('keydown', function (event) {

            if (event.which === 8) {
                // Check if Backspace key is pressed 
                var searchName = $(this).closest('.search-bar').find('.search-box').val();
                if (searchName.length === 1) {
                    $(".right-side tbody:first").empty().hide();
                    poform(searchName);
                }
            }
        });

        poform();

        function poform(search = "", createAsn = "as") {
            $("#poItemss").empty();
            $(".right-side tbody:first").show();
            var uri = "<?php echo \Cake\Routing\Router::url(array('controller' => '/purchase-orders', 'action' => 'po-for-asn')); ?>";
            if (search != "") { uri += "/" + search}
            $.ajax({
                type: "GET",
                url: uri,
                dataType: 'json',
                success: function (response) {
                    if (response.status) {
                        $.each(response.data, function (key, val) {
                            $("#poItemss").append(`<div class="po-box details-control  ponum" header-id="` + val.id + `">
                                    <p class="po-no mb-0">PO No.</p>
                                    <b class="text-info">
                                    ` + val.po_no + `
                                    </b>
                                </div>`);

                        });
                        $('div.details-control:first').click();
                    }
                }
            });
        }

        $(document).ready(function () { $('div.details-control:first').click(); });

        $(document).on("click", "#ckbCheckAll", function () {
            if (this.checked) {
                $('.checkBoxClass').each(function (key, val) {
                 
                    if ($("#qty" + $(val).data("id")).val() == 0) { $("#qty" + $(val).data("id")).val($(val).data("pendingqty")); }
                    // var 
                    this.checked = true;
                    $("#select" + $(this).data("id")).trigger("change");
                });
            } else {

                $('.checkBoxClass').each(function () {
                    $("#qty" + $(this).data("id")).val('0');
                    this.checked = false;
                    $("#select" + $(this).data("id")).trigger("change");
                });
            }
            if ($('.checkBoxClass:checked').length) {
                $(".continue_btn").addClass('btn-success').removeAttr('disabled').removeClass('btn-secondary');
            } else {
                $(".continue_btn").addClass('btn-secondary ').removeClass('btn-success').attr('disabled', 'disabled');
            }
        });

        $(document).on("change", ".checkBoxClass", function () {
            if ($(this).is(':checked')) { if ($("#qty" + $(this).data("id")).val() == "0"|| $("#qty" + $(this).data("id")).val() == "") { $("#qty" + $(this).data("id")).val($(this).data("pendingqty")); } }
            else { $("#qty" + $(this).data("id")).val(''); }
        });

        $(document).on("change", ".checkBoxClass", function () {
            if ($('.checkBoxClass:checked').length == $('.checkBoxClass').length) { $('#ckbCheckAll').prop('checked', true); }
            else { $('#ckbCheckAll').prop('checked', false); }

            if ($('.checkBoxClass:checked').length) { $(".continue_btn").addClass('btn-success').removeAttr('disabled').removeClass('btn-secondary'); }
            else { $(".continue_btn").addClass('btn-secondary ').removeClass('btn-success').attr('disabled', 'disabled'); }
        });
    });
    $(document).on("change focusout", ".check_qty", function () {
        if ($(this).val() > $(this).data('max')){$(this).val($(this).data('max'))}
    });
</script>