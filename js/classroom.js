function editClassroom(building, roomNumber, capacity) {
    document.getElementById('edit_building').value = building;
    document.getElementById('edit_room_number').value = roomNumber;
    document.getElementById('edit_capacity').value = capacity;
    
    // Show the modal
    var editModal = new bootstrap.Modal(document.getElementById('editClassroomModal'));
    editModal.show();
}

// Function to refresh the classroom list
function refreshClassroomList() {
    fetch('../controller/classroomController.php?action=fetch')
        .then(response => response.text())
        .then(data => {
            document.querySelector('#classroom-list tbody').innerHTML = data;
        });
}

// Call refreshClassroomList when the page loads
document.addEventListener('DOMContentLoaded', refreshClassroomList);

$(document).ready(function() {
    // Load Departments
    function loadClassroom() {
        $.get('../controller/classroomController.php?action=fetch', function(data) {
            $('#ClassroomTable').html(data);
        });
    }

    loadClassroom();

    // Display message if any
    const urlParams = new URLSearchParams(window.location.search);
    const message = urlParams.get('success') || urlParams.get('error');
    if (message) {
        const messageType = urlParams.get('success') ? 'alert-success' : 'alert-danger';
        $('#message').text(message).addClass(messageType).show();

        // Hide the message after 5 seconds
        setTimeout(function() {
            $('#message').fadeOut('slow');
        }, 3000); // 5000 milliseconds = 5 seconds
    }
});