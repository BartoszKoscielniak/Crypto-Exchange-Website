<?php

namespace app\Models;

class User
{
    private $user_id;
    private $name;
    private $surname;
    private $phone_number;
    private $email_address;
    private $password;

    /**
     * @param $user_id
     * @param $name
     * @param $surname
     * @param $phone_number
     * @param $emial_address
     * @param $password
     */
    public function __construct($user_id, $name, $surname, $phone_number, $email_address, $password)
    {
        $this->user_id = $user_id;
        $this->name = $name;
        $this->surname = $surname;
        $this->phone_number = $phone_number;
        $this->email_address = $email_address;
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param mixed $surname
     */
    public function setSurname($surname): void
    {
        $this->surname = $surname;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phone_number;
    }

    /**
     * @param mixed $phone_number
     */
    public function setPhoneNumber($phone_number): void
    {
        $this->phone_number = $phone_number;
    }

    /**
     * @return mixed
     */
    public function getEmialAddress()
    {
        return $this->emial_address;
    }

    /**
     * @param mixed $emial_address
     */
    public function setEmialAddress($emial_address): void
    {
        $this->emial_address = $emial_address;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }


}