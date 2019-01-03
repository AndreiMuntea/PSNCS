<?php

require 'database.php';
require 'session.php';

function AddComment()
{
    SetContext(null, null); // Actualize timestamp;
    
    if(empty($_POST) || !isset($_POST['comment']))
    {
        return;
    }
    
    $connection = new DatabaseConnection();
    $comment    = $_POST['comment'];
    $user       = GetContextUser();
    $response   = json_decode($connection->InsertComment($user, $comment), true);
}

AddComment();
header('Location: /second_page.php');

?>