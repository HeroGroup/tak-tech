<?php

function gen($length) {
    $chars = 'abcdefghijklmnopqrstuvwxyz';

    return substr( str_shuffle( $chars ), 0, $length );
}

function generateUID() {
    // ex: awe-msub-ore
    return gen(3) . '-' . gen(4) . '-' . gen(3);
}

function rand_string( $length ) {
    $chars = "+=-)(*&^%$#@!abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

    return substr( str_shuffle( $chars ), 0, $length );
}

function generateInviteCode($length=5) {
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

    return substr( str_shuffle( $chars ), 0, $length );
}

function generateDiscountCode($length=8) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

    return substr( str_shuffle( $chars ), 0, $length );
}