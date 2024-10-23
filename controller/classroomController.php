<?php
include '../db.php';

// Create
if (isset($_POST['create'])) {
    $building = $_POST['building'];
    $room_number = $_POST['room_number'];
    $capacity = $_POST['capacity'];

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
    $building = $_POST['building'];
    $room_number = $_POST['room_number'];
    $capacity = $_POST['capacity'];

    $sql = "UPDATE classroom SET room_number='$room_number', capacity='$capacity' WHERE building='$building'";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: ../views/classroom.html?success=classroom updated successfully!");
        exit();
    } else {
        header("Location: ../views/classroom.html?error=" . urlencode($conn->error));
        exit();
    }
}

// Delete
if (isset($_GET['delete'])) {
    $building = $_GET['delete'];

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

// include '../db.php';

// // Create
// if (isset($_POST['create'])) {
//     $building = $_POST['building'];
//     $room_number = $_POST['room_number'];
//     $capacity = $_POST['capacity'];

//     $sql = "INSERT INTO classroom (building, room_number, capacity) 
//             VALUES ('$building', '$room_number', '$capacity')";
    
//     if ($conn->query($sql) === TRUE) {
//         header("Location: ../classroom/classroom.html?success=classroom added successfully!");
//         exit();
//     } else {
//         header("Location: ../classroom/classroom.html?error=" . urlencode($conn->error));
//         exit();
//     }
// }

// // Update
// if (isset($_POST['update'])) {
//     $building = $_POST['building'];
//     $room_number = $_POST['room_number'];
//     $capacity = $_POST['capacity'];
    

//     $sql = "UPDATE classroom SET room_number='$room_number', capacity='$capacity' WHERE building='$building'";
    
//     if ($conn->query($sql) === TRUE) {
//         header("Location: ../classroom/classroom.html?success=classroom updated successfully!");
//         exit();
//     } else {
//         header("Location: ../classroom/classroom.html?error=" . urlencode($conn->error));
//         exit();
//     }
// }

// // Delete
// if (isset($_GET['delete'])) {
//     $building = $_GET['delete'];

//     $sql = "DELETE FROM classroom WHERE building='$building'";
    
//     if ($conn->query($sql) === TRUE) {
//         header("Location: ../classroom/classroom.html?success=classroom deleted successfully!");
//         exit();
//     } else {
//         header("Location: ../classroom/classroom.html?error=" . urlencode($conn->error));
//         exit();
//     }
// }

// // Function to display classroom
// function displayClassroom() {
//     global $conn;
//     $sql = "SELECT * FROM classroom";
//     $result = $conn->query($sql);

//     if ($result->num_rows > 0) {
//         while($row = $result->fetch_assoc()) {
//             echo "<tr>";
//             echo "<td>" . $row["building"]. "</td>";
//             echo "<td>" . $row["room_number"] . "</td>";
//             echo "<td>" . $row["capacity"] . "</td>";
//             echo "<td>
//                     <form method='post' action='../controller/classroomController.php'>
//                         <input type='hidden' name='building' value='" . $row["building"] . "'>
//                         <input type='text' name='room_number' value='" . $row["room_number"] . "'>
//                         <input type='number' name='capacity' value='" . $row["capacity"] . "' step='0.01'>
//                         <input type='submit' name='update' value='Update'>
//                     </form>
//                     <a href='../controller/classroomController.php?delete=" . $row["building"] . "'>Delete</a>
//                 </td>";
//             echo "</tr>";
//         }
//     } else {
//         echo "<tr><td colspan='5'>No classroom found</td></tr>";
//     }
// }

// // Fetch department
// if (isset($_GET['action']) && $_GET['action'] == 'fetch') {
//     displayClassroom();
//     exit();
// }
?>
