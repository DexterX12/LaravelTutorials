<table>
    <tr>
        <th>Registration ID</th>
        <th>Name</th>    
    </tr>
    <?php
        $connection = new mysqli("localhost", "root", "", "dbtarea1");

        echo $connection->connect_error ? die("Could not connect to DB") : "";

        $sql = "SELECT * FROM users";
        $result = $connection->query($sql);

        if ($result->num_rows === 0) die("");

        while ($row = $result->fetch_assoc()) {
            echo
            "<tr>
                <td>{$row["user_id"]}</td>
                <td>{$row["user_name"]}</td>
            </tr>";
        }
    ?>
</table>