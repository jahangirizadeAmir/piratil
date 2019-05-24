<?php
/**
 * Created by PhpStorm.
 * User: amirjahangiri
 * Date: 2019-05-09
 * Time: 16:37
 */
include "../class/dataBase.php";
$db = new dataBase();

if (
   isset($_POST['mobile']) &&
   isset($_POST['code']) &&
   isset($_POST['type']) &&
   isset($_POST['appVersion']) &&
   isset($_POST['device']) &&
   $_POST['mobile']!='' &&
   $_POST['code']!='' &&
   $_POST['type']!='' &&
   $_POST['appVersion']!='' &&
   $_POST['device']!=''
) {
    $result = $db::RealString($_POST);
    $mobile = $result['mobile'];
    $code = $result['code'];
    $type = $result['type'];
    $appVersion = $result['appVersion'];
    $device = $result['device'];
    if(!$db::checkVersion($device,$appVersion)){
        $version=false;
        $call=array("error"=>true,"version"=>false);
        echo json_encode($call);
        return;
    }
    if($type=='user') {
        $selectNum = $db::Query("
                        SELECT userId FROM user where 
                              user.userMobile='$mobile'
                          AND 
                              user.userCode='$code'", $db::$NUM_ROW);
        if ($selectNum == 1) {
            $password = $db::randomString(10);
            $encode = hash("md5", $password);
            //For Client
            $encode2 = hash("md5", $encode);
            //For Server
            $va_password = $db::HashPassword($encode2);
            //Encode MD5 For security
            $update = $db::Query(
                "UPDATE user set 
                        userPassword='$va_password',userCode=''
                        where userCode='$code'
                          AND userMobile='$mobile'
        "
            );
            $call = array("error" => false, "token" => $encode, "version" => true);
            echo json_encode($call);
        } else {
            $call = array("error" => true, "MSG" => "کد وارد شده اشتباه است", "version" => true);
            echo json_encode($call);
        }
    }else if($type=="location"){
        $selectNum = $db::Query("
                        SELECT locationId FROM location where 
                              location.locationPhoneNumber='$mobile'
                          AND 
                              location.locationCode='$code'", $db::$NUM_ROW);
        if ($selectNum == 1) {
            $password = $db::randomString(10);
            $encode = hash("md5", $password);
            //For Client
            $encode2 = hash("md5", $encode);
            //For Server
            $va_password = $db::HashPassword($encode2);
            //Encode MD5 For security
            $update = $db::Query(
                "UPDATE location set 
                        locationPassword='$va_password',locationCode=''
                        where locationCode='$code'
                          AND locationPhoneNumber='$mobile'"
            );
            $call = array("error" => false, "token" => $encode, "version" => true);
            echo json_encode($call);
        } else {
            $call = array("error" => true, "MSG" => "کد وارد شده اشتباه است");
            echo json_encode($call);
        }
    }
}
?>