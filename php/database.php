<?php

class DatabaseConnection 
{ 
    private $DatabaseConnection; 
    
    public function __construct() 
    {
       $this->DatabaseConnection = new SQLite3('psncs_lab.db');
       $this->DatabaseConnection->exec( 'PRAGMA foreign_keys = ON;' );
       
       $this->CreateUsersTable();
       $this->CreateCommentsTable();
    }
    
    public function __destruct()
    {
        $this->DatabaseConnection->close();
    }
    
    public function InsertOrUpdateUser($Username, $Password, $Role)
    {
        try
        {
            $EncryptedPass = hash("sha256", $Password);
            
            $InsertStatement = $this->DatabaseConnection->prepare("INSERT OR REPLACE INTO users(username, password, role) values (:username, :password, :role)");
            $InsertStatement->bindParam(':username', $Username,      SQLITE3_TEXT);
            $InsertStatement->bindParam(':password', $EncryptedPass, SQLITE3_TEXT);
            $InsertStatement->bindParam(':role',     $Role,          SQLITE3_TEXT);
            $InsertStatement->execute();
            
            return $this->GetResponse("OK", "User successfully updated");
        }
        catch(Exception $ex)
        {
            return $this->GetResponse("ERROR", "Failed to insert user");
        }
    }
    
    public function InsertComment($Username, $Comment)
    {
       try
        {     
            $InsertStatement = $this->DatabaseConnection->prepare("INSERT INTO comments(text, username) values (:text, :username)");
            $InsertStatement->bindParam(':username', $Username,      SQLITE3_TEXT);
            $InsertStatement->bindParam(':text',     $Comment,       SQLITE3_TEXT);
            $InsertStatement->execute();
            
            return $this->GetResponse("OK", "Comment successfully added");
        }
        catch(Exception $ex)
        {
            return $this->GetResponse("ERROR", "Failed to add comment");
        } 
    }
    
    public function GetUser($Username, $Password, $Role)
    {
        try
        {
            $EncryptedPass = hash("sha256", $Password);
            $SelectStatement = $this->DatabaseConnection->prepare("SELECT * FROM users WHERE username=:username AND password=:password AND (role=:role OR role=\"admin\") ");
            $SelectStatement->bindParam(':username', $Username,      SQLITE3_TEXT);
            $SelectStatement->bindParam(':role',     $Role,          SQLITE3_TEXT);
            $SelectStatement->bindParam(':password', $EncryptedPass, SQLITE3_TEXT);
            $Objects = $SelectStatement->execute();
            $Data = array();
            
            while ($Row = $Objects->fetchArray(1)) 
            {
                array_push($Data, $Row);
            }
            
            return $this->GetResponse("OK", $Data);
        }
        catch(Exception $ex)
        {
            return $this->GetResponse("ERROR", "Failed to fetch all data");
        }
    }
    
    public function GetAllUsers()
    {
        return $this->GetAll("Users");
    }
    
    public function GetAllComments()
    {
        return $this->GetAll("Comments");
    }
    
    private function GetAll($TableName)
    { 
        try
        {
            $Objects = $this->DatabaseConnection->query('SELECT * FROM ' . $TableName);
            $Data = array();
            
            while ($Row = $Objects->fetchArray(1)) 
            {
                array_push($Data, $Row);
            }
            
            return $this->GetResponse("OK", $Data);
        }
        catch(Exception $ex)
        {
            return $this->GetResponse("ERROR", "Failed to fetch all data");
        }
    }
    
    private function CreateCommentsTable()
    {
        try
        {
            $SqlCreateTableComments = 'CREATE TABLE IF NOT EXISTS Comments (
                                        commentId INTEGER PRIMARY KEY AUTOINCREMENT,
                                        text      TEXT,
                                        username  TEXT,
                                        FOREIGN KEY(username) REFERENCES users(username)
                                    )';
                                    
            $this->DatabaseConnection->exec($SqlCreateTableComments);
            return $this->GetResponse("OK", "OK");
        }
        catch(Exception $ex)
        {
            return $this->GetResponse("ERROR", "Failed to create comments table");
        }       
    }
   
    private function CreateUsersTable()
    {
        try
        {
            $SqlCreateTableUsers = 'CREATE TABLE IF NOT EXISTS users (
                                        username TEXT PRIMARY KEY,
                                        password TEXT,
                                        role     TEXT CHECK(role IN (\'admin\', \'user\'))
                                    )';
                                    
            $this->DatabaseConnection->exec($SqlCreateTableUsers);
            
            // Insert some dummy data for test
            $this->InsertOrUpdateUser("Ana",      "parola", "admin");
            $this->InsertOrUpdateUser("Ion",      "1234",   "user" );
            $this->InsertOrUpdateUser("Gheorghe", "qwerty", "admin");
            $this->InsertOrUpdateUser("Razvan",   "a1s23d", "user" );
            
            return $this->GetResponse("OK", "OK");
        }
        catch(Exception $ex)
        {
            return $this->GetResponse("ERROR", "Failed to create users table");
        }
    }
    
    private function GetResponse($Response, $Payload)
    {
        $response = array(
            'response' => $Response,
            'payload'  => $Payload
        );
        return json_encode($response);
    }
} 
?>