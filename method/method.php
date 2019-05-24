<?php
/**
 * Created by PhpStorm.
 * User: amirjahangiri
 * Date: 2019-05-24
 * Time: 17:16
 */
if (
    isset($_POST['method'])
    &&
    $_POST['method'] != '') {

    include "../class/request.php";
    $request = new request();

    switch ($_POST['method']) {
        case 'submitUser':
            $request::submitUser();
            break;
        case 'Lang':

    }


}