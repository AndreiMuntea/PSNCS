<?php

require 'crypto.php';

session_start();

function GetContextData($Field)
{
    $decryptedData = (array)json_decode(Decrypt($_SESSION['context'], $_SESSION['tag']));
    return $decryptedData[$Field];  
}

function GetExpirationDate()
{
    return GetContextData('expirationDate');
}

function GetContextUser()
{
    return GetContextData('user');
}

function GetContextRole()
{
    return GetContextData('role');
}

function CheckSession()
{
    $decryptedData = (array)json_decode(Decrypt($_SESSION['context'], $_SESSION['tag']));
    if(!isset($decryptedData['user']) || !isset($decryptedData['expirationDate']) || !isset($decryptedData['role']))
    {
        header('Location: /index.html');
        exit(0);
    }        
    
    if (time() > GetExpirationDate())
    {
        header('Location: /index.html'); 
        exit(0);
    }
}


CheckSession();

?>