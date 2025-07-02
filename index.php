<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered data</title>
    <style>
        .box {
            margin-block: 10px;
            padding: 10px;
            border: 2px solid;
            word-wrap: break-word;
        }
    </style>
</head>

<body>
    <h1>Registered data</h1>
    <?php
    ini_set('display_errors', 1);
    require_once 'DataRepository.php';
    require_once 'DatabaseConnection.php';

    $connection = new DatabaseConnection();

    $data = new DataRepositoy($connection->start());

    if (isset($_GET['idToDelete']))
    {
        $data->delete('usuarios', ['id' => $_GET['idToDelete']]);
        header('Location: ./');
    }

    // $data->create('usuarios', ['nome' => 'test', 'email' => 'a@asd'], ['senha' => 123]);

    $rows = $data->read('usuarios');

    foreach ($rows as $row)
    {
        echo "<div class='box'>";
        echo "<div><strong>id: </strong>{$row['id']}</div>";
        echo "<div><strong>name: </strong>{$row['nome']}</div>";
        echo "<div><strong>email: </strong>{$row['email']}</div>";
        echo "<div><strong>senha: </strong>{$row['senha']}</div>";
        echo "<a href='./?idToDelete={$row['id']}'>deletar</a>";
        echo "</div>";
    }
    ?>
</body>

</html>