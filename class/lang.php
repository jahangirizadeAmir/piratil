<?php
/**
 * Created by PhpStorm.
 * User: amirjahangiri
 * Date: 2019-05-24
 * Time: 17:52
 */

class lang
{
    private static $array;
    public function __construct($lang='fa')
    {
        if($lang=='fa'){
            $this::$array=array(
                "submit"=>"ورود",
                "enter your number"=>"شماره تلفن خود را وارد کنید"
            );
        }

        if($lang=='en'){
            $this::$array=array(
                "submit"=>"ورود",
                "enter your number"=>"شماره تلفن خود را وارد کنید"
            );
        }

    }

}