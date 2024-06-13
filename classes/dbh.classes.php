<?php

class Dbh {

    protected function connect() {
        try {
           $username = "root";
           $password = "";
           $dbh = new PDO('mysql:host=localhost:3307;dbname=mywakili2', $username, $password);
           return $dbh;
        } 
        catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }
    
}