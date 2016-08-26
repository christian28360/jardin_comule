<?php

// pour test commit git

/*
 * retourne un print_r ou var_dump entouré de balises <hr>
 * 
 * var $titre le titre du dump (facultatif), default = none
 * var $var variable à afficher (facultatif)
 *      var_dump()  si array ou object
 *      print_f     si autre (string, integer, ...
 * 
 */
function _dump($t = null, $var = null) {
    
    echo '<br>';
    if ($t) {
        print_r('<b><u>Debug de</u> : ' . $t . ' (' . gettype($var) . ')</b>&nbsp;&nbsp;&nbsp;');
    }
    if (is_null($var)){
            print_r('<b><u>La variable testée est nulle</u></b>');
    }
    if ($var) {
        if (is_object($var) || is_array($var)) {
            var_dump($var);
        } else {
            print_r('"' . $var . '"');
        }
    }
    echo '<hr>';
}

/*
 * var $var variable à tester
 * var $min borne minimum
 * var $max borne maximum
 * 
 * return true si $param est entre $min et $max
 * return false sinon ou si paramètres incorrects
 */

function _between($var = null, $min = null, $max = null) {

    if ($min === null || $max === null) {
        return false;
    }

    // $var non null doit être géré au niveau du formulaire, si nécessaire
    if ($var === null or ( $var >= $min && $var <= $max )) {
        return true;
    }

    return false;
}

/*
 * var $code integer
 * return md5 de var numérisé
 */

function _calcCode($param) {

    $text = md5(strtoupper(trim($param)));
    $code = 0;

    for ($i = 0; $i < strlen($text); $i++) {
        $code += ord(substr($text, $i, 1)) * ( bcpow($i + 1, 2) );
    }
    return $code;
}

function _empty($value) {
    return empty($value);
}

function _isset($value) {
    return isset($value);
}

function _reset($value) {
    return reset($value);
}

function _end($value) {
    return end($value);
}

function toUTF8($string = '') {
    return mb_convert_encoding($string, 'UTF-8', mb_detect_encoding($string, array('UTF-8', 'ISO-8859-1', 'ASCII'), true));
}

function array_filter_key($input, $callback) {
    if (!is_array($input)) {
        trigger_error('array_filter_key() expects parameter 1 to be array, ' . gettype($input) . ' given', E_USER_WARNING);
        return null;
    }

    if (empty($input)) {
        return $input;
    }

    $filteredKeys = array_filter(array_keys($input), $callback);
    if (empty($filteredKeys)) {
        return array();
    }

    $input = array_intersect_key($input, array_flip($filteredKeys));

    return $input;
}

function _left($str, $length) {
    return substr($str, 0, $length);
}

function _right($str, $length) {
    return substr($str, -$length);
}
