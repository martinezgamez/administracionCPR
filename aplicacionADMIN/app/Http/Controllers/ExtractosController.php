<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use DB, View, Input, Response, Redirect;

class ExtractosController extends Controller
{
	private $table;

    function __construct() {
        $this->table = "extractos_cab";
    }

    public function index() {
    	$searchStr = Input::get('search', '');
        $items_per_page = Input::get('items', 10);

		$extractos = $this->getExtractos($searchStr, $items_per_page);

        return View::make('extractos.index', array('search' => $searchStr, 'items' => $items_per_page)) -> nest('datatable', 'extractos.subviews.datatable', array('extractos' => $extractos, 'search' => $searchStr, 'items' => $items_per_page));
    }

    public function search() {
        // Obtengo la cadena del buscador
        $searchStr = Input::get('searchStr');
        $items_per_page = Input::get('items_per_page');

        $extractos = $this->getExtractos($searchStr, $items_per_page);

        // Renderizo la vista con los datos necesarios
        $view = View::make('extractos.subviews.datatable', array('extractos' => $extractos, 'search' => $searchStr, 'items' => $items_per_page));

        // Y la devuelvo
        return Response::make($view);
    }

	public function delete($idNotificacion) {
    	$query = "DELETE FROM notificaciones WHERE id = ".$idNotificacion;
        if(DB::delete(DB::raw($query))) {
            return Redirect::back();
        }
        return "Error al eliminar la notificación";
    }


    // La función devuelve un paginador con las notificaciones de extractos
    private function getExtractos($searchStr, $items_per_page) {
        // obtengo las notificaciones de extractos nuevos
        $query = "SELECT id, fecha, informacion
                  FROM notificaciones
                  WHERE tipo = 'nuevo extracto'
                  ORDER BY fecha";
        // Ejecuto la consulta
        $notificaciones = DB::select( DB::raw($query));

        $results = [];
        if (count($notificaciones) > 0) {
        	// los codigos de los extractos estan guardados en la columna 'informacion' de la tabla de notificaciones
        	// obtengo todos los codigos de los extractos de la columna 'informacion' en una variable
        	$codigos = "";
        	foreach ($notificaciones as $fila) {
        		$codigos = $codigos.$fila->informacion.",";
        	}
        	$codigos = rtrim($codigos,','); // elimino la ultima coma

        	if($searchStr != '') { // si hay una busqueda
        		// se obtienen los datos de profesores que esten en los extractos
        		// y los extractos cuyo codigos coincidan con los codigos extraidos de las notificaciones
        		$query = "SELECT e.CODIGO, p.NOMBRE, p.APELLIDO1, p.APELLIDO2, p.DNI, p.MOVIL, p.MAIL
                  		FROM profesores_cab p
                  		INNER JOIN extractos_cab e ON e.PROFESOR = p.CODIGO
                  		WHERE e.CODIGO IN (".$codigos.") AND
                  			(p.NOMBRE LIKE '%".$searchStr."%' OR
                            p.APELLIDO1 LIKE '%".$searchStr."%' OR
                            p.APELLIDO2 LIKE '%".$searchStr."%' OR
                            p.DNI LIKE '%".$searchStr."%' OR
                            p.MOVIL LIKE '%".$searchStr."%' OR
                            p.MAIL LIKE '%".$searchStr."%')
                  		ORDER BY e.CODIGO";
            } else {
            	// se obtienen los datos de profesores que esten en los extractos
        		// y los extractos cuyo codigos coincidan con los codigos extraidos de las notificaciones
            	$query = "SELECT e.CODIGO, p.NOMBRE, p.APELLIDO1, p.APELLIDO2, p.DNI, p.MOVIL, p.MAIL
                  		FROM profesores_cab p
                  		INNER JOIN extractos_cab e ON e.PROFESOR = p.CODIGO
                  		WHERE e.CODIGO IN (".$codigos.")
                  		ORDER BY e.CODIGO";
			}
        	$results = DB::select( DB::raw($query));

        	// incluir los datos de las notificaciones en el resultado
        	foreach ($results as $coleccion) {
        		foreach ($notificaciones as $fila) {
        			if($coleccion->CODIGO == $fila->informacion) {
		        		$coleccion->NOTIFICACION = $fila;
        			}
        		}
        	}
        }

		// Obtengo el paginador
        $pageNumber = Input::get('page', 1);
        $slice = array_slice($results, $items_per_page * ($pageNumber - 1), $items_per_page);

        $extractos = new LengthAwarePaginator($slice, count($results), $items_per_page, $pageNumber);

        // Al ser una subvista, el paginador de la tabla necesita una url base para funcionar correctamente
        $extractos->withPath("extractos");

        return $extractos;
    }
}
