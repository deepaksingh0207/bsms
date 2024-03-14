<?= $this->Html->css('bootstrap-multiselect') ?>
<?= $this->Html->script('bootstrap-multiselect') ?>
<?= $this->Html->css('dropdown-filter') ?>
<?= $this->Html->css('select2.min.css') ?>
<?= $this->Html->script('select2.js') ?>
<div class="row">
    <div class="col-lg-12">
        <div class="card mb-2">
            <div class="card-header">TRANSFER LOG</div>
            <div class="card-body">
                <?= $this->Form->create(null, ['id' => 'addvendorform']) ?>
                <div class="row">
                    <div class="col-2">
                        <label for="id_from">Transfer Date From</label>
                        <input type="date" name="from" class="form-control cherry" id="id_from">
                    </div>
                    <div class="col-2">
                        <label for="id_to">Transfer Date To</label>
                        <input type="date" name="to" class="form-control cherry" id="id_to">
                    </div>
                    <div class="col-4">
                        <label for="id_material">From & To Material</label><br>
                        <select name="material[]" id="id_material" multiple="multiple"
                            class="form-control chosen cherry">
                            <?php if (isset($materials)) : ?>
                            <?php foreach ($materials as $mat) : ?>
                            <?= h($mat) ?>
                            <option value="<?= h($mat['code']) ?>" data-select="<?= h($mat['code']) ?>">
                                <?= h($mat['code']) ?> -
                                <?= h($mat['description']) ?>
                            </option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
            
        </div>
    </div>
</div>
<div class="card">
<div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered" id="example1">
                        <thead>
                            <tr>
                                <th>Transfer Date</th>
                                <th>Factory Code</th>
                                <th>From Material</th>
                                <th>To Material</th>
                                <th>Transfer Qty.</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
</div>

<script>
    $('.chosen').select2({
        closeOnSelect: false,
        placeholder: 'Select',
        allowClear: true,
        tags: false,
        tokenSeparators: [','],
        templateSelection: function (selection) {
            if (selection.element && $(selection.element).attr('data-select') !== undefined) {
                return $(selection.element).attr('data-select');
            } else {
                return selection.text;
            }
        }
    });

    var nonschexp_dtbl = $("#example1").DataTable({
        searching: false,
        paging: true,
        // dom: 'Bfrtip',
        // buttons: [{
        //     extend: 'excel',
        //     text: 'Export to Excel',
        //     attr: { id: 'memebt' },
        //     filename: 'Transfer Log'
        // }]
    });

    $(document).on("change", ".cherry", function () { loadlog(); });

    function loadlog() {
        // $.ajax({
        //     type: "POST",
        //     url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/stock-uploads', 'action' => 'transferLog')); ?>/",
        //     data: { from: $('#id_from').val(), to: $('#id_to').val(), material: $('#id_material').val() },
        //     headers: { 'X-CSRF-Token': $('meta[name="csrfToken"]').attr('content') },
        //     dataType: "json",
        //     beforeSend: function () { $("#gif_loader").show(); },
        //     success: function (response) {
        //         if (response.status) {
        //             dd_data = response.data;
        //             nonschexp_dtbl.clear().draw();
        //             nonschexp_dtbl.rows.add(dd_data['data']).draw();
        //             nonschexp_dtbl.columns.adjust().draw();
        //         } else { nonschexp_dtbl.clear().draw(); }
        //     },
        //     complete: function () { $("#gif_loader").hide(); }
        // });

        $.ajax({
            type: "POST",
            url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/stock-uploads', 'action' => 'transferLog')); ?>/",
            data: $("#addvendorform").serialize(),
            dataType: 'json',
            beforeSend: function () { $("#gif_loader").show(); },
            success: function (response) {
                if (response.status) {
                    dd_data = response.data;
                    nonschexp_dtbl.clear().draw();
                    nonschexp_dtbl.rows.add(dd_data).draw();
                    nonschexp_dtbl.columns.adjust().draw();
                } else { nonschexp_dtbl.clear().draw(); }
            },
            complete: function () { $("#gif_loader").hide(); }
        });
    }

    loadlog();
</script>