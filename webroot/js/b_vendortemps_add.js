$('#OpenImgUpload').click(function () { $('#vendorCodeInput').trigger('click'); });

$('#vendorCodeInput').change(function () {
  var file = $(this).prop('files')[0].name;
  $("#filessnames").append(file);
});

var Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000
});

$.validator.setDefaults({
  submitHandler: function () {
    $.ajax({
      type: "POST",
      url: addurl,
      data: $("#addvendorform").serialize(),
      dataType: 'json',
      beforeSend: function () { $("#gif_loader").show(); $('#modal-sm').modal('hide'); },
      success: function (response) {
        if (response.status == 'success') {
          Toast.fire({
            icon: 'success',
            title: response.message
          });
          setTimeout(function () { window.location.reload(); }, 1000);
        } else {
          Toast.fire({
            icon: 'error',
            title: response.message
          });
        }
      },
      complete: function () { $("#gif_loader").hide(); }
    });
    return false;
  }
});

$('#id_addvendor').click(function () { if ($("#addvendorform").valid()) { $('#modal-sm').modal('show'); } });

$('#modal-sm').on('click', '.btn-success', function () {
  if ($("#addvendorform").valid()) { $('#modal-sm').modal('hide'); $('#addvendorform')[0].submit(); }
});

$('#addvendorform').validate({
  rules: {
    name: { required: true },
    mobile: { required: true, number: true },
    email: { required: true },
    payment_term_id: { required: true },
    purchasing_organization_id: { required: true },
    account_group_id: { required: true },
    schema_group_id: { required: true },
    company_code_id: { required: true },
    reconciliation_account_id: { required: true }
  },
  messages: {
    name: { required: "Please provide name" },
    mobile: { required: "Please provide mobile", number: "Please enter a valid number" },
    email: { required: "Please provide email" },
    payment_term_id: { required: "Please select payment_term" },
    purchasing_organization_id: { required: "Please select Purchasing Organization" },
    account_group_id: { required: "Please select Account Group" },
    schema_group_id: { required: "Please select Schema Group" },
    company_code_id: { required: "Please select Company Code" },
    reconciliation_account_id: { required: "Please select Reconciliation Account" }
  },

  errorElement: 'span',
  errorPlacement: function (error, element) {
    error.addClass('invalid-feedback');
    element.closest('.form-group').append(error);
  },
  highlight: function (element, errorClass, validClass) { $(element).addClass('is-invalid'); },
  unhighlight: function (element, errorClass, validClass) { $(element).removeClass('is-invalid'); },
});

function getRemote(remote_url, method = "GET", type = "json", convertapi = true) {
  var resp = $.ajax({ type: method, dataType: type, url: remote_url, async: false }).responseText;
  if (convertapi) { return JSON.parse(resp); }
  return resp;
}

$(document).on("change", "#company-code-id", function () {
  var companycode = $(this).val();
  var resp = getRemote(baseurl + "buyer/vendor-temps/master-by-company-code/" + companycode);
  var opt = "<option selected='' value=''>Please Select</option>";
  resp = resp["message"];
  $.each(resp["PurchasingOrganizations"], function (i, v) { opt += `<option value="` + v.id + `">` + v.name + `</option>`; })
  $("#purchasing-organization-id").html(opt);
  opt = "<option selected='' value='' >Please Select</option>";
  $.each(resp["ReconciliationAccounts"], function (id, v) { opt += `<option value="` + v.id + `">` + v.name + `</option>`; })
  $("#reconciliation-account-id").html(opt);
});
