<?php
include('DB.php');

class Login {
    public static function isLoggedIn() {
    
        if (isset($_COOKIE['SNID'])) {
            if (DB::query('SELECT userid FROM logintoken WHERE token=:token', array(':token'=>sha1($_COOKIE['SNID'])))) {
                    $userid = DB::query('SELECT userid FROM logintoken WHERE token=:token', array(':token'=>sha1($_COOKIE['SNID'])))[0]['userid'];
                    
                    if (isset($_COOKIE['SNID_'])){
                    return $userid; 
                    } else {
                        $cstrong = True;
                        $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong)) ;
                        DB::query('INSERT INTO logintoken VALUES (\'\', :token, :userid)', array(':token'=>sha1($token), ':userid'=>$userid));
                        DB::query('DELETE FROM logintoken WHERE token=:token', array(':token'=>sha1($_COOKIE['SNID'])));
    
                        setcookie("SNID", $token, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, TRUE);
                        setcookie("SNID_", '1', time() + 60 * 60 * 24 * 3, '/', NULL, NULL, TRUE);
    
                        return $userid;
                    }
            }
        }
    
        return false;
    }
}

class LoginAdmin {
    public static function isLoggedIn() {
    
        if (isset($_COOKIE['SNID'])) {
            if (DB::query('SELECT idadmin FROM logintokenadmin WHERE token=:token', array(':token'=>sha1($_COOKIE['SNID'])))) {
                    $id = DB::query('SELECT idadmin FROM logintokenadmin WHERE token=:token', array(':token'=>sha1($_COOKIE['SNID'])))[0]['idadmin'];
                    
                    if (isset($_COOKIE['SNID_'])){
                    return $id; 
                    } else {
                        $cstrong = True;
                        $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong)) ;
                        DB::query('INSERT INTO logintokenadmin VALUES (\'\', :token, :id)', array(':token'=>sha1($token), ':id'=>$id));
                        DB::query('DELETE FROM logintokenadmin WHERE token=:token', array(':token'=>sha1($_COOKIE['SNID'])));
    
                        setcookie("SNID", $token, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, TRUE);
                        setcookie("SNID_", '1', time() + 60 * 60 * 24 * 3, '/', NULL, NULL, TRUE);
    
                        return $id;
                    }
            }
        }
    
        return false;
    }
}
?>