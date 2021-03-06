<?php

/**
 * @author MetalMichael
 * @copyright 2013
 */


function db_string($String, $DisableWildcards = false) {
    global $DB;
    //Remove user input wildcards
    if ($DisableWildcards) {
        $String = str_replace(array('%', '_'), '', $String);
    }
    //Escape and return
    return $DB->escape_str($String);
}

function formatBytes($bytes, $precision = 2) { 
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 

    $bytes /= pow(1024, $pow);

    return round($bytes, $precision) . ' ' . $units[$pow]; 
} 

function get_time($Time) {
    $Hours = $Minutes = $Seconds = 0;
    while($Time > 60*60) {
        $Time -= 60*60;
        $Hours++;
    }
    while($Time > 60) {
        $Time -= 60;
        $Minutes++;
    }
    $Time = round($Time);
    
    if($Time < 10) $Time = '0' . $Time;
    
    if($Hours) {
        if($Minutes < 10) $Minutes = '0' + $Minutes;
        return $String = $Hours . ':' . $Minutes . ':' . $Time;
    } else {
        return $Minutes . ':' . $Time;
    }
}

function timeDiff($TimeStamp, $Levels=2, $HideAgo=false, $Span=true, $Lowercase=false) {
	/*
	Returns a <span> by default but can optionally return the raw time
	difference in text (e.g. "16 hours and 28 minutes", "1 day, 18 hours").
	*/
	if(!is_number($TimeStamp)) { // Assume that $TimeStamp is SQL timestamp
		if($TimeStamp == '0000-00-00 00:00:00') { return 'Never'; }
		$TimeStamp = strtotime($TimeStamp);
	}
	if($TimeStamp == 0) { return 'Never'; }
	$Time = time()-$TimeStamp;
	
	// If the time is negative, then it expires in the future.
	if($Time < 0) {
		$Time = -$Time;
		$HideAgo = true;
	}

	$Years=floor($Time/31556926); // seconds in one year
	$Remain = $Time - $Years*31556926;

	$Months = floor($Remain/2629744); // seconds in one month
	$Remain = $Remain - $Months*2629744;

	$Weeks = floor($Remain/604800); // seconds in one week
	$Remain = $Remain - $Weeks*604800;

	$Days = floor($Remain/86400); // seconds in one day
	$Remain = $Remain - $Days*86400;

	$Hours=floor($Remain/3600); // seconds in one hour
	$Remain = $Remain - $Hours*3600;

	$Minutes=floor($Remain/60); // seconds in one minute
	$Remain = $Remain - $Minutes*60;

	$Seconds=$Remain;

	$Return = '';

	if ($Years>0 && $Levels>0) {
		if ($Years>1) {
			$Return .= $Years.' years';
		} else {
			$Return .= $Years.' year';
		}
		$Levels--;
	}

	if ($Months>0 && $Levels>0) {
		if ($Return!='') {
			$Return.=', ';
		}
		if ($Months>1) {
			$Return.=$Months.' months';
		} else {
			$Return.=$Months.' month';
		}
		$Levels--;
	}

	if ($Weeks>0 && $Levels>0) {
		if ($Return!="") {
			$Return.=', ';
		}
		if ($Weeks>1) { 
			$Return.=$Weeks.' weeks';
		} else {
			$Return.=$Weeks.' week';
		}
		$Levels--;
	}

	if ($Days>0 && $Levels>0) {
		if ($Return!='') {
			$Return.=', ';
		}
		if ($Days>1) {
			$Return.=$Days.' days';
		} else {
			$Return.=$Days.' day';
		}
		$Levels--;
	}

	if ($Hours>0 && $Levels>0) {
		if ($Return!='') {
			$Return.=', ';
		}
		if ($Hours>1) {
			$Return.=$Hours.' hours';
		} else {
			$Return.=$Hours.' hour';
		}
		$Levels--;
	}

	if ($Minutes>0 && $Levels>0) {
		if ($Return!='') {
			$Return.=' and ';
		}
		if ($Minutes>1) {
			$Return.=$Minutes.' mins';
		} else {
			$Return.=$Minutes.' min';
		}
		$Levels--;
	}
	
	if($Return == '') {
		$Return = 'Just now';
	} elseif (!$HideAgo) {
		$Return .= ' ago';
	}

	if ($Lowercase) {
		$Return = strtolower($Return);
	}
	
	if ($Span) {
		return '<span class="time" title="'.date('M d Y, H:i', $TimeStamp).'">'.$Return.'</span>';
	} else {
		return $Return;
	}
}

function formatUsername($ID, $Username) {
    return $Username;
}

// This is preferable to htmlspecialchars because it doesn't screw up upon a double escape
function display_str($Str) {
    if($Str === NULL || $Str === FALSE || is_array($Str)) {
        return '';
    }
    if($Str != '' && !is_number($Str)) {
        $Str = make_utf8($Str);
        $Str = mb_convert_encoding($Str, "HTML-ENTITIES", "UTF-8");
        $Str = preg_replace("/&(?![A-Za-z]{0,4}\w{2,3};|#[0-9]{2,5};)/m", "&amp;", $Str);

        $Replace = array(
            "'", '"', "<", ">",
            '&#128;', '&#130;', '&#131;', '&#132;', '&#133;', '&#134;', '&#135;', '&#136;', '&#137;', '&#138;', '&#139;', '&#140;', '&#142;', '&#145;', '&#146;', '&#147;', '&#148;', '&#149;', '&#150;', '&#151;', '&#152;', '&#153;', '&#154;', '&#155;', '&#156;', '&#158;', '&#159;'
        );

        $With = array(
            '&#39;', '&quot;', '&lt;', '&gt;',
            '&#8364;', '&#8218;', '&#402;', '&#8222;', '&#8230;', '&#8224;', '&#8225;', '&#710;', '&#8240;', '&#352;', '&#8249;', '&#338;', '&#381;', '&#8216;', '&#8217;', '&#8220;', '&#8221;', '&#8226;', '&#8211;', '&#8212;', '&#732;', '&#8482;', '&#353;', '&#8250;', '&#339;', '&#382;', '&#376;'
        );

        $Str = str_replace($Replace, $With, $Str);
    }
    return $Str;
}

function is_number($Str) {
    $Return = true;
    if ($Str < 0) {
        $Return = false;
    }
    // We're converting input to a int, then string and comparing to original
    $Return = ($Str == strval(intval($Str)) ? true : false);
    return $Return;
}

function make_utf8($Str) {
    if ($Str != "") {
        if (is_utf8($Str)) {
            $Encoding = "UTF-8";
        }
        if (empty($Encoding)) {
            $Encoding = mb_detect_encoding($Str, 'UTF-8, ISO-8859-1');
        }
        if (empty($Encoding)) {
            $Encoding = "ISO-8859-1";
        }
        if ($Encoding == "UTF-8") {
            return $Str;
        }
        else {
            return @mb_convert_encoding($Str, "UTF-8", $Encoding);
        }
    }
}

function is_utf8($Str) {
    return preg_match('%^(?:
		[\x09\x0A\x0D\x20-\x7E]			 // ASCII
		| [\xC2-\xDF][\x80-\xBF]			// non-overlong 2-byte
		| \xE0[\xA0-\xBF][\x80-\xBF]		// excluding overlongs
		| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} // straight 3-byte
		| \xED[\x80-\x9F][\x80-\xBF]		// excluding surrogates
		| \xF0[\x90-\xBF][\x80-\xBF]{2}	 // planes 1-3
		| [\xF1-\xF3][\x80-\xBF]{3}		 // planes 4-15
		| \xF4[\x80-\x8F][\x80-\xBF]{2}	 // plane 16
		)*$%xs', $Str
    );
}

// Escape an entire array for output
// $Escape is either true, false, or a list of array keys to not escape
function display_array($Array, $Escape = array()) {
    foreach($Array as $Key => $Val) {
        if((!is_array($Escape) && $Escape == true) || !in_array($Key, $Escape)) {
            $Array[$Key] = display_str($Val);
        }
    }
    return $Array;
}

function error($E = '', $Ajax = false) {
    die($E);
}

//Remove colons from our IDs
function sanitizeID($ID) {
    return str_replace(':', '---', $ID);
}

function validID($ID) {
    return preg_match('/(spotify:(?:track:[a-zA-Z0-9]+))/', $ID); 
}

//HTTP Errors
function invalid() {
    header('HTTP/1.0 400 Bad Request');
    die('Invalid Argument');
}
function denied() {
    header('HTTP/1.0 403 Access Denied');
    die('403 Access Denied');
}

//Time in SQL Format
function sqltime() {
    return date('Y-m-d H:i:s');
}

//Setup Classes
require(RESOURCE_DIR . 'class_mysql.php');
$DB = new MYSQL;

//Connect to Memcached
if(USE_CACHE) {
    require(RESOURCE_DIR . 'class_cache.php');
    $Cache = new CACHE(MEMCACHED_ID);
}

require(RESOURCE_DIR . 'class_mcrypt.php');
$Enc = new MCRYPT;

//User Object
require(RESOURCE_DIR . 'class_user.php');
$User = new USER;
?>