<?php

include '../db.php';

// Create
if (isset($_POST['create'])) {
    $building = $_POST['building'];
    $room_number = $_POST['room_number'];
    $capacity = $_POST['capacity'];

    // Check if a building with the same classroom already exists
    $sql = "SELECT * FROM classroom WHERE room_number = '$room_number'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        header("Location: ../views/classroom.html?error=" . urlencode("A building with the same classroom already exists."));
        exit();
    }

    $sql = "INSERT INTO classroom (building, room_number, capacity) 
            VALUES ('$building', '$room_number', '$capacity')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: ../views/classroom.html?success=classroom added successfully!");
        exit();
    } else {
        header("Location: ../views/classroom.html?error=" . urlencode($conn->error));
        exit();
    }
}

// Update
if (isset($_POST['update'])) {
    $old_building = mysqli_real_escape_string($conn, $_POST['building']);
    $new_building = mysqli_real_escape_string($conn, $_POST['new_building']);
    $new_room_number = mysqli_real_escape_string($conn, $_POST['room_number']);
    $capacity = mysqli_real_escape_string($conn, $_POST['capacity']);

    // Update the classroom with the new room number
    $sql = "UPDATE classroom 
            SET room_number = '$new_room_number', 
                capacity = '$capacity', 
                building = '$new_building' 
            WHERE building = '$old_building'";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: ../views/classroom.html?success=Classroom updated successfully!");
        exit();
    } else {
        header("Location: ../views/classroom.html?error=" . urlencode($conn->error));
        exit();
    }
}

// Delete
if (isset($_GET['delete'])) {
    $building = $_GET['delete'];
    // $building = $_GET['delete'];

    $sql = "DELETE FROM classroom WHERE building='$building'";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: ../views/classroom.html?success=classroom deleted successfully!");
        exit();
    } else {
        header("Location: ../views/classroom.html?error=" . urlencode($conn->error));
        exit();
    }
}

// Function to display classroom
function displayClassroom() {
    global $conn;
    $sql = "SELECT * FROM classroom";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["building"]. "</td>";
            echo "<td>" . $row["room_number"] . "</td>";
            echo "<td>" . $row["capacity"] . "</td>";
            echo "<td>
                    <button onclick='editClassroom(\"" . $row["building"] . "\", \"" . $row["room_number"] . "\", \"" . $row["capacity"] . "\")' class='btn btn-primary btn-sm'>Edit</button>
                    <a href='../controller/classroomController.php?delete=" . $row["building"] . "' class='btn btn-danger btn-sm'>Delete</a>
                </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No classroom found</td></tr>";
    }
}

// Fetch classroom
if (isset($_GET['action']) && $_GET['action'] == 'fetch') {
    displayClassroom();
    exit();
}
?>
