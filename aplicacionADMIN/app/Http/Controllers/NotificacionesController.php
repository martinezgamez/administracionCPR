<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use DB, View, Input, Response, Redirect;

class NotificacionesController extends Controller
{
    private $table;

    function __construct(){
        $this->table = "notificaciones";
    }

    public function index(){
    	$searchStr = Input::get('search', '');
        $items_per_page = Input::get('items', 10);

		$notificaciones = $this->getNotificaciones($searchStr, $items_per_page);

        return View::make('notificaciones.index', array('search' => $searchStr, 'items' => $items_per_page)) -> nest('datatable', 'notificaciones.subviews.datatable', array('notificaciones' => $notificaciones, 'search' => $searchStr, 'items' => $items_per_page));
    }

	public function search(){
        // Obtengo la cadena del buscador
        $searchStr = Input::get('searchStr');
        $items_per_page = Input::get('items_per_page');

        $notificaciones = $this->getNotificaciones($searchStr, $items_per_page);

        // Renderizo la vista con los datos necesarios
        $view = View::make('notificaciones.subviews.datatable', array('notificaciones' => $notificaciones, 'search' => $searchStr, 'items' => $items_per_page));

        // Y la devuelvo
        return Response::make($view);
    }

    public function delete($idNotificacion){
    	$query = "DELETE FROM notificaciones WHERE id = ".$idNotificacion;
        if(DB::delete(DB::raw($query))) {
            return Redirect::back();
        }
        return "Error al eliminar la notificación";
    }

    // La función devuelve un paginador con las notificaciones
    private function getNotificaciones($searchStr, $items_per_page){
		// Si la cadena no está vacia, obtengo los registros con alguna coincidencia
        if ($searchStr != ''){
        	$query = "SELECT id, fecha, informacion
                  		FROM notificaciones
                  		WHERE tipo = 'cambio de correo' 
                  				AND informacion LIKE '%".$searchStr."%'
                  		ORDER BY fecha";            
        } else {
        	$query = "SELECT id, fecha, informacion
                  FROM notificaciones
                  WHERE tipo = 'cambio de correo'
                  ORDER BY fecha";
        }
        // Ejecuto la consulta
        $results = DB::select( DB::raw($query));

        // Obtengo el paginador
        $pageNumber = Input::get('page', 1);
        $slice = array_slice($results, $items_per_page * ($pageNumber - 1), $items_per_page);
        
        $notificaciones = new LengthAwarePaginator($slice, count($results), $items_per_page, $pageNumber);

        // Al ser una subvista, el paginador de la tabla necesita una url base para funcionar correctamente
        $notificaciones->withPath("notificaciones");

        return $notificaciones;
    }
}
