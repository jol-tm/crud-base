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
    <a href="data/create/">Create data</a>
    <?php
    require_once 'crud.php';

    $crud = new CRUD();

    $rows = $crud->read('users');

    foreach ($rows as $row)
    {
        echo "<div class='box'>";
        echo "<div><strong>name: </strong>{$row['name']}</div>";
        echo "<div><strong>email: </strong>{$row['email']}</div>";
        echo "<div><strong>password: </strong>{$row['password']}</div>";
        echo "<a href='data/delete/?id={$row['id']}'>delete</a>";
        echo "</div>";
    }
    ?>
</body>

</html>