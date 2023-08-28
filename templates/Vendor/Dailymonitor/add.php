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
<div class="row">
    <div class="col-12">
        <?= $this->Form->create($dailymonitor) ?>
        <div class="card card_box_shadow">
            <div class="card-header pb-1 pt-2">
                <div class="row">
                    <div class="col-lg-6 d-flex justify-content-start">
                        <h5><b>Add Production Planner</b></h5>
                    </div>
                </div>
            </div>
            <div class="card-body invoice-details">

                <div class="row dgf m-0">
                    <div class="col-sm-8 col-md-2">
                        <div class="form-group">
                            <?php echo $this->Form->control('plan_date', array('type'=>'date', 'class' => 'form-control w-100')); ?>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-2 col-lg-2">
                        <div class="form-group">
                            <?php echo $this->Form->control('vendor_factory_id', array('class' => 'form-control w-100', 'options' => $factory, 'empty' => 'Please Select', 'label' => 'Factory', 'required')); ?>
                        </div>
                    </div>
                    
                    <div class="col-sm-8 col-md-2">
                        <div class="form-group">
                            <?php echo $this->Form->control('production_line_id', array('name' => 'production_line_id', 'class' => 'form-control w-100', 'empty' => 'Please Select')); ?>
                        </div>
                    </div>
                    <div class="col-sm-8 col-md-2">
                        <div class="form-group">
                            <?php echo $this->Form->control('material_id', array('class' => 'form-control w-100', 'empty' => 'Please Select')); ?>
                        </div>
                    </div>

                    <div class="col-sm-8 col-md-2">
                        <div class="form-group">
                            <?php echo $this->Form->control('target_production', array('type' => 'number', 'class' => 'form-control rounded-0 w-100', 'div' => 'form-group', 'required')); ?>
                        </div>
                    </div>

                    <div class="col-sm-8 col-md-2" style="display:none;">
                        <div class="form-group">
                            <?php echo $this->Form->control('confirm_production', array('type' => 'number', 'value' => '0','class' => 'form-control rounded-0 w-100', 'div' => 'form-group', 'required')); ?>
                        </div>
                    </div>
                    <div class="col-sm-8 col-md-2 d-flex justify-content-end align-items-end">
                        <div class="form-group">
                            <button type="button" class="btn bg-gradient-submit" onclick="showConfirmationModal()">Submit</button>
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
        <?= $this->Form->create(null, ['id' => 'formUpload', 'url' => ['controller' => '/dailymonitor', 'action' => 'upload']]) ?>
        <div class="card">
            <div class="card-header">
                <h5><b>Bulk Production Planner</b></h5>
            </div>

            <div class="card-body">
            <div class="row">
                <div class="col-sm-6 col-md-4 col-lg-2">

                    <?= $this->Form->control('upload_file', ['type' => 'file', 'label' => false, 'class' => 'pt-1 rounded-0', 'style' => 'visibility: hidden; position: absolute;', 'div' => 'form-group', 'id' => 'bulk_file']); ?>
                    <?= $this->Form->button('Upload File', ['id' => 'OpenImgUpload', 'type' =>
                    'button', 'label' => 'Upload File', 'class' => 'd-block btn btn-block bg-gradient-button mb-0 file-upld-btn']); ?>
                    <span id="filessnames"></span>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-2">
                    <button type="button" class="btn bg-gradient-submit" id="id_exportme">IMPORT FILE</button>
                </div>
                <div class="col-12 pt-2">
                    <i style="color: black;">
                    <a href="<?= $this->Url->build('/') ?>webroot/templates/production_planner_template.xlsx"
                                    target="_blank" rel="noopener noreferrer">Sample_Excel_Template.xlsx</a>
                    </i>
                </div>
            </div>
    </div>

            <div class="card-footer table-responsive">
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
                    xhr.setRequestHeader(
                        "Content-type",
                        "application/x-www-form-urlencoded"
                    );
                },
                success: function (response) {
                    if (response.status) {
                        $.each(response.data.materials, function (key, val) { 
                            $("#material-id").append("<option value='"+val.id+"' data-capacity='"+val.capacity+"'>"+val.description+"</option>");
                        });
                    }
                },
                error: function (e) {
                    alert("An error occurred: " + e.responseText.message);
                    console.log(e);
                },
            });
        }
    });


    $("#material-id").change(function () {
        var capacity = $('option:selected', this).attr("data-capacity");
        console.log(capacity);
        $("#target-production").val(capacity);
    });

    $('#OpenImgUpload').click(function() {
        $('#bulk_file').trigger('click');
    });
    $('#bulk_file').change(function() {
        var file = $(this).prop('files')[0].name;
        $("#filessnames").append(file);
    });

    $("#id_exportme").click(function() {
        var fd = new FormData($('#formUpload')[0]);

        $.ajax({
            url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/dailymonitor', 'action' => 'upload')); ?>",
            type: "post",
            dataType: 'json',
            processData: false, // important
            contentType: false, // important
            data: fd,
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
            }
        });
    });
    
</script>

