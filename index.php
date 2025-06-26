<?php

require_once 'crud.php';

$crud = new CRUD();

$data = [
    'email' => 'ovofrito@email.com',
    'password' => password_hash('ovo', PASSWORD_DEFAULT),
];

// $crud->create('users', $data);

$rows = $crud->read('users');

foreach ($rows as $key => $row)
{
    echo "<div style='border: 2px solid; margin: 1rem; padding: 1rem;'>";
    echo "<div>{$row['email']}</div>";
    echo "<div>{$row['password']}</div>";
    echo "</div>";
}