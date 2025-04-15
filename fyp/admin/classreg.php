<?php
// Fetch registrations for admin page
$sql = "SELECT * FROM ClassRegistrations ORDER BY date, time";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>ID</th>
                <th>Class Name</th>
                <th>Date</th>
                <th>Time</th>
                <th>Client Name</th>
                <th>Contact</th>
                <th>Payment Method</th>
                <th>Status</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row['id'] . "</td>
                <td>" . $row['class_name'] . "</td>
                <td>" . $row['date'] . "</td>
                <td>" . $row['time'] . "</td>
                <td>" . $row['client_name'] . "</td>
                <td>" . $row['contact_number'] . "</td>
                <td>" . $row['payment_method'] . "</td>
                <td>" . $row['status'] . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No registrations found.";
}
?>
