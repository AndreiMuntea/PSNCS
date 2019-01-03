<?php

require 'database.php';
require 'session.php';

function GetComments()
{
    $connection = new DatabaseConnection();
    $response = json_decode($connection->GetAllComments(), true);
    
    $output = "Privileges : " . GetContextRole() . "<br><br>";
    
    $output = $output . "<table> <tr> <th>User</th> <th>Comment</th> </tr>";
    
    if(strcmp($response['response'], 'OK') != 0 || count($response['payload']) == 0)
    {
        return $output . "</table>";
    }  
    
    foreach ($response['payload'] as $value) 
    {
        $output = $output . "<tr> <td>" . htmlspecialchars($value['username']) . "</td> <td> " . htmlspecialchars($value['text']) ."</td> </tr>";
    }
    
    return $output . "</table>";
}

echo GetComments();

?>