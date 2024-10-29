<?php
include '../db.php';

// Create
if (isset($_POST['create'])) {
    $course_code = $_POST['course_code'];
    $title = $_POST['title'];
    $credits = $_POST['credits'];
    $dept_name = $_POST['dept_name'];

    $sql = "INSERT INTO course (course_code, title, credits, dept_name) 
            VALUES ('$course_code', '$title', '$credits', '$dept_name')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: ../views/course.html?success=course added successfully!");
        exit();
    } else {
        header("Location: ../views/course.html?error=" . urlencode($conn->error));
        exit();
    }
}

// Update
if (isset($_POST['update'])) {
    $course_code = $_POST['course_code'];
    $title = $_POST['title'];
    $credits = $_POST['credits'];
    $dept_name = $_POST['dept_name'];
    

    $sql = "UPDATE course SET title='$title', credits='$credits', dept_name='$dept_name' WHERE course_code='$course_code'";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: ../views/course.html?success=course updated successfully!");
        exit();
    } else {
        header("Location: ../views/course.html?error=" . urlencode($conn->error));
        exit();
    }
}

// Delete
if (isset($_GET['delete'])) {
    $course_code = $_GET['delete'];

    $sql = "DELETE FROM course WHERE course_code='$course_code'";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: ../views/course.html?success=course deleted successfully!");
        exit();
    } else {
        header("Location: ../views/course.html?error=" . urlencode($conn->error));
        exit();
    }
}

// Function to display courses
function displayCourse() {
    global $conn;
    $sql = "SELECT * FROM course";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["course_code"]. "</td>";
            echo "<td>" . $row["title"] . "</td>";
            echo "<td>" . $row["credits"] . "</td>";
            echo "<td>" . $row["dept_name"] . "</td>";
            echo "<td>
                    <button onclick='editCourse(\"" . $row["course_code"] . "\", \"" . $row["title"] . "\", \"" . $row["credits"] . "\", \"" . $row["dept_name"] . "\")' class='btn btn-primary btn-sm'>Edit</button>
                    <a href='../controller/courseController.php?delete=" . $row["course_code"] . "' class='btn btn-danger btn-sm'>Delete</a>
                </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No Course found</td></tr>";
    }
}

// Function to get department options
function getDepartmentOptions($selected = '') {
    global $conn;
    $sql = "SELECT DISTINCT dept_name FROM department";
    $result = $conn->query($sql);
    
    $options = "";
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $isSelected = ($row['dept_name'] == $selected) ? 'selected' : '';
            $options .= "<option value='" . $row['dept_name'] . "' $isSelected>" . $row['dept_name'] . "</option>";
        }
    }
    return $options;
}

// Fetch courses
if (isset($_GET['action']) && $_GET['action'] == 'fetch') {
    displayCourse();
    exit();
}

// Fetch department options
if (isset($_GET['action']) && $_GET['action'] == 'get_department_options') {
    echo getDepartmentOptions();
    exit();
}
?>