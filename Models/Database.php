<?php

namespace app\Models;

use PDO;

class Database
{
    protected $host = 'localhost';
    protected $db_user = 'root';
    protected $db_password = 'admin';
    protected $db_name = 'cyptoexch';
    private $dbConnection;

    public function __construct()
    {
        try {
            $this->dbConnection = new PDO('mysql:host='.$this->host.';dbname='.$this->db_name,$this->db_user, $this->db_password);
        }catch (PDOException $exception){
            print "Err:".$exception->getMessage();
        }
    }

    public function runSQLQuery($sqlQuery, Array $stmtParams = null)
    {
        try {
            $statement = $this->dbConnection->prepare($sqlQuery);
            $statement->execute($stmtParams);
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $exception){
            print "Err:".$exception->getMessage();
        }
        return $result;
    }

    public function findUserWithEmail($email)
    {
        $result = $this -> runSQLQuery("SELECT * FROM użytkownicy WHERE BINARY adres_email = :email",array(
            'email' => $email
        ) );
        if (empty($result))
        {
            //TODO:zamykanie polaczenia
            return false;
        }else
        {
            $result = $result[0];
            return new User($result['id_użytkownika'], $result['imię'], $result['nazwisko'], $result['nr_telefonu'], $result['adres_email'], $result['haslo']);
        }
    }
}