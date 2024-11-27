function editStudent(id, firstName, middleName, lastName, streetNumber, streetName, city, province, postalCode, dob, totCredit, deptName) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_name').value = firstName;
    document.getElementById('edit_middle_name').value = middleName;
    document.getElementById('edit_last_name').value = lastName;
    document.getElementById('edit_street_number').value = streetNumber;
    document.getElementById('edit_street_name').value = streetName;
    document.getElementById('edit_city').value = city;
    document.getElementById('edit_province').value = province;
    document.getElementById('edit_postal_code').value = postalCode;
    document.getElementById('edit_date_of_birth').value = dob;
    document.getElementById('edit_tot_credit').value = totCredit;

    // Set department
    const deptSelect = document.getElementById('edit_dept_name');
    deptSelect.innerHTML = `<option value="${deptName}" selected>${deptName}</option>`;
    // Optionally, you can also re-fetch department options here if needed
    $('#editStudentModal').modal('show');
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