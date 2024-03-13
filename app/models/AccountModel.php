<?php
namespace App\models;
use App\core\Db\Db;

class AccountModel
{
    public function get_user_data($params) 
    {
        $db = new Db;
        $sql = "SELECT hash, ip, id, login, role FROM users WHERE id = :id";
        $userdata = $db->row($sql, $params);
        return $userdata;
    }

    public function insert_user_data($params)
    {
        $db = new Db();
        $sql = "INSERT INTO users (login, password, role) VALUES (:username, :password, :role)";
        $id = $db->insert($sql, $params);
        return $id;
    }

    public function get_signin_data($params)
    {
        $db = new Db();
        $sql = "SELECT id, password, role FROM users WHERE login = :username";
        $data = $db->row($sql, $params);
        return $data;
    }

    public function update_user_hash($params)
    {
        $db = new Db();
        $sql = "UPDATE users SET hash = :hash, ip = :ip WHERE id = :id";
        $db->query($sql, $params);
    }

}
