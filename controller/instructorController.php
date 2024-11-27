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
    $apt_number = $_POST['apt_number'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $postal_code = $_POST['postal_code'];
    $date_of_birth = $_POST['date_of_birth'];
    $salary = $_POST['salary'];
    $dept_name = !empty($_POST['dept_name']) ? $_POST['dept_name'] : null;

    // Check if the instructor is at least 18 years old
    $dob = new DateTime($date_of_birth);
    $today = new DateTime();
    $age = $today->diff($dob)->y;

    if ($age < 18) {
        header("Location: ../views/instructor.html?error=" . urlencode("Instructor must be at least 18 years old above."));
        exit();
    }

         // Check if a student with the same ID already exists
        $check_sql = "SELECT * FROM instructor WHERE ID = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            // Instructor with the same name already exists
            header("Location: ../views/instructor.html?error=" . urlencode("Instructor ID already exists."));
            exit();
        }

    // If no duplicate found and age is valid, proceed with insertion
    $insert_sql = "INSERT INTO instructor (ID, first_name, middle_name, last_name, street_number, street_name, apt_number, city, province, postal_code, date_of_birth, salary, dept_name) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("sssssssssssds", $id, $first_name, $middle_name, $last_name, $street_number, $street_name, $apt_number, $city, $province, $postal_code, $date_of_birth, $salary, $dept_name);
    
    if ($insert_stmt->execute()) {
        header("Location: ../views/instructor.html?success=Instructor added successfully!");
        exit();
    } else {
        header("Location: ../views/instructor.html?error=" . urlencode($conn->error));
        exit();
    }
}

// Update
if (isset($_POST['update'])) {
    $id = ($_POST['id']);
    $first_name = ($_POST['first_name']);
    $middle_name = ($_POST['middle_name']);
    $last_name = ($_POST['last_name']);
    $salary = ($_POST['salary']);
    $dept_name = !empty($_POST['dept_name']) ? $_POST['dept_name'] : null;
    $street_number = ($_POST['street_number']);
    $street_name = ($_POST['street_name']);
    $apt_number = ($_POST['apt_number']);
    $city = ($_POST['city']);
    $province = ($_POST['province']);
    $postal_code = ($_POST['postal_code']);
    $date_of_birth = $_POST['date_of_birth'];
    // $fullName = $row["first_name"] . " " . $row["middle_name"] . " " . $row["last_name"];

    // Update the instructor
    $sql = "UPDATE instructor 
            SET first_name = '$first_name',
                middle_name = '$middle_name',
                last_name = '$last_name',
                salary = '$salary', 
                dept_name = '$dept_name', 
                street_number = '$street_number', 
                street_name = '$street_name', 
                apt_number = '$apt_number', 
                city = '$city', 
                province = '$province', 
                postal_code = '$postal_code', 
                date_of_birth = '$date_of_birth'
            WHERE ID = '$id'";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: ../views/instructor.html?success=Instructor updated successfully!");
        exit();
    } else {
        header("Location: ../views/instructor.html?error=" . urlencode($conn->error));
        exit();
    }
}

// Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $sql = "DELETE FROM instructor WHERE ID='$id'";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: ../views/instructor.html?success=Instructor deleted successfully!");
        exit();
    } else {
        header("Location: ../views/instructor.html?error=" . urlencode($conn->error));
        exit();
    }
}

// Function to display instructors
function displayInstructors() {
    global $conn;
    $sql = "SELECT * FROM instructor";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $fullName = $row["first_name"] . " " . $row["middle_name"] . " " . $row["last_name"];
            echo "<tr>";
            echo "<td>" . $row["ID"] . "</td>";
            echo "<td>" . $fullName . "</td>";
            echo "<td>" . $row["street_number"] . " ". $row["street_name"]. " ". $row["apt_number"]. ", " . $row["city"] . ", " . $row["province"] . " " . $row["postal_code"] . "</td>";
            echo "<td>" . $row["date_of_birth"] . "</td>";
            echo "<td>" . $row["salary"] . "</td>";
            echo "<td>" . $row["dept_name"] . "</td>";
            echo "<td>
                   <button onclick='editInstructor(" . $row["ID"] . ", \"" . addslashes($fullName) . "\", " . $row["salary"] . ", \"" . $row["dept_name"] . "\")' class='btn btn-primary btn-sm'>Edit</button>
                    <a href='../controller/instructorController.php?delete=" . $row["ID"] . "' class='btn btn-danger btn-sm'>Delete</a>
                </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No instructors found</td></tr>";
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

// Fetch instructors
if (isset($_GET['action']) && $_GET['action'] == 'fetch') {
    displayInstructors();
    exit();
}

// Fetch department options
if (isset($_GET['action']) && $_GET['action'] == 'get_department_options') {
    echo getDepartmentOptions();
    exit();
}
?>