$("#newrfqlist").DataTable({
    "responsive": false, "lengthChange": false, "autoWidth": false,
    "ordering": false,
    "searching": false,
    "paging": true

  });

  $("#respondedrfqlist").DataTable({
    "responsive": false, "lengthChange": false, "autoWidth": false,
    "ordering": false,
    "searching": false,
    "paging": true

  });

  $('#newrfqlist, #respondedrfqlist').on('click', 'tbody tr', function () {
    window.location = $(this).closest('tr').attr('redirect');
  });