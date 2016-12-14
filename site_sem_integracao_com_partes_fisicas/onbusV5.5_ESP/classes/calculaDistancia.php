<?php

function calcDistancia( $lat1, $long1, $lat2, $long2 ){
    $d2r = 0.017453292519943295769236;

    $dlong = ($long2 - $long1) * $d2r;
    $dlat = ($lat2 - $lat1) * $d2r;

    $temp_sin = sin($dlat/2.0);
    $temp_cos = cos($lat1 * $d2r);
    $temp_sin2 = sin($dlong/2.0);

    $a = ($temp_sin * $temp_sin) + ($temp_cos * $temp_cos) * ($temp_sin2 * $temp_sin2);
    $c = 2.0 * atan2(sqrt($a), sqrt(1.0 - $a));

    return 6368.1 * $c;
}

function calcDistancia1( $lat1, $long1, $lat2, $long2 ){
    $dlong = ($long2 - $long1);
    $dlat = ($lat2 - $lat1);

    $a = pow( sin($dlat/2.0), 2 ) + cos($lat1)*cos($lat2)*pow( sin($dlong/2.0), 2 );

    $c = 2.0 * asin( min( 1, sqrt($a)) );

    return 6368100 * $c;
}

?>