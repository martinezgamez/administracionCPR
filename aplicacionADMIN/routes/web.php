<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::resource('users', 'UsersController');

Route::get('/', "HomeController@getIndex");
Route::get('login', "HomeController@getLogin");
Route::post('login', "HomeController@postLogin");
Route::get('logout', "HomeController@getLogout");

Route::group(array('middleware' => 'auth'), function(){

    Route::group(array('prefix' => 'notificaciones'), function(){
        Route::get('/', 'NotificacionesController@index');
        Route::post('/search', 'NotificacionesController@search');
        Route::get('/{idNotificacion}/delete', 'NotificacionesController@delete');
    });
    
    Route::group(array('prefix' => 'extractos'), function(){
        Route::get('/', 'ExtractosController@index');
        Route::post('/search', 'ExtractosController@search');
        Route::get('/{idNotificacion}/delete', 'ExtractosController@delete');
    });
    
    Route::group(array('prefix' => 'profesores'), function(){

        Route::get('/', 'ProfesoresController@index');
        Route::get('/alta', 'ProfesoresController@form');
        Route::post('/alta', 'ProfesoresController@save');
        Route::get('/editar/{id}', 'ProfesoresController@form');
        Route::post('/editar/{id}', 'ProfesoresController@edit');
        Route::post('/search', 'ProfesoresController@search');
        Route::get('/{idProfesor}/delete', 'ProfesoresController@delete');

        Route::get('/email', 'ProfesoresController@email');
        Route::post('/enviar-email', 'ProfesoresController@enviarEmail');

    });

    Route::group(array('prefix' => 'ponentes'), function(){

        Route::get('/', 'PonentesController@index');
        Route::get('/alta', 'PonentesController@form');
        Route::post('/alta', 'PonentesController@save');
        Route::get('/editar/{id}', 'PonentesController@form');
        Route::post('/editar/{id}', 'PonentesController@edit');
        Route::post('/search', 'PonentesController@search');

    });

    Route::group(array('prefix' => 'cursos'), function(){

        Route::get('/', 'CursosController@index');
        Route::post('/search', 'CursosController@search');
        /*Route::get('/alta', 'CursosController@getCursosForm');
        Route::get('/{id}', 'CursosController@getCursosForm');*/

        // Secciones de formulario
        Route::get('/{id}/datos', 'CursosController@cursoDatos');
        Route::get('/{id}/detalles', 'CursosController@cursoDetalles');
        Route::get('/{id}/ponentes', 'CursosController@cursoPonentes');
        Route::get('/{id}/ponentes/presupuesto', 'CursosController@cursoPonentesPresupuesto');
        Route::get('/{id}/ponentes/gastos/{numPonente?}','CursosController@cursoPonentesGastos');
        Route::get('/{id}/solicitudes', 'CursosController@cursoSolicitudes');
        Route::get('/{id}/materiales', 'CursosController@cursoMateriales');
        Route::get('/{id}/asistencia/{sesion?}', 'CursosController@cursoAsistencia');


        // Guardado formulario
        Route::get('/create', 'CursosController@createCurso');
        Route::post('/{idCurso}/datos/edit', 'CursosController@editDatos');
        Route::post('/{idCurso}/detalles/edit', 'CursosController@editDetalles');
        Route::post('/{idCurso}/ponente/{dniPonente}', 'CursosController@addPonente');
        Route::post('/{idCurso}/ponentes/edit/{ponente}', 'CursosController@editPonente');
        Route::post('/{idCurso}/ponentes/gastos/{numPonente}/add/dieta', 'CursosController@addDieta');
        Route::post('/{idCurso}/ponentes/gastos/{numPonente}/edit/dieta/{numDieta}', 'CursosController@editDieta');
        Route::post('/{idCurso}/ponentes/gastos/{numPonente}/add/manutencion', 'CursosController@addManutencion');
        Route::post('/{idCurso}/ponentes/gastos/{numPonente}/edit/manutencion/{numManutencion}', 'CursosController@editManutencion');
        Route::post('/{idCurso}/ponentes/gastos/{numPonente}/add/locomocion', 'CursosController@addLocomocion');
        Route::post('/{idCurso}/ponentes/gastos/{numPonente}/edit/locomocion/{numLocomocion}', 'CursosController@editLocomocion');

        Route::post('/{idCurso}/solicitud/{dniProfesor}', 'CursosController@addSolicitud');
        Route::get('/{idCurso}/admitir/{idAlumno}', 'CursosController@admitirSolicitud');
        Route::get('/{idCurso}/admitir/', 'CursosController@admitirSolicitudes');
        Route::get('/{idCurso}/excluir/{idAlumno}', 'CursosController@excluirSolicitud');


        Route::post('/{idCurso}/material/add', 'CursosController@addMaterial');
        Route::post('/{idCurso}/otros-gastos/add', 'CursosController@addGasto');

        Route::post('/{id}/asistencia/edit/{sesion}', 'CursosController@editSesionAlumno');
        Route::post('/{idCurso}/asistencia/add-no-presencial', 'CursosController@noPresencialesForm');
        Route::post('/{idCurso}/asistencia/add-sesion', 'CursosController@sesionForm');

        // Deletes
        Route::get('/{idCurso}/delete', 'CursosController@delete');
        Route::get('/{idCurso}/ponentes/delete/{ponente}', 'CursosController@deletePonente');
        Route::post('/{idCurso}/ponentes/gastos/{numPonente}/delete/dieta/{numDieta}', 'CursosController@deleteDieta');
        Route::post('/{idCurso}/ponentes/gastos/{numPonente}/delete/manutencion/{numManutencion}', 'CursosController@deleteManutencion');
        Route::post('/{idCurso}/ponentes/gastos/{numPonente}/delete/locomocion/{numLocomocion}', 'CursosController@deleteLocomocion');
        Route::get('/{idCurso}/solicitudes/admitido/delete/{idAdmitido}', 'CursosController@deleteAdmitido');
        Route::get('/{idCurso}/solicitudes/excluido/delete/{idExcluido}', 'CursosController@deleteExcluido');
        Route::get('/{idCurso}/materiales/delete/{idMaterial}', 'CursosController@deleteMaterial');
        Route::get('/{idCurso}/gastos/delete/{idGasto}', 'CursosController@deleteGasto');
        Route::get('/{id}/asistencia/delete/{sesion}', 'CursosController@deleteSesion');

        Route::get('/{idCurso}/email', 'CursosController@email');
        Route::post('/enviar-email', 'CursosController@enviarEmail');
    });

    Route::group(array('prefix' => 'informes'), function(){
        Route::group(array('prefix' => 'curso'), function(){

            Route::get('/{idCurso}/sesion/{numSesion}','InformesController@actaAsistencia');
            Route::get('/{idCurso}/informe-inicial', 'InformesController@informeInicial');
            Route::get('/{idCurso}/listados-solicitudes', 'InformesController@listadosSolicitudes');
            Route::get('/{idCurso}/informe-final', 'InformesController@informeFinal');
            Route::get('/{idCurso}/ponente/{numPonente}/recibos/locomocion', 'InformesController@reciboLocomocion');
            Route::get('/{idCurso}/ponente/{numPonente}/recibos/dietas', 'InformesController@reciboDietas');
            Route::get('/{idCurso}/ponente/{numPonente}/recibos/docencia', 'InformesController@reciboDocencia');
            Route::get('/{idCurso}/ponente/{numPonente}/recibos/manutencion', 'InformesController@reciboManutencion');
            Route::get('/{idCurso}/ponente/{numPonente}/irpf', 'InformesController@irpf');
            Route::get('/{idCurso}/certificacion', 'InformesController@certificacion');
            Route::get('/{idCurso}/excel-admitidos', 'InformesController@excelAdmitidos');

            Route::get('/{idSeminario}/informe-final-asesor', 'InformesController@inforeFinalAsesor');
            Route::get('/{idSeminario}/propuesta-certificacion', 'InformesController@propuestaCertificacion');

        });
        Route::group(array('prefix' => 'seminario'), function(){

            Route::get('/{idSeminario}/informe-final-asesor', 'InformesController@inforeFinalAsesor');
            Route::get('/{idSeminario}/propuesta-certificacion', 'InformesController@propuestaCertificacion');

        });
    });

    Route::group(array('prefix' => 'seminarios'), function(){

        Route::get('/', 'SeminariosController@index');
        Route::post('/search', 'SeminariosController@search');

        // obtener datos para los diferentes formularios
        Route::get('/{idSeminario}/datos', 'SeminariosController@datos');
        Route::get('/{idSeminario}/participantes', 'SeminariosController@participantes');
        Route::get('/{idSeminario}/otros', 'SeminariosController@otrosDatos');
		Route::get('/{idSeminario}/asesor', 'SeminariosController@asesor');
		Route::get('/{idSeminario}/seguimiento', 'SeminariosController@seguimiento');
		Route::get('/{idSeminario}/control', 'SeminariosController@control');
		Route::get('/{idSeminario}/actas/{idSesion?}', 'SeminariosController@actas');
		Route::get('/{idSeminario}/memoria', 'SeminariosController@memoria');

		// guardado de formularios
		Route::post('/{idSeminario}/datos/guardar', 'SeminariosController@guardarDatos');
		Route::post('/{idSeminario}/participantes/guardar', 'SeminariosController@guardarParticipantes');
		Route::post('/{idSeminario}/otros/guardar', 'SeminariosController@guardarOtros');
		Route::post('/{idSeminario}/asesor/guardar', 'SeminariosController@guardarAsesor');
		Route::post('/{idSeminario}/seguimiento/guardar', 'SeminariosController@guardarSeguimiento');
		Route::post('/{idSeminario}/control/guardar', 'SeminariosController@guardarControl');
		Route::post('/{idSeminario}/actas/guardar', 'SeminariosController@guardarActas');
		Route::post('/{idSeminario}/memoria/guardar', 'SeminariosController@guardarMemoria');

		// crear/eliminar seminario
		Route::get('/{idSeminario}/delete', 'SeminariosController@borrar');
		Route::get('/create', 'SeminariosController@nuevo');

		// checkboxes
		Route::post('/{idSeminario}/crparticipante/{idParticipante}/{coordina}', 'SeminariosController@coordinaParticipante');
		Route::post('/{idSeminario}/cerparticipante/{idParticipante}/{certifica}', 'SeminariosController@certificaParticipante');
		Route::post('/{idSeminario}/actas/{idSesion}/{realizada}', 'SeminariosController@sesionRealizada');
		Route::post('/{idSeminario}/actas/{idSesion}/profesor/{idProfesor}/{asistido}', 'SeminariosController@profesorAsistido');
		
		// añadir/modificar solicitante
		Route::post('/{idSeminario}/addsolicitante/{dniSolicitante}', 'SeminariosController@addSolicitante');

		// añadir/quitar participante
		Route::post('/{idSeminario}/participantes/{dniParticipante}/add', 'SeminariosController@addParticipante');
		Route::get('/{idSeminario}/participantes/{idParticipante}/delete', 'SeminariosController@deleteParticipante');
		
		// añadir/quitar/modificar objetivo
		Route::get('/{idSeminario}/objetivos/add', 'SeminariosController@addObjetivo');
		Route::get('/{idSeminario}/objetivos/{idObjetivo}/delete', 'SeminariosController@deleteObjetivo');
		Route::post('/{idSeminario}/objetivos/{idObjetivo}/{columna}/{dato}', 'SeminariosController@guardarObjetivo');
    });

});