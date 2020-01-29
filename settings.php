<?php

	//$userID = $_SERVER['REMOTE_USER'];
	$userID = $webAccessUser;

	$dbServer = 'localhost';
	$dbUser	= 'REDACTED FOR PRIVACY';
	$dbPass = 'REDACTED FOR PRIVACY';
	$dbName = 'REDACTED FOR PRIVACY';

	try {
		$conn = new PDO("mysql:host=$dbServer;dbname=$dbName;charset=utf8", $dbUser, $dbPass);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e) {
		echo'Failed to connect to MySQL: ' . $e->getMessage() . '<br>';
	}


	function getBannedUsers($conn) {
		$query = "SELECT * FROM banned";
		$bannedUsers = $conn->prepare($query);
		$bannedUsers->execute();
		return $bannedUsers;
	}


	function getSessions($conn) {
        $currentDayOfWeek = date('l', strtotime('now'));
        switch ($currentDayOfWeek) {
            case "Sunday":
                $query = "SELECT * FROM sessions WHERE sessionDateTime > NOW() - INTERVAL 30 MINUTE AND sessionDateTime < NOW() + INTERVAL 13 DAY ORDER BY sessionDateTime ASC";
                break;
            case "Monday":
                $query = "SELECT * FROM sessions WHERE sessionDateTime > NOW() - INTERVAL 30 MINUTE AND sessionDateTime < NOW() + INTERVAL 12 DAY ORDER BY sessionDateTime ASC";
                break;
            case "Tuesday":
                $query = "SELECT * FROM sessions WHERE sessionDateTime > NOW() - INTERVAL 30 MINUTE AND sessionDateTime < NOW() + INTERVAL 11 DAY ORDER BY sessionDateTime ASC";
                break;
            case "Wednesday":
                $query = "SELECT * FROM sessions WHERE sessionDateTime > NOW() - INTERVAL 30 MINUTE AND sessionDateTime < NOW() + INTERVAL 10 DAY ORDER BY sessionDateTime ASC";
                break;
            case "Thursday":
                $query = "SELECT * FROM sessions WHERE sessionDateTime > NOW() - INTERVAL 30 MINUTE AND sessionDateTime < NOW() + INTERVAL 9 DAY ORDER BY sessionDateTime ASC";
                break;
            case "Friday":
                $query = "SELECT * FROM sessions WHERE sessionDateTime > NOW() - INTERVAL 30 MINUTE AND sessionDateTime < NOW() + INTERVAL 8 DAY ORDER BY sessionDateTime ASC";
                break;
            case "Saturday":
                $query = "SELECT * FROM sessions WHERE sessionDateTime > NOW() - INTERVAL 30 MINUTE AND sessionDateTime < NOW() + INTERVAL 7 DAY ORDER BY sessionDateTime ASC";
                break;
        }
		$sessions = $conn->prepare($query);
		$sessions->execute();
		return $sessions;
	}


	function getAdminSessions($conn) {
        $currentDayOfWeek = date('l', strtotime('now'));
        $query = 'SELECT * FROM sessions WHERE sessionDateTime > NOW() - INTERVAL 1 HOUR ORDER BY sessionDateTime ASC';
		$sessions = $conn->prepare($query);
		$sessions->execute();
		return $sessions;
	}


    function clientHistory($conn, $clientID) {
        /* Array with three values: 1. hasEverRegistered, 2. alreadyRegisteredThisWeek, 3. alreadyRegisteredNextWeek */
        $clientHistoryArray = ["hasEverRegistered" => false,"alreadyRegisteredThisWeek" => false,"alreadyRegisteredNextWeek" => false];
        
        /* Query the session table for all rows with the clientID */
        $query = "SELECT * FROM sessions WHERE clientID = :clientID ORDER BY sessionDateTime ASC";
        $PDOResult = $conn->prepare($query);
		$PDOResult->execute(['clientID' => $clientID]);
        $historyResult = $PDOResult->fetchall(PDO::FETCH_ASSOC);
        
        /* Has the client registered before */
        if (count($historyResult) > 0) {
            
            $clientHistoryArray["hasEverRegistered"] = true;
            /* Has the client registered this week */
            /* Populate new array with only client's sessionDateTime */
            $clientAllDates = array();
            foreach ($historyResult as $a1) {
                array_push($clientAllDates, $a1['sessionDateTime']);
            }
            
            foreach ($clientAllDates as $date) {
                //echo $date;
                //echo "<br>";
                if (strtotime($date) > strtotime("-7 days", strtotime('next saturday')) and strtotime($date) < strtotime('next saturday')) {
                    $clientHistoryArray["alreadyRegisteredThisWeek"] = true;
                }
                /* Has the client registered for next week !NOT WORKING!: Seems to only evaluate the first if, and if successful, does not test anymore dates*/
                elseif (strtotime($date) > strtotime('next saturday') and strtotime($date) < strtotime("+7 days", strtotime('next saturday'))) {
                    $clientHistoryArray["alreadyRegisteredNextWeek"] = true;
                }
            }
        }

        //print_r($historyResult);
        //print_r($clientHistoryArray);
        return $clientHistoryArray;
    }


    function registerClient($conn, $clientID, $sessionID) {
        $query = "UPDATE sessions SET clientID = :clientID, clientName = :clientName WHERE sessionID = :sessionID";
        $registerResult = $conn->prepare($query);
		$registerResult->execute(['clientID' => $clientID, 'clientName' => ldapName($clientID), 'sessionID' => $sessionID]);
    }


    function cancelClient($conn, $sessionID) {
        $query = "UPDATE sessions SET clientID = NULL, clientName = NULL WHERE sessionID = :sessionID";
        $cancelResult = $conn->prepare($query);
		$cancelResult->execute(['sessionID' => $sessionID]);
        return $cancelResult;
    }


    function ldapName($userID) {

        $ldaphost = 'ldap.psu.edu';
        // Connect to LDAP and bind if connected
        $ldapconn = ldap_connect($ldaphost);
        if ($ldapconn) {
                $r = ldap_bind($ldapconn);
        } else {
                return "Name not found";
                exit();
        }

        // Search LDAP for the current user
        $sr = ldap_search($ldapconn, 'dc=psu,dc=edu', 'uid=' . $userID);
        $info = ldap_get_entries($ldapconn, $sr);


        // Check to see if there was exactly one entry for this User ID
        if ($info['count'] == '1') {
            return ($info[0]['givenname'][0] . " " . $info[0]['sn'][0]);
        } else {
            return "Name not found";
        }
    }
?>