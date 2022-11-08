<?php

namespace netvod\classes;

class User
{
    private $id;
    private $email;
    private $passwd;

    public function __construct(int $i, string $email, string $passwd)
    {
        $this->id = $i;
        $this->email = $email;
        $this->passwd = $passwd;
    }

    public function __set(string $at, mixed $val): void
    {
        if (property_exists($this, $at)) {
            $this->$at = $val;
        }else{
            throw new \Exception("Attribut $at inexistant");
        }
    }

    public function __get(string $at): mixed
    {
        if (property_exists($this, $at)) {
            return $this->$at;
        }else{
            throw new \Exception("Attribut $at inexistant");
        }
    }




    public function __toString()
    {
        return $this->email.' '.$this->passwd;
    }
}