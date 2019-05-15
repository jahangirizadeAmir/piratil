<?php
$userId="";
include "../inc/checkUser.php";
if(!isset($_POST['searchText']) || $_POST['searchText']=='') {
    $selectLocation = $db::Query("SELECT locationAddress,locationFullname,locationLat,locationLng
FROM location where location.locationStatus='1' AND locationCountGem>0");
    while ($rowLocation = mysqli_fetch_assoc($selectLocation)) {

        $callArray[] = array(
            'vip'=>$rowLocation['vip'],
            'Address' => $rowLocation['locationAddress'],
            'name' => $rowLocation['locationFullname'],
            'lat' => $rowLocation['locationLat'],
            'lng' => $rowLocation['locationLng']
        );


    }
    if (!empty($callArray)) {
        $call = array("error" => false, "version" => true, "login" => true,"result"=>true);
        $call['location'] = $callArray;
    } else {
        $call = array("error" => false, "version" => true, "login" => true,"result"=>false,"MSG"=>"موردی یافت نشد.");
        $call['location'] = array();
    }
    echo json_encode($call);
}else{
    $text = mysqli_real_escape_string($db::connection(),$_POST['searchText']);
    $selectLocation = $db::Query("SELECT locationAddress,locationFullname,locationLat,locationLng
    FROM location where location.locationStatus='1' AND locationCountGem>0 AND locationFullname LIKE '%$text%'");
    while ($rowLocation = mysqli_fetch_assoc($selectLocation)) {

        $callArray[] = array(
            'vip'=>$rowLocation['vip'],
            'Address' => $rowLocation['locationAddress'],
            'name' => $rowLocation['locationFullname'],
            'lat' => $rowLocation['locationLat'],
            'lng' => $rowLocation['locationLng']
        );


    }
    if (!empty($callArray)) {
        $call = array("error" => false, "version" => true, "login" => true,"result"=>true);
        $call['location'] = $callArray;
    } else {
        $call = array("error" => false, "version" => true, "login" => true,"result"=>false,"MSG"=>"موردی یافت نشد.");
        $call['location'] = array();
    }
    echo json_encode($call);
}

