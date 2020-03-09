<?php

namespace App\Libraries;

use DB;

class Helper {

    public static function issetor(&$var, $default = false) {
        return isset($var) ? $var : $default;
    }

    public static function num_notificaciones() {
    	$query = "SELECT count(id) AS total
                  FROM notificaciones
                  WHERE tipo = 'cambio de correo'";
        $results = DB::select(DB::raw($query));
        return $results[0]->total;
    }

	public static function num_extractos() {
    	$query = "SELECT count(id) AS total
                  FROM notificaciones
                  WHERE tipo = 'nuevo extracto'";
        $results = DB::select(DB::raw($query));
        return $results[0]->total;
    }
} 