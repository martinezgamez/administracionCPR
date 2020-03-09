<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use Input, DB, View, Response, Redirect, Validator, Auth, Request;

class CursosController extends Controller{

    private $table;

    function __construct(){
        $this->table = "cursos_cab";
    }

    public function index(){

        $searchStr = Input::get('search', '');
        $items_per_page = Input::get('items', 10);

        // Al ser una subvista, el paginador de la tabla necesita una url base para funcionar correctamente
        $cursos = $this->getList($searchStr, $items_per_page);

        return View::make('cursos.index', array('search' => $searchStr, 'items' => $items_per_page)) -> nest('datatable', 'cursos.subviews.datatable', array('cursos' => $cursos, 'search' => $searchStr, 'items' => $items_per_page));

    }

    public function search(){

        // Obtengo la cadena del buscador
        $searchStr = Input::get('searchStr');
        $items_per_page = Input::get('items_per_page');

        $cursos = $this->getList($searchStr, $items_per_page);

        // Renderizo la vista con los datos necesarios
        $view = View::make('cursos.subviews.datatable', array('cursos' => $cursos, 'search' => $searchStr, 'items' => $items_per_page));

        // Y la devuelvo
        return Response::make($view);

    }

    public function createCurso(){

        $query_datos = "INSERT INTO cursos_cab (NOMBRE) VALUES ('Nuevo curso')";

        if(DB::insert(DB::raw($query_datos))){
            $id = DB::getPdo()->lastInsertId();
            $query_detalles = "INSERT INTO cursos_dat (CURSO) VALUES (".$id.")";
            if(DB::insert(DB::raw($query_detalles))){
                $query_dir = "INSERT INTO cursos_dir (CURSO) VALUES (".$id.")";
                if(DB::insert(DB::raw($query_dir))){
                    //return Redirect::to('cursos');
                    return Redirect::back();
                }
            }
        }
        return "Error al crear curso";
    }

    public function delete($idCurso){

        $query = "DELETE FROM cursos_cab WHERE CODIGO = ".$idCurso;
        if(DB::delete(DB::raw($query))){
            //return Redirect::to("cursos");
            return Redirect::back();
        }
        return "Error al eliminar el curso";

    }

    // Datos
    public function cursoDatos($id){

        // Datos para formulario
        $niveles = DB::table('cursos_niv')->get();
        $nivelesList = array();
        foreach($niveles as $nivel){
            $nivelesList[$nivel->CODIGO] = $nivel->NIVEL;
        }
        $nivelesList[null] = "(vacío)";

        // Datos básicos del curso
        $tmp = DB::table('cursos_cab')->where('CODIGO', $id)->get();
        $datos = $tmp[0];

        // "Dirigido a" en datos básicos (Una gilipollez de el que diseño esta BD)
        $tmp = DB::table('cursos_dir')->where('CURSO', $id)->get();
        $dirigido_a = "";
        if(count($tmp) > 0){
            $dirigido_a = $tmp[0];
        }

        // Array de datos con los argumento que pasarán a la vista
        // Contrucción del array de datos necesarios para la vista
        $data = array(
            'idCurso' => $id,
            'nombreCurso' => $datos->NOMBRE,
            'niveles' => $nivelesList,
            'datos' => $datos,
            'dirigido_a' => $dirigido_a,
        );

        return View::make("cursos.cursos_form_datos", $data);
    }

    public function editDatos(){
        $input = Input::all();

        $rules = array(
            'PLAZAS' => 'regex:[^\d*$]',
            'DURACION' => 'regex:[^\d*$]',
            'PRESENCIAL' => 'regex:[^\d*$]',
        );
        $messages = array(
            'regex' => 'El formato del campo :attribute es incorrecto',
        );

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput($input);
        }

        $datos = $input;
        $dirigido_a = $datos['DESTINO'];
        $idCurso = $datos['CODIGO'];

        unset($datos['CODIGO']);
        unset($datos['DESTINO']);
        unset($datos['_token']);

        DB::table('cursos_cab')->where("CODIGO", $idCurso)->update($datos);

        // el campo 'DESTINO' de la tabla 'cursos_dir' no puede ser null. Si es null, lo dejamos en una cadena vacia.
        if(!isset($dirigido_a)) {
            $dirigido_a = '';
        }

        DB::table('cursos_dir')->where("CURSO", $idCurso)->update(["DESTINO" => $dirigido_a]);

        return Redirect::to('cursos/'.$idCurso.'/datos');
    }

    public function editDetalles(){

        $input = Input::all();

        $datos = $input;
        $idCurso = $datos['CURSO'];

        unset($datos['CURSO']);
        unset($datos['_token']);

        DB::table('cursos_dat')
            ->where("CURSO", $idCurso)
            ->update($datos);

        return Redirect::to('cursos/'.$idCurso.'/detalles');

    }

    // Detalles
    public function cursoDetalles($id){

        $tmp = DB::table('cursos_dat')->where('CURSO', $id)->get();
        
        $detalles = null;
        if(isset($tmp[0])){
            $detalles = $tmp[0];
        }

        // Para coger el nombre del curso
        $tmp = DB::table('cursos_cab')->select('NOMBRE')->where('CODIGO', $id)->get();
        $nombreCurso = $tmp[0]->NOMBRE;

        $data = array(
            'idCurso' => $id,
            'nombreCurso' => $nombreCurso,
            'detalles' => $detalles,
        );

        return View::make("cursos.cursos_form_detalles", $data);

    }

    /***************
     * Ponentes
     ***************/
    public function cursoPonentes($id){

        // Para coger el nombre del curso
        $tmp = DB::table('cursos_cab')->select('NOMBRE')->where('CODIGO', $id)->get();
        $nombreCurso = $tmp[0]->NOMBRE;

        // Ponentes
        $ponentes = $this->getPonentesCompleto($id);

        $data = array(
            'idCurso' => $id,
            'nombreCurso' => $nombreCurso,
            'ponentes' => $ponentes,
        );

        return View::make("cursos.cursos_form_ponentes", $data);
    }

    public function deletePonente($idCurso, $ponente){

        $query = "DELETE FROM cursos_pon WHERE CURSO = ".$idCurso." AND PONENTE = ".$ponente;
        $resultado = DB::delete(DB::raw($query));
        if($resultado == 1){
            return Redirect::to("cursos/".$idCurso."/ponentes/");
        }else{
            return "Error al eliminar al ponente";
        }
    }

    public function cursoPonentesPresupuesto($id){

        // Para coger el nombre del curso
        $tmp = DB::table('cursos_cab')->select('NOMBRE')->where('CODIGO', $id)->get();
        $nombreCurso = $tmp[0]->NOMBRE;

        // Ponentes
        $ponentes = $this->getPonentesCompleto($id);

        $data = array(
            'idCurso' => $id,
            'nombreCurso' => $nombreCurso,
            'ponentes' => $ponentes,
        );

        return View::make("cursos.cursos_form_ponentes_presupuesto", $data);
    }

    public function cursoPonentesGastos($id, $numPonente = null){

        // Para coger el nombre del curso
        $tmp = DB::table('cursos_cab')->select('NOMBRE')->where('CODIGO', $id)->get();
        $nombreCurso = $tmp[0]->NOMBRE;

        $ponentes = $this->getPonentes($id);

        $data = array(
            'idCurso' => $id,
            'nombreCurso' => $nombreCurso,
            'ponentes' => $ponentes,
        );

        if($numPonente){

            $data['dietas'] = $this->getGastosDietasPonente($id, $numPonente);
            $data['manutencion'] = $this->getGastosManutencionPonente($id, $numPonente);
            $data['locomocion'] = $this->getGastosLocomocionPonente($id, $numPonente);

            $transportes = DB::table("transportes_tip")->get();
            $transportesList = array();
            foreach($transportes as $transporte){
                $transportesList[$transporte->CODIGO] = $transporte->TRANSPORTE;
            }

            $data["transportes"] = $transportesList;
            $data['numPonente'] = $numPonente;

        }

        return View::make("cursos.cursos_form_ponentes_gastos", $data);
    }

    public function addPonente(){

        $input = Input::all();
        $idCurso = $input['idCurso'];
        $dniPonente = $input['dniPonente'];

        $idPonente = PonentesController::exist($dniPonente);

        if($idPonente != null){
            if(!$this->existPonente($idCurso, $idPonente)){
                $numero = $this->getNextPonenteNumberInCurso($idCurso);

                $query = "INSERT INTO cursos_pon (CURSO, PONENTE, COD_PON, PRECIO, IRPF) VALUES (".$idCurso.", ".$numero.", ".$idPonente.", 60, 10.5)";
                if(DB::insert(DB::raw($query))){
                    return Redirect::to("/cursos/".$idCurso."/ponentes");
                }
            }
        }
        return Redirect::to("/cursos/".$idCurso."/ponentes");

    }

    public function editPonente($idCurso, $ponente){

        $input = Input::all();
        $fieldName = $input['fieldName'];
        $value = $input['value'];

        if(DB::table('cursos_pon')
            ->where('CURSO', $idCurso)
            ->where('PONENTE', $ponente)
            ->update([$fieldName => $value])){
            return "Se ha modificado con éxito";
        }
        return "Error a modificar el registro";

    }

    // Dietas
    public function editDieta($idCurso, $numPonente, $numDieta){

        $input = Input::all();
        $fieldName = $input['fieldName'];
        $value = $input['value'];

        $update = "UPDATE cursos_pon_die
                   SET ".$fieldName." = '".$value."'
                   WHERE CURSO = ".$idCurso." AND PONENTE = '".$numPonente."' AND NUMERO = '".$numDieta."'";
        if(DB::update(DB::raw($update))){
            return "Datos insertados correctamente.";
        }
        return "Error a modificar el registro";

    }

    public function addDieta($idCurso, $numPonente){

        $numDieta = $this->getNextDietaNumberInPonente($idCurso, $numPonente);
        $query = "INSERT INTO cursos_pon_die (CURSO, PONENTE, NUMERO) VALUES (".$idCurso.", ".$numPonente.", ".$numDieta.")";
        if(DB::insert(DB::raw($query))){
            return "Datos insertados correctamente";
        }
        return "Error al insertar los datos";
    }

    public function deleteDieta($idCurso, $numPonente, $numDieta){

        $delete = "DELETE FROM cursos_pon_die
                   WHERE CURSO = '".$idCurso."' AND PONENTE = '".$numPonente."' AND NUMERO = '".$numDieta."'";

        if(DB::delete(DB::raw($delete))){
            return "Datos eleminados correctamente";
        }
        return "Error al eliminar los datos";
    }
    // Fin dietas
    // Manutencion
    public function editManutencion($idCurso, $numPonente, $numManutencion){

        $input = Input::all();
        $fieldName = $input['fieldName'];
        $value = $input['value'];

        $update = "UPDATE cursos_pon_man
                   SET ".$fieldName." = '".$value."'
                   WHERE CURSO = ".$idCurso." AND PONENTE = '".$numPonente."' AND NUMERO = '".$numManutencion."'";
        if(DB::update(DB::raw($update))){
            return "Datos insertados correctamente.";
        }
        return "Error a modificar el registro";

    }

    public function addManutencion($idCurso, $numPonente){

        $numManutencion = $this->getNextManutencionNumberInPonente($idCurso, $numPonente);
        $query = "INSERT INTO cursos_pon_man (CURSO, PONENTE, NUMERO) VALUES (".$idCurso.", ".$numPonente.", ".$numManutencion.")";
        if(DB::insert(DB::raw($query))){
            return "Datos insertados correctamente";
        }
        return "Error al insertar los datos";
    }

    public function deleteManutencion($idCurso, $numPonente, $numManutencion){

        $delete = "DELETE FROM cursos_pon_man
                   WHERE CURSO = '".$idCurso."' AND PONENTE = '".$numPonente."' AND NUMERO = '".$numManutencion."'";

        if(DB::delete(DB::raw($delete))){
            return "Datos eleminados correctamente";
        }
        return "Error al eliminar los datos";
    }
    // Fin manutencion
    // Locomocion
    public function editLocomocion($idCurso, $numPonente, $numLocomocion){

        $input = Input::all();
        $fieldName = $input['fieldName'];
        $value = $input['value'];

        $update = "UPDATE cursos_pon_iti
                   SET ".$fieldName." = '".$value."'
                   WHERE CURSO = ".$idCurso." AND PONENTE = '".$numPonente."' AND NUMERO = '".$numLocomocion."'";
        if(DB::update(DB::raw($update))){
            return "Datos insertados correctamente.";
        }
        return "Error a modificar el registro";

    }

    public function addLocomocion($idCurso, $numPonente){

        $numLocomocion = $this->getNextLocomocionNumberInPonente($idCurso, $numPonente);
        $query = "INSERT INTO cursos_pon_iti (CURSO, PONENTE, NUMERO) VALUES (".$idCurso.", ".$numPonente.", ".$numLocomocion.")";
        if(DB::insert(DB::raw($query))){
            return "Datos insertados correctamente";
        }
        return "Error al insertar los datos";
    }

    public function deleteLocomocion($idCurso, $numPonente, $numLocomocion){

        $delete = "DELETE FROM cursos_pon_iti
                   WHERE CURSO = '".$idCurso."' AND PONENTE = '".$numPonente."' AND NUMERO = '".$numLocomocion."'";

        if(DB::delete(DB::raw($delete))){
            return "Datos eleminados correctamente";
        }
        return "Error al eliminar los datos";
    }
    // Fin locomocion
    /***************
     * Fin ponentes
     ***************/

    // Solicitudes
    public function cursoSolicitudes($id){

        // Para coger el nombre del curso
        $tmp = DB::table('cursos_cab')->select('NOMBRE')->where('CODIGO', $id)->get();
        $nombreCurso = $tmp[0]->NOMBRE;

        $data = array(
            'idCurso' => $id,
            'nombreCurso' => $nombreCurso,
            'solicitudes' => $this->getSolicitudes($id),
            'admitidos' => $this->getSolicitudesAdmitidas($id),
            'excluidos' => $this->getSolicitudesExcluidas($id),
        );

        return View::make("cursos.cursos_form_solicitudes", $data);

    }

    public function addSolicitud(){

        $input = Input::all();
        $idCurso = $input['idCurso'];
        $dniProfesor = $input['dniProfesor'];

        $idProfesor = ProfesoresController::exist($dniProfesor);

        if($idProfesor != null) {
            if(!$this->existSolicitud($idCurso, $idProfesor)){
                $date = date("Y-m-d");
                $query = "INSERT INTO cursos_sol (CURSO, PROFESOR, FECHA) VALUES (" . $idCurso . ", " . $idProfesor . ", '" . $date . "')";
                if (DB::insert(DB::raw($query))) {
                    return Redirect::to("/cursos/".$idCurso."/solicitudes");
                }
            }
        }
        return Redirect::to("/cursos/".$idCurso."/solicitudes");

    }

    //Todo - recopilar todos los ids de profesores que solicitan entrar en el curso y llamar a addSolicitudes.
    public function  admitirSolicitudes(){

    }

    public function admitirSolicitud($idCurso, $idAlumno){

        // Añadir solicitud admitida a su tabla
        $this->addSolicitudAdmitida($idCurso, $idAlumno);

        return Redirect::to("/cursos/".$idCurso."/solicitudes");
    }

    public function deleteAdmitido($idCurso, $idAdmitido){

        $query = "DELETE FROM cursos_adm WHERE CURSO = ".$idCurso." AND PROFESOR = ".$idAdmitido;
        if(DB::delete(DB::raw($query))){
            return Redirect::to("cursos/".$idCurso."/solicitudes");
        }
        return "Error al borrar la solicitud admitida";
    }

    public function excluirSolicitud($idCurso, $idAlumno){

        // Añadir solicitud admitida a su tabla
        $this->addSolicitudExcluida($idCurso, $idAlumno);

        return Redirect::to("/cursos/".$idCurso."/solicitudes");
    }

    public function deleteExcluido($idCurso, $idExcluido){

        $query = "DELETE FROM cursos_exc WHERE CURSO = ".$idCurso." AND PROFESOR = ".$idExcluido;
        if(DB::delete(DB::raw($query))){
            return Redirect::to("cursos/".$idCurso."/solicitudes");
        }
        return "Error al borrar la solicitud excluida";
    }
    // Fin solicitudes

    // Materiales
    public function cursoMateriales($id){

        // Para coger el nombre del curso
        $tmp = DB::table('cursos_cab')->select('NOMBRE')->where('CODIGO', $id)->get();
        $nombreCurso = $tmp[0]->NOMBRE;

        // Materiales
        $materiales = $this->getMateriales($id);

        // Otros gastos
        $otros = $this->getOtrosGastos($id);

        $data = array(
            'idCurso' => $id,
            'nombreCurso' => $nombreCurso,
            'materiales' => $materiales,
            'otros' => $otros,
        );

        return View::make("cursos.cursos_form_materiales", $data);
    }

    // Añade un material nuevo al curso y obtiene la vista actualizada
    public function addMaterial(){

        $input = Input::all();
        $idCurso = $input['CURSO'];
        $cantidad = $input['CANTIDAD'];
        $precio = $input['PRECIO'];
        $concepto = $input['CONCEPTO'];
        $numero = $this->getNextMaterialNumberInCurso($idCurso);

        // comprobamos si se ha introducido algunas cantidades correctamente
        // si no, se inserta como 0
        if (!is_numeric($cantidad)) {
            $cantidad = 0;
        }
        if (!is_numeric($precio)) {
            $precio = 0;
        }

        $query = "INSERT INTO cursos_mat (CURSO, NUMERO, CONCEPTO, CANTIDAD, PRECIO) VALUES (".$idCurso.", ".$numero.", '".$concepto."', ".$cantidad.", '".$precio."')";
        if(DB::insert(DB::raw($query))){
            return Redirect::to("/cursos/".$idCurso."/materiales");
        }

        return false;

    }

    public function deleteMaterial($idCurso, $idMaterial){

        $query = "DELETE FROM cursos_mat WHERE CURSO = ".$idCurso." AND NUMERO = ".$idMaterial;
        if(DB::delete(DB::raw($query))){
           return Redirect::to("cursos/".$idCurso."/materiales");
        }
        return "Error al eliminar el material";
    }

    // Añade un material nuevo al curso y obtiene la vista actualizada
    public function addGasto(){

        $input = Input::all();
        $idCurso = $input['CURSO'];
        $precio = $input['PRECIO'];
        $concepto = $input['CONCEPTO'];
        $numero = $this->getNextGastoNumberInCurso($idCurso);

        // comprobamos si se ha introducido algunas cantidades correctamente
        // si no, se inserta como 0
        if (!is_numeric($precio)) {
            $precio = 0;
        }

        $query = "INSERT INTO cursos_oto (CURSO, NUMERO, CONCEPTO, PRECIO) VALUES (".$idCurso.", ".$numero.", '".$concepto."', '".$precio."')";
        if(DB::insert(DB::raw($query))){
            return Redirect::to("/cursos/".$idCurso."/materiales");
        }

        return false;

    }

    public function deleteGasto($idCurso, $idGasto){

        $query = "DELETE FROM cursos_oto WHERE CURSO = ".$idCurso." AND NUMERO = ".$idGasto;
        if(DB::delete(DB::raw($query))){
            return Redirect::to("cursos/".$idCurso."/materiales");
        }
        return "Error al eliminar el gasto";
    }
    // Fin materiales

    // Sesiones
    public function cursoAsistencia($id, $sesion = null){

        // Para coger el nombre del curso
        $tmp = DB::table('cursos_cab')->select('NOMBRE')->where('CODIGO', $id)->get();
        $nombreCurso = $tmp[0]->NOMBRE;

        // Sesiones
        $sesiones = $this->getSesiones($id);

        $data = array(
            'idCurso' => $id,
            'nombreCurso' => $nombreCurso,
            'sesiones' => $sesiones,
            'haveNoPresenciales' => $this->haveNoPresenciales($id),
        );

        // Listado de sesion
        if($sesion != null){
            $sesionAlumnos = $this->getSesion($id, $sesion);
            $data['sesionAlumnos'] = $sesionAlumnos;
            $data['isNoPresencial'] = true; // Para saber si estoy cargando una sesión no presencial
            if($sesion > 0){
                $data['isNoPresencial'] = false;
            }
            $data['sesionNum'] = $sesion;
        }

        return View::make("cursos.cursos_form_asistencia", $data);
    }

    public function editSesionAlumno($idCurso, $sesionNum){

        $input = Input::all();
        $fieldName = $input['fieldName'];
        $idProfesor = $input['idProfesor'];
        $value = $input['value'];

        if(DB::table('cursos_fal')->where('CURSO', $idCurso)
                               ->where('SESION', $sesionNum)
                               ->where('PROFESOR', $idProfesor)
                               ->update([$fieldName => $value])){
            return "Se ha modificado con éxito";
        }
        return "Error a modificar el registro";

    }

    public function noPresencialesForm(){

        $input = Input::all();

        $idCurso = $input['CURSO'];
        $horas = $input['HORAS'];
        $sesionNum = 0;
        $profesores = $this->getSolicitudesAdmitidas($idCurso);

        if($this->addSesion($idCurso, $sesionNum)){
            foreach($profesores as $profesor){
                $this->addSesionAlumno($idCurso, $sesionNum, $profesor->CODIGO, $horas);
            }
        }

        return Redirect::to("/cursos/".$idCurso."/asistencia/".$sesionNum);
    }

    public function sesionForm(){

        $input = Input::all();

        $idCurso = $input['CURSO'];
        $horas = $input['HORAS'];
        $fecha = $input['FECHA'];
        $sesionNum = $this->getNextSesionInCurso($idCurso);
        $profesores = $this->getSolicitudesAdmitidas($idCurso);

        if($this->addSesion($idCurso, $sesionNum, $fecha)){
            foreach($profesores as $profesor){
                $this->addSesionAlumno($idCurso, $sesionNum, $profesor->CODIGO, $horas);
            }
        }

        return Redirect::to("/cursos/".$idCurso."/asistencia/".$sesionNum);
    }

    // Fin sesiones

    /*********************************************
     * INSERCION DE DATOS
     *********************************************/

    //Todo - insertar todos los alumnos
    private function addSolicitudes($idCurso, $idAlumnos){

        $query = "INSERT INTO cursos_adm (CURSO, PROFESOR) VALUES (".$idCurso.", ".$idAlumnos.")";
        if(DB::insert(DB::raw($query))){
            return true;
        }
        return false;

    }

    private function addSolicitudAdmitida($idCurso, $idAlumno){

        $query = "INSERT INTO cursos_adm (CURSO, PROFESOR) VALUES (".$idCurso.", ".$idAlumno.")";
        if(DB::insert(DB::raw($query))){
            return true;
        }
        return false;

    }

    private function addSolicitudExcluida($idCurso, $idAlumno){

        $query = "INSERT INTO cursos_exc (CURSO, PROFESOR) VALUES (".$idCurso.", ".$idAlumno.")";
        if(DB::insert(DB::raw($query))){
            return true;
        }
        return false;

    }

    public function addSesion($idCurso, $sesionNum, $fecha = null){

        $query_cursos_dia = "INSERT INTO cursos_dia (CURSO, SESION, FECHA) VALUES (".$idCurso.", ".$sesionNum.", '".$fecha."')";
        if($fecha == null){
            $query_cursos_dia = "INSERT INTO cursos_dia (CURSO, SESION) VALUES (".$idCurso.", ".$sesionNum.")";
        }

        if(DB::insert(DB::raw($query_cursos_dia))){
            return true;
        }
        return false;
    }

    public function deleteSesion($idCurso, $sesion){

        $query = "DELETE FROM cursos_dia WHERE CURSO = ".$idCurso." AND SESION = ".$sesion;
        $query2 = "DELETE FROM cursos_fal WHERE CURSO = ".$idCurso." AND SESION = ".$sesion;
        if(DB::delete(DB::raw($query)) && DB::delete(DB::raw($query2))){
                return Redirect::to("cursos/" . $idCurso . "/asistencia");
        }
        return "Error al borrar la sesión";
    }

    public function addSesionAlumno($idCurso, $sesionNum, $idProfesor, $horas){

        $query_cursos_fal = "INSERT INTO cursos_fal (CURSO, SESION, PROFESOR, HORAS, JUSTIFICADAS) VALUES (".$idCurso.", ".$sesionNum.", ".$idProfesor.", ".$horas.", 0)";
        if(DB::insert(DB::raw($query_cursos_fal))){
            return true;
        }
        return false;
    }

    /*********************************************
     * VARIOS
     *********************************************/

    private function getNextPonenteNumberInCurso($idCurso){

        return DB::table('cursos_pon')->where('CURSO', $idCurso)->max('PONENTE') + 1;

    }

    private function getNextDietaNumberInPonente($idCurso, $idPonente){

        return DB::table('cursos_pon_die')->where('CURSO', $idCurso)->WHERE('PONENTE', $idPonente)->max('NUMERO') + 1;

    }

    private function getNextManutencionNumberInPonente($idCurso, $idPonente){

        return DB::table('cursos_pon_man')->where('CURSO', $idCurso)->WHERE('PONENTE', $idPonente)->max('NUMERO') + 1;

    }

    private function getNextLocomocionNumberInPonente($idCurso, $idPonente){

        return DB::table('cursos_pon_iti')->where('CURSO', $idCurso)->WHERE('PONENTE', $idPonente)->max('NUMERO') + 1;

    }

    private function getNextMaterialNumberInCurso($idCurso){

        return DB::table('cursos_mat')->where('CURSO', $idCurso)->max('NUMERO') + 1;

    }

    private function getNextGastoNumberInCurso($idCurso){

        return DB::table('cursos_oto')->where('CURSO', $idCurso)->max('NUMERO') + 1;

    }

    private function getNextSesionInCurso($idCurso){

        return DB::table('cursos_dia')->where('CURSO', $idCurso)->where('SESION', '<>', 0)->max('SESION') + 1;

    }

    // Comprueba si existe una solicitud
    public function existSolicitud($idCurso, $idAlumno){

        // Comprueba si existe
        $solicitud = DB::table('cursos_sol')->where('CURSO', $idCurso)->where('PROFESOR', $idAlumno)->get();
        if(count($solicitud) > 0){
            return true;
        }
        return false;

    }

    private function haveNoPresenciales($idCurso){

        $sesiones = DB::table('cursos_dia')->where('CURSO', $idCurso)->where('SESION', 0)->get();

        if(count($sesiones) > 0){
            return true;
        }
        return false;
    }

    // Comprueba si existe un ponente en este curso
    public function existPonente($idCurso, $idPonente){

        // Comprueba si existe
        $ponente = DB::table('cursos_pon')->where('CURSO', $idCurso)->where('COD_PON', $idPonente)->get();
        if(count($ponente) > 0){
            return true;
        }
        return false;

    }

    /*********************************************
     * OBTENCION DE DATOS
     *********************************************/

    // La función devuelve un paginador con los cursos
    private function getList($searchStr, $items_per_page){

        // Si la cadena no está vacia, obtengo los registros cobn alguna coincidencia
        if($searchStr != ''){
            $query = "SELECT *
                      FROM ".$this->table."
                      WHERE CODIGO = '".$searchStr."' OR
                            NOMBRE LIKE '%".$searchStr."%'
                      ORDER BY CODIGO DESC";
        }else{
            $query = "SELECT * FROM ".$this->table." ORDER BY CODIGO DESC";
        }

        // Ejecuto la consulta
        $results = DB::select( DB::raw($query) );

        // Obtengo el paginador a mano
        // NOTA: Parece ser que el método Paginator::make(...) tiene un bug y por ello tengo que hacer el array_slice(...)
        $pageNumber = Input::get('page', 1);
        $slice = array_slice($results, $items_per_page * ($pageNumber - 1), $items_per_page);
        
        $cursos = new LengthAwarePaginator($slice, count($results), $items_per_page, $pageNumber);

        // Al ser una subvista, el paginador de la tabla necesita una url base para funcionar correctamente
        $cursos->withPath("cursos");

        return $cursos;

    }

    // Función que devuelve el listado de ponentes de un curso con sus datos básicos
    private function getPonentes($idCurso){

        $query = "SELECT p.CODIGO, p.DNI, p.NOMBRE, p.APELLIDO1, p.APELLIDO2, cp.PONENTE
                  FROM cursos_pon cp
                  INNER JOIN ponentes_cab p ON cp.COD_PON = p.CODIGO
                  WHERE cp.CURSO = ".$idCurso;

        $ponentes = DB::select(DB::raw($query));

        //return Paginator::make($ponentes, count($ponentes), 5);
        return $ponentes;
    }

    // Función que devuelve el listado de ponentes de un curso con todas las columnas de la tabla ponentes_cab
    private function getPonentesCompleto($idCurso){

        $query = "SELECT p.CODIGO, p.DNI, p.NOMBRE, p.APELLIDO1, p.APELLIDO2, cp.*
                  FROM cursos_pon cp
                  INNER JOIN ponentes_cab p ON cp.COD_PON = p.CODIGO
                  WHERE cp.CURSO = ".$idCurso;

        $ponentes = DB::select(DB::raw($query));

        //return Paginator::make($ponentes, count($ponentes), 5);
        return $ponentes;
    }

    private function getGastosDietasPonente($idCurso, $numPonente){

        $query = "SELECT * FROM cursos_pon_die WHERE CURSO = ".$idCurso." AND PONENTE = ".$numPonente;

        $dietas = DB::select(DB::raw($query));

        return $dietas;
    }

    private function getGastosManutencionPonente($idCurso, $numPonente){

        $query = "SELECT * FROM cursos_pon_man WHERE CURSO = ".$idCurso." AND PONENTE = ".$numPonente;

        $manutencion = DB::select(DB::raw($query));

        return $manutencion;
    }

    private function getGastosLocomocionPonente($idCurso, $numPonente){

        $query = "SELECT * FROM cursos_pon_iti WHERE CURSO = ".$idCurso." AND PONENTE = ".$numPonente;

        $locomocion = DB::select(DB::raw($query));

        return $locomocion;
    }

    /*private function getPresupuestoPonente($idCurso, $numPonente){

        $query = "SELECT HORAS, PRECIO, IRPF, AVION, TREN, TAXI, BUS, COCHE, BARCO, OTROS, ALOJDIAS, ALOJPRECIO, DIETDIAS, DIETPRECIO
                  FROM cursos_pon
                  WHERE CURSO = ".$idCurso." AND PONENTE = ".$numPonente;

        $ponente = DB::select(DB::raw($query));
        return $ponente[0];
    }*/

    // Función que devuelve el listado de alumnos admitidos en un curso
    private function getSolicitudesAdmitidas($idCurso){

        $query = "SELECT profesores_cab.CODIGO, profesores_cab.DNI, profesores_cab.NOMBRE, profesores_cab.APELLIDO1, profesores_cab.APELLIDO2
                  FROM cursos_adm
                  INNER JOIN profesores_cab ON cursos_adm.PROFESOR = profesores_cab.CODIGO
                  WHERE CURSO = ".$idCurso;

        $admitidos = DB::select(DB::raw($query));

        return new LengthAwarePaginator($admitidos, count($admitidos), 10, 1);
        //return Paginator::make($admitidos, count($admitidos), 10);
    }

    // Función que devuelve el listado de alumnos excluidos en un curso
    private function getSolicitudesExcluidas($idCurso){

        $query = "SELECT profesores_cab.CODIGO, profesores_cab.DNI, profesores_cab.NOMBRE, profesores_cab.APELLIDO1, profesores_cab.APELLIDO2
                  FROM cursos_exc
                  INNER JOIN profesores_cab ON cursos_exc.PROFESOR = profesores_cab.CODIGO
                  WHERE CURSO = ".$idCurso;

        $excluidos = DB::select(DB::raw($query));

        return new LengthAwarePaginator($excluidos, count($excluidos), 10, 1);
        //return Paginator::make($excluidos, count($excluidos), 10);

    }

    // Función que devuelve el listado de alumnos excluidos en un curso
    private function getSolicitudes($idCurso){

        $query = "SELECT p.CODIGO, p.DNI, p.NOMBRE, p.APELLIDO1, p.APELLIDO2, s.FECHA
                  FROM profesores_cab p
                  INNER JOIN cursos_sol s ON p.CODIGO = s.PROFESOR
                  WHERE s.CURSO = ".$idCurso."
                  AND s.PROFESOR NOT IN (SELECT a.PROFESOR
                                         FROM cursos_adm a
                                         WHERE CURSO = ".$idCurso.")
                  AND s.PROFESOR NOT IN (SELECT e.PROFESOR
                                         FROM cursos_exc e
                                         WHERE CURSO = ".$idCurso.")";

        $solicitudes = DB::select(DB::raw($query));

        return new LengthAwarePaginator($solicitudes, count($solicitudes), 10, 1);
        //return Paginator::make($solicitudes, count($solicitudes), 10);

    }

    private function getAlumnosCertificados($idCurso){

        $profesores = $this->getSolicitudesAdmitidas($idCurso);
        $curso = DB::table('cursos_cab')->where('CODIGO', $idCurso)->get()[0];

        $totalCurso = $curso->DURACION;
        $faltasPermitidas = ($totalCurso * 15)/100;

        foreach($profesores as $i => $profesor){

            $query = "SELECT SUM(HORAS) AS TOTALHORAS, SUM(JUSTIFICADAS) AS TOTALJUSTIFICADAS
                      FROM cursos_fal
                      WHERE CURSO = ".$idCurso." AND PROFESOR = ".$profesor->CODIGO;
            $result = DB::select(DB::raw($query))[0];

            $totalAlumno = $result->TOTALHORAS - $result->TOTALJUSTIFICADAS;

            if($totalAlumno < ($totalCurso - $faltasPermitidas)){
                unset($profesores[$i]);
            }

        }

        return $profesores;

    }

    private function getMateriales($idCurso){

        $materiales = DB::table('cursos_mat')->where('CURSO', $idCurso)->get();

        return new LengthAwarePaginator($materiales, count($materiales), 10, 1);
        //return Paginator::make($materiales, count($materiales), 10);

    }

    private function getOtrosGastos($idCurso){

        $otros = DB::table('cursos_oto')->where('CURSO', $idCurso)->get();
        
        return new LengthAwarePaginator($otros, count($otros), 10, 1);
        //return Paginator::make($otros, count($otros), 10);

    }

    private function getSesiones($idCurso){

        $sesiones = DB::table('cursos_dia')->where('CURSO', $idCurso)->where('SESION', '<>', 0)->get();

        return $sesiones;
    }

    private function getSesion ($idCurso, $sesion){

        $query = "SELECT cursos_fal.SESION,
                         cursos_fal.HORAS,
                         cursos_fal.JUSTIFICADAS,
                         profesores_cab.CODIGO,
                         profesores_cab.DNI,
                         profesores_cab.NOMBRE,
                         profesores_cab.APELLIDO1,
                         profesores_cab.APELLIDO2
                  FROM cursos_fal
                  INNER JOIN profesores_cab ON profesores_cab.CODIGO = cursos_fal.PROFESOR
                  WHERE cursos_fal.CURSO = '".$idCurso."' AND cursos_fal.SESION = '".$sesion."'";


        $sesion = DB::select(DB::raw($query));

        return $sesion;
    }

    public function email($idCurso) {
        $email_emisor = Auth::user()->email;
        $query_admitidos = "SELECT profesores_cab.MAIL
                  FROM cursos_adm
                  INNER JOIN profesores_cab ON cursos_adm.PROFESOR = profesores_cab.CODIGO
                  WHERE CURSO = ".$idCurso." AND profesores_cab.MAIL IS NOT NULL";
        $admitidos = DB::select(DB::raw($query_admitidos));
        $emails_admitidos = "";
        foreach ($admitidos as $admitido) {
            if (filter_var($admitido->MAIL, FILTER_VALIDATE_EMAIL)) {
                $emails_admitidos = $emails_admitidos.$admitido->MAIL.", ";
            }
        }
        if($emails_admitidos != "") {
            $emails_admitidos = substr($emails_admitidos, 0, -2); // eliminamos el ultimo ", "
        }

        $query_excluidos = "SELECT profesores_cab.MAIL
                  FROM cursos_exc
                  INNER JOIN profesores_cab ON cursos_exc.PROFESOR = profesores_cab.CODIGO
                  WHERE CURSO = ".$idCurso." AND profesores_cab.MAIL IS NOT NULL";
        $excluidos = DB::select(DB::raw($query_excluidos));
        $emails_excluidos = "";
        foreach ($excluidos as $excluido) {
            if (filter_var($excluido->MAIL, FILTER_VALIDATE_EMAIL)) {
                $emails_excluidos = $emails_excluidos.$excluido->MAIL.", ";
            }
        }
        if($emails_excluidos != "") {
            $emails_excluidos = substr($emails_excluidos, 0, -2); // eliminamos el ultimo ", "
        }

        // Para coger el nombre del curso
        $tmp = DB::table('cursos_cab')->select('NOMBRE')->where('CODIGO', $idCurso)->get();
        $nombreCurso = $tmp[0]->NOMBRE;

        $datos = [
            'idCurso' => $idCurso,
            'nombreCurso' => $nombreCurso,
            'emisor' => $email_emisor,
            'admitidos' => $emails_admitidos,
            'excluidos' => $emails_excluidos
        ];

        return View::make('cursos.cursos_form_mail', $datos);
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