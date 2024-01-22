<?php

function gen($count) {
    $rand = "";
    $seed = str_split('abcdefghijklmnopqrstuvwxyz');
    shuffle($seed);
    foreach (array_rand($seed, $count) as $k)
        $rand .= $seed[$k];

    return $rand;
}

function generateUID() {
    // ex: awe-msub-ore
    return gen() . '-' . gen() . '-' . gen();
}

function rand_string( $length ) {
    $chars = "+=-)(*&^%$#@!abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

    return substr( str_shuffle( $chars ), 0, $length );
}