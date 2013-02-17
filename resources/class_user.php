<?php

/**
 * @author MetalMichael
 * @copyright 2013
 */

class USER {
    public $ID;
    private $SessionID;
    private $Authenticated = false;
    
    public $Username;
    public $AuthKey;
    public $Joined;
    
    
    function __construct() {
        global $Enc, $DB, $Cache;
        
        if(isset($_COOKIE['Session'])){
            $CookieInfo = explode('|<~>|', $Enc->decrypt($_COOKIE['Session']));
            if(!is_array($CookieInfo) || count($CookieInfo) !== 2) $this->logout();
            $this->SessionID = $CookieInfo[0];
            $this->ID = $CookieInfo[1];
            
            if(!is_number($this->ID)) $this->logout();
            
            if(USE_CACHE) {
                $Session = $Cache->get('SESSION_' . $this->SessionID);
            }
            if(!isset($Session) || $Session === false) {
                $DB->query("SELECT UserID FROM users_sessions WHERE SessionID = '" . db_string($this->SessionID) . "'");
                $Session = $DB->to_array(false, MYSQLI_ASSOC);
            }
            if($Session && !empty($Session) && array_key_exists('UserID', $Session) && $Session['UserID'] == $this->ID) {
                $this->Authenticated = true;
                $this->loadInfo();
            }
        }
    }
    
    function loadInfo() {
        global $DB, $Cache;
        
        if(!isset($this->ID) || !$this->Authenticated) return;
        
        if(USE_CACHE) {
            $UserInfo = $Cache->get('USER_INFO_' . $this->ID);
        }
        if(!$UserInfo) {
            $DB->query("SELECT Username, Joined, AuthKey FROM users WHERE ID = " . $this->ID);
             $UserInfo = $DB->next_record(MYSQLI_NUM);
             $Cache->set('USER_INFO_' . $this->ID, $UserInfo);
        }
        list($this->Username, $this->Joined, $this->AuthKey) = $UserInfo;
    }
    
    function enforceLogin() {
        if(!$this->Authenticated) $this->logout;
    }
    
    function logout() {
        setcookie('Session', '', time()-3600, '/', false, true);
        header('Location: login.php');    
    }    
}

?>