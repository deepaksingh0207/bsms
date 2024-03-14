// function showConfirmationModal() {
//   $('#modal-sm').modal('show');
// }

// function validateForm() {
//   var isValid = true;

//   $('.form-field').each(function () {
//       var input = $(this);
//       var errorContainer = input.next('.text-danger');
//       var errorMessage = '';

//       if (input.attr('type') === 'date' && !input.val()) {
//           errorMessage = 'Please enter a date.';
//           isValid = false;
//       } else if (input.val() === '') {
//           errorMessage = 'This field is required.';
//           isValid = false;
//       }

//       errorContainer.text(errorMessage).toggle(!!errorMessage);
//   });

//   return isValid;
// }
$(document).ready(function() {
  $('.custom-required').on('input', function() {
      validateField($(this));
  });

  $('#submit-btn').on('click', function() {
      $('.custom-required').each(function() {
          validateField($(this));
      });

      if ($('.custom-required .error-message:visible').length === 0) {
          console.log('Form submitted successfully!');
      } else {
          console.log('Form validation failed.');
      }
  });

  function validateField($field) {
      var value = $field.val();
      var $errorMessage = $field.closest('.form-group').find('.error-message');

      if (!value || value.trim() === '') {
          $errorMessage.show();
      } else {
          $errorMessage.hide();
      }
  }
});

function showConfirmationModal() {
  var formIsValid = validateForm();

  if (formIsValid) {
      $('#modal-sm').modal('show');
  } else {
      console.log('Please fill in all fields before submitting.');
  }
}

function validateForm() {
  var formIsValid = true;

  $('.invoice-details .custom-required').each(function() {
      var value = $(this).val();
      var $errorMessage = $(this).closest('.form-group').find('.error-message');

      if (!value || value.trim() === '') {
          $errorMessage.show();
          formIsValid = false;
      } else {
          $errorMessage.hide();
      }
  });

  return formIsValid;
}

function showConfirmationModal() {
  if (validateForm()) {
      $('#modal-sm').modal('show');
  }
}

// $('#OpenImgUpload').click(function () { $('#vendorCodeInput').trigger('click'); });

// $('#vendorCodeInput').change(function () {
//   var file = $(this).prop('files')[0].name;
//   $("#filessnames").append(file);
// });

$('#OpenImgUpload').click(function() {
  $('#vendorCodeInput').trigger('click');
});
$('#vendorCodeInput').change(function () {
  var file = $(this).prop('files')[0];
  var fileName = file ? file.name : '';

  $('#OpenImgUpload').text(fileName ?  fileName : 'Choose File');
});

setTimeout(function() {
  $('.success').fadeOut('slow');
}, 2000);

$('#sapvendorcodeform').on('submit', function() {
  $('#sapvendorcode').trigger('reset');
});


$('#sapvendorInputForm').on('submit', function() {
  $('#sapvendorInput').trigger('reset');
});
