<?php

namespace App\Http\Controllers;

use App\Libraries\NumberToLetterConverter;
use DB, PDF;

class InformesController extends Controller {

    /**
     * Creates a temporary file.
     * The file is not created if the $content argument is null
     *
     * @param string $content   Optional content for the temporary file
     * @param string $extension An optional extension for the filename
     *
     * @return string The filename
     */

    /* ====================================================================================================
    ========================                                                       ========================
    ========================       Funciones para los informes de los cursos       ========================
    ========================                                                       ========================
    ==================================================================================================== */

    public function informeInicial($idCurso){
        // Datos para formulario
        $niveles = DB::table('cursos_niv')->get();
        $nivelesList = array();
        foreach($niveles as $nivel){
            $nivelesList[$nivel->CODIGO] = $nivel->NIVEL;
        }

        // "Dirigido a" en datos básicos (Una gilipollez de el que diseño esta BD)
        $tmp = DB::table('cursos_dir')->where('CURSO', $idCurso)->get();
        $dirigido_a = "";
        if(count($tmp) > 0){
            $dirigido_a = $tmp[0];
        }

        // Calculo de presupuesto de ponentes
        $query_ponentes = "SELECT IFNULL(SUM(PRECIO*HORAS-PRECIO*HORAS*IRPF/100),0) AS DOCENCIA,
                           IFNULL(SUM(AVION+TREN+TAXI+BUS+COCHE+BARCO+OTROS),0) AS TRANSPORTE,
                           IFNULL(SUM(ALOJPRECIO*ALOJDIAS),0) AS ALOJAMIENTO,
                           IFNULL(SUM(DIETDIAS*DIETPRECIO),0) AS DIETAS
                           FROM cursos_pon WHERE CURSO = ".$idCurso;
        $tmp = DB::select(DB::raw($query_ponentes));
        $presupuesto_ponentes = null;
        if (isset($tmp[0])) {
            $presupuesto_ponentes = $tmp[0];
        }

        // Calculo de presupuesto de materiales
        $query_materiales = "SELECT IFNULL(SUM(CANTIDAD*PRECIO),0) AS MATERIALES
                             FROM cursos_mat WHERE CURSO = ".$idCurso;
        $tmp = DB::select(DB::raw($query_materiales));
        $presupuesto_materiales = $tmp[0];

        // Calculo de presupuesto de otros gastos
        $query_otros = "SELECT IFNULL(SUM(PRECIO),0) AS OTROS
                             FROM cursos_oto WHERE CURSO = ".$idCurso;
        $tmp = DB::select(DB::raw($query_otros));
        $presupuesto_otros = $tmp[0];

        // Ponentes
        $query = "SELECT p.NOMBRE, p.APELLIDO1, p.APELLIDO2, cp.HORAS
                  FROM cursos_pon cp
                  INNER JOIN ponentes_cab p ON cp.COD_PON = p.CODIGO
                  WHERE cp.CURSO = ".$idCurso;

        $ponentes = DB::select(DB::raw($query));

        // Datos básicos del curso
        $tmp = DB::table('cursos_cab')->where('CODIGO', $idCurso)->get();
        $datos = $tmp[0];

        // Detalles
        $tmp = DB::table('cursos_dat')->where('CURSO', $idCurso)->get();
        $detalles = null;
        if (isset($tmp[0])) {
            $detalles = $tmp[0];
        }

        //Poner las fechas en formato adecuado para impresión
        $datos->INICIO = date("d/m/Y", strtotime($datos->INICIO));
        $datos->FIN = date("d/m/Y", strtotime($datos->FIN));

        // Añadido de los demás datos
        $datos->DIRIGIDO = "";
        if (isset($dirigido_a->DESTINO)) {
            $datos->DIRIGIDO = $dirigido_a->DESTINO;        
        }

        if (isset($datos->NIVEL)) {
            $datos->NIVEL = $nivelesList[$datos->NIVEL];
        } else {
            $datos->NIVEL = "";
        }

        $datos->PRESUPUESTO = 0;
        if (isset($presupuesto_ponentes)) {
            $datos->PRESUPUESTO = $presupuesto_ponentes->DOCENCIA + $presupuesto_ponentes->TRANSPORTE + $presupuesto_ponentes->ALOJAMIENTO + $presupuesto_ponentes->DIETAS + $presupuesto_materiales->MATERIALES + $presupuesto_otros->OTROS;
        }

        $datos->PRESUPUESTO = number_format($datos->PRESUPUESTO, 2);
        $datos->PONENTES = $ponentes;

        // Meses del año en español
        $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

        $data = array(
            "datos" => $datos,
            "detalles" => $detalles,
            "meses" => $meses,
        );

        $pdf = PDF::loadView('informes.informe-inicial', $data);
        $pdf->setOption('header-spacing', 5);
        $pdf->setOption('header-html', './informes-subviews/header.html');
        $pdf->setOption('footer-spacing', 5);
        $pdf->setOption('footer-html', './informes-subviews/footer.html');
        return $pdf->download('Informe_inicial.pdf');
    }

    public function informeFinal($idCurso){
        // Datos para formulario
        $niveles = DB::table('cursos_niv')->get();
        $nivelesList = array();
        foreach($niveles as $nivel){
            $nivelesList[$nivel->CODIGO] = $nivel->NIVEL;
        }

        // "Dirigido a" en datos básicos (Una gilipollez de el que diseño esta BD)
        $tmp = DB::table('cursos_dir')->where('CURSO', $idCurso)->get();
        $dirigido_a = "";
        if(count($tmp) > 0){
            $dirigido_a = $tmp[0];
        }

        // Calculo de presupuesto de ponentes
        $query_ponentes = "SELECT IFNULL(SUM(PRECIO*HORAS-PRECIO*HORAS*IRPF/100),0) AS DOCENCIA
                           FROM cursos_pon WHERE CURSO = ".$idCurso;
        $tmp = DB::select(DB::raw($query_ponentes));
        $gastos_ponencias = $tmp[0]->DOCENCIA;

        // Calculo de presupuesto de materiales
        $query_materiales = "SELECT IFNULL(SUM(CANTIDAD*PRECIO),0) AS MATERIALES
                             FROM cursos_mat WHERE CURSO = ".$idCurso;
        $tmp = DB::select(DB::raw($query_materiales));
        $gastos_materiales = $tmp[0]->MATERIALES;

        // Calculo de presupuesto de otros gastos
        $query_otros = "SELECT IFNULL(SUM(PRECIO),0) AS OTROS
                             FROM cursos_oto WHERE CURSO = ".$idCurso;
        $tmp = DB::select(DB::raw($query_otros));
        $gastos_otros = $tmp[0]->OTROS;

        // Calculo los gastos de los ponentes
        $query_dietas = "SELECT IFNULL(SUM(ALOJAMIENTO*DIASALOJAMIENTO),0) AS ALOJAMIENTO,
                                IFNULL(SUM(DIETAS*DIASDIETAS), 0) AS DIETAS, 
                                IFNULL(SUM(EVENTUAL*DIASEVENTUAL), 0) AS EVENTUAL
                           FROM cursos_pon_die WHERE CURSO = ".$idCurso;
        $tmp = DB::select(DB::raw($query_dietas));
        $gastos_dietas = $tmp[0]->ALOJAMIENTO + $tmp[0]->DIETAS + $tmp[0]->EVENTUAL;

        $query_manutencion = "SELECT IFNULL(SUM(ALMUERZO), 0) AS ALMUERZO, 
                                    IFNULL(SUM(CENA), 0) AS CENA, 
                                    IFNULL(SUM(COTROS), 0) AS OTROS
                              FROM cursos_pon_man
                              WHERE CURSO = ".$idCurso;
        $tmp = DB::select(DB::raw($query_manutencion));
        $gastos_manutencion = $tmp[0]->ALMUERZO + $tmp[0]->CENA + $tmp[0]->OTROS;

        $query_locomocion = "SELECT IFNULL(SUM(IMPORTE), 0) AS LOCOMOCION
                             FROM cursos_pon_iti
                             WHERE CURSO = ".$idCurso;
        $tmp = DB::select(DB::raw($query_locomocion));
        $gastos_locomocion = $tmp[0]->LOCOMOCION;

        // Ponentes
        $query = "SELECT p.DNI, p.NOMBRE, p.APELLIDO1, p.APELLIDO2, cp.HORAS
                  FROM cursos_pon cp
                  INNER JOIN ponentes_cab p ON cp.COD_PON = p.CODIGO
                  WHERE cp.CURSO = ".$idCurso;

        $ponentes = DB::select(DB::raw($query));

        // Solicitudes admitidas
        $query = "SELECT COUNT(profesores_cab.CODIGO) AS ADMITIDOS
                  FROM cursos_adm
                  INNER JOIN profesores_cab ON cursos_adm.PROFESOR = profesores_cab.CODIGO
                  WHERE CURSO = ".$idCurso;

        $tmp = DB::select(DB::raw($query));
        $admitidos = $tmp[0]->ADMITIDOS;

        // Profesores que certifican
        $certificados = count($this->getAlumnosCertificados($idCurso));

        // Datos básicos del curso
        $tmp = DB::table('cursos_cab')->where('CODIGO', $idCurso)->get();
        $datos = $tmp[0];

        // Detalles
        $tmp = DB::table('cursos_dat')->where('CURSO', $idCurso)->get();
        $detalles = null;
        if(isset($tmp[0])) {
            $detalles = $tmp[0];
        }

        //Poner las fechas en formato adecuado para impresión
        $datos->INICIO = date("d/m/Y", strtotime($datos->INICIO));
        $datos->FIN = date("d/m/Y", strtotime($datos->FIN));

        // Añadido de los demás datos
        $datos->DIRIGIDO = "";
        if (isset($dirigido_a->DESTINO)) {
            $datos->DIRIGIDO = $dirigido_a->DESTINO;
        }

        if (isset($datos->NIVEL)) {
            $datos->NIVEL = $nivelesList[$datos->NIVEL];
        } else {
            $datos->NIVEL = "";
        }

        $datos->GASTOS = $gastos_ponencias+$gastos_materiales+$gastos_otros+$gastos_dietas+$gastos_manutencion+$gastos_locomocion;
        $datos->GASTOS = number_format($datos->GASTOS, 2);

        $datos->PONENTES = $ponentes;
        $datos->ADMITIDOS = $admitidos;
        $datos->CERTIFICADOS = $certificados;

        // Meses del año en español
        $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

        $data = array(
            "datos" => $datos,
            "detalles" => $detalles,
            "meses" => $meses,
        );

        $pdf = PDF::loadView('informes.informe-final', $data);
        $pdf->setOption('header-spacing', 5);
        $pdf->setOption('header-html', './informes-subviews/header.html');
        $pdf->setOption('footer-spacing', 5);
        $pdf->setOption('footer-html', './informes-subviews/footer.html');
        return $pdf->download('Informe_final.pdf');
    }

    public function certificacion($idCurso){
        // Datos básicos del curso
        $tmp = DB::table('cursos_cab')->where('CODIGO', $idCurso)->get();
        $datos = $tmp[0];
        $datos->INICIO = date("d/m/Y", strtotime($datos->INICIO));
        $datos->FIN = date("d/m/Y", strtotime($datos->FIN));

        // Admitidos
        $query_admitidos = "SELECT profesores_cab.CODIGO, profesores_cab.DNI, profesores_cab.NOMBRE, profesores_cab.APELLIDO1, profesores_cab.APELLIDO2, profesores_cab.ADMINISTRACION
                  FROM cursos_adm
                  INNER JOIN profesores_cab ON cursos_adm.PROFESOR = profesores_cab.CODIGO
                  WHERE CURSO = ".$idCurso." ORDER BY profesores_cab.APELLIDO1";
        $admitidos = DB::select(DB::raw($query_admitidos));
        
        $query_resumen = "SELECT administracion, cuerpo, count(ifnull(cuerpo, 1)) as total 
                            from profesores_cab 
                            INNER JOIN cursos_adm ON cursos_adm.profesor=profesores_cab.codigo
                            WHERE cursos_adm.curso=".$idCurso." GROUP BY administracion, cuerpo";
        $resumen_res = DB::select(DB::raw($query_resumen));
        $resumen = $this->getResumen($resumen_res);

        // No admitidos
        $query_no_admitidos = "SELECT profesores_cab.CODIGO, profesores_cab.DNI, profesores_cab.NOMBRE, profesores_cab.APELLIDO1, profesores_cab.APELLIDO2, profesores_cab.ADMINISTRACION
                  FROM cursos_exc
                  INNER JOIN profesores_cab ON cursos_exc.PROFESOR = profesores_cab.CODIGO
                  WHERE CURSO = ".$idCurso." ORDER BY profesores_cab.APELLIDO1";
        $noAdmitidos = DB::select(DB::raw($query_no_admitidos));

        // Certificados
        $certificados = $this->getAlumnosCertificados($idCurso);

        // No certificado
        $noCertificados = $this->getAlumnosCertificados($idCurso, false);

        // Ponentes
        $query = "SELECT p.DNI, p.NOMBRE, p.APELLIDO1, p.APELLIDO2, cp.PONENCIA, cp.HORAS
                  FROM cursos_pon cp
                  INNER JOIN ponentes_cab p ON cp.COD_PON = p.CODIGO
                  WHERE cp.CURSO = ".$idCurso;

        $ponentes = DB::select(DB::raw($query));

        // Meses del año en español
        $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

        $data = array(
            'datos' => $datos,
            'admitidos' => $admitidos,
            'noAdmitidos' => $noAdmitidos,
            'certificados' => $certificados,
            'noCertificados' => $noCertificados,
            'ponentes' => $ponentes,
            'meses' => $meses,
            'resumen' => $resumen,
        );
        
        $pdf = PDF::loadView('informes.certificacion', $data);
        $pdf->setOption('header-spacing', 5);
        $pdf->setOption('header-html', './informes-subviews/header.html');
        $pdf->setOption('footer-spacing', 5);
        $pdf->setOption('footer-html', './informes-subviews/footer.html');
        return $pdf->download('propuesta_certificacion.pdf');
    }

    public function listadosSolicitudes($idCurso){
        $query_admitidos = "SELECT profesores_cab.CODIGO, profesores_cab.DNI, profesores_cab.NOMBRE, profesores_cab.APELLIDO1, profesores_cab.APELLIDO2, profesores_cab.MAIL, profesores_cab.MOVIL
                  FROM cursos_adm
                  INNER JOIN profesores_cab ON cursos_adm.PROFESOR = profesores_cab.CODIGO
                  WHERE CURSO = ".$idCurso." ORDER BY profesores_cab.APELLIDO1";
        $query_excluidos = "SELECT profesores_cab.CODIGO, profesores_cab.DNI, profesores_cab.NOMBRE, profesores_cab.APELLIDO1, profesores_cab.APELLIDO2, profesores_cab.MAIL, profesores_cab.MOVIL
                  FROM cursos_exc
                  INNER JOIN profesores_cab ON cursos_exc.PROFESOR = profesores_cab.CODIGO
                  WHERE CURSO = ".$idCurso." ORDER BY profesores_cab.APELLIDO1";
        $query_curso = "SELECT NOMBRE, INICIO, FIN FROM cursos_cab WHERE CODIGO = ".$idCurso;

        $admitidos = DB::select(DB::raw($query_admitidos));
        $excluidos = DB::select(DB::raw($query_excluidos));

        $tmp = DB::select(DB::raw($query_curso));
        $curso = $tmp[0];
        $curso->INICIO = date("d/m/Y", strtotime($curso->INICIO));
        $curso->FIN = date("d/m/Y", strtotime($curso->FIN));

        $data = ['admitidos' => $admitidos, 'excluidos' => $excluidos, 'curso' => $curso];

        $pdf = PDF::loadView('informes.listados-solicitudes', $data);
        $pdf->setOption('header-spacing', 5);
        $pdf->setOption('header-html', './informes-subviews/header.html');
        $pdf->setOption('footer-spacing', 5);
        $pdf->setOption('footer-html', './informes-subviews/footer.html');
        return $pdf->download('listados_solicitudes.pdf');
    }

	public function actaAsistencia($idCurso, $numsesion){
        $query_alumnos = "SELECT profesores_cab.NOMBRE, profesores_cab.APELLIDO1, profesores_cab.APELLIDO2
                  FROM cursos_adm
                  INNER JOIN profesores_cab ON cursos_adm.PROFESOR = profesores_cab.CODIGO
                  WHERE CURSO = ".$idCurso." ORDER BY profesores_cab.APELLIDO1";
        $query_sesion = "SELECT SESION, FECHA FROM cursos_dia WHERE CURSO = ".$idCurso." AND SESION = ".$numsesion;
        $query_curso = "SELECT NOMBRE, INICIO, FIN FROM cursos_cab WHERE CODIGO = ".$idCurso;

        $alumnos = DB::select(DB::raw($query_alumnos));

        $tmp = DB::select(DB::raw($query_sesion));
        $sesion = $tmp[0];
        $fecha = $sesion->FECHA; // La guardo para el nombre del fichero
        $sesion->FECHA = date("d/m/Y", strtotime($sesion->FECHA));

        $tmp = DB::select(DB::raw($query_curso));
        $curso = $tmp[0];
        $curso->INICIO = date("d/m/Y", strtotime($curso->INICIO));
        $curso->FIN = date("d/m/Y", strtotime($curso->FIN));
        /*
        //return View::make('informes.acta-asistencia', ['alumnos' => $alumnos, 'sesion' => $sesion, 'curso' => $curso]);
        $pdf = PDF::loadView('informes.acta-asistencia', ['alumnos' => $alumnos, 'sesion' => $sesion, 'curso' => $curso])
            ->setOption('header-spacing', 10)
            //->setOption('header-html', $this->createTemporaryFile(url().'/informes/header','html'))
            ->setOption('header-html', url().'/informes-subviews/header.html')
            ->setOption('footer-spacing', 10)
            //->setOption('footer-html', $this->createTemporaryFile(url().'/informes/footer', 'html'));
            ->setOption('footer-html', url().'/informes-subviews/footer.html');
        */
        $pdf = PDF::loadView('informes.acta-asistencia', ['alumnos' => $alumnos, 'sesion' => $sesion, 'curso' => $curso]);
        return $pdf->download('Acta_'.$fecha.'.pdf');
    }

    public function reciboLocomocion($idCurso, $numPonente){
        // Meses del año en español
        $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

        //curso
        $query_curso = "SELECT NOMBRE, INICIO, FIN FROM cursos_cab WHERE CODIGO = ".$idCurso;
        $tmp = DB::select(DB::raw($query_curso));
        $curso = $tmp[0];
        $curso->INICIO = date("d/m/Y", strtotime($curso->INICIO));
        $curso->FIN = date("d/m/Y", strtotime($curso->FIN));

        // Ponente_curso
        $query_cursos_pon = "SELECT COD_PON FROM cursos_pon WHERE CURSO = ".$idCurso." AND PONENTE = ".$numPonente;
        $tmp = DB::select(DB::raw($query_cursos_pon));
        $idPonente = $tmp[0]->COD_PON;

        // Ponente
        $query_ponente = "SELECT NOMBRE, APELLIDO1, APELLIDO2, DNI, ENTIDAD, SUCURSAL, CONTROL, CUENTA FROM ponentes_cab WHERE CODIGO = ".$idPonente;
        $tmp = DB::select(DB::raw($query_ponente));
        $ponente = $tmp[0];

        // gastos del ponente
        $query_locomocion = "SELECT IFNULL(SUM(IMPORTE), 0) AS LOCOMOCION
                             FROM cursos_pon_iti
                             WHERE CURSO = ".$idCurso." AND PONENTE = ".$numPonente;
        $tmp = DB::select(DB::raw($query_locomocion));
        $gastos_locomocion = $tmp[0]->LOCOMOCION;
        $gastosLetras = $this->convertEurosToString($gastos_locomocion);
        $gastos_locomocion = number_format($gastos_locomocion, 2, ',', '.');

        $data = array(
            'meses' => $meses,
            'curso' => $curso,
            'ponente' => $ponente,
            'gastos' => $gastos_locomocion,
            'gastosLetras' => $gastosLetras,
        );

        $pdf = PDF::loadView('informes.recibo-locomocion', $data);
        $pdf->setOption('header-spacing', 5);
        $pdf->setOption('header-html', './informes-subviews/header.html');
        $pdf->setOption('footer-spacing', 5);
        $pdf->setOption('footer-html', './informes-subviews/footer.html');
        return $pdf->download('recibo_locomocion'.$idPonente.'.pdf');
    }

    public function reciboDietas($idCurso, $numPonente){
        // Meses del año en español
        $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

        //curso
        $query_curso = "SELECT NOMBRE, INICIO, FIN FROM cursos_cab WHERE CODIGO = ".$idCurso;
        $tmp = DB::select(DB::raw($query_curso));
        $curso = $tmp[0];
        $curso->INICIO = date("d/m/Y", strtotime($curso->INICIO));
        $curso->FIN = date("d/m/Y", strtotime($curso->FIN));

        // Ponente_curso
        $query_cursos_pon = "SELECT COD_PON FROM cursos_pon WHERE CURSO = ".$idCurso." AND PONENTE = ".$numPonente;
        $tmp = DB::select(DB::raw($query_cursos_pon));
        $idPonente = $tmp[0]->COD_PON;

        // Ponente
        $query_ponente = "SELECT NOMBRE, APELLIDO1, APELLIDO2, DNI, ENTIDAD, SUCURSAL, CONTROL, CUENTA FROM ponentes_cab WHERE CODIGO = ".$idPonente;
        $tmp = DB::select(DB::raw($query_ponente));
        $ponente = $tmp[0];

        // gastos del ponente
        $query_dietas = "SELECT IFNULL(SUM(ALOJAMIENTO*DIASALOJAMIENTO),0) AS ALOJAMIENTO,
                                IFNULL(SUM(DIETAS*DIASDIETAS), 0) AS DIETAS, 
                                IFNULL(SUM(EVENTUAL*DIASEVENTUAL), 0) AS EVENTUAL
                           FROM cursos_pon_die WHERE CURSO = ".$idCurso." AND PONENTE = ".$numPonente;
        $tmp = DB::select(DB::raw($query_dietas));
        $gastos_dietas = number_format($tmp[0]->ALOJAMIENTO + $tmp[0]->DIETAS + $tmp[0]->EVENTUAL, 2, ",", ".");
        $gastosLetras = $this->convertEurosToString($gastos_dietas);

        $data = array(
            'meses' => $meses,
            'curso' => $curso,
            'ponente' => $ponente,
            'gastos' => $gastos_dietas,
            'gastosLetras' => $gastosLetras,
        );

        $pdf = PDF::loadView('informes.recibo-dietas', $data);
        $pdf->setOption('header-spacing', 5);
        $pdf->setOption('header-html', './informes-subviews/header.html');
        $pdf->setOption('footer-spacing', 5);
        $pdf->setOption('footer-html', './informes-subviews/footer.html');
        return $pdf->download('recibo_dietas'.$idPonente.'.pdf');
    }

    public function reciboManutencion($idCurso, $numPonente){
        // Meses del año en español
        $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

        //curso
        $query_curso = "SELECT NOMBRE, INICIO, FIN FROM cursos_cab WHERE CODIGO = ".$idCurso;
        $tmp = DB::select(DB::raw($query_curso));
        $curso = $tmp[0];
        $curso->INICIO = date("d/m/Y", strtotime($curso->INICIO));
        $curso->FIN = date("d/m/Y", strtotime($curso->FIN));

        // Ponente_curso
        $query_cursos_pon = "SELECT COD_PON FROM cursos_pon WHERE CURSO = ".$idCurso." AND PONENTE = ".$numPonente;
        $tmp = DB::select(DB::raw($query_cursos_pon));
        $idPonente = $tmp[0]->COD_PON;

        // Ponente
        $query_ponente = "SELECT NOMBRE, APELLIDO1, APELLIDO2, DNI, ENTIDAD, SUCURSAL, CONTROL, CUENTA FROM ponentes_cab WHERE CODIGO = ".$idPonente;
        $tmp = DB::select(DB::raw($query_ponente));
        $ponente = $tmp[0];

        $query_manutencion = "SELECT IFNULL(SUM(ALMUERZO), 0) AS ALMUERZO, 
                                    IFNULL(SUM(CENA), 0) AS CENA, 
                                    IFNULL(SUM(COTROS), 0) AS OTROS
                              FROM cursos_pon_man
                              WHERE CURSO = ".$idCurso;
        $tmp = DB::select(DB::raw($query_manutencion));
        $gastos_manutencion = $tmp[0]->ALMUERZO + $tmp[0]->CENA + $tmp[0]->OTROS;
        $gastos_manutencion = number_format($gastos_manutencion, 2, ",", ".");
        $gastosLetras = $this->convertEurosToString($gastos_manutencion);

        $data = array(
            'meses' => $meses,
            'curso' => $curso,
            'ponente' => $ponente,
            'gastos' => $gastos_manutencion,
            'gastosLetras' => $gastosLetras,
        );

        $pdf = PDF::loadView('informes.recibo-manutencion', $data);
        $pdf->setOption('header-spacing', 5);
        $pdf->setOption('header-html', './informes-subviews/header.html');
        $pdf->setOption('footer-spacing', 5);
        $pdf->setOption('footer-html', './informes-subviews/footer.html');
        return $pdf->download('recibo_manutencion'.$idPonente.'.pdf');
    }

    public function reciboDocencia($idCurso, $numPonente){
        // Meses del año en español
        $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

        //curso
        $query_curso = "SELECT NOMBRE, INICIO, FIN FROM cursos_cab WHERE CODIGO = ".$idCurso;
        $tmp = DB::select(DB::raw($query_curso));
        $curso = $tmp[0];
        $curso->INICIO = date("d/m/Y", strtotime($curso->INICIO));
        $curso->FIN = date("d/m/Y", strtotime($curso->FIN));

        // Ponente_curso
        $query_cursos_pon = "SELECT COD_PON, PONENCIA FROM cursos_pon WHERE CURSO = ".$idCurso." AND PONENTE = ".$numPonente;
        $tmp = DB::select(DB::raw($query_cursos_pon));
        $idPonente = $tmp[0]->COD_PON;
        $ponencia = $tmp[0]->PONENCIA;

        // Ponente
        $query_ponente = "SELECT NOMBRE, APELLIDO1, APELLIDO2, DNI, ENTIDAD, SUCURSAL, CONTROL, CUENTA FROM ponentes_cab WHERE CODIGO = ".$idPonente;
        $tmp = DB::select(DB::raw($query_ponente));
        $ponente = $tmp[0];
        $ponente->PONENCIA = $ponencia;

        // gastos del ponente
        $query_docencia = "SELECT IRPF,
                                  IFNULL(((IRPF*PRECIO)/100)*SUM(HORAS),0) AS IRPF_CANTIDAD,
                                  IFNULL(SUM(HORAS*PRECIO),0) AS DOCENCIA
                           FROM cursos_pon WHERE CURSO = ".$idCurso . " GROUP BY IRPF, PRECIO, HORAS";
        $tmp = DB::select(DB::raw($query_docencia));
        $gastos_docencia = $tmp[0]->DOCENCIA;
        $irpf_cantidad = $tmp[0]->IRPF_CANTIDAD;
        $irpf = $tmp[0]->IRPF;
        $cantidad = $gastos_docencia - $irpf_cantidad;

        $gastosLetras = $this->convertEurosToString($gastos_docencia);
        $gastos_docencia = number_format(floatval($gastos_docencia), 2, ',', '.');
        $irpf_cantidad = number_format(floatval($irpf_cantidad), 2, ',', '.');
        $cantidad = number_format(floatval($cantidad), 2, ',', '.');

        $data = array(
            'meses' => $meses,
            'curso' => $curso,
            'ponente' => $ponente,
            'gastos' => $gastos_docencia,
            'irpf_cantidad' => $irpf_cantidad,
            'irpf' => $irpf,
            'gastosLetras' => $gastosLetras,
            'cantidad' => $cantidad,
        );

        $pdf = PDF::loadView('informes.recibo-docencia', $data);
        $pdf->setOption('header-spacing', 5);
        $pdf->setOption('header-html', './informes-subviews/header.html');
        $pdf->setOption('footer-spacing', 5);
        $pdf->setOption('footer-html', './informes-subviews/footer.html');
        return $pdf->download('recibo_docencia'.$idPonente.'.pdf');
    }

    public function irpf($idCurso, $numPonente){
        // Meses del año en español
        $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

        //curso
        $query_curso = "SELECT NOMBRE, INICIO, FIN FROM cursos_cab WHERE CODIGO = ".$idCurso;
        $tmp = DB::select(DB::raw($query_curso));
        $curso = $tmp[0];
        $curso->INICIO = date("d/m/Y", strtotime($curso->INICIO));
        $curso->FIN = date("d/m/Y", strtotime($curso->FIN));

        // Ponente_curso
        $query_cursos_pon = "SELECT COD_PON, PONENCIA FROM cursos_pon WHERE CURSO = ".$idCurso." AND PONENTE = ".$numPonente;
        $tmp = DB::select(DB::raw($query_cursos_pon));
        $idPonente = $tmp[0]->COD_PON;
        $ponencia = $tmp[0]->PONENCIA;

        // Ponente
        $query_ponente = "SELECT NOMBRE, APELLIDO1, APELLIDO2, DNI FROM ponentes_cab WHERE CODIGO = ".$idPonente;
        $tmp = DB::select(DB::raw($query_ponente));
        $ponente = $tmp[0];
        $ponente->PONENCIA = $ponencia;

        // gastos del ponente
        $query_docencia = "SELECT IRPF,
                                  IFNULL(((IRPF*PRECIO)/100)*SUM(HORAS),0) AS IRPF_CANTIDAD,
                                  IFNULL(SUM(HORAS*PRECIO),0) AS DOCENCIA
                           FROM cursos_pon WHERE CURSO = ".$idCurso . " GROUP BY IRPF, PRECIO, HORAS";
        $tmp = DB::select(DB::raw($query_docencia));
        $gastos_docencia = $tmp[0]->DOCENCIA;
        $irpf_cantidad = $tmp[0]->IRPF_CANTIDAD;
        $irpf = $tmp[0]->IRPF;

        $cantidad = $gastos_docencia - $irpf_cantidad;
        $gastosLetras = $this->convertEurosToString($cantidad);

        $gastos_docencia = number_format($gastos_docencia, 2, ",", ".");
        $irpf_cantidad = number_format($irpf_cantidad, 2, ",", ".");
        $irpf = number_format($irpf, 2, ",", ".");
        $cantidad = number_format($cantidad, 2, ",", ".");

        // el director lo leemos de un fichero
        $director = file_get_contents('./info/director.txt');

        $data = array(
            'meses' => $meses,
            'curso' => $curso,
            'ponente' => $ponente,
            'gastos' => $gastos_docencia,
            'irpf_cantidad' => $irpf_cantidad,
            'irpf' => $irpf,
            'gastosLetras' => $gastosLetras,
            'cantidad' => $cantidad,
            'director' => $director
        );

        $pdf = PDF::loadView('informes.irpf', $data);
        $pdf->setOption('header-spacing', 5);
        $pdf->setOption('header-html', './informes-subviews/header.html');
        $pdf->setOption('footer-spacing', 5);
        $pdf->setOption('footer-html', './informes-subviews/footer.html');
        return $pdf->download('irpf'.$idPonente.'.pdf');
    }

    public function excelAdmitidos($idCurso) {
        $fichero = "admitidos curso ".$idCurso.".csv";
        $delimitador = ";";
        $query_admitidos = "SELECT profesores_cab.DNI, profesores_cab.NOMBRE, profesores_cab.APELLIDO1, profesores_cab.APELLIDO2, profesores_cab.TELEFONO, profesores_cab.MOVIL, profesores_cab.MAIL, cuerpos_tip.CUERPO, profesores_cab.ESPECIALIDAD, centros_cab.CENTRO
                    FROM cursos_adm
                    INNER JOIN profesores_cab ON cursos_adm.PROFESOR = profesores_cab.CODIGO
                    INNER JOIN centros_cab ON profesores_cab.CENTRO = centros_cab.CODIGO
                    INNER JOIN cuerpos_tip ON profesores_cab.CUERPO = cuerpos_tip.CODIGO
                    WHERE CURSO = ".$idCurso." ORDER BY profesores_cab.APELLIDO1, profesores_cab.APELLIDO2, profesores_cab.NOMBRE";
        $datos = DB::select(DB::raw($query_admitidos));
        if(isset($datos)) {
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename="'.$fichero.'";');

            $f = fopen('php://output', 'w');
            $linea = ['DNI', 'NOMBRE', 'APELLIDOS', 'TELEFONO', 'MÓVIL', 'EMAIL', 'CUERPO', 'ESPECIALIDAD', 'CENTRO'];
            fputcsv($f, $linea, $delimitador); // primera linea con la cabecera
            foreach ($datos as $objeto) {
                $linea = [$objeto->DNI, $objeto->NOMBRE, $objeto->APELLIDO1." ".$objeto->APELLIDO2, $objeto->TELEFONO, $objeto->MOVIL, $objeto->MAIL, $objeto->CUERPO, $objeto->ESPECIALIDAD, $objeto->CENTRO];
                fputcsv($f, $linea, $delimitador);
            }
        }
    }
    /* ====================================================================================================
    ========================                                                       ========================
    ========================     Funciones para los informes de los seminarios     ========================
    ========================                                                       ========================
    ==================================================================================================== */

    public function inforeFinalAsesor($idSeminario)
    {
        $datos = [];
        // datos básicos del seminario
        $query = "SELECT nombre, tipo, centro, inicio, fin, IFNULL(horas, 0) AS horas, IFNULL(horas_cerfifican, 0) AS horas_cerfifican, IFNULL(porcentaje, 0) AS porcentaje, asesor
                    FROM seminarios_cab
                    WHERE codigo=".$idSeminario;
        $results = DB::select(DB::raw($query));
        $datos_basicos = $results[0];

        // nombre del centro
        $datos['CENTRO'] = "";
        if(isset($datos_basicos->centro))
        {
            $query = "SELECT centro
                    FROM centros_cab
                    WHERE codigo=".$datos_basicos->centro;
            $results = DB::select(DB::raw($query));
            $datos['CENTRO'] = $results[0]->centro;
        }

        // título del seminario
        $datos['TITULO'] = $datos_basicos->nombre;

        // modalidad del seminario (seminario o grupo de trabajo)
        $datos['MODALIDAD'] = "";
        if(isset($datos_basicos->tipo))
        {
            $query = "SELECT tipo
                        FROM seminarios_tip
                        WHERE codigo=".$datos_basicos->tipo;
            $results = DB::select(DB::raw($query));
            $datos['MODALIDAD'] = $results[0]->tipo;
        }

        // fechas de inicio y fin
        $datos['INICIO'] = date("d/m/Y", strtotime($datos_basicos->inicio));
        $datos['FIN'] = date("d/m/Y", strtotime($datos_basicos->fin));

        // número de sesiones
        $query = "SELECT count(numero) AS sesiones
                    FROM actas_cab
                    WHERE seminario=".$idSeminario;
        $results = DB::select(DB::raw($query));
        $datos['SESIONES'] = $results[0]->sesiones;
        $datos['HORASINICIAL'] = $datos_basicos->horas;
        $datos['HORASFINAL'] = $datos_basicos->horas_cerfifican;

        //otros datos
        $datos['PORCENTAJE'] = $datos_basicos->porcentaje;
        $datos['ASESOR'] = $datos_basicos->asesor;

        // participantes con/sin derecho a certificación y coordinadores
        $query = "SELECT p.DNI, p.NOMBRE, p.APELLIDO1, p.APELLIDO2, s.coordinador, s.certifica
                    FROM seminarios_prf s
                    INNER JOIN profesores_cab p ON p.codigo=s.profesor
                    WHERE seminario=".$idSeminario." ORDER BY p.APELLIDO1, p.APELLIDO2, p.NOMBRE";
        $results = DB::select(DB::raw($query));
        $coordinadores = $results;
        $certifican = $results;
        $nocertifican = $results;

        $i = 0;
        foreach ($results as $result) {
            if($result->coordinador != 1) { // si NO es un coordinador
                unset($coordinadores[$i]); // lo elimino de coordinadores
                if($result->certifica == 1) { // SÍ certifica
                    unset($nocertifican[$i]); // lo eliminamos de los que NO certifican
                } else { // NO certifica
                    unset($certifican[$i]); // lo eliminamos de los que SÍ certifican
                }
            } else { // si es un coordinador lo eliminamos de ambos sitios
                unset($certifican[$i]);
                unset($nocertifican[$i]);
            }
            $i++;
        }

        // datos necesarios de la tabla seminarios_obs (valoración global y observaciones)
        $query = "SELECT VALFINALSOL, OBSFINALSOL
                    FROM seminarios_obs
                    WHERE seminario=".$idSeminario;
        $results = DB::select(DB::raw($query));
        $obs = $results[0];

        // Meses del año en español
        $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

        $data = array(
            "datos" => $datos,
            "obs" => $obs,
            'coordinadores' => $coordinadores,
            'certifican' => $certifican,
            'nocertifican' => $nocertifican,
            "meses" => $meses
        );

        $pdf = PDF::loadView('informes.informe-final-asesor', $data);
        $pdf->setOption('header-spacing', 5);
        $pdf->setOption('header-html', './informes-subviews/header.html');
        $pdf->setOption('footer-spacing', 5);
        $pdf->setOption('footer-html', './informes-subviews/footer.html');
        return $pdf->download('informe_final_asesor.pdf');
    }

    public function propuestaCertificacion($idSeminario)
    {
        $datos = [];
        // datos básicos del seminario
        $query = "SELECT nombre, tipo, centro, inicio, fin, IFNULL(horas, 0) AS horas, IFNULL(horas_cerfifican, 0) AS horas_cerfifican, IFNULL(porcentaje, 0) AS porcentaje, asesor, aprobado
                    FROM seminarios_cab
                    WHERE codigo=".$idSeminario;
        $results = DB::select(DB::raw($query));
        $datos_basicos = $results[0];

        // nombre del centro
        $datos['CENTRO'] = "";
        if(isset($datos_basicos->centro))
        {
            $query = "SELECT centro
                    FROM centros_cab
                    WHERE codigo=".$datos_basicos->centro;
            $results = DB::select(DB::raw($query));
            $datos['CENTRO'] = $results[0]->centro;
        }

        // título del seminario
        $datos['TITULO'] = $datos_basicos->nombre;

        // modalidad del seminario (seminario o grupo de trabajo)
        $datos['MODALIDAD'] = "";
        if(isset($datos_basicos->tipo))
        {
            $query = "SELECT tipo
                        FROM seminarios_tip
                        WHERE codigo=".$datos_basicos->tipo;
            $results = DB::select(DB::raw($query));
            $datos['MODALIDAD'] = $results[0]->tipo;
        }

        // fechas de inicio y fin
        $datos['INICIO'] = date("d/m/Y", strtotime($datos_basicos->inicio));
        $datos['FIN'] = date("d/m/Y", strtotime($datos_basicos->fin));

        // sesiones
        $query = "SELECT count(numero) AS sesiones
                    FROM actas_cab
                    WHERE seminario=".$idSeminario;
        $results = DB::select(DB::raw($query));
        $datos['SESIONES'] = $results[0]->sesiones;
        $datos['HORASINICIAL'] = $datos_basicos->horas;
        $datos['HORASFINAL'] = $datos_basicos->horas_cerfifican;

        //otros datos
        $datos['PORCENTAJE'] = $datos_basicos->porcentaje;
        $datos['ASESOR'] = $datos_basicos->asesor;
        $datos['APROBADO'] = date("d/m/Y", strtotime($datos_basicos->aprobado));

        // participantes con/sin derecho a certificación
        $query = "SELECT p.DNI, p.NOMBRE, p.APELLIDO1, p.APELLIDO2, s.coordinador, s.certifica
                    FROM seminarios_prf s
                    INNER JOIN profesores_cab p ON p.codigo=s.profesor
                    WHERE seminario=".$idSeminario." ORDER BY p.APELLIDO1, p.APELLIDO2, p.NOMBRE";
        $results = DB::select(DB::raw($query));
        $coordinadores = $results;
        $certifican = $results;
        $nocertifican = $results;

        $i = 0;
        foreach ($results as $result) {
            if($result->coordinador != 1) { // si NO es un coordinador continuo
                unset($coordinadores[$i]); // lo elimino de coordinadores
                if($result->certifica == 1) { // SÍ certifica
                    unset($nocertifican[$i]); // lo eliminamos de los que NO certifican
                } else { // NO certifica
                    unset($certifican[$i]); // lo eliminamos de los que SÍ certifican
                }
            } else { // si es un coordinador lo eliminamos de ambos sitios
                unset($certifican[$i]);
                unset($nocertifican[$i]);
            }
            $i++;
        }

        // datos necesarios de la tabla seminarios_obs (valoración global y observaciones)
        $query = "SELECT VALFINALSOL, OBSFINALSOL
                    FROM seminarios_obs
                    WHERE seminario=".$idSeminario;
        $results = DB::select(DB::raw($query));
        $obs = $results[0];

        // Meses del año en español
        $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

        // el director lo leemos de un fichero
        $director = file_get_contents('./info/director.txt');

        $data = array(
            "datos" => $datos,
            "obs" => $obs,
            'coordinadores' => $coordinadores,
            'certifican' => $certifican,
            'nocertifican' => $nocertifican,
            "meses" => $meses,
            "director" => $director
        );

        $pdf = PDF::loadView('informes.propuesta-certificacion', $data);
        $pdf->setOption('header-spacing', 5);
        $pdf->setOption('header-html', './informes-subviews/header.html');
        $pdf->setOption('footer-spacing', 5);
        $pdf->setOption('footer-html', './informes-subviews/footer.html');
        return $pdf->download('propuesta_certificacion.pdf');
    }




    /* ====================================================================================================
    ========================                                                       ========================
    ========================       Funciones auxiliares privadas de la clase       ========================
    ========================                                                       ========================
    ==================================================================================================== */

    protected function createTemporaryFile($content = null, $extension = null){
        $filename = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . uniqid('easymed_emr', true);

        if (null !== $extension) {
            $filename .= '.'.$extension;
        }

        if (null !== $content) {
            file_put_contents($filename, $content);
        }

        $this->temporaryFiles[] = $filename;

        return $filename;
    }

    private function convertEurosToString($cantidad){
        return mb_strtoupper(NumberToLetterConverter::convertirEurosEnLetras(floatval(str_replace(",", "", $cantidad))));
    }

    private function getAlumnosCertificados($idCurso, $certificado = true){
        $query = "SELECT profesores_cab.CODIGO, profesores_cab.DNI, profesores_cab.NOMBRE, profesores_cab.APELLIDO1, profesores_cab.APELLIDO2
                  FROM cursos_adm
                  INNER JOIN profesores_cab ON cursos_adm.PROFESOR = profesores_cab.CODIGO
                  WHERE CURSO = ".$idCurso. " ORDER BY profesores_cab.APELLIDO1";
        $profesores = DB::select(DB::raw($query));
        $curso = DB::table('cursos_cab')->where('CODIGO', $idCurso)->get()[0];
        $totalCurso = $curso->DURACION;
        $faltasPermitidas = ($totalCurso * 15)/100;

        foreach($profesores as $i => $profesor){
            $query = "SELECT SUM(HORAS) AS TOTALHORAS, SUM(JUSTIFICADAS) AS TOTALJUSTIFICADAS
                      FROM cursos_fal
                      WHERE CURSO = ".$idCurso." AND PROFESOR = ".$profesor->CODIGO;
            $result = DB::select(DB::raw($query))[0];
            $profesores[$i]->ASISTENCIA = $result->TOTALHORAS + $result->TOTALJUSTIFICADAS;
            $totalAlumno = $result->TOTALHORAS + $result->TOTALJUSTIFICADAS;

            // Para calcular las horas no presenciales. Las horas no presenciales esta en una sesion 'escondida'
            // en la tabla cursos_fal con numero de sesion 0.
            $query_nopresencial = "SELECT SUM(HORAS) AS NOPRESENCIALES, SUM(JUSTIFICADAS) AS NOPRESENCIALESJUSTIFICADAS
                      FROM cursos_fal
                      WHERE CURSO = ".$idCurso." AND PROFESOR = ".$profesor->CODIGO." AND SESION=0";
            $result_nopresencial = DB::select(DB::raw($query_nopresencial))[0];
            $profesores[$i]->ASISTENCIANOPRESENCIAL = $result_nopresencial->NOPRESENCIALES + $result_nopresencial->NOPRESENCIALESJUSTIFICADAS;

            if( ($totalAlumno == $totalCurso) && ($result->TOTALJUSTIFICADAS <= $faltasPermitidas) ) {
                if(!$certificado){
                    unset($profesores[$i]);
                }
            }else{
                if($certificado){
                    unset($profesores[$i]);
                }
            }
        }

        return $profesores;
    }

    private function getResumen($celdas) {
        $resumen = ['fdefinitivo' => ['infantil' => 0, 'primaria' => 0, 'secundaria' => 0, 'fp' => 0, 'otros' => 0],
                    'finterino' => ['infantil' => 0, 'primaria' => 0, 'secundaria' => 0, 'fp' => 0, 'otros' => 0],
                    'claboral' => ['infantil' => 0, 'primaria' => 0, 'secundaria' => 0, 'fp' => 0, 'otros' => 0],
                    'desempleado' => ['infantil' => 0, 'primaria' => 0, 'secundaria' => 0, 'fp' => 0, 'otros' => 0],
                    'otros' => ['infantil' => 0, 'primaria' => 0, 'secundaria' => 0, 'fp' => 0, 'otros' => 0],
                    'total' => ['infantil' => 0, 'primaria' => 0, 'secundaria' => 0, 'fp' => 0, 'otros' => 0]];

        foreach ($celdas as $celda) {
            switch (true) {
                // ======================================== funcionario definitivo ========================================
                case ($celda->administracion == 1 && $celda->cuerpo == 1): // infantil
                    $resumen['fdefinitivo']['infantil'] = $celda->total;
                    $resumen['total']['infantil'] = $resumen['total']['infantil'] + $celda->total;
                    break;
                case ($celda->administracion == 1 && $celda->cuerpo == 2): // primaria
                    $resumen['fdefinitivo']['primaria'] = $celda->total;
                    $resumen['total']['primaria'] = $resumen['total']['primaria'] + $celda->total;
                    break;
                case ($celda->administracion == 1 && $celda->cuerpo == 3): // secundaria
                    $resumen['fdefinitivo']['secundaria'] = $celda->total;
                    $resumen['total']['secundaria'] = $resumen['total']['secundaria'] + $celda->total;
                    break;
                case ($celda->administracion == 1 && $celda->cuerpo == 5): // fp
                    $resumen['fdefinitivo']['fp'] = $celda->total;
                    $resumen['total']['fp'] = $resumen['total']['fp'] + $celda->total;
                    break;
                case ($celda->administracion == 1 && ($celda->cuerpo == 4 || $celda->cuerpo > 5 || $celda->cuerpo == NULL))://otros
                    $resumen['fdefinitivo']['otros'] = $resumen['fdefinitivo']['otros'] + $celda->total;
                    $resumen['total']['otros'] = $resumen['total']['otros'] + $celda->total;
                    break;

                // ======================================== funcionario interino ========================================
                case ($celda->administracion == 2 && $celda->cuerpo == 1): // infantil
                    $resumen['finterino']['infantil'] = $celda->total;
                    $resumen['total']['infantil'] = $resumen['total']['infantil'] + $celda->total;
                    break;
                case ($celda->administracion == 2 && $celda->cuerpo == 2): // primaria
                    $resumen['finterino']['primaria'] = $celda->total;
                    $resumen['total']['primaria'] = $resumen['total']['primaria'] + $celda->total;
                    break;
                case ($celda->administracion == 2 && $celda->cuerpo == 3): // secundaria
                    $resumen['finterino']['secundaria'] = $celda->total;
                    $resumen['total']['secundaria'] = $resumen['total']['secundaria'] + $celda->total;
                    break;
                case ($celda->administracion == 2 && $celda->cuerpo == 5): // fp
                    $resumen['finterino']['fp'] = $celda->total;
                    $resumen['total']['fp'] = $resumen['total']['fp'] + $celda->total;
                    break;
                case ($celda->administracion == 2 && ($celda->cuerpo == 4 || $celda->cuerpo > 5 || $celda->cuerpo == NULL))://otros
                    $resumen['finterino']['otros'] = $resumen['finterino']['otros'] + $celda->total;
                    $resumen['total']['otros'] = $resumen['total']['otros'] + $celda->total;
                    break;

                // ======================================== contrato laboral ========================================
                case ($celda->administracion == 3 && $celda->cuerpo == 1): // infantil
                    $resumen['claboral']['infantil'] = $celda->total;
                    $resumen['total']['infantil'] = $resumen['total']['infantil'] + $celda->total;
                    break;
                case ($celda->administracion == 3 && $celda->cuerpo == 2): // primaria
                    $resumen['claboral']['primaria'] = $celda->total;
                    $resumen['total']['primaria'] = $resumen['total']['primaria'] + $celda->total;
                    break;
                case ($celda->administracion == 3 && $celda->cuerpo == 3): // secundaria
                    $resumen['claboral']['secundaria'] = $celda->total;
                    $resumen['total']['secundaria'] = $resumen['total']['secundaria'] + $celda->total;
                    break;
                case ($celda->administracion == 3 && $celda->cuerpo == 5): // fp
                    $resumen['claboral']['fp'] = $celda->total;
                    $resumen['total']['fp'] = $resumen['total']['fp'] + $celda->total;
                    break;
                case ($celda->administracion == 3 && ($celda->cuerpo == 4 || $celda->cuerpo > 5 || $celda->cuerpo == NULL))://otros
                    $resumen['claboral']['otros'] = $resumen['claboral']['otros'] + $celda->total;
                    $resumen['total']['otros'] = $resumen['total']['otros'] + $celda->total;
                    break;

                // ======================================== desempleado ========================================
                case ($celda->administracion == 4 && $celda->cuerpo == 1): // infantil
                    $resumen['desempleado']['infantil'] = $celda->total;
                    $resumen['total']['infantil'] = $resumen['total']['infantil'] + $celda->total;
                    break;
                case ($celda->administracion == 4 && $celda->cuerpo == 2): // primaria
                    $resumen['desempleado']['primaria'] = $celda->total;
                    $resumen['total']['primaria'] = $resumen['total']['primaria'] + $celda->total;
                    break;
                case ($celda->administracion == 4 && $celda->cuerpo == 3): // secundaria
                    $resumen['desempleado']['secundaria'] = $celda->total;
                    $resumen['total']['secundaria'] = $resumen['total']['secundaria'] + $celda->total;
                    break;
                case ($celda->administracion == 4 && $celda->cuerpo == 5): // fp
                    $resumen['desempleado']['fp'] = $celda->total;
                    $resumen['total']['fp'] = $resumen['total']['fp'] + $celda->total;
                    break;
                case ($celda->administracion == 4 && ($celda->cuerpo == 4 || $celda->cuerpo > 5 || $celda->cuerpo == NULL))://otros
                    $resumen['desempleado']['otros'] = $resumen['desempleado']['otros'] + $celda->total;
                    $resumen['total']['otros'] = $resumen['total']['otros'] + $celda->total;
                    break;

                // ======================================== otros ========================================
                case (($celda->administracion > 4 || $celda->administracion == NULL) && $celda->cuerpo == 1): // infantil
                    $resumen['otros']['infantil'] = $celda->total;
                    $resumen['total']['infantil'] = $resumen['total']['infantil'] + $celda->total;
                    break;
                case (($celda->administracion > 4 || $celda->administracion == NULL) && $celda->cuerpo == 2): // primaria
                    $resumen['otros']['primaria'] = $celda->total;
                    $resumen['total']['primaria'] = $resumen['total']['primaria'] + $celda->total;
                    break;
                case (($celda->administracion > 4 || $celda->administracion == NULL) && $celda->cuerpo == 3): // secundaria
                    $resumen['otros']['secundaria'] = $celda->total;
                    $resumen['total']['secundaria'] = $resumen['total']['secundaria'] + $celda->total;
                    break;
                case (($celda->administracion > 4 || $celda->administracion == NULL) && $celda->cuerpo == 5): // fp
                    $resumen['otros']['fp'] = $celda->total;
                    $resumen['total']['fp'] = $resumen['total']['fp'] + $celda->total;
                    break;
                case (($celda->administracion > 4 || $celda->administracion == NULL) && ($celda->cuerpo == 4 || $celda->cuerpo > 5 || $celda->cuerpo == NULL))://otros
                    $resumen['otros']['otros'] = $resumen['otros']['otros'] + $celda->total;
                    $resumen['total']['otros'] = $resumen['total']['otros'] + $celda->total;
                    break;
            }
        }
        return $resumen;
    }
}
