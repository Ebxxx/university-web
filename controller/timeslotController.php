<?php
include '../db.php';

// Create
if (isset($_POST['create'])) {
    $day = $_POST['day'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $sql = "INSERT INTO time_slot (day, start_time, end_time) 
            VALUES ('$day', '$start_time', '$end_time')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: ../views/timeslot.html?success=timeslot added successfully!");
        exit();
    } else {
        header("Location: ../views/timeslot.html?error=" . urlencode($conn->error));
        exit();
    }
}

// Update
if (isset($_POST['update'])) {
    $time_slot_id = $_POST['time_slot_id'];
    $day = $_POST['day'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $sql = "UPDATE time_slot SET day = '$day', start_time = '$start_time', end_time = '$end_time' WHERE time_slot_id = '$time_slot_id'";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: ../views/timeslot.html?success=timeslot updated successfully!");
        exit();
    } else {
        header("Location: ../views/timeslot.html?error=" . urlencode($conn->error));
        exit();
    }
}

// Delete
if (isset($_GET['delete'])) {
    $time_slot_id = $_GET['delete'];

    $sql = "DELETE FROM time_slot WHERE time_slot_id = '$time_slot_id'";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: ../views/timeslot.html?success=timeslot deleted successfully!");
        exit();
    } else {
        header("Location: ../views/timeslot.html?error=" . urlencode($conn->error));
        exit();
    }
}

// Fetch timeslots for display
function displayTimeslot() {
    global $conn;
    $sql = "SELECT * FROM time_slot";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["time_slot_id"] . "</td>";
            echo "<td>" . $row["day"] . "</td>";
            echo "<td>" . $row["start_time"] . "</td>";
            echo "<td>" . $row["end_time"] . "</td>";
            echo "<td>
                    <button class='btn btn-sm btn-warning edit-btn' 
                        data-id='" . $row["time_slot_id"] . "' 
                        data-day='" . $row["day"] . "' 
                        data-start_time='" . $row["start_time"] . "' 
                        data-end_time='" . $row["end_time"] . "'>
                        Edit
                    </button>
                    <a href='../controller/timeslotController.php?delete=" . $row["time_slot_id"] . "' class='btn btn-sm btn-danger'>
                        Delete
                    </a>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No timeslots found</td></tr>";
    }
}

// Fetch action
if (isset($_GET['action']) && $_GET['action'] == 'fetch') {
    displayTimeslot();
    exit();
}
?>