<?php
/**
 * Subject: PHP functions related to the SQL database
 * User: FrK
 * Date: 26/07/2018
 * Time: 12:58
 */

include_once("config/config.inc.php");

/* BCrypt --------------------------------------------------------------------------------- */
/***
 * fctBcryptHash : Return BCrypt hash of password
 * @param string $password
 * @return bool|string
 */
function fctBcryptHash($password)
{
    $bcryptOptions = [
        'cost' => CONST_BCRYPT_COST,
    ];

    return password_hash($password, PASSWORD_BCRYPT, $bcryptOptions); // BCRYPT Password hash
}

/* User ----------------------------------------------------------------------------------- */
/***
 * fctUserAdd : Add a User and return lastInsertId()
 * @param string $name
 * @param string $lastname
 * @param string $email
 * @param string $password
 * @return bool|string
 */
function fctUserAdd($name, $lastname, $email, $password)
{
    try {
        $pwdhash = fctBcryptHash($password);

        $db = new myPDO();

        $sql = $db->prepare("INSERT INTO user (usr_name, usr_lastname, usr_email, usr_pwdhash) VALUES (:name, :lastname, :email, :pwdhash)");
        $sql->bindParam(':name', $name, PDO::PARAM_STR);
        $sql->bindParam(':lastname', $lastname, PDO::PARAM_STR);
        $sql->bindParam(':email', $email, PDO::PARAM_STR);
        $sql->bindParam(':pwdhash', $pwdhash, PDO::PARAM_INT);
        $sql->execute();

        $lastId = $db->lastInsertId();
        $db = NULL; // Close connection
        return $lastId;

    } catch (PDOException $e) {
        if (strpos($e->getMessage(), "Duplicate entry") || (strpos($e->getMessage(), "1062"))) {
            $messg = "Email <em>{$email}</em> already registered."; //TODO
        } else {
            die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
        }
        return false;
    }
}

/***
 * fctUserEdit : Update a User and return rowCount()
 * @param int $id
 * @param string $name
 * @param string $lastname
 * @return bool|int
 */
function fctUserEdit($id, $name, $lastname)
{


    try {
        $db = new MyPDO();

        $sql = $db->prepare("UPDATE user set usr_name=:name, usr_lastname=:lastname WHERE usr_id=:id");
        $sql->bindParam(':name', $name, PDO::PARAM_STR);
        $sql->bindParam(':lastname', $lastname, PDO::PARAM_STR);
        $sql->bindParam(':id', $id, PDO::PARAM_INT);
        $sql->execute();

        $result = $sql->rowCount();

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $result;
}

/***
 * fctUserEditEmail : Update a User email and return rowCount()
 * @param int $id
 * @param string $email
 * @return bool|int
 */
function fctUserEditEmail($id, $email)
{
    try {
        $db = new myPDO();

        $sql = $db->prepare("UPDATE user set usr_email=:email WHERE usr_id=:id");
        $sql->bindParam(':email', $email, PDO::PARAM_STR);
        $sql->bindParam(':id', $id, PDO::PARAM_INT);
        $sql->execute();

        $result = $sql->rowCount();

    } catch (PDOException $e) {
        if (strpos($e->getMessage(), "Duplicate entry") || (strpos($e->getMessage(), "1062"))) {
            $error = -2;
        } else {
            die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
        }
        return $error;
    }
    $db = NULL; // Close connection
    return $result;
}

/***
 * fctUserEditPwd : Update a User Password and return rowCount()
 * @param $id
 * @param $pwd
 * @return int|array
 */
function fctUserEditPwd($id, $pwd)
{
    try {
        $pwdhash = fctBcryptHash($pwd);

        $db = new myPDO();

        $sql = $db->prepare("UPDATE user set usr_pwdhash=:pwdhash WHERE usr_id=:id");
        $sql->bindParam(':pwdhash', $pwdhash, PDO::PARAM_INT);
        $sql->bindParam(':id', $id, PDO::PARAM_INT);
        $sql->execute();

        $result = $sql->rowCount();

    } catch (PDOException $e) {
        return array(-1 => $e->getMessage());
    }
    $db = NULL; // Close connection
    return $result;
}

/***
 * fctUserIfAdmin : Return boolean value if a user is admin or not
 * @param int $id
 * @param string $pwd
 * @return bool|int
 */
function fctUserIfAdmin($id)
{
    try {
        $db = new myPDO();

        $sql = $db->prepare("SELECT COUNT(*) FROM user u JOIN membership m ON m.mem_usrid = u.usr_id JOIN ugroup g on g.grp_id = m.mem_grpid WHERE u.usr_id=:id AND g.grp_name= 'ADMIN'");
        $sql->bindParam(':pwdhash', $pwdhash, PDO::PARAM_INT);
        $sql->bindParam(':id', $id, PDO::PARAM_INT);
        $sql->execute();

        $result = $sql->rowCount();

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $result;
}

/***
 * fctUserChangeActive : Change the user active status return rowCount()
 * @param int $id
 * @param int $active
 * @return int
 */
function fctUserChangeActive($id, $active)
{
    try {
        $db = new myPDO();

        $sql = $db->prepare("UPDATE user set usr_active=:active WHERE usr_id=:id");
        $sql->bindParam(':id', $id, PDO::PARAM_INT);
        $sql->bindParam(':active', $active, PDO::PARAM_INT);
        $sql->execute();

        $result = $sql->rowCount();

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $result;
}

/***
 * fctUserList : Return user-s in an array
 * @param int $id (Default 0 = all users)
 * @return mixed
 */
function fctUserList($id = 0)
{
    try {
        $db = new myPDO();

        if ($id > 0) {
            $sql = $db->prepare("SELECT * FROM user WHERE usr_id=:id");
            $sql->bindParam(':id', $id, PDO::PARAM_INT);
        } else {
            $sql = $db->prepare("SELECT * FROM user ORDER BY usr_lastname, usr_name");
        }

        $sql->execute();
        $userList = $sql->fetchall(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $userList;
}

/***
 * fctUserLogin : Return True if login is found AND password matches
 * @param string $email
 * @param string $password
 * @return bool
 */
function fctUserLogin($email, $password)
{
    try {
        $db = new myPDO();
        $sql = $db->prepare("SELECT * FROM user u
                                        JOIN membership m ON m.mem_usrid = u.usr_id AND u.usr_email = :email
                                        LEFT JOIN ugroup g ON g.grp_id = m.mem_grpid AND g.grp_name = 'ADMIN'");
        $sql->bindParam(':email', $email, PDO::PARAM_STR);
        $sql->execute();

        $user = $sql->fetch(PDO::FETCH_ASSOC);

        if ($user) {

            if ($user['usr_active'] == 1) {
                if (password_verify($password, $user['usr_pwdhash'])) {
                    $_SESSION['user']['id'] = $user['usr_id'];
                    $_SESSION['user']['name'] = $user['usr_name'];
                    $_SESSION['user']['lastname'] = $user['usr_lastname'];
                    $_SESSION['user']['email'] = $user['usr_email'];
                    if ($user['grp_name'] == "ADMIN") {
                        $_SESSION['user']['admin'] = 1;
                    } else {
                        $_SESSION['user']['admin'] = 0;
                    }

                    $_SESSION['key'] = openssl_random_pseudo_bytes(12); //This is a key for the session!
                } else {
                    $_SESSION['Error']['Data'] = array("Type" => "danger", "Message" => "Email/password not recognized");
                    return false;
                }
            } else {
                $_SESSION['Error']['Data'] = array("Type" => "warning", "Message" => "Account is disabled contact admin");
                return false;
            }

        } else {
            $_SESSION['Error']['Data'] = array("Type" => "danger", "Message" => "Email/password not recognized");
            return false;
        }

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage()); //TODO
    }
    $db = NULL; // Close connection
    return true;
}

/***
 * fctUsersFromGroup: Return a list of users from a group
 * @param int $id
 * @return array
 */
function fctUsersFromGroup($id)
{
    try {
        $db = new myPDO();

        $sql = $db->prepare("SELECT * FROM user u JOIN membership m ON m.mem_usrid = u.usr_id JOIN ugroup g ON g.grp_id = m.mem_grpid WHERE grp_id=:id");
        $sql->bindParam(':id', $id, PDO::PARAM_INT);

        $sql->execute();
        $groupMembersList = $sql->fetchall(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $groupMembersList;
}

//TODO OBSOLETE
/***
 * fctUsersNotGroup: Return a list of users NOT in a group
 * @param int $id
 * @return array
 */
function fctUsersNotGroup($id)
{
    try {
        $db = new myPDO();

        $sql = $db->prepare("SELECT usr_id, usr_name, usr_lastname, usr_email, usr_active
                                        FROM user
                                        WHERE usr_id NOT IN(SELECT mem_usrid FROM membership WHERE mem_grpid = :id)
                                        ORDER BY usr_lastname;");
        $sql->bindParam(':id', $id, PDO::PARAM_INT);

        $sql->execute();
        $groupMembersList = $sql->fetchall(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $groupMembersList;
}

/* Usergroup ------------------------------------------------------------------------------ */
/***
 * fctGroupAdd : Add a group and return lastInsertId()
 * @param string $name
 * @param string $description
 * @return bool|string
 */
function fctGroupAdd($name, $description)
{
    try {
        $db = new myPDO();

        $sql = $db->prepare("INSERT INTO ugroup (grp_name, grp_description) VALUES (UPPER(:name),:description)");
        $sql->bindParam(':name', $name, PDO::PARAM_STR);
        $sql->bindParam(':description', $description, PDO::PARAM_STR);
        $sql->execute();

        $lastId = $db->lastInsertId();

    } catch (PDOException $e) {
        if (strpos($e->getMessage(), "Duplicate entry") || (strpos($e->getMessage(), "1062"))) {
            $error = -2;

        } else {
            die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
        }
        return $error;
    }
    $db = NULL; // Close connection
    return $lastId;
}

/***
 * fctGroupAdd : Edit a group and return rowCount()
 * @param string $name
 * @param string $description
 * @return bool|string$
 */
function fctGroupEdit($id, $name, $description)
{
    try {
        $db = new myPDO();

        $sql = $db->prepare("UPDATE ugroup SET grp_name = UPPER(:name), grp_description = :description WHERE grp_id = :id");
        $sql->bindParam(':id', $id, PDO::PARAM_INT);
        $sql->bindParam(':name', $name, PDO::PARAM_STR);
        $sql->bindParam(':description', $description, PDO::PARAM_STR);
        $sql->execute();

        $result = $sql->rowCount();

    } catch (PDOException $e) {
        if (strpos($e->getMessage(), "Duplicate entry") || (strpos($e->getMessage(), "1062"))) {
            $messg = "Duplicate entry."; //TODO
        } else {
            die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
        }
        return false;
    }
    $db = NULL; // Close connection
    return $result;
}

/***
 * fctGroupList: Return group-s in an array
 * @param int $id (default 0 = All)
 * @return array
 */
function fctGroupList($id = 0)
{
    try {
        $db = new myPDO();

        if ($id > 0) {
            $sql = $db->prepare("SELECT * FROM ugroup WHERE grp_id=:id");
            $sql->bindParam(':id', $id, PDO::PARAM_INT);
        } else {
            $sql = $db->prepare("SELECT * FROM ugroup ORDER BY grp_name");
        }

        $sql->execute();
        $groupList = $sql->fetchall(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $groupList;
}

/* Membership ----------------------------------------------------------------------------- */
/***
 * fctMembershipAdd : Add a user->group relation and return rowCount()
 * @param int $groupId
 * @param int $userId
 * @return bool|string
 */
function fctMembershipAdd($groupId, $userId)
{
    try {
        $db = new myPDO();

        $sql = $db->prepare("INSERT INTO membership (mem_grpid, mem_usrid) VALUES (:groupId, :userId)");
        $sql->bindParam(':groupId', $groupId, PDO::PARAM_INT);
        $sql->bindParam(':userId', $userId, PDO::PARAM_INT);
        $sql->execute();

        $result = $sql->rowCount();

    } catch (PDOException $e) {
        if (strpos($e->getMessage(), "Duplicate entry") || (strpos($e->getMessage(), "1062"))) {
            $messg = "Duplicate entry."; //TODO
        } else {
            die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
        }
        return false;
    }
    $db = NULL; // Close connection
    return $result;
}

/***
 * fctMembershipRemove : Remove a user->group relation and return rowCount()
 * @param $groupId
 * @param $userId
 * @return int|array
 */
function fctMembershipRemove($groupId, $userId)
{
    try {
        $db = new myPDO();

        $sql = $db->prepare("DELETE FROM membership WHERE mem_grpid = :groupId AND mem_usrid = :userId");
        $sql->bindParam(':groupId', $groupId, PDO::PARAM_INT);
        $sql->bindParam(':userId', $userId, PDO::PARAM_INT);
        $sql->execute();

        $result = $sql->rowCount();

    } catch (PDOException $e) {
        return array(-1 => $e->getMessage());
    }
    $db = NULL; // Close connection
    return $result;
}

/* Subject -------------------------------------------------------------------------------- */
/***
 * fctSubjectNew: Add a subject, subject->user relation-s and first message return subject lastInsertId()
 * Resource: https://stackoverflow.com/questions/24408434/pdo-transaction-syntax-with-try-catch
 *           http://php.net/manual/en/pdo.begintransaction.php
 * @param int $from
 * @param array $to
 * @param string $subject
 * @param string $content
 * @param null $date
 * @return bool|string
 */
function fctSubjectNew($from, $to, $subject, $content, $date = NULL)
{
    $db = new myPDO();

    if ($db->beginTransaction()) {
        try {

            //creation of subject, distribution and first message
            $sql = $db->prepare("INSERT INTO subject (sub_name) VALUES (:subject)");
            $sql->bindParam(':subject', $subject, PDO::PARAM_STR);
            $sql->execute();
            $lastSubjectId = $db->lastInsertId();

            array_unshift($to, $from);
            foreach ($to as $item) {

                $sql = $db->prepare("INSERT INTO distribution (dis_subid, dis_usrid) VALUES (:subId, :usrId)");
                $sql->bindParam(':subId', $lastSubjectId, PDO::PARAM_INT);
                $sql->bindParam(':usrId', $item, PDO::PARAM_INT);
                $sql->execute();

            }

            $sql = $db->prepare("INSERT INTO message (msg_from, msg_subid, msg_content, msg_date) VALUES (:from, :subId, :content, :date)");
            $sql->bindParam(':from', $from, PDO::PARAM_INT);
            $sql->bindParam(':subId', $lastSubjectId, PDO::PARAM_INT);
            $sql->bindParam(':content', $content, PDO::PARAM_STR);
            $sql->bindParam(':date', $date, PDO::PARAM_STR);
            $sql->execute();

            //commit when everything is fine
            $db->commit();

        } catch (PDOException $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            if (strpos($e->getMessage(), "Duplicate entry") || (strpos($e->getMessage(), "1062"))) {
                $messg = "Duplicate entry."; //TODO
            } else {
                die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
            }
            return false;
        }
        $db = NULL; // Close connection
        return $lastSubjectId;
    }
}

/***
 * fctSubjectList: Return a user subjects list in an array
 * @param int $id
 * @return array
 */
function fctSubjectList($id)
{
    try {
        $db = new myPDO();

        $sql = $db->prepare("SELECT DISTINCT (s.sub_id), s.sub_name, s.sub_lastdate, u.usr_id, u.usr_name, u.usr_lastname, usr_avatar
                                        FROM notification n
                                           JOIN message m ON m.msg_id = n.not_msgid
                                           JOIN subject s ON s.sub_id = m.msg_subid
                                           JOIN user u ON u.usr_id = s.sub_lastusrid
                                        WHERE n.not_usrid = :userId
                                        ORDER BY s.sub_lastdate DESC");
        $sql->bindParam(':userId', $id, PDO::PARAM_INT);
        $sql->execute();

        $settingsList = $sql->fetchall(PDO::FETCH_BOTH);

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $settingsList;
}

//TODO:OBSOLETE
/***
 * fctSubjectDelete: Delete a subject
 * @param $subId
 * @return int
 */
function fctSubjectDelete($subId)
{
    fctMessageSubDelete($subId);

    try {
        $db = new myPDO();

        $sql = $db->prepare("DELETE FROM subject WHERE sub_id =:subId");
        $sql->bindParam(':subId', $subId, PDO::PARAM_INT);
        $sql->execute();

        $result = $sql->rowCount();

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $result;
}

/* Distribution --------------------------------------------------------------------------- */
/***
 * fctDistributionAdd : Add a relation user->subject and return rowCount()
 * @param $subId
 * @param $usrId
 * @return bool|string
 */
function fctDistributionAdd($subId, $usrId)
{

    try {
        $db = new myPDO();

        $sql = $db->prepare("INSERT INTO distribution (dis_subid, dis_usrid) VALUES (:subId, :usrId)");
        $sql->bindParam(':subId', $subId, PDO::PARAM_INT);
        $sql->bindParam(':usrId', $usrId, PDO::PARAM_INT);
        $sql->execute();

        $result = $sql->rowCount();

    } catch (PDOException $e) {
        if (strpos($e->getMessage(), "Duplicate entry") || (strpos($e->getMessage(), "1062"))) {
            $messg = "Duplicate entry."; //TODO
        } else {
            die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
        }
        return false;
    }
    $db = NULL; // Close connection
    return $result;
}

/***
 * fctDistributionRemove : Remove a relation user->subject and return rowCount()
 * @param int $subId
 * @param int $usrId
 * @param int $notifications
 * @return int
 */
function fctDistributionRemove($subId, $usrId, $notifications)
{
    try {
        $db = new myPDO();

        $sql = $db->prepare("DELETE FROM distribution WHERE dis_subid=:subId AND dis_usrid=:usrId");
        $sql->bindParam(':subId', $subId, PDO::PARAM_INT);
        $sql->bindParam(':usrId', $usrId, PDO::PARAM_INT);
        $sql->execute();

        $result = $sql->rowCount();

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection

    if ($notifications > 0) {
        //TODO Remove notifications
    } else {
        return $result;
    };
}

/* Message -------------------------------------------------------------------------------- */
//TODO:OBSOLETE - done a check
/***
 * fctMessageAdd : Add a Message and return lastInsertId()
 * @param int $subId
 * @param int $from
 * @param string $content
 * @param $date
 * @return bool|string
 */
function fctMessageAdd($subId, $from, $content, $date = NULL)
{
    try {
        $db = new myPDO();

        $sql = $db->prepare("INSERT INTO message (msg_from, msg_subid, msg_content, msg_date) VALUES (:from, :subId, :content, :date)");
        $sql->bindParam(':from', $from, PDO::PARAM_INT);
        $sql->bindParam(':subId', $subId, PDO::PARAM_INT);
        $sql->bindParam(':content', $content, PDO::PARAM_STR);
        $sql->bindParam(':date', $date, PDO::PARAM_STR);
        $sql->execute();

        $lastId = $db->lastInsertId();

    } catch (PDOException $e) {
        if (strpos($e->getMessage(), "Duplicate entry") || (strpos($e->getMessage(), "1062"))) {
            $messg = "Duplicate entry."; //TODO
        } else {
            die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
        }
        return false;
    }
    $db = NULL; // Close connection
    return $lastId;
}

//TODO: OBSOLETE
/***
 * fctMessageSubDelete
 * @param int $subId
 * @return int
 */
function fctMessageSubDelete($subId)
{
    try {
        $db = new myPDO();

        $sql = $db->prepare("DELETE FROM message WHERE msg_subid = :subId");
        $sql->bindParam(':subId', $subId, PDO::PARAM_INT);
        $sql->execute();

        $result = $sql->rowCount();

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $result;
}

/***
 * fctMessageList : Return user's messages in an array
 * @param int $userId
 * @param int $subjectId
 * @param int $deleted
 * @return mixed
 */
function fctMessageList($userId, $subjectId, $deleted)
{
    try {
        $db = new myPDO();

        $query = "SELECT u.usr_id, u.usr_name, u.usr_lastname, usr_avatar, m.msg_date, m.msg_content, n.not_read
                    FROM message m
                       JOIN subject s on s.sub_id = m.msg_subid
                       JOIN distribution d on d.dis_subid = msg_subid
                       JOIN user u ON u.usr_id = m.msg_from
                       ";
        !$deleted ?: $query .= "LEFT OUTER ";
        $query .= "JOIN notification n ON n.not_msgid = m.msg_id
                    WHERE d.dis_usrid = :usrId AND s.sub_id = :subId AND (n.not_usrid=:usrId OR n.not_usrid IS NULL)";
        $sql = $db->prepare($query);

        $sql->bindParam(':usrId', $userId, PDO::PARAM_INT);
        $sql->bindParam(':subId', $subjectId, PDO::PARAM_INT);

        $sql->execute();
        $messageList = $sql->fetchall(PDO::FETCH_ASSOC);


    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $messageList;
}

/* -- Notifications ----------------------------------------------------------------------- */
/***
 * fctNotificationRemove: Remove a notification and return rowCount()
 * @param int $msgId
 * @param int $usrId
 * @return int
 */
function fctNotificationRemove($msgId, $usrId)
{
    try {
        $db = new myPDO();

        $sql = $db->prepare("DELETE FROM notification WHERE not_msgid=:msgId AND not_usrid=:usrId");
        $sql->bindParam(':msgId', $msgId, PDO::PARAM_INT);
        $sql->bindParam(':usrId', $usrId, PDO::PARAM_INT);
        $sql->execute();

        $result = $sql->rowCount();

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $result;
}

/* -- Admin ------------------------------------------------------------------------------- */

/* Settings ------------------------------------------------------------------------------- */
/***
 * fctSettingAdd : Add a Setting and return True or False
 * @param string $name
 * @param string $type
 * @param string $value
 * @return bool
 */
function fctSettingAdd($type, $name, $value)
{
    try {
        $db = new myPDO();

        $sql = $db->prepare("INSERT INTO settings (set_type, set_name, set_value) VALUES (:type, :name, :value)");
        $sql->bindParam(':type', $type, PDO::PARAM_STR);
        $sql->bindParam(':name', $name, PDO::PARAM_STR);
        $sql->bindParam(':value', $value, PDO::PARAM_STR);
        $sql->execute();

    } catch (PDOException $e) {
        if (strpos($e->getMessage(), "Duplicate entry") || (strpos($e->getMessage(), "1062"))) {
            $messg = "Setting <em>{$name}</em> already existing."; //TODO
        } else {
            die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
        }
        return false;
    }
    $db = NULL; // Close connection
    return true;
}

/***
 * fctSettingEdit : Edit a Setting and return rowCount()
 * @param string $name
 * @param string $value
 * @return int
 */
function fctSettingEdit($name, $value)
{
    try {
        $db = new myPDO();

        $sql = $db->prepare("UPDATE settings SET set_value = :value WHERE set_name = :name");
        $sql->bindParam(':name', $name, PDO::PARAM_STR);
        $sql->bindParam(':value', $value, PDO::PARAM_STR);
        $sql->execute();

        $result = $sql->rowCount();

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $result;
}

/***
 * fctSettingList : Return $type Settings in an array
 * @param string $type
 * @return mixed
 */
function fctSettingList($type)
{
    try {
        $db = new myPDO();

        $sql = $db->prepare("SELECT set_name, set_value FROM settings WHERE set_type=:type");
        $sql->bindParam(':type', $type, PDO::PARAM_STR);
        $sql->execute();

        $settingsList = $sql->fetchall(PDO::FETCH_BOTH);

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $settingsList;
}

/***
 * fctSettingItem: Return a $type->$name setting
 * @param string $type
 * @param string $name
 * @return mixed
 */
function fctSettingItem($type, $name)
{
    try {
        $db = new myPDO();

        $sql = $db->prepare("SELECT set_value FROM settings WHERE set_type=:type AND set_name=:name");
        $sql->bindParam(':type', $type, PDO::PARAM_STR);
        $sql->bindParam(':name', $name, PDO::PARAM_STR);
        $sql->execute();

        $settingsItem = $sql->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $settingsItem['set_value'];
}

//echo "user <br/>";
//echo fctUserAdd('Admin', 'Admin', 'admsin@localhost', 'admin') > 0 ? "OK <br/>" : "Pas OK <br/>";
//echo "login : <br/>";
//echo fctUserLogin('frank@localhost', '1234') . " <br/>" ? "OK <br/>" : "Pas OK <br/>";
//echo fctUserLogin('frank@localhost', '2234') . " <br/>" ? "OK <br/>" : "Pas OK <br/>";
//echo "group : <br/>";
//echo fctGroupAdd('Admin') . " <br/>";
//echo "member : <br/>";
//echo fctMembershipAdd(1, 1) . " <br/>";
//echo "subject : <br/>";
//echo fctSubjectAdd('Test subject2', 2) . " <br/>";
//echo "message : <br/>";
//echo fctMessageAdd(1, 2, 2, 'Message 2') . " <br/>";
//echo "settings : <br/>";
//echo fctSettingAdd('SITE_CONFIG', 'SITE_NAME', 'ContactModule') ? "OK <br/>" : "Pas OK <br/>";
//echo fctSettingAdd('SITE_CONFIG', 'SITE_ADDRESS', 'http://localhost/phpsql/') ? "OK <br/>" : "Pas OK <br/>";
//echo fctSettingAdd('SITE_CONFIG', 'SITE_WEBMASTER', 'Frank Théodoloz') ? "OK <br/>" : "Pas OK <br/>";
//echo fctSettingAdd('SITE_CONFIG', 'SITE_WEBMASTER_EMAIL', 'elv-frank.thdlz@eduge.ch') ? "OK <br/>" : "Pas OK <br/>";
//
//print_r(fctSettingList('SITE_CONFIG'));
//print_r(fctMessageList());


function clearDatabase()
{
    try {
        $db = new myPDO();

        $sql = $db->prepare("DELETE FROM settings");
        $sql->execute();
        $sql = $db->prepare("DELETE FROM message; ALTER TABLE message AUTO_INCREMENT=1");
        $sql->execute();
        $sql = $db->prepare("DELETE FROM subject; ALTER TABLE subject AUTO_INCREMENT=1");
        $sql->execute();
        $sql = $db->prepare("DELETE FROM membership");
        $sql->execute();
        $sql = $db->prepare("DELETE FROM ugroup; ALTER TABLE ugroup AUTO_INCREMENT=1");
        $sql->execute();
        $sql = $db->prepare("DELETE FROM user; ALTER TABLE user AUTO_INCREMENT=1");
        $sql->execute();

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
}

//TODO Update subject, distribution
/***
 * Populate database with demonstration data --> Called at the end of this file !!
 */
function fctInsertDemoData()
{
    clearDatabase();

    fctSettingAdd('SITE_CONFIG', 'SITE_NAME', 'MessagingModule');
    fctSettingAdd('SITE_CONFIG', 'COPYRIGHT', '&copy; 2018 &mdash; Frank Théodoloz');

    fctGroupAdd('ADMIN', 'Administrators');
    fctGroupAdd('USER', 'Users');

    fctUserAdd('Admin', 'Admin1', 'admin@localhost', 'admin');
    fctMembershipAdd(1, 1);
    fctUserChangeActive(1, 1);

    fctUserAdd('Super', 'Admin2', 'super@localhost', 'super');
    fctMembershipAdd(1, 2);
    //fctUserChangeActive(2,1);

    fctUserAdd('Frank', 'Théodoloz', 'fthe@bluewin.ch', '1234');
    fctMembershipAdd(1, 3);
    fctUserChangeActive(3, 1);

    fctUserAdd('User', '1', 'user1@localhost', 'user1');
    fctMembershipAdd(2, 4);
    fctUserChangeActive(4, 1);

    fctUserAdd('User', '2', 'user1@localhost', 'user1');
    fctMembershipAdd(2, 5);
    fctUserChangeActive(5, 1);

    fctUserAdd('User', '3', 'user1@localhost', 'user1');
    fctMembershipAdd(2, 6);
    fctUserChangeActive(6, 1);

    fctUserAdd('User', '4', 'user1@localhost', 'user1');
    fctMembershipAdd(2, 7);
    fctUserChangeActive(7, 1);

    fctUserAdd('User', '5', 'user1@localhost', 'user1');
    fctMembershipAdd(2, 8);
    //fctUserChangeActive(8, 1);

    fctMessageAdd(3, 1, 1, 'Message 1.1', "2018-08-01");
    fctMessageAdd(1, 3, 1, 'Message 1.2', "2018-08-02 13:22:12");

    fctSubjectAdd('Subject2', 1);
    fctMessageAdd(3, 1, 2, 'Message 2.1', "2018-08-03 13:22:12");
    fctMessageAdd(1, 3, 2, 'Message 2.2', "2018-08-04 13:22:12");

    fctMessageAdd(3, 1, 1, 'Message 1.3', "2018-08-09 13:22:12");

    fctMessageAdd(3, 1, 1, 'Message 1.4', NULL);
}

//fctInsertDemoData();
