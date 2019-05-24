<?php
/**
 * Created by PhpStorm.
 * User: amirjahangiri
 * Date: 2019-05-24
 * Time: 17:49
 */

class request
{

    public static function submitUser(){
        include "dataBase.php";
        $db = new dataBase();
        $CheckedArray = array("mobile","appVersion","device");
        if(
            $db::issetParams($_POST,$CheckedArray)
            &&
            $db::emptyParams($_POST,$CheckedArray)
        ){
            $result = $db::RealString($_POST);
            $mobile = $result['mobile'];
            $appVersion = $result['appVersion'];
            $device = $result['device'];
            if(!$db::checkVersion($device,$appVersion)){
                $version=false;
                $call=array("error"=>true,"version"=>false);
                echo json_encode($call);
                return;
            }
            $select = $db::Query("SELECT * FROM user WHERE userMobile='$mobile'",$db::$NUM_ROW);
            $resultRow = $db::Query("SELECT * FROM user WHERE userMobile='$mobile'",$db::$RESULT_ARRAY);
            if($select==1){
                $username = $resultRow['userName'];
                if($username!="" && $username!=null){
                    $dataComplete=true;
                }else{
                    $dataComplete=false;
                }
                $userId = $resultRow['userId'];
                $type = "user";
                $code = rand(10000,99999);
                $update = $db::Query("UPDATE user Set userCode='$code' where userId='$userId'");
                $call = array("type"=>$type,"submit"=>false,"error"=>false,"dataComplete"=>$dataComplete,"version"=>true);
                $db::sendSms($code,$mobile);
                echo json_encode($call);
                return;
            }else{
                $select2 = $db::Query("SELECT * FROM location WHERE locationPhoneNumber='$mobile'",$db::$NUM_ROW);
                $resultRow2 = $db::Query("SELECT * FROM location WHERE locationPhoneNumber='$mobile'",$db::$RESULT_ARRAY);
                if($select2==1){
                    $locationId = $resultRow2['locationId'];
                    $type="location";
                    $code = rand(10000,99999);
                    $update = $db::Query("
            UPDATE location Set locationCode='$code' 
            where locationId='$locationId'"
                    );
                    $call = array("type"=>$type,"submit"=>false,"error"=>false,"version"=>true);
                    echo json_encode($call);
                    $db::sendSms($code,$mobile);
                    return;
                }else{
                    $id = $db::Gid();
                    $date = $db::GetDate();
                    $time = $db::GetTime();
                    $code = rand(10000,99999);
                    $insert = $db::Query(
                        "INSERT INTO user
          (userId,userMobile,userRegDate,userRegTime,userCode)
          VALUES ('$id','$mobile','$date','$time','$code')"
                    );
                    $call = array("type"=>"user","submit"=>true,"error"=>false,"dataComplete"=>false,"version"=>true);
                    $db::sendSms($code,$mobile);
                    echo  json_encode($call);
                    return;
                }
            }
        }
    }

}