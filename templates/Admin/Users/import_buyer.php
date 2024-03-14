<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\AdminUser[]|\Cake\Collection\CollectionInterface $adminUsers
 */


?>

<?= $this->Html->css('admincss') ?>
<div class="content admin-console">
  <div class="row">
    <div class="col-12 useradd ">
      <div class="card" >
        <div class="card-header">
          CREATE BUYER
        </div>


        <?= $this->Form->create(null, ['id' => 'userForm']) ?>
        <div class="card-body">
          <div class="row">
            <div class="col-4">
              <div class="form-group">
                <?php
                      echo $this->Form->control('sap_user', ['label' => 'SAP User',
                          'class' => 'form-control',
                          'placeholder' => 'Enter SAP user'
                      ]);
                      ?>
              </div>
            </div>
          </div>
          <div>
            <button type="submit" class="submit_btn btn btn-primary">Submit</button>
          </div>
        </div>
        <?= $this->Form->end() ?>
      </div>
    </div>
  </div>
</div>



<script>
  var userlisturl = '<?= $this->Url->build(['controller' => 'users','action' => 'index']); ?>';
  var useraddurl = '<?= $this->Url->build(['controller' => 'users','action' => 'check-buyer']); ?>';

  $(document).ready(function () {
    $("#userForm").validate({
      rules: {
        sap_user: {
          required: true,
        },
      },
      messages: {
        sap_user: {
          required: "Please enter a SAP user",
        },
      },
      errorElement: "span",
      errorPlacement: function (error, element) {
        error.addClass("invalid-feedback");
        element.closest(".form-group").append(error);
      },
      highlight: function (element, errorClass, validClass) {
        $(element).addClass("is-invalid");
      },
      unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass("is-invalid");
      },
      submitHandler: function (form) {
        $.ajax({
          type: "POST",
          url: useraddurl,
          data: $("#userForm").serialize(),
          dataType: "json",
          beforeSend: function () { $("#gif_loader").show(); 
          },
          success: function (response) {
            console.log(response);
            if (response.status) {
              Toast.fire({
                icon: "success",
                title: response.message,
              });
              setTimeout(function() {
                window.location.href = userlisturl;
              }, 2000);
              //form.submit(); // Submit the form without referencing the current page
            } else {
              Toast.fire({
                icon: "error",
                title: response.message,
              });
            }
          },
          complete: function () { $("#gif_loader").hide(); }
        });
      },
    });
  });

</script>