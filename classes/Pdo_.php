<?php

use PHPMailer\src\Exception\Mail;

require './htmlpurifier-4.14.0/library/HTMLPurifier.auto.php';
include_once 'classes/Aes.php';
include_once 'classes/Mail.php';


class Pdo_
{
    private $db;
    private $purifier;

    public function __construct()
    {
        $config = HTMLPurifier_Config::createDefault();
        $this->purifier = new HTMLPurifier($config);
        try {
            $this->db = new PDO('mysql:host=localhost; dbname=php_proj', 'root', '');
        } catch (PDOException $e) {
            // add relevant code
            die();
        }
    }

    public function add_user($login, $email, $password)
    {

        //generate salt
        $salt = random_bytes(16);

        //$password = Aes::encrypt($password);
        //hash password with salt
        $password = hash('sha512', $password . $salt);

        $login = $this
            ->purifier
            ->purify($login);
        $email = $this
            ->purifier
            ->purify($email);
        try {
            $sql = "INSERT INTO `user`( `login`, `email`, `hash`, `salt`, `id_status`, `password_form`)
 VALUES (:login,:email,:hash,:salt,:id_status,:password_form)";
            // hash password
            // $password = hash('sha512', $password);
            $data = ['login' => $login, 'email' => $email, 'hash' => $password, 'salt' => $salt, 'id_status' => '1', 'password_form' => '1'];
            $this
                ->db
                ->prepare($sql)->execute($data);

            print 'Success';
        } catch (Exception $e) {
            //modify the code here
            print 'Exception' . $e->getMessage();
        }
    }

    public function change_pass($login, $password)
    {
        $salt = random_bytes(16);
        $password = hash('sha512', $password . $salt);
        $login = $this
            ->purifier
            ->purify($login);
        try {
            $sql = "UPDATE `user` 
                    SET `hash`=:hash, `salt`=:salt 
                    WHERE `login`=:login";
            $data = ['login' => $login, 'hash' => $password, 'salt' => $salt,];
            $this
                ->db
                ->prepare($sql)->execute($data);
            print 'Success';
        } catch (Exception $e) {
            //modify the code here
            print 'Exception' . $e->getMessage();
        }
    }

    public function log_user_in($login, $password): void
    {
        $login = $this->purifier->purify($login);
        try {
            $sql = "SELECT id,hash,login,salt FROM user WHERE login=:login";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['login' => $login]);
            $user_data = $stmt->fetch();
            $password = hash('sha512', $password . $user_data['salt']);

            if ($password == $user_data['hash']) {
                $_SESSION['login'] = $user_data['login'];
                echo 'login successfull<BR/>';
                echo 'You are logged in as: ' . $user_data['login'] . '<BR/>';
            } else {
                echo 'login FAILED<BR/>';
            }
        } catch (Exception $e) {
//modify the code here
            print 'Exception' . $e->getMessage();
        }
    }


    public function log_2F_step1($login, $password)
    {
        $login = $this
            ->purifier
            ->purify($login);
        try {
            $sql = "SELECT id,hash,login,salt,email FROM user WHERE
login=:login";
            $stmt = $this
                ->db
                ->prepare($sql);
            $stmt->execute(['login' => $login]);
            $user_data = $stmt->fetch();
            $password = hash('sha512', $password . $user_data['salt']);
            if ($password == $user_data['hash']) {
//generate and send OTP
                $otp = random_int(100000, 999999);
                $code_lifetime = date('Y-m-d H:i:s', time() + 300);
                try {
                    $sql = "UPDATE `user` SET `sms_code`=:code,
`code_timelife`=:lifetime WHERE login=:login";
                    $data = ['login' => $login, 'code' => $otp, 'lifetime' =>
                        $code_lifetime];
                    $this
                        ->db
                        ->prepare($sql)->execute($data);
                    $m = new Mail();
                    $m->send_email($user_data['email'], $otp);
                    $result = ['result' => 'success'];
                    return $result;
                } catch (Exception $e) {
                    print 'Exception' . $e->getMessage();
                }
            } else {
                echo 'login FAILED<BR/>';
                $result = ['result' => 'failed'];
                return $result;
            }
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
    }


    public function log_2F_step2($login, $code)
    {
        $login = $this
            ->purifier
            ->purify($login);
        $code = $this
            ->purifier
            ->purify($code);
        try {
            $sql = "SELECT id,login,sms_code,code_timelife
       FROM user WHERE login=:login";
            $stmt = $this
                ->db
                ->prepare($sql);
            $stmt->execute(['login' => $login]);
            $stmt = $this->db->prepare($sql);
            if ($stmt->execute(['login' => $login])) {
                $user_data = $stmt->fetch();
                if ($code == $user_data['sms_code'] && time() < strtotime($user_data['code_timelife'])) {
                    //login successfull
                    echo 'Login successfull<BR/>';
                    return true;
                } else {
                    echo 'login FAILED<BR/>';
                    return false;
                }
            } else {
                // handle error
            }
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
    }

    public function wyloguj()
    {
        $_SESSION['login'] = NULL;
        $_SESSION['logged'] = 'niezalogowany';
        $_SESSION['expire_time'] = 0;
    }

}

