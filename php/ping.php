<?php

require 'session.php';

function Ping()
{
    if(strcmp(GetContextRole(), "admin") != 0)
    {
        return "Ping command is available only for admin users";
    }        
    
    if(empty($_GET) || !isset($_GET['ip'])) 
    {
        return "Please provide an ip address";
    }
    
    $ip = $_GET['ip'];
    if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) 
    {
        return "Not a valid IP address";    
    } 
    
    return shell_exec('ping -n 1 ' . $ip);
}

echo Ping();
?>