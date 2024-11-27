<?php
include '../db.php';

// Create
if (isset($_POST['create'])) {
    $id = $_POST['id'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $street_number = $_POST['street_number'];
    $street_name = $_POST['street_name'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $postal_code = $_POST['postal_code'];
    $date_of_birth = $_POST['date_of_birth'];
    $tot_credit = $_POST['tot_credit'];
    $dept_name = !empty($_POST['dept_name']) ? $_POST['dept_name'] : null;

    
    // Check if the student is at least 13 years old
    $dob = new DateTime($date_of_birth);
    $today = new DateTime();
    $age = $today->diff($dob)->y;

    if ($age < 13) {
        header("Location: ../views/student.html?error=" . urlencode("Student must be at least 13 years old above."));
        exit();
    }

        // Check if a student with the same ID already exists
        $check_sql = "SELECT * FROM student WHERE ID = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
    
        if ($result->num_rows > 0) {
            // Student with the same ID already exists
            header("Location: ../views/student.html?error=" . urlencode("Student ID already exists."));
            exit();
        }
 
     // If no duplicate found and age is valid, proceed with insertion
     $insert_sql = "INSERT INTO student (ID, first_name, middle_name, last_name, street_number, street_name, city, province, postal_code, date_of_birth, tot_credit, dept_name) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
     
     $insert_stmt = $conn->prepare($insert_sql);
     $insert_stmt->bind_param("ssssssssssds", $id, $first_name, $middle_name, $last_name, $street_number, $street_name, $city, $province, $postal_code, $date_of_birth, $tot_credit, $dept_name);
     
     if ($insert_stmt->execute()) {
         header("Location: ../views/student.html?success=student added successfully!");
         exit();
     } else {
         header("Location: ../views/student.html?error=" . urlencode($conn->error));
         exit();
     }
 }

// Update
if (isset($_POST['update'])) {
    $id = ($_POST['id']);
    $first_name = ($_POST['first_name']);
    $middle_name = ($_POST['middle_name']);
    $last_name = ($_POST['last_name']);
    $tot_credit = ($_POST['tot_credit']);
    $dept_name = !empty($_POST['dept_name']) ? $_POST['dept_name'] : null;
    $street_number = ($_POST['street_number']);
    $street_name = ($_POST['street_name']);
    $city = ($_POST['city']);
    $province = ($_POST['province']);
    $postal_code = ($_POST['postal_code']);
    $date_of_birth = $_POST['date_of_birth'];

        // Update the instructor
    $sql = "UPDATE student 
        SET first_name = '$first_name',
            middle_name = '$middle_name',
            last_name = '$last_name',
            tot_credit = '$tot_credit', 
            dept_name = '$dept_name', 
            street_number = '$street_number', 
            street_name = '$street_name', 
            city = '$city', 
            province = '$province', 
            postal_code = '$postal_code', 
            date_of_birth = '$date_of_birth'
        WHERE ID = '$id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../views/student.html?success=student updated successfully!");
        exit();
    } else {
        header("Location: ../views/student.html?error=" . urlencode($conn->error));
        exit();
    }
}

// Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $sql = "DELETE FROM student WHERE ID='$id'";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: ../views/student.html?success=student deleted successfully!");
        exit();
    } else {
        header("Location: ../views/student.html?error=" . urlencode($conn->error));
        exit();
    }
}

// Function to display     displayStudents
function displayStudents() {
    global $conn;
    $sql = "SELECT * FROM student";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $fullName = $row["first_name"] . " " . $row["middle_name"] . " " . $row["last_name"];
            echo "<tr>";
            echo "<td>" . $row["ID"] . "</td>";
            echo "<td>" . $fullName . "</td>";
            echo "<td>" . $row["street_number"] . " " . $row["street_name"] . ", " . $row["city"] . ", " . $row["province"] . " " . $row["postal_code"] . "</td>";
            echo "<td>" . $row["date_of_birth"] . "</td>";
            echo "<td>" . $row["tot_credit"] . "</td>";
            echo "<td>" . $row["dept_name"] . "</td>";
            echo "<td>
                   <button onclick='editStudent(" . $row["ID"] . ", \"" . addslashes($fullName) . "\", " . $row["tot_credit"] . ", \"" . $row["dept_name"] . "\")' class='btn btn-primary btn-sm'>Edit</button>
                    <a href='../controller/studentController.php?delete=" . $row["ID"] . "' class='btn btn-danger btn-sm'>Delete</a>
                </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No students found</td></tr>";
    }
}

// Function to get department options
function getDepartmentOptions($selected = '') {
    global $conn;
    $sql = "SELECT dept_name FROM department";
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

// Fetch Students
if (isset($_GET['action']) && $_GET['action'] == 'fetch') {
    displayStudents();
    exit();
}

// Fetch department options
if (isset($_GET['action']) && $_GET['action'] == 'get_department_options') {
    echo getDepartmentOptions();
    exit();
}
?>