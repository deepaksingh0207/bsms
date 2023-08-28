<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PoHeader $poHeader
 */
?>
<?= $this->Html->css('cstyle.css') ?>
<?= $this->Html->css('custom') ?>
<?= $this->Html->css('table.css') ?>
<?= $this->Html->css('listing.css') ?>
<?= $this->Html->css('v_index.css') ?>
<link rel="stylesheet"
  href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<div class="card">
  <div class="card-header">
    <div class="d-flex">
      <div class="col-md-6">
      <h5 class="text-info mt-2"><b>PO NO : 
        <?= h($poHeader[0]->po_no) ?>
      </b></h5>
      </div>
      <div class="col-md-6 d-flex justify-content-end">
        <h6 class="text-right">Expected Delivery Date <br> <b>May 28, 2022</b></h6>
        <a href="javascript:history.back()" class=" back-btn d-block create_asn_bckbtn"><i class="fas fa-angle-double-left"></i> BACK</a>
        <button type="submit" class="btn btn-info mb-0 ml-2 create_asn_btn">Create ASN</button>
      </div>
    </div>
   
  </div>
  <!-- <div class="card-body">
    <table class="table" style="border-left: .5px solid lightgray;border-right: .5px solid lightgray;border-bottom: .5px solid lightgray;">
      <tr style="background-color: #d3d3d36e;">
        <th>
          <?= __('Vendor Code') ?>
        </th>
        <th>
          <?= __('Po No') ?>
        </th>
        <th>
          <?= __('Document Type') ?>
        </th>
        <th>
          <?= __('Created By') ?>
        </th>
        <th>
          <?= __('Pay Terms') ?>
        </th>
        <th>
          <?= __('Currency') ?>
        </th>
        <th>
          <?= __('Exchange Rate') ?>
        </th>
        <th>
          <?= __('Created On') ?>
        </th>
        <th>
          <?= __('Added Date') ?>
        </th>
      </tr>
      <tr>
        <td>
          <?= h($poHeader->sap_vendor_code) ?>
        </td>
        <td>
          <?= h($poHeader->po_no) ?>
        </td>
        <td>
          <?= h($poHeader->document_type) ?>
        </td>
        <td>
          <?= h($poHeader->created_by) ?>
        </td>
        <td>
          <?= h($poHeader->pay_terms) ?>
        </td>
        <td>
          <?= h($poHeader->currency) ?>
        </td>
        <td>
          <?= $this->Number->format($poHeader->exchange_rate) ?>
        </td>
        <td>
          <?= h($poHeader->created_on) ?>
        </td>
        <td>
          <?= h($poHeader->added_date) ?>
        </td>
      </tr>
    </table>
  </div> -->
</div>

<?= $this->Form->create(null,['type' => 'file', 'id' => 'asnForm']) ?>
<?= $this->form->control('po_header_id', ['label' => false, 'type' => 'hidden', 'value'=> $poHeader[0]->id]) ?>


<div class="card">
  <div class="card-header">
    <h5><b><?= __('Invoice Details') ?></b></h5>
  </div>
  <div class="card-body invoice-details">
        <div class="row dgf" style="background-color:#f1f1f1 !important;width:100%">
        <div class="col-sm-8 col-md-2">
               <label>VENDOR</label>
               <p>Dharti Enterprise</p>
            </div>
            <div class="col-sm-8 col-md-2">
                <label>VENDOR CODE</label>
                <p>LARET0</p>
                
            </div>
            <div class="col-sm-8  col-md-2">
                <?php echo $this->Form->control('invoice_no', array('class' => 'form-control rounded-0','div' => 'form-group', 'required'));?>
            </div>
            <div class="col-sm-8 col-md-2">
                <?php echo $this->Form->control('invoice_date', array('type' => 'date', 'class' => 'form-control rounded-0','div' => 'form-group', 'required'));?>
            </div>

            <div class="col-sm-8 col-md-2">
                <?php echo $this->Form->control('invoice_value', array('type' => 'number', 'class' => 'form-control rounded-0','div' => 'form-group', 'required'));?>
            </div>
            
            <div class="col-sm-8 col-md-2">
                <?php echo $this->Form->control('vehicle_no', array('class' => 'form-control rounded-0','div' => 'form-group', 'required'));?>
            </div>
            <div class="col-sm-8 col-md-2">
                <?php echo $this->Form->control('driver_name', array('class' => 'form-control rounded-0','div' => 'form-group', 'required'));?>
            </div>

            <div class="col-sm-8 col-md-2">
                <?php echo $this->Form->control('driver_contact', array('type' => 'mobile', 'class' => 'form-control rounded-0','div' => 'form-group', 'required'));?>
            </div>
            
            <div class="col-sm-8 col-md-2">
            <?php echo $this->Form->control('invoices', array('label' => 'Upload Invoice', 'type' => 'file', 'class' => 'pt-1 rounded-0','div' => 'form-group', 'required'));?>
              
            </div>
        </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h5><b>
        <?= __('Material List') ?>
      </b></h5>
  </div>
  <div class="card-body">
    <?php if (count($poHeader)) : ?>
    <div class="table-responsive">
      <table class="table table-bordered material-list">
        <thead>
          <tr style="background-color: #fff;">
            <th>
              <?= __('Item') ?>
            </th>
            <th>
              <?= __('Material') ?>
            </th>
            <th>
              <?= __('Short Text') ?>
            </th>
            <th>
              <?= __('Pending Qty') ?>
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
              <?= __('Expected Date') ?>
            </th>
            
          </tr>
        </thead>
        <tbody>
          <?php foreach ($poHeader as $row) : ?>
          <tr>
            <td>
              <?= h($row['PoFooters']['item']) ?>
            </td>
            <td>
              <?= h($row['PoFooters']['material']) ?>
            </td>
            <td>
              <?= h($row['PoFooters']['short_text']) ?>
            </td>
            <td>
              <?= h($row['actual_qty']) ?>&nbsp;<?= h($row['PoFooters']['order_unit']) ?>
            </td>
            <td>
              <?= h($row['PoFooters']['net_price']) ?> &nbsp;<?= h($row['currency']) ?>
            </td>
            <td style="width:50px;">
              <?= $this->form->control('po_footer_id[]', ['label' => false, 'type' => 'hidden', 'value'=> $row['PoFooters']['id']]) ?>
              <?= $this->form->control('schedule_id[]', ['label' => false, 'type' => 'hidden', 'value'=> $row['PoItemSchedules']['id']]) ?>
              <?= $this->form->control('qty[]', ['label' => false, 'value' => 0, 'class' => 'form-control check_qty', 'type' => 'number', 'required' , 'data-item' => $row['PoFooters']['item'],'data-net-price' => $row['PoFooters']['net_price']]) ?>
            </td>
            <td class="net_value" id="net_value_<?= h($row['PoFooters']['item']) ?>">0</td>
            <td>
              <?= h($row['PoItemSchedules']['delivery_date']) ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

   <div class="calcu">
   <table>
      <tbody>
        <tr><td>  Sub Total : </td>
      <td><span id="sub_total"> 0 </span></td></tr>
        <tr><td> Total GST(18%) : </td>
      <td><span id="total_gst"> 0 </span></td></tr>
        <tr><td colspan="2"><hr class="mt-2 mb-2"></td></tr>
        <tr><td> <b>Total Value : </b></td>
      <td><b><span id="total_value"> 0 </span></b></td></tr>
      </tbody>
    </table>
   </div>
   
    <?php endif; ?>
  </div>
</div>
<?= $this->form->end() ?>

<script>
  $(document).ready(function () {
    // var Toast = Swal.mixin({
    //   toast: true,
    //   position: 'top-end',
    //   showConfirmButton: false,
    //   timer: 3000
    // });


    $(document).on('keyup', '.check_qty', function () {
        var id = $(this).attr('data-item');
        var netPrice = $(this).attr('data-net-price');

        $("#net_value_"+id).html($(this).val() * netPrice);

        var subTotal = 0;
        $('.net_value').each(function(i, obj) {
          var tmp = 0
          if($(obj).html() == NaN) {
              tmp = 0;
          } else {
            tmp = $(obj).html();
          }
          subTotal = (subTotal + parseFloat(tmp));
          console.log(subTotal);
        });

        gst  = subTotal*18/100;
        $("#sub_total").html(subTotal);
        $("#total_gst").html(gst);
        $("#total_value").html(subTotal + gst);
    });

    
    $("#asnForm").validate({
      rules: {
        vehicle_no: { required: true },
        driver_name: { required: true,  },
        driver_contact: { required: true, number: true, maxlength:10},
        "qty[]": { required: true,  number: true, maxlength:5, checkQty:true},
      },
      messages: {
        vehicle_no: { required: "Please enter a vehicle no" },
        driver_name: { required: "Please enter a driver name" },
        driver_contact: { required: "Please enter a driver contact",number: "Please enter number only" }
      },
      errorElement: 'span',
      errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function (element, errorClass, validClass) { $(element).addClass('is-invalid'); },
      unhighlight: function (element, errorClass, validClass) { $(element).removeClass('is-invalid'); },
      submitHandler: function(form, event) { 
        event.preventDefault();
        $('#asnForm')[0].submit();
        return false;
      }
    });

    $.validator.addMethod('checkQty', function(value, element) {
        if(parseInt(value) == 0) {
            return false;
        }

        if(parseInt(value) > parseInt($(element).attr('data-check'))) {
            return false;
        }
        return true;
    }, 'message');

    $('.row').attr('style','width:110vw;');
    

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


</script>
