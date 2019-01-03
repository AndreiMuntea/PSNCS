<?php

require 'crypto.php';

session_start();

function CheckSession()
{
    $decryptedData = (array)json_decode(Decrypt($_SESSION['context'], $_SESSION['tag']));
    if(!isset($decryptedData['user']) || !isset($decryptedData['timestamp']) || !isset($decryptedData['role']))
    {
        header('Location: /index.html');
    }        
}

function GetContextData($Field)
{
    $decryptedData = (array)json_decode(Decrypt($_SESSION['context'], $_SESSION['tag']));
    return $decryptedData[$Field];  
}

function GetContextTimestamp()
{
    return GetContextData('timestamp');
}

function GetContextUser()
{
    return GetContextData('user');
}

function GetContextRole()
{
    return GetContextData('role');
}

function SetContext($User, $Role)
{
    $decryptedData = (array)json_decode(Decrypt($_SESSION['context'], $_SESSION['tag']));
    
    if(!is_null($User))
    {
        $decryptedData['user'] = $User;
    }
    
    if(!is_null($Role))
    {
        $decryptedData['role'] = $Role;
    }
    
    $decryptedData['timestamp'] = time();
    
    $crypto = Encrypt(json_encode($decryptedData));
    $_SESSION['context'] = $crypto[0];
    $_SESSION['tag'] = $crypto[1];
}

CheckSession();
// 300 seconds timeout
if (time() - GetContextTimestamp() > 300)
{
    header('Location: /index.html');
}

?>