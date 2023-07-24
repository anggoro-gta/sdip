<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}  class user extends CI_Model
{
    public $error = null;
    public function __construct()
    {
        parent::__construct();
    }
    public function verify($password, $hashedPassword)
    {
        return crypt($password, $hashedPassword);
    }
    public function authenticate($username, $password)
    {
        $username = $username;
        $password1=md5($password);
        $sql1 = "SELECT password FROM smc_user WHERE (user_name = '$username') AND is_aktif = 1 ";
        $dt = $this->db->query($sql1)->row();
        if ($dt) {
            $hash 	 = $dt->password;
            $password= $this->verify($password, $hash);
            $uname = explode("@", $username);
            $unameval = reset($uname);
            $sql = "SELECT smc_user.*, smc_unit.nama as nama_unit, smc_unit.kategori as kategori_unit, smc_unit.id_parent as id_parent_unit, parent_unit.nama as nama_parent_unit, (SELECT group_concat(keterangan) FROM smc_conf_role WHERE smc_conf_role.id_role IN (smc_user.id_role )) as `role` 
                FROM smc_user 
                    LEFT join smc_unit on smc_unit.id_unit=smc_user.id_unit_kerja 
                    LEFT join smc_unit as parent_unit on parent_unit.id_unit=smc_unit.id_parent 
                WHERE (user_name = '$username') AND password = '$password' AND is_aktif = 1 ";
            $result = $this->db->query($sql)->row();
            return $result;
        } else {
            return $dt;
        }
    }
    public function get_user_id()
    {
        $sql = "SELECT concat('" . date("Ym") . "',RIGHT(concat( '0000' , CAST(IFNULL(MAX(CAST(right(user_id,4) AS unsigned)), 0) + 1 AS unsigned)),4)) as `data` FROM smc_user WHERE left(user_id,6)='" . date("Ym") . "' ";
        $strresult = $this->db->query($sql)->row();
        return $strresult -> data;
    }
    public function enkrip_password($password)
    {
        return substr(md5($this->db->escape($password)), 3, 30);
    }
    public function update_user($data, $where)
    {
        $this->db->update("smc_user", $data, $where);
    }
    public function get_userid($data)
    {
        $sql = "SELECT smc_user.user_id FROM smc_user WHERE smc_user.user_name  = '$data'";
        $result = $this->db->query($sql)->row();
        return $result->user_id;
    }
    public function get_user($id_user)
    {
        $sql = "select * from smc_user where id_user='$id_user'";
        return $this->db->query($sql)->row();
    }
    public function cekToken($data)
    {
        $sql = "SELECT MID(MD5(tbl_forgot.user_id),9,15) user_id FROM tbl_forgot WHERE MID(SHA2(tbl_forgot.token_id, 256),12,47)  = '$data'";
        $result = $this->db->query($sql)->row();
        return $result;
    }
    public function oldPass($data)
    {
        $sql = "SELECT smc_user.password FROM smc_user WHERE MID(MD5(smc_user.user_id),9,15)  = '$data'";
        $result = $this->db->query($sql)->row();
        return $result->password;
    }
    public function save_password($data)
    {
        $sql = "UPDATE smc_user SET smc_user.password='".$data['password']."' WHERE MID(MD5(smc_user.user_id),9,15)='".$data['userid']."'";
        return $this->db->query($sql) or die(mysql_error());
    }
    public function generateHash($password)
    {
        if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
            $salt = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
            return crypt($password, $salt);
        }
    }
}
