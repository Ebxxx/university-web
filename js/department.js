
function editDepartment(deptName, building, budget) {
    document.getElementById('edit_dept_name').value = deptName;
    document.getElementById('edit_budget').value = budget;
    
    // Populate building options
    fetch('../controller/departmentController.php?action=get_building_option')
        .then(response => response.text())
        .then(options => {
            document.getElementById('edit_building').innerHTML = options;
            document.getElementById('edit_building').value = building;
        });
    
    // Show the modal
    var editModal = new bootstrap.Modal(document.getElementById('editDepartmentModal'));
    editModal.show();
}

// Function to refresh the department list
function refreshDepartmentList() {
    fetch('../controller/departmentController.php?action=fetch')
        .then(response => response.text())
        .then(data => {
            document.querySelector('#department-list tbody').innerHTML = data;
        });
}

// Call refreshDepartmentList when the page loads
document.addEventListener('DOMContentLoaded', refreshDepartmentList);   

$(document).ready(function() {
    // Load Departments
    function loadDepartment() {
        $.get('../controller/departmentController.php?action=fetch', function(data) {
            $('#DepartmentTable').html(data);
        });
    }

    // Load building options
    function loadBuilding() {
        $.get('../controller/departmentController.php?action=get_building_option', function(data) {
            $('#building').append(data);
        });
    }

    loadDepartment();
    loadBuilding();

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