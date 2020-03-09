<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use Input, DB, View, Response, Redirect, Validator, Auth, Request;

class SeminariosController extends Controller {

    private $table;

    function __construct() {
        $this->table = "seminarios_cab";
    }

    /* ====================================================================================================
    ========================                                                       ========================
    ======================== Funciones de obtención de datos para su visualización ========================
    ========================                                                       ========================
    ==================================================================================================== */

    // vista inicial (lista de seminarios/grupos de trabajo)
    public function index() {
        $searchStr = Input::get('search', '');
        $items_per_page = Input::get('items', 10);
        $activos = Input::get('activos', 'no');
        $orden = Input::get('orden', 'ninguno');

        $seminarios = $this->getList($searchStr, $items_per_page, $activos, $orden);

        return View::make('seminarios.index', array('search' => $searchStr, 'items' => $items_per_page, 'activos' => $activos, 'orden' => $orden)) -> nest('datatable', 'seminarios.subviews.datatable', array('seminarios' => $seminarios, 'search' => $searchStr, 'items' => $items_per_page, 'activos' => $activos, 'orden' => $orden));
    }

    // cuando se busca seminario o cambia el número de elementos a visualizar
    public function search() {
        // Obtengo la cadena del buscador
        $searchStr = Input::get('searchStr');
        $items_per_page = Input::get('items_per_page');
        $activos = Input::get('activos', 'no');
        $orden = Input::get('orden', 'ninguno');

        $seminarios = $this->getList($searchStr, $items_per_page, $activos, $orden);

        // Renderizo la vista con los datos necesarios
        $view = View::make('seminarios.subviews.datatable', array('seminarios' => $seminarios, 'search' => $searchStr, 'items' => $items_per_page, 'activos' => $activos, 'orden' => $orden));

        // Y la devuelvo
        return Response::make($view);
    }

    // datos del seminario para mostrarlo (sección 'datos')
    public function datos($idSeminario) {
        // datos básicos del seminario
        $tmp = DB::table('seminarios_cab')->where('CODIGO', $idSeminario)->get();
        $datos = $tmp[0];

        // datos adicionales del seminario
        $query = "SELECT tema1, tema2, observaciones, cuestiones
                    FROM seminarios_obs
                    WHERE seminario=".$idSeminario;
        $results = DB::select(DB::raw($query));
        $datos_adicionales = $results[0];

        // nombres de los centros
        $query = "SELECT codigo, centro FROM centros_cab";
        $results = DB::select(DB::raw($query));
        $centros = array();
        foreach ($results as $result) {
            $centros[$result->codigo] = $result->centro;
        }
        // para el caso en que no se haya introducido un centro
        $centros[null] = "(vacío)";

        // modalidades de los seminarios (seminario o grupo de trabajo)
        $query = "SELECT codigo, tipo FROM seminarios_tip";
        $results = DB::select(DB::raw($query));
        $modalidades = array();
        foreach ($results as $result) {
            $modalidades[$result->codigo] = $result->tipo;
        }
        // para el caso en que no se haya introducido la modalidad
        $modalidades[null] = "(vacío)";

        // estados de los seminarios
        $query = "SELECT codigo, estado FROM seminarios_est";
        $results = DB::select(DB::raw($query));
        $estados = array();
        foreach ($results as $result) {
            $estados[$result->codigo] = $result->estado;
        }
        // para el caso en que no se haya introducido el estado
        $estados[null] = "(vacío)";

        // solicitante
        $solicitante = ['nombre' => '',
                        'dni' => '',
                        'codigo' => ''];
        if($datos->SOLICITANTE != null) {
            $query = "SELECT dni, nombre, apellido1, apellido2
                    FROM profesores_cab
                    WHERE codigo=". $datos->SOLICITANTE;
            $results = DB::select(DB::raw($query));
            $solicitante['nombre'] = $results[0]->nombre . " " . $results[0]->apellido1 . " " . $results[0]->apellido2;
            $solicitante['dni'] = $results[0]->dni;
        }

        // array de datos necesarios para la vista
        $data = array(
            'idSeminario' => $idSeminario,
            'nombreSeminario' => $datos->NOMBRE,
            'centros' => $centros,
            'modalidades' => $modalidades,
            'estados' => $estados,
            'solicitante' => $solicitante,
            'datos' => $datos,
            'datos_adicionales' => $datos_adicionales
        );

        return View::make("seminarios.seminarios_form_datos", $data);
    }

    // sección 'participantes'
    public function participantes($idSeminario) {
        // datos básicos del seminario
        $query = "SELECT codigo, nombre, horas_cerfifican, porcentaje
                    FROM seminarios_cab
                    WHERE codigo=".$idSeminario;
        $results = DB::select(DB::raw($query));
        $seminario = $results[0];

        // participantes
        $query = "SELECT p.codigo, p.dni, p.nombre, p.apellido1, p.apellido2, s.coordinador, s.certifica, c.centro, a.administracion
                    FROM seminarios_prf s
                    INNER JOIN profesores_cab p ON s.profesor=p.codigo
                    LEFT JOIN centros_cab c ON p.centro=c.codigo
                    LEFT JOIN administracion_tip a ON p.administracion=a.codigo
                    WHERE s.seminario=".$idSeminario;
        $participantes = DB::select(DB::raw($query));

        $data = array(
            'idSeminario' => $idSeminario,
            'nombreSeminario' => $seminario->nombre,
            'datos' => $seminario,
            'participantes' => $participantes,
        );

        return View::make("seminarios.seminarios_form_participantes", $data);
    }

    // sección 'otros datos'
    public function otrosDatos($idSeminario) {
        // datos básicos del seminario
        $query = "SELECT nombre
                    FROM seminarios_cab
                    WHERE codigo=".$idSeminario;
        $results = DB::select(DB::raw($query));
        $nombreSeminario = $results[0]->nombre;

        // datos para los checkboxes
        $query = "SELECT d1, d2, d3, d4, d5
                    FROM seminarios_cab
                    WHERE codigo=".$idSeminario;
        $results = DB::select(DB::raw($query));
        $checkboxes = $results[0];

        // datos necesarios de la tabla seminarios_obs
        $query = "SELECT D4, AREA, INTACADEMICO, INTPROYECTOS, INTACTIVOS, CONTENIDOS, METODOLOGIA, SEXISTA, TIC, ASESORAMIENTO, MATERIALES, REVISAR
                    FROM seminarios_obs
                    WHERE seminario=".$idSeminario;
        $results = DB::select(DB::raw($query));
        $obs = $results[0];

        // objetivos
        $query = "SELECT numero, objetivo, evaluacion, tecnicas, momentos, personas
                    FROM seminarios_obj
                    WHERE seminario=".$idSeminario;
        $objetivos = DB::select(DB::raw($query));

        // datos necesarios de la tabla seminarios_rec
        $query = "SELECT REPROGRAFIA, IFNULL(DREPROGRAFIA, 0) AS DREPROGRAFIA, OTROS, IFNULL(DOTROS, 0) AS DOTROS, BIBLIOGRAFIA, IFNULL(DBIBLIOGRAFIA, 0) AS DBIBLIOGRAFIA, (IFNULL(DREPROGRAFIA, 0) + IFNULL(DOTROS, 0) + IFNULL(DBIBLIOGRAFIA, 0)) AS TOTAL
                    FROM seminarios_rec
                    WHERE seminario=".$idSeminario;
        $results = DB::select(DB::raw($query));
        $rec = null;
        if(isset($results[0]))
        {
            $rec = $results[0];
            // cambiamos formato
            $rec->DREPROGRAFIA = number_format($rec->DREPROGRAFIA, 2, ",", ".");
            $rec->DOTROS = number_format($rec->DOTROS, 2, ",", ".");
            $rec->DBIBLIOGRAFIA = number_format($rec->DBIBLIOGRAFIA, 2, ",", ".");
            $rec->TOTAL = number_format($rec->TOTAL, 2, ",", ".");
        }

        $data = array(
            'idSeminario' => $idSeminario,
            'nombreSeminario' => $nombreSeminario,
            'checkboxes' => $checkboxes,
            'obs' => $obs,
            'objetivos' => $objetivos,
            'rec' => $rec
        );

        return View::make("seminarios.seminarios_form_otros", $data);
    }

    // sección 'asesor final'
    public function asesor($idSeminario) {
        // datos básicos del seminario
        $query = "SELECT nombre
                    FROM seminarios_cab
                    WHERE codigo=".$idSeminario;
        $results = DB::select(DB::raw($query));
        $nombreSeminario = $results[0]->nombre;

        // datos necesarios de la tabla seminarios_obs
        $query = "SELECT OBSFINALSOL, VALFINALSOL
                    FROM seminarios_obs
                    WHERE seminario=".$idSeminario;
        $results = DB::select(DB::raw($query));
        $obs = $results[0];

        $data = array(
            'idSeminario' => $idSeminario,
            'nombreSeminario' => $nombreSeminario,
            'obs' => $obs
        );

        return View::make("seminarios.seminarios_form_asesor", $data);
    }

    // sección 'seguimiento'
    public function seguimiento($idSeminario) {
        // datos básicos del seminario
        $query = "SELECT nombre
                    FROM seminarios_cab
                    WHERE codigo=".$idSeminario;
        $results = DB::select(DB::raw($query));
        $nombreSeminario = $results[0]->nombre;

        // datos necesarios
        $query = "SELECT SEGDESARROLLO, REALIZADO, METAPLICADA, EMPLEADOS, CUANTITATIVA, SEGASESOR, SEGCOORDINADOR
                    FROM seminarios_obs
                    WHERE seminario=".$idSeminario;
        $results = DB::select(DB::raw($query));
        $seguimiento = $results[0];

        // checkbox de informe favorable
        $data = array(
            'idSeminario' => $idSeminario,
            'nombreSeminario' => $nombreSeminario,
            'seguimiento' => $seguimiento,
            'checkbox' => '0'
        );

        return View::make("seminarios.seminarios_form_seguimiento", $data);
    }

    // sección 'control'
    public function control($idSeminario) {
        // datos básicos del seminario
        $query = "SELECT nombre
                    FROM seminarios_cab
                    WHERE codigo=".$idSeminario;
        $results = DB::select(DB::raw($query));
        $nombreSeminario = $results[0]->nombre;

        // datos necesarios de la tabla seminarios_dia
        $query = "SELECT T1, T2, T3
                    FROM seminarios_dia
                    WHERE seminario=".$idSeminario;
        $results = DB::select(DB::raw($query));
        $dias = NULL;
        if (isset($results[0])) {
            $dias = $results[0];
        }

        $data = array(
            'idSeminario' => $idSeminario,
            'nombreSeminario' => $nombreSeminario,
            'dias' => $dias
        );

        return View::make("seminarios.seminarios_form_control", $data);
    }

    // sección 'actas'
    public function actas($idSeminario, $idSesion = -1) {
        // número de la primera sesión si idSesion es null
        if($idSesion == -1) {
            $query = "SELECT MIN(NUMERO) as NUMERO
                    FROM actas_prf
                    WHERE seminario=".$idSeminario;
            $results = DB::select(DB::raw($query));
            if(isset($results[0]) && isset($results[0]->NUMERO)) {
                $idSesion = $results[0]->NUMERO;
            }
        }

        // datos básicos del seminario
        $query = "SELECT nombre
                    FROM seminarios_cab
                    WHERE codigo=".$idSeminario;
        $results = DB::select(DB::raw($query));
        $nombreSeminario = $results[0]->nombre;

        // actas de las todas las sesiones
        $query = "SELECT NUMERO, FECHA, INICIO, FIN, LUGAR, REALIZADA
                    FROM actas_cab
                    WHERE seminario=".$idSeminario."
                    ORDER BY NUMERO";
        $sesiones = DB::select(DB::raw($query));

        // profesores de la sesión
        $profesores = null;
        if($idSesion != -1) {
            $query = "SELECT a.NUMERO, a.PROFESOR, p.DNI, p.NOMBRE, p.APELLIDO1, p.APELLIDO2, a.ASISTIDO
                    FROM actas_prf a
                    INNER JOIN profesores_cab p ON a.PROFESOR=p.CODIGO
                    WHERE a.seminario=".$idSeminario." AND a.numero=".$idSesion;
            $profesores = DB::select(DB::raw($query));
        }


        // información de la sesión
        $infoSesion = ['OBSERVACIONES' => '', 'TEMAS' => '', 'ACUERDOS' => ''];
        if($idSesion != -1) {
            $query = "SELECT OBSERVACIONES, TEMAS, ACUERDOS
                        FROM actas_cab
                        WHERE seminario=".$idSeminario." AND numero=".$idSesion;
            $results = DB::select(DB::raw($query));
            $infoSesion = ['OBSERVACIONES' => $results[0]->OBSERVACIONES, 'TEMAS' => $results[0]->TEMAS, 'ACUERDOS' => $results[0]->ACUERDOS];
        }


        $data = array(
            'idSeminario' => $idSeminario,
            'nombreSeminario' => $nombreSeminario,
            'idSesion' => $idSesion,
            'sesiones' => $sesiones,
            'profesores' => $profesores,
            'infoSesion' => $infoSesion
        );

        return View::make("seminarios.seminarios_form_actas", $data);
    }

    // sección 'memoria'
    public function memoria($idSeminario) {
        // datos básicos del seminario
        $query = "SELECT nombre
                    FROM seminarios_cab
                    WHERE codigo=".$idSeminario;
        $results = DB::select(DB::raw($query));
        $nombreSeminario = $results[0]->nombre;

        // datos de la tabla seminarios_mem que son necesarios
        $query = "SELECT CARACTERISTICAS, OBJETIVOS, PARTICIPANTES, CONTENIDOS, METODOLOGIA, FORMACION, COBJETIVOS, CCONTENIDOS, cactividades, CMETODOLOGIA, CRECURSOS, CTEMPORALIZACION, MATERIALES, NECESIDADES
                    FROM seminarios_mem
                    WHERE seminario=".$idSeminario;
        $results = DB::select(DB::raw($query));
        $memoria = NULL;
        if (isset($results[0])) {
            $memoria = $results[0];
        }

        $data = array(
            'idSeminario' => $idSeminario,
            'nombreSeminario' => $nombreSeminario,
            'memoria' => $memoria
        );

        return View::make("seminarios.seminarios_form_memoria", $data);
    }

    /* ====================================================================================================
    ========================                                                       ========================
    ========================    Funciones para guardar datos de los formularios    ========================
    ========================                                                       ========================
    ==================================================================================================== */

    // datos del seminario para mostrarlo (sección 'datos')
    public function guardarDatos() {
        $input = Input::all();

        // comprobamos primero que los cambios tienen el formato correcto para la base de datos
        $rules = array(
            'NI' => 'nullable|regex:[^\d*$]',
            'HORAS' => 'nullable|regex:[^\d*$]'
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

        // tomamos los datos que necesitamos para la tabla seminarios_cab
        $datos = $input;
        $idSeminario = $datos['CODIGO'];
        unset($datos['_token']);
        unset($datos['CODIGO']);
        unset($datos['TEMA1']);
        unset($datos['TEMA2']);
        unset($datos['CUESTIONES']);
        unset($datos['OBSERVACIONES']);
        unset($datos['dniSolicitante']);
        unset($datos['SOLICITANTE']);

        // actualizamos la tabla seminarios_cab
        DB::table('seminarios_cab')->where("CODIGO", $idSeminario)->update($datos);

        // tomamos los datos que necesitamos para la tabla seminarios_obs
        $datos = ['TEMA1' => $input['TEMA1'],
                'TEMA2' => $input['TEMA2'],
                'CUESTIONES' => $input['CUESTIONES'],
                'OBSERVACIONES' => $input['OBSERVACIONES']];

        // actualizamos la tabla seminarios_obs
        DB::table('seminarios_obs')->where("SEMINARIO", $idSeminario)->update($datos);

        // volvemos a la vista
        return Redirect::to('seminarios/'.$idSeminario.'/datos');
    }

    // guardado del formulario 'participantes'
    public function guardarParticipantes()
    {
        $input = Input::all();
        $idSeminario = $input['CODIGO'];
        // comprobamos primero que los cambios tienen el formato correcto para la base de datos
        $rules = array(
            'HORAS' => 'nullable|regex:[^\d*$]',
            'PORCENTAJE' => 'nullable|regex:[^\d*$]'
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

        $datos = ['horas_cerfifican' => $input['HORAS'], 'PORCENTAJE' => $input['PORCENTAJE']];

        // actualizamos la tabla seminarios_obs
        DB::table('seminarios_cab')->where("CODIGO", $idSeminario)->update($datos);

        // volvemos a la vista
        return Redirect::to('seminarios/'.$idSeminario.'/participantes');
    }

    // guardado del formulario 'otros datos'
    public function guardarOtros()
    {
        $input = Input::all();
        $idSeminario = $input['CODIGO'];

        // comprobamos primero que los cambios tienen el formato correcto para la base de datos
        $rules = array(
            'DREPROGRAFIA' => 'nullable|regex:[^\d*\,?\d*$]',
            'DOTROS' => 'nullable|regex:[^\d*\,?\d*$]',
            'DBIBLIOGRAFIA' => 'nullable|regex:[^\d*\,?\d*$]'
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

        $datos = [];
        if(isset($input['d1']))
        {
            $datos['d1'] = '1';
        } else {
            $datos['d1'] = '0';
        }
        if(isset($input['d2']))
        {
            $datos['d2'] = '1';
        } else {
            $datos['d2'] = '0';
        }
        if(isset($input['d3']))
        {
            $datos['d3'] = '1';
        } else {
            $datos['d3'] = '0';
        }
        if(isset($input['d4']))
        {
            $datos['d4'] = '1';
        } else {
            $datos['d4'] = '0';
        }
        if(isset($input['d5']))
        {
            $datos['d5'] = '1';
        } else {
            $datos['d5'] = '0';
        }

        // actualizamos la tabla seminarios_cab
        DB::table('seminarios_cab')->where("CODIGO", $idSeminario)->update($datos);

        // tomamos los datos necesarios para la tabla seminarios_obs
        $datos = ['D4' => $input['D4'], 'AREA' => $input['AREA'], 'INTACADEMICO' => $input['INTACADEMICO'], 'INTPROYECTOS' => $input['INTPROYECTOS'], 'INTACTIVOS' => $input['INTACTIVOS'], 'CONTENIDOS' => $input['CONTENIDOS'], 'METODOLOGIA' => $input['METODOLOGIA'], 'SEXISTA' => $input['SEXISTA'], 'TIC' => $input['TIC'], 'ASESORAMIENTO' => $input['ASESORAMIENTO'], 'MATERIALES' => $input['MATERIALES'], 'REVISAR' => $input['REVISAR']];

        // actualizamos la tabla seminarios_obs
        DB::table('seminarios_obs')->where("SEMINARIO", $idSeminario)->update($datos);

        // tomamos los datos necesarios para la tabla seminarios_rec
        $datos = ['SEMINARIO' => $idSeminario,
                    'REPROGRAFIA' => $input['REPROGRAFIA'],
                    'DREPROGRAFIA' => str_replace(',', '.', $input['DREPROGRAFIA']),
                    'OTROS' => $input['OTROS'],
                    'DOTROS' => str_replace(',', '.', $input['DOTROS']),
                    'BIBLIOGRAFIA' => $input['BIBLIOGRAFIA'],
                    'DBIBLIOGRAFIA' => str_replace(',', '.', $input['DBIBLIOGRAFIA'])];

        // si ya existe la fila la actualizamos sino insertamos
        if (DB::table('seminarios_rec')->where("SEMINARIO", $idSeminario)->exists()) {
            DB::table('seminarios_rec')->where("SEMINARIO", $idSeminario)->update($datos);
        } else {
            DB::table('seminarios_rec')->where("SEMINARIO", $idSeminario)->insert($datos);
        }

        // volvemos a la vista
        return Redirect::to('seminarios/'.$idSeminario.'/otros');
    }

    // guardado del formulario 'asesor final'
    public function guardarAsesor()
    {
        $input = Input::all();
        $idSeminario = $input['CODIGO'];

        // tomamos los datos necesarios para la tabla seminarios_obs
        $datos = ['OBSFINALSOL' => $input['OBSFINALSOL'], 'VALFINALSOL' => $input['VALFINALSOL']];

        // actualizamos la tabla seminarios_obs
        DB::table('seminarios_obs')->where("SEMINARIO", $idSeminario)->update($datos);

        // volvemos a la vista
        return Redirect::to('seminarios/'.$idSeminario.'/asesor');
    }

    // guardado del formulario 'seguimiento'
    public function guardarSeguimiento()
    {
        $input = Input::all();
        $idSeminario = $input['CODIGO'];

        // tomamos los datos necesarios para la tabla seminarios_obs
        $datos = ['SEGDESARROLLO' => $input['SEGDESARROLLO'],
                    'REALIZADO' => $input['REALIZADO'],
                    'METAPLICADA' => $input['METAPLICADA'],
                    'EMPLEADOS' => $input['EMPLEADOS'],
                    'CUANTITATIVA' => $input['CUANTITATIVA'],
                    'SEGASESOR' => $input['SEGASESOR'],
                    'SEGCOORDINADOR' => $input['SEGCOORDINADOR']];

        // actualizamos la tabla seminarios_obs
        DB::table('seminarios_obs')->where("SEMINARIO", $idSeminario)->update($datos);

        // volvemos a la vista
        return Redirect::to('seminarios/'.$idSeminario.'/seguimiento');
    }

    // guardado del formulario 'control'
    public function guardarControl()
    {
        $input = Input::all();
        $idSeminario = $input['CODIGO'];

        // tomamos los datos necesarios para la tabla seminarios_obs
        $datos = ['SEMINARIO' => $idSeminario,
                    'T1' => $input['T1'],
                    'T2' => $input['T2'],
                    'T3' => $input['T3']];

        // actualizamos la tabla seminarios_mem
        // si ya existe la fila la actualizamos sino insertamos
        if (DB::table('seminarios_dia')->where("SEMINARIO", $idSeminario)->exists()) {
            DB::table('seminarios_dia')->where("SEMINARIO", $idSeminario)->update($datos);
        } else {
            DB::table('seminarios_dia')->where("SEMINARIO", $idSeminario)->insert($datos);
        }

        // volvemos a la vista
        return Redirect::to('seminarios/'.$idSeminario.'/control');
    }

    // guardado del formulario 'actas'
    public function guardarActas()
    {
        $input = Input::all();
        $idSeminario = $input['CODIGO'];
        $idSesion = $input['SESION'];

        // tomamos los datos necesarios para la tabla seminarios_mem
        $datos = ['TEMAS' => $input['TEMAS'],
                    'ACUERDOS' => $input['ACUERDOS'],
                    'OBSERVACIONES' => $input['OBSERVACIONES']];

        // actualizamos la tabla actas_cab
        DB::table('actas_cab')->where("SEMINARIO", $idSeminario)->where('NUMERO', $idSesion)->update($datos);

        // volvemos a la vista
        return Redirect::to('seminarios/'.$idSeminario.'/actas/'.$idSesion);
    }

    // guardado del formulario 'memoria'
    public function guardarMemoria()
    {
        $input = Input::all();
        $idSeminario = $input['CODIGO'];

        // tomamos los datos necesarios para la tabla seminarios_mem
        $datos = ['SEMINARIO' => $idSeminario, 'CARACTERISTICAS' => $input['CARACTERISTICAS'], 'OBJETIVOS' => $input['OBJETIVOS'], 'PARTICIPANTES' => $input['PARTICIPANTES'], 'CONTENIDOS' => $input['CONTENIDOS'], 'METODOLOGIA' => $input['METODOLOGIA'], 'FORMACION' => $input['FORMACION'], 'COBJETIVOS' => $input['COBJETIVOS'], 'CCONTENIDOS' => $input['CCONTENIDOS'], 'cactividades' => $input['cactividades'], 'CMETODOLOGIA' => $input['CMETODOLOGIA'], 'CRECURSOS' => $input['CRECURSOS'], 'CTEMPORALIZACION' => $input['CTEMPORALIZACION'], 'MATERIALES' => $input['MATERIALES'], 'NECESIDADES' => $input['NECESIDADES']];

        // actualizamos la tabla seminarios_mem
        // si ya existe la fila la actualizamos sino insertamos
        if (DB::table('seminarios_mem')->where("SEMINARIO", $idSeminario)->exists()) {
            DB::table('seminarios_mem')->where("SEMINARIO", $idSeminario)->update($datos);
        } else {
            DB::table('seminarios_mem')->where("SEMINARIO", $idSeminario)->insert($datos);
        }

        // volvemos a la vista
        return Redirect::to('seminarios/'.$idSeminario.'/memoria');
    }


    /* ====================================================================================================
    ========================                                                       ========================
    ========================               Funciones más específicas               ========================
    ========================                                                       ========================
    ==================================================================================================== */

    public function nuevo()
    {
        /* insertamos en la base de datos un seminario vacío con los datos mínimos
        lo hacemos con varias las tablas que se utilizan en los seminarios para que al extraer dato para visualizar el seminario no salte errores*/
        // tabla principal de 'seminarios_cab'
        $query = "INSERT INTO seminarios_cab (NOMBRE, TIPO) VALUES ('Nuevo seminario o grupo de trabajo', 1)";
        DB::insert(DB::raw($query));

        // obtenemos el código del nuevo seminario
        $id = DB::getPdo()->lastInsertId();

        // tabla 'seminarios_dia'
        $query = "INSERT INTO seminarios_dia (SEMINARIO) VALUES (" . $id . ")";
        DB::insert(DB::raw($query));

        // tabla 'seminarios_mem'
        $query = "INSERT INTO seminarios_mem (SEMINARIO) VALUES (" . $id . ")";
        DB::insert(DB::raw($query));

        // tabla 'seminarios_obs'
        $query = "INSERT INTO seminarios_obs (SEMINARIO) VALUES (" . $id . ")";
        DB::insert(DB::raw($query));

        // tabla 'seminarios_rec'
        $query = "INSERT INTO seminarios_rec (SEMINARIO) VALUES (" . $id . ")";
        DB::insert(DB::raw($query));
        
        return Redirect::back();
    }

    public function borrar($idSeminario)
    {
        // eliminamos los datos de todas las tablas que se usan en los seminarios (si existen datos)
        // en la tabla 'seminarios_cab'
        if (DB::table('seminarios_cab')->where("CODIGO", $idSeminario)->exists()) {
            DB::table('seminarios_cab')->where("CODIGO", $idSeminario)->delete();
        }
        // en la tabla 'seminarios_dia'
        if (DB::table('seminarios_dia')->where("SEMINARIO", $idSeminario)->exists()) {
            DB::table('seminarios_dia')->where("SEMINARIO", $idSeminario)->delete();
        }
        // en la tabla 'seminarios_mem'
        if (DB::table('seminarios_mem')->where("SEMINARIO", $idSeminario)->exists()) {
            DB::table('seminarios_mem')->where("SEMINARIO", $idSeminario)->delete();
        }
        // en la tabla 'seminarios_obj'
        if (DB::table('seminarios_obj')->where("SEMINARIO", $idSeminario)->exists()) {
            DB::table('seminarios_obj')->where("SEMINARIO", $idSeminario)->delete();
        }
        // en la tabla 'seminarios_obs'
        if (DB::table('seminarios_obs')->where("SEMINARIO", $idSeminario)->exists()) {
            DB::table('seminarios_obs')->where("SEMINARIO", $idSeminario)->delete();
        }
        // en la tabla 'seminarios_prf'
        if (DB::table('seminarios_prf')->where("SEMINARIO", $idSeminario)->exists()) {
            DB::table('seminarios_prf')->where("SEMINARIO", $idSeminario)->delete();
        }
        // en la tabla 'seminarios_rec'
        if (DB::table('seminarios_rec')->where("SEMINARIO", $idSeminario)->exists()) {
            DB::table('seminarios_rec')->where("SEMINARIO", $idSeminario)->delete();
        }
        // en la tabla 'actas_cab'
        if (DB::table('actas_cab')->where("SEMINARIO", $idSeminario)->exists()) {
            DB::table('actas_cab')->where("SEMINARIO", $idSeminario)->delete();
        }

        // volvemos a la vista anterior
        return Redirect::back();
    }

    // en la pestaña 'datos' se puede buscar un profesor por DNI y obtenemos su nombre
    public function addSolicitante(){
        $input = Input::all();

        $idSeminario = $input['idSeminario'];
        $dniSolicitante = $input['dniSolicitante'];

        // obenemos el id del profesor solicitante, si no existe devuelve null
        $idSolicitante = ProfesoresController::exist($dniSolicitante);
        $nombreSolicitante = "";
        if($idSolicitante != null) {
            $query = "UPDATE seminarios_cab 
                        SET SOLICITANTE=".$idSolicitante." WHERE CODIGO=".$idSeminario;
            DB::insert(DB::raw($query));
            
            // obtenemos el nombre del profesor para presentarlo en la vista
            $query = "SELECT CONCAT(COALESCE(nombre, ''), ' ', COALESCE(apellido1, ''), ' ', COALESCE(apellido2, '')) AS ncompleto
                        FROM profesores_cab
                        WHERE codigo=".$idSolicitante;
            $results = DB::select(DB::raw($query));
            $nombreSolicitante = $results[0]->ncompleto;
        }

        // devolvemos el nombre del profesor o una cadena vacía si no existe
        return $nombreSolicitante;
    }

    // para la sección 'participantes'
    public function addParticipante($idSeminario, $dniParticipante)
    {
        // obenemos el id del profesor participante, si no existe devuelve null
        $idParticitante = ProfesoresController::exist($dniParticipante);
        if($idParticitante != null) {
            // si el participante no exite, lo introducimos en la tabla 'seminarios_prf'
            if (!DB::table('seminarios_prf')->where("SEMINARIO", $idSeminario)->where("PROFESOR", $idParticitante)->exists()) {
                $query = "INSERT INTO seminarios_prf (SEMINARIO, PROFESOR) VALUES (" . $idSeminario . ", ".$idParticitante.")";
                DB::insert(DB::raw($query));
            }

            // si el participante no esta en las actas (tabla actas_prf), lo introducimos
            if(!DB::table('actas_prf')->where("SEMINARIO", $idSeminario)->where("PROFESOR", $idParticitante)->exists()) {
                // obtenemos todos las sesiones
                $query = "SELECT numero
                    FROM actas_cab
                    WHERE seminario=".$idSeminario;
                $results = DB::select(DB::raw($query));
                if(isset($results) && isset($results[0]->numero)) {
                    $actas = $results;
                    foreach ($actas as $acta) {
                        $query = "INSERT INTO actas_prf (SEMINARIO, NUMERO, PROFESOR) VALUES (" . $idSeminario . ", ".$acta->numero. ", " .$idParticitante. ")";
                        DB::insert(DB::raw($query));
                    }
                }
            }
        }
        return Redirect::to("seminarios/".$idSeminario."/participantes/");
    }

    // para la sección 'participantes'
    public function deleteParticipante($idSeminario, $idParticipante)
    {
        // borramos de la tabla de los participantes
        $query = "DELETE FROM seminarios_prf WHERE SEMINARIO = ".$idSeminario." AND PROFESOR = ".$idParticipante;
        DB::delete(DB::raw($query));

        // borramos de la tabla de las actas
        $query = "DELETE FROM actas_prf WHERE SEMINARIO = ".$idSeminario." AND PROFESOR = ".$idParticipante;
        DB::delete(DB::raw($query));
        
        return Redirect::to("seminarios/".$idSeminario."/participantes/");
    }

    // para el checkbox 'CER' de la sección 'participantes'
    public function certificaParticipante($idSeminario, $idParticipante, $certifica)
    {
        if($certifica == "si") {
            $datos = ["certifica" => '1'];
        } else {
            $datos = ["certifica" => '0'];
        }
        // actualizamos el campo 'certifica' del profesor
        DB::table('seminarios_prf')->where("SEMINARIO", $idSeminario)->where("PROFESOR", $idParticipante)->update($datos);
    }

    // para el checkbox 'CR' de la sección 'participantes'
    public function coordinaParticipante($idSeminario, $idParticipante, $coordina)
    {
        if($coordina == "si") {
            $datos = ["COORDINADOR" => '1'];
        } else {
            $datos = ["COORDINADOR" => '0'];
        }
        // actualizamos el campo 'COORDINADOR' del profesor
        DB::table('seminarios_prf')->where("SEMINARIO", $idSeminario)->where("PROFESOR", $idParticipante)->update($datos);
    }

    // para el checkbox 'REALIZADA' de la sección 'actas'
    public function sesionRealizada($idSeminario, $idSesion, $realizada)
    {
        if($realizada == "si") {
            $datos = ["REALIZADA" => '1'];
        } else {
            $datos = ["REALIZADA" => '0'];
        }
        // actualizamos el campo 'REALIZADA' del acta
        DB::table('actas_cab')->where("SEMINARIO", $idSeminario)->where("NUMERO", $idSesion)->update($datos);
    }

    // para el checkbox 'ASISTIDO' de la lista de profesores de la sección 'actas'
    public function profesorAsistido($idSeminario, $idSesion, $idProfesor, $asistido)
    {
        if($asistido == "si") {
            $datos = ["ASISTIDO" => '1'];
        } else {
            $datos = ["ASISTIDO" => '0'];
        }
        // actualizamos el campo 'ASISTIDO' del acta de la lista de profesores
        DB::table('actas_prf')->where("SEMINARIO", $idSeminario)->where("NUMERO", $idSesion)->where("PROFESOR", $idProfesor)->update($datos);
    }

    // añadir objetivos de la sección 'otros datos'
    public function addObjetivo($idSeminario)
    {
        // datos de la tabla seminarios_mem que son necesarios
        $query = "SELECT MAX(NUMERO) AS max
                    FROM seminarios_obj
                    WHERE seminario=".$idSeminario;
        $results = DB::select(DB::raw($query));
        $nuevoID = $results[0]->max + 1;

        $query = "INSERT INTO seminarios_obj (SEMINARIO, NUMERO) VALUES (" . $idSeminario . ", ".$nuevoID.")";
        DB::insert(DB::raw($query));
        return Redirect::to("seminarios/".$idSeminario."/otros/");
    }

    // eliminar objetivos de la sección 'otros datos'
    public function deleteObjetivo($idSeminario, $idObjetivo)
    {
        $query = "DELETE FROM seminarios_obj WHERE SEMINARIO = ".$idSeminario." AND NUMERO = ".$idObjetivo;
        $resultado = DB::delete(DB::raw($query));
        if($resultado == 1){
            return Redirect::to("seminarios/".$idSeminario."/otros/");
        }else{
            return "Error al eliminar el objetivo";
        }
    }

    // guardar dato sobre los objetivos de la sección 'otros datos'
    public function guardarObjetivo($idSeminario, $idObjetivo, $columna, $dato)
    {
        $datos = [$columna => $dato];
        if (DB::table('seminarios_obj')->where("SEMINARIO", $idSeminario)->where("NUMERO", $idObjetivo)->exists()) {
            DB::table('seminarios_obj')->where("SEMINARIO", $idSeminario)->where("NUMERO", $idObjetivo)->update($datos);
        } else {
            DB::table('seminarios_obj')->where("SEMINARIO", $idSeminario)->where("NUMERO", $idObjetivo)->insert($datos);
        }
    }


    // para el checkbox 'Informe favorable' de la sección 'seguimiento'
    public function informeFavorable($idSeminario, $favorable)
    {/*
        if($favorable == "si") {
            $datos = ["favorable" => '1'];
        } else {
            $datos = ["favorable" => '0'];
        }
        // actualizamos el campo 'favorable' del profesor
        DB::table('seminarios_prf')->where("SEMINARIO", $idSeminario)->where("PROFESOR", $idParticipante)->update($datos);
    */}


    /* ====================================================================================================
    ========================                                                       ========================
    ========================            Funciones privadas de la clase             ========================
    ========================                                                       ========================
    ==================================================================================================== */

    // La función devuelve un paginador con los seminarios
    private function getList($searchStr, $items_per_page, $activos, $orden){

        if($activos == 'si') { // solo se muestran los seminarios actualmente activos (por fechas)
            // Si la cadena no está vacia, obtengo los registros cobn alguna coincidencia
            if($searchStr != '') {
                if($orden == 'ninguno') { // orden indica la fecha de inicio
                    $query = "SELECT s.CODIGO, s.NI, s.NOMBRE, t.TIPO, s.INICIO, s.FIN
                            FROM ".$this->table." s
                            INNER JOIN seminarios_tip t ON s.TIPO=t.CODIGO
                            WHERE (s.INICIO<CURDATE() AND s.FIN>CURDATE()) AND
                                (s.CODIGO LIKE '".$searchStr."' OR
                                s.NOMBRE LIKE '%".$searchStr."%')
                            ORDER BY s.CODIGO DESC";
                } else if($orden == 'descendente') {
                    $query = "SELECT s.CODIGO, s.NI, s.NOMBRE, t.TIPO, s.INICIO, s.FIN
                            FROM ".$this->table." s
                            INNER JOIN seminarios_tip t ON s.TIPO=t.CODIGO
                            WHERE (s.INICIO<CURDATE() AND s.FIN>CURDATE()) AND
                                (s.CODIGO LIKE '".$searchStr."' OR
                                s.NOMBRE LIKE '%".$searchStr."%')
                            ORDER BY s.INICIO DESC";
                } else if ($orden == 'ascendente') {
                    $query = "SELECT s.CODIGO, s.NI, s.NOMBRE, t.TIPO, s.INICIO, s.FIN
                            FROM ".$this->table." s
                            INNER JOIN seminarios_tip t ON s.TIPO=t.CODIGO
                            WHERE (s.INICIO<CURDATE() AND s.FIN>CURDATE()) AND
                                (s.CODIGO LIKE '".$searchStr."' OR
                                s.NOMBRE LIKE '%".$searchStr."%')
                            ORDER BY s.INICIO ASC";
                }
            } else {
                if($orden == 'ninguno') {
                    $query = "SELECT s.CODIGO, s.NI, s.NOMBRE, t.TIPO, s.INICIO, s.FIN
                            FROM ".$this->table." s
                            INNER JOIN seminarios_tip t ON s.TIPO=t.CODIGO
                            WHERE s.INICIO<CURDATE() AND s.FIN>CURDATE()
                            ORDER BY s.CODIGO DESC";
                } else if($orden == 'descendente') {
                    $query = "SELECT s.CODIGO, s.NI, s.NOMBRE, t.TIPO, s.INICIO, s.FIN
                            FROM ".$this->table." s
                            INNER JOIN seminarios_tip t ON s.TIPO=t.CODIGO
                            WHERE s.INICIO<CURDATE() AND s.FIN>CURDATE()
                            ORDER BY s.INICIO DESC";
                } else if ($orden == 'ascendente') {
                    $query = "SELECT s.CODIGO, s.NI, s.NOMBRE, t.TIPO, s.INICIO, s.FIN
                            FROM ".$this->table." s
                            INNER JOIN seminarios_tip t ON s.TIPO=t.CODIGO
                            WHERE s.INICIO<CURDATE() AND s.FIN>CURDATE()
                            ORDER BY s.INICIO ASC";
                }
            }
        } else { // se muestran todos los seminarios
            // Si la cadena no está vacia, obtengo los registros cobn alguna coincidencia
            if($searchStr != ''){
                if($orden == 'ninguno') {
                    $query = "SELECT s.CODIGO, s.NI, s.NOMBRE, t.TIPO, s.INICIO, s.FIN
                            FROM ".$this->table." s
                            INNER JOIN seminarios_tip t ON s.TIPO=t.CODIGO
                            WHERE s.CODIGO LIKE '%".$searchStr."%' OR
                                s.NOMBRE LIKE '%".$searchStr."%'
                            ORDER BY s.CODIGO DESC";
                } else if($orden == 'descendente') {
                    $query = "SELECT s.CODIGO, s.NI, s.NOMBRE, t.TIPO, s.INICIO, s.FIN
                            FROM ".$this->table." s
                            INNER JOIN seminarios_tip t ON s.TIPO=t.CODIGO
                            WHERE s.CODIGO LIKE '%".$searchStr."%' OR
                                s.NOMBRE LIKE '%".$searchStr."%'
                            ORDER BY s.INICIO DESC";
                } else if ($orden == 'ascendente') {
                    $query = "SELECT s.CODIGO, s.NI, s.NOMBRE, t.TIPO, s.INICIO, s.FIN
                            FROM ".$this->table." s
                            INNER JOIN seminarios_tip t ON s.TIPO=t.CODIGO
                            WHERE s.CODIGO LIKE '%".$searchStr."%' OR
                                s.NOMBRE LIKE '%".$searchStr."%'
                            ORDER BY s.INICIO ASC";
                }
            }else{
                if($orden == 'ninguno') {
                    $query = "SELECT s.CODIGO, s.NI, s.NOMBRE, t.TIPO, s.INICIO, s.FIN
                        FROM ".$this->table." s
                        INNER JOIN seminarios_tip t ON s.TIPO=t.CODIGO
                        ORDER BY s.CODIGO DESC";
                } else if($orden == 'descendente') {
                    $query = "SELECT s.CODIGO, s.NI, s.NOMBRE, t.TIPO, s.INICIO, s.FIN
                        FROM ".$this->table." s
                        INNER JOIN seminarios_tip t ON s.TIPO=t.CODIGO
                        ORDER BY s.INICIO DESC";
                } else if ($orden == 'ascendente') {
                    $query = "SELECT s.CODIGO, s.NI, s.NOMBRE, t.TIPO, s.INICIO, s.FIN
                        FROM ".$this->table." s
                        INNER JOIN seminarios_tip t ON s.TIPO=t.CODIGO
                        ORDER BY s.INICIO ASC";
                }
            }
        } 

        // Ejecuto la consulta
        $results = DB::select( DB::raw($query));

        // Obtengo el paginador a mano
        // NOTA: Parece ser que el método Paginator::make(...) tiene un bug y por ello tengo que hacer el array_slice(...)
        $pageNumber = Input::get('page', 1);
        $slice = array_slice($results, $items_per_page * ($pageNumber - 1), $items_per_page);
        
        $seminarios = new LengthAwarePaginator($slice, count($results), $items_per_page, $pageNumber);

        // Al ser una subvista, el paginador de la tabla necesita una url base para funcionar correctamente
        $seminarios->withPath("seminarios");

        return $seminarios;
    }

}