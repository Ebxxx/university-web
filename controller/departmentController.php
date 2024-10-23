<?php
include '../db.php';

// Create
if (isset($_POST['create'])) {
    $dept_name = $_POST['dept_name'];
    $building = $_POST['building'];
    $budget = $_POST['budget'];

    // Check if department already exists
    $check_sql = "SELECT dept_name FROM department WHERE dept_name = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $dept_name);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: ../views/department.html?error=" . urlencode("Department '$dept_name' already exists!"));
        exit();
    }

    // If department doesn't exist, proceed with insertion using prepared statement
    $insert_sql = "INSERT INTO department (dept_name, building, budget) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("ssd", $dept_name, $building, $budget);
    
    if ($insert_stmt->execute()) {
        header("Location: ../views/department.html?success=department added successfully!");
        exit();
    } else {
        header("Location: ../views/department.html?error=" . urlencode($conn->error));
        exit();
    }
}

// Update
if (isset($_POST['update'])) {
    $dept_name = $_POST['dept_name'];
    $building = $_POST['building'];
    $budget = $_POST['budget'];
    

    $sql = "UPDATE department SET building='$building', budget='$budget' WHERE dept_name='$dept_name'";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: ../views/department.html?success=department updated successfully!");
        exit();
    } else {
        header("Location: ../views/department.html?error=" . urlencode($conn->error));
        exit();
    }
}

// Delete
if (isset($_GET['delete'])) {
    $dept_name = $_GET['delete'];

    $sql = "DELETE FROM department WHERE dept_name='$dept_name'";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: ../views/department.html?success=department deleted successfully!");
        exit();
    } else {
        header("Location: ../views/department.html?error=" . urlencode($conn->error));
        exit();
    }
}

// Function to display departments
function displayDepartment() {
    global $conn;
    $sql = "SELECT * FROM department";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["dept_name"]. "</td>";
            echo "<td>" . $row["building"] . "</td>";
            echo "<td>" . $row["budget"] . "</td>";
            echo "<td>
                    <button onclick='editDepartment(\"" . $row["dept_name"] . "\", \"" . $row["building"] . "\", \"" . $row["budget"] . "\")' class='btn btn-primary btn-sm'>Edit</button>
                    <a href='../controller/departmentController.php?delete=" . $row["dept_name"] . "' class='btn btn-danger btn-sm'>Delete</a>
                </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No department found</td></tr>";
    }
}

// Function to get building options
function getBuildingOption($selected = '') {
    global $conn;
    $sql = "SELECT DISTINCT building FROM classroom";
    $result = $conn->query($sql);
    
    $options = "";
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $isSelected = ($row['building'] == $selected) ? 'selected' : '';
            $options .= "<option value='" . $row['building'] . "' $isSelected>" . $row['building'] . "</option>";
        }
    }
    return $options;
}

// Fetch department
if (isset($_GET['action']) && $_GET['action'] == 'fetch') {
    displayDepartment();
    exit();
}

// Fetch building options
if (isset($_GET['action']) && $_GET['action'] == 'get_building_option') {
    echo getBuildingOption();
    exit();
}
?>