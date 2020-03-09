<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use Input, DB, View, Response, Validator, Redirect;

class PonentesController extends Controller {

    private $table;

    function __construct(){
        $this->table = "ponentes_cab";
    }

    public function index(){

        $searchStr = Input::get('search', '');
        $items_per_page = Input::get('items', 10);

        $ponentes = $this->getList($searchStr, $items_per_page);

        // Genero la vista proncipal de profesores pasandole una subvista con la tabla de datos
        return View::make('ponentes.index', array('search' => $searchStr, 'items' => $items_per_page)) -> nest('datatable', 'ponentes.subviews.datatable', array('ponentes' => $ponentes, 'search' => $searchStr, 'items' => $items_per_page));

    }

    public function form($id = null){

        $data = array();

        // Si el argumento $id existe enviaré también al profesor y cargaré la vista de formulario de edición
        if($id){
            $ponente = DB::table($this->table)->where('CODIGO', $id)->get();
            $data['ponente'] = $ponente[0];

            return View::make('ponentes.ponentes_form_edit', $data);
        }

        return View::make('ponentes.ponentes_form');

    }

    public function save(){

        $rules = array(
            'DNI' => 'required|unique:ponentes_cab,DNI|regex:[\d{8}[-][A-Z]{1}]',
        );

        $messages = array(
            'required' => 'El campo :attribute es obligatorio.',
            'unique'  => 'El campo :attribute ya existe en la base de datos.',
            'regex' => 'El formato del campo :attribute es incorrecto',
        );

        $validator = Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails()) {

            return Redirect::back()
                ->withErrors($validator)
                ->withInput(Input::all());

        } else {

            $input = Input::all();

            if (isset($input['BAJA'])) {
                $input['BAJA'] = 1;
            } else {
                $input['BAJA'] = 0;
            }

            if (isset($input['PUBLICO'])) {
                $input['PUBLICO'] = 1;
            } else {
                $input['PUBLICO'] = 0;
            }

            $ponente = $input;
            unset($ponente['_token']);

            DB::table($this->table)->insert($ponente);

            return Redirect::to('ponentes');
        }

    }

    public function edit(){

        $rules = array(
            'DNI' => 'required|regex:[\d{8}[-][A-Z]{1}]'
        );

        $messages = array(
            'required' => 'El campo :attribute es obligatorio.',
            'unique'  => 'El campo :attribute ya existe en la base de datos.',
            'regex' => 'El formato del campo :attribute es incorrecto',
        );

        $validator = Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails()) {

            return Redirect::back()
                ->withErrors($validator)
                ->withInput(Input::all());

        } else {

            $input = Input::all();

            if (isset($input['BAJA'])) {
                $input['BAJA'] = 1;
            } else {
                $input['BAJA'] = 0;
            }

            if (isset($input['PUBLICO'])) {
                $input['PUBLICO'] = 1;
            } else {
                $input['PUBLICO'] = 0;
            }

            $ponente = $input;
            unset($ponente['_token']);

            DB::table($this->table)->where('CODIGO', $ponente['CODIGO'])->update($ponente);

            return Redirect::to('ponentes');
        }

    }

    public function search(){
        // Obtengo la cadena del buscador
        $searchStr = Input::get('searchStr');
        $items_per_page = Input::get('items_per_page');

        $ponentes = $this->getList($searchStr, $items_per_page);

        // Renderizo la vista con los datos necesarios
        $view = View::make('ponentes.subviews.datatable', array('ponentes' => $ponentes, 'search' => $searchStr, 'items' => $items_per_page));
        // Y la devuelvo
        return Response::make($view);

    }

    // La función devuelve un paginador con los ponentes
    private function getList($searchStr, $items_per_page){

        // Si la cadena no está vacia, obtengo los registros cobn alguna coincidencia
        if($searchStr != ''){
            $query = "SELECT * FROM ".$this->table." WHERE CODIGO = '".$searchStr."' OR
                                                         DNI LIKE '%".$searchStr."%' OR
                                                         NOMBRE LIKE '%".$searchStr."%' OR
                                                         APELLIDO1 LIKE '%".$searchStr."%' OR
                                                         APELLIDO2 LIKE '%".$searchStr."%'";
        }else{
            $query = "SELECT * FROM ponentes_cab";
        }

        // Ejecuto la consulta
        $results = DB::select( DB::raw($query) );

        // Obtengo el paginador a mano
        // NOTA: Parece ser que el método Paginator::make(...) tiene un bug y por ello tengo que hacer el array_slice(...)
        $pageNumber = Input::get('page', 1);
        $slice = array_slice($results, $items_per_page * ($pageNumber - 1), $items_per_page);

        // crear paginador con el trozo a mostrar
        $ponentes = new LengthAwarePaginator($slice, count($results), $items_per_page, $pageNumber);

        // Al ser una subvista, el paginador de la tabla necesita una url base para funcionar correctamente
        $ponentes->withPath("ponentes");

        return $ponentes;
    }

    /****************************
     * Métodos estáticos
     */

    // Comprueba si existe un ponente por el DNI, si existe, devuelve su ID, si no, devuelve null
    public static function exist($dni){

        // Comprueba si existe
        $ponente = DB::table('ponentes_cab')->where('DNI', $dni)->get();
        if(count($ponente) > 0){
            return $ponente[0]->CODIGO;
        }
        return null;

    }

}