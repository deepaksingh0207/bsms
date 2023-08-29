<?php

/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Dailymonitor> $dailymonitor
 */
?>
<!-- <?= $this->Html->css('cstyle.css') ?> -->
<!-- <?= $this->Html->css('custom') ?> -->
<!-- <?= $this->Html->css('table.css') ?> -->
<!-- <?= $this->Html->css('listing.css') ?> -->
<!-- <?= $this->Html->css('v_index.css') ?> -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-6 d-flex justify-content-start">
                        <h5>Production Planner</h5>
                    </div>
                    <div class="col-lg-6 d-flex justify-content-end text-align-end">
                        <a href="<?= $this->Url->build('/') ?>vendor/dailymonitor/add"><button type="button" id="continueSub" class="btn continue_btn bg-gradient-button">Add Monitor</button></a>
                    </div>
                </div>
            </div>
            <div class="card-body" id="id_pohead">
                <table class="table table-hover table-striped table-bordered" id="example1">
                    <thead>
                        <tr>
                            <th>Plan Date</th>
                            <th>Production Line</th>
                            <th>Material</th>
                            <th>Production Plan</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($dailymonitor)) : ?>
                        <?php foreach ($dailymonitor as $dailymonitors) : 

                        //echo '<pre>'; print_r($dailymonitors); exit;
                            $status = 'Active';
                            if($dailymonitors->status == 2) { 
                                $status = 'Cancelled'; 
                            } else if($dailymonitors->status == 3) { 
                                $status = 'Production Confirmed'; 
                            }
                            ?>
                            <tr>
                                <td><?= h($dailymonitors->plan_date) ?></td>
                                <td><?= h($dailymonitors->production_line->line_master->name) ?></td>
                                <td><?= h($dailymonitors->material->description) ?></td>
                                <td><?= h($dailymonitors->target_production) ?></td>
                                <td><?= h($status) ?></td>
                                <td>
                                    <?php if($dailymonitors->status == 1) : ?>
                                    <div class="float-left">
                                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $dailymonitors->id], ['class' => 'btn btn-info btn-sm mb-0']) ?>
                                    
                                        <!-- <?= $this->Html->link(__('Cancel'), "#", ['data-value' => $dailymonitors->id, 'data-key' => 'cancel', 'class' => 'btn btn-info btn-sm mb-0 cancel']) ?> -->
                                    </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">
                                    No Records Found
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
    var table = $("#example1").DataTable({
            "paging": true,
            "responsive": false,
            "lengthChange": false,
            "autoWidth": false,
            "searching": false,
            "ordering": false,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search..."
            },
        });
    
        $(document).on("click", ".cancel", function() {
            var value = $(this).attr('data-value');
            var action = $(this).attr('data-key');
            if (value != "") {
                $.ajax({
                    type: "get",
                    url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/daily-monitor', 'action' => 'change-status')); ?>/" + action +"/"+value,
                    dataType: "json",
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader(
                            "Content-type",
                            "application/x-www-form-urlencoded"
                        );
                    },
                    success: function(response) {
                        if (response.status == "1") {
                            table.draw();
                            Toast.fire({
                                icon: 'success',
                                title: response.message
                            });
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: response.message
                            });
                        }
                    },
                    error: function(e) {
                        alert("An error occurred: " + e.responseText.message);
                        console.log(e);
                    },
                });
            }
        });
    });

</script>