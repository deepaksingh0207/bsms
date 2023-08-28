<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Productionline $productionline
 */
?>
<?= $this->Html->css('cstyle.css') ?>
<?= $this->Html->css('custom') ?>
<?= $this->Html->css('table.css') ?>
<?= $this->Html->css('listing.css') ?>
<?= $this->Html->css('v_index.css') ?>
<?= $this->Form->create($productionline) ?>
<div class="card card_box_shadow">
    <div class="card-header pb-1 pt-2">
        <div class="row">
            <div class="col-lg-6 d-flex justify-content-start">
                <h5>Production Edit</h5>
            </div>
        </div>
    </div>


    <div class="card mb-0 card_box_shadow">
        <div class="card-body  pb-0">
            <div class="row">
                <div class="col-sm-8 col-md-3">
                    <div class="form-group">
                        <?php echo $this->Form->control('material_id', array('class' => 'form-control w-100', 'options' => $vendor_mateial, 'id' => 'descripe', 'style' => "height: unset !important;", 'empty' => 'Please Select', 'label' => 'Material Code', 'readonly')); ?>
                    </div>
                    
                </div>

                <div class="col-sm-8 col-md-3">
                    <div class="form-group">
                        <?php echo $this->Form->control('description', array('type' => 'text', 'class' => 'form-control rounded-0 w-100', 'style' => "height: unset !important;", 'div' => 'form-group', 'required', 'label' => 'Material Description', 'readonly')); ?>
                    </div>
                </div>
                <div class="col-sm-4 col-md-4 col-lg-3">
                    <div class="form-group">
                        <?php echo $this->Form->control('uom', array('type' => 'text', 'id' => 'uom', 'class' => 'form-control rounded-0 w-100', 'style' => "height: unset !important;", 'div' => 'form-group', 'required', 'label' => 'Unit Of Measurement', 'readonly')); ?>
                    </div>
                </div>
                <div class="col-sm-4 col-md-4 col-lg-3">
                    <?php echo $this->Form->control('name', ['class' => 'form-control mb-3', 'label' => 'Production Line Description', 'readonly']); ?>
                </div>
                <div class="col-sm-4 col-md-4 col-lg-3">
                    <?php echo $this->Form->control('capacity', ['class' => 'form-control mb-3', 'label' => 'Production Line Capacity']); ?>
                </div>
                <div class="col-sm-4 col-md-4 col-lg-4 mt-4">
                    <button type="button" class="production_line_submitbtn btn btn-custom mt-1" onclick="showConfirmationModal()">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-sm" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h6>Are you sure you want to Update?</h6>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn" style="border:1px solid #6610f2" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn" style="border:1px solid #28a745">Ok</button>
            </div>
        </div>
    </div>
</div>

<?= $this->Form->end() ?>



<script>
    function showConfirmationModal() {
        $('#modal-sm').modal('show');
    }

    $("#descripe").change(function() {
        var vendorId = $(this).val();
        if (vendorId != "") {
            $.ajax({
                type: "get",
                url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/stock-uploads', 'action' => 'material')); ?>/" + vendorId,
                dataType: "json",
                beforeSend: function(xhr) {
                    xhr.setRequestHeader(
                        "Content-type",
                        "application/x-www-form-urlencoded"
                    );
                },
                success: function(response) {
                    if (response.status == "1") {
                        $("#description").val(response.data.description);
                        $("#uom").val(response.data.uom);
                    }
                },
                error: function(e) {
                    alert("An error occurred: " + e.responseText.message);
                    console.log(e);
                },
            });
        }
    });


    $(document).ready(function() {
        setTimeout(function() {
            $('.success').fadeOut('slow');
        }, 2000);
        $("#descripe").trigger("change");
    });
</script>