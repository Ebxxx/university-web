function editStudent(id, name, totCredit, deptName) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_tot_credit').value = totCredit;
    
    // Populate department options
    fetch('../controller/studentController.php?action=get_department_options')
        .then(response => response.text())
        .then(options => {
            document.getElementById('edit_dept_name').innerHTML = options;
            document.getElementById('edit_dept_name').value = deptName;
        });
    
    // Show the modal
    var editModal = new bootstrap.Modal(document.getElementById('editStudentModal'));
    editModal.show();
}

// Function to refresh the student list
function refreshStudentList() {
    fetch('../controller/studentController.php?action=fetch')
        .then(response => response.text())
        .then(data => {
            document.querySelector('#students-list tbody').innerHTML = data;
        });
}

// Call refreshStudentList when the page loads
document.addEventListener('DOMContentLoaded', refreshStudentList);

$(document).ready(function() {
    // Load Students
    function loadStudents() {
        $.get('../controller/studentController.php?action=fetch', function(data) {
            $('#StudentsTable').html(data);
        });
    }

    // Load department options
    function loadDepartments() {
        $.get('../controller/studentController.php?action=get_department_options', function(data) {
            $('#dept_name').append(data);
        });
    }

    loadStudents();
    loadDepartments();

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