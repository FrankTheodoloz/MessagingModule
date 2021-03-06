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
            $error = -2;

        } else {
            die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
        }
        return $error;
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
 * fctUserList : Return user-s details in an array
 * Usage:
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
 * Usage: Login page on the frontpage...
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
 * fctUserAvatarChange: Update the user avatar and return rowCount()
 * @param $id
 * @param $avatar
 * @return int
 */
function fctUserAvatarChange($id, $avatar)
{
    try {
        $avatar == '' ? $avatar = null : '';
        $db = new myPDO();

        $sql = $db->prepare("UPDATE user SET usr_avatar = :avatar WHERE usr_id = :id");
        $sql->bindParam(':id', $id, PDO::PARAM_INT);
        $sql->bindParam(':avatar', $avatar, PDO::PARAM_STR);
        $sql->execute();

        $result = $sql->rowCount();

    } catch (PDOException $e) {

        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $result;
}

/* Groups --------------------------------------------------------------------------------- */
/***
 * fctGroupAdd : Add a group and return lastInsertId()
 * Usage: Administration-Groups
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
 * Usage: Administration-Groups
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
 * fctGroupDelete: Remove a Group and return rowCount()
 * Usage: Administration-Groups
 * @param $id
 * @return int
 */
function fctGroupDelete($id)
{
    try {
        $db = new myPDO();

        $sql = $db->prepare("DELETE FROM ugroup WHERE grp_id = :id");
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
 * fctGroupList: Return group-s in an array
 * Usage: Administration-Groups
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

/***
 * fctMembershipAdd : Add a user->group relation and return rowCount()
 * Usage: Administration-Groups
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
 * Usage: Administration-Groups
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

/***
 * fctMembershipRemove : Remove all user->group relation and return rowCount()
 * Usage: Administration-Groups
 * @param $groupId
 * @return int|array
 */
function fctMembershipGroupDelete($groupId)
{
    try {
        $db = new myPDO();

        $sql = $db->prepare("DELETE FROM membership WHERE mem_grpid = :groupId");
        $sql->bindParam(':groupId', $groupId, PDO::PARAM_INT);
        $sql->execute();

        $result = $sql->rowCount();

    } catch (PDOException $e) {
        return array(-1 => $e->getMessage());
    }
    $db = NULL; // Close connection
    return $result;
}

/***
 * fctUsersFromGroup: Return a list of users from a group
 * Usage: Administration-Groups
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

/***
 * fctUsersNotGroup: Return a list of users NOT in a group
 * Usage: Administration-Groups (add user to group)
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

/* Subject -------------------------------------------------------------------------------- */
/***
 * fctSubjectNew: Add a subject, subject->user relation-s and first message return subject lastInsertId()
 *
 * This is a Transaction !
 * TODO Look the transaction more in details ref https://stackoverflow.com/questions/10500217/pdo-transactions-function-calls
 *
 * Usage: In the chatbox --> trigger after insert on message
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
            $sql = $db->prepare("INSERT INTO subject (sub_name, sub_lastusrid) VALUES (:subject, :userId)");
            $sql->bindParam(':subject', $subject, PDO::PARAM_STR);
            $sql->bindParam(':userId', $from, PDO::PARAM_STR);
            $sql->execute();
            $lastSubjectId = $db->lastInsertId();

            array_unshift($to, $from); //add current user (sender)
            foreach ($to as $item) {

//                fctDistributionAdd($lastSubjectId, $item);
                $sql = $db->prepare("INSERT INTO distribution (dis_subid, dis_usrid) VALUES (:subId, :usrId)");
                $sql->bindParam(':subId', $lastSubjectId, PDO::PARAM_INT);
                $sql->bindParam(':usrId', $item, PDO::PARAM_INT);
                $sql->execute();
            }

//            fctMessageAdd($lastSubjectId, $from, $content, $date);
            $sql = $db->prepare("INSERT INTO message (msg_from, msg_subid, msg_content, msg_date) VALUES (:from, :subId, :content, :date)");
            $sql->bindParam(':from', $from, PDO::PARAM_INT);
            $sql->bindParam(':subId', $lastSubjectId, PDO::PARAM_INT);
            $sql->bindParam(':content', $content, PDO::PARAM_STR);
            $sql->bindParam(':date', $date, PDO::PARAM_STR);
            $sql->execute();

            $lastId = $db->lastInsertId();

            //commit when everything is fine
            $db->commit();

        } catch (PDOException $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
        }
        $db = NULL; // Close connection
        fctNotificationMessages($lastSubjectId, $lastId);
        return $lastSubjectId;
    }
}

/***
 * fctDistributionUsersIn: Return a list of users in subject.
 * Usage: List of avatar and Administration-Subjects (remove)
 * @param $id
 * @return array
 */
function fctDistributionUsersIn($id)
{
    try {
        $db = new myPDO();

        $sql = $db->prepare("SELECT * FROM distribution d JOIN user u ON u.usr_id = d.dis_usrid WHERE d.dis_subid=:id");
        $sql->bindParam(':id', $id, PDO::PARAM_INT);

        $sql->execute();
        $userList = $sql->fetchall(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $userList;
}

/***
 * fctDistributionUsersNotIn: Return a list of users not in subject.
 * Usage: Add a user in the chatbox
 * @param $id
 * @return array
 */
function fctDistributionUsersNotIn($id)
{
    try {
        $db = new myPDO();
        //TODO TO BE IMPROVED
        //SELECT u.* FROM user u LEFT JOIN distribution d ON d.dis_usrid=u.usr_id WHERE  d.dis_subid= 4 AND d.dis_usrid is null;
        $sql = $db->prepare("select * from user u where u.usr_id not in (select d.dis_usrid from distribution d where d.dis_subid =:id) ORDER BY usr_lastname ");
        $sql->bindParam(':id', $id, PDO::PARAM_INT);

        $sql->execute();
        $userList = $sql->fetchall(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $userList;
}

/***
 * fctSubjectEdit: Change a Subject name and return rowCount()
 * Usage: Administration-Subjects
 * @param $id
 * @param $name
 * @return int
 */
function fctSubjectEdit($id, $name)
{
    try {
        $db = new myPDO();

        $query = "UPDATE subject SET sub_name=:subName WHERE sub_id=:subId";
        $sql = $db->prepare($query);
        $sql->bindParam(':subId', $id, PDO::PARAM_INT);
        $sql->bindParam(':subName', $name, PDO::PARAM_STR);
        $sql->execute();

        $result = $sql->rowCount();

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $result;
}

/***
 * fctSubjectEdit: Update a Subject date and return rowCount()
 * Usage: Administration-Subjects
 * @param $id
 * @param $name
 * @return int
 */
function fctSubjectUpdate($subId, $userId)
{
    try {
        $db = new myPDO();

        $query = "UPDATE subject SET sub_lastdate=NOW(), sub_lastusrid=:userId WHERE sub_id=:subId";
        $sql = $db->prepare($query);
        $sql->bindParam(':subId', $subId, PDO::PARAM_INT);
        $sql->bindParam(':userId', $userId, PDO::PARAM_STR);
        $sql->execute();

        $result = $sql->rowCount();

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $result;
}


/***
 * fctSubjectList: Return (User's) Subject list in an array
 * Usage: Subject list in chatbox and Administration-Subjects
 * @param int $id
 * @return array
 */
function fctUserSubjectList($id)
{
    try {
        $db = new myPDO();

        $query = "SELECT s.sub_id, s.sub_name, s.sub_lastdate, u.usr_id, u.usr_name, u.usr_lastname, u.usr_avatar, count(n.not_read) as count
                  FROM subject s
                    JOIN message m ON m.msg_subid = s.sub_id
                    JOIN distribution d ON (d.dis_subid = s.sub_id)
                    JOIN user u ON u.usr_id = s.sub_lastusrid
                    LEFT JOIN notification n ON (n.not_msgid = m.msg_id AND (n.not_usrid = :usrId OR n.not_usrid is NULL )) ";
        $id < 0 ?: $query .= "WHERE d.dis_usrid = :usrId ";
        $query .= "GROUP BY s.sub_id
                  ORDER BY s.sub_lastdate DESC";

        $sql = $db->prepare($query);
        $sql->bindParam(':usrId', $id, PDO::PARAM_INT);
        $sql->execute();

        $subjectList = $sql->fetchall(PDO::FETCH_BOTH);

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $subjectList;
}

/***
 * fctSubjectDetails: Return details about a subject in an array
 * Usage: Administration-Subjects
 * @param int $id
 * @return array
 */
function fctSubjectDetails($id)
{
    try {
        $db = new myPDO();

        $query = "SELECT s.*, count(distinct(m.msg_id)) as nbMessages, count(distinct(d.dis_usrid)) as nbMembers
                  FROM subject s
                    JOIN message m ON m.msg_subid = s.sub_id
                    JOIN distribution d on d.dis_subid=s.sub_id
                  WHERE sub_id=:subId";
        $sql = $db->prepare($query);
        $sql->bindParam(':subId', $id, PDO::PARAM_INT);
        $sql->execute();

        $subjectList = $sql->fetchall(PDO::FETCH_BOTH);

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $subjectList;
}

/* Distribution --------------------------------------------------------------------------- */
/***
 * fctDistributionAdd : Add a relation user->subject and return rowCount()
 * @param int $subId
 * @param array $to
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
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
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
function fctDistributionRemove($subId, $usrId)
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
    return $result;
}

/* Message -------------------------------------------------------------------------------- */
/***
 * fctMessageAdd : Add a Message and return lastInsertId()
 * Usage: In the chatbox --> Trigger after insert on Message
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
        fctSubjectUpdate($subId, $from);

    } catch (PDOException $e) {

        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    fctNotificationMessages($subId, $lastId);
    return $lastId;
}

/***
 * fctMessageList : Return user's messages in an array
 * --> Calls fctNotificationRead before displaying messages
 * @param int $userId
 * @param int $subjectId
 * @return mixed
 */
function fctMessageList($userId, $subjectId)
{
    try {
        $db = new myPDO();

        $query = "SELECT u.usr_id, u.usr_name, u.usr_lastname, usr_avatar, m.msg_id, m.msg_date, m.msg_content, n.not_read
                  FROM message m
                       JOIN subject s on s.sub_id = m.msg_subid
                       JOIN distribution d on d.dis_subid = m.msg_subid
                       JOIN user u ON u.usr_id = m.msg_from
                       left
                       JOIN notification n ON n.not_msgid = m.msg_id
                       AND( n.not_usrid = :usrId OR n.not_usrid IS NULL)
                  WHERE d.dis_usrid = :usrId
                   AND s.sub_id = :subId";

        $sql = $db->prepare($query);

        $sql->bindParam(':usrId', $userId, PDO::PARAM_INT);
        $sql->bindParam(':subId', $subjectId, PDO::PARAM_INT);

        $sql->execute();
        $messageList = $sql->fetchAll(PDO::FETCH_ASSOC);

        foreach ($messageList as $messageItem) {
            //fctNotificationRead($messageItem['msg_id'], $userId);
            $sql = $db->prepare("UPDATE notification SET not_read = 1 WHERE not_msgid=:msgId AND not_usrid=:usrId");
            $sql->bindParam(':msgId', $messageItem['msg_id'], PDO::PARAM_INT);
            $sql->bindParam(':usrId', $userId, PDO::PARAM_INT);
            $sql->execute();
        }

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $messageList;
}

/* -- Notifications ----------------------------------------------------------------------- */
/***
 * fctNotificationRemove: Remove a notification and return rowCount()
 * Usage: Link displayed on each message in the chatbox
 * @param int $usrId
 * @param int $msgId
 * @return int
 */
function fctNotificationRemove($usrId, $msgId)
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

/***
 * fctNotificationRead: Mark User Subject Notification as read and return rowCount()
 * Usage: Called when Subject Messages are retrieved (fctMessageList)
 * @param int $msgId
 * @param int $usrId
 * @return int
 */
function fctNotificationRead($msgId, $usrId)
{
    try {
        $db = new myPDO();

        $sql = $db->prepare("UPDATE notification SET not_read = 1 WHERE not_msgid=:msgId AND not_usrid=:usrId");
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

/***
 * fctNotificationCount: Return number of unread notifications
 * @param int $usrId
 * @param $int $subId
 * @return array
 */
function fctNotificationCount($usrId, $subId, $showUnRead)
{
    try {
        $db = new myPDO();

        $query = "SELECT COUNT(*) AS count FROM notification n
                    JOIN message m ON msg_id = n.not_msgid
                    WHERE m.msg_subid = :subId ";
        $usrId < 0 ? '' : $query .= "AND n.not_usrid = :usrId";
        $showUnRead == 0 ? '' : $query .= " AND n.not_read = 0";

        $sql = $db->prepare($query);
        $sql->bindParam(':subId', $subId, PDO::PARAM_INT);
        $usrId < 0 ?: $sql->bindParam(':usrId', $usrId, PDO::PARAM_INT);
        $sql->execute();

        $result = $sql->fetch();

        if ($result['count'] > 0) {
            $count = $result['count'];
        } else {
            $count = 0;
        }
    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $count;
}

/***
 * fctNotificationUserRemove: Calls SQL procedure P_NotifUserRemove --NOT SUPPORTED BY INFOMANIAK
 * @param $usrId
 * @param $subId
 * @return int
 */
function fctNotificationUserRemove($usrId, $subId)
{
    try {
        $db = new myPDO();

        $sql = $db->prepare("call P_NotifUserRemove(:usrId,:subId,@count)");
        $sql->bindParam(':subId', $subId, PDO::PARAM_INT);
        $sql->bindParam(':usrId', $usrId, PDO::PARAM_INT);
        $sql->execute();
        $sql = $db->query("SELECT @count");
        $sql->execute();

        $result = $sql->fetchColumn();

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $result;
}

/***
 * fctNotificationUserAdd: Calls SQL procedure P_NotifUserAdd --NOT SUPPORTED BY INFOMANIAK
 * @param $usrId
 * @param $subId
 * @return int
 */
function fctNotificationUserAdd($userId, $subjectId)
{
    try {

        $db = new myPDO();
        $sql = $db->prepare("call P_NotifUserAdd(:usrId,:subId,@count)");
        $sql->bindParam(':subId', $subjectId, PDO::PARAM_INT);
        $sql->bindParam(':usrId', $userId, PDO::PARAM_INT);
        $sql->execute();
        $sql = $db->query("SELECT @count");
        $sql->execute();

        $result = $sql->fetchColumn();

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $result;
}

/*** REMPLACE LA PROCEDURE CAR PAS SUPPORTE PAR SERVEURS INFOMANIAK
 * @param $subjectId
 * @param $userId
 * @return int
 */
function fctNotificationUserRemove2($subjectId, $userId)
{
    try {
        $db = new myPDO();
        $sql = $db->prepare("DELETE n FROM distribution d
                                        JOIN subject s ON d.dis_subid= s.sub_id AND d.dis_usrid = :usrId
                                        JOIN message m ON m.msg_subid = s.sub_id AND m.msg_subid = :subId
                                        JOIN notification n ON n.not_msgid=m.msg_id AND n.not_usrid = :usrId");
        $sql->bindParam(':subId', $subjectId, PDO::PARAM_INT);
        $sql->bindParam(':usrId', $userId, PDO::PARAM_INT);
        $sql->execute();

        $result = $sql->rowCount();

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $result;
}

/***REMPLACE LA PROCEDURE CAR PAS SUPPORTE PAR SERVEURS INFOMANIAK
 * fctNotificationUserAdd2: crée une notification
 * @param $subjectId
 * @param $userId
 * @return int
 */
function fctNotificationUserAdd2($subjectId, $userId)
{

    try {
        $db = new myPDO();
        $sql = $db->prepare("SELECT distinct(m.msg_id) FROM message m  where m.msg_subid = :subId");
        $sql->bindParam(':subId', $subjectId, PDO::PARAM_INT);
        $sql->execute();

        $messageList = $sql->fetchall(PDO::FETCH_BOTH);

        $i = 0;
        foreach ($messageList as $messageItem) {

            try {
                $sql = $db->prepare("INSERT INTO notification (not_msgid, not_usrid) VALUES(:msgId, :usrId)");;
                $sql->bindParam(':usrId', $userId, PDO::PARAM_INT);
                $sql->bindParam(':msgId', $messageItem['msg_id'], PDO::PARAM_INT);
                $sql->execute();
                $i++;// should the execute() throw an error, counter is not incremented.
            } catch (PDOException $e) {
                if (strpos($e->getMessage(), "Duplicate entry") || (strpos($e->getMessage(), "1062"))) {
                    //don't care, continue !
                }
            }
        }
    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $i;
}

/*** REMPLACE LE TRIGGER CAR PAS SUPPORTE PAR SERVEURS INFOMANIAK
 * @param $messageId
 * @return int
 */
function fctNotificationMessages($subjectId, $messageId)
{
    try {
        $db = new myPDO();
        $sql = $db->prepare("SELECT distinct(u.usr_id) from user u join distribution d on d.dis_usrid = u.usr_id where d.dis_subid =:subId");
        $sql->bindParam(':subId', $subjectId, PDO::PARAM_INT);
        $sql->execute();

        $userList = $sql->fetchall(PDO::FETCH_COLUMN);

        $i = 0;
        foreach ($userList as $userItem) {
            $sql = $db->prepare("INSERT INTO notification (not_usrid, not_msgid) VALUES(:usrId, :msgId)");;
            $sql->bindParam(':msgId', $messageId, PDO::PARAM_INT);
            $sql->bindParam(':usrId', $userItem, PDO::PARAM_INT);
            $sql->execute();
            $i++;
        }
    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $i;
}

/* -- Admin ------------------------------------------------------------------------------- */

/* Settings ------------------------------------------------------------------------------- */
/***
 * fctSettingAdd : Add a Setting and return True or False (Configuration / Demo data)
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
function fctSettingEdit($id, $value)
{
    try {
        $db = new myPDO();

        $sql = $db->prepare("UPDATE settings SET set_value = :value WHERE set_id = :id");
        $sql->bindParam(':id', $id, PDO::PARAM_STR);
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
 * fctSettingTypeList : Return Types of Settings in an array
 * @param string $type
 * @return mixed
 */
function fctSettingTypeList()
{
    try {
        $db = new myPDO();

        $sql = $db->prepare("SELECT DISTINCT(set_type) FROM settings");
        $sql->execute();

        $TypesList = $sql->fetchall(PDO::FETCH_BOTH);

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $TypesList;
}

/***
 * fctSettingList : Return Settings of a Type in an array
 * @param string $type
 * @return mixed
 */
function fctSettingList($type)
{
    try {
        $db = new myPDO();

        $sql = $db->prepare("SELECT * FROM settings WHERE set_type=:type");
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
 * fctSettingItem: Return a given Type + Setting
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

/***
 * clearDatabase: clear content of db
 */
function clearDatabase()
{
    try {
        $db = new myPDO();

        $sql = $db->prepare("DELETE FROM notification");
        $sql->execute();
        $sql = $db->prepare("DELETE FROM message; ALTER TABLE message AUTO_INCREMENT=1");
        $sql->execute();
        $sql = $db->prepare("DELETE FROM distribution");
        $sql->execute();
        $sql = $db->prepare("DELETE FROM subject; ALTER TABLE subject AUTO_INCREMENT=1");
        $sql->execute();
        $sql = $db->prepare("DELETE FROM membership");
        $sql->execute();
        $sql = $db->prepare("DELETE FROM ugroup; ALTER TABLE ugroup AUTO_INCREMENT=1");
        $sql->execute();
        $sql = $db->prepare("DELETE FROM user; ALTER TABLE user AUTO_INCREMENT=1");
        $sql->execute();
        $sql = $db->prepare("DELETE FROM settings; ALTER TABLE settings AUTO_INCREMENT=1");
        $sql->execute();

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
}

/***
 * fctInsertDemoData: Populate database with demonstration data
 */
function fctInsertDemoData()
{ include_once("config/dummytexts.php");
    clearDatabase();

    fctSettingAdd('SITE_CONFIG', 'SITE_NAME', 'MessagingModule');
    fctSettingAdd('SITE_CONFIG', 'COPYRIGHT', '&copy; 2018 &mdash; Frank Théodoloz');

    fctGroupAdd('ADMIN', 'Administrators');
    fctGroupAdd('USER', 'Users');

    fctUserAdd('ADMIN', 'Administrator', 'admin@localhost', 'admin');
    fctMembershipAdd(1, 1);
    fctUserChangeActive(1, 1);

    fctUserAdd('SUPER', 'Administrator', 'super@localhost', 'super');
    fctMembershipAdd(1, 2);
    fctUserChangeActive(2, 1);

    fctUserAdd('Frank', 'Théodoloz', 'fthe@bluewin.ch', '1234');
    fctMembershipAdd(1, 3);
    fctUserChangeActive(3, 1);

    fctUserAdd('Al', 'Pacino', 'usera@localhost', 'usera');
    fctMembershipAdd(2, 4);
    fctUserChangeActive(4, 1);
    fctUserAdd('Bruce', 'Willis', 'userb@localhost', 'userb');
    fctMembershipAdd(2, 5);
    fctUserChangeActive(5, 1);
    fctUserAdd('Clint', 'Eastwood', 'userc@localhost', 'userc');
    fctMembershipAdd(2, 6);
    fctUserChangeActive(6, 1);
    fctUserAdd('Dustin', 'Hoffman', 'userd@localhost', 'userd');
    fctMembershipAdd(2, 7);
    fctUserChangeActive(7, 1);
    fctUserAdd('Eddie', 'Murphy', 'usere@localhost', 'usere');
    fctMembershipAdd(2, 8);
    fctUserChangeActive(8, 1);
    fctUserAdd('Franck', 'Dubosc', 'userf@localhost', 'userf');
    fctMembershipAdd(2, 9);
    fctUserChangeActive(9, 1);
    fctUserAdd('Gerard', 'Depardieu', 'userg@localhost', 'userg');
    fctMembershipAdd(2, 10);
    fctUserChangeActive(10, 1);
    fctUserAdd('Harrison', 'Ford', 'userh@localhost', 'userh');
    fctMembershipAdd(2, 11);
    fctUserChangeActive(11, 1);
    fctUserAdd('Isabelle', 'Adjani', 'useri@localhost', 'useri');
    fctMembershipAdd(2, 12);
    //fctUserChangeActive(12, 1);
    fctUserAdd('Jason', 'Statham', 'userj@localhost', 'userj');
    fctMembershipAdd(2, 13);
    fctUserChangeActive(13, 1);
    fctUserAdd('Leslie', 'Nielsen', 'userl@localhost', 'userl');
    fctMembershipAdd(2, 14);
    fctUserChangeActive(14, 1);
    fctUserAdd('Morgan', 'Freeman', 'userm@localhost', 'userm');
    fctMembershipAdd(2, 15);
    fctUserChangeActive(15, 1);
    fctUserAdd('Nicole', 'Kidman', 'usern@localhost', 'usern');
    fctMembershipAdd(2, 16);
    fctUserChangeActive(16, 1);
    fctUserAdd('Pierre', 'Richard', 'userp@localhost', 'userp');
    fctMembershipAdd(2, 17);
    fctUserChangeActive(17, 1);
    fctUserAdd('Quantin', 'Tarantino', 'userq@localhost', 'userq');
    fctMembershipAdd(2, 18);
    fctUserChangeActive(18, 1);
    fctUserAdd('Robin', 'Wiliams', 'userr@localhost', 'userr');
    fctMembershipAdd(2, 19);
    fctUserChangeActive(19, 1);
    fctUserAdd('Sandra', 'Bullock', 'users@localhost', 'users');
    fctMembershipAdd(2, 20);
    fctUserChangeActive(20, 1);
    fctUserAdd('Thierry', 'Lhermitte', 'usert@localhost', 'usert');
    fctMembershipAdd(2, 21);
    fctUserChangeActive(21, 1);
    fctUserAdd('Vincent', 'Cassel', 'userv@localhost', 'userv');
    fctMembershipAdd(2, 22);
    fctUserChangeActive(22, 1);
    fctUserAdd('Will', 'Smith', 'userw@localhost', 'userw');
    fctMembershipAdd(2, 23);
    fctUserChangeActive(23, 1);

//    fctSubjectNew(1, array(2, 3), "Sujet1", 'Message 1.1', "2018-08-01");
//    fctMessageAdd(1, 2, 'Message 1.2', "2018-08-24");
//    fctMessageAdd(1, 3, 'Message 1.3', "2018-08-25");
//    fctMessageAdd(1, 2, 'Message 1.4', NULL);
//
//    fctSubjectNew(3, array(1, 2, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23), "Sujet2", 'Message 2.1', "2018-08-01");
//    fctMessageAdd(2, 1, 'Message 2.2', "2018-08-03 13:22:12");

fctSubjectNew(1, array(2, 3), fctGetRandomSubject()."Sujet1", fctGetRandomText(), "2018-08-01 00:03:11");
fctMessageAdd(1, 2, fctGetRandomText(), "2018-08-08 01:37:39");
fctMessageAdd(1, 3, fctGetRandomText(), "2018-08-08 14:34:40");
fctMessageAdd(1, 2, fctGetRandomText(), "2018-08-09 09:15:10");
fctMessageAdd(1, 3, fctGetRandomText(), "2018-08-11 12:39:18");
fctMessageAdd(1, 2, fctGetRandomText(), "2018-08-15 15:04:16");
fctMessageAdd(1, 3, fctGetRandomText(), "2018-08-23 02:24:15");
fctMessageAdd(1, 2, fctGetRandomText(), "2018-08-23 16:56:03");
fctSubjectNew(3, array(1, 2, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13), fctGetRandomSubject()."Sujet2", fctGetRandomText(), "2018-08-04 02:34:29");
fctMessageAdd(2, 1, fctGetRandomText(), "2018-08-04 11:52:37");
fctMessageAdd(2, 2, fctGetRandomText(), "2018-08-07 11:41:34");
fctMessageAdd(2, 3, fctGetRandomText(), "2018-08-09 00:47:33");
fctMessageAdd(2, 4, fctGetRandomText(), "2018-08-09 13:54:15");
fctMessageAdd(2, 2, fctGetRandomText(), "2018-08-11 17:07:19");
fctMessageAdd(2, 1, fctGetRandomText(), "2018-08-13 09:04:45");
fctMessageAdd(2, 7, fctGetRandomText(), "2018-08-14 10:16:41");
fctMessageAdd(2, 5, fctGetRandomText(), "2018-08-19 15:33:22");
fctMessageAdd(2, 3, fctGetRandomText(), "2018-08-20 04:56:28");
fctMessageAdd(2, 6, fctGetRandomText(), "2018-08-21 13:19:12");
fctMessageAdd(2, 5, fctGetRandomText(), "2018-08-22 10:28:52");
fctMessageAdd(2, 12, fctGetRandomText(), "2018-08-24 13:32:01");
fctSubjectNew(4, array(2, 3, 8, 13, 15), fctGetRandomSubject()."Sujet3", fctGetRandomText(), "2018-08-02 12:08:57");
fctMessageAdd(3, 8, fctGetRandomText(), "2018-08-06 10:32:05");
fctMessageAdd(3, 13, fctGetRandomText(), "2018-08-08 10:27:56");
fctMessageAdd(3, 3, fctGetRandomText(), "2018-08-12 05:29:27");
fctMessageAdd(3, 4, fctGetRandomText(), "2018-08-13 10:29:22");
fctMessageAdd(3, 15, fctGetRandomText(), "2018-08-14 14:25:08");
fctMessageAdd(3, 8, fctGetRandomText(), "2018-08-14 14:40:21");
fctMessageAdd(3, 3, fctGetRandomText(), "2018-08-17 04:09:25");
fctMessageAdd(3, 2, fctGetRandomText(), "2018-08-19 04:40:05");
fctMessageAdd(3, 13, fctGetRandomText(), "2018-08-27 19:24:19");
fctSubjectNew(1, array(3), fctGetRandomSubject()."Sujet4", fctGetRandomText(), "2018-08-08 02:51:35");
fctMessageAdd(4, 3, fctGetRandomText(), "2018-08-08 07:28:37");
fctMessageAdd(4, 1, fctGetRandomText(), "2018-08-09 05:46:32");
fctMessageAdd(4, 3, fctGetRandomText(), "2018-08-21 10:09:18");
fctSubjectNew(8, array(2, 6), fctGetRandomSubject()."Sujet5", fctGetRandomText(), "2018-08-02 03:21:45");
fctMessageAdd(5, 2, fctGetRandomText(), "2018-08-11 21:43:28");
fctMessageAdd(5, 6, fctGetRandomText(), "2018-08-18 17:41:04");
fctMessageAdd(5, 8, fctGetRandomText(), "2018-08-23 21:49:25");
fctSubjectNew(15, array(2, 3), fctGetRandomSubject()."Sujet6", fctGetRandomText(), "2018-08-05 09:02:58");
fctMessageAdd(6, 2, fctGetRandomText(), "2018-08-09 08:18:34");
fctMessageAdd(6, 3, fctGetRandomText(), "2018-08-20 03:14:36");
fctSubjectNew(19, array(2, 4), fctGetRandomSubject()."Sujet7", fctGetRandomText(), "2018-08-05 11:32:03");
fctMessageAdd(7, 4, fctGetRandomText(), "2018-08-07 04:16:37");
fctMessageAdd(7, 2, fctGetRandomText(), "2018-08-16 20:59:05");
fctMessageAdd(7, 19, fctGetRandomText(), "2018-08-18 04:10:49");
fctMessageAdd(7, 4, fctGetRandomText(), "2018-08-22 21:01:02");
fctSubjectNew(16, array(2, 15), fctGetRandomSubject()."Sujet8", fctGetRandomText(), "2018-08-03 00:29:04");
fctMessageAdd(8, 15, fctGetRandomText(), "2018-08-04 11:53:53");
fctMessageAdd(8, 16, fctGetRandomText(), "2018-08-12 19:46:52");
fctSubjectNew(3, array(1, 2), fctGetRandomSubject()."Sujet9", fctGetRandomText(), "2018-08-04 08:58:46");
fctMessageAdd(9, 2, fctGetRandomText(), "2018-08-09 21:46:38");
fctMessageAdd(9, 1, fctGetRandomText(), "2018-08-18 18:41:51");
fctMessageAdd(9, 3, fctGetRandomText(), "2018-08-19 13:26:25");
fctSubjectNew(18, array(2,3,4,5), fctGetRandomSubject()."Sujet10", fctGetRandomText(), "2018-08-08 08:40:55");
fctMessageAdd(10, 2, fctGetRandomText(), "2018-08-15 23:43:06");
fctMessageAdd(10, 3, fctGetRandomText(), "2018-08-16 22:38:44");
fctMessageAdd(10, 4, fctGetRandomText(), "2018-08-19 08:17:22");
fctMessageAdd(10, 5, fctGetRandomText(), "2018-08-23 03:21:44");


}

//clearDatabase();
//fctInsertDemoData();
//print_r(fctNotificationCount(1, 13));