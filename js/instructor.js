function editInstructor(id, name, salary, deptName) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_salary').value = salary;
    
    // Populate department options
    fetch('../controller/instructorController.php?action=get_department_options')
        .then(response => response.text())
        .then(options => {
            document.getElementById('edit_dept_name').innerHTML = options;
            document.getElementById('edit_dept_name').value = deptName;
        });
    
    // Show the modal
    var editModal = new bootstrap.Modal(document.getElementById('editInstructorModal'));
    editModal.show();
}

// Function to refresh the Instructor list
function refreshStudentList() {
    fetch('../controller/instructorController.php?action=fetch')
        .then(response => response.text())
        .then(data => {
            document.querySelector('#instructor-list tbody').innerHTML = data;
        });
}


function validateAge(dateOfBirth) {
    const dob = new Date(dateOfBirth);
    const today = new Date();
    let age = today.getFullYear() - dob.getFullYear();
    const monthDiff = today.getMonth() - dob.getMonth();
    
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
        age--;
    }
    
    return age >= 18;
}

// Call refreshStudentList when the page loads
document.addEventListener('DOMContentLoaded', refreshStudentList);
        $(document).ready(function() {
            // Load instructors
            function loadInstructors() {
                $.get('../controller/instructorController.php?action=fetch', function(data) {
                    $('#instructorsTable').html(data);
                });
            }

            // Load department options
            function loadDepartments() {
                $.get('../controller/instructorController.php?action=get_department_options', function(data) {
                    $('#dept_name').append(data);
                });
            }

            loadInstructors();
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