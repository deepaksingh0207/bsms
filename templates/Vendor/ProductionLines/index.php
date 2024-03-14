<?php

/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Productionline> $productionline
 */
?>
<style>
    .hide {
        display: none;
    }
</style>
<?= $this->Html->css('bootstrap-multiselect') ?>
<?= $this->Html->script('bootstrap-multiselect') ?>
<?= $this->Html->css('dropdown-filter') ?>
<?= $this->Html->css('select2.min.css') ?>
<?= $this->Html->script('select2.js') ?>
<div class="card mb-2">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-sm-6 col-lg-6 d-flex justify-content-start">
                PRODUCTION LINE
            </div>
            <div class="col-sm-6 col-lg-6 d-flex justify-content-end text-align-end add_prod">
                <a href="<?= $this->Url->build('/') ?>vendor/production-lines/add"><button type="button"
                        id="continueSub" class="btn bg-gradient-button mb-0 continue_btn">Add Production Line</button></a>
            </div>
        </div>
    </div>
    <div class="card-body">
    <?= $this->Form->create(null, ['id' => 'addvendorform']) ?>
    <div class="row">
        <div class="col-sm-12 col-md-3 col-lg-2">
            <label for="">Production Line</label>
            <select name="lines[]" id="id_line" multiple="multiple" class="form-control chosen">
            <?php if (isset($lines)) : ?>
            <?php foreach ($lines as $mat) : ?>
            <option value="<?= h($mat["line_master"]->id) ?>" data-select="<?= h($mat["line_master"]->name) ?>">
                <?= h($mat["line_master"]->name) ?>
            </option>
            <?php endforeach; ?>
            <?php endif; ?>
        </select>
        </div>
        <div class="col-sm-12 col-md-3 col-lg-3">
        <label for="id_material">Material</label><br>
        <select name="material[]" id="id_material" multiple="multiple" class="form-control chosen">
            <?php if (isset($materials)) : ?>
            <?php foreach ($materials as $mat) : ?>
            <option value="<?= h($mat["material"]->code) ?>" data-select="<?= h($mat["material"]->code) ?>">
                <?= h($mat["material"]->code) ?> - <?= h($mat["material"]->description) ?>
            </option>
            <?php endforeach; ?>
            <?php endif; ?>
        </select>
        </div>
        <div class="col-sm-12 col-md-3 col-lg-2 mt-3 pt-3">
            <button class="btn bg-gradient-button" type="submit" id="id_sub">Search</button>
        </div>
    </div>
    <?= $this->Form->end() ?>
    </div>
    
</div>
<div class="card">
<div class="card-body" id="id_pohead">
        <table class="table table-bordered table-striped table-hover" id="example1">
            <thead>
                <tr>
                    <th>Production Line</th>
                    <th>Material Code</th>
                    <th>Material Description</th>
                    <th>Capacity (Per Day)</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<script>
    var prodrlist_url = `<?php echo \Cake\Routing\Router::url(array('controller' => '/production-lines', 'action' => 'prdordlist')); ?>`;

    $('.chosen').select2({
        closeOnSelect : false,
        placeholder: 'Select',
        allowClear: true,
        tags: false,
        tokenSeparators: [','],
        templateSelection: function(selection) {
            if (selection.element && $(selection.element).attr('data-select') !== undefined) {
                return $(selection.element).attr('data-select');
            } else { return selection.text; }
        }
    });

    setTimeout(function () {$('.success').fadeOut('slow');}, 2000);

    $(document).on("click", ".redirect", function () { window.location.href = $(this).data("href"); });
    
    $(function () {
    dtable = $("#example1").DataTable({
        "paging": true,
        "responsive": false,
        "lengthChange": false,
        "autoWidth": false,
        "searching": true,
        "ordering": true,
        "destroy": true,
        dom: 'Blfrtip',
        buttons: [{ extend: 'copy' }, { extend: 'excelHtml5', text: 'Export', title:''}]
    });

    $("#addvendorform").validate({
        submitHandler: function (form) {
            $.ajax({
                type: "POST",
                url: prodrlist_url,
                data: $("#addvendorform").serialize(),
                dataType: "json",
                beforeSend: function () { $("#gif_loader").show(); },
                success: function (response) {
                    console.log(response);
                    if (response.status) {
                        dtable.clear().draw();
                        dtable.rows.add(response.data).draw();
                        dtable.columns.adjust().draw();
                    } else { dtable.clear().draw(); }
                },
                complete: function () { $("#gif_loader").hide(); }
            });
        },
    });

    $("#id_sub").trigger("click");
});
</script>
