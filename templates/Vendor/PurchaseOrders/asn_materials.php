<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PoHeader $poHeader
 */
?>
<!-- <?= $this->Html->css('cstyle.css') ?> -->
<!-- <?= $this->Html->css('table.css') ?>
<?= $this->Html->css('listing.css') ?> -->
<!-- <link rel="stylesheet"  href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" /> -->
<!-- <?= $this->Html->css('custom') ?> -->
<!-- <?= $this->Html->css('v_purchaseorder_asn_material') ?> -->
<?= $this->Form->create(null, ['url' => '/vendor/purchase-orders/view/' . $poHeader[0]->id, 'type' => 'file', 'id' => 'asnForm']) ?>
<?= $this->form->control('po_header_id', ['label' => false, 'type' => 'hidden', 'value' => $poHeader[0]->id]) ?>
<div class="row">
  <div class="col-12">
    <div class="card card_boxshadow">
      <div class="card-header">
        <div class="row d-flex align-items-center">
          <div class="col-2">
            <h6 class="mb-0"><small>PO NO :</small><b>
                <?= h($poHeader[0]->po_no) ?>
              </b></h6>
          </div>
          <div class="col-8">
            <h6 class="mb-0">
              <small>Vendor Name: </small>
              <b>
                <?php echo $this->getRequest()->getSession()->read('first_name'); ?>
              </b>
            </h6>
          </div>
          <!-- <div class="col-6"></div> -->
          <div class="col-1">
            <a style="display:none;" href="vendor/purchase-orders/create-asn" id="id_backmodal" class="btn bg-gradient-cancel float-right">
              <i class="fas fa-angle-double-left"></i> BACK</a>
          </div>
          <div class="col-1 text-center">
            <button type="button" class="btn bg-gradient-submit" id="Create-btn">Create ASN</button>
            <div class="modal fade" id="modal-confirm" style="display: none;" aria-hidden="true">
              <div class="modal-dialog modal-sm">
                <div class="modal-content">
                  <div class="modal-body text-center">
                    <h6>Are you sure you want to create asn ?</h6>
                  </div>
                  <div class="modal-footer justify-content-between p-1 d-flex align-items-center">
                    <button type="button" class="cancel_btn btn btn-sm btn-link" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm">Ok</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h5><b>
            <?= __('Invoice Details') ?>
          </b></h5>
      </div>
      <div class="card-body invoice-details">

        <div class="row dgf">
          <div class="col-sm-8  col-md-2">
            <div class="form-group">
            <?= $this->form->control('vendor_factory_id', ['id' => 'vendor_factory_id', 'label' => false, 'type' => 'hidden']) ?>
              <?php echo $this->Form->control('vendor_factory_id', array( 'name' => '', 'class' => 'form-control rounded-0', 'maxlength'=>'15', 'div' => 'form-group', 'required', 'disabled', 'empty' => 'Please Select')); ?>
            </div>
          </div>

          <div class="col-sm-8  col-md-2">
            <div class="form-group">
              <?php echo $this->Form->control('invoice_no', array('class' => 'form-control rounded-0', 'maxlength'=>'15', 'div' => 'form-group', 'required')); ?>
            </div>
          </div>

          <div class="col-sm-8 col-md-2">
            <div class="form-group">
              <?php echo $this->Form->control('invoice_date', array('type' => 'date', 'class' => 'form-control rounded-0', 'div' => 'form-group', 'required')); ?>
            </div>
          </div>

          <div class="col-sm-8 col-md-2">
            <div class="form-group">
              <?php echo $this->Form->control('invoice_value', array('id' => 'invoice_value', 'type' => 'number', 'maxlength'=>'12', 'class' => 'form-control rounded-0', 'div' => 'form-group', 'required', 'readonly')); ?>
            </div>
          </div>

          <div class="col-sm-8 col-md-2">
            <div class="form-group">
              <?php echo $this->Form->control('vehicle_no', array('class' => 'form-control rounded-0', 'maxlength'=>'12', 'div' => 'form-group', 'required')); ?>
            </div>
          </div>
          <div class="col-sm-8 col-md-2">
            <div class="form-group">
              <?php echo $this->Form->control('driver_name', array('class' => 'form-control rounded-0', 'maxlength'=>'15','type' => 'text','div' => 'form-group', 'required')); ?>
            </div>
          </div>

          <div class="col-sm-8 col-md-2">
            <div class="form-group">
              <?php echo $this->Form->control('driver_contact', array('type' => 'text', 'maxlength'=>'10', 'class' => 'form-control numberonly rounded-0', 'div' => 'form-group', 'required')); ?>
            </div>
          </div>

          <div class="col-sm-8 col-md-2">
            <div class="form-group">
              <?php echo $this->Form->control('transporter_name', array('type' => 'text', 'class' => 'form-control rounded-0', 'div' => 'form-group')); ?>
            </div>
          </div>


          <div class="col-sm-8 col-md-2">
            <div class="form-group">
              <label for="invoices">Upload Invoice</label>
              <input type="file" name="invoice" accept=".pdf" class="pt-1 rounded-0"
                style="visibility: hidden;position:absolute;" div="form-group" required="required" id="invoices">
              <button id="OpenImgUpload" type="button"
                class="upload_invoice d-block btn bg-gradient-button mb-0 file-upld-btn">
                Choose File
              </button>

              <p id="files-area"><span id="invoice_filesList"><span id="files-names"></span></span></p>
            </div>

          </div>

          <div class="col-sm-8 col-md-2">
            <div class="form-group">
              <label for="invoices">Upload E-wayBill</label>
              <input type="file" name="ewaybill" accept=".pdf" class="pt-1 rounded-0" id="ewaybill"
                style="visibility: hidden;position:absolute;" div="form-group">
              <button id="OpenImgUploadEwaybill" type="button"
                class="upload_ewaybill d-block btn bg-gradient-button mb-0 file-upld-btn">
                Choose File
              </button>

              <p id="files-area"><span id="ewaybill_filesList"><span id="files-names"></span></span></p>
            </div>

          </div>
          <div class="col-sm-8 col-md-2">
            <div class="form-group">
              <label for="invoices">Other Document</label>
              <input type="file" name="others[]" accept=".pdf" class="pt-1 rounded-0" id="others"
                style="visibility: hidden;position:absolute;" div="form-group">
              <button id="OpenImgUploadOthers" type="button"
                class="other_document d-block btn bg-gradient-button mb-0 file-upld-btn">
                Choose File
              </button>

              <p id="files-area"><span id="others_filesList"><span id="files-names"></span></span></p>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h5><b>
            <?= __('Material List') ?>
          </b></h5>
      </div>

      <div class="card-body">
        <?php if (count($poHeader)) : ?>
        <div class="row">
          <div class="col-12">
            <div class="table-responsive">
              <table class="table table-bordered material-list" id="material_list">
                <thead>
                  <tr>
                    <th>
                      <?= __('Item') ?>
                    </th>
                    <th>
                      <?= __('Material') ?>
                    </th>
                    <th>
                      <?= __('Delivery Date') ?>
                    </th>
                    <th>
                      <?= __('Short Text') ?>
                    </th>
                    <th>
                      <?= __('Schedule Qty') ?>
                    </th>
                    <th>
                      <?= __('Base Price') ?>
                    </th>
                    <th>
                      <?= __('Shipping Qty') ?>
                    </th>
                    <th>
                      <?= __('Net Value') ?>
                    </th>
                    <th>
                      <?= __('Current Stock') ?>
                    </th>
                    <th>
                      <?= __('Min. Stock') ?>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($poHeader as $row) : 
                    
                    //echo '<pre>'; print_r($row); exit;
                    ?>
                  <tr>
                    <td>
                      <?= h($row['PoFooters']['item']) ?>
                    </td>
                    <td>
                      <?= h($row['PoFooters']['material']) ?>
                    </td>
                    <td>
                      <?= h($row['delivery_date']) ?>
                    </td>
                    <td>
                      <?= h($row['PoFooters']['short_text']) ?>
                    </td>
                    <td>
                      <?= h($row['actual_qty']) ?>&nbsp;
                      <?= h($row['PoFooters']['order_unit']) ?>
                    </td>
                    <td>
                      <?= h($row['PoFooters']['net_price']) ?> &nbsp;
                      <?= h($row['currency']) ?>
                    </td>
                    <td style="width:50px;">
                      <div class="form-group mb-0">
                        <?= $this->form->control('po_footer_id[]', ['label' => false, 'type' => 'hidden', 'value' => $row['PoFooters']['id']]) ?>
                        <?= $this->form->control('schedule_id[]', ['label' => false, 'type' => 'hidden', 'value' => $row['PoItemSchedules']['id']]) ?>
                        <?= $this->form->control('qty[]', ['label' => false, 'value' => $row['ship_qty'], 'readonly' => 'readonly', 'class' => 'form-control form-control-sm check_qty', 'type' => 'number', 'required', 'data-item' => $row['PoFooters']['item'], 'min' => '0', 'data-minstock'=>$row['current_stock'], 'max' => $row['ship_qty'],  'div' => 'form-group', 'data-net-price' => $row['PoFooters']['net_price']]) ?>
                      </div>
                    </td>
                    <td class="net_value" id="net_value_<?= h($row['PoFooters']['item']) ?>">
                      <?= ($row['PoFooters']['net_price'] * $row['ship_qty']) ?>
                    </td>
                    <td><span id="current_stock">
                        <?php echo $row['current_stock'] ?>
                      </span></td>
                    <td><span id="minimum_stock">
                        <?php echo $row['min_stock'] ?>
                      </span></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
              <span id="error_msg" class="text-danger"></span>
            </div>
          </div>
          <div class="col-10"></div>
          <div class="col-2">
            <table class="table table-bordered">
              <tbody>
                <tr>
                  <td> Sub Total : </td>
                  <td><span id="sub_total"> 0 </span></td>
                </tr>
                <tr>
                  <td> Total GST(18%) : </td>
                  <td><span id="total_gst"> 0 </span></td>
                </tr>
                <tr>
                  <td> <b>Total Value : </b></td>
                  <td><b><span id="total_value"> 0 </span></b></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-default">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header border-0 pt-2 pb-2">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <i class="fas fa-question-circle text-warning" style="font-size: 55px;"></i>
        <h5 class="mb-0 pt-2 fw-bold">Are You Sure ?</h5>
        <small>Your data will be lost!</small>
      </div>
      <div class="modal-footer border-0 justify-content-between">
        <button type="button" id="leaveButton" class="btn bg-gradient-warn">Leave</button>
        <button type="button" class="btn bg-gradient-submit" data-dismiss="modal">Stay</button>
      </div>
    </div>
  </div>
</div>
<?= $this->form->end() ?>

<script>
  $(document).ready(function () {
    const dt = new DataTransfer();
    const dt2 = new DataTransfer();
    const dt3 = new DataTransfer();
    $(document).ready(function ($) {
      var subTotal = 0;
      /*$(".check_qty").each(function (i, o) {
        var id = $(this).attr('data-item');
        var netPrice = $(this).attr('data-net-price');
        var minstock = $(this).data('minstock');
        if ($(o).val() > minstock) { $(o).val(minstock) }
        $("#net_value_" + id).html($(o).val() * netPrice);
      });*/
      $('.net_value').each(function (i, obj) {
        var tmp = 0
        if ($(obj).html() == NaN) { tmp = 0; }
        else { tmp = $(obj).html(); }
        subTotal = (subTotal + parseFloat(tmp));
      });
      gst = subTotal * 18 / 100;
      $("#sub_total").html(subTotal);
      $("#total_gst").html(gst);
      var cc = (subTotal + gst).toFixed(3);
      $("#total_value").html(cc);
      $('#invoice_value').val(cc)
    });

    $('#invoices').on('change', function (event) {
      var files = event.target.files;
      for (var i = 0; i < this.files.length; i++) {
        let fileBloc = $('<span/>', { class: 'file-block' }),
          fileName = $('<span/>', { class: 'name', text: this.files.item(i).name });
        $("#invoice_filesList > #files-names").append(fileBloc);
        fileBloc.append('<span class="file-invoice-delete"><span><i class="fas fa-times-circle text-danger"></i></span></span>').append(fileName);
      };

      for (let file of this.files) { dt.items.add(file); }
      this.files = dt.files;

      $('span.file-invoice-delete').click(function () {
        let name = $(this).next('span.name').text();
        $(this).parent().remove();
        for (let i = 0; i < dt.items.length; i++) {
          if (name === dt.items[i].getAsFile().name) {
            dt.items.remove(i);
            continue;
          }
        }
        document.getElementById('invoices').files = dt.files;
      });
    });

    $('#ewaybill').on('change', function (event) {
      var files = event.target.files;
      for (var i = 0; i < this.files.length; i++) {
        let fileBloc = $('<span/>', { class: 'file-block' }),
          fileName = $('<span/>', { class: 'name', text: this.files.item(i).name });
        $("#ewaybill_filesList > #files-names").append(fileBloc);
        fileBloc.append('<span class="file-ewaybill-delete"><span><i class="fas fa-times-circle text-danger"></i></span></span>').append(fileName);
      };

      for (let file of this.files) { dt2.items.add(file); }
      this.files = dt2.files;

      $('span.file-ewaybill-delete').click(function () {
        let name = $(this).next('span.name').text();
        $(this).parent().remove();
        for (let i = 0; i < dt2.items.length; i++) {
          if (name === dt2.items[i].getAsFile().name) {
            dt2.items.remove(i);
            continue;
          }
        }
        document.getElementById('ewaybill').files = dt2.files;
      });
    });

    $('#others').on('change', function (event) {
      var files = event.target.files;
      for (var i = 0; i < this.files.length; i++) {
        let fileBloc = $('<span/>', {
          class: 'file-block'
        }),
          fileName = $('<span/>', {
            class: 'name',
            text: this.files.item(i).name
          });
        $("#others_filesList > #files-names").append(fileBloc);
        fileBloc.append('<span class="file-others-delete"><span><i class="fas fa-times-circle text-danger"></i></span></span>')
          .append(fileName);
      };

      for (let file of this.files) { dt3.items.add(file); }
      this.files = dt3.files;

      $('span.file-others-delete').click(function () {
        let name = $(this).next('span.name').text();
        $(this).parent().remove();
        for (let i = 0; i < dt3.items.length; i++) {
          if (name === dt3.items[i].getAsFile().name) {
            dt3.items.remove(i);
            continue;
          }
        }
        document.getElementById('others').files = dt3.files;
      });
    });


    // file upload button
    $('#OpenImgUpload').click(function () { $('#invoices').trigger('click'); });
    $('#OpenImgUploadEwaybill').click(function () { $('#ewaybill').trigger('click'); });
    $('#OpenImgUploadOthers').click(function () { $('#others').trigger('click'); });


    $(document).on('keyup', '.check_qty', function () {
      var id = $(this).attr('data-item');
      var netPrice = $(this).attr('data-net-price');

      $("#net_value_" + id).html($(this).val() * netPrice);

      var subTotal = 0;
      $('.net_value').each(function (i, obj) {
        var tmp = 0
        if ($(obj).html() == NaN) { tmp = 0; }
        else { tmp = $(obj).html(); }
        subTotal = (subTotal + parseFloat(tmp));
      });

      gst = subTotal * 18 / 100;
      $("#sub_total").html(subTotal);
      $("#total_gst").html(gst);
      $("#total_value").html(subTotal + gst);
      $('#invoice_value').val(subTotal + gst)
    });

    // $.validator.addMethod("validateVehicleNo", function (value, element) {
    //   const pattern = /^[A-Z]{2}\d{2}[a-z]{2,}\d{4}$/;
    //   return this.optional(element) || pattern.test(value);
    // }, "Please enter a valid vehicle number (e.g., MH02vd2626)");

    $("#asnForm").validate({
      rules: {
        invoice_no: {
          required: true,
          maxlength: 15,
          //pattern: /^\d{10}$/,
        },
        vehicle_no: {
          required: true,
          maxlength: 12,
          //validateVehicleNo: true
        },
        driver_name: {
          required: true,
          maxlength: 15,
        },
        transporter_name: {
          maxlength: 30,
        },
        invoice_date: {
          required: true,
          date: true,
        },
        driver_contact: {
          required: true,
          number: true,
          maxlength: 10,
          minlength: 10
          //pattern: /^\d{10}$/,
        },
        transporter_name : {
          maxlength: 30,
        },
        invoices: {
          required: true
        },
        "qty[]": {
          required: true,
          number: true,
          //maxlength: 5,
          checkQty: true
        },
      },
      messages: {
        vehicle_no: {
          required: "Please enter a vehicle no"
        },
        driver_name: {
          required: "Please enter a driver name"
        },
        driver_contact: {
          required: "Please enter a driver contact",
          number: "Please enter number only"
        },
        invoices: {
          required: "Please upload file"
        },
        "qty[]": {
          required: "Please enter a quantity",
          number: "Please enter a valid number",
          //maxlength: "Maximum length exceeded",
          checkQty: "Current value is less than shiping qty"
        }
      },
      errorElement: 'span',
      errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      },
      submitHandler: function (form, event) {
        event.preventDefault();
        // $('#asnForm')[0].submit();
        $('#modal-confirm').modal('show');
        return false;
      }
    });


    $("#vehicle-no").on("keyup", function () {
      var splpattern = /[^a-zA-Z0-9]/g;
      $(this).val(($(this).val()).replace(splpattern, ''));
      const currentValue = $(this).val();
      const capitalizedValue = currentValue.slice(0, 2).toUpperCase() + currentValue.slice(2);
      $(this).val(capitalizedValue);
    });

    $("#Create-btn").click(function () {
      var currentDate = new Date();
      var inputDate = new Date($("#invoice-date").val());
      if(inputDate.setHours(0, 0, 0, 0) >= currentDate.setHours(0, 0, 0, 0)){
        if ($("#asnForm").valid()) { // Check form validation
          $('#modal-confirm').modal('show'); // Show the modal
        }
      } else {
        Toast.fire({ icon: "error", title: 'Past Invoice Date Restricted' });
      }

      /*if ($("#asnForm").valid()) { // Check form validation
        $('#modal-confirm').modal('show'); // Show the modal
      }*/
    });
    // 
    $('#modal-confirm').on('click', '.btn-success', function () {
      if ($("#asnForm").valid()) { // Check form validation again before submitting
        $('#modal-confirm').modal('hide'); // Hide the modal
        //$('#asnForm')[0].submit(); // Submit the form

        var fd = new FormData($('#asnForm')[0]);

        $.ajax({
                type: "post",
                dataType: 'json',
                processData: false, // important
                contentType: false, // important
                url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/purchase-orders', 'action' => 'save-asn', $poHeader[0]->id)); ?>",
                data: fd,
                beforeSend: function () { $("#gif_loader").show(); },
                success: function (response) {
                    console.log(response);
                    if (response.status) {
                      Toast.fire({ icon: "success", title: response.message, allowHtml: true});
                      location.href = '<?php echo \Cake\Routing\Router::url(array('controller' => '/asn', 'action' => 'index')); ?>';
                    } else {
                         Toast.fire({ icon: "error", title: response.message,allowHtml: true});
                    }
                },
                complete: function () { $("#gif_loader").hide(); }
            });
      }
    });
    $.validator.addMethod('checkQty', function (value, element) {
      if (parseInt(value) == 0) {
        return false;
      }

      if (parseInt(value) > parseInt($(element).attr('data-check'))) {
        return false;
      }
      return true;
    }, 'message');

    // $('.row').attr('style', 'width:110vw;');


    $(document).on("click", ".flu", function () {
      if ($(this).data('alt') == '+') {
        $(this).data('alt', '-');
        $(this).empty();
        $(this).append('Remove');
      } else {
        $(this).data('alt', '+');
        $(this).empty();
        $(this).append('add');
      }
    });
  });

  // for page leave popup
  $(document).ready(function () { 
    // var previousUrl = null;
    // $('.nav-link').click(function () {
    //   previousUrl = $(this).attr('href');
    //   $("#modal-default").modal('show');
    //   $("#leaveButton").click(function () {
    //     if (previousUrl) {
    //       window.location.href = previousUrl;
    //     }
    //   });
    //   return false; // cancel the event
    // });

    var currStock = parseFloat($("#current_stock").text());
    var minStock = parseFloat($("#minimum_stock").text());

    // console.log('stock=' + currStock+"="+minStock );
    if (currStock < minStock) {
      $("#error_msg").text('Please maintain minimum stocks');
    }

  });
</script>