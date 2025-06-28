<?php

require_once '../../crud.php';

$crud = new CRUD();

if ($crud->delete('users', ['id'=> $_GET['id']]))
{
    header('Location: ../../');
}
else 
{
    echo 'sorry, error deleting';
    echo "<a href='../..'>back to home</a>";
}