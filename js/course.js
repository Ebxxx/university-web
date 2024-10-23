function editCourse(courseId, title, credits, deptName) {
    document.getElementById('edit_course_id').value = courseId;
    document.getElementById('edit_title').value = title;
    document.getElementById('edit_credits').value = credits;
    
    // Populate department options
    fetch('../controller/courseController.php?action=get_department_options')
        .then(response => response.text())
        .then(options => {
            document.getElementById('edit_dept_name').innerHTML = options;
            document.getElementById('edit_dept_name').value = deptName;
        });
    
    // Show the modal
    var editModal = new bootstrap.Modal(document.getElementById('editCourseModal'));
    editModal.show();
}

// Function to refresh the course list
function refreshCourseList() {
    fetch('../controller/courseController.php?action=fetch')
        .then(response => response.text())
        .then(data => {
            document.querySelector('#course-list tbody').innerHTML = data;
        });
}

// Call refreshCourseList when the page loads
document.addEventListener('DOMContentLoaded', refreshCourseList);

$(document).ready(function() {
    // Load Departments
    function loadCourse() {
        $.get('../controller/courseController.php?action=fetch', function(data) {
            $('#CourseTable').html(data);
        });
    }

    // Load building options
    function loadDepartment() {
        $.get('../controller/courseController.php?action=get_department_options', function(data) {
            $('#dept_name').append(data);
        });
    }

    loadCourse();
    loadDepartment();

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