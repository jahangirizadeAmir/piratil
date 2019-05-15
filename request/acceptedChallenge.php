<?php
/**
 * Created by PhpStorm.
 * User: amirjahangiri
 * Date: 2019-05-10
 * Time: 22:18
 */
$userId="";
include "../inc/checkUser.php";
$checkArray = array("challengeId");
if($db::issetParams($_POST,$checkArray)&&$db::emptyParams($_POST,$checkArray)){
    $result = $db::RealString($_POST);
    $challengeId = $result['challengeId'];

    $selectChallenge = $db::Query("Select * from challenge where challengeId='$challengeId' AND challengeStatus='1'");
    if(mysqli_num_rows($selectChallenge)==1){
        $challengeGemRow = mysqli_fetch_assoc($selectChallenge);
        $gemPrice= $challengeGemRow['challengePriceGem'];
        $selectGem = $db::Query("SELECT * FROM gemUser where gemStatus='1' AND gemUserUserId='$userId'",$db::$NUM_ROW);
        if($selectGem > $gemPrice){
            $selectGem = $db::Query("SELECT * FROM gemUser where gemStatus='1' AND gemUserUserId='$userId' LIMIT $gemPrice");
            while ($rowGemExt = mysqli_fetch_assoc($selectGem)){
                $gemId = $rowGemExt['gemUserId'];
                $updatedGame = $db::Query("UPDATE gemUser set gemStatus='0' where gemStatus='1' AND gemUser.gemUserId='$gemId'");
                $challengeIdUser = $db::GenerateId();
                $insert = $db::Query("INSERT INTO challengUser (challengUserId, challengUserUserId, challengUserChallengeId) VALUES ('$challengeIdUser','$userId','$challengeId')");
                $call = array("error"=>false,"version"=>true,"login"=>true);
            }
        }else{
            $call = array("error"=>true,"version"=>true,"login"=>true,"MSG"=>"تعداد الماس های شما کمتر از تعداد مورد نیاز است.");

        }

    }else{
        $call = array("error"=>true,"version"=>true,"login"=>true,"MSG"=>"چالش به درستی انتخاب نشده است");
    }
    echo json_encode($call);
}