<?php
/**
 * Created by PhpStorm.
 * User: amirjahangiri
 * Date: 2019-05-10
 * Time: 15:54
 */
include "../class/dataBase.php";
$db = new dataBase();
//$CheckedArray = array("mobile","token","gender","name","invitedMobile","appVersion","device");
//$CheckedArrayEmpty = array("mobile","token","gender","name","appVersion","device");
if(
//isset($_POST['mobile']) &&
//isset($_POST['token']) &&
//isset($_POST['gender']) &&
//isset($_POST['name']) &&
//isset($_POST['invitedMobile']) &&
//isset($_POST['appVersion']) &&
//isset($_POST['device']) &&
$_POST['device']!='' &&
$_POST['appVersion']!='' &&
$_POST['name']!='' &&
$_POST['gender']!='' &&
$_POST['token']!='' &&
//$_POST['invitedMobile']!='' &&
$_POST['mobile']!=''

) {
    $result = $db::RealString($_POST);
    $appVersion = $result['appVersion'];
    $device = $result['device'];
    if(!$db::checkVersion($device,$appVersion)){
        $version=false;
        $call=array("error"=>true,"version"=>false);
        echo json_encode($call);
        return;
    }
    $mobile = $result['mobile'];
    $token = $result['token'];
    $token = substr($token,0,-6);
    $password = $db::HashPassword($token);
    $gender = $result['gender'];
    $name = $result['name'];
    $invitedMobile = $result['invitedMobile'];
    $selectUser = $db::Query("
      SELECT userId FROM user where user.userMobile='$mobile'
      AND userPassword='$password'
    ");
    if(mysqli_num_rows($selectUser)==1){
        if($invitedMobile!=""){

            $select = $db::Query("SELECT * FROM user where userMobile='$invitedMobile'");
            if(mysqli_num_rows($select)==1){
                $rowSelect = mysqli_fetch_assoc($select);
                $userId = $rowSelect['userId'];
                $code = rand(2000,4000);
                $GemId=$db::GenerateId() ;
                $date = $db::GetDate();
                $time = $db::GetTime();
                $gemUserId = $db::GenerateId();
                $Insert = $db::Query("INSERT INTO gem (gemId, gemCode, gemGeneratedBy, gemFor, gemRegDate, gemRegTime) VALUES ('$GemId','$code','admin','دعوت','$date','$time')");
                $insert2 = $db::Query("INSERT INTO gemUser (gemUserId, gemUserUserId, gemUserGemId, gemStatus) VALUES ('$gemUserId','$userId','$GemId','1')");
            }else{
                $call = array("error"=>true,"version"=>true,"login"=>true,"MSG"=>"کاربری با این شماره تلفن در سیستم نیست");
                echo json_encode($call);
                return;
            }
        }
        $update = $db::Query("UPDATE user SET userGender='$gender',userName='$name' where user.userMobile='$mobile'
         AND userPassword='$password' ");
        $call = array("error"=>false,"version"=>true,"login"=>true);
        echo json_encode($call);
        return;
    }else{
        $call = array("error"=>true,"version"=>true,"login"=>false);
    }
}
