<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create data</title>
    <style>
        form {
            margin-block: 10px;
        }
    </style>
</head>

<body>
    <h1>Create data</h1>
    <a href="../../">View data</a>
    <form action="" method="post">
        <input type="text" placeholder="name" name="name">
        <input type="email" placeholder="email" name="email">
        <input type="password" placeholder="password" name="password">
        <button type="submit">Create</button>
    </form>
    <?php
    ini_set('display_errors', 1);
    require_once '../../crud.php';

    $crud = new CRUD();

    $all_input_filled = true; // hypothetically

    foreach ($_POST as $value)
    {
        if (empty($value))
        {
            $all_input_filled = false;
            break;
        }
    }

    if ($all_input_filled)
    {
        $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);

        if ($crud->create('users', $_POST))
        {
            echo 'user created';
        }
    }

    ?>
</body>

</html>