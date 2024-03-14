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
      <div class="card">
        <div class="card-header">
          ADD MANAGER
        </div>


        <?= $this->Form->create(null, ['id' => 'userForm']) ?>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-4">
              <label for="mobileno">Role</label>
              <select class="custom-select" id="myCustomSelect" name="group_id" required>
                <!-- <option disabled selected>Please Select</option>
                <option value="1">Admin</option> -->
                <option value="4" selected>Manager</option>
              </select>
            </div>
            <div class="col-lg-4 ">
              <div class="form-group">
                <?php
                      echo $this->Form->control('first_name', [
                          'class' => 'form-control',
                          'placeholder' => 'Enter First Name'
                      ]);
                      ?>
              </div>
            </div>
            <div class="col-lg-4 ">
              <div class="form-group">
                <?php
                      echo $this->Form->control('last_name', [
                          'class' => 'form-control',
                          'placeholder' => 'Enter Last Name'
                      ]);
                      ?>
              </div>
            </div>
            <div class="col-lg-4 ">
              <div class="form-group">
                <?php echo $this->Form->control('email', array('class' => 'form-control rounded-0', 'placeholder' => 'please enter email id', 'required')); ?>
              </div>
            </div>


            <div class="col-lg-4 ">
              <div class="form-group">
                <?php echo $this->Form->control('mobile', array('class' => 'form-control tel numberonly', 'minlength' => '10', 'maxlength' => '10', 'pattern' => '[9,8,7,6]{1}[0-9]{9}', 'type' => 'tel', 'placeholder' => 'please enter mobile number')); ?>
              </div>
            </div>
            <div class="col-lg-1 mt-3 pt-3">
            <button type="submit" class="submit_btn btn btn-primary">Submit</button>
          </div>
          </div>
          
        </div>
        <?= $this->Form->end() ?>
      </div>
    </div>
  </div>
</div>



<script>
  var userlisturl = '<?= $this->Url->build(['controller' => 'users','action' => 'index']); ?>';
  var useraddurl = '<?= $this->Url->build(['controller' => 'users','action' => 'check-manager']); ?>';

  function getRemote(remote_url, method = "GET", type = "json", convertapi = true) {
    var resp = $.ajax({ type: method, dataType: type, url: remote_url, async: false }).responseText;
    if (convertapi) { return JSON.parse(resp); }
    return resp;
  }
  
  $(document).ready(function () {
    $("#userForm").validate({
      rules: {
        first_name: {
          required: true,
        },
        last_name: {
          required: true,
        },
        username: {
          required: true,
          email: true,
        },
        mobile: {
          required: true,
          number: true,
          minlength: 10,
          maxlength: 10,
        },
        group_id: {
          required: true,
        },
      },
      messages: {
        first_name: {
          required: "Please enter a first name",
        },
        last_name: {
          required: "Please enter a last name",
        },
        username: {
          required: "Please enter an email",
          email: "Please enter a valid email address",
        },
        mobile: {
          required: "Please enter a mobile number",
          number: "Please enter a valid mobile number",
        },
        group_id: {
          required: "Please select a user group",
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
          beforeSend: function () { $("#gif_loader").show(); },
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

  $(document).on("change", "#company-code-id", function () {
    var companycode = $(this).val();
    var resp = getRemote(baseurl + "api/api/master-by-company-code/" + companycode);
    var opt = "<option selected='' value=''>Please Select</option>";
    resp = resp["message"];
    $.each(resp["PurchasingOrganizations"], function (i, v) { opt += `<option value="` + v.id + `">` + v.name + `</option>`; })
    $("#purchasing-organization-id").html(opt);
  });

</script>