<?php
/**
 * Created by PhpStorm.
 * User: amirjahangiri
 * Date: 2019-05-10
 * Time: 22:10
 */
$userId='';
include "../inc/checkUser.php";
$select = $db::Query("SELECT * from challenge where challengeStatus='1'");

while ($rowChallenge = mysqli_fetch_assoc($select)){
    $challengeId = $rowChallenge['challengeId'];

    $checkUser = $db::Query("SELECT * FROM challengUser where challengUserUserId='$userId' AND challengUserChallengeId='$challengeId'",$db::$NUM_ROW);
    $checkUser==0?$in=false:$in=true;

    $callArray[] = array(
        "userInSide"=>$in,
        "id"=>$rowChallenge['challengeId'],
        "name"=>$rowChallenge['challengeName'],
        "lat"=>$rowChallenge['challengeLat'],
        "lng"=>$rowChallenge['challengeLng'],
        "address"=>$rowChallenge['challengeAddress'],
        "price"=>$rowChallenge['challengePriceGem'],
        "winner"=>$rowChallenge['challengeWinnerGem']
    );
    if (!empty($callArray)) {
        $call = array("error" => false, "version" => true, "login" => true,"result"=>true);
        $call['challenge'] = $callArray;
    } else {
        $call = array("error" => false, "version" => true, "login" => true,"result"=>false,"MSG"=>"موردی یافت نشد.");
        $call['challenge'] = array();
    }
}