
$(document).ready(function() {
    // Load timeslots
    function loadTimeslot() {
        $.get('../controller/timeslotController.php?action=fetch', function(data) {
            $('#TimeslotTable').html(data);
        });
    }

    loadTimeslot();

    // Handle Edit button click
    $(document).on('click', '.edit-btn', function() {
        const timeslotId = $(this).data('id');
        const day = $(this).data('day');
        const startTime = $(this).data('start_time');
        const endTime = $(this).data('end_time');

        // Populate the edit modal fields with current timeslot data
        $('#edit_time_slot_id').val(timeslotId);
        $('#edit_day').val(day);
        $('#edit_start_time').val(startTime);
        $('#edit_end_time').val(endTime);

        // Show the edit modal
        $('#editTimeslotModal').modal('show');
    });

    // Display message if any
    const urlParams = new URLSearchParams(window.location.search);
    const message = urlParams.get('success') || urlParams.get('error');
    if (message) {
        const messageType = urlParams.get('success') ? 'alert-success' : 'alert-danger';
        $('#message').text(message).addClass(messageType).show();

        setTimeout(function() {
            $('#message').fadeOut('slow');
        }, 3000); // Hide after 3 seconds
    }
});