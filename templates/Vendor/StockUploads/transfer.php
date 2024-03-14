<?= $this->Html->css('select2.min.css') ?>
<?= $this->Html->script('select2.js') ?>
<?= $this->Html->css('bootstrap-multiselect') ?>
<?= $this->Html->css('dropdown-filter') ?>
<?= $this->Html->script('bootstrap-multiselect') ?>
<?= $this->Html->script('FilterMultiSelect') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">STOCK TRANSFER</div>
            <div class="card-body">
                <?= $this->Form->create(null, ['id' => 'transferStock']) ?>
                <div class="row">
                    <div class="col-3">
                        <?php echo $this->Form->control('vendor_factory_id', array('label' => 'Factory', 'class' => 'form-control rounded-0', 'options' => $vendor_factory_code, 'maxlength' => '20', 'div' => 'form-group', 'required', 'empty' => 'Please Select')); ?>
                        <?php echo $this->Form->control('vendor_factory_code', array('type' => 'hidden')); ?>
                    </div>
                    <div class="col-3">
                        <?php echo $this->Form->control('from_material', array('label' => 'From Material', 'class' => 'form-control chosen rounded-0', 'options' => [], 'maxlength' => '20', 'div' => 'form-group', 'required', 'empty' => 'Please Select')); ?>
                        <?php echo $this->Form->control('from_material_id', array('type' => 'hidden')); ?>
                        <?php echo $this->Form->control('out_transfer_stock', array('type' => 'hidden')); ?>
                        <span id="availableStockColumn1">Available Stock: </span>
                    </div>

                    <div class="col-3">
                        <?php echo $this->Form->control('to_material', array('label' => 'To Material', 'class' => 'form-control chosen rounded-0', 'options' => [], 'maxlength' => '20', 'div' => 'form-group', 'required', 'empty' => 'Please Select')); ?>
                        <?php echo $this->Form->control('to_material_id', array('type' => 'hidden')); ?>
                        <?php echo $this->Form->control('in_transfer_stock', array('type' => 'hidden')); ?>
                        <span id="availableStockColumn2">Available Stock: </span>
                    </div>
                    <div class="col-2">
                        <label for="">Stock Transfer</label>
                        <input type="text" name="stock_qty" maxlength="10" class="form-control numberonly"
                            placeholder="Enter Stock" id="stockTransferInput">
                    </div>

                    <div class="col-1 mt-4 pt-2">
                        <button type="button" id="transfer" class="btn mb-0 continue_btn float-right"
                            data-toggle="modal" data-target="#modal-sm" disabled>Transfer</button>
                    </div>

                </div>
                <?= $this->Form->end() ?>
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
                <button type="button" class="btn cancel_btn" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn continue_btn" onclick="performStockTransfer()">Ok</button>
            </div>
        </div>
    </div>
</div>

<script>

    stockData = {
        Material1: 100,
        Material2: 150,
        Material3: 200
    };

    $(document).on("change", "#stockTransferInput", function () {
        const inputValue = $(this).val();
        const materialColumn1 = $("#from-material").val();
        const materialColumn2 = $("#to-material").val();
        const transferButton = $("#transfer");

        if (materialColumn1 === "" || materialColumn2 === "") {
            $(this).val('');
            transferButton.prop('disabled', true);
        } else {
            const totalStock = stockData[materialColumn1];

            if (!/^[1-9]\d*$/.test(inputValue) || parseInt(inputValue) > totalStock) {
                $(this).val('');
                transferButton.prop('disabled', true);
            } else {
                transferButton.prop('disabled', false);
            }
        }
    });


    $(document).on("change", "#vendor-factory-id", function () {
        var factoryId = $(this).val();

        // Store the selected values
        var fromMaterialValue = $("#from-material").val();
        var toMaterialValue = $("#to-material").val();

        stockData = {};
        $("#from-material, #to-material").empty();

        $("#vendor-factory-code").val($(this).find('option:selected').text());
        if (factoryId === "") {
            $("#from-material").append("<option value=''>Please Select</option>");
            $("#to-material").append("<option value=''>Please Select</option>");
        } else {
            $.ajax({
                type: "get",
                url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/stock-uploads', 'action' => 'get-factory-materials')); ?>/" + factoryId,
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
                        // Add "Please Select" option first
                        $("#from-material").append("<option value=''>Please Select</option>");
                        $("#to-material").append("<option value=''>Please Select</option>");

                        $.each(response.data.materials, function (key, val) {
                            $("#from-material").append("<option value='" + val.code + "' data-id='" + val.id + "' data-out-stock='" + val.out_transfer_stock + "' >" + val.code + ' - ' + val.description + "</option>");
                            $("#to-material").append("<option value='" + val.code + "' data-id='" + val.id + "' data-in-stock='" + val.in_transfer_stock + "'>" + val.code + ' - ' + val.description + "</option>");
                            stockData[val.code] = val.current_stock;
                        });
                        // console.log(stockData);

                        // Reapply the stored selected values
                        $("#from-material").val(fromMaterialValue);
                        $("#to-material").val(toMaterialValue);
                    }
                },
                error: function (e) {
                    Toast.fire({
                        icon: 'error',
                        title: 'An error occurred: ' + e.responseText.message
                    });
                    console.log(e);
                },
                complete: function () {
                    $("#gif_loader").hide();
                }
            });
        }
    });

    $('.chosen').select2({
        closeOnSelect: false,
        placeholder: 'Select',
        allowClear: true,
        tags: false,
        tokenSeparators: [','],
        templateSelection: function (selection) {
            if (selection.element && $(selection.element).attr('data-select') !== undefined) {
                return $(selection.element).attr('data-select');
            } else { return selection.text; }
        }
    });

    $(document).on("change", "#from-material", function () {
        const selectedMaterial = $(this).val();
        const stockTransferInput = $("#stockTransferInput");

        $("#from-material-id").val($(this).find('option:selected').attr('data-id'));
        $("#out-transfer-stock").val($(this).find('option:selected').attr('data-out-stock'));
        if (selectedMaterial === "") {
            stockTransferInput.prop('disabled', true); // Disable the stock transfer input
            stockTransferInput.val(''); // Clear the input value
        } else {
            stockTransferInput.prop('disabled', false); // Enable the stock transfer input
            $('#availableStockColumn1').html(`Available Stock: <strong>${stockData[selectedMaterial] || 0}</strong>`);
            validateMaterialSelection();
        }
    });

    $(document).on("change", "#to-material", function () {
        const selectedMaterial = $(this).val();
        $("#to-material-id").val($(this).find('option:selected').attr('data-id'));
        $("#in-transfer-stock").val($(this).find('option:selected').attr('data-in-stock'));
        $('#availableStockColumn2').html(`Available Stock: <strong>${stockData[selectedMaterial] || 0}</strong>`);
        validateMaterialSelection();
    });

    function validateMaterialSelection() {
        const materialColumn1 = $("#from-material").val();
        const materialColumn2 = $("#to-material").val();

        const stockTransferInput = $("#stockTransferInput");

        if (materialColumn1 === "") {
            stockTransferInput.prop('disabled', true); // Disable the stock transfer input
            stockTransferInput.val(''); // Clear the input value
        } else {
            stockTransferInput.prop('disabled', false); // Enable the stock transfer input
        }

        if (materialColumn1 === materialColumn2) {
            Toast.fire({
                icon: 'error',
                title: 'Error: Cannot select the same Material for Stock Transfer.'
            });

            // Clear both input values and span text
            $("#from-material, #to-material").val("");
            $('#availableStockColumn1, #availableStockColumn2').text("Available Stock:");

            return false; // Returning false to indicate validation failure
        }

        return true; // Returning true to indicate validation success
    }


    function performStockTransfer() {
        const stockTransferInput = $("#stockTransferInput");
        const transferAmount = parseFloat(stockTransferInput.val());

        if (isNaN(transferAmount) || transferAmount <= 0) {
            Toast.fire({
                icon: 'error',
                title: 'Please enter a valid positive number for stock transfer.'
            });
            return;
        }


        const materialColumn1 = $("#from-material").val();
        const materialColumn2 = $("#to-material").val();

        if (materialColumn1 && materialColumn2) {
            // Convert stock values to numbers and add the transfer amount
            stockData[materialColumn1] = (+stockData[materialColumn1] || 0) - transferAmount;
            stockData[materialColumn2] = (+stockData[materialColumn2] || 0) + transferAmount;

            $('#availableStockColumn1').text(`Available Stock: ${stockData[materialColumn1]}`);
            $('#availableStockColumn2').text(`Available Stock: ${stockData[materialColumn2]}`);

            Toast.fire({
                icon: 'success',
                title: 'Stock Transfer Successful'
            });

            var fd = new FormData($('#transferStock')[0]);

            $.ajax({
                type: "POST",
                url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/stock-uploads', 'action' => 'save-transfer')); ?>",
                dataType: 'json',
                processData: false, // important
                contentType: false, // important
                data: fd,
                beforeSend: function () { $("#gif_loader").show(); },
                success: function (response) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Stock Transfer Successful'
                    });
                    if (response.status) {
                        Toast.fire({ icon: 'success', title: response.message });
                    } else {
                        Toast.fire({ icon: 'error', title: response.message });
                    }
                },
                error: function () { Toast.fire({ icon: 'error', title: 'An error occured, please try again.' }); },
                complete: function () { $("#gif_loader").hide(); }
            });

            // Hide the Bootstrap modal
            $('#modal-sm').modal('hide');
        } else {
            Toast.fire({
                icon: 'error',
                title: 'Please select materials before transferring stock.'
            });
        }
    }
</script>