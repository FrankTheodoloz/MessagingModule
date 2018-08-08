<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 05/08/2018
 * Time: 13:43
 */
global $pageParameter;
global $pageStatus;
$infoMessage="";

if(!$pageStatus==0){
    $record=fctCategoryList($pageParameter);
    $infoMessage="Category #".$record[0]['cat_id']." ".$record[0]['cat_name']." (" . $record[0]['cat_description'].") ".$pageStatus;
    $pageStatus ="";
}

$categoryList = fctCategoryList();
?>

<div class="container container-fluid mt-4 mb-4">

    <div class="row">
        <div class="col"><h2>Categories List</h2></div>
        <div class="col"></div>
        <div class="col"><input class="form-control " id="myInput" type="text" placeholder="Search.."/></div>
    </div>
    <?=$infoMessage?'<div class="alert alert-success alert-dismissible">'.$infoMessage.'</div>':""?>
    <table class="table table-striped" id="myTable">
        <thead>
        <tr>
            <th>id</th>
            <th>name</th>
            <th>description</th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        <?php
        foreach ($categoryList as $item) {
            echo '<tr><td>' . $item["cat_id"] . '</td><td>' . $item["cat_name"] . '</td><td>' . $item["cat_description"] . '</td>
                    <td><a class="badge badge-primary" href="?id=' . fctUrlOpensslCipher("categoryDetail.php," . $item["cat_id"]) . '" ><i class="fas fa-edit"></i><small> Edit</small></a></td>
                  </tr>';
        }
        ?>
        </tbody>
    </table>

    <a href="?id=<?= fctUrlOpensslCipher("categoryDetail.php") ?>">
        <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Add Category</button>
    </a>

</div>

<?= fctFilterJS(); ?>
