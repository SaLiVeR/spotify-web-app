<?php

/**
 * @author MetalMichael
 * @copyright 2013
 */

class USER {
    public $ID;
    private $SessionID;
    private $Authenticated = false;
    
    function __construct() {
        global $Enc, $DB, $Cache;
        
        if(isset($_COOKIE['Session'])){
            $CookieInfo = explode('|<~>|', $Enc->decrypt($_COOKIE['Session']));
            if(!is_array($CookieInfo) || count($CookieInfo) !== 2) logout();
            $this->SessionID = $CookieInfo[0];
            $this->ID = $CookieInfo[1];
            
            if(USE_CACHE) {
                $Session = $Cache->get('SESSION_' . $this->SessionID);
            }
            if(!isset($Session) || $Session === false) {
                $DB->query("SELECT UserID FROM users_sessions WHERE SessionID = '" . db_string($this->SessionID) . "'");
                $Session = $DB->to_array();
            }
            if($Session && !empty($Session) && array_key_exists('UserID', $Session) && $Session['UserID'] == $this->ID) {
                $this->Authenticated = true;
            }
        }
    }
    
    function enforceLogin() {
        if(!$this->Authenticated) $this->logout;
    }
    
    function logout() {
        setcookie('Session', '', time()-3600, '/', false, true);
        header('Location: login.php');    
    }    
}

function enforceLogin() {
    global $DB, $Enc, $Cache;
    if(isset($_COOKIE['Session'])){
        $CookieInfo = explode('|<~>|', $Enc->decrypt($_COOKIE['Session']));
        if(!is_array($CookieInfo) || count($CookieInfo) !== 2) logout();
        $SessionID = $CookieInfo[0];
        $UserID = $CookieInfo[1];
        if(USE_CACHE) {
            $Session = $Cache->get('SESSION_' . $SessionID);
        }
        if(!isset($Session) || $Session === false) {
            $DB->query("SELECT UserID FROM users_sessions WHERE SessionID = '" . db_string($SessionID) . "'");
            $Session = $DB->to_array();
        }
        if(!$Session || empty($Session) || !array_key_exists('UserID', $Session) || $Session['UserID'] !== $UserID) {
            logout();
        }
    } else {
        header("Location: login.php");
    }
}

?>