<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use Input, DB, View, Response, Validator, Redirect, Auth;

class ProfesoresController extends Controller {

    private $table;

    function __construct(){
        $this->table = "profesores_cab";
    }

    public function index(){

        $searchStr = Input::get('search', '');
        $items_per_page = Input::get('items', 10);

        // Al ser una subvista, el paginador de la tabla necesita una url base para funcionar correctamente
        $profesores = $this->getList($searchStr, $items_per_page);

        // Genero la vista principal de profesores pasandole una subvista con la tabla de datos
        return View::make('profesores.index', array('search' => $searchStr,
                                                    'items' => $items_per_page))
            -> nest('datatable', 'profesores.subviews.datatable', array('profesores' => $profesores,
                                                                        'search' => $searchStr,
                                                                        'items' => $items_per_page));

    }

    public function form($id = null){

        $centros = DB::table('centros_cab')->get();
        $cuerpos = DB::table('cuerpos_tip')->get();
        $administraciones = DB::table('administracion_tip')->get();

        $selectCentros = array();
        $selectCuerpos = array();
        $selectAdministraciones = array();

        foreach($centros as $centro){
            $selectCentros[$centro->CODIGO] = $centro->CENTRO;
        }
        $selectCentros[null] = "(vacío)";

        foreach($cuerpos as $cuerpo){
            $selectCuerpos[$cuerpo->CODIGO] = $cuerpo->CUERPO;
        }
        $selectCuerpos[null] = "(vacío)";

        foreach ($administraciones as $administracion) {
            $selectAdministraciones[$administracion->CODIGO] = $administracion->ADMINISTRACION;
        }
        $selectAdministraciones[null] = "(vacío)";


        // Contrucción del array de datos necesarios para la vista
        $data = array(
            'centros' => $selectCentros,
            'cuerpos' => $selectCuerpos,
            'administraciones' => $selectAdministraciones,
        );
        // Si el argumento $id existe enviaré también al profesor y cargaré la vista de formulario de edición
        if($id){
            $profesor = DB::table('profesores_cab')->where('CODIGO', $id)->get();
            $data['profesor'] = $profesor[0];

            return View::make('profesores.profesores_form_edit', $data);
        }

        return View::make('profesores.profesores_form', $data);

    }

    public function save(){

        $rules = array(
            'DNI' => 'required|unique:profesores_cab,DNI|regex:[^\d{8}[-][A-Z]{1}$]',
            'EXPERIENCIA' => 'nullable|regex:[^\d{4}$]',
        );

        $messages = array(
            'required' => 'El campo :attribute es obligatorio.',
            'unique'  => 'El campo :attribute ya existe en la base de datos.',
            'regex' => 'El formato del campo :attribute es incorrecto.',
        );

        $validator = Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails()) {

            return Redirect::back()
                ->withErrors($validator)
                ->withInput(Input::all());

        } else {

            $input = Input::all();

            if (isset($input['VERIFICADO'])) {
                $input['VERIFICADO'] = 1;
            } else {
                $input['VERIFICADO'] = 0;
            }
            if (isset($input['BAJA'])) {
                $input['BAJA'] = 0;
            } else {
                $input['BAJA'] = 1;
            }

            //$input['BAJA'] = 1;

            $profesor = $input;
            unset($profesor['_token']);

            DB::table('profesores_cab')->insert($profesor);

            return Redirect::to('profesores');

        }
    }

    public function edit(){
        $rules = array(
            'DNI' => 'required|regex:[^\d{8}[-][A-Z]{1}$]',
            'EXPERIENCIA' => 'nullable|regex:[^\d{4}$]',
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

            if (isset($input['VERIFICADO'])) {
                $input['VERIFICADO'] = 1;
            } else {
                $input['VERIFICADO'] = 0;
            }
            if (isset($input['BAJA'])) {
                $input['BAJA'] = 0;
            } else {
                $input['BAJA'] = 1;
            }

            $profesor = $input;
            unset($profesor['_token']);

            DB::table('profesores_cab')->where('CODIGO', $profesor['CODIGO'])->update($profesor);

            return Redirect::to('profesores');

        }

    }

    public function search(){
        // Obtengo la cadena del buscador
        $searchStr = Input::get('searchStr');
        $items_per_page = Input::get('items_per_page');
        
        $profesores = $this->getList($searchStr, $items_per_page);

        // Renderizo la vista con los datos necesarios
        $view = View::make('profesores.subviews.datatable', array('profesores' => $profesores, 'search' => $searchStr, 'items' => $items_per_page));
        // Y la devuelvo
        return Response::make($view);
    }

    // La función devuelve un paginador con los profesores
    private function getList($searchStr, $items_per_page){

        // Si la cadena no está vacia, obtengo los registros cobn alguna coincidencia
        if($searchStr != ''){
            $query = "SELECT * FROM ".$this->table." WHERE CODIGO = '".$searchStr."' OR
                                                         DNI LIKE '%".$searchStr."%' OR
                                                         NOMBRE LIKE '%".$searchStr."%' OR
                                                         APELLIDO1 LIKE '%".$searchStr."%' OR
                                                         APELLIDO2 LIKE '%".$searchStr."%'";
        }else{
            $query = "SELECT * FROM profesores_cab";
        }

        // Ejecuto la consulta
        $results = DB::select( DB::raw($query) );

        // Obtengo el paginador a mano
        // NOTA: Parece ser que el método Paginator::make(...) tiene un bug y por ello tengo que hacer el array_slice(...)
        $pageNumber = Input::get('page', 1);
        $slice = array_slice($results, $items_per_page * ($pageNumber - 1), $items_per_page);
        
        // crear paginador con el trozo a mostrar
        $profesores = new LengthAwarePaginator($slice, count($results), $items_per_page, $pageNumber);

        // Al ser una subvista, el paginador de la tabla necesita una url base para funcionar correctamente
        $profesores->withPath("profesores");

        return $profesores;
    }

    /****************************
     * Métodos estáticos
     */

    // Comprueba si existe un ponente por el DNI, si existe, devuelve su ID, si no, devuelve null
    public static function exist($dni){

        // Comprueba si existe
        $profesor = DB::table('profesores_cab')->where('DNI', $dni)->get();
        if(count($profesor) > 0){
            return $profesor[0]->CODIGO;
        }
        return null;

    }

    public function delete($idProfesor){
        $query_delete = "DELETE FROM profesores_cab WHERE CODIGO = ".$idProfesor;
        if(DB::delete(DB::raw($query_delete))){
            return Redirect::back();
        }
        return "No se pudo borrar el profesor";
    }

    public function email() {
        $email_emisor = Auth::user()->email;
        $query_profesores = "SELECT profesores_cab.MAIL
                  FROM profesores_cab
                  WHERE profesores_cab.MAIL IS NOT NULL";
        $profesores = DB::select(DB::raw($query_profesores));

        $emails_profesores = "";
        foreach ($profesores as $profesor) {
            if (filter_var($profesor->MAIL, FILTER_VALIDATE_EMAIL)) {
                $emails_profesores = $emails_profesores.$profesor->MAIL.", ";
            }
        }
        if($emails_profesores != "") {
            $emails_profesores = substr($emails_profesores, 0, -2); // eliminamos el ultimo ", "
        }

        $datos = [
            'emisor' => $email_emisor,
            'receptor' => $emails_profesores,
        ];

        return View::make('profesores.profesores_form_mail', $datos);
    }

    public function enviarEmail()
    {
        $emisor = Input::get('de', '');
        $receptor = Input::get('direciones', '');
        $asunto = Input::get('asunto', '');
        $mensaje = Input::get('mensaje', '');

        // Si hay líneas de más de 70 caracteres, se usa wordwrap()
        $mensajew = wordwrap($mensaje, 70, "\r\n");

        $datos = [
            'emisor' => $emisor,
            'receptor' => $receptor,
            'asunto' => $asunto,
            'mensaje' => $mensaje
        ];
        $headers = "From:" . $emisor;
        if (mail($receptor,$asunto,$mensajew, $headers)) {
            $datos['status'] = 'Correo aceptado para su envío.';
            return back()->with($datos);
        } else {
            return back()->with($datos)->withErrors('No pudo ser enviado.');
        }
    }

}