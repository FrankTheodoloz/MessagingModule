<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 05/08/2018
 * Time: 21:34
 */

$userList = fctUserList();

?>

<div class="container container-fluid mt-4 mb-4">

    <div class="row">
        <div class="col"><h2>Users List</h2></div>
        <div class="col"></div>
        <div class="col"><input class="form-control " id="myInput" type="text" placeholder="Search.."/></div>
    </div>

    <table class="table table-striped" id="myTable">
        <thead>
        <tr>
            <th>id</th>
            <th>name</th>
            <th>lastname</th>
            <th>email</th>
            <th colspan="2">active</th>

        </tr>
        </thead>
        <tbody>

        <?php
        foreach ($userList as $item) {
            echo '<tr><td>' . $item["usr_id"] . '</td><td>' . $item["usr_name"] . '</td><td>' . $item["usr_lastname"] . '</td><td>' . $item["usr_email"] . '</td><td>' . $item["usr_active"] . '</td>
                    <td><a class="badge badge-primary" href="?id=' . fctUrlOpensslCipher("userDetail.php," . $item["usr_id"]) . '"><i class="fas fa-edit"></i><small> Edit</small></a></td>
                  </tr>';
        }
        ?>

        </tbody>

    </table>

    <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Add User</button>
</div>


