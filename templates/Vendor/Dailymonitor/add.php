<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Dailymonitor $dailymonitor
 */
?>
<!-- <?= $this->Html->css('cstyle.css') ?> -->
<!-- <?= $this->Html->css('custom') ?> -->
<!-- <?= $this->Html->css('table.css') ?> -->
<!-- <?= $this->Html->css('listing.css') ?> -->
<!-- <?= $this->Html->css('v_index.css') ?> -->
<?= $this->Html->css('search-filter.css') ?>
<?= $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css') ?>
<?= $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js') ?>
<div class="row">
    <div class="col-12">
        <?= $this->Form->create($dailymonitor) ?>
        <div class="card mb-2 card_box_shadow">
            <div class="card-header pb-1 pt-2">
                <div class="row">
                    <div class="col-lg-6 d-flex justify-content-start">
                        ADD PRODUCTION PLANNER
                    </div>
                </div>
            </div>
            <div class="card-body invoice-details">

                <div class="row dgf m-0">
                    <div class="col-sm-8 col-md-2">
                        <div class="form-group">
                            <?php echo $this->Form->control('plan_date', array('type' => 'date', 'class' => 'form-control custom-required w-100 form-field','min' => date('Y-m-d'))); ?>
                            <small class="text-danger error-message" style="display:none;">This field is required.</small>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-2 col-lg-2">
                        <div class="form-group">
                            <?php echo $this->Form->control('vendor_factory_id', array('class' => 'form-control custom-required  w-100 form-field', 'options' => $factory, 'empty' => 'Please Select', 'label' => 'Factory', 'required')); ?>
                            <small class="text-danger error-message" style="display:none;">This field is required.</small>
                        </div>
                    </div>
                    <div class="col-sm-8 col-md-2">
                        <div class="form-group">
                            <?php echo $this->Form->control('production_line_id', array('name' => 'production_line_id', 'class' => 'form-control custom-required  w-100 form-field', 'empty' => 'Please Select')); ?>
                            <small class="text-danger error-message" style="display:none;">This field is required.</small>
                        </div>
                    </div>
                    <div class="col-sm-8 col-md-3">
                        <div class="form-group">
                            <?php echo $this->Form->control('material_id', array('class' => 'form-control custom-required  w-100 form-field chosen', 'empty' => 'Please Select')); ?>
                            <small class="text-danger error-message" style="display:none;">This field is required.</small>
                        </div>
                        
                    </div>
                    <div class="col-sm-8 col-md-2">
                        <div class="form-group">
                            <?php echo $this->Form->control('target_production', array('type' => 'text', 'class' => 'form-control numberonly custom-required  rounded-0 w-100 form-field', 'maxlength' => '10', 'div' => 'form-group', 'required')); ?>
                            <?php echo $this->Form->control('prod_line', array('type' => 'hidden', 'class' => 'form-control rounded-0 w-100 form-field', 'div' => 'form-group', 'required')); ?>
                            <small class="text-danger error-message" style="display:none;">This field is required.</small>
                        </div>
                    </div>
                    <div class="col-sm-8 col-md-2" style="display:none;">
                        <div class="form-group">
                            <?php echo $this->Form->control('confirm_production', array('type' => 'number', 'value' => '0', 'class' => 'form-control rounded-0 w-100 form-field', 'min'=>'0', 'max'=>'999999999999', 'div' => 'form-group', 'required')); ?>
                        </div>
                    </div>
                    <div class="col-sm-8 col-md-1">
                        <div class="form-group mgt">
                            <button type="button" class="btn bg-gradient-submit" id="submit-btn" onclick="showConfirmationModal()">Add</button>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="modal fade" id="modal-sm" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <h6>Are you sure you want to Add?</h6>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn addCancel"  data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn addSubmit" >Ok</button>
                        </div>
                    </div>
                </div>
        </div>

        <?= $this->Form->end() ?>
    </div>
    <div class="col-12">
        <?= $this->Form->create(null, ['id' => 'formUpload', 'url' => ['controller' => '/dailymonitor', 'action' => 'upload-plan']]) ?>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="col-6">BULK PRODUCTION PLANNER</div>
                <div class="d-flex justify-content-end col-6 pr-0 ">
                    <div class="template_file pl-2 ml-3 mr-1" data-toggle="tooltip" data-original-title="Download Template" data-placement="bottom">
                        <a class="template_format" href="<?= $this->Url->build('/') ?>webroot/templates/production_planner_template.xlsx"
                            target="_blank" rel="noopener noreferrer"><i class="fa fa-solid fa-file-download"></i></a>
                    </div>
                    <div class="pl-1">
    
                        <?= $this->Form->control('upload_file', ['type' => 'file', 'label' => false, 'class' => 'pt-1 rounded-0', 'style' => 'visibility: hidden; position: absolute;', 'div' => 'form-group', 'id' => 'bulk_file']); ?>
                        <?= $this->Form->button('Select File', [
                            'id' => 'OpenImgUpload',
                            'type' =>
                                'button',
                            'label' => 'Select File',
                            'class' => 'd-block btn btn-block bg-gradient-button mb-0 file-upld-btn'
                        ]); ?>
                        <!-- <span id="filessnames"></span> -->
                    </div>
                    <div class="pl-2">
                        <button type="button" class="btn bg-gradient-submit" id="id_exportme">Upload</button>
                    </div>
    
                </div>
            </div>

            

            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover table-striped" id="example1">
                    <thead>
                        <tr>
                            <th>Factory</th>
                            <th>Production Line</th>
                            <th>Material</th>
                            <th>Target Production</th>
                            <th>Plan Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5" class="text-center">No data found !</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>
<?= $this->Html->script('v_dailymonitor_add') ?>
<script>
    $("#vendor-factory-id").change(function () {
  var lineId = $(this).val();
  $("#production-line-id").empty().append("<option value=''>Please Select</option>");
  $("#material-id").empty().append("<option value=''>Please Select</option>");

  if (lineId != "") {
      $.ajax({
          type: "get",
          url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/line-masters', 'action' => 'get-factory-lines')); ?>/" + lineId,
          dataType: "json",
          beforeSend: function (xhr) {
            $("#gif_loader").show();
              xhr.setRequestHeader(
                  "Content-type",
                  "application/x-www-form-urlencoded"
              );
          },
          success: function (response) {
              if (response.status) {
                  $.each(response.data.lines, function (key, val) { 
                       $("#production-line-id").append("<option value='"+val.id+"'>"+val.name+"</option>");
                  });
                  $.each(response.data.materials, function (key, val) { 
                       $("#material-id").append("<option value='"+val.id+"'>"+val.code+"</option>");
                  });
              }
          },
          error: function (e) {
              alert("An error occurred: " + e.responseText.message);
              console.log(e);
          },
          complete: function () { $("#gif_loader").hide(); }
      });
  }
});

$("#production-line-id").change(function () {
        var lineId = $(this).val();
        $("#material-id").empty().append("<option value=''>Please Select</option>");
        if (lineId != "") {
            $.ajax({
                type: "get",
                url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/production-lines', 'action' => 'get-line-materials')); ?>/" + lineId,
                dataType: "json",
                beforeSend: function (xhr) {
                    $("#gif_loader").show();
                    xhr.setRequestHeader(
                        "Content-type",
                        "application/x-www-form-urlencoded"
                    );
                },
                success: function (response) {
                    if (response.status) {
                        $.each(response.data.materials, function (key, val) { 
                            $("#material-id").append("<option value='"+val.id+"' prod-line='"+val.prod_line+"' data-capacity='"+val.capacity+"'>"+val.code +" - "+val.description+"</option>");
                        });
                    }
                },
                error: function (e) {
                    alert("An error occurred: " + e.responseText.message);
                    console.log(e);
                },
                complete: function () { $("#gif_loader").hide(); }
            });
        }
    });


    $("#material-id").change(function () {
        var capacity = $('option:selected', this).attr("data-capacity");
        var line = $('option:selected', this).attr("prod-line");
        console.log(capacity);
        //$("#target-production").val(capacity);
        $("#prod-line").val(line);
    });

    $('#OpenImgUpload').click(function() {
        $('#bulk_file').trigger('click');
    });
    $('#bulk_file').change(function () {
        var file = $(this).prop('files')[0];
        var fileName = file ? file.name : '';

        $('#OpenImgUpload').text(fileName ?  fileName : 'Choose File');
    });

    $("#id_exportme").click(function() {
        var fd = new FormData($('#formUpload')[0]);

        $.ajax({
            url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/dailymonitor', 'action' => 'upload-plan')); ?>",
            type: "post",
            dataType: 'json',
            processData: false, // important
            contentType: false, // important
            data: fd,
            beforeSend: function () { $("#gif_loader").show(); },
            success: function(response) {
                if (response.status) {
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    });

                    $("#example1 tbody").empty();

                    // Loop through the response data and build the table rows dynamically
                    $.each(response.data, function (key, val) { 
                        var rowHtml = `<tr>
                        <td> `+ val.factory_code + `</td>
                        <td> `+ val.line + `</td>
                        <td> `+ val.material +`</td>
                        <td> `+ val.target_production + `</td>
                        <td> `+ val.plan_date + `</td>
                        <td> `+ val.error + `</td>
                        </tr>`;
                        $("#example1 tbody").append(rowHtml);
                    });

                } else {
                    Toast.fire({
                        icon: 'error',
                        title: response.message
                    });
                }
            },
            error: function() {
                Toast.fire({
                    icon: 'error',
                    title: 'An error occured, please try again.'
                });
            },
            complete: function () { $("#gif_loader").hide(); }
        });
    });

    $('.chosen').select2({
        closeOnSelect : false,
        placeholder: 'Select',
        allowClear: true,
        tags: false,
        tokenSeparators: [','],
    });
    
</script>

