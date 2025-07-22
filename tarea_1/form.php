<?php
    $userInput = $_POST["username"] ?? "";

    // Colocando data quemada como diosito manda
    $database_url = "localhost";
    $database_user = "root";
    $database_name = "dbtarea1";
    $table_name = "users";

    $connection = new mysqli($database_url, $database_user, "", $database_name);

    echo $connection->connect_error ? die("Could not connect to DB") : "";

    try {
        $statement =  $connection->prepare("INSERT INTO ? (user_name) VALUES (?)");
        $statement->bind_param("ss", $table_name, $userInput);
        $statement->execute();
        $statement->close();

    } catch (Exception $e) {
        echo "There was an error";
    }
?>
<script>
    window.location = "index.html";
</script>