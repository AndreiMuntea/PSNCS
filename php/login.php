<?php

require 'database.php';
require 'crypto.php';

function SessionCleanup()
{
    session_start();
    session_unset();
    session_destroy();
}

function CheckCredentials()
{
    if(empty($_POST) || !isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['role']))
    {
        return false;
    }
    
    $connection = new DatabaseConnection();
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    $response = json_decode($connection->GetUser($username, $password, $role), true);
    if(strcmp($response['response'], 'OK') != 0 || count($response['payload']) != 1)
    {
        return false;
    }  
    
    session_start();
    
    $ctx = array(
        "user" => $username,
        "role" => $role,
        "expirationDate" => (time() + 300)
    );
    
    $crypto = Encrypt(json_encode($ctx));
    $_SESSION['context'] = $crypto[0];
    $_SESSION['tag'] = $crypto[1];
    
    return true;
}

SessionCleanup();

if(CheckCredentials())
{
    header('Location: second_page.php');
}
else
{
    echo("Please try again");
}
?>