<?php
/**
 * Subject: PHP functions related to the SQL database
 * User: FrK
 * Date: 26/07/2018
 * Time: 12:58
 */

include_once("config/config.inc.php");

/* Bcrypt --------------------------------------------------------------------------------- */
/***
 * fctBcryptHash : Return $password BCrypt hash
 * @param $password
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
 * @param $name
 * @param $lastname
 * @param $email
 * @param $password
 * @return bool|string
 */
function fctUserAdd($name, $lastname, $email, $password)
{
    global $dsn, $dbUser, $dbPassword;

    try {
        $pwdhash = fctBcryptHash($password);

        $db = new MyPDO($dsn, "$dbUser", "$dbPassword");

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
            $messg = "Email <em>{$email}</em> already registered.";
        } else {
            die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
        }
        return false;
    }
}

/***
 * fctUserEdit : Update a User and return rowCount() otherwise False
 * @param $id
 * @param $name
 * @param $lastname
 * @return bool|int
 */
function fctUserEdit($id, $name, $lastname)
{
    global $dsn, $dbUser, $dbPassword;

    try {
        $db = new MyPDO($dsn, "$dbUser", "$dbPassword");

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
 * fctUserEditEmail : Update a User email and return rowCount() otherwise False
 * @param $id
 * @param $email
 * @return bool|int
 */
function fctUserEditEmail($id, $email)
{
    global $dsn, $dbUser, $dbPassword;

    try {
        $db = new MyPDO($dsn, "$dbUser", "$dbPassword");

        $sql = $db->prepare("UPDATE user set usr_email=:email WHERE usr_id=:id");
        $sql->bindParam(':email', $email, PDO::PARAM_STR);
        $sql->bindParam(':id', $id, PDO::PARAM_INT);
        $sql->execute();

        $result = $sql->rowCount();

    } catch (PDOException $e) {
        if (strpos($e->getMessage(), "Duplicate entry") || (strpos($e->getMessage(), "1062"))) {
            $messg = "Email <em>{$email}</em> already registered.";
        } else {
            die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
        }
        return false;
    }
    $db = NULL; // Close connection
    return $result;
}

/***
 * fctUserEditPwd : Update a User Password and return rowCount() otherwise False
 * @param $id
 * @param $pwd
 * @return bool|int
 */
function fctUserEditPwd($id, $pwd)
{
    global $dsn, $dbUser, $dbPassword;

    try {
        $pwdhash = fctBcryptHash($pwd);

        $db = new MyPDO($dsn, "$dbUser", "$dbPassword");

        $sql = $db->prepare("UPDATE user set usr_pwdhash=:pwdhash WHERE usr_id=:id");
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
 * fctUserIfAdmin : Return if a user is admin or not
 * @param $id
 * @param $pwd
 * @return bool|int
 */
function fctUserIfAdmin($id)
{
    global $dsn, $dbUser, $dbPassword;

    try {
        $db = new MyPDO($dsn, "$dbUser", "$dbPassword");
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
 * fctUserEnable : Reactivate a user, return rowCount()
 * @param $id
 * @return int
 */
function fctUserEnable($id)
{
    global $dsn, $dbUser, $dbPassword;

    try {
        $db = new MyPDO($dsn, "$dbUser", "$dbPassword");

        $sql = $db->prepare("UPDATE user set usr_active=1 WHERE usr_id=:id");
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
 * fctUserDisable : Deactivate a User, return rowCount()
 * @param $id
 * @return int
 */
function fctUserDisable($id)
{
    global $dsn, $dbUser, $dbPassword;

    try {
        $db = new MyPDO($dsn, "$dbUser", "$dbPassword");

        $sql = $db->prepare("UPDATE user set usr_active=0 WHERE usr_id=:id");
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
 * fctUserList : Return User(s) in an array
 * @param $userId (optional: default All)
 * @return mixed
 */
function fctUserList($userId = 0)
{

    global $dsn, $dbUser, $dbPassword;

    try {
        $db = new MyPDO($dsn, "$dbUser", "$dbPassword");

        if ($userId > 0) {
            $sql = $db->prepare("SELECT * FROM user WHERE usr_id=:userId");
            $sql->bindParam(':userId', $userId, PDO::PARAM_INT);
        } else {
            $sql = $db->prepare("SELECT * FROM user");
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
 * @param $email
 * @param $password
 * @return bool
 */
function fctUserLogin($email, $password)
{
    global $dsn, $dbUser, $dbPassword, $loginMessage;

    try {
        $db = new MyPDO($dsn, "$dbUser", "$dbPassword");
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
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return true;
}

/***
 * fctUsersFromGroup: Return a list of users from a group
 * @param $id
 * @return array
 */
function fctUsersFromGroup($id)
{
    global $dsn, $dbUser, $dbPassword;

    try {
        $db = new MyPDO($dsn, "$dbUser", "$dbPassword");

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
 * @param $id
 * @return array
 */
function fctUsersNotGroup($id)
{
    global $dsn, $dbUser, $dbPassword;

    try {
        $db = new MyPDO($dsn, "$dbUser", "$dbPassword");

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
 * fctGroupAdd : Add a Group and return lastInsertId()
 * @param $groupName
 * @return bool|string
 */
function fctGroupAdd($groupName, $groupDescription)
{
    global $dsn, $dbUser, $dbPassword;

    try {
        $db = new MyPDO($dsn, "$dbUser", "$dbPassword");

        $sql = $db->prepare("INSERT INTO ugroup (grp_name, grp_description) VALUES (UPPER(:groupName),:groupDescription)");
        $sql->bindParam(':groupName', $groupName, PDO::PARAM_STR);
        $sql->bindParam(':groupDescription', $groupDescription, PDO::PARAM_STR);
        $sql->execute();

        $lastId = $db->lastInsertId();

    } catch (PDOException $e) {
        if (strpos($e->getMessage(), "Duplicate entry") || (strpos($e->getMessage(), "1062"))) {
            $messg = "Duplicate entry.";
        } else {
            die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
        }
        return false;
    }
    $db = NULL; // Close connection
    return $lastId;
}


/***
 * fctGroupAdd : Add a Group and return lastInsertId()
 * @param $name
 * @return bool|string$
 */
function fctGroupEdit($id, $name, $description)
{
    global $dsn, $dbUser, $dbPassword;

    try {
        $db = new MyPDO($dsn, "$dbUser", "$dbPassword");

        $sql = $db->prepare("UPDATE ugroup SET grp_name = UPPER(:name), grp_description = :description WHERE grp_id = :id");
        $sql->bindParam(':id', $id, PDO::PARAM_INT);
        $sql->bindParam(':name', $name, PDO::PARAM_STR);
        $sql->bindParam(':description', $description, PDO::PARAM_STR);
        $sql->execute();

        $result = $sql->rowCount();

    } catch (PDOException $e) {
        if (strpos($e->getMessage(), "Duplicate entry") || (strpos($e->getMessage(), "1062"))) {
            $messg = "Duplicate entry.";
        } else {
            die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
        }
        return false;
    }
    $db = NULL; // Close connection
    return $result;
}

/***
 * fctGroupList: Return Groups in an array
 * @param int $id (optional: default All)
 * @return array
 */
function fctGroupList($id = 0)
{
    global $dsn, $dbUser, $dbPassword;

    try {
        $db = new MyPDO($dsn, "$dbUser", "$dbPassword");

        if ($id > 0) {
            $sql = $db->prepare("SELECT * FROM ugroup WHERE grp_id=:id");
            $sql->bindParam(':id', $id, PDO::PARAM_INT);
        } else {
            $sql = $db->prepare("SELECT * FROM ugroup");
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
 * fctMembershipAdd : Add a relation btw a User and a Group, return lastInsertId()
 * @param $groupId
 * @param $userId
 * @return bool|string
 */
function fctMembershipAdd($groupId, $userId)
{
    global $dsn, $dbUser, $dbPassword;

    try {
        $db = new MyPDO($dsn, "$dbUser", "$dbPassword");

        $sql = $db->prepare("INSERT INTO membership (mem_grpid, mem_usrid) VALUES (:groupId, :userId)");
        $sql->bindParam(':groupId', $groupId, PDO::PARAM_INT);
        $sql->bindParam(':userId', $userId, PDO::PARAM_INT);
        $sql->execute();

        $result = $sql->RowCount();

    } catch (PDOException $e) {
        if (strpos($e->getMessage(), "Duplicate entry") || (strpos($e->getMessage(), "1062"))) {
            $messg = "Duplicate entry.";
        } else {
            die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
        }
        return false;
    }
    $db = NULL; // Close connection
    return $result;
}

/***
 * fctMembershipRemove : Remove a relation btw a User and a Group, return rowCount()
 * @param $groupId
 * @param $userId
 * @return bool|string
 */
function fctMembershipRemove($groupId, $userId)
{
    global $dsn, $dbUser, $dbPassword;

    try {
        $db = new MyPDO($dsn, "$dbUser", "$dbPassword");

        $sql = $db->prepare("DELETE FROM membership WHERE mem_grpid = :groupId AND mem_usrid = :userId");
        $sql->bindParam(':groupId', $groupId, PDO::PARAM_INT);
        $sql->bindParam(':userId', $userId, PDO::PARAM_INT);
        $sql->execute();

        $result = $sql->RowCount();

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());

        return false;
    }
    $db = NULL; // Close connection
    return $result;
}

/* Subject -------------------------------------------------------------------------------- */
/***
 * fctSubjectAdd : Add a Subject and return lastInsertId()
 * @param $subjectName
 * @param $categoryId
 * @return bool|string
 */
function fctSubjectAdd($subjectName, $categoryId)
{
    global $dsn, $dbUser, $dbPassword;

    try {
        $db = new MyPDO($dsn, "$dbUser", "$dbPassword");

        $sql = $db->prepare("INSERT INTO subject (sub_name, sub_catid) VALUES (:subjectName, :categoryId)");
        $sql->bindParam(':subjectName', $subjectName, PDO::PARAM_STR);
        $sql->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
        $sql->execute();

        $lastId = $db->lastInsertId();

    } catch (PDOException $e) {
        if (strpos($e->getMessage(), "Duplicate entry") || (strpos($e->getMessage(), "1062"))) {
            $messg = "Duplicate entry.";
        } else {
            die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
        }
        return false;
    }
    $db = NULL; // Close connection
    return $lastId;
}

function fctSubjectList($userId)
{

    global $dsn, $dbUser, $dbPassword;

    try {
        $db = new MyPDO($dsn, "$dbUser", "$dbPassword");

        $sql = $db->prepare("SELECT DISTINCT (s.sub_id), s.sub_date, s.sub_name, u.usr_id, u.usr_name, u.usr_lastname, u.usr_email
                                        FROM subject s
                                        JOIN message m on m.msg_subid = sub_id
                                        JOIN user u ON u.usr_id = m.msg_from
                                        WHERE (m.msg_from = :userId OR m.msg_to = :userId) AND (m.msg_date = s.sub_date)
                                        ORDER BY s.sub_date DESC");
        $sql->bindParam(':userId', $userId, PDO::PARAM_INT);
        $sql->execute();

        $settingsList = $sql->fetchall(PDO::FETCH_BOTH);

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $settingsList;


}

function fctSubjectDelete($subId)
{
    fctMessageSubDelete($subId);

    global $dsn, $dbUser, $dbPassword;

    try {
        $db = new MyPDO($dsn, "$dbUser", "$dbPassword");

        $sql = $db->prepare("DELETE FROM subject WHERE sub_id =:subId");
        $sql->bindParam(':subId', $subId, PDO::PARAM_INT);
        $sql->execute();

        $result = $sql->RowCount();

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $result;


}

/* Message -------------------------------------------------------------------------------- */
/***
 * fctMessageAdd : Add a Message and return lastInsertId()
 * @param $from
 * @param $to
 * @param $subId
 * @param $content
 * @param $date
 * @return bool|string
 */
function fctMessageAdd($from, $to, $subId, $content, $date= NULL)
{
    global $dsn, $dbUser, $dbPassword;

    try {
        $db = new MyPDO($dsn, "$dbUser", "$dbPassword");

        $sql = $db->prepare("INSERT INTO message (msg_from, msg_to, msg_subid, msg_content, msg_date) VALUES (:from, :to, :subId, :content, :date)");
        $sql->bindParam(':from', $from, PDO::PARAM_INT);
        $sql->bindParam(':to', $to, PDO::PARAM_INT);
        $sql->bindParam(':subId', $subId, PDO::PARAM_INT);
        $sql->bindParam(':content', $content, PDO::PARAM_STR);
        $sql->bindParam(':date', $date, PDO::PARAM_STR);
        $sql->execute();

        $lastId = $db->lastInsertId();

    } catch (PDOException $e) {
        if (strpos($e->getMessage(), "Duplicate entry") || (strpos($e->getMessage(), "1062"))) {
            $messg = "Duplicate entry.";
        } else {
            die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
        }
        return false;
    }
    $db = NULL; // Close connection
    return $lastId;
}

function fctMessageSubDelete($subId)
{
    global $dsn, $dbUser, $dbPassword;

    try {
        $db = new MyPDO($dsn, "$dbUser", "$dbPassword");

        $sql = $db->prepare("DELETE FROM message WHERE msg_subid = :subId");
        $sql->bindParam(':subId', $subId, PDO::PARAM_INT);
        $sql->execute();

        $result = $sql->RowCount();

    } catch (PDOException $e) {
            die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $result;
}
/***
 * fctMessageList : Return Messages in an array
 * @param $subjectId (optional: default All)
 * @return mixed
 */
function fctMessageList($subjectId = 0)
{

    global $dsn, $dbUser, $dbPassword;

    try {
        $db = new MyPDO($dsn, "$dbUser", "$dbPassword");

        if ($subjectId > 0) {
            $sql = $db->prepare("SELECT u.usr_id, u.usr_name, u.usr_name, u.usr_lastname, m.msg_date, m.msg_content FROM message m JOIN user u ON u.usr_id =  m.msg_from WHERE msg_subid=:subid");
            $sql->bindParam(':subid', $subjectId, PDO::PARAM_INT);
        } else {
            $sql = $db->prepare("SELECT * FROM message");
        }

        $sql->execute();
        $messageList = $sql->fetchall(PDO::FETCH_ASSOC);

        $db = NULL; // Close connection
        return $messageList;

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }

}

/* -- Admin ------------------------------------------------------------------------------- */
/***
 * fctCategoryAdd : Add a Category and return lastInsertId()
 * @param $name
 * @return bool|string$
 */
function fctCategoryAdd($name, $description)
{
    global $dsn, $dbUser, $dbPassword;

    try {
        $db = new MyPDO($dsn, "$dbUser", "$dbPassword");

        $sql = $db->prepare("INSERT INTO category (cat_name, cat_description) VALUES (:name, :description)");
        $sql->bindParam(':name', $name, PDO::PARAM_STR);
        $sql->bindParam(':description', $description, PDO::PARAM_STR);
        $sql->execute();

        $lastId = $db->lastInsertId();

    } catch (PDOException $e) {
        if (strpos($e->getMessage(), "Duplicate entry") || (strpos($e->getMessage(), "1062"))) {
            $messg = "Duplicate entry.";
        } else {
            die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
        }
        return false;
    }
    $db = NULL; // Close connection
    return $lastId;
}

/***
 * fctCategoryAdd : Add a Category and return lastInsertId()
 * @param $name
 * @return bool|string$
 */
function fctCategoryEdit($id, $name, $description)
{
    global $dsn, $dbUser, $dbPassword;

    try {
        $db = new MyPDO($dsn, "$dbUser", "$dbPassword");

        $sql = $db->prepare("UPDATE category SET cat_name = :name, cat_description = :description WHERE cat_id = :id");
        $sql->bindParam(':id', $id, PDO::PARAM_INT);
        $sql->bindParam(':name', $name, PDO::PARAM_STR);
        $sql->bindParam(':description', $description, PDO::PARAM_STR);
        $sql->execute();

        $result = $sql->rowCount();

    } catch (PDOException $e) {
        if (strpos($e->getMessage(), "Duplicate entry") || (strpos($e->getMessage(), "1062"))) {
            $messg = "Duplicate entry.";
        } else {
            die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
        }
        return false;
    }
    $db = NULL; // Close connection
    return $result;
}

/***
 * fctCategoryList: Return Categories in an array
 * @param int $categoryId (optional: default All)
 * @return array
 */
function fctCategoryList($categoryId = 0)
{
    global $dsn, $dbUser, $dbPassword;

    try {
        $db = new MyPDO($dsn, "$dbUser", "$dbPassword");

        if ($categoryId > 0) {
            $sql = $db->prepare("SELECT * FROM category WHERE cat_id=:catId");
            $sql->bindParam(':catId', $categoryId, PDO::PARAM_INT);
        } else {
            $sql = $db->prepare("SELECT * FROM category");
        }

        $sql->execute();

        $list = $sql->fetchall(PDO::FETCH_BOTH);

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection
    return $list;
}


/* Settings ------------------------------------------------------------------------------- */
/***
 * fctSettingAdd : Add a Setting and return True or False
 * @param $name
 * @param $type
 * @param $value
 * @return bool
 */
function fctSettingAdd($type, $name, $value)
{
    global $dsn, $dbUser, $dbPassword;

    try {
        $db = new MyPDO($dsn, "$dbUser", "$dbPassword");

        $sql = $db->prepare("INSERT INTO settings (set_type, set_name, set_value) VALUES (:type, :name, :value)");
        $sql->bindParam(':type', $type, PDO::PARAM_STR);
        $sql->bindParam(':name', $name, PDO::PARAM_STR);
        $sql->bindParam(':value', $value, PDO::PARAM_STR);
        $sql->execute();

    } catch (PDOException $e) {
        if (strpos($e->getMessage(), "Duplicate entry") || (strpos($e->getMessage(), "1062"))) {
            $messg = "Setting <em>{$name}</em> already existing.";
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
 * @param $name
 * @param $value
 * @return int
 */
function fctSettingEdit($name, $value)
{
    global $dsn, $dbUser, $dbPassword;

    try {
        $db = new MyPDO($dsn, "$dbUser", "$dbPassword");

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
 * @param $type
 * @return mixed
 */
function fctSettingList($type)
{
    global $dsn, $dbUser, $dbPassword;

    try {
        $db = new MyPDO($dsn, "$dbUser", "$dbPassword");

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


function fctSettingItem($type, $name)
{
    global $dsn, $dbUser, $dbPassword;

    try {
        $db = new MyPDO($dsn, "$dbUser", "$dbPassword");

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
    global $dsn, $dbUser, $dbPassword;

    try {
        $db = new MyPDO($dsn, "$dbUser", "$dbPassword");

        $sql = $db->prepare("DELETE FROM settings");
        $sql->execute();
        $sql = $db->prepare("DELETE FROM message; ALTER TABLE message AUTO_INCREMENT=1");
        $sql->execute();
        $sql = $db->prepare("DELETE FROM subject; ALTER TABLE subject AUTO_INCREMENT=1");
        $sql->execute();
        $sql = $db->prepare("DELETE FROM category; ALTER TABLE category AUTO_INCREMENT=1");
        $sql->execute();
        $sql = $db->prepare("DELETE FROM membership");
        $sql->execute();
        $sql = $db->prepare("DELETE FROM ugroup; ALTER TABLE ugroup AUTO_INCREMENT=1");
        $sql->execute();
        $sql = $db->prepare("DELETE FROM user; ALTER TABLE user AUTO_INCREMENT=1");
        $sql->execute();
        $sql = $db->prepare("DELETE FROM status; ALTER TABLE status AUTO_INCREMENT=1");
        $sql->execute();

    } catch (PDOException $e) {
        die("SQL Error (" . __FUNCTION__ . ") " . $e->getMessage());
    }
    $db = NULL; // Close connection


}

function demoData()
{
    clearDatabase();

    fctSettingAdd('SITE_CONFIG', 'SITE_NAME', 'ContactModule');
    fctSettingAdd('SITE_CONFIG', 'COPYRIGHT', '&copy; 2018 &mdash; Frank Théodoloz');

    fctGroupAdd('ADMIN', 'Administrators');
    fctGroupAdd('USER', 'Users');

    fctUserAdd('Admin', 'Admin', 'admin@localhost', 'admin');
    fctMembershipAdd(1, 1);
    fctUserEnable(1);

    fctUserAdd('Admin2', 'Admin2', 'admin2@localhost', 'admin2');
    fctMembershipAdd(1, 2);
    //fctUserEnable(2);

    fctUserAdd('Frank', 'Théodoloz', 'fthe@bluewin.ch', '1234');
    fctMembershipAdd(1, 3);
    fctUserEnable(3);

    fctUserAdd('User1', 'Firstuser', 'user1@localhost', 'user1');
    fctMembershipAdd(2, 4);
    fctUserEnable(4);


    fctCategoryAdd('DEMO', 'Demo data');
    fctCategoryAdd('TEST', 'Test data');

    fctSubjectAdd('Subject1', 1);
    fctMessageAdd(1, 3, 1, 'Message 1.1',"2018-08-01");
    fctMessageAdd(3, 1, 1, 'Message 1.2', "2018-08-02 13:22:12");

    fctSubjectAdd('Subject2', 1);
    fctMessageAdd(1, 3, 2, 'Message 2.1', "2018-08-03 13:22:12");
    fctMessageAdd(3, 1, 2, 'Message 2.2', "2018-08-04 13:22:12");

    fctMessageAdd(1, 3, 1, 'Message 1.3', "2018-08-09 13:22:12");

    fctMessageAdd(1, 3, 1, 'Message 1.4', NULL);
}

//demoData();