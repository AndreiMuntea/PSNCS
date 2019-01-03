<?php

function GlobalsSetup()
{
    if(!isset($GLOBALS['cipher']))
    {
        $GLOBALS['cipher'] = "aes-128-gcm";
    }
    
    if(!isset($GLOBALS['iv']))
    {
        $GLOBALS['iv'] = implode(array_map("chr", array(105, 46, 196, 206, 108, 219, 129, 116, 31, 146, 103, 13)));
    }
    
    if(!isset($GLOBALS['key']))
    {
        $GLOBALS['key'] = implode(array_map("chr", array(3, 23, 14, 101, 76, 228, 162, 43, 41, 175, 168, 179, 19, 214, 238, 133, 238, 192, 185, 79, 33, 173, 8, 68, 38, 33, 24, 84, 65, 164, 63, 203, 140, 163, 92, 226, 144, 200, 114, 26, 246, 66, 151, 91, 112, 180, 249, 71, 152, 39, 213, 101, 19, 71, 45, 119, 157, 71, 218, 235, 50, 76, 235, 238)));
    }
}

function Encrypt($Text)
{
    $ciphertext = openssl_encrypt($Text, $GLOBALS['cipher'], $GLOBALS['key'], $options=0, $GLOBALS['iv'], $tag);
    return array($ciphertext, $tag);
}

function Decrypt($Text, $Tag)
{
    return openssl_decrypt($Text, $GLOBALS['cipher'], $GLOBALS['key'], $options=0, $GLOBALS['iv'], $Tag);
}

GlobalsSetup();

?>