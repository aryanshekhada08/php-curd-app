<?php
    // echo "hello world";
     $conn=mysqli_connect("localhost","root","","student");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if (isset($_GET['id'])) {
        $no = intval($_GET['id']); // Ensure the ID is an integer
        $sql = "DELETE FROM student WHERE ID = $no";

        // Debugging: Print the SQL query
        echo "SQL Query: " . $sql . "<br>";

        $re = mysqli_query($conn, $sql);

        if ($re) {
            echo "Deleted successfully";
            // Redirect to another page after deletion
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Error deleting record: " . mysqli_error($conn);
        }
    }

    mysqli_close($conn);
?>
