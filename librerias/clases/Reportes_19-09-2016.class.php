<?php

include "../../contenidos/desembolso/plantillaEstudioTitulos.php";
include "../../contenidos/reportes/reporteEscrituracion.php";
include "../../contenidos/reportes/informeProyectoActo.php";
ini_set('memory_limit', '256M');

class Reportes {

    public $arrTablas;
    public $arrGraficas;
    public $arrErrores;
    public $arrSoluciones;
    public $arrDesembolsosEstudioOferta;
    public $arrDesembolsoEstudioTitulos;
    public $arrDesembolsoTramite;
    public $arrAsignado;
    public $arrPostuladosInhabilitados;
    public $arrPostulados;
    public $arrEnProcesoPostulacion;
    public $arrInscritos;
    public $arrGrupos;
    public $arrSeqFormularios;
    public $seqFormularios;

    public function Reportes() {
        $this->arrTablas = array();
        $this->arrGraficas = array();
        $this->arrErrores = array();
        $this->arrSoluciones = array();
        $this->arrDesembolsosEstudioOferta = array();
        $this->arrDesembolsoEstudioTitulos = array();
        $this->arrDesembolsoTramite = array();
        $this->arrAsignado = array();
        $this->arrPostuladosInhabilitados = array();
        $this->arrPostulados = array();
        $this->arrEnProcesoPostulacion = array();
        $this->arrInscritos = array();
        $this->arrGrupos = array();
        $this->arrSeqFormularios = array();
    }

// fin constructor de la clase

    public function crearListadoReportes() {


        $this->arrTablas['titulos'][] = "Listado Exportables";
        $this->arrTablas['titulos'][] = "";

        $textoForm = "";

        $datos = &$this->arrTablas['datos'];

        $datosFila = &$datos[];
        $datosFila[] = "Numero Id Repetido";
        $datosFila[] = $this->textoFormLinks("ReporteIdRepetido");

        $datosFila = &$datos[];
        $datosFila[] = "Cruzar Edad Tipo Documento vs Fecha Postulacion";
        $datosFila[] = $this->textoFormLinks("ReporteCruzarEdadTodFchPos");

        $datosFila = &$datos[];
        $datosFila[] = "Tipo Documento Pasaporte o Extranjeria";
        $datosFila[] = $this->textoFormLinks("ReporteTipoDocPasExt");

        $datosFila = &$datos[];
        $datosFila[] = "Verificar Condicion Mayor de Edad";
        $datosFila[] = $this->textoFormLinks("ReporteCondicionMayorEdad");

        $datosFila = &$datos[];
        $datosFila[] = "Verificar Ingresos vs Reglamento";
        $datosFila[] = $this->textoFormLinks("ReporteIngresosVsReglamento");

        $datosFila = &$datos[];
        $datosFila[] = "Verificar Direccion o Barrio en Soacha";
        $datosFila[] = $this->textoFormLinks("ReporteSoacha");

        $datosFila = &$datos[];
        $datosFila[] = "Verificar si son realmente Beneficiarios del Subsidio";
        $datosFila[] = $this->textoFormLinks("ReporteBeneficiariosSubsidio");

        $datosFila = &$datos[];
        $datosFila[] = "Verificar si son realmente Beneficiarios de Caja de Compensacion";
        $datosFila[] = $this->textoFormLinks("ReporteBeneficiariosCajaCompensacion");

        $datosFila = &$datos[];
        $datosFila[] = "Cruce tipo Solucion con Cierre Financiero (Verifica Hogares con Promesa CompraVenta)";
        $datosFila[] = $this->textoFormLinks("ReporteCierreFinancieroConPromesa");

        $datosFila = &$datos[];
        $datosFila[] = "VR Subsidio Mejoramiento";
        $datosFila[] = $this->textoFormLinks("ReporteVRSubsidioMejoramiento");

        $datosFila = &$datos[];
        $datosFila[] = "Verificar Modalidad, Solucion vs Subsidio";
        $datosFila[] = $this->textoFormLinks("ReporteVerificaModalidadSolucion");

        $datosFila = &$datos[];
        $datosFila[] = "Ahorro, Credito y/o Subsidio Nacional sin Soporte";
        $datosFila[] = $this->textoFormLinks("ReporteAhorroCreditoSoporte");

        $datosFila = &$datos[];
        $datosFila[] = "Nombres Miembros de Hogar";
        $datosFila[] = $this->textoFormLinks("ReporteMiembrosHogar");

        $datosFila = &$datos[];
        $datosFila[] = "Datos de Contacto";
        $datosFila[] = $this->textoFormLinks("ReporteDatosDeContacto");

        $datosFila = &$datos[];
        $datosFila[] = "Todos con Estado";
        $datosFila[] = $this->textoFormLinks("ReporteTodosConEstado");

        $datosFila = &$datos[];
        $datosFila[] = "Reporte General";
        $datosFila[] = $this->textoFormLinks("ReporteGeneral");

        $datosFila = &$datos[];
        $datosFila[] = "Reporte Analisis Poblacion";
        $datosFila[] = $this->textoFormLinks("ReporteAnalisisPoblacion");

        $datosFila = &$datos[];
        $datosFila[] = "Resumen SDV. Metrovivienda y SDHT";
        $datosFila[] = $this->textoFormLinks("ReporteResumenMetroSDHT", "./descargas/RESUMEN SUBSIDIOS JUL.xlsx");

        $datosFila = &$datos[];
        $datosFila[] = "Analisis programa SDV Marzo 2009-2010";
        $datosFila[] = $this->textoFormLinks("ReporteAnalisisPrograma", "./descargas/Resumen SUBSIDIOS DE SDV.xlsx");

        $arrFiltros = &$this->arrFiltros;

        $arrFiltros = array();

        $datosFila = &$arrFiltros[];
        $datosFila[] = "Cedulas";
        $datosFila[] = $this->formArchivo("fileSecuenciales");
    }

    public function exportableReporteFormsEliminados() {
        global $aptBd;

        try {

            $txtCondiciones = ( $_POST['fchInicio'] != "" ) ? "AND bor.fchBorrado >= '" . $_POST['fchInicio'] . " 00:00:00'" : "";
            $txtCondiciones .= ( $_POST['fchFin'] != "" ) ? "AND bor.fchBorrado <= '" . $_POST['fchFin'] . " 23:59:59'" : "";

            $sql = "
            SELECT
              bor.seqFormulario as Formulario,
              tdo.txtTipoDocumento as TipoDocumento,
              bor.numDocumento as Documento,
              UPPER( bor.txtNombre ) as Nombre,
              bor.fchBorrado as Fecha,
              bor.txtComentario as Comentario
            FROM T_FRM_BORRADO bor
            INNER JOIN T_CIU_TIPO_DOCUMENTO tdo ON bor.seqTipoDocumento = tdo.seqTipoDocumento
            WHERE 1 = 1
            $txtCondiciones
          ";
            $objRes = $aptBd->execute($sql);
            $this->obtenerReportesGeneral($objRes, "ReporteFormsEliminados");
        } catch (Exception $objError) {
            $arrErrores[] = "No se pudo obtener el reporte de formularios eliminados";
            imprimirMensajes($arrErrores, array());
        }
    }

    public function exportableReporteDatosDeContacto() {

        global $aptBd;

        $arrErrores = &$this->arrErrores;

        if (empty($arrErrores)) {

            $sql = "SELECT
                        frm.seqFormulario,
                        frm.txtFormulario,
                        ciu.numDocumento,
                        UPPER(CONCAT(ciu.txtNombre1, ' ', ciu.txtNombre2, ' ', ciu.txtApellido1, ' ', ciu.txtApellido2)) AS Nombre,
                        if(frm.bolDesplazado = 1, 'Si', 'No') AS Desplazado,
                        moa.txtModalidad,
                        sol.txtSolucion,
                        frm.txtDireccion,
                        frm.numCelular,
                        frm.numTelefono1,
                        frm.numTelefono2,
                        frm.txtCorreo
                        FROM 
                        T_FRM_FORMULARIO frm
                        INNER JOIN T_FRM_HOGAR hog ON hog.seqFormulario = frm.seqFormulario
                        INNER JOIN T_CIU_CIUDADANO ciu ON hog.seqCiudadano = ciu.seqCiudadano
                        INNER JOIN T_FRM_MODALIDAD moa ON frm.seqModalidad = moa.seqModalidad
                        INNER JOIN T_FRM_SOLUCION sol ON frm.seqSolucion = sol.seqSolucion
                        WHERE hog.seqParentesco = 1
                        AND frm.seqFormulario in (" . $this->seqFormularios . ")";

            try {
                $objRes = $aptBd->execute($sql);

                $arrTitulosCampos[] = 'seqFormulario';
                $arrTitulosCampos[] = 'txtFormulario';
                $arrTitulosCampos[] = 'numDocumento';
                $arrTitulosCampos[] = 'Nombre';
                $arrTitulosCampos[] = 'Desplazado';
                $arrTitulosCampos[] = 'txtModalidad';
                $arrTitulosCampos[] = 'txtSolucion';
                $arrTitulosCampos[] = 'txtDireccion';
                $arrTitulosCampos[] = 'numCelular';
                $arrTitulosCampos[] = 'numTelefono1';
                $arrTitulosCampos[] = 'numTelefono2';
                $arrTitulosCampos[] = 'CorreoElectronico';

                $this->obtenerReportesGeneral($objRes, "ReporteDatosDeContacto", $arrTitulosCampos);
            } catch (Exception $objError) {
                $arrErrores[] = "Se ha producido un error al consultar los datos";
            }

            if (!empty($arrErrores)) {
                imprimirMensajes($arrErrores, array());
            }
        } else {
            imprimirMensajes($arrErrores, array());
        }
    }

    public function exportableReporteAnalisisPoblacion() {

        global $aptBd;

        $arrErrores = &$this->arrErrores;

        if (empty($arrErrores)) {

            $sql = "SELECT 
						frm.seqFormulario,
						frm.txtFormulario,
						if(frm.bolDesplazado = 1, 'Si', 'No') AS Desplazado,
						tpv.txtTipoVictima AS TipoVictima,
						moa.txtModalidad AS Modalidad,
						CONCAT(sol.txtDescripcion, ' (',  sol.txtSolucion, ')') AS Solucion,
						locfrm.txtLocalidad AS Localidad,
						if(trim(frm.txtBarrio) = '', 'Desconocido', frm.txtBarrio) AS Barrio,
						locdes.txtLocalidad AS LocalidadDesembolso,
						if(trim(des.txtBarrio) = '', 'Desconocido', des.txtBarrio) AS BarrioDesembolso,
						if((trim(des.txtCompraVivienda) = '' or des.txtCompraVivienda is null), 'Ninguna', des.txtCompraVivienda) as TipoViviendaComprar,
						(
						  SELECT 
						  tdo1.txtTipoDocumento
						  FROM 
						  T_FRM_HOGAR hog1
						  INNER JOIN T_CIU_CIUDADANO ciu1 on hog1.seqCiudadano = ciu1.seqCiudadano
						  INNER JOIN T_CIU_TIPO_DOCUMENTO tdo1 on ciu1.seqTipoDocumento = tdo1.seqTipoDocumento
						  WHERE hog1.seqFormulario = hog.seqFormulario
						  AND hog1.seqParentesco = 1
						) AS TipoDocumentoPPAL,
						(
						  SELECT 
						  ciu1.numDocumento
						  FROM 
						  T_FRM_HOGAR hog1
						  INNER JOIN T_CIU_CIUDADANO ciu1 on hog1.seqCiudadano = ciu1.seqCiudadano
						  WHERE hog1.seqFormulario = hog.seqFormulario
						  AND hog1.seqParentesco = 1
						) AS NumeroDocumentoPPAL,
						(
						  SELECT 
						  UPPER(CONCAT(ciu1.txtNombre1, ' ', ciu1.txtNombre2, ' ', ciu1.txtApellido1, ' ', ciu1.txtApellido2))
						  FROM 
						  T_FRM_HOGAR hog1
						  INNER JOIN T_CIU_CIUDADANO ciu1 on hog1.seqCiudadano = ciu1.seqCiudadano
						  WHERE hog1.seqFormulario = hog.seqFormulario
						  AND hog1.seqParentesco = 1
						) AS NombrePPAL,
						(
						  SELECT 
						  tdo1.txtTipoDocumento
						  FROM  T_CIU_TIPO_DOCUMENTO tdo1
						  WHERE ciu.seqTipoDocumento = tdo1.seqTipoDocumento
						)AS TipoDocumento,
						ciu.numDocumento AS Documento,
						UPPER(CONCAT(ciu.txtNombre1, ' ', ciu.txtNombre2, ' ', ciu.txtApellido1, ' ', ciu.txtApellido2)) AS Nombre,
						sex.txtSexo AS Sexo,
						if(ciu.bolLgtb = 1, 'Si', 'No') AS LGBT,
						ces1.txtCondicionEspecial as CondicionEspecial1,
						ces2.txtCondicionEspecial as CondicionEspecial2,
						ces3.txtCondicionEspecial as CondicionEspecial3,
						ucwords( cabezaFamilia( ciu.seqCondicionEspecial , ciu.seqCondicionEspecial2 , ciu.seqCondicionEspecial3 ) ) as txtCabezaFamilia,
						ucwords( mayor65anos( ciu.seqCondicionEspecial , ciu.seqCondicionEspecial2 , ciu.seqCondicionEspecial3 ) ) as txtMayor65Anos,
						ucwords( discapacitado( ciu.seqCondicionEspecial , ciu.seqCondicionEspecial2 , ciu.seqCondicionEspecial3 ) ) as txtDiscapacitado,
						ucwords( ningunaCondicionEspecial( ciu.seqCondicionEspecial , ciu.seqCondicionEspecial2 , ciu.seqCondicionEspecial3 ) ) as txtNingunaCondicionEspecial,
						par.txtParentesco AS Parentesco,
						ciu.fchNacimiento AS FechaNacimiento,
						FLOOR((DATEDIFF(NOW(), ciu.fchNacimiento) / 365)) AS Edad,
						rangoEdad(FLOOR((DATEDIFF(NOW(), ciu.fchNacimiento) / 365))) AS RangoEdad,
						etn.txtEtnia AS Etnia,
						(
							SELECT
								sum(dsol.valSolicitado)
							FROM T_DES_SOLICITUD dsol 
							WHERE
								frm.seqFormulario = des.seqFormulario AND 
								dsol.seqDesembolso = des.seqDesembolso
						) as ValorSolicitado
						FROM T_FRM_FORMULARIO frm
						INNER JOIN T_FRM_HOGAR hog ON hog.seqFormulario = frm.seqFormulario
						INNER JOIN T_CIU_CIUDADANO ciu ON hog.seqCiudadano = ciu.seqCiudadano
						LEFT JOIN T_CIU_PARENTESCO par ON hog.seqParentesco = par.seqParentesco
						LEFT JOIN T_CIU_SEXO sex ON ciu.seqSexo = sex.seqSexo
						LEFT JOIN T_FRM_TIPOVICTIMA tpv ON ciu.seqTipoVictima = tpv.seqTipoVictima
						LEFT JOIN T_FRM_MODALIDAD moa ON frm.seqModalidad = moa.seqModalidad
						LEFT JOIN T_FRM_SOLUCION sol ON frm.seqSolucion = sol.seqSolucion
						LEFT JOIN T_CIU_ETNIA etn ON ciu.seqEtnia = etn.seqEtnia
						LEFT JOIN T_CIU_CONDICION_ESPECIAL ces1 ON ciu.seqCondicionEspecial = ces1.seqCondicionEspecial
						LEFT JOIN T_CIU_CONDICION_ESPECIAL ces2 ON ciu.seqCondicionEspecial2 = ces2.seqCondicionEspecial
						LEFT JOIN T_CIU_CONDICION_ESPECIAL ces3 ON ciu.seqCondicionEspecial3 = ces3.seqCondicionEspecial
						LEFT JOIN T_FRM_LOCALIDAD locfrm ON frm.seqLocalidad = locfrm.seqLocalidad
						LEFT JOIN T_DES_DESEMBOLSO des ON des.seqFormulario = frm.seqFormulario
						LEFT JOIN T_FRM_LOCALIDAD locdes ON des.seqLocalidad = locdes.seqLocalidad
						WHERE  frm.seqFormulario in (" . $this->seqFormularios . ")
					";
            try {
                //pr( $sql ); die( );
                $objRes = $aptBd->execute($sql);

                $arrTitulosCampos[] = 'seqFormulario';
                $arrTitulosCampos[] = 'txtFormulario';
                $arrTitulosCampos[] = 'Desplazado';
                $arrTitulosCampos[] = 'Tipo Victima';
                $arrTitulosCampos[] = 'Modalidad';
                $arrTitulosCampos[] = 'Solucion';
                $arrTitulosCampos[] = 'Localidad';
                $arrTitulosCampos[] = 'Barrio';
                $arrTitulosCampos[] = 'LocalidadDesembolso';
                $arrTitulosCampos[] = 'BarrioDesembolso';
                $arrTitulosCampos[] = 'TipoViviendaComprar';
                $arrTitulosCampos[] = 'TipoDocumentoPPAL';
                $arrTitulosCampos[] = 'NumeroDocumentoPPAL';
                $arrTitulosCampos[] = 'NombrePPAL';
                $arrTitulosCampos[] = 'TipoDocumento';
                $arrTitulosCampos[] = 'Documento';
                $arrTitulosCampos[] = 'Nombre';
                $arrTitulosCampos[] = 'Sexo';
                $arrTitulosCampos[] = 'LGBT';
                $arrTitulosCampos[] = 'CondicionEspecial1';
                $arrTitulosCampos[] = 'CondicionEspecial2';
                $arrTitulosCampos[] = 'CondicionEspecial3';
                $arrTitulosCampos[] = 'txtCabezaFamilia';
                $arrTitulosCampos[] = 'txtMayor65Anos';
                $arrTitulosCampos[] = 'txtDiscapacitado';
                $arrTitulosCampos[] = 'txtNingunaCondicionEspecial';
                $arrTitulosCampos[] = 'Parentesco';
                $arrTitulosCampos[] = 'FechaNacimiento';
                $arrTitulosCampos[] = 'Edad';
                $arrTitulosCampos[] = 'RangoEdad';
                $arrTitulosCampos[] = 'Etnia';


                $this->obtenerReportesGeneral($objRes, "ReporteAnalisisPoblacion", $arrTitulosCampos);
            } catch (Exception $objError) {
                $arrErrores[] = "Se ha producido un error al consultar los datos";
            }

            if (!empty($arrErrores)) {
                imprimirMensajes($arrErrores, array());
            }
        } else {
            imprimirMensajes($arrErrores, array());
        }
    }

    public function exportableReporteGeneral() {

        global $aptBd;

        $arrErrores = &$this->arrErrores;

        if (empty($arrErrores)) {

            $sql = "SELECT
						frm.seqFormulario,
						frm.txtFormulario,
                                                frm.fchVigencia as Vigencia_SDV,
						CONCAT(eta.txtEtapa, ' ', epr.txtEstadoProceso) AS EstadoProceso,
						if(frm.bolDesplazado = 1, 'Si', 'No') AS Desplazado,
						moa.txtModalidad,
						sol.txtDescripcion as DescripcionSolucion,
						sol.txtSolucion,
						if(frm.bolCerrado = 1, 'Si', 'No') AS Cerrado,
						(
						  SELECT 
						   tdo.txtTipoDocumento
						   FROM T_FRM_HOGAR hog1
						   INNER JOIN T_CIU_CIUDADANO ciu1 ON hog1.seqCiudadano = ciu1.seqCiudadano
						   INNER JOIN T_CIU_TIPO_DOCUMENTO tdo ON ciu1.seqTipoDocumento = tdo.seqTipoDocumento
						   WHERE hog1.seqFormulario = hog.seqFormulario AND hog1.seqParentesco = 1
						) AS TipoDocumentoPPAL,
						(
						  SELECT 
						    ciu1.numDocumento
						   FROM T_FRM_HOGAR hog1
						   INNER JOIN T_CIU_CIUDADANO ciu1 ON hog1.seqCiudadano = ciu1.seqCiudadano
						   WHERE hog1.seqFormulario = hog.seqFormulario AND hog1.seqParentesco = 1
						) AS DocumentoPPAL,
						(
						  SELECT 
						    UPPER( CONCAT( ciu1.txtNombre1, ' ', ciu1.txtNombre2, ' ', ciu1.txtApellido1, ' ', ciu1.txtApellido2 ) )
						    FROM T_FRM_HOGAR hog1 
						    INNER JOIN T_CIU_CIUDADANO ciu1 ON hog1.seqCiudadano = ciu1.seqCiudadano
						    WHERE hog1.seqFormulario = hog.seqFormulario AND hog1.seqParentesco = 1
						) AS NombrePPAL,
						(
						  SELECT 
						    tdo.txtTipoDocumento
						    FROM T_CIU_TIPO_DOCUMENTO tdo
						    WHERE tdo.seqTipoDocumento = ciu.seqTipoDocumento
						) as TipoDocumento,
						ciu.numDocumento,
						UPPER( CONCAT( ciu.txtNombre1, ' ', ciu.txtNombre2, ' ', ciu.txtApellido1, ' ', ciu.txtApellido2 ) ) as Nombre,
						par.txtParentesco,
						sex.txtSexo,
						etn.txtEtnia,
						(
						  SELECT
						  ces.txtCondicionEspecial
						  FROM 
						  T_CIU_CONDICION_ESPECIAL ces 
						  WHERE ciu.seqCondicionEspecial = ces.seqCondicionEspecial
						) as CondicionEspecial1,
						(
						  SELECT
						  ces.txtCondicionEspecial
						  FROM 
						  T_CIU_CONDICION_ESPECIAL ces 
						  WHERE ciu.seqCondicionEspecial2 = ces.seqCondicionEspecial
						) as CondicionEspecial2,
						(
						  SELECT
						  ces.txtCondicionEspecial
						  FROM 
						  T_CIU_CONDICION_ESPECIAL ces 
						  WHERE ciu.seqCondicionEspecial3 = ces.seqCondicionEspecial
						) as CondicionEspecial3,
						ned.txtNivelEducativo,
						sis.txtSisben,
						frm.numAdultosNucleo,
						frm.numNinosNucleo,
						(frm.numAdultosNucleo + frm.numNinosNucleo) as numMiembrosHogar,
						if(ciu.bolLgtb = 1, 'Si', 'No') AS LGBT,
						ocu.txtOcupacion,
						eci.txtEstadoCivil,
						frm.fchInscripcion,
						frm.fchPostulacion,
						frm.fchUltimaActualizacion,
						frm.valAspiraSubsidio,
						pat.txtPuntoAtencion,
						viv.txtVivienda,
						frm.valIngresoHogar,
						frm.valSaldoCuentaAhorro,
						(
						  SELECT ban.txtBanco
						  FROM T_FRM_BANCO ban
						  WHERE ban.seqBanco = frm.seqBancoCuentaAhorro
						) AS EntidadAhorro1,
						frm.valSaldoCuentaAhorro2,
						(
						  SELECT ban.txtBanco
						  FROM T_FRM_BANCO ban
						  WHERE ban.seqBanco = frm.seqBancoCuentaAhorro2
						) AS EntidadAhorro2,
						frm.valCredito,
						(
						  SELECT ban.txtBanco
						  FROM T_FRM_BANCO ban
						  WHERE ban.seqBanco = frm.seqBancoCredito
						) AS EntidadCredito,
						frm.valDonacion,
						(
						  SELECT edo.txtEmpresaDonante
						  FROM T_FRM_EMPRESA_DONANTE edo
						  WHERE edo.seqEmpresaDonante = frm.seqEmpresaDonante
						) as entidadDonante,
						(frm.valSaldoCuentaAhorro + frm.valSaldoCuentaAhorro2) as SumaAhorro,
						(frm.valSaldoCuentaAhorro + frm.valSaldoCuentaAhorro2 + frm.valSubsidioNacional + frm.valAporteLote + frm.valSaldoCesantias + frm.valAporteAvanceObra + frm.valAporteMateriales + frm.valCredito + frm.valDonacion) as SumaCierreFinanciero,
						frm.valArriendo,
						(
						  SELECT loc.txtLocalidad
						  FROM T_FRM_LOCALIDAD loc
						  WHERE loc.seqLocalidad = frm.seqLocalidad
						) as localidad,
						frm.txtBarrio,
						if(frm.bolIdentificada = 1, 'Si', 'No') AS IdetificadaSolSDHT,
						if(frm.bolViabilizada = 1, 'Si', 'No') AS PerteneceViaSDHT,
						des.txtNombreVendedor,
						(
						  SELECT loc.txtLocalidad
						  FROM T_FRM_LOCALIDAD loc
						  WHERE loc.seqLocalidad = des.seqLocalidad
						) as localidadSolucion,
						des.txtBarrio,
						if((trim(des.txtCompraVivienda) = '' or des.txtCompraVivienda is null), 'Ninguna', des.txtCompraVivienda) as TipoViviendaComprar,
						(
							SELECT
								sum(dsol.valSolicitado)
							FROM T_DES_SOLICITUD dsol 
							WHERE
								frm.seqFormulario = des.seqFormulario AND 
								dsol.seqDesembolso = des.seqDesembolso
						) as ValorSolicitado
						FROM T_FRM_FORMULARIO frm
						INNER JOIN T_FRM_HOGAR hog on hog.seqFormulario = frm.seqFormulario
						INNER JOIN T_CIU_CIUDADANO ciu on hog.seqCiudadano = ciu.seqCiudadano
						left join T_CIU_PARENTESCO par ON hog.seqParentesco = par.seqParentesco
						left join T_CIU_SEXO sex on ciu.seqSexo = sex.seqSexo
						left join T_CIU_ETNIA etn on ciu.seqEtnia = etn.seqEtnia
						left join T_CIU_NIVEL_EDUCATIVO ned ON ciu.seqNivelEducativo = ned.seqNivelEducativo
						left join T_FRM_SISBEN sis on frm.seqSisben = sis.seqSisben
						left join T_CIU_OCUPACION ocu ON ciu.seqOcupacion = ocu.seqOcupacion
						left join T_CIU_ESTADO_CIVIL eci ON ciu.seqEstadoCivil = eci.seqEstadoCivil
						left JOIN T_FRM_ESTADO_PROCESO epr ON frm.seqEstadoProceso = epr.seqEstadoProceso
						INNER JOIN T_FRM_ETAPA eta ON epr.seqEtapa = eta.seqEtapa
						left join T_FRM_MODALIDAD moa ON frm.seqModalidad = moa.seqModalidad
						left join T_FRM_SOLUCION sol ON frm.seqSolucion = sol.seqSolucion
						left join T_FRM_PUNTO_ATENCION pat ON frm.seqPuntoAtencion = pat.seqPuntoAtencion
						left join T_FRM_VIVIENDA viv on frm.seqVivienda = viv.seqVivienda
						left join T_DES_DESEMBOLSO des on des.seqFormulario = frm.seqFormulario
						WHERE  frm.seqFormulario in (" . $this->seqFormularios . ")
				";
            try {
                $objRes = $aptBd->execute($sql);

                $arrTitulosCampos[] = 'seqFormulario';
                $arrTitulosCampos[] = 'txtFormulario';
                $arrTitulosCampos[] = 'Vigencia SDV';
                $arrTitulosCampos[] = 'EstadoProceso';
                $arrTitulosCampos[] = 'Desplazado';
                $arrTitulosCampos[] = 'txtModalidad';
                $arrTitulosCampos[] = 'DescripcionSolucion';
                $arrTitulosCampos[] = 'txtSolucion';
                $arrTitulosCampos[] = 'Cerrado';
                $arrTitulosCampos[] = 'TipoDocumentoPPAL';
                $arrTitulosCampos[] = 'DocumentoPPAL';
                $arrTitulosCampos[] = 'NombrePPAL';
                $arrTitulosCampos[] = 'TipoDocumento';
                $arrTitulosCampos[] = 'numDocumento';
                $arrTitulosCampos[] = 'Nombre';
                $arrTitulosCampos[] = 'txtParentesco';
                $arrTitulosCampos[] = 'txtSexo';
                $arrTitulosCampos[] = 'txtEtnia';
                $arrTitulosCampos[] = 'CondicionEspecial1';
                $arrTitulosCampos[] = 'CondicionEspecial2';
                $arrTitulosCampos[] = 'CondicionEspecial3';
                $arrTitulosCampos[] = 'txtNivelEducativo';
                $arrTitulosCampos[] = 'txtSisben';
                $arrTitulosCampos[] = 'numAdultosNucleo';
                $arrTitulosCampos[] = 'numNinosNucleo';
                $arrTitulosCampos[] = 'numMiembrosHogar';
                $arrTitulosCampos[] = 'LGBT';
                $arrTitulosCampos[] = 'txtOcupacion';
                $arrTitulosCampos[] = 'txtEstadoCivil';
                $arrTitulosCampos[] = 'fchInscripcion';
                $arrTitulosCampos[] = 'fchPostulacion';
                $arrTitulosCampos[] = 'fchUltimaActualizacion';
                $arrTitulosCampos[] = 'valAspiraSubsidio';
                $arrTitulosCampos[] = 'txtPuntoAtencion';
                $arrTitulosCampos[] = 'txtVivienda';
                $arrTitulosCampos[] = 'valIngresoHogar';
                $arrTitulosCampos[] = 'valSaldoCuentaAhorro';
                $arrTitulosCampos[] = 'EntidadAhorro1';
                $arrTitulosCampos[] = 'valSaldoCuentaAhorro2';
                $arrTitulosCampos[] = 'EntidadAhorro2';
                $arrTitulosCampos[] = 'valCredito';
                $arrTitulosCampos[] = 'EntidadCredito';
                $arrTitulosCampos[] = 'valDonacion';
                $arrTitulosCampos[] = 'entidadDonante';
                $arrTitulosCampos[] = 'SumaAhorro';
                $arrTitulosCampos[] = 'SumaCierreFinanciero';
                $arrTitulosCampos[] = 'valArriendo';
                $arrTitulosCampos[] = 'localidad';
                $arrTitulosCampos[] = 'txtBarrio';
                $arrTitulosCampos[] = 'IdetificadaSolSDHT';
                $arrTitulosCampos[] = 'PerteneceViaSDHT';
                $arrTitulosCampos[] = 'txtNombreVendedor';
                $arrTitulosCampos[] = 'localidadSolucion';
                $arrTitulosCampos[] = 'txtCompraVivienda';

                $this->obtenerReportesGeneral($objRes, "ReporteGeneral", $arrTitulosCampos);
            } catch (Exception $objError) {
                $arrErrores[] = "Se ha producido un error al consultar los datos";
            }

            if (!empty($arrErrores)) {
                imprimirMensajes($arrErrores, array());
            }
        } else {
            imprimirMensajes($arrErrores, array());
        }
    }

    public function exportableReporteMiembrosHogar() {

        global $aptBd;

        $arrErrores = &$this->arrErrores;

        if (empty($arrErrores)) {

            $sql = "SELECT 
						frm.seqFormulario,
						frm.txtFormulario,
						(
						 SELECT 
						   tdo1.txtTipoDocumento
						 FROM T_CIU_TIPO_DOCUMENTO tdo1
						 WHERE tdo1.seqTipoDocumento = ciu.seqTipoDocumento
						) AS tipoDocumento,
						ciu.numDocumento AS Documento,
						UPPER(CONCAT(ciu.txtNombre1, ' ', ciu.txtNombre2, ' ', ciu.txtApellido1, ' ', ciu.txtApellido2)) AS Nombre,
						CONCAT(eta.txtEtapa, ' ', epr.txtEstadoProceso) AS EstadoProceso,
						if(frm.bolDesplazado = 1, 'Si', 'No') AS Desplazado,
						if(frm.bolCerrado = 1, 'Si', 'No') AS Cerrado
						FROM T_FRM_FORMULARIO frm
						INNER JOIN T_FRM_HOGAR hog ON hog.seqFormulario = frm.seqFormulario
						INNER JOIN T_CIU_CIUDADANO ciu ON hog.seqCiudadano = ciu.seqCiudadano
						INNER JOIN T_FRM_ESTADO_PROCESO epr ON frm.seqEstadoProceso = epr.seqEstadoProceso
						INNER JOIN T_FRM_ETAPA eta ON epr.seqEtapa = eta.seqEtapa
						WHERE  frm.seqFormulario in (" . $this->seqFormularios . ")
				";

            try {
                $objRes = $aptBd->execute($sql);

                $arrTitulosCampos[] = 'seqFormulario';
                $arrTitulosCampos[] = 'txtFormulario';
                $arrTitulosCampos[] = 'tipoDocumento';
                $arrTitulosCampos[] = 'Documento';
                $arrTitulosCampos[] = 'Nombre';
                $arrTitulosCampos[] = 'EstadoProceso';
                $arrTitulosCampos[] = 'Desplazado';
                $arrTitulosCampos[] = 'Cerrado';

                $this->obtenerReportesGeneral($objRes, "ReporteMiembrosHogar", $arrTitulosCampos);
            } catch (Exception $objError) {
                $arrErrores[] = "Se ha producido un error al consultar los datos";
            }

            if (!empty($arrErrores)) {
                imprimirMensajes($arrErrores, array());
            }
        } else {
            imprimirMensajes($arrErrores, array());
        }
    }

    public function exportableReporteInscritosNoCC() {

        global $aptBd;

        $arrErrores = &$this->arrErrores;

        if (empty($arrErrores)) {

            $sql = "
						SELECT
							frm.seqFormulario,
							frm.txtFormulario,
							UPPER( CONCAT( ciu.txtNombre1, ' ', ciu.txtNombre2, ' ', ciu.txtApellido1, ' ', ciu.txtApellido2 ) ) as Nombre,
							ciu.numDocumento,
							tdo.txtTipoDocumento,
							moa.txtModalidad,
							CONCAT( eta.txtEtapa, ' - ', epr.txtEstadoProceso ) as EstadoProceso,
							frm.fchInscripcion,
							(
								SELECT
									seg.txtComentario
								FROM T_SEG_SEGUIMIENTO seg
								WHERE seg.seqFormulario = frm.seqFormulario
								ORDER BY seg.seqSeguimiento asc
							LIMIT 1
							) as UltimoSeguimiento,
							(
								SELECT
									seg.fchMovimiento
								FROM T_SEG_SEGUIMIENTO seg
								WHERE seg.seqFormulario = frm.seqFormulario
								ORDER BY seg.seqSeguimiento asc
								LIMIT 1
							) as FechaUltimoSeguimiento,
							UPPER( CONCAT( usu.txtNombre, ' ', usu.txtApellido ) ) as Usuario
						FROM T_FRM_FORMULARIO frm
							INNER JOIN T_FRM_HOGAR hog on hog.seqFormulario = frm.seqFormulario
							INNER JOIN T_CIU_CIUDADANO ciu on hog.seqCiudadano = ciu.seqCiudadano
							INNER JOIN T_FRM_MODALIDAD moa on frm.seqModalidad = moa.seqModalidad
							INNER JOIN T_FRM_ESTADO_PROCESO epr on frm.seqEstadoProceso = epr.seqEstadoProceso
							INNER JOIN T_FRM_ETAPA eta on epr.seqEtapa = eta.seqEtapa
							INNER JOIN T_COR_USUARIO usu on usu.seqUsuario = frm.seqUsuario
							INNER JOIN T_CIU_TIPO_DOCUMENTO tdo ON ciu.seqTipoDocumento = tdo.seqTipoDocumento
						WHERE hog.seqParentesco = 1
							AND ciu.seqTipoDocumento NOT IN (1,2)
					";

            try {
                $objRes = $aptBd->execute($sql);

                $arrTitulosCampos[] = 'seqFormulario';
                $arrTitulosCampos[] = 'txtFormulario';
                $arrTitulosCampos[] = 'Nombre';
                $arrTitulosCampos[] = 'numDocumento';
                $arrTitulosCampos[] = 'txtTipoDocumento';
                $arrTitulosCampos[] = 'txtModalidad';
                $arrTitulosCampos[] = 'EstadoProceso';
                $arrTitulosCampos[] = 'fchInscripcion';
                $arrTitulosCampos[] = 'UltimoSeguimiento';
                $arrTitulosCampos[] = 'FechaUltimoSeguimiento';
                $arrTitulosCampos[] = 'Usuario';

                $this->obtenerReportesGeneral($objRes, "ReporteInscritosNoCC", $arrTitulosCampos);
            } catch (Exception $objError) {
                $arrErrores[] = "Se ha producido un error al consultar los datos";
            }

            if (!empty($arrErrores)) {
                imprimirMensajes($arrErrores, array());
            }
        } else {
            imprimirMensajes($arrErrores, array());
        }
    }

    public function exportableReporteAhorroCreditoSoporte() {

        global $aptBd;

        $arrErrores = &$this->arrErrores;

        if (empty($arrErrores)) {
            $sql = "SELECT 
						frm.seqFormulario,
						frm.txtFormulario,
						ciu.numDocumento,
						moa.txtModalidad,
						sol.txtSolucion,
						UPPER(CONCAT(ciu.txtNombre1, ' ', ciu.txtNombre2, ' ', ciu.txtApellido1, ' ', ciu.txtApellido2)) AS Nombre,
						(
						  SELECT 
						  ban.txtBanco
						  FROM T_FRM_BANCO ban
						  WHERE ban.seqBanco = frm.seqBancoCuentaAhorro
						) AS Ahorro1,
						frm.txtSoporteCuentaAhorro,
						(
						  SELECT 
						  ban.txtBanco
						  FROM T_FRM_BANCO ban
						  WHERE ban.seqBanco = frm.seqBancoCuentaAhorro2
						) AS Ahorro2,
						frm.txtSoporteCuentaAhorro2,
						(
						  SELECT 
						  ban.txtBanco
						  FROM T_FRM_BANCO ban
						  WHERE ban.seqBanco = frm.seqBancoCredito
						) AS Credito,
						frm.txtSoporteCredito,
						frm.valSubsidioNacional,
						frm.txtSoporteSubsidioNacional,
						(
						  SELECT 
						  CONCAT(eta.txtEtapa, ' - ', epr.txtEstadoProceso)
						  FROM T_FRM_ETAPA eta 
						  INNER JOIN T_FRM_ESTADO_PROCESO epr ON epr.seqEtapa = eta.seqEtapa
						  WHERE frm.seqEstadoProceso = epr.seqEstadoProceso
						) AS EstadoProceso,
						if(frm.bolDesplazado = 1, 'Si', 'No') AS Desplazado,
						if(frm.bolCerrado = 1, 'Si', 'No') AS Cerrado
						FROM T_FRM_FORMULARIO frm
						INNER JOIN T_FRM_HOGAR hog ON hog.seqFormulario = frm.seqFormulario
						INNER JOIN T_CIU_CIUDADANO ciu ON hog.seqCiudadano = ciu.seqCiudadano
						INNER JOIN T_FRM_MODALIDAD moa ON frm.seqModalidad = moa.seqModalidad
            			INNER JOIN T_FRM_SOLUCION sol ON frm.seqSolucion = sol.seqSolucion
					 WHERE frm.seqFormulario in (" . $this->seqFormularios . ") AND
							hog.seqParentesco = 1
					       AND((frm.seqBancoCuentaAhorro <> 1 AND ( frm.txtSoporteCuentaAhorro = '' or frm.txtSoporteCuentaAhorro is null) )
							OR(frm.seqBancoCuentaAhorro2 <> 1 AND ( frm.txtSoporteCuentaAhorro2 = '' or frm.txtSoporteCuentaAhorro2 is null ) )
							OR(frm.seqBancoCredito <> 1 AND ( frm.txtSoporteCredito = '' or frm.txtSoporteCredito is null ))
							OR(frm.valSubsidioNacional > 0 and ( frm.txtSoporteSubsidioNacional = '' or frm.txtSoporteSubsidioNacional is null ))) 
					";

            try {
                $objRes = $aptBd->execute($sql);

                $arrTitulosCampos[] = 'seqFormulario';
                $arrTitulosCampos[] = 'txtFormulario';
                $arrTitulosCampos[] = 'numDocumento';
                $arrTitulosCampos[] = 'Nombre';
                $arrTitulosCampos[] = 'Ahorro1';
                $arrTitulosCampos[] = 'txtSoporteCuentaAhorro';
                $arrTitulosCampos[] = 'Ahorro2';
                $arrTitulosCampos[] = 'txtSoporteCuentaAhorro2';
                $arrTitulosCampos[] = 'Credito';
                $arrTitulosCampos[] = 'txtSoporteCredito';
                $arrTitulosCampos[] = 'valSubsidioNacional';
                $arrTitulosCampos[] = 'txtSoporteSubsidioNacional';
                $arrTitulosCampos[] = 'EstadoProceso';
                $arrTitulosCampos[] = 'Desplazado';
                $arrTitulosCampos[] = 'Cerrado';

                $this->obtenerReportesGeneral($objRes, "ReporteAhorroCreditoSoporte", $arrTitulosCampos);
            } catch (Exception $objError) {
                $arrErrores[] = "Se ha producido un error al consultar los datos";
            }

            if (!empty($arrErrores)) {
                imprimirMensajes($arrErrores, array());
            }
        } else {
            imprimirMensajes($arrErrores, array());
        }
    }

    public function exportableReporteVerificaModalidadSolucion() {

        global $aptBd;

        $arrErrores = &$this->arrErrores;

        if (empty($arrErrores)) {

            $sql = "SELECT 
						frm.seqFormulario,
						frm.txtFormulario,
						(
						  SELECT 
						  UPPER( CONCAT(ciu.txtNombre1, ' ', ciu.txtNombre2, ' ', ciu.txtApellido1, ' ', ciu.txtApellido2) )
						  FROM T_CIU_CIUDADANO ciu
						  WHERE hog.seqCiudadano = ciu.seqCiudadano
						) AS NombrePPAL,
						(
						  SELECT 
						  ciu.numDocumento 
						  FROM T_CIU_CIUDADANO ciu
						  WHERE hog.seqCiudadano = ciu.seqCiudadano
						) AS Documento,
						(
						  SELECT 
						  CONCAT(eta.txtEtapa, ' - ', epr.txtEstadoProceso) 
						  FROM T_FRM_ETAPA eta 
						  INNER JOIN T_FRM_ESTADO_PROCESO epr ON epr.seqEtapa = eta.seqEtapa
						  WHERE frm.seqEstadoProceso = epr.seqEstadoProceso
						) AS EstadoProceso,
						if(frm.bolDesplazado = 1, 'Si', 'No') AS Desplazado,
						moa.txtModalidad,
						sol.txtDescripcion,
						frm.valAspiraSubsidio,
						vsu.valSubsidio,
						frm.txtSoporteSubsidio
						FROM
						T_FRM_FORMULARIO frm
						INNER JOIN T_FRM_HOGAR hog ON hog.seqFormulario = frm.seqFormulario
						INNER JOIN T_FRM_MODALIDAD moa ON frm.seqModalidad = moa.seqModalidad
						INNER JOIN T_FRM_SOLUCION sol ON frm.seqSolucion = sol.seqSolucion
						INNER JOIN T_FRM_VALOR_SUBSIDIO vsu ON ( vsu.seqModalidad = frm.seqModalidad AND vsu.seqSolucion = frm.seqSolucion )
						WHERE frm.seqFormulario in (" . $this->seqFormularios . ") AND 
						frm.valAspiraSubsidio <= vsu.valSubsidio AND hog.seqParentesco = 1
					";


            try {
                $objRes = $aptBd->execute($sql);

                $arrTitulosCampos[] = 'seqFormulario';
                $arrTitulosCampos[] = 'txtFormulario';
                $arrTitulosCampos[] = 'NombrePPAL';
                $arrTitulosCampos[] = 'Documento';
                $arrTitulosCampos[] = 'EstadoProceso';
                $arrTitulosCampos[] = 'Desplazado';
                $arrTitulosCampos[] = 'Modalidad';
                $arrTitulosCampos[] = 'Solucion';
                $arrTitulosCampos[] = 'valAspiraSubsidio';
                $arrTitulosCampos[] = 'valSubsidio';
                $arrTitulosCampos[] = 'txtSoporteSubsidio';

                $this->obtenerReportesGeneral($objRes, "ReporteVerificaModalidadSolucion", $arrTitulosCampos);
            } catch (Exception $objError) {
                $arrErrores[] = "Se ha producido un error al consultar los datos";
            }
            if (!empty($arrErrores)) {
                imprimirMensajes($arrErrores, array());
            }
        } else {
            imprimirMensajes($arrErrores, array());
        }
    }

    public function exportableReporteTodosConEstado() {

        global $aptBd;

        $arrErrores = &$this->arrErrores;

        if (empty($arrErrores)) {

            $sql = "SELECT 
						frm.seqFormulario,
						frm.txtFormulario,
						ciu.numDocumento,
						UPPER(CONCAT(ciu.txtNombre1, ' ', ciu.txtNombre2, ' ', ciu.txtApellido1, ' ', ciu.txtApellido2)) AS Nombre,
						par.txtParentesco AS Parentesco,
						if(frm.bolDesplazado = 1, 'Si', 'No') AS Desplazado,
						moa.txtModalidad,
						sol.txtSolucion,
						CONCAT(eta.txtEtapa, ' - ', epr.txtEstadoProceso) AS EstadoProceso,
						if(frm.bolCerrado = 1, 'Si', 'No') AS Cerrado
						FROM 
						T_FRM_FORMULARIO frm
						INNER JOIN T_FRM_HOGAR hog ON hog.seqFormulario = frm.seqFormulario
						INNER JOIN T_CIU_CIUDADANO ciu ON hog.seqCiudadano = ciu.seqCiudadano
						INNER JOIN T_CIU_PARENTESCO par ON hog.seqParentesco = par.seqParentesco
						INNER JOIN T_FRM_MODALIDAD moa ON frm.seqModalidad = moa.seqModalidad
						INNER JOIN T_FRM_SOLUCION sol ON frm.seqSolucion = sol.seqSolucion
						INNER JOIN T_FRM_ESTADO_PROCESO epr ON frm.seqEstadoProceso = epr.seqEstadoProceso
						INNER JOIN T_FRM_ETAPA eta ON epr.seqEtapa = eta.seqEtapa
						WHERE frm.seqFormulario in (" . $this->seqFormularios . ")
				";

            try {
                $objRes = $aptBd->execute($sql);

                $arrTitulosCampos[] = 'seqFormulario';
                $arrTitulosCampos[] = 'txtFormulario';
                $arrTitulosCampos[] = 'numDocumento';
                $arrTitulosCampos[] = 'Nombre';
                $arrTitulosCampos[] = 'Parentesco';
                $arrTitulosCampos[] = 'Desplazado';
                $arrTitulosCampos[] = 'Modalidad';
                $arrTitulosCampos[] = 'Solucion';
                $arrTitulosCampos[] = 'EstadoProceso';
                $arrTitulosCampos[] = 'Cerrado';

                $this->obtenerReportesGeneral($objRes, "ReporteTodosConEstado", $arrTitulosCampos);
            } catch (Exception $objError) {
                $arrErrores[] = "Se ha producido un error al consultar los datos";
            }

            if (!empty($arrErrores)) {
                imprimirMensajes($arrErrores, array());
            }
        } else {
            imprimirMensajes($arrErrores, array());
        }
    }

    public function exportableReporteVRSubsidioMejoramiento() {

        global $aptBd;
        global $arrConfiguracion;

        $arrErrores = &$this->arrErrores;

        if (empty($arrErrores)) {
            $sql = "SELECT 
						frm.seqFormulario,
						frm.txtFormulario,
						ciu.numDocumento,
						UPPER(CONCAT(ciu.txtNombre1, ' ', ciu.txtNombre2, ' ', ciu.txtApellido1, ' ', ciu.txtApellido2)) AS Nombre,
						moa.txtModalidad,
						CONCAT(sol.txtDescripcion, '( ', sol.txtSolucion, ' )') AS Solucion,
						frm.valAvaluo,
						frm.valPresupuesto,
						frm.valTotal,
						CONCAT(eta.txtEtapa, ' ', epr.txtEstadoProceso) AS EstadoProceso,
						if(frm.bolDesplazado = 1, 'Si', 'No') AS Desplazado,
						if(frm.bolCerrado = 1, 'Si', 'No') AS Cerrado
						FROM 
						T_FRM_FORMULARIO frm
						INNER JOIN T_FRM_HOGAR hog ON hog.seqFormulario = frm.seqFormulario
						INNER JOIN T_CIU_CIUDADANO ciu ON hog.seqCiudadano = ciu.seqCiudadano
						INNER JOIN T_FRM_MODALIDAD moa ON frm.seqModalidad = moa.seqModalidad
						INNER JOIN T_FRM_SOLUCION sol ON frm.seqSolucion = sol.seqSolucion
						INNER JOIN T_FRM_ESTADO_PROCESO epr ON frm.seqEstadoProceso = epr.seqEstadoProceso
						INNER JOIN T_FRM_ETAPA eta ON epr.seqEtapa = eta.seqEtapa
					 	WHERE frm.seqFormulario in (" . $this->seqFormularios . ") AND  
							hog.seqParentesco = 1
					       AND frm.seqModalidad IN (3, 4)
					       AND frm.valTotal > (" . $arrConfiguracion['constantes']['salarioMinimo'] . " * 70)
						";

            try {
                $objRes = $aptBd->execute($sql);

                $arrTitulosCampos[] = 'seqFormulario';
                $arrTitulosCampos[] = 'txtFormulario';
                $arrTitulosCampos[] = 'numDocumento';
                $arrTitulosCampos[] = 'Nombre';
                $arrTitulosCampos[] = 'txtModalidad';
                $arrTitulosCampos[] = 'Solucion';
                $arrTitulosCampos[] = 'valAvaluo';
                $arrTitulosCampos[] = 'valPresupuesto';
                $arrTitulosCampos[] = 'valTotal';
                $arrTitulosCampos[] = 'EstadoProceso';
                $arrTitulosCampos[] = 'Desplazado';
                $arrTitulosCampos[] = 'Cerrado';

                $this->obtenerReportesGeneral($objRes, "ReporteVRSubsidioMejoramiento", $arrTitulosCampos);
            } catch (Exception $objError) {
                $arrErrores[] = "Se ha producido un error al consultar los datos";
            }

            if (!empty($arrErrores)) {
                imprimirMensajes($arrErrores, array());
            }
        } else {
            imprimirMensajes($arrErrores, array());
        }
    }

    /*
     * 
     *  TOCA CUADRAR BIEN EL QUERY
     * 
     */

    public function exportableReporteCierreFinancieroConPromesa() {

        global $aptBd;

        $arrErrores = &$this->arrErrores;

        if (empty($arrErrores)) {
            $sql = "SELECT 
						frm.seqFormulario,
						frm.txtFormulario,
						ciu.numDocumento,
						UPPER(CONCAT(ciu.txtNombre1, ' ', ciu.txtNombre2, ' ', ciu.txtApellido1, ' ', ciu.txtApellido2)) AS Nombre,
						moa.txtModalidad,
						CONCAT(sol.txtDescripcion, ' ( ', sol.txtSolucion, ' )') AS Solucion,
						if(frm.bolPromesaFirmada = 1, 'Si', 'No') AS PromesaFirmada,
						CONCAT(eta.txtEtapa, ' - ', epr.txtEstadoProceso) AS EstadoProceso,
						if(frm.bolDesplazado = 1, 'Si', 'No') AS Desplazado,
						if(frm.bolCerrado = 1, 'Si', 'No') AS Cerrado,
						frm.valTotalRecursos,
						frm.seqModalidad as modalidad, 
						frm.seqSolucion as solucion,
						frm.txtSoporteSubsidio
						FROM 
						T_FRM_FORMULARIO frm
						INNER JOIN T_FRM_HOGAR hog ON hog.seqFormulario = frm.seqFormulario
						INNER JOIN T_CIU_CIUDADANO ciu ON hog.seqCiudadano = ciu.seqCiudadano
						INNER JOIN T_FRM_MODALIDAD moa ON frm.seqModalidad = moa.seqModalidad
						INNER JOIN T_FRM_SOLUCION sol ON frm.seqSolucion = sol.seqSolucion
						INNER JOIN T_FRM_ESTADO_PROCESO epr ON frm.seqEstadoProceso = epr.seqEstadoProceso
						INNER JOIN T_FRM_ETAPA eta ON epr.seqEtapa = eta.seqEtapa
						WHERE frm.seqFormulario in (" . $this->seqFormularios . ") AND hog.seqParentesco = 1
					";
//				pr( $sql );die( );

            try {

                $arrTitulosCampos[] = 'seqFormulario';
                $arrTitulosCampos[] = 'txtFormulario';
                $arrTitulosCampos[] = 'numDocumento';
                $arrTitulosCampos[] = 'Nombre';
                $arrTitulosCampos[] = 'Modalidad';
                $arrTitulosCampos[] = 'Solucion';
                $arrTitulosCampos[] = 'PromesaFirmada';
                $arrTitulosCampos[] = 'EstadoProceso';
                $arrTitulosCampos[] = 'Desplazado';
                $arrTitulosCampos[] = 'Cerrado';
                $arrTitulosCampos[] = 'valTotalRecursos';
                $arrTitulosCampos[] = 'ValCierreFinanciero';
                $arrTitulosCampos[] = 'txtSoporteSubsidio';

                $objRes = $aptBd->execute($sql);
                $arrResultado = array();
                while ($objRes->fields) {
                    $valCierreFinanciero = $this->valorCierreFinanciero($objRes->fields['modalidad'], $objRes->fields['solucion']);
                    unset($objRes->fields['modalidad']);
                    unset($objRes->fields['solucion']);
                    if ($objRes->fields['modalidad'] != 5) {
                        if ($objRes->fields['valTotalRecursos'] < $valCierreFinanciero) {
                            $arrResultado[] = $objRes->fields;
                        }
                    } else {
                        if ($objRes->fields['valTotalRecursos'] > $valCierreFinanciero) {
                            $arrResultado[] = $objRes->fields;
                        }
                    }
                    $objRes->MoveNext();
                }

                $this->obtenerReportesGeneral($arrResultado, "ReporteCierreFinancieroConPromesa", $arrTitulosCampos);
            } catch (Exception $objError) {
                $arrErrores[] = "Se ha producido un error al consultar los datos";
            }
            if (!empty($arrErrores)) {
                imprimirMensajes($arrErrores, array());
            }
        } else {
            imprimirMensajes($arrErrores, array());
        }
    }

    public function exportableReporteBeneficiariosCajaCompensacion() {

        global $aptBd;

        $arrErrores = &$this->arrErrores;

        if (empty($arrErrores)) {
            $sql = "SELECT 
						frm.seqFormulario,
						frm.txtFormulario,
						moa.txtModalidad,
						(
						  SELECT 
						  tdo1.txtTipoDocumento
						  FROM T_FRM_HOGAR hog1
						  INNER JOIN T_CIU_CIUDADANO ciu1 ON hog1.seqCiudadano = ciu1.seqCiudadano
						  INNER JOIN T_CIU_TIPO_DOCUMENTO tdo1 ON ciu1.seqTipoDocumento = tdo1.seqTipoDocumento
						  WHERE hog1.seqFormulario = hog.seqFormulario
						  AND hog1.seqParentesco = 1
						) AS TipoDocumentoPPAL,
						(
						  SELECT 
						  ciu1.numDocumento
						  FROM T_FRM_HOGAR hog1 
						  INNER JOIN T_CIU_CIUDADANO ciu1 ON hog1.seqCiudadano = ciu1.seqCiudadano
						  WHERE hog1.seqFormulario = hog.seqFormulario 
						  AND hog1.seqParentesco = 1
						) AS DocumentoPPAL,
						(
						  SELECT 
						  UPPER(CONCAT(ciu1.txtNombre1, ' ', ciu1.txtNombre2, ' ', ciu1.txtApellido1, ' ', ciu1.txtApellido2))
						  FROM T_FRM_HOGAR hog1 
						  INNER JOIN T_CIU_CIUDADANO ciu1 ON hog1.seqCiudadano = ciu1.seqCiudadano
						  WHERE hog1.seqFormulario = hog.seqFormulario
						  AND hog1.seqParentesco = 1
						) AS NombrePPAL,
						(
						  SELECT 
						  tdo1.txtTipoDocumento
						  FROM T_CIU_TIPO_DOCUMENTO tdo1
						  WHERE tdo1.seqTipoDocumento = ciu.seqTipoDocumento
						) AS TipoDocumento,
						ciu.numDocumento AS Documento,
						UPPER(CONCAT(ciu.txtNombre1, ' ', ciu.txtNombre2, ' ', ciu.txtApellido1, ' ', ciu.txtApellido2)) AS Nombre,
						par.txtParentesco,
						CONCAT(eta.txtEtapa, ' ', epr.txtEstadoProceso) AS EstadoProceso,
						if(frm.bolDesplazado = 1, 'Si', 'No') AS Desplazado,
						if(frm.bolCerrado = 1, 'Si', 'No') AS Cerrado
						FROM 
						T_FRM_FORMULARIO frm
						INNER JOIN T_FRM_HOGAR hog ON hog.seqFormulario = frm.seqFormulario
						INNER JOIN T_CIU_CIUDADANO ciu ON hog.seqCiudadano = ciu.seqCiudadano
						INNER JOIN T_FRM_ESTADO_PROCESO epr ON frm.seqEstadoProceso = epr.seqEstadoProceso
						INNER JOIN T_FRM_ETAPA eta ON epr.seqEtapa = eta.seqEtapa
						INNER JOIN T_CIU_PARENTESCO par ON hog.seqParentesco = par.seqParentesco
						INNER JOIN T_FRM_MODALIDAD moa ON frm.seqModalidad = moa.seqModalidad
					 	WHERE frm.seqFormulario in (" . $this->seqFormularios . ") AND
							 ciu.seqCajaCompensacion <> 1
					";

            try {
                $objRes = $aptBd->execute($sql);

                $arrTitulosCampos[] = 'seqFormulario';
                $arrTitulosCampos[] = 'txtFormulario';
                $arrTitulosCampos[] = 'txtModalidad';
                $arrTitulosCampos[] = 'TipoDocumentoPPAL';
                $arrTitulosCampos[] = 'DocumentoPPAL';
                $arrTitulosCampos[] = 'NombrePPAL';
                $arrTitulosCampos[] = 'TipoDocumento';
                $arrTitulosCampos[] = 'Documento';
                $arrTitulosCampos[] = 'Nombre';
                $arrTitulosCampos[] = 'txtParentesco';
                $arrTitulosCampos[] = 'EstadoProceso';
                $arrTitulosCampos[] = 'Desplazado';
                $arrTitulosCampos[] = 'Cerrado';

                $this->obtenerReportesGeneral($objRes, "ReporteBeneficiariosCajaCompensacion", $arrTitulosCampos);
            } catch (Exception $objError) {
                $arrErrores[] = "Se ha producido un error al consultar los datos";
            }

            if (!empty($arrErrores)) {
                imprimirMensajes($arrErrores, array());
            }
        } else {
            imprimirMensajes($arrErrores, array());
        }
    }

    public function exportableReporteBeneficiariosSubsidio() {

        global $aptBd;

        $arrErrores = &$this->arrErrores;

        if (empty($arrErrores)) {
            $sql = "SELECT 
						frm.seqFormulario,
						frm.txtFormulario,
						moa.txtModalidad,
						(
						  SELECT 
						  tdo1.txtTipoDocumento
						  FROM T_FRM_HOGAR hog1
						  INNER JOIN T_CIU_CIUDADANO ciu1 ON hog1.seqCiudadano = ciu1.seqCiudadano
						  INNER JOIN T_CIU_TIPO_DOCUMENTO tdo1 ON ciu1.seqTipoDocumento = tdo1.seqTipoDocumento
						  WHERE hog1.seqFormulario = hog.seqFormulario
						  AND hog1.seqParentesco = 1
						) AS TipoDocumentoPPAL,
						(
						  SELECT 
						  ciu1.numDocumento
						  FROM T_FRM_HOGAR hog1 
						  INNER JOIN T_CIU_CIUDADANO ciu1 ON hog1.seqCiudadano = ciu1.seqCiudadano
						  WHERE hog1.seqFormulario = hog.seqFormulario
						  AND hog1.seqParentesco = 1
						) AS DocumentoPPAL,
						(
						  SELECT 
						  UPPER(CONCAT(ciu1.txtNombre1, ' ', ciu1.txtNombre2, ' ', ciu1.txtApellido1, ' ', ciu1.txtApellido2))
						  FROM T_FRM_HOGAR hog1 
						  INNER JOIN T_CIU_CIUDADANO ciu1 ON hog1.seqCiudadano = ciu1.seqCiudadano
						  WHERE hog1.seqFormulario = hog.seqFormulario
						  AND hog1.seqParentesco = 1
						) AS NombrePPAL,
						(
						  SELECT 
						  tdo1.txtTipoDocumento
						  FROM T_CIU_TIPO_DOCUMENTO tdo1 
						  INNER JOIN T_CIU_CIUDADANO ciu1 ON ciu1.seqTipoDocumento = tdo1.seqTipoDocumento
						  WHERE ciu1.seqCiudadano = ciu.seqCiudadano 
						) AS TipoDocumento,
						ciu.numDocumento AS Documento,
						UPPER(CONCAT(ciu.txtNombre1, ' ', ciu.txtNombre2, ' ', ciu.txtApellido1, ' ', ciu.txtApellido2)) AS Nombre,
						par.txtParentesco,
						CONCAT(eta.txtEtapa, ' ', epr.txtEstadoProceso) AS EstadoProceso,
						if(frm.bolDesplazado = 1, 'Si', 'No') AS Desplazado,
						if(frm.bolCerrado = 1, 'Si', 'No') AS Cerrado
						FROM 
						T_FRM_FORMULARIO frm
						INNER JOIN T_FRM_HOGAR hog ON hog.seqFormulario = frm.seqFormulario
						INNER JOIN T_CIU_CIUDADANO ciu ON hog.seqCiudadano = ciu.seqCiudadano
						INNER JOIN T_FRM_ESTADO_PROCESO epr ON frm.seqEstadoProceso = epr.seqEstadoProceso
						INNER JOIN T_FRM_ETAPA eta ON epr.seqEtapa = eta.seqEtapa
						INNER JOIN T_CIU_PARENTESCO par ON hog.seqParentesco = par.seqParentesco
						INNER JOIN T_FRM_MODALIDAD moa ON frm.seqModalidad = moa.seqModalidad
						WHERE frm.seqFormulario in (" . $this->seqFormularios . ") AND 
							ciu.bolBeneficiario = 1
							AND frm.bolCerrado = 1
					";

            try {
                $objRes = $aptBd->execute($sql);

                $arrTitulosCampos[] = 'seqFormulario';
                $arrTitulosCampos[] = 'txtFormulario';
                $arrTitulosCampos[] = 'txtModalidad';
                $arrTitulosCampos[] = 'TipoDocumentoPPAL';
                $arrTitulosCampos[] = 'DocumentoPPAL';
                $arrTitulosCampos[] = 'NombrePPAL';
                $arrTitulosCampos[] = 'TipoDocumento';
                $arrTitulosCampos[] = 'Documento';
                $arrTitulosCampos[] = 'Nombre';
                $arrTitulosCampos[] = 'txtParentesco';
                $arrTitulosCampos[] = 'EstadoProceso';
                $arrTitulosCampos[] = 'Desplazado';
                $arrTitulosCampos[] = 'Cerrado';

                $this->obtenerReportesGeneral($objRes, "ReporteBeneficiariosSubsidio", $arrTitulosCampos);
            } catch (Exception $objError) {
                $arrErrores[] = "Se ha producido un error al consultar los datos";
            }

            if (!empty($arrErrores)) {
                imprimirMensajes($arrErrores, array());
            }
        } else {
            imprimirMensajes($arrErrores, array());
        }
    }

    public function exportableReporteIngresosVsReglamento() {

        global $aptBd;

        $arrErrores = &$this->arrErrores;

        if (empty($arrErrores)) {
            $sql = "SELECT 
						frm.seqFormulario,
						frm.txtFormulario,
						UPPER(CONCAT(ciu.txtNombre1, ' ', ciu.txtNombre2, ' ', ciu.txtApellido1, ' ', ciu.txtApellido2)) AS Nombre,
						ciu.numDocumento,
						frm.valIngresoHogar,
						frm.valAspiraSubsidio,
						moa.txtModalidad,
						CONCAT(sol.txtDescripcion, '( ', sol.txtSolucion, ' )') AS Solucion,
						if(frm.bolDesplazado = 1, 'Si', 'No') AS Desplazado
						FROM 
						T_FRM_FORMULARIO frm
						INNER JOIN T_FRM_HOGAR hog ON hog.seqFormulario = frm.seqFormulario
						INNER JOIN T_CIU_CIUDADANO ciu ON hog.seqCiudadano = ciu.seqCiudadano
						INNER JOIN T_FRM_MODALIDAD moa ON frm.seqModalidad = moa.seqModalidad
						INNER JOIN T_FRM_SOLUCION sol ON frm.seqSolucion = sol.seqSolucion
						WHERE frm.seqFormulario in (" . $this->seqFormularios . ") AND
						hog.seqParentesco = 1 
						AND frm.valIngresoHogar > salarioReglamento(frm.seqSolucion)
					";

            try {
                $objRes = $aptBd->execute($sql);

                $arrTitulosCampos[] = 'seqFormulario';
                $arrTitulosCampos[] = 'txtFormulario';
                $arrTitulosCampos[] = 'Nombre';
                $arrTitulosCampos[] = 'numDocumento';
                $arrTitulosCampos[] = 'valIngresoHogar';
                $arrTitulosCampos[] = 'valSubsidio';
                $arrTitulosCampos[] = 'Modalidad';
                $arrTitulosCampos[] = 'Solucion';
                $arrTitulosCampos[] = 'Desplazado';

                $this->obtenerReportesGeneral($objRes, "ReporteIngresosVsReglamento", $arrTitulosCampos);
            } catch (Exception $objError) {
                $arrErrores[] = "Se ha producido un error al consultar los datos";
            }

            if (!empty($arrErrores)) {
                imprimirMensajes($arrErrores, array());
            }
        } else {
            imprimirMensajes($arrErrores, array());
        }
    }

    public function exportableReporteCondicionMayorEdad() {

        global $aptBd;

        $arrErrores = &$this->arrErrores;

        if (empty($arrErrores)) {
            $sql = "SELECT 
						frm.seqFormulario,
						frm.txtFormulario,
						(
						  SELECT 
						  tdo.txtTipoDocumento
						  FROM T_FRM_HOGAR hog1
						  INNER JOIN T_CIU_CIUDADANO ciu1 ON hog1.seqCiudadano = ciu1.seqCiudadano
						  INNER JOIN T_CIU_TIPO_DOCUMENTO tdo ON ciu1.seqTipoDocumento = tdo.seqTipoDocumento
						  WHERE hog1.seqFormulario = hog.seqFormulario
						  AND hog1.seqParentesco = 1
						) AS TipoDocumentoPPAL,
						(
						  SELECT 
						  ciu1.numDocumento
						  FROM 
						  T_FRM_HOGAR hog1
						  INNER JOIN T_CIU_CIUDADANO ciu1 on hog1.seqCiudadano = ciu1.seqCiudadano
						  WHERE hog1.seqFormulario = hog.seqFormulario
						  AND hog1.seqParentesco = 1
						) AS DocumentoPPAL,
						(
						  SELECT 
						  UPPER(CONCAT(ciu1.txtNombre1, ' ', ciu1.txtNombre2, ' ', ciu1.txtApellido1, ' ', ciu1.txtApellido2))
						  FROM T_FRM_HOGAR hog1
						  INNER JOIN T_CIU_CIUDADANO ciu1 on hog1.seqCiudadano = ciu1.seqCiudadano
						  WHERE hog1.seqFormulario = hog.seqFormulario
						  AND hog1.seqParentesco = 1
						) AS NombrePPAL,
						(
						  SELECT 
						  tod.txtTipoDocumento
						  FROM T_CIU_CIUDADANO ciu1 
						  INNER JOIN T_CIU_TIPO_DOCUMENTO tod ON ciu1.seqTipoDocumento = tod.seqTipoDocumento
						  WHERE ciu1.seqCiudadano = ciu.seqCiudadano 
						) AS TipoDocumento,
						ciu.numDocumento AS Documento,
						UPPER(CONCAT(ciu.txtNombre1, ' ', ciu.txtNombre2, ' ', ciu.txtApellido1, ' ', ciu.txtApellido2)) AS Nombre,
						ce1.txtCondicionEspecial AS CondicionEspecial1,
						ce2.txtCondicionEspecial AS CondicionEspecial2,
						ce3.txtCondicionEspecial AS CondicionEspecial3,
						frm.fchPostulacion,
						ADDDATE(ciu.fchNacimiento, INTERVAL 65 YEAR) AS fchTerceraEdad,
						ciu.fchNacimiento,
						if(SUBDATE(frm.fchPostulacion, INTERVAL 65 YEAR) >= ciu.fchNacimiento, 'Si', 'No') AS TerceraEdad,
						CONCAT(eta.txtEtapa, ' ', epr.txtEstadoProceso) AS EstadoProceso,
						if(frm.bolDesplazado = 1, 'Si', 'No') AS Desplazado,
						if(frm.bolCerrado = 1, 'Si', 'No') AS Cerrado
						FROM 
						T_FRM_FORMULARIO frm
						INNER JOIN T_FRM_HOGAR hog ON hog.seqFormulario = frm.seqFormulario
						INNER JOIN T_CIU_CIUDADANO ciu ON hog.seqCiudadano = ciu.seqCiudadano
						INNER JOIN T_FRM_ESTADO_PROCESO epr ON frm.seqEstadoProceso = epr.seqEstadoProceso
						INNER JOIN T_FRM_ETAPA eta ON epr.seqEtapa = eta.seqEtapa
						INNER JOIN T_CIU_CONDICION_ESPECIAL ce1 ON ciu.seqCondicionEspecial = ce1.seqCondicionEspecial
						INNER JOIN T_CIU_CONDICION_ESPECIAL ce2 ON ciu.seqCondicionEspecial2 = ce2.seqCondicionEspecial
						INNER JOIN T_CIU_CONDICION_ESPECIAL ce3 ON ciu.seqCondicionEspecial3 = ce3.seqCondicionEspecial
						WHERE frm.seqFormulario in (" . $this->seqFormularios . ")
						AND (
						(  
			              (
			                ciu.seqCondicionEspecial = 2
			                OR ciu.seqCondicionEspecial2 = 2
			                OR ciu.seqCondicionEspecial3 = 2
			              ) AND ADDDATE(ciu.fchNacimiento, INTERVAL 65 YEAR) > frm.fchPostulacion
			            ) OR 
			            (
			              (
			                ciu.seqCondicionEspecial <> 2
			                AND ciu.seqCondicionEspecial2 <> 2
			                AND ciu.seqCondicionEspecial3 <> 2
			              ) AND ADDDATE(ciu.fchNacimiento, INTERVAL 65 YEAR) <= frm.fchPostulacion
			            )
						)
					";

            try {
                $objRes = $aptBd->execute($sql);

                $arrTitulosCampos[] = 'seqFormulario';
                $arrTitulosCampos[] = 'txtFormulario';
                $arrTitulosCampos[] = 'TipoDocumentoPPAL';
                $arrTitulosCampos[] = 'DocumentoPPAL';
                $arrTitulosCampos[] = 'NombrePPAL';
                $arrTitulosCampos[] = 'TipoDocumento';
                $arrTitulosCampos[] = 'Documento';
                $arrTitulosCampos[] = 'Nombre';
                $arrTitulosCampos[] = 'CondicionEspecial1';
                $arrTitulosCampos[] = 'CondicionEspecial2';
                $arrTitulosCampos[] = 'CondicionEspecial3';
                $arrTitulosCampos[] = 'fchPostulacion';
                $arrTitulosCampos[] = 'fchTerceraEdad';
                $arrTitulosCampos[] = 'fchNacimiento';
                $arrTitulosCampos[] = 'TerceraEdad';
                $arrTitulosCampos[] = 'EstadoProceso';
                $arrTitulosCampos[] = 'Desplazado';
                $arrTitulosCampos[] = 'Cerrado';

                $this->obtenerReportesGeneral($objRes, "ReporteCondicionMayorEdad", $arrTitulosCampos);
            } catch (Exception $objError) {
                $arrErrores[] = "Se ha producido un error al consultar los datos";
            }
            if (!empty($arrErrores)) {
                imprimirMensajes($arrErrores, array());
            }
        } else {
            imprimirMensajes($arrErrores, array());
        }
    }

    public function exportableReporteIdRepetido() {

        global $aptBd;

        $arrErrores = &$this->arrErrores;
        $arrNumDocumento = array();

        if (empty($arrErrores)) {

            $sql = "SELECT 
						ciu.numDocumento
						FROM 
						T_FRM_HOGAR hog
						INNER JOIN T_CIU_CIUDADANO ciu ON hog.seqCiudadano = ciu.seqCiudadano
						WHERE hog.seqFormulario in (" . $this->seqFormularios . ") 
						GROUP BY ciu.numDocumento
						HAVING count(ciu.numDocumento) > 1
					";
            try {
                $objRes = $aptBd->execute($sql);
            } catch (Exception $objError) {
                $arrErrores[] = "Se ha producido un error al consultar los datos";
            }

            if (empty($arrErrores)) {

                while ($objRes->fields) {
                    $arrNumDocumento[] = $objRes->fields['numDocumento'];
                    $objRes->MoveNext();
                }

                $arrNumDocumento = ( empty($arrNumDocumento) ) ? "null" :
                        implode($arrNumDocumento, ",");

                $sql = "SELECT 
							DISTINCT hog.seqFormulario
							FROM 
							T_FRM_HOGAR hog 
							INNER JOIN T_CIU_CIUDADANO ciu ON hog.seqCiudadano = ciu.seqCiudadano
							WHERE ciu.numDocumento IN (" . $arrNumDocumento . ")
						";

                try {
                    $objRes = $aptBd->execute($sql);
                } catch (Exception $objError) {
                    $arrErrores[] = "Se ha producido un error al consultar los datos";
                }

                if (empty($arrErrores)) {

                    $arrSeqFormularios = array();
                    while ($objRes->fields) {
                        $arrSeqFormularios[] = $objRes->fields['seqFormulario'];
                        $objRes->MoveNext();
                    }
                    $arrSeqFormularios = ( empty($arrSeqFormularios) ) ? "null" :
                            implode($arrSeqFormularios, ",");


                    $sql = "SELECT 
								frm.seqFormulario,
								frm.txtFormulario,
								(
								  SELECT 
								  ciu1.numDocumento
								  FROM T_FRM_HOGAR hog1 
								  INNER JOIN T_CIU_CIUDADANO ciu1 ON hog1.seqCiudadano = ciu1.seqCiudadano
								  WHERE hog1.seqFormulario = frm.seqFormulario
								  AND hog1.seqParentesco = 1
								) AS DocumentoPPAL,
								(
								  SELECT 
								  UPPER(CONCAT(ciu1.txtNombre1, ' ', ciu1.txtNombre2, ' ', ciu1.txtApellido1, ' ', ciu1.txtApellido2))
								  FROM 
								  T_FRM_HOGAR hog1 
								  INNER JOIN T_CIU_CIUDADANO ciu1 ON hog1.seqCiudadano = ciu1.seqCiudadano
								  WHERE hog1.seqFormulario = frm.seqFormulario
								  AND hog1.seqParentesco = 1
								) AS NombrePPAL,
								(
								  SELECT 
								  tod.txtTipoDocumento
								  FROM T_CIU_TIPO_DOCUMENTO tod
								  WHERE ciu.seqTipoDocumento = tod.seqTipoDocumento
								) AS TipoDocumento,
								ciu.numDocumento AS Documento,
								UPPER(CONCAT(ciu.txtNombre1, ' ', ciu.txtNombre2, ' ', ciu.txtApellido1, ' ', ciu.txtApellido2)) AS Nombre,
								CONCAT(eta.txtEtapa, ' ', epr.txtEstadoProceso) AS EstadoProceso,
								if(frm.bolDesplazado = 1, 'Si', 'No') AS Desplazado
								FROM 
								T_FRM_FORMULARIO frm
								INNER JOIN T_FRM_HOGAR hog ON hog.seqFormulario = frm.seqFormulario
								INNER JOIN T_CIU_CIUDADANO ciu ON hog.seqCiudadano = ciu.seqCiudadano
								INNER JOIN T_FRM_ESTADO_PROCESO epr ON frm.seqEstadoProceso = epr.seqEstadoProceso
								INNER JOIN T_FRM_ETAPA eta ON epr.seqEtapa = eta.seqEtapa
								WHERE frm.seqFormulario IN (" . $arrSeqFormularios . ") 
								AND ciu.numDocumento in (" . $arrNumDocumento . ")
							";

                    try {
                        $objRes = $aptBd->execute($sql);
                        $arrTitulosCampos[] = 'seqFormulario';
                        $arrTitulosCampos[] = 'txtFormulario';
                        $arrTitulosCampos[] = 'DocumentoPPAL';
                        $arrTitulosCampos[] = 'NombrePPAL';
                        $arrTitulosCampos[] = 'TipoDocumento';
                        $arrTitulosCampos[] = 'Documento';
                        $arrTitulosCampos[] = 'Nombre';
                        $arrTitulosCampos[] = 'EstadoProceso';
                        $arrTitulosCampos[] = 'Desplazado';

                        $this->obtenerReportesGeneral($objRes, "ReporteIdRepetido", $arrTitulosCampos);
                    } catch (Exception $objError) {
                        $arrErrores[] = "Se ha producido un error al consultar los datos";
                    }
                    if (!empty($arrErrores)) {
                        imprimirMensajes($arrErrores, array());
                    }
                } else { //ERROR SQL PARA OBTENER seqFormulario DE LOS DOCUMENTOS
                    imprimirMensajes($arrErrores, array());
                }
            } else { // ERROR SQL PARA OBTENER DOCUMENTOS REPETIDAS
                imprimirMensajes($arrErrores, array());
            }
        } else {
            imprimirMensajes($arrErrores, array());
        }
    }

    public function exportableReporteSoacha() {

        global $aptBd;

        $arrErrores = &$this->arrErrores;

        if (empty($arrErrores)) {
            $sql = "SELECT 
						frm.seqFormulario,
						frm.txtFormulario,
						ciu.numDocumento,
						UPPER(CONCAT(ciu.txtNombre1, ' ', ciu.txtNombre2, ' ', ciu.txtApellido1, ' ', ciu.txtApellido2)) AS Nombre,
						frm.txtDireccion,
						frm.txtBarrio,
						CONCAT(eta.txtEtapa, ' ', epr.txtEstadoProceso) AS EstadoProceso,
						if(frm.bolDesplazado = 1, 'Si', 'No') AS Desplazado,
						if(frm.bolCerrado = 1, 'Si', 'No') AS Cerrado
						FROM 
						T_FRM_FORMULARIO frm
						INNER JOIN T_FRM_HOGAR hog ON hog.seqFormulario = frm.seqFormulario
						INNER JOIN T_CIU_CIUDADANO ciu ON hog.seqCiudadano = ciu.seqCiudadano
						INNER JOIN T_FRM_ESTADO_PROCESO epr ON frm.seqEstadoProceso = epr.seqEstadoProceso
						INNER JOIN T_FRM_ETAPA eta ON epr.seqEtapa = eta.seqEtapa
						WHERE frm.seqFormulario in (" . $this->seqFormularios . ") AND 
						hog.seqParentesco = 1 
						AND frm.bolCerrado = 1
						AND( frm.txtDireccion LIKE '%soacha%' OR frm.txtBarrio LIKE '%soacha%' )
					";

            try {
                $objRes = $aptBd->execute($sql);

                $arrTitulosCampos[] = 'seqFormulario';
                $arrTitulosCampos[] = 'txtFormulario';
                $arrTitulosCampos[] = 'numDocumento';
                $arrTitulosCampos[] = 'Nombre';
                $arrTitulosCampos[] = 'txtDireccion';
                $arrTitulosCampos[] = 'txtBarrio';
                $arrTitulosCampos[] = 'EstadoProceso';
                $arrTitulosCampos[] = 'Desplazado';
                $arrTitulosCampos[] = 'Cerrado';


                $this->obtenerReportesGeneral($objRes, "ReporteSoacha", $arrTitulosCampos);
            } catch (Exception $objError) {
                $arrErrores[] = "Se ha producido un error al consultar los datos";
            }
            if (!empty($arrErrores)) {
                imprimirMensajes($arrErrores, array());
            }
        } else {
            imprimirMensajes($arrErrores, array());
        }
    }

    public function exportableReporteTipoDocPasExt() {

        global $aptBd;

        $arrErrores = &$this->arrErrores;

        if (empty($arrErrores)) {
            $sql = "SELECT 
						frm.seqFormulario,
						(
						  SELECT 
						  tdo.txtTipoDocumento
						  FROM T_FRM_HOGAR hog1
						  INNER JOIN T_CIU_CIUDADANO ciu1 ON hog1.seqCiudadano = ciu1.seqCiudadano
						  INNER JOIN T_CIU_TIPO_DOCUMENTO tdo ON ciu1.seqTipoDocumento = tdo.seqTipoDocumento
						  WHERE hog1.seqFormulario = hog.seqFormulario
						  AND hog1.seqParentesco = 1
						)AS TipoDocumentoPPAL,
						(
						  SELECT 
						  ciu1.numDocumento
						  FROM T_FRM_HOGAR hog1
						  INNER JOIN T_CIU_CIUDADANO ciu1 ON hog1.seqCiudadano = ciu1.seqCiudadano
						  WHERE hog1.seqFormulario = hog.seqFormulario
						  AND hog1.seqParentesco = 1 
						) AS DocumentoPPAL,
						(
						  SELECT 
						  UPPER(CONCAT(ciu1.txtNombre1, ' ', ciu1.txtNombre2, ' ', ciu1.txtApellido1, ' ', ciu1.txtApellido2))
						  FROM T_FRM_HOGAR hog1
						  INNER JOIN T_CIU_CIUDADANO ciu1 ON hog1.seqCiudadano = ciu1.seqCiudadano
						  WHERE hog1.seqFormulario = hog.seqFormulario
						  AND hog1.seqParentesco = 1 
						) AS NombrePPAL,
						(
						  SELECT 
						  tod.txtTipoDocumento
						  FROM T_CIU_TIPO_DOCUMENTO tod
						  WHERE ciu.seqTipoDocumento = tod.seqTipoDocumento 
						) AS TipoDocumento,
						ciu.numDocumento AS Documento,
						UPPER(CONCAT(ciu.txtNombre1, ' - ', ciu.txtNombre2, ' ', ciu.txtApellido1, ' ', ciu.txtApellido2)) AS Nombre,
						CONCAT(eta.txtEtapa, ' ', epr.txtEstadoProceso) AS EstadoProceso,
						if(frm.bolDesplazado = 1, 'Si', 'No') AS Desplazado
						FROM 
						T_FRM_FORMULARIO frm
						INNER JOIN T_FRM_HOGAR hog ON hog.seqFormulario = frm.seqFormulario
						INNER JOIN T_CIU_CIUDADANO ciu ON hog.seqCiudadano = ciu.seqCiudadano
						INNER JOIN T_FRM_ESTADO_PROCESO epr ON frm.seqEstadoProceso = epr.seqEstadoProceso
						INNER JOIN T_FRM_ETAPA eta ON epr.seqEtapa = eta.seqEtapa
						WHERE frm.seqFormulario in (" . $this->seqFormularios . ")
						AND ciu.seqTipoDocumento IN (2, 5)
					";
            try {
                $objRes = $aptBd->execute($sql);

                $arrTitulosCampos[] = 'seqFormulario';
                $arrTitulosCampos[] = 'TipoDocumentoPPAL';
                $arrTitulosCampos[] = 'DocumentoPPAL';
                $arrTitulosCampos[] = 'NombrePPAL';
                $arrTitulosCampos[] = 'TipoDocumento';
                $arrTitulosCampos[] = 'Documento';
                $arrTitulosCampos[] = 'Nombre';
                $arrTitulosCampos[] = 'EstadoProceso';
                $arrTitulosCampos[] = 'Desplazado';


                $this->obtenerReportesGeneral($objRes, "ReporteTipoDocPasExt", $arrTitulosCampos);
            } catch (Exception $objError) {
                $arrErrores[] = "Se ha producido un error al consultar los datos";
            }
            if (!empty($arrErrores)) {
                imprimirMensajes($arrErrores, array());
            }
        } else {
            imprimirMensajes($arrErrores, array());
        }
    }

    public function obtenerJsReporteador($objRes, $arrTitulosCampos) {

        $txtJs = "var objReporteador = { ";
        $txtJs .= "datos: [";
        while ($objRes->fields) {
            $txtJs .= "{";
            foreach ($objRes->fields as $txtTitulo => $txtDato) {
                $txtDato = $txtDato;
                $txtTitulo = ereg_replace(" +", "", $txtTitulo);
                $txtJs .= "$txtTitulo:'$txtDato', ";
            }
            $txtJs = trim($txtJs, ", ");
            $txtJs .= "}, ";
            $objRes->MoveNext();
        }
        $txtJs = trim($txtJs, ", ");
        $txtJs .= " ], ";
        $txtJs .= "titulos: [";
        foreach ($arrTitulosCampos as $txtTitulo) {
            $txtTitulo = ereg_replace(" +", "", $txtTitulo);
            $txtTitulo = utf8_decode($txtTitulo);
            $txtJs .= "'$txtTitulo', ";
        }
        $txtJs = trim($txtJs, ", ");
        $txtJs .= "] ";
        $txtJs .= " }; ";
        return $txtJs;
    }

    public function obtenerReportesGeneralReporteador($objRes, $nombreArchivo) {
        $this->arrErrores = array();
        $this->obtenerReportesGeneral($objRes, $nombreArchivo);
        return;
    }

    public function obtenerReportesGeneral($objRes, $nombreArchivo, $arrTitulosCampos = array()) {
        
        if ($this) {
            $arrErrores = $this->arrErrores;
        } else {
            $arrErrores = array();
        }

        if (empty($arrErrores)) {

            $txtNombreArchivo = $nombreArchivo . date("Ymd_His") . ".xls";

            header("Content-disposition: attachment; filename=$txtNombreArchivo");
            header("Content-Type: application/force-download");
            header("Content-Type: application/vnd.ms-excel; charset=utf-8;");
            header("Content-Transfer-Encoding: binary");
            header("Pragma: no-cache");
            header("Expires: 1");

            // si es el objeto ResultSet
            if (is_object($objRes)) {
                if (!empty($objRes->fields)) {
                    echo utf8_decode(implode("\t", array_keys($objRes->fields))) . "\r\n";
                } else {
                    foreach ($arrTitulosCampos as $txtTitulo) {
                        echo utf8_decode($txtTitulo) . "\t";
                    }
                    echo "\r\n";
                }
                //print_r ($objRes->fields[CAmbios]);
                while ($objRes->fields) {
                    //echo ( utf8_decode(implode("\t", preg_replace("/\s+/", " ", $objRes->fields))) ) . "\r\n";
                    $dato = ( utf8_decode(implode("\t", preg_replace("/\s+/", " ", $objRes->fields))) );
                    $dato = str_replace('&nbsp;', ' ', $dato); // Reemplaza caracter por espacios
                    $dato = str_replace('<b>', '', $dato); // Reemplaza caracter por espacios
                    $dato = str_replace('</b>', '', $dato); // Reemplaza caracter por espacios
                    $dato = str_replace('<br>', ';', $dato); // Reemplaza caracter por espacios
                    //$dato = str_replace('<br>', '"&CARACTER(10)&"', $dato);
                    echo $dato . "\r\n";
                    $objRes->MoveNext();
                }

                // Si es un arreglo
            } elseif (is_array($objRes)) {

                if (!empty($objRes)) {
                    echo utf8_decode(implode("\t", array_keys($objRes[0]))) . "\r\n";
                } else {
                    foreach ($arrTitulosCampos as $txtTitulo) {
                        echo utf8_decode($txtTitulo) . "\t";
                    }
                    echo "\r\n";
                }

                foreach ($objRes as $arrDatos) {
                    echo utf8_decode(implode("\t", $arrDatos)) . "\r\n";
                }
            }
        } else {
            imprimirMensajes($arrErrores, array());
        }
    }

    private function textoFormLinks($idForm, $txtNombreArchivo = "") {

        if ($txtNombreArchivo == "") {
            $txtForm = "<a onclick = \"someterFormulario( 'mensajes', document.formFiltros , 
								'./contenidos/reportes/ReportesExportables.php?reporte=$idForm', true, false );\" 
								href='#'>Exportable</a>
						";
        } else {
            $txtForm = "<a id='$idForm' href='$txtNombreArchivo'>Exportable</a>";
        }


        return $txtForm;
    }

    public function consolidadoPrograma() {

        global $aptBd;
        $arrErrores = array();

        $sql = "SELECT 
                    est.seqEstadoProceso,
                    if(frm.bolDesplazado = 1, 'Desplazado', 'Independiente') AS Grupo,
                    frm.bolCerrado,
                    -- CONCAT(eta.txtEtapa, ' - ', est.txtEstadoProceso) AS estadoProceso,
                    count(1) AS cuenta
                    FROM 
                    T_FRM_FORMULARIO frm
                    INNER JOIN T_FRM_ESTADO_PROCESO est ON frm.seqEstadoProceso = est.seqEstadoProceso
                    INNER JOIN T_FRM_ETAPA eta ON est.seqEtapa = eta.seqEtapa
                    GROUP BY 1, 2, 3
                    ORDER BY 1, 2 desc";

        try {

            $objRes = $aptBd->execute($sql);

            $valPostuladoCosecha = 7;
            $valPostuladoInhabilitado = 8;
            $arrConsolidadoPrograma = &$this->arrTablas["datos"];

            $this->arrTablas['titulos'][] = "Consolidado Historico";
            $this->arrTablas['titulos'][] = "Independiente";
            $this->arrTablas['titulos'][] = "Desplazado";
            $this->arrTablas['titulos'][] = "Total Hogares";

            $arrDesembolso = array(
                32, //Desembolso - Parcial
                33  //Desembolso - Total
            );

            $arrAsignados = array(
                15,
                16,
                17,
                18,
                20,
                21,
                19,
                22,
                24,
                26,
                27,
                28,
                29,
                30,
                32,
                33
            );

            $arrPostulados = array(8,
                14,
                9,
                15,
                16,
                17,
                18,
                20,
                21,
                19,
                22,
                24,
                26,
                27,
                28,
                29,
                30,
                32,
                33
            );

            $arrProcesoPostulacion = array(5,
                6,
                7
            );

            $arrInscritos = array(
                10, //Inscripcion - Call Center (Opción Cierre)
                11, //Inscripcion - Call Center (Sin Cierre)
                12, //Inscripcion - Call Center (Recursos Cero)
                1, //Inscripcion - Inscrito
                13  //Inscripcion - Renuncia
            );

            $arrConsolidadoPrograma = array();
            $arrConsolidadoPrograma["Inscritos"] = array();
            $arrConsolidadoPrograma["Asignados"] = array();
            $arrConsolidadoPrograma["En Inscripcion"] = array();
            $arrConsolidadoPrograma["Proceso Postulacion"] = array();
            $arrConsolidadoPrograma["Postulados"] = array();
            $arrConsolidadoPrograma["Postulados Inhabilitados"] = array();
            $arrConsolidadoPrograma["Desembolso"] = array();

            $arrDatosGrafica = array();

            while ($objRes->fields) {

                $grupo = $objRes->fields["Grupo"];

                $arrConsolidadoPrograma["Inscritos"]["estadoProceso"] = "Inscritos";
                $arrConsolidadoPrograma["Inscritos"]["Independiente"] += $grupo ? $objRes->fields["cuenta"] : 0;
                $arrConsolidadoPrograma["Inscritos"]["Desplazado"] += ( $grupo == "Desplazado" ) ? $objRes->fields["cuenta"] : 0;
                $arrConsolidadoPrograma["Inscritos"]["Total"] += $objRes->fields["cuenta"];

                $arrDatosGrafica["Inscritos - $grupo"] += $objRes->fields["cuenta"];

                if (in_array($objRes->fields["seqEstadoProceso"], $arrAsignados)) {
                    $arrConsolidadoPrograma["Asignados"]["estadoProceso"] = "Asignados";
                    $arrConsolidadoPrograma["Asignados"]["Independiente"] += ( $grupo == "Independiente" ) ? $objRes->fields["cuenta"] : 0;
                    $arrConsolidadoPrograma["Asignados"]["Desplazado"] += ( $grupo == "Desplazado" ) ? $objRes->fields["cuenta"] : 0;
                    $arrConsolidadoPrograma["Asignados"]["Total"] += $objRes->fields["cuenta"];

                    $arrDatosGrafica["Asignados - $grupo"] += $objRes->fields["cuenta"];
                }

                if (in_array($objRes->fields["seqEstadoProceso"], $arrInscritos)) {
                    $arrConsolidadoPrograma["En Inscripcion"]["estadoProceso"] = "En Inscripcion";
                    $arrConsolidadoPrograma["En Inscripcion"]["Independiente"] += ( $grupo == "Independiente" ) ? $objRes->fields["cuenta"] : 0;
                    $arrConsolidadoPrograma["En Inscripcion"]["Desplazado"] += ( $grupo == "Desplazado" ) ? $objRes->fields["cuenta"] : 0;
                    $arrConsolidadoPrograma["En Inscripcion"]["Total"] += $objRes->fields["cuenta"];

                    $arrDatosGrafica["En Inscripcion - $grupo"] += $objRes->fields["cuenta"];
                }

                if (in_array($objRes->fields["seqEstadoProceso"], $arrPostulados)) {
                    $arrConsolidadoPrograma["Postulados"]["estadoProceso"] = "Postulados";
                    $arrConsolidadoPrograma["Postulados"]["Independiente"] += ( $grupo == "Independiente" ) ? $objRes->fields["cuenta"] : 0;
                    $arrConsolidadoPrograma["Postulados"]["Desplazado"] += ( $grupo == "Desplazado" ) ? $objRes->fields["cuenta"] : 0;
                    $arrConsolidadoPrograma["Postulados"]["Total"] += $objRes->fields["cuenta"];

                    $arrDatosGrafica["Postulados - $grupo"] += $objRes->fields["cuenta"];
                }

                if ($objRes->fields["seqEstadoProceso"] == $valPostuladoCosecha && $objRes->fields["bolCerrado"] == 1) {
                    $arrConsolidadoPrograma["Postulados"]["estadoProceso"] = "Postulados";
                    $arrConsolidadoPrograma["Postulados"]["Independiente"] += ( $grupo == "Independiente" ) ? $objRes->fields["cuenta"] : 0;
                    $arrConsolidadoPrograma["Postulados"]["Desplazado"] += ( $grupo == "Desplazado" ) ? $objRes->fields["cuenta"] : 0;
                    $arrConsolidadoPrograma["Postulados"]["Total"] += $objRes->fields["cuenta"];

                    $arrDatosGrafica["Postulados - $grupo"] += $objRes->fields["cuenta"];
                }

                if ($objRes->fields["seqEstadoProceso"] == $valPostuladoInhabilitado) {
                    $arrConsolidadoPrograma["Postulados Inhabilitados"]["estadoProceso"] = "Postulados Inhabilitados";
                    $arrConsolidadoPrograma["Postulados Inhabilitados"]["Independiente"] += ( $grupo == "Independiente" ) ? $objRes->fields["cuenta"] : 0;
                    $arrConsolidadoPrograma["Postulados Inhabilitados"]["Desplazado"] += ( $grupo == "Desplazado" ) ? $objRes->fields["cuenta"] : 0;
                    $arrConsolidadoPrograma["Postulados Inhabilitados"]["Total"] += $objRes->fields["cuenta"];

                    $arrDatosGrafica["Postulados Inhabilitados - $grupo"] += $objRes->fields["cuenta"];
                }

                if (in_array($objRes->fields["seqEstadoProceso"], $arrDesembolso)) {
                    $arrConsolidadoPrograma["Desembolso"]["estadoProceso"] = "En Desembolso";
                    $arrConsolidadoPrograma["Desembolso"]["Independiente"] += ( $grupo == "Independiente" ) ? $objRes->fields["cuenta"] : 0;
                    $arrConsolidadoPrograma["Desembolso"]["Desplazado"] += ( $grupo == "Desplazado" ) ? $objRes->fields["cuenta"] : 0;
                    $arrConsolidadoPrograma["Desembolso"]["Total"] += $objRes->fields["cuenta"];

                    $arrDatosGrafica["Desembolso - $grupo"] += $objRes->fields["cuenta"];
                }

                if (in_array($objRes->fields["seqEstadoProceso"], $arrProcesoPostulacion) && $objRes->fields["bolCerrado"] == 0) {
                    $arrConsolidadoPrograma["Proceso Postulacion"]["estadoProceso"] = "Proceso Postulacion";
                    $arrConsolidadoPrograma["Proceso Postulacion"]["Independiente"] += ( $grupo == "Independiente" ) ? $objRes->fields["cuenta"] : 0;
                    $arrConsolidadoPrograma["Proceso Postulacion"]["Desplazado"] += ( $grupo == "Desplazado" ) ? $objRes->fields["cuenta"] : 0;
                    $arrConsolidadoPrograma["Proceso Postulacion"]["Total"] += $objRes->fields["cuenta"];

                    $arrDatosGrafica["Proceso Postulacion - $grupo"] += $objRes->fields["cuenta"];
                }



                $objRes->MoveNext();
            }
            arsort($arrDatosGrafica);
            pr($arrDatosGrafica);

            $arrGraficas = &$this->arrGraficas; // apoyemos la pereza de Diego
            // Configuracion de la grafica
            $arrGraficas['configuracion']['ConsolidadoPrograma']['tipo'] = "columna";

            $arrGraficas['configuracion']['ConsolidadoPrograma']['ejes'][] = "ejeX";
            $arrGraficas['configuracion']['ConsolidadoPrograma']['ejes'][] = "conteo";

//	    		$arrGraficas['configuracion'][ 'ConsolidadoProgramaPie' ]['tipo'] = "pie";
//	    		
//	    		$arrGraficas['configuracion'][ 'ConsolidadoProgramaPie' ]['ejes'][] = "ejeX";
//	    		$arrGraficas['configuracion'][ 'ConsolidadoProgramaPie' ]['ejes'][] = "conteo";

            $arrConsolidadoPrograma = &$arrGraficas['datos']['ConsolidadoPrograma'];
//				$arrConsolidadoProgramaPie	= &$arrGraficas['datos']['ConsolidadoProgramaPie'];
            foreach ($arrDatosGrafica as $txtEjeY => $conteo) {
                $arrConsolidadoPrograma[$txtEjeY]['conteo'] = $conteo;
//	    			$arrConsolidadoProgramaPie[$txtEjeY]['conteo'] = $conteo;
            }
        } catch (Exception $objError) {
            $arrErrores = "Hubo un problema al intentar obtener los datos";
        }

        if (!empty($arrErrores)) {
            imprimirMensajes($arrErrores, array());
        }
    }

    public function resumenPrograma() {

        global $aptBd;

        $sql = "
				SELECT est.seqEstadoProceso,
			         if(frm.bolDesplazado = 1, 'Desplazado', 'Independiente') AS Grupo,
			         CONCAT(eta.txtEtapa, ' - ', est.txtEstadoProceso) AS estadoProceso,
			         count(1) AS cuenta
			    FROM T_FRM_FORMULARIO frm
			         INNER JOIN T_FRM_ESTADO_PROCESO est
			            ON frm.seqEstadoProceso = est.seqEstadoProceso
			         INNER JOIN T_FRM_ETAPA eta
			            ON est.seqEtapa = eta.seqEtapa
				GROUP BY 2, 3
				ORDER BY 1, 2 desc
			";

        try {

            $objRes = $aptBd->execute($sql);

            $arrDesembolsosEstudioOferta = &$this->arrDesembolsosEstudioOferta;
            $arrDesembolsoEstudioTitulos = &$this->arrDesembolsoEstudioTitulos;
            $arrDesembolsoTramite = &$this->arrDesembolsoTramite;
            $arrAsignado = &$this->arrAsignado;
            $arrPostuladosInhabilitados = &$this->arrPostuladosInhabilitados;
            $arrPostulados = &$this->arrPostulados;
            $arrEnProcesoPostulacion = &$this->arrEnProcesoPostulacion;
            $arrInscritos = &$this->arrInscritos;

            $arrDesembolsosEstudioOferta = array(
                19, //Desembolso - Revisión Oferta
                24, //Desembolso - Revisión Jurídica Aprobada
                26  //Desembolso - Revisión Técnica Aprobada
            );

            $arrDesembolsoEstudioTitulos = array(
                27, //Desembolso - Escrituracion
                28, //Desembolso - Estudio de Titulos
                29  //Desembolso - Estudio de Titulos Aprobado
            );

            $arrDesembolsoTramite = array(
                30, //Desembolso - Solicitud de desembolso
                32, //Desembolso - Parcial
                33  //Desembolso - Total
            );

            $arrAsignado = array(
                15, //Asignacion - Asignado
                20, //Asignacion - Bloqueado
                18  //Asignacion - Renuncia
            );

            $arrPostuladosInhabilitados = array(
                8 //Postulacion - Inhabilitado
            );

            $arrPostulados = array(
                7 //Postulacion - Cosecha
            );

            $arrEnProcesoPostulacion = array(
                6, //Postulacion - Riego
                5  //Postulacion - Siembra
            );

            $arrInscritos = array(
                10, //Inscripcion - Call Center (Opción Cierre)
                11, //Inscripcion - Call Center (Sin Cierre)
                12, //Inscripcion - Call Center (Recursos Cero)
                1, //Inscripcion - Inscrito
                13  //Inscripcion - Renuncia
            );

            $this->arrTablas['titulos'][] = "Grupo";
            $this->arrTablas['titulos'][] = "Estado Proceso";
            // $this->arrTablas['titulos'][] = "Desplazado/Independiente";
            $this->arrTablas['titulos'][] = "Conteo de Hogares";

            $filas = &$this->arrTablas['filas'];
            $total = &$this->arrTablas['total'];

            $totalFinal = 0;
            while ($objRes->fields) {
                $estadoProceso = $objRes->fields['estadoProceso'];

                $grupo = ucwords(strtolower($objRes->fields['Grupo']));
                if (!in_array($grupo, $this->arrGrupos)) {
                    $grupo = ucwords(strtolower($objRes->fields['Grupo']));
                    $this->arrGrupos[] = $grupo;
                }

                switch (true) {

                    case (in_array($objRes->fields['seqEstadoProceso'], $arrDesembolsosEstudioOferta)):

                        $filas['Desembolsos Estudio de Oferta'][$estadoProceso][$grupo] = $objRes->fields['cuenta'];
                        $total['Desembolsos Estudio de Oferta'] += $objRes->fields['cuenta'];

                        break;

                    case (in_array($objRes->fields['seqEstadoProceso'], $arrDesembolsoEstudioTitulos)):

                        $filas['Desembolsos Estudio de Títulos'][$estadoProceso][$grupo] = $objRes->fields['cuenta'];
                        // $total['Desembolsos Estudio de Títulos'] += $objRes->fields['cuenta'];
                        break;

                    case (in_array($objRes->fields['seqEstadoProceso'], $arrDesembolsoTramite)):

                        $filas['Desembolsos en tramite'][$estadoProceso][$grupo] = $objRes->fields['cuenta'];
                        // $total['Desembolsos en tramite'] += $objRes->fields['cuenta'];
                        break;


                    case (in_array($objRes->fields['seqEstadoProceso'], $arrAsignado)):

                        $filas['Asignados'][$estadoProceso][$grupo] = $objRes->fields['cuenta'];
                        //$total['Asignados'] += $objRes->fields['cuenta'];
                        break;

                    case (in_array($objRes->fields['seqEstadoProceso'], $arrPostuladosInhabilitados)):

                        $filas['Postulados Inhabilitados'][$estadoProceso][$grupo] = $objRes->fields['cuenta'];
                        $total['Postulados Inhabilitados'] += $objRes->fields['cuenta'];
                        break;

                    case (in_array($objRes->fields['seqEstadoProceso'], $arrPostulados)):

                        $filas['Postulados'][$estadoProceso][$grupo] = $objRes->fields['cuenta'];
                        $total['Postulados'] += $objRes->fields['cuenta'];
                        break;

                    case (in_array($objRes->fields['seqEstadoProceso'], $arrEnProcesoPostulacion)):

                        $filas['En proceso de postulación'][$estadoProceso][$grupo] = $objRes->fields['cuenta'];
                        $total['En proceso de postulación'] += $objRes->fields['cuenta'];
                        break;

                    case (in_array($objRes->fields['seqEstadoProceso'], $arrInscritos)):

                        $filas['Inscritos'][$estadoProceso][$grupo] = $objRes->fields['cuenta'];
                        $total['Inscritos'] += $objRes->fields['cuenta'];
                        break;
                }

                $totalFinal += $objRes->fields['cuenta'];

                $objRes->MoveNext();
            }
            //$total = array();
            $datos = &$this->arrTablas['datos'];

            $i = 0;
            foreach ($filas as $grupoResumen => $datosGrupoResumen) {


                if ($grupoResumen == "Asignados" || strstr($grupoResumen, 'Desembolsos')) {

                    $datosAsignados = array();

                    foreach ($datosGrupoResumen as $estadoProceso => $datosEstadoProceso) {
                        foreach ($this->arrGrupos as $grupo) {

                            $valTotal = $datosEstadoProceso[$grupo];
                            if ($grupo == "Desplazado") {
                                $datosAsignados["$grupoResumen Desplazados"][$estadoProceso] = $valTotal;
                                $total["$grupoResumen Desplazados"] += $valTotal;
                            } else {
                                $datosAsignados["$grupoResumen Independientes"][$estadoProceso] = $valTotal;
                                $total["$grupoResumen Independientes"] += $valTotal;
                            }
                        }
                    }

                    foreach ($datosAsignados as $grupoResumenAsignados => $datosResumenAsignados) {
                        $datos[$i][0] = $grupoResumenAsignados;
                        $datos[$i][1] = '';
                        // $datos[$i][2] = '';
                        $datos[$i][2] = $total[$grupoResumenAsignados];
                        $i++;

                        foreach ($datosResumenAsignados as $estadoProceso => $valorTotal) {
                            if ($valorTotal) {
                                $datos[$i][0] = '';
                                $datos[$i][1] = $estadoProceso;
                                $datos[$i][2] = $valorTotal;
                                $i++;
                            }
                        }
                    }
                } else {

                    $datos[$i][0] = $grupoResumen;
                    $datos[$i][1] = '';
                    $datos[$i][2] = $total[$grupoResumen];
                    $i++;

                    foreach ($datosGrupoResumen as $estadoProceso => $datosEstadoProceso) {

                        $datos[$i][0] = '';
                        $datos[$i][1] = $estadoProceso;
                        foreach ($this->arrGrupos as $grupo) {
                            $datos[$i][2] += $datosEstadoProceso[$grupo];
                        }
                        $i++;
                    }
                }
            }
            $datos[$i][0] = "TOTAL";
            $datos[$i][1] = '';
            $datos[$i][2] = $totalFinal;

            /**
             * CONSTRUYENDO EL ARREGLO DE GRAFICAS
             */
            $arrGraficas = &$this->arrGraficas; // apoyemos la pereza de Diego
            // Configuracion de la grafica
            $arrGraficas['configuracion']['ResumenPrograma']['tipo'] = "bar";

            $arrGraficas['configuracion']['ResumenPrograma']['ejes'][] = "ejeX";
            $arrGraficas['configuracion']['ResumenPrograma']['ejes'][] = "conteo";

            $arrGraficas['configuracion']['ResumenProgramaPie']['tipo'] = "pie";

            $arrGraficas['configuracion']['ResumenProgramaPie']['ejes'][] = "ejeX";
            $arrGraficas['configuracion']['ResumenProgramaPie']['ejes'][] = "conteo";

            // Datos de las graficas	    		
            $arrRegumenPrograma = &$arrGraficas['datos']['ResumenPrograma'];
            $arrRegumenProgramaPie = &$arrGraficas['datos']['ResumenProgramaPie'];
            foreach ($total as $txtEjeY => $conteo) {
                $arrRegumenPrograma[$txtEjeY]['conteo'] = $conteo;
                $arrRegumenProgramaPie[$txtEjeY]['conteo'] = $conteo;
            }
        } catch (Exception $objError) {
            $arrErrores = "Hubo un problema al intentar obtener los datos";
        }

        if (!empty($arrErrores)) {
            imprimirMensajes($arrErrores, array());
        }
    }

    public function exportableReporteCruzarEdadTodFchPos() {

        global $aptBd;

        $arrErrores = &$this->arrErrores;

        if (empty($arrErrores)) {
            $sql = "SELECT frm.seqFormulario,
					       frm.txtFormulario,
					       (
					       SELECT 
					        tdo.txtTipoDocumento
					       FROM T_CIU_TIPO_DOCUMENTO tdo WHERE tdo.seqTipoDocumento = ciu.seqTipoDocumento
					       )
					       AS TipoDocumento,
					       ciu.numDocumento,
					       UPPER(CONCAT(ciu.txtNombre1,
					                    ' ',
					                    ciu.txtNombre2,
					                    ' ',
					                    ciu.txtApellido1,
					                    ' ',
					                    ciu.txtApellido2))
					          AS txtNombre,
					       frm.fchPostulacion,
					       ADDDATE(ciu.fchNacimiento, INTERVAL 18 YEAR) AS fchMayorEdad,
					       ciu.fchNacimiento,
					       if(SUBDATE(frm.fchPostulacion, INTERVAL 18 YEAR) >= ciu.fchNacimiento,
					          'Si',
					          'No')
					          AS MayorEdad,
					       CONCAT(eta.txtEtapa, ' ', epr.txtEstadoProceso) AS txtEstadoProceso,
					       if(frm.bolDesplazado = 1, 'Si', 'No') AS Desplazado,
					       if(frm.bolCerrado = 1, 'Si', 'No') AS Cerrado
					  FROM T_FRM_FORMULARIO frm
					       INNER JOIN T_FRM_HOGAR hog ON hog.seqFormulario = frm.seqFormulario
					       INNER JOIN T_CIU_CIUDADANO ciu ON hog.seqCiudadano = ciu.seqCiudadano
					       INNER JOIN T_FRM_ESTADO_PROCESO epr ON frm.seqEstadoProceso = epr.seqEstadoProceso
					       INNER JOIN T_FRM_ETAPA eta  ON epr.seqEtapa = eta.seqEtapa
					 WHERE frm.seqFormulario in (" . $this->seqFormularios . ")
					 AND ( 
						( ADDDATE(ciu.fchNacimiento, INTERVAL 18 YEAR) <= frm.fchPostulacion AND ciu.seqTipoDocumento NOT IN (1, 2) )
  					 OR  ( ADDDATE(ciu.fchNacimiento, INTERVAL 18 YEAR) >  frm.fchPostulacion AND ciu.seqTipoDocumento NOT IN (4, 3) )
						)	
					";
            try {
                $arrTitulosCampos[] = 'seqFormulario';
                $arrTitulosCampos[] = 'txtFormulario';
                $arrTitulosCampos[] = 'TipoDocumento';
                $arrTitulosCampos[] = 'numDocumento';
                $arrTitulosCampos[] = 'txtNombre';
                $arrTitulosCampos[] = 'fchPostulacion';
                $arrTitulosCampos[] = 'fchMayorEdad';
                $arrTitulosCampos[] = 'fchNacimiento';
                $arrTitulosCampos[] = 'MayorEdad';
                $arrTitulosCampos[] = 'txtEstadoProceso';
                $arrTitulosCampos[] = 'Desplazado';
                $arrTitulosCampos[] = 'Cerrado';

                $objRes = $aptBd->execute($sql);
                $this->obtenerReportesGeneral($objRes, "ReporteCruzarEdadTodFchPos", $arrTitulosCampos);
            } catch (Exception $objError) {
                $arrErrores[] = "Se ha producido un error al consultar los datos";
            }
            if (!empty($arrErrores)) {
                imprimirMensajes($arrErrores, array());
            }
        } else {
            imprimirMensajes($arrErrores, array());
        }
    }

    public function exportableResumenPrograma() {

        global $aptBd;

        $sql = "
				SELECT est.seqEstadoProceso,
			         if(frm.bolDesplazado = 1, 'Desplazado', 'Independiente') AS Grupo,
			         CONCAT(eta.txtEtapa, ' - ', est.txtEstadoProceso) AS estadoProceso,
			         count(1) AS cuenta
			    FROM T_FRM_FORMULARIO frm
			         INNER JOIN T_FRM_ESTADO_PROCESO est
			            ON frm.seqEstadoProceso = est.seqEstadoProceso
			         INNER JOIN T_FRM_ETAPA eta
			            ON est.seqEtapa = eta.seqEtapa
				GROUP BY 2, 3
				ORDER BY 1, 2 desc
			";

        try {
            $objRes = $aptBd->execute($sql);

            $arrTitulosCampos[] = 'seqEstadoProceso';
            $arrTitulosCampos[] = 'Grupo';
            $arrTitulosCampos[] = 'estadoProceso';
            $arrTitulosCampos[] = 'cuenta';

            $this->obtenerReportesGeneral($objRes, "ReporteResumenPrograma", $arrTitulosCampos);
        } catch (Exception $objError) {
            $arrErrores[] = "Se ha producido un error al consultar los datos";
        }
        if (!empty($arrErrores)) {
            imprimirMensajes($arrErrores, array());
        }
    }

    public function exportableEstadoCorte() {

        global $aptBd;

        $sql = "
				  SELECT frm.seqFormulario,
				       frm.txtFormulario,
				       (
						SELECT 
							ciu1.numDocumento
						FROM T_CIU_CIUDADANO ciu1, 
							T_FRM_HOGAR hog1
						WHERE 
							hog1.seqCiudadano = ciu1.seqCiudadano
							AND hog1.seqFormulario = hog.seqFormulario
							AND hog1.seqParentesco = 1
						) AS DocumentoPPAL,
				       (
						SELECT 
							UPPER(CONCAT(ciu1.txtNombre1, ' ', ciu1.txtNombre2, ' ', ciu1.txtApellido1, ' ', ciu1.txtApellido2))
						FROM T_CIU_CIUDADANO ciu1,
							T_FRM_HOGAR hog1
						WHERE
							hog1.seqFormulario = hog.seqFormulario
							AND hog1.seqCiudadano = ciu1.seqCiudadano
							AND hog1.seqParentesco = 1
						) AS NombrePPAL,
				       ciu.numDocumento,
				       UPPER(CONCAT(ciu.txtNombre1,
				                    ' ',
				                    ciu.txtNombre2,
				                    ' ',
				                    ciu.txtApellido1,
				                    ' ',
				                    ciu.txtApellido2))
				          AS Nombre,
				       par.txtParentesco,
				       if(frm.bolDesplazado = 1, 'Si', 'No') AS Desplazado,
				       est.txtEstadoProceso,
						if(frm.bolCerrado = 1, 'Si', 'No') AS Cerrado,
				       moa.txtModalidad,
				       sol.txtSolucion,
				       frm.numTelefono1,
				       frm.numTelefono2,
				       frm.numCelular,
				       sol.txtDescripcion,
				       frm.fchPostulacion
				  FROM T_FRM_FORMULARIO frm
				       INNER JOIN T_FRM_HOGAR hog ON frm.seqFormulario = hog.seqFormulario
				       INNER JOIN T_CIU_CIUDADANO ciu ON hog.seqCiudadano = ciu.seqCiudadano
				       INNER JOIN T_CIU_PARENTESCO par ON hog.seqParentesco = par.seqParentesco
				       INNER JOIN T_FRM_MODALIDAD moa ON frm.seqModalidad = moa.seqModalidad
				       INNER JOIN T_FRM_ESTADO_PROCESO est ON est.seqEstadoProceso = frm.seqEstadoProceso
				       INNER JOIN T_FRM_SOLUCION sol ON sol.seqSolucion = frm.seqSolucion
				 WHERE frm.seqFormulario in (" . $this->seqFormularios . ")	
	    	";

        try {
            $objRes = $aptBd->execute($sql);
            $arrTitulosCampos[] = 'seqFormulario';
            $arrTitulosCampos[] = 'txtFormulario';
            $arrTitulosCampos[] = 'DocumentoPPAL';
            $arrTitulosCampos[] = 'NombrePPAL';
            $arrTitulosCampos[] = 'numDocumento';
            $arrTitulosCampos[] = 'Nombre';
            $arrTitulosCampos[] = 'txtParentesco';
            $arrTitulosCampos[] = 'Desplazado';
            $arrTitulosCampos[] = 'txtEstadoProceso';
            $arrTitulosCampos[] = 'Derrado';
            $arrTitulosCampos[] = 'txtModalidad';
            $arrTitulosCampos[] = 'txtSolucion';
            $arrTitulosCampos[] = 'numTelefono1';
            $arrTitulosCampos[] = 'numTelefono2';
            $arrTitulosCampos[] = 'numCelular';
            $arrTitulosCampos[] = 'txtDescripcion';
            $arrTitulosCampos[] = 'fchPostulacion';

            $this->obtenerReportesGeneral($objRes, "ReporteEstadoCorte", $arrTitulosCampos);
        } catch (Exception $objError) {
            $arrErrores[] = "Se ha producido un error al consultar los datos";
            $arrErrores[] = $objError->getMessagge();
        }
        if (!empty($arrErrores)) {
            imprimirMensajes($arrErrores, array());
        }
    }

    public function exportableCartasAsignacion() {

        global $aptBd;

        $sql = "
					SELECT 
						frm.seqFormulario as Formulario,
						upper(frm.txtDireccion) as Direccion,
						if( frm.numTelefono1 != 0 , frm.numTelefono1 , ( if( frm.numTelefono2 != 0 , frm.numTelefono2 , frm.numCelular ) ) ) as Telefono ,
						moa.txtModalidad as Modalidad,
						sol.txtDescripcion as Solucion, 
						REPLACE (valorMaximoVivienda( sol.txtSolucion ), '?', 'í') as ValorMaximo,
						format( frm.valAspiraSubsidio , 0 )as Subsidio,
						if( tdo.seqTipoDocumento in( 3 , 4 , 7) , 'Menor de Edad', tdo.txtTipoDocumento ) as TipoDocumento ,
						if( tdo.seqTipoDocumento in( 3 , 4 , 7) , '', format( ciu.numDocumento , 0 ) ) as Documento ,
						upper( CONCAT( ciu.txtNombre1 , ' ' , ciu.txtNombre2 , ' ' , ciu.txtApellido1 , ' ' , ciu.txtApellido2 )) as Nombre,
						civ.txtEstadoCivil as Estado_Civil
						
					FROM 
					T_FRM_FORMULARIO frm
					INNER JOIN T_FRM_HOGAR hog ON hog.seqFormulario = frm.seqFormulario
					INNER JOIN T_CIU_CIUDADANO ciu ON hog.seqCiudadano = ciu.seqCiudadano
					INNER JOIN T_CIU_TIPO_DOCUMENTO tdo ON ciu.seqTipoDocumento = tdo.seqTipoDocumento
					INNER JOIN T_FRM_MODALIDAD moa ON frm.seqModalidad = moa.seqModalidad
					INNER JOIN T_FRM_SOLUCION sol ON frm.seqSolucion = sol.seqSolucion
					INNER JOIN t_ciu_estado_civil civ on civ.seqEstadoCivil = ciu.seqEstadoCivil
					WHERE  
					frm.seqFormulario in (" . $this->seqFormularios . ")
					ORDER BY frm.seqFormulario, hog.seqParentesco
				";

        try {
            $objRes = $aptBd->execute($sql);
            $arrTitulosCampos[] = 'Formulario';
            $arrTitulosCampos[] = 'Direccion';
            $arrTitulosCampos[] = 'Telefono';
            $arrTitulosCampos[] = 'Modalidad';
            $arrTitulosCampos[] = 'Solucion';
            $arrTitulosCampos[] = 'ValorMaximo';
            $arrTitulosCampos[] = 'Subsidio';
            $arrTitulosCampos[] = 'TipoDocumento';
            $arrTitulosCampos[] = 'Documento';
            $arrTitulosCampos[] = 'Nombre';
            $arrTitulosCampos[] = 'EstadoCivil';

            $this->obtenerReportesGeneral($objRes, "ReporteCartasAsignacion", $arrTitulosCampos);
        } catch (Exception $objError) {
            $arrErrores[] = "Se ha producido un error al consultar los datos";
            $arrErrores[] = $objError->getMessagge();
        }
        if (!empty($arrErrores)) {
            imprimirMensajes($arrErrores, array());
        }
    }

    public function inscritosostuladosConsulta() {

        global $aptBd;

        $arrPostulados[] = 6;
        $arrPostulados[] = 7;
        $arrPostulados[] = 8;
        $arrPostulados[] = 14;

        $arrInscritos[] = 1;
        $arrInscritos[] = 10;
        $arrInscritos[] = 11;
        $arrInscritos[] = 12;
        $arrInscritos[] = 13;

        if ($_POST['filtroEstadoProceso'] == "postulados") {
            $txtEstadoProceso = implode(",", $arrPostulados);
        }

        if ($_POST['filtroEstadoProceso'] == "inscritos") {
            $txtEstadoProceso = implode(",", $arrInscritos);
        }

        if ($_POST['filtroUsuarioPunto'] == "punto") {
            $txtSelect = " pun.txtPuntoAtencion as PuntoAtencion, ";
            $txtInnerJoin = " INNER JOIN T_FRM_PUNTO_ATENCION pun ON frm.seqPuntoAtencion = pun.seqPuntoAtencion ";
            $txtTitulo = "PuntoAtencion";
        }

        if ($_POST['filtroUsuarioPunto'] == "usuario") {
            $txtSelect = " CONCAT(usu.txtNombre, ' ', usu.txtApellido) as Usuario, ";
            $txtInnerJoin = " INNER JOIN T_COR_USUARIO usu on usu.seqUsuario = frm.seqUsuario ";
            $txtTitulo = "Usuario";
        }


        $sql = "
				SELECT 
					$txtSelect
					CONCAT(eta.txtEtapa, ' - ', epr.txtEstadoProceso) AS EstadoProceso,
					count(1) as Cuenta
				FROM T_FRM_FORMULARIO frm
				$txtInnerJoin
				INNER JOIN T_FRM_ESTADO_PROCESO epr ON frm.seqEstadoProceso = epr.seqEstadoProceso
				INNER JOIN T_FRM_ETAPA eta ON epr.seqEtapa = eta.seqEtapa
				WHERE frm.seqEstadoProceso IN ( $txtEstadoProceso )
				GROUP BY 1, 2
				";

        try {

            $objRes = $aptBd->execute($sql);

            $this->arrTablas['titulos'][] = $txtTitulo;
            $this->arrTablas['titulos'][] = "Estado Proceso";
            $this->arrTablas['titulos'][] = "Total";

            while ($objRes->fields) {


                $txtPuntoAtencion = $objRes->fields[$txtTitulo];
                $txtEstadoProceso = $objRes->fields['EstadoProceso'];
                $txtCuenta = $objRes->fields['Cuenta'];

                $this->arrTablas['filas'][$txtPuntoAtencion][$txtEstadoProceso] = $txtCuenta;
                $this->arrTablas['total'][$txtTitulo][$txtPuntoAtencion] += $txtCuenta;
                $this->arrTablas['total']['EstadoProceso'][$txtEstadoProceso] += $txtCuenta;
                $this->arrTablas['total']['total'] += $txtCuenta;

                $objRes->MoveNext();
            }

            $arrDatos = &$this->arrTablas['datos'];
            foreach ($this->arrTablas['filas'] as $txtPuntoAtencion => $arrPuntoAtencion) {
                $arrDatosFila = &$arrDatos[];
                $arrDatosFila[] = "<b>" . $txtPuntoAtencion . "</b>";
                $arrDatosFila[] = "";
                $arrDatosFila[] = "<b>" . $this->arrTablas['total'][$txtTitulo][$txtPuntoAtencion] . "</b>";
                foreach ($arrPuntoAtencion as $txtEstadoProceso => $numTotal) {
                    $arrDatosFila = &$arrDatos[];
                    $arrDatosFila[] = "";
                    $arrDatosFila[] = $txtEstadoProceso;
                    $arrDatosFila[] = $numTotal;
                }
            }

            $arrDatosFila = &$arrDatos[];
            $arrDatosFila[] = "<td colspan='2' align='center'><b>TOTAL ESTADOS PROCESO</b></td>";

            foreach ($this->arrTablas['total']['EstadoProceso'] as $txtEstadoProceso => $numTotal) {
                $arrDatosFila = &$arrDatos[];
                $arrDatosFila[] = "";
                $arrDatosFila[] = "<b>" . $txtEstadoProceso . "</b>";
                $arrDatosFila[] = "<b>" . $numTotal . "</b>";
            }

            $arrDatosFila = &$arrDatos[];
            $arrDatosFila[] = "<td colspan='2' align='center'><b>TOTAL</b></td>";

            $arrDatosFila = &$arrDatos[];
            $arrDatosFila[] = "";
            $arrDatosFila[] = "";
            $arrDatosFila[] = "<b>" . $this->arrTablas['total']['total'] . "</b>";

            $arrGraficasTabs = array();
            $arrGraficasTabs[] = $txtTitulo;
            $arrGraficasTabs[] = "EstadoProceso";

            $arrGraficas = &$this->arrGraficas;
            foreach ($arrGraficasTabs as $txtTab) {

                $arrTab = &$arrGraficas['configuracion'][$txtTab];
                $arrTab['tipo'] = "bar";
                $arrTab['ejes'][] = "ejeX";
                $arrTab['ejes'][] = "conteo";

                $arrEjes = array();
                switch ($txtTab) {
                    case $txtTitulo:
                        $arrEjes = $this->arrTablas['total'][$txtTitulo];
                        break;
                    case "EstadoProceso":
                        $arrEjes = $this->arrTablas['total']['EstadoProceso'];
                        break;
                    default:
                        $arrEjes = array();
                        break;
                }

                foreach ($arrEjes as $txtEje => $txt) {
                    $arrGraficas['datos'][$txtTab][$txtEje]["conteo"] = $arrEjes[$txtEje];
                }
            }
        } catch (Exception $objError) {
            $this->arrErrores[] = "Hubo un problema al intentar obtener los datos";
        }
        if (!empty($arrErrores)) {
            imprimirMensajes($arrErrores, array());
        }
//			pr( $this );
    }

    public function estadoCorte() {

        global $aptBd;

        $sql = "
				 SELECT if(frm.bolDesplazado = 1, 'Desplazado', 'Independiente') AS Grupo,
			         moa.txtModalidad AS Modalidad,
			         if(frm.seqModalidad = 3 OR frm.seqModalidad = 4 OR frm.seqModalidad = 5 ,
			            avaluo2Tipo(frm.valTotal),
			            sol.txtDescripcion)
			            AS Solucion,
			         count(1) AS cuenta
			    FROM T_FRM_FORMULARIO frm
			         INNER JOIN T_FRM_HOGAR hog ON frm.seqFormulario = hog.seqFormulario
			         INNER JOIN T_FRM_MODALIDAD moa ON frm.seqModalidad = moa.seqModalidad
			         INNER JOIN T_FRM_SOLUCION sol ON frm.seqSolucion = sol.seqSolucion
			   WHERE frm.seqEstadoProceso = 7 
					AND frm.bolCerrado = 1
					 AND hog.seqParentesco = 1
			         AND frm.fchPostulacion <=
			               (SELECT fchFinal
			                  FROM T_FRM_PERIODO
			                 WHERE seqPeriodo = (SELECT max(seqPeriodo) FROM T_FRM_PERIODO))
				GROUP BY 1, 2, 3
				ORDER BY 1, 2, 3		
	    	";

        try {

            $this->arrTablas['titulos'][] = "Grupo";
            $this->arrTablas['titulos'][] = "Modalidad";

            $objRes = $aptBd->execute($sql);

            $prueba = array();

            while ($objRes->fields) {
                if (!in_array(ucwords(strtolower($objRes->fields['Solucion'])), $this->arrTablas['titulos'])) {
                    $solucion = ucwords(strtolower($objRes->fields['Solucion']));
                    $this->arrTablas['titulos'][] = $solucion;
                    $this->arrSoluciones[] = $solucion;
                }

                $txtGrupo = $objRes->fields['Grupo'];
                $txtModalidad = $objRes->fields['Modalidad'];
                $txtSolucion = ucwords(strtolower($objRes->fields['Solucion']));
                $txtCuenta = $objRes->fields['cuenta'];

                $this->arrTablas['filas'][$txtGrupo][$txtModalidad][$txtSolucion] = $txtCuenta;

                $this->arrTablas['total']['TotalModalidad'][$txtModalidad][$txtSolucion] += $txtCuenta;
                $this->arrTablas['total']['TotalPoblacion'][$txtSolucion] += $txtCuenta;
                $this->arrTablas['total']['total'] += $txtCuenta;

                $objRes->MoveNext();
            }
            $this->arrTablas['titulos'][] = "Total";

            $j = 0;
            $datos = &$this->arrTablas['datos'];

            foreach ($this->arrTablas['filas'] as $grupo => $datoGrupo) {

                foreach ($datoGrupo as $modalidad => $datosModalidad) {

                    $datos[$j][0] = $grupo;
                    $datos[$j][1] = $modalidad;

                    $i = 2;
                    $total = 0;

                    foreach ($this->arrSoluciones as $solucion) {

                        if ($datosModalidad[$solucion]) {
                            $datos[$j][$i] = $datosModalidad[$solucion];
                            $total += $datosModalidad[$solucion];
                        } else {
                            $datos[$j][$i] = 0;
                        }

                        $i++;
                    }

                    $datos[$j][5] = $total;
                    $j++;
                }
            }


            foreach ($this->arrTablas['total']['TotalModalidad'] as $modalidad => $datosModalidad) {

                $datos[$j][0] = "<b>TODOS</b>";
                $datos[$j][1] = "<b>$modalidad</b>";

                $i = 2;
                $total = 0;

                foreach ($this->arrSoluciones as $solucion) {

                    if ($datosModalidad[$solucion]) {
                        $datos[$j][$i] = "<b>" . $datosModalidad[$solucion] . "</b>";
                        $total += $datosModalidad[$solucion];
                    } else {
                        $datos[$j][$i] = "<b>" . 0 . "</b>";
                    }

                    $i++;
                }

                $datos[$j][5] = "<b>" . $total . "</b>";
                $j++;
            }

            $totalPoblacion = $this->arrTablas['total']['TotalPoblacion'];

            $datos[$j][0] = "<b>TODOS</b>";
            $datos[$j][1] = "<b>TOTAL POBLACION</b>";

            $i = 2;
            $total = 0;
            foreach ($this->arrSoluciones as $solucion) {

                if ($totalPoblacion[$solucion]) {
                    $datos[$j][$i] = "<b>" . $totalPoblacion[$solucion] . "</b>";
                    $total += $totalPoblacion[$solucion];
                } else {
                    $datos[$j][$i] = "<b>" . 0 . "</b>";
                }

                $i++;
            }
            $datos[$j][5] = "<b>" . $total . "</b>";


            /**
             * ARREGLO DE GRAFICAS
             */
            $arrGraficas = &$this->arrGraficas; // apoyemos la pereza de Diego
            // Configuracion de la grafica
            //$arrGraficas['configuracion'][ 'TotalCorte' ]['tipo'] = "pie";

            foreach ($this->arrTablas['filas'] as $txtGrupo => $arrInfo) {
                $arrGraficas['configuracion'][$txtGrupo]['tipo'] = "columna";
                $arrGraficas['configuracion'][$txtGrupo]['ejes'][] = "ejeX";
                $arrGraficas['configuracion'][$txtGrupo]['ejes'][] = "VipTipo1";
                $arrGraficas['configuracion'][$txtGrupo]['ejes'][] = "VipTipo2";
                $arrGraficas['configuracion'][$txtGrupo]['ejes'][] = "Vis";
            }

            // Datos de las graficas
            $arrGraficas['datos'] = $this->arrTablas['filas'];
            $arrGraficas['datos']['TotalCorte'] = array();
            foreach ($this->arrTablas['total']['TotalModalidad'] as $txtModalidad => $arrSolucion) {
                foreach ($arrSolucion as $txtSolucion => $numValor) {
                    $arrGraficas['datos']['TotalCorte'][$txtModalidad]['conteo'] += $numValor;
                }
            }

            $arrGraficas['configuracion']['TotalCorte']['tipo'] = "pie";
            $arrGraficas['configuracion']['TotalCorte']['ejes'][] = "ejeX";
            $arrGraficas['configuracion']['TotalCorte']['ejes'][] = "conteo";
        } catch (Exception $objError) {
            $this->arrErrores[] = "Hubo un problema al intentar obtener los datos";
        }
        if (!empty($arrErrores)) {
            imprimirMensajes($arrErrores, array());
        }
    }

    /**
     * TOMA UN ARREGLO DE PHP Y RETORNA UN 
     * STRING CON SINTAXIS JAVASCRITPT
     * @author Diego Felipe Gaitan 
     * @author Bernardo Zerda
     * @param Array Void
     * @return String txtJs
     * @version 0.1 Marzo 2010
     */
    public function php2js() {

        $arrGraficas = $this->arrGraficas;
        $txtJs = "var objGraficas = { ";

        // Iteracion de graficas
        foreach ($this->arrGraficas['datos'] as $txtNombreGrafica => $arrEjeX) {
            $txtJs .= $txtNombreGrafica . ": { ";
            $txtJs .= "tipo: '" . $this->arrGraficas['configuracion'][$txtNombreGrafica]['tipo'] . "',";
            $txtJs .= "nombre: '$txtNombreGrafica' , ";
            $txtJs .= "ejes: [";
            foreach ($this->arrGraficas['configuracion'][$txtNombreGrafica]['ejes'] as $txtEje) {
                $txtJs .= "'" . $txtEje . "',";
            }
            $txtJs = trim($txtJs, ", ");
            $txtJs .= "], ";
            $txtJs .= "datos: [";
            foreach ($arrEjeX as $txtNombreEjeX => $arrSeries) {
                $txtJs .= "{ ejeX: '$txtNombreEjeX' , ";
                foreach ($arrSeries as $txtNombreSerie => $numValorSerie) {
                    $numValorSerie = ( is_numeric($numValorSerie) ) ? $numValorSerie : "'$numValorSerie'";
                    $txtJs .= ereg_replace(" ", "", $txtNombreSerie) . ": $numValorSerie , ";
                }
                $txtJs = trim($txtJs, ", ");
                $txtJs .= "}, ";
            }
            $txtJs = trim($txtJs, ", ");
            $txtJs .= "]}, ";
        }
        $txtJs = trim($txtJs, ", ");
        $txtJs .= " }; ";

        return $txtJs;
    }

    private function formArchivo($nomVariable) {

        $txtFile = "<input 
						type='file'
						id='$nomVariable'
						name = '$nomVariable' >";

        return $txtFile;
    }

    private function leerArchivoSecuenciales() {

        global $aptBd;

        $arrSeqFormularios = &$this->arrSeqFormularios;
        $arrErrores = &$this->arrErrores;

        try {
            $aptArchivo = fopen($_FILES['fileSecuenciales']['tmp_name'], "r");
            $numLinea = 1;
            while ($txtLinea = fgets($aptArchivo)) {

                try {
                    $txtLinea = trim($txtLinea);
                    if (is_numeric($txtLinea)) {
                        $seqFormulario = Ciudadano::formularioVinculado($txtLinea);
                        if ($seqFormulario) {
                            $arrSeqFormularios[] = $seqFormulario;
                        } else {
                            throw new Exception("La linea $numLinea . El documento $txtLinea no tiene asignado un formulario");
                        }
                    } else if ($txtLinea != "") {
                        throw new Exception("La linea $numLinea del archivo no contiene un número de documento válido");
                    }
                } catch (Exception $objError) {
                    $arrErrores[] = $objError->getMessage();
                }
                $numLinea++;
            }
        } catch (Exception $objError) {
            $arrErrores[] = "No se ha podido abrir el archivo, puede que no tenga el formato correcto";
        }
    }

    public function cargarSecuencialesFormulario() {

        global $aptBd;

        $arrErrores = &$this->arrErrores;
        $bolExisteFileSecuenciales = true;

        switch ($_FILES['fileSecuenciales']['error']) {
            case UPLOAD_ERR_INI_SIZE:
                $arrErrores[] = "El archivo Excede el tamaño permitido, contacte al administrador del sistema";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $arrErrores[] = "El archivo Excede el tamaño permitido, contacte al administrador del sistema";
                break;
            case UPLOAD_ERR_PARTIAL:
                $arrErrores[] = "El archivo no fue completamente cargado, intente de nuevo, si el error persiste contacte al administrador";
                break;
            case UPLOAD_ERR_NO_FILE:
                $bolExisteFileSecuenciales = false;
                break;
        }



        if (empty($arrErrores) and $bolExisteFileSecuenciales and ! empty($_FILES['fileSecuenciales'])) {
            $this->leerArchivoSecuenciales();
        }

        $seqFormularios = &$this->seqFormularios;
        $arrSeqFormularios = &$this->arrSeqFormularios;

        if (!empty($arrSeqFormularios)) {
            $seqFormularios = implode($arrSeqFormularios, ",");
        } else {

            $sql = "SELECT frm.seqFormulario
						  FROM T_FRM_FORMULARIO frm
						 WHERE 
							frm.seqEstadoProceso = 7  AND -- frm.bolCerrado = 1 AND 
								frm.fchPostulacion <=
						             (SELECT fchFinal
						                FROM T_FRM_PERIODO
						               WHERE seqPeriodo = (SELECT max(seqPeriodo) FROM T_FRM_PERIODO)) 
						";

            try {

                $objRes = $aptBd->execute($sql);

                while ($objRes->fields) {
                    $arrSeqFormularios[] = $objRes->fields['seqFormulario'];
                    $objRes->MoveNext();
                }

                $seqFormularios = ( empty($arrSeqFormularios) ) ? "null" :
                        implode($arrSeqFormularios, ",");
            } catch (Exception $objError) {
                $arrErrores[] = "Se ha producido un error al consultar los datos";
            }
        }
    }

    public function pasivosExigibles($fchDesde, $fchHasta) {
        global $aptBd;

        // Validacion de la fecha de inicio
        list( $ano, $mes, $dia ) = split("-", $fchDesde);
        if (@checkdate($mes, $dia, $ano) === false) {
            $this->arrErrores[] = "La fecha de inicio no es válida";
        }

        // Validacion de la fecha de fin
        list( $ano, $mes, $dia ) = split("-", $fchHasta);
        if (@checkdate($mes, $dia, $ano) === false) {
            $this->arrErrores[] = "La fecha de fin no es válida";
        }

        if (empty($this->arrErrores)) {
            try {
                $sql = "
						SELECT 
						  hvi.numActo as Numero,
						  hvi.fchActo as Fecha,
						  ciu.numDocumento as Documento,
						  UPPER( CONCAT( ciu.txtNombre1 , ' ' , ciu.txtNombre2 , ' ' , ciu.txtApellido1 , ' ' , ciu.txtApellido2 ) ) as Nombre,
						  moa.txtModalidad as Modalidad,
						  UPPER( CONCAT( sol.txtDescripcion , ' ( ' , sol.txtSolucion , ' )' ) ) as Solucion,
						  frm.valAspiraSubsidio as ValorSubsidio,
						  CONCAT( eta.txtEtapa , ' - ' , epr.txtEstadoProceso ) as EstadoProceso,
						  des.fchActualizacionEscrituracion as FechaActualizacionEscrituracion
						FROM T_FRM_FORMULARIO frm
						INNER JOIN T_FRM_HOGAR hog ON frm.seqFormulario = hog.seqFormulario
						INNER JOIN T_CIU_CIUDADANO ciu ON hog.seqCiudadano = ciu.seqCiudadano
						INNER JOIN T_FRM_MODALIDAD moa ON frm.seqModalidad = moa.seqModalidad
						INNER JOIN T_FRM_SOLUCION sol ON sol.seqSolucion = frm.seqSolucion
						INNER JOIN T_FRM_ESTADO_PROCESO epr ON frm.seqEstadoProceso = epr.seqEstadoProceso
						INNER JOIN T_FRM_ETAPA eta ON epr.seqEtapa = eta.seqEtapa
						INNER JOIN T_DES_DESEMBOLSO des ON frm.seqFormulario = des.seqFormulario
						INNER JOIN T_AAD_FORMULARIO_ACTO fac ON  frm.seqFormulario = fac.seqFormulario
						INNER JOIN T_AAD_HOGARES_VINCULADOS hvi ON fac.seqFormularioActo = hvi.seqFormularioActo
						INNER JOIN T_AAD_ACTO_ADMINISTRATIVO aad ON ( hvi.numActo = aad.numActo AND hvi.fchActo = aad.fchActo )
						WHERE hog.seqParentesco = 1	
						  AND aad.seqTipoActo = 1
						  AND des.fchActualizacionEscrituracion >= '$fchDesde 00:00:00'
						  AND des.fchActualizacionEscrituracion <= '$fchHasta 23:59:59'
						  AND frm.seqEstadoProceso IN (26,27,28,29,30)
						GROUP BY frm.seqFormulario, hvi.numActo, hvi.fchActo	    		
		    		";
                $arrResultado = array();
                $objRes = $aptBd->execute($sql);
                $this->obtenerReportesGeneral($objRes, "PasivosExigibles_$fchDesde\_$fchHasta\_");
            } catch (Exception $objError) {
                $this->arrErrores[] = "No se pudo realizar la consulta de pasivos exigibles";
            }
        } else {
            imprimirMensajes($this->arrErrores, array());
        }
    }

    /**
     * OBTENCION DEL VALOR DEL CIERRE FINANCIERO
     * @author bzerdar
     * @param Integer seqModalidad
     * @param Integer seqSolucion
     * @return Integer valCierreFinanciero
     * @version 1.0 Ene 2011
     */
    public function valorCierreFinanciero($seqModalidad, $seqSolucion) {
        global $aptBd;
        global $arrConfiguracion;

        try {
            $sql = "
				    SELECT
				      vsu.valSubsidio,
				      sol.txtSolucion
				    FROM
				      T_FRM_VALOR_SUBSIDIO vsu,
				      T_FRM_SOLUCION sol
				    WHERE vsu.seqModalidad = $seqModalidad
				    AND vsu.seqSolucion = $seqSolucion
				    AND sol.seqSolucion = vsu.seqSolucion;	    		
		    	";

            $objRes = $aptBd->execute($sql);
            if ($objRes->fields) {
                if ($seqModalidad != 5) {
                    switch ($objRes->fields['txtSolucion']) {
                        case "<= 50 SMMLV":
                            $valCierreFinanciero = ( 50 * $arrConfiguracion['constantes']['salarioMinimo'] ) - $objRes->fields['valSubsidio'];
                            break;
                        case "> 50 y <= 70 SMMLV":
                            $valCierreFinanciero = ( 50 * $arrConfiguracion['constantes']['salarioMinimo'] ) - $objRes->fields['valSubsidio'];
                            break;
                        case "> 70 y <= 135 SMMLV":
                            $valCierreFinanciero = ( 70 * $arrConfiguracion['constantes']['salarioMinimo'] ) - $objRes->fields['valSubsidio'];
                            break;
                        default:
                            $valCierreFinanciero = 0;
                            break;
                    }
                } else {
                    $valCierreFinanciero = 0;
                }
            } else {
                $valCierreFinanciero = 0;
            }
        } catch (Exception $objError) {
            $this->arrErrores[] = "No se ha podido verificar el valor del cierre financiero";
            $valCierreFinanciero = 0;
        }

        return $valCierreFinanciero;
    }

    public function seguimientoDesembolsos($arrDocumentos) {
        global $aptBd;

        if (!empty($arrDocumentos)) {

            $sql = "
            SELECT 
               frm.seqFormulario as Formulario,
               CONCAT( eta.txtEtapa , ' - ' , pro.txtEstadoProceso ) as Estado, 
               ciu.numDocumento as Documento,
               CONCAT( TRIM( ciu.txtNombre1 ) , ' ' ,  if( ciu.txtNombre2 <> '' , CONCAT( TRIM( ciu.txtNombre2 ) , ' ' ), '' ), ciu.txtApellido1, ' ', ciu.txtApellido2 ) as Nombre,
               UPPER( flu.txtFlujo ) as Esquema,
               sol.numRegistroPresupuestal1 as NumeroRegistroPresupuestal1,
               sol.fchRegistroPresupuestal1 as FechaRegistroPresupuestal1,
               sol.numRegistroPresupuestal2 as NumeroRegistroPresupuestal2,
               sol.fchRegistroPresupuestal2 as FechaRegistroPresupuestal2,
               sol.valSolicitado as ValorSolicitado,
               sol.numRadiacion as NumeroRadicado,
               sol.fchRadicacion as FechaRadicacion,
               sol.numOrden as NumeroOrdenPago,
               sol.fchOrden as FechaOrdenPago,
               sol.valOrden as ValorOrdenPago,
			   des.txtNombreVendedor AS NombreVendedor,
			   sol.txtNombreBeneficiarioGiro AS NombreBeneficiarioGiro
            FROM T_FRM_FORMULARIO frm
            INNER JOIN T_FRM_HOGAR hog ON frm.seqFormulario = hog.seqFormulario
            INNER JOIN T_CIU_CIUDADANO ciu ON ciu.seqCiudadano = hog.seqCiudadano
            INNER JOIN T_FRM_ESTADO_PROCESO pro ON frm.seqEstadoProceso = pro.seqEstadoProceso
            INNER JOIN T_FRM_ETAPA eta ON pro.seqEtapa = eta.seqEtapa
            LEFT JOIN T_DES_FLUJO flu ON frm.seqFormulario = flu.seqFormulario
            LEFT JOIN T_DES_DESEMBOLSO des ON frm.seqFormulario = des.seqFormulario
            LEFT JOIN T_DES_SOLICITUD sol ON des.seqDesembolso = sol.seqDesembolso
            WHERE hog.seqParentesco = 1
            AND ciu.numDocumento IN ( " . implode(",", $arrDocumentos) . " )
         ";
            $objRes = $aptBd->execute($sql);
            $this->obtenerReportesGeneral($objRes, "seguimientoDesembolsos");
        } else {
            $this->arrErrores[] = "No hay documentos en el archivo";
            imprimirMensajes($this->arrErrores, array());
        }
    }

    public function obtenerEscrituracion($arrDocumentos) {

        obtenerReporteEscrituracion($arrDocumentos);
    }

    public function exportableCartasMovilizacion() {
        global $aptBd;

        $sql = "
         SELECT
            numDocumento,
            txtTipoCarta,
            fchCarta,
            txtCodigo
         FROM T_CIU_CARTA
      ";
        $objRes = $aptBd->execute($sql);
        $this->obtenerReportesGeneral($objRes, "cartasMovilizacion");
    }

    /* public function exportableHogaresCalificados(){
      global $aptBd;

      $sql = "
      SELECT
      numDocumento,
      txtTipoCarta,
      fchCarta,
      txtCodigo
      FROM T_CIU_CARTA
      ";
      $objRes = $aptBd->execute( $sql );
      $this->obtenerReportesGeneral($objRes, "hogaresCalificados");

      } */

    public function exportableHogaresCalificados() {

        global $aptBd;

        $arrErrores = &$this->arrErrores;

        $txtCondiciones = '';

        if (empty($arrErrores)) {

            $txtCondiciones = ( $_POST['fchInicio'] != "" ) ? "AND fchCalificacion >= '" . $_POST['fchInicio'] . " 00:00:00'" : "";
            $txtCondiciones .= ( $_POST['fchFin'] != "" ) ? "AND fchCalificacion <= '" . $_POST['fchFin'] . " 23:59:59'" : "";

            $sql = "SELECT 
						T_FRM_CALIFICACION_PLAN2.seqFormulario,
						numDocumento AS DocumentoPPal,
						UPPER(CONCAT(txtNombre1,' ',txtNombre2,' ',txtApellido1,' ',txtApellido2)) AS NombrePPal,
						DATE_FORMAT(fchCalificacion,'%d-%m-%Y') AS FechaCalificacion,
						FORMAT(valTransformado, 3) AS Puntaje
					FROM T_FRM_CALIFICACION_PLAN2 LEFT JOIN T_FRM_HOGAR ON (T_FRM_CALIFICACION_PLAN2.seqFormulario = T_FRM_HOGAR.seqFormulario)
					LEFT JOIN T_CIU_CIUDADANO ON (T_FRM_HOGAR.seqCiudadano = T_CIU_CIUDADANO.seqCiudadano)
					WHERE 
						seqParentesco = 1 
					$txtCondiciones";
            //echo $sql;
            try {
                $objRes = $aptBd->execute($sql);

                $arrTitulosCampos[] = 'SeqFormulario';
                $arrTitulosCampos[] = 'Documento PPal';
                $arrTitulosCampos[] = 'Nombre PPal';
                $arrTitulosCampos[] = 'Fecha Calificación';
                $arrTitulosCampos[] = 'Puntaje';

                $this->obtenerReportesGeneral($objRes, "ReporteHogaresCalificados", $arrTitulosCampos);
            } catch (Exception $objError) {
                $arrErrores[] = "Se ha producido un error al consultar los datos";
            }

            if (!empty($arrErrores)) {
                imprimirMensajes($arrErrores, array());
            }
        } else {
            imprimirMensajes($arrErrores, array());
        }
    }

    public function exportableActosAdministrativosAsignacion() {
        global $aptBd;

        // Se ocultan los campos: 
        // par.txtParentesco AS Parentesco, tac.txtNombreTipoActo AS Tipo_Acto, fac.valCredito
        // Se ocultan las relaciones:
        // LEFT JOIN T_CIU_PARENTESCO par ON hac.seqParentesco = par.seqParentesco
        // LEFT JOIN T_AAD_TIPO_ACTO tac ON aad.seqTipoActo = tac.seqTipoActo
        $sql = "
               SELECT DISTINCT fac.seqFormulario AS seqFormulario,
                fac.seqFormularioActo AS seqActo,
                cac.numDocumento AS Documento,
                concat(cac.txtNombre1,
                       ' ',
                       cac.txtNombre2,
                       ' ',
                       cac.txtApellido1,
                       ' ',
                       cac.txtApellido2)
                   AS Nombre,
                if(fac.bolDesplazado = 1, 'Si', 'No') AS Desplazado,
                concat('Res. ', aad.numActo) AS Resolucion,
                year(aad.fchActo) AS Año,
                aad.fchActo AS Fecha_Resolucion,
                frm.fchVigencia AS Fecha_Vigencia,
                moda.txtModalidad,
                sol.txtDescripcion AS Tipo,
                sol.txtSolucion AS Rango,
                fac.valAspiraSubsidio AS Vr_SDV,
				CONCAT(txtEtapa ,' - ', txtEstadoProceso ) AS Estado_Proceso,
                esq.txtTipoEsquema AS Esquema,
                pry.txtNombreProyecto AS Proyecto,
                prh.txtNombreProyecto AS Conjunto_Residencial,
                und.txtNombreUnidad AS Unidad_Residencial,
                fac.txtMatriculaInmobiliaria AS Matricula_Inmobiliaria,
                fac.txtDireccionSolucion AS Direccion,
                bh1.txtBanco AS Banco_Ahorro_1,
				bh2.txtBanco AS Banco_Ahorro_2,
				bcr.txtBanco AS Banco_Credito,
				ets.txtEntidadSubsidio AS Entidad_Subsidio,
				edn.txtEmpresaDonante AS Entidad_Donante
			FROM T_AAD_ACTO_ADMINISTRATIVO aad
			LEFT JOIN T_AAD_HOGARES_VINCULADOS hvi ON aad.fchActo = hvi.fchActo AND aad.numActo = hvi.numActo
			LEFT JOIN T_AAD_FORMULARIO_ACTO fac ON hvi.seqFormularioActo = fac.seqFormularioActo
			LEFT JOIN T_FRM_BANCO bh1 ON fac.seqBancoCuentaAhorro = bh1.seqBanco
			LEFT JOIN T_FRM_BANCO bh2 ON fac.seqBancoCuentaAhorro = bh2.seqBanco
			LEFT JOIN T_FRM_BANCO bcr ON fac.seqBancoCredito = bcr.seqBanco
			LEFT JOIN T_FRM_ENTIDAD_SUBSIDIO ets ON fac.seqEntidadSubsidio = ets.seqEntidadSubsidio
			LEFT JOIN T_FRM_EMPRESA_DONANTE edn ON fac.seqEmpresaDonante = edn.seqEmpresaDonante
			LEFT JOIN T_FRM_ESTADO_PROCESO est ON fac.seqEstadoProceso = est.seqEstadoProceso
			LEFT JOIN T_FRM_ETAPA etp ON est.seqEtapa = etp.seqEtapa
			LEFT JOIN T_AAD_HOGAR_ACTO hac ON fac.seqFormularioActo = hac.seqFormularioActo
			LEFT JOIN T_AAD_CIUDADANO_ACTO cac ON hac.seqCiudadanoActo = cac.seqCiudadanoActo
			LEFT JOIN T_FRM_MODALIDAD moda ON fac.seqModalidad = moda.seqModalidad
			LEFT JOIN T_FRM_SOLUCION sol ON fac.seqSolucion = sol.seqSolucion
			LEFT JOIN T_PRY_PROYECTO pry ON fac.seqProyecto = pry.seqProyecto
			LEFT JOIN T_PRY_PROYECTO prh ON fac.seqProyectoHijo = prh.seqProyecto
			LEFT JOIN T_FRM_FORMULARIO frm ON fac.seqFormulario = frm.seqFormulario
			LEFT JOIN T_PRY_UNIDAD_PROYECTO und ON frm.seqUnidadProyecto = und.seqUnidadProyecto
			LEFT JOIN T_PRY_TIPO_ESQUEMA AS esq ON fac.seqTipoEsquema = esq.seqTipoEsquema
 WHERE     (hac.seqParentesco = 1 OR hac.seqParentesco IS NULL)
       AND aad.seqCaracteristica = 1
ORDER BY aad.fchActo DESC;
      ";
        $objRes = $aptBd->execute($sql);
        $this->obtenerReportesGeneral($objRes, "reporteAsignadosAAD");
    }

    public function exportableAsignacionUnidades() {
        global $aptBd;

        $sql = "(
				SELECT
				  pro.txtNombreProyecto AS 'Proyecto',
				  prh.txtNombreProyecto AS 'Conjunto Residencial',
				  und.txtNombreUnidad AS 'Unidad Proyecto',
				  frm.seqFormulario,
				  frm.txtFormulario,
				  UPPER(CONCAT(ciu.txtNombre1,' ',ciu.txtNombre2,' ',ciu.txtApellido1,' ',ciu.txtApellido2)) AS 'Nombre',
				  ciu.numDocumento AS 'Documento',
				  CONCAT(eta.txtEtapa,' - ',epr.txtEstadoProceso) AS 'Estado del Proceso'
				FROM T_PRY_UNIDAD_PROYECTO und
				  LEFT JOIN T_FRM_FORMULARIO frm ON (frm.seqUnidadProyecto = und.seqUnidadProyecto)
				  LEFT JOIN T_FRM_HOGAR hog ON (frm.seqFormulario = hog.seqFormulario)
				  LEFT JOIN T_CIU_CIUDADANO ciu ON (hog.seqCiudadano = ciu.seqCiudadano)
				  LEFT JOIN T_FRM_ESTADO_PROCESO epr ON (frm.seqEstadoProceso = epr.seqEstadoProceso)
				  LEFT JOIN T_FRM_ETAPA eta ON (epr.seqEtapa = eta.seqEtapa)
				  LEFT JOIN T_PRY_PROYECTO pro ON (frm.seqProyecto = pro.seqProyecto)
				  LEFT JOIN T_PRY_PROYECTO prh ON (frm.seqProyectoHijo = prh.seqProyecto)
				WHERE hog.seqParentesco = 1
				AND pro.seqTipoEsquema in (1, 2)
				AND pro.seqProyectoPadre is null
				) UNION (
				SELECT
				  pro.txtNombreProyecto AS 'Proyecto',
				  prh.txtNombreProyecto AS 'Conjunto Residencial',
				  und.txtNombreUnidad AS 'Unidad Proyecto',
				  '' as 'seqFormulario',
				  '' as 'txtFormulario',
				  '' as 'Nombre',
				  '' as 'Documento',
				  '' as 'Estado del Proceso'
				FROM T_PRY_UNIDAD_PROYECTO und
				LEFT JOIN T_PRY_PROYECTO pro ON (und.seqProyecto = pro.seqProyecto)
				LEFT JOIN T_PRY_PROYECTO prh ON (pro.seqProyectoPadre = prh.seqProyecto)
				WHERE (und.seqFormulario = 0 OR und.seqFormulario is null)
				AND pro.seqTipoEsquema in (1, 2)
				AND pro.seqProyectoPadre is null
				)
				ORDER BY 1,2,3
      ";
        $objRes = $aptBd->execute($sql);
        $this->obtenerReportesGeneral($objRes, "reporteAsignacionUnidades");
    }

    public function exportableAsignacionUnidadesMejoramiento() {
        global $aptBd;

        $sql = "SELECT
				  pro.txtNombreProyecto AS 'Proyecto',
				  frm.txtDireccionSolucion AS 'Direccion Solucion',
				  frm.seqFormulario,
				  frm.txtFormulario,
				  UPPER(CONCAT(ciu.txtNombre1,' ',ciu.txtNombre2,' ',ciu.txtApellido1,' ',ciu.txtApellido2)) AS 'Nombre',
				  ciu.numDocumento AS 'Documento',
				  CONCAT(eta.txtEtapa,' - ',epr.txtEstadoProceso) AS 'Estado del Proceso',
				  ahv.numActo AS acto,
					ahv.fchActo AS fecha
				FROM T_PRY_UNIDAD_PROYECTO und
				  LEFT JOIN T_FRM_FORMULARIO frm ON (frm.seqUnidadProyecto = und.seqUnidadProyecto)
				  LEFT JOIN T_FRM_HOGAR hog ON (frm.seqFormulario = hog.seqFormulario)
				  LEFT JOIN T_CIU_CIUDADANO ciu ON (hog.seqCiudadano = ciu.seqCiudadano)
				  LEFT JOIN T_FRM_ESTADO_PROCESO epr ON (frm.seqEstadoProceso = epr.seqEstadoProceso)
				  LEFT JOIN T_FRM_ETAPA eta ON (epr.seqEtapa = eta.seqEtapa)
				  LEFT JOIN T_AAD_FORMULARIO_ACTO aac ON (frm.seqFormulario = aac.seqFormulario)
				  LEFT JOIN T_PRY_PROYECTO pro ON (aac.seqProyecto = pro.seqProyecto)
				  LEFT JOIN T_AAD_HOGARES_VINCULADOS ahv ON (aac.seqFormularioActo = ahv.seqFormularioActo)
				  LEFT JOIN T_AAD_TIPO_ACTO ata ON ( ahv.seqTipoActo = ata.seqTipoActo )
				WHERE hog.seqParentesco = 1
				AND pro.seqTipoEsquema = 4
				AND ahv.seqTipoActo = 1
				ORDER BY 1,2,3
      ";
        $objRes = $aptBd->execute($sql);
        $this->obtenerReportesGeneral($objRes, "reporteAsignacionUnidadesMejoramiento");
    }

    public function exportableActosAdministrativosEpigrafe() {
        global $aptBd;

        $sql = "
               SELECT 
               aad.numActo as 'Número Resolución',
       aad.fchActo as 'Fecha Resolución',
       year(aad.fchActo) AS Año,
       tad.txtNombreTipoActo as 'Tipo Acto',
       trim(aad.txtValorCaracteristica) as 'Epígrafe '
  FROM    (   sdht_subsidios.T_AAD_CARACTERISTICA_ACTO tac
           INNER JOIN
              sdht_subsidios.t_aad_tipo_acto tad
           ON (tac.seqTipoActo = tad.seqTipoActo))
       INNER JOIN
          sdht_subsidios.T_AAD_ACTO_ADMINISTRATIVO aad
       ON     (aad.seqTipoActo = tad.seqTipoActo)
          AND (aad.seqCaracteristica = tac.seqCaracteristica)
 WHERE aad.seqCaracteristica IN (1, 2, 3, 8, 31)
ORDER BY aad.fchActo DESC;
      ";
        $objRes = $aptBd->execute($sql);
        $this->obtenerReportesGeneral($objRes, "reporteEpigrafeAAD");
    }

    public function reporteGeneralInscritos() {
        global $aptBd;

        $sql = "
               SELECT frm.seqFormulario,
       frm.txtFormulario,
       upper(concat(ciu.txtNombre1,
                    ' ',
                    ciu.txtNombre2,
                    ' ',
                    ciu.txtApellido1,
                    ' ',
                    ciu.txtApellido2))
          AS 'Nombre',
       ciu.numDocumento AS 'Documento',
       concat(eta.txtEtapa, ' - ', epr.txtEstadoProceso)
          AS 'Estado del Proceso',
       frm.seqEstadoProceso AS seqEstado,
       IF(
             ((frm.fchUltimaActualizacion > '2013-05-11')
          OR     (frm.fchInscripcion > '2013-05-11'))
             AND (ciu.fchNacimiento <> '0000-00-00'
			      AND frm.seqEstadoProceso <> 5                      #Renuncia
                  AND frm.seqEstadoProceso <> 8                  #Inhabilitado
                  AND frm.seqEstadoProceso <> 13        #Inscrito Inhabilitado
                  AND frm.seqEstadoProceso <> 14                     #Renuncia
                  AND frm.seqEstadoProceso <> 18                     #Renuncia
                  AND frm.seqEstadoProceso <> 35            #Inscrito Inactivo
                  AND frm.seqEstadoProceso <> 39        #Inscrito Inhabilitado
                  AND frm.seqEstadoProceso <> 52),               #Inhabilitado
          'ACTIVO',
          'INACTIVO')
          AS 'Activo / Inactivo',
       tvh.txtDesplazado AS 'Hogar Victima',
       moa.txtModalidad AS 'Modalidad',
       frm.seqModalidad AS 'SeqModalidad',
       CASE
          WHEN frm.seqModalidad = 1 THEN 'Adquisición de Vivienda Nueva'
          WHEN frm.seqModalidad = 2 THEN 'Construcción en Sitio Propio'
          WHEN frm.seqModalidad = 3 THEN 'Mejoramiento Habitacional'
          WHEN frm.seqModalidad = 5 THEN 'Adquisición de Vivienda Nueva'
          ELSE moa.txtModalidad
       END
          AS 'NModalidad',
       frm.valTotalRecursos AS 'Total Recursos Hogar',
       CASE
          WHEN frm.valTotalRecursos = 0
          THEN
             '$0'
          WHEN frm.valTotalRecursos BETWEEN 0 AND 2500000
          THEN
             '$1 -< $2.5M'
          WHEN frm.valTotalRecursos BETWEEN 2500000 AND 5000000
          THEN
             '$2.5M -< $5M'
		  WHEN frm.valTotalRecursos BETWEEN 5000000 AND 10000000
          THEN
             '$5M -< $10M' 
          WHEN frm.valTotalRecursos BETWEEN 10000000 AND 15000000
          THEN
             '$10M -< $15M'
          WHEN frm.valTotalRecursos BETWEEN 15000000 AND 20000000
          THEN
             '$15M -< $20M'
          WHEN frm.valTotalRecursos BETWEEN 20000000 AND 25000000
          THEN
             '$20M -< $25M'
          WHEN frm.valTotalRecursos BETWEEN 25000000 AND 30336020
          THEN
             '$25M -< $30336020'
          WHEN frm.valTotalRecursos >= 30336020
          THEN
             '> $30336020'
       END
          AS 'Rango Cierre Financiero',
       IF(
             (    tvh.txtDesplazado = 'Victima'
              AND frm.valTotalRecursos >= 30336020
              AND (   frm.seqModalidad = 1            #Adquisición de Vivienda
                   OR frm.seqModalidad = 5                      #Arrendamiento
                   OR frm.seqModalidad = 6      #Adquisición de Vivienda Nueva
                   OR frm.seqModalidad = 11))   #Adquisición de Vivienda Usada
          OR frm.seqModalidad = 10            #Mejoramiento en Redensificación
          OR frm.seqModalidad = 4                    #Mejoramiento Estructural
          OR frm.seqModalidad = 8                    #Mejoramiento Estructural
          OR frm.seqModalidad = 9                   #Mejoramiento Habitacional
          OR frm.seqModalidad = 3               #Mejoramiento de Habitabilidad
          OR frm.seqModalidad = 7                #Construcción en Sitio Propio
          OR frm.seqModalidad = 2                                #Construcción
          OR     frm.valTotalRecursos >= 30336020
             AND (   frm.seqModalidad = 1             #Adquisición de Vivienda
                  OR frm.seqModalidad = 5                       #Arrendamiento
                  OR frm.seqModalidad = 6       #Adquisición de Vivienda Nueva
                  OR frm.seqModalidad = 11),    #Adquisición de Vivienda Usada
          'CON CIERRE',
          'SIN CIERRE')
          AS 'cierre financiero',
       eta.txtEtapa AS 'Etapa',
       DATE_FORMAT(ciu.fchNacimiento, '%d-%m-%Y') AS 'Fecha de Nacimiento',
       DATE_FORMAT(frm.fchInscripcion, '%d-%m-%Y') AS 'Fecha de Inscripcion',
       DATE_FORMAT(frm.fchUltimaActualizacion, '%d-%m-%Y')
          AS 'Fecha Ultima Actualizacion',
       upper(concat(usu.txtNombre, ' ', usu.txtApellido)) AS 'Usuario',
       loc.txtLocalidad AS 'Localidad',
       frm.numTelefono1 AS 'Telefono Fijo 1',
       frm.numTelefono2 AS 'Telefono Fijo 2',
       frm.numCelular AS 'Telefono Celular',
       frm.txtCorreo AS 'Correo Electronico',
       pun.txtPuntoAtencion AS 'Punto de Atencion',
       IF(frm.bolCerrado = 1, 'SI', 'NO') AS 'Formulario Cerrado',
       pro.txtNombreProyecto AS 'Proyecto',
       upper(frm.txtMatriculaInmobiliaria) AS 'Matricula Inmobiliaria',
       frm.valIngresoHogar AS 'Ingresos del Hogar',
       frm.valSaldoCuentaAhorro AS 'Saldo Cuenta Ahorro 1',
       ba1.txtBanco AS 'Banco Cuenta Ahorro 1',
       upper(frm.txtSoporteCuentaAhorro) AS 'Soporte Cuenta Ahorro 1',
       IF(frm.bolInmovilizadoCuentaAhorro = 1, 'SI', 'NO')
          AS 'Cuenta Ahorro 1 Inmobilizada',
       DATE_FORMAT(frm.fchAperturaCuentaAhorro, '%d-%m-%Y')
          AS 'Fecha Apertura Cuenta Ahorro 1',
       frm.valSaldoCuentaAhorro2 AS 'Saldo Cuenta Ahorro 2',
       ba2.txtBanco AS 'Banco Cuenta Ahorro 2',
       upper(frm.txtSoporteCuentaAhorro2) AS 'Soporte Cuenta Ahorro 2',
       IF(frm.bolInmovilizadoCuentaAhorro2 = 1, 'SI', 'NO')
          AS 'Cuenta Ahorro 2 Inmobilizada',
       DATE_FORMAT(frm.fchAperturaCuentaAhorro2, '%d-%m-%Y')
          AS 'Fecha Apertura Cuenta Ahorro 2',
       frm.valSubsidioNacional AS 'Valor Subsidio (AVC / FOVIS / SFV)',
       upper(frm.txtSoporteSubsidioNacional)
          AS 'Soporte Subsidio (AVC / FOVIS / SFV)',
       ccf.txtEntidadSubsidio AS 'Entidad Subsidio (AVC / FOVIS / SFV)',
       frm.valAporteLote AS 'Valor Aporte Lote',
       frm.valSaldoCesantias AS 'Valor Cesantias',
       upper(frm.txtSoporteCesantias) AS 'Soporte Cesantias',
       frm.valAporteAvanceObra AS 'Valor Aporte Avance Obra',
       upper(frm.txtSoporteAvanceObra) AS 'Soporte Avance Obra',
       frm.valCredito AS 'Valor Credito',
       bcr.txtBanco AS 'Banco Credito',
       upper(frm.txtSoporteCredito) AS 'Soporte Credito',
       DATE_FORMAT(frm.fchAprobacionCredito, '%d-%m-%Y')
          AS 'Fecha Vencimiento del Credito',
       frm.valAporteMateriales AS 'Valor Aporte Materiales',
       upper(frm.txtSoporteAporteMateriales) AS 'Soporte Aporte Materiales',
       frm.valDonacion AS 'Valor Donacion / V.U.R.',
       edo.txtEmpresaDonante AS 'Empresa Donante / V.U.R.',
       upper(frm.txtSoporteDonacion) AS 'Soporte Donacion / V.U.R.',
       upper(frm.txtSoporteSubsidio) AS 'Soporte Cambio Valor Subsidio',
       frm.valTotalRecursos AS 'Total Recursos Hogar'
  FROM T_FRM_FORMULARIO frm
       INNER JOIN T_FRM_HOGAR hog ON hog.seqFormulario = frm.seqFormulario
       INNER JOIN T_CIU_CIUDADANO ciu ON hog.seqCiudadano = ciu.seqCiudadano
       INNER JOIN T_FRM_ESTADO_PROCESO epr
          ON frm.seqEstadoProceso = epr.seqEstadoProceso
       INNER JOIN T_FRM_ETAPA eta ON epr.seqEtapa = eta.seqEtapa
       INNER JOIN T_COR_USUARIO usu ON frm.seqUsuario = usu.seqUsuario
       INNER JOIN T_FRM_LOCALIDAD loc ON frm.seqLocalidad = loc.seqLocalidad
       INNER JOIN T_FRM_TIPO_VICTIMA_HOGAR tvh
          ON frm.bolDesplazado = tvh.bolDesplazado
       INNER JOIN T_FRM_PUNTO_ATENCION pun
          ON frm.seqPuntoAtencion = pun.seqPuntoAtencion
       INNER JOIN T_FRM_MODALIDAD moa ON frm.seqModalidad = moa.seqModalidad
       LEFT JOIN T_PRY_PROYECTO pro ON frm.seqProyecto = pro.seqProyecto
       INNER JOIN T_FRM_BANCO ba1 ON frm.seqBancoCuentaAhorro = ba1.seqBanco
       INNER JOIN T_FRM_BANCO ba2 ON frm.seqBancoCuentaAhorro2 = ba2.seqBanco
       INNER JOIN T_FRM_ENTIDAD_SUBSIDIO ccf
          ON frm.seqEntidadSubsidio = ccf.seqEntidadSubsidio
       INNER JOIN T_FRM_BANCO bcr ON frm.seqBancoCredito = bcr.seqBanco
       INNER JOIN T_FRM_EMPRESA_DONANTE edo
          ON frm.seqEmpresaDonante = edo.seqEmpresaDonante
 WHERE     (ciu.numDocumento >= 0 AND frm.bolCerrado = 0 AND 1 = 1)
       AND numDocumento > 0
       AND hog.seqParentesco = 1  ;";
        $objRes = $aptBd->execute($sql);
        $this->obtenerReportesGeneral($objRes, "reporteGeneralInscritos");
    }

    public function Caracterizacion() {
        

        global $aptBd;

        $arrErrores = &$this->arrErrores;


        if (empty($arrErrores)) {

            $sql = "SELECT frm.seqFormulario,
       pry.txtNombreProyecto,
       IF(COUNT(
             IF(cabezaFamilia(ciu.seqCondicionEspecial,
                              ciu.seqCondicionEspecial2,
                              ciu.seqCondicionEspecial3) = 'Si',
                1,
                NULL)) >= 1,
          'Si',
          'No')
          AS CabezaFamilia,
       COUNT(
          IF(cabezaFamilia(ciu.seqCondicionEspecial,
                           ciu.seqCondicionEspecial2,
                           ciu.seqCondicionEspecial3) = 'Si',
             1,
             NULL))
          AS NumCabezaFamilia,
       COUNT(
          IF(
                 cabezaFamilia(ciu.seqCondicionEspecial,
                               ciu.seqCondicionEspecial2,
                               ciu.seqCondicionEspecial3) = 'Si'
             AND (seqSexo = 1),
             1,
             NULL))
          AS Masculino,
       COUNT(
          IF(
                 cabezaFamilia(ciu.seqCondicionEspecial,
                               ciu.seqCondicionEspecial2,
                               ciu.seqCondicionEspecial3) = 'Si'
             AND (seqSexo = 2),
             1,
             NULL))
          AS Femenino,
       IF(COUNT(
             IF(mayor65anos(ciu.seqCondicionEspecial,
                            ciu.seqCondicionEspecial2,
                            ciu.seqCondicionEspecial3) = 'Si',
                1,
                NULL)) >= 1,
          'Si',
          'No')
          AS Mayor65Anos,
       COUNT(
          IF(
                 mayor65anos(ciu.seqCondicionEspecial,
                             ciu.seqCondicionEspecial2,
                             ciu.seqCondicionEspecial3) = 'Si'
             AND (seqSexo = 1),
             1,
             NULL))
          AS '>65-Masculino',
       COUNT(
          IF(
                 mayor65anos(ciu.seqCondicionEspecial,
                             ciu.seqCondicionEspecial2,
                             ciu.seqCondicionEspecial3) = 'Si'
             AND (seqSexo = 2),
             1,
             NULL))
          AS '>65-Femenino',
       IF(COUNT(
             IF(discapacitado(ciu.seqCondicionEspecial,
                              ciu.seqCondicionEspecial2,
                              ciu.seqCondicionEspecial3) = 'Si',
                1,
                NULL)) >= 1,
          'Si',
          'No')
          AS Discapacitado,
       COUNT(
          IF(
                 discapacitado(ciu.seqCondicionEspecial,
                               ciu.seqCondicionEspecial2,
                               ciu.seqCondicionEspecial3) = 'Si'
             AND (seqSexo = 1),
             1,
             NULL))
          AS 'Disc-Masculino',
       COUNT(
          IF(
                 discapacitado(ciu.seqCondicionEspecial,
                               ciu.seqCondicionEspecial2,
                               ciu.seqCondicionEspecial3) = 'Si'
             AND (seqSexo = 2),
             1,
             NULL))
          AS 'Disc-Femenino',
       COUNT(frm.seqformulario) AS TotalMiembros,
       COUNT(IF(seqSexo = 1, 1, NULL)) AS MiembrosMasculino,
       COUNT(IF(seqSexo = 2, 1, NULL)) AS MiembrosFemenino,
       COUNT(
          IF(
             rangoEdad(FLOOR((DATEDIFF(NOW(), ciu.fchNacimiento) / 365)))   = '0 a 5',
             1,
             NULL))
          AS '0 a 5',
       COUNT(
          IF(
             rangoEdad(FLOOR((DATEDIFF(NOW(), ciu.fchNacimiento) / 365)))   = '6 a 13',
             1,
             NULL))
          AS '6 a 13',
       COUNT(
          IF(
             rangoEdad(FLOOR((DATEDIFF(NOW(), ciu.fchNacimiento) / 365)))   = '14 a 17',
             1,
             NULL))
          AS '14 a 17',
       COUNT(
          IF(
             rangoEdad(FLOOR((DATEDIFF(NOW(), ciu.fchNacimiento) / 365)))   = '18 a 26',
             1,
             NULL))
          AS '18 a 26',
       COUNT(
          IF(
             rangoEdad(FLOOR((DATEDIFF(NOW(), ciu.fchNacimiento) / 365)))   = '27 a 59',
             1,
             NULL))
          AS '27 a 59',
       COUNT(
          IF(
             rangoEdad(FLOOR((DATEDIFF(NOW(), ciu.fchNacimiento) / 365)))   = 'Mayor de 60',
             1,
             NULL))
          AS 'Mayor de 60',
       IF(COUNT(IF(ciu.seqEtnia <> 1, 1, NULL) >= 1), 'Si', 'No') AS Etnia,
       #COUNT(IF(ciu.seqEtnia = 1, 1, NULL)) AS Ninguna,               #Ninguna
       COUNT(IF(ciu.seqEtnia = 2, 1, NULL)) AS Indigena,             #Indigena
       COUNT(IF(ciu.seqEtnia = 3, 1, NULL)) AS ROM,                       #ROM
       COUNT(IF(ciu.seqEtnia = 4, 1, NULL)) AS Raizal,                 #Raizal
       COUNT(IF(ciu.seqEtnia = 5, 1, NULL)) AS Palenquero,         #Palenquero
       COUNT(IF(ciu.seqEtnia = 6, 1, NULL)) AS Afrocolombiano, #Afrocolombiano
       COUNT(
          IF(
                 ((FLOOR((DATEDIFF(NOW(), ciu.fchNacimiento) / 365))) >= 14) # mayor o igual a 14 años
             AND (ciu.seqNivelEducativo <= 3), # menor o igual a primaria completa
             1,
             NULL))
          AS Analfabetismo
  FROM T_FRM_FORMULARIO frm
       INNER JOIN T_FRM_HOGAR hog ON hog.seqFormulario = frm.seqFormulario
       INNER JOIN T_CIU_CIUDADANO ciu ON hog.seqCiudadano = ciu.seqCiudadano
       LEFT JOIN T_CIU_ETNIA etn ON ciu.seqEtnia = etn.seqEtnia
       INNER JOIN t_ciu_nivel_educativo edu
          ON ciu.seqNivelEducativo = edu.seqNivelEducativo
       LEFT JOIN T_PRY_PROYECTO pry ON pry.seqProyecto = frm.seqProyecto
 WHERE frm.seqFormulario in (" . $this->seqFormularios . ")
     GROUP BY frm.seqFormulario
				";

            try {
                //pr ($sql); die();
                //echo'hasta aca entro';
                $objRes = $aptBd->execute($sql);
                //pr($objRes);
                //die();
                $arrTitulosCampos[] = 'seqFormulario';
                $arrTitulosCampos[] = 'txtNombreProyecto';
                $arrTitulosCampos[] = 'CabezaFamilia';
                $arrTitulosCampos[] = 'NumCabezaFamilia';
                $arrTitulosCampos[] = 'Masculino';
                $arrTitulosCampos[] = 'Femenino';
                $arrTitulosCampos[] = 'Mayor65Anos';
                $arrTitulosCampos[] = '>65-Masculino';
                $arrTitulosCampos[] = '>65-Femenino';
                $arrTitulosCampos[] = 'Discapacitado';
                $arrTitulosCampos[] = 'Disc-Masculino';
                $arrTitulosCampos[] = 'Disc-Femenino';
                $arrTitulosCampos[] = 'TotalMiembros';
                $arrTitulosCampos[] = 'MiembrosMasculino';
                $arrTitulosCampos[] = 'MiembrosFemenino';
                $arrTitulosCampos[] = '0 a 5';
                $arrTitulosCampos[] = '6 a 13';
                $arrTitulosCampos[] = '14 a 17';
                $arrTitulosCampos[] = '18 a 26';
                $arrTitulosCampos[] = '27 a 59';
                $arrTitulosCampos[] = 'Mayor de 60';
                $arrTitulosCampos[] = 'Etnia';
                $arrTitulosCampos[] = 'Indigena';
                $arrTitulosCampos[] = 'ROM';
                $arrTitulosCampos[] = 'Raizal';
                $arrTitulosCampos[] = 'Palenquero';
                $arrTitulosCampos[] = 'Afrocolombiano';
                $arrTitulosCampos[] = 'Analfabetismo';


                $this->obtenerReportesGeneral($objRes, "Caracterizacion", $arrTitulosCampos);
            } catch (Exception $objError) {
                $arrErrores[] = "Se ha producido un error al consultar los datos";
            }

            if (!empty($arrErrores)) {

                imprimirMensajes($arrErrores, array());
            }
        } else {
            imprimirMensajes($arrErrores, array());
        }
    }

    public function reporteBasedeDatosPoblacional() {

        global $aptBd;

        $fechaIni = $_POST['fchInicio'];
        $fechaFin = $_POST['fchFin'];

        $arrCondiciones[] = "ciu.fchNacimiento > 0000-00-00";

        if ($fechaIni) {
            $arrCondiciones[] = "fchUltimaActualizacion >= '$fechaIni.00:00:00'";
        } else {
            $arrCondiciones[] = "ciu.numDocumento > '0'";
        }
        if ($fechaFin) {
            $arrCondiciones[] = "fchUltimaActualizacion <= '$fechaFin.23:59:59'";
        } else {
            $arrCondiciones[] = "ciu.numDocumento > '0'";
        }

        $txtCondicion = implode(" and ", $arrCondiciones);



        $sql = "SELECT ciu.seqCiudadano AS id,
		frm.seqFormulario AS idhogar,
		DATE_FORMAT(frm.fchUltimaActualizacion, '%d-%m-%Y')
          AS  ultimaactualizacion,
		upper(ciu.txtNombre1) AS nombre_1,
		upper(ciu.txtNombre2) AS nombre_2,
		upper(ciu.txtApellido1) AS apellido_1,
		upper(ciu.txtApellido2) AS apellido_2,
		CASE
		 WHEN ciu.seqTipoDocumento = 1 THEN 'CC'
		 WHEN ciu.seqTipoDocumento = 2 THEN 'CE'
		 WHEN ciu.seqTipoDocumento = 3 THEN 'TI'
		 WHEN ciu.seqTipoDocumento = 4 THEN 'RC'
		 WHEN ciu.seqTipoDocumento = 5 THEN 'PA'
		 WHEN ciu.seqTipoDocumento = 6 THEN 'NIT'
		 WHEN ciu.seqTipoDocumento = 7 THEN 'NUIP'
		 ELSE 'SI'
		END
		AS tip_id,
		ciu.numDocumento AS num_id,
		'' AS mun_nac,
		'' AS pais_nac,
		'' AS fec_id,
		CASE WHEN sex.txtSexo = 'Masculino' THEN 'H' ELSE 'M' END AS sexo,
		DATE_FORMAT(ciu.fchNacimiento, '%Y-%m-%d') AS fec_nac,
		'' AS gru_sang,
		'' AS fact_rh,
		CASE
		 WHEN ciu.seqEtnia = 1 THEN '9'
		 WHEN ciu.seqEtnia = 2 THEN '1'
		 WHEN ciu.seqEtnia = 3 THEN '2'
		 WHEN ciu.seqEtnia = 4 THEN '3'
		 WHEN ciu.seqEtnia = 5 THEN '4'
		 WHEN ciu.seqEtnia = 6 THEN '5'
		END
		AS etnia,
		'' AS cual_etnia,
		'' AS genero,
		'' AS cual_genero,
		'' AS nom_identitario,
		CASE
		WHEN osex.seqGrupoLgtbi = 1 THEN '1'
		WHEN osex.seqGrupoLgtbi = 2 THEN '1'
		WHEN osex.seqGrupoLgtbi = 0 THEN '2'
		WHEN osex.seqGrupoLgtbi = 4 THEN '3'
		WHEN osex.seqGrupoLgtbi = 3 THEN '8'
		WHEN osex.seqGrupoLgtbi = 5 THEN '8'
		ELSE '9'
		END
		AS orient_sex,
		IF((osex.seqGrupoLgtbi = 3 OR osex.seqGrupoLgtbi = 5),
		osex.txtGrupoLgtbi,
		'')
		AS cual_orient_sex,
		'' AS ocupacion,
		'' AS cual_ocupacion,
		'' AS cond_habitacion,
		'' AS tipo_aten_pob_infantil,
		'' AS ocup_especial,
		IF(frm.bolDesplazado = 1, 'D-04', '') AS cond_especial,
		'' AS cara_espe_padres,
		IF(ciu.seqCondicionEspecial = 3, 'F-02', '') AS cond_espe_salud,
		'' AS traba_sexual,
		'' AS persona_talento,
		'' AS est_afi_sgsss,
		CASE
		 WHEN frm.seqLocalidad = 2 THEN '15'
		 WHEN frm.seqLocalidad = 3 THEN '12'
		 WHEN frm.seqLocalidad = 4 THEN '07'
		 WHEN frm.seqLocalidad = 5 THEN '02'
		 WHEN frm.seqLocalidad = 6 THEN '19'
		 WHEN frm.seqLocalidad = 7 THEN '10'
		 WHEN frm.seqLocalidad = 8 THEN '09'
		 WHEN frm.seqLocalidad = 9 THEN '08'
		 WHEN frm.seqLocalidad = 10 THEN '17'
		 WHEN frm.seqLocalidad = 11 THEN '14'
		 WHEN frm.seqLocalidad = 12 THEN '16'
		 WHEN frm.seqLocalidad = 13 THEN '18'
		 WHEN frm.seqLocalidad = 14 THEN '04'
		 WHEN frm.seqLocalidad = 15 THEN '03'
		 WHEN frm.seqLocalidad = 16 THEN '11'
		 WHEN frm.seqLocalidad = 17 THEN '20'
		 WHEN frm.seqLocalidad = 18 THEN '13'
		 WHEN frm.seqLocalidad = 19 THEN '06'
		 WHEN frm.seqLocalidad = 20 THEN '01'
		 WHEN frm.seqLocalidad = 21 THEN '05'
		ELSE ''
		END
		AS localidad,
		'' AS tipo_zona,
		'' AS tip_via_prin,
		'' AS num_via_prin,
		'' AS nom_via_prin,
		'' AS nom_sin_via_prin,
		'' AS letra_via_prin,
		'' AS bis,
		'' AS letra_Bis,
		'' AS cuad_via_prin,
		'' AS num_via_gen,
		'' AS letra_via_gen,
		'' AS num_placa,
		'' AS cuad_via_gen,
		'' AS complemento,
		IF(frm.bolDesplazado = 1, '', upper(frm.txtDireccion))
		AS direccion_rural,
		'' AS estrato,
		#frm.bolDesplazado AS VICTIMA,
		IF(frm.bolDesplazado = 1, '', frm.numTelefono1) AS tel_fijo_contacto, #NO SE IMPRIME INFO SI ES VICTIMA BOLDESPLAZADO=1
		IF(frm.bolDesplazado = 1, '', frm.numCelular) AS tel_celular_contacto, #NO SE IMPRIME INFO SI ES VICTIMA BOLDESPLAZADO=1
		IF(frm.bolDesplazado = 1, '', frm.txtCorreo) AS correo_electr, #NO SE IMPRIME INFO SI ES VICTIMA BOLDESPLAZADO=1
		'' AS localidad_contacto,
		'' AS tipo_zona_contacto,
		'' AS tip_via_prin_contacto,
		'' AS num_via_prin_contacto,
		'' AS nom_via_prin_contacto,
		'' AS nom_sin_via_prin_contacto,
		'' AS letra_via_prin_contacto,
		'' AS bis_contacto,
		'' AS letra_bis_contacto,
		'' AS cuad_via_prin_contacto,
		'' AS num_via_gen_contacto,
		'' AS letra_via_gen_contacto,
		'' AS num_placa_contacto,
		'' AS cuad_via_gen_contacto,
		'' AS complemento_contacto,
		'' AS direccion_rural_contacto,
		'' AS estrato_contacto,
		'' AS tel_fijo_contacto_contacto,
		'' AS tel_celular_contacto_contacto,
		'' AS correo_electr_contacto,
		'' AS nombre_contacto
		FROM T_FRM_FORMULARIO frm
		INNER JOIN T_FRM_HOGAR hog ON hog.seqFormulario = frm.seqFormulario
		INNER JOIN T_CIU_CIUDADANO ciu ON hog.seqCiudadano = ciu.seqCiudadano
		INNER JOIN T_CIU_SALUD sal ON ciu.seqSalud = sal.seqSalud
		INNER JOIN T_CIU_SEXO sex ON ciu.seqSexo = sex.seqSexo
		INNER JOIN T_FRM_GRUPO_LGTBI osex
		ON ciu.seqGrupolgtbi = osex.seqGrupolgtbi
		LEFT JOIN T_CIU_ETNIA etn ON ciu.seqEtnia = etn.seqEtnia
		WHERE $txtCondicion";

        $objRes = $aptBd->execute($sql);
        $this->obtenerReportesGeneral($objRes, "reporteBasedeDatosPoblacional");
    }

    public function InformacionSolucion() {

        global $aptBd;


        $arrErrores = &$this->arrErrores;

        if (empty($arrErrores)) {


            /* $sql = "SELECT frm.seqFormulario AS 'id Hogar',
              (SELECT ciu1.numDocumento
              FROM T_FRM_HOGAR hog1
              INNER JOIN T_CIU_CIUDADANO ciu1
              ON hog1.seqCiudadano = ciu1.seqCiudadano
              WHERE     hog1.seqFormulario = hog.seqFormulario
              AND hog1.seqParentesco = 1)
              AS 'CC Postulante Principal',
              tdo.txtTipoDocumento AS 'Tipo Documento',
              (SELECT UPPER(CONCAT(ciu1.txtNombre1,
              ' ',
              ciu1.txtNombre2,
              ' ',
              ciu1.txtApellido1,
              ' ',
              ciu1.txtApellido2))
              FROM T_FRM_HOGAR hog1
              INNER JOIN T_CIU_CIUDADANO ciu1
              ON hog1.seqCiudadano = ciu1.seqCiudadano
              WHERE     hog1.seqFormulario = hog.seqFormulario
              AND hog1.seqParentesco = 1)
              AS 'Nombre',
              frm.txtFormulario AS 'Formulario',
              frm.seqProyecto AS 'Proyecto Padre',
              frm.seqProyectoHijo AS 'Proyecto Hijo',
              pry.txtNombreProyecto AS 'Proyecto',
              frm.seqUnidadproyecto,
              und.txtNombreunidad AS 'Unidad Proyecto',
              und.valSDVEAprobado AS 'Valor Aprobado',
              und.valSDVEActual AS 'Valor Actual',
              tec.txtexistencia AS 'Viabilidad Tecnica',
              concat('Res. ', aad.numActo) AS 'Resolucion Vinculacion',
              year(aad.fchActo) AS 'Año',
              aad.fchActo AS 'Fecha Resolucion',
              CONCAT(eta.txtEtapa, ' - ', epr.txtEstadoProceso) AS 'Estado del Proceso',
              frm.valAspiraSubsidio AS 'Valor Subsidio',
              IF(frm.SeqProyectoHijo = '', upper(pry.txtNombreComercial), upper(prh.txtNombreComercial)) as 'Nombre Comercial',
              und.txtNombreunidad AS 'Descripcion de la Unidad',
              GROUP_CONCAT(
              DISTINCT (upper(concat(CONVERT(ciu.txtNombre1 USING utf8),
              ' ',
              CONVERT(ciu.txtNombre2 USING utf8),
              ' ',
              CONVERT(ciu.txtApellido1 USING utf8),
              ' ',
              CONVERT(ciu.txtApellido2 USING utf8)))),
              '  ',
              CONVERT(tdo.txtTipoDocumento USING utf8),
              '  ',
              ciu.numDocumento,
              '  ',
              civ.txtEstadocivil,
              '  ' SEPARATOR' -- ') AS 'Miembros Mayores de Edad'
              FROM T_FRM_FORMULARIO frm
              LEFT JOIN T_PRY_PROYECTO pry ON (frm.seqProyecto = pry.seqProyecto)
              LEFT JOIN T_PRY_PROYECTO prh ON (frm.seqProyectoHijo = prh.seqProyecto)
              LEFT JOIN T_PRY_UNIDAD_PROYECTO und ON (frm.seqFormulario = und.seqFormulario)
              LEFT JOIN T_FRM_ESTADO_PROCESO epr ON (frm.seqEstadoProceso = epr.seqEstadoProceso)
              LEFT JOIN T_FRM_ETAPA eta ON (epr.seqEtapa = eta.seqEtapa)
              INNER JOIN T_FRM_VALOR_SUBSIDIO vsu ON (frm.seqModalidad = vsu.seqModalidad) AND (vsu.seqSolucion = frm.seqSolucion)
              LEFT JOIN T_FRM_HOGAR hog ON (frm.seqFormulario = hog.seqFormulario)
              LEFT JOIN T_CIU_CIUDADANO ciu ON (hog.seqCiudadano = ciu.seqCiudadano)
              INNER JOIN T_CIU_PARENTESCO par ON (par.seqParentesco = hog.seqParentesco)
              INNER JOIN T_CIU_ESTADO_CIVIL civ ON (civ.seqEstadoCivil = ciu.seqEstadoCivil)
              INNER JOIN T_CIU_TIPO_DOCUMENTO tdo ON (ciu.seqTipoDocumento = tdo.seqTipoDocumento)
              LEFT JOIN T_PRY_TECNICO tec ON (und.seqUnidadProyecto = tec.seqUnidadProyecto)
              LEFT JOIN T_AAD_FORMULARIO_ACTO fac ON (frm.seqFormulario = fac.seqFormulario)
              LEFT JOIN T_AAD_HOGARES_VINCULADOS hvi ON (fac.seqFormularioActo = hvi.seqFormularioActo)

              INNER JOIN
              (SELECT *
              FROM T_AAD_ACTO_ADMINISTRATIVO
              ORDER BY T_AAD_ACTO_ADMINISTRATIVO.fchActo desc)
              AS aad ON ( hvi.numActo =
              aad.numActo
              AND hvi.fchActo =
              aad.fchActo)

              WHERE   (
              tdo.seqTipoDocumento =1
              OR tdo.seqTipoDocumento =2
              OR tdo.seqTipoDocumento =5
              )
              AND frm.seqFormulario IN (". $this->seqFormularios ." )
              GROUP BY frm.seqFormulario
              "; */

            $sql = "select frm.seqFormulario AS 'id Hogar',
ciu.numDocumento 'CC Postulante Principal',
tdo.txtTipoDocumento AS 'Tipo Documento',
UPPER(CONCAT(ciu.txtNombre1, ' ', ciu.txtNombre2, ' ', ciu.txtApellido1, ' ', ciu.txtApellido2)) AS 'Nombre',
frm.txtFormulario AS 'Formulario', 
frm.seqProyecto AS 'Proyecto Padre', 
frm.seqProyectoHijo AS 'Proyecto Hijo',
pry.txtNombreProyecto AS 'Proyecto',
frm.seqUnidadproyecto, und.txtNombreunidad AS 'Unidad Proyecto',
und.valSDVEAprobado AS 'Valor Aprobado', 
und.valSDVEActual AS 'Valor Actual',
tec.txtexistencia AS 'Viabilidad Tecnica', 
CONCAT('Res. ', aad.numActo) AS 'Resolucion Vinculacion',
YEAR(aad.fchActo) AS 'Año', 
aad.fchActo AS 'Fecha Resolucion', 
CONCAT(eta.txtEtapa, ' - ', epr.txtEstadoProceso) AS 'Estado del Proceso', 
frm.valAspiraSubsidio AS 'Valor Subsidio',
IF(frm.SeqProyectoHijo = '', UPPER(pry.txtNombreComercial), UPPER(prh.txtNombreComercial)) AS 'Nombre Comercial', 
und.txtNombreunidad AS 'Descripcion de la Unidad',
(SELECT 
GROUP_CONCAT(DISTINCT (UPPER(CONCAT(CONVERT(ciu1.txtNombre1 USING utf8), ' ', 
CONVERT(ciu1.txtNombre2 USING utf8), ' ', CONVERT(ciu1.txtApellido1 USING utf8), ' ', 
CONVERT(ciu1.txtApellido2 USING utf8)))), ' ', CONVERT(tdo.txtTipoDocumento USING utf8),' ', ciu1.numDocumento, ' ',  civ.txtEstadocivil, ' ' SEPARATOR' -- ')
FROM T_FRM_HOGAR hog1
INNER JOIN T_CIU_CIUDADANO ciu1 ON hog1.seqCiudadano = ciu1.seqCiudadano
WHERE hog1.seqFormulario =  hog.seqFormulario and ( ciu1.seqTipoDocumento =1 OR ciu1.seqTipoDocumento =2 OR ciu1.seqTipoDocumento =5 )) AS 'Miembros Mayores de Edad'
FROM T_FRM_FORMULARIO frm 
INNER JOIN T_FRM_VALOR_SUBSIDIO vsu ON (frm.seqModalidad = vsu.seqModalidad) AND (vsu.seqSolucion = frm.seqSolucion)
LEFT JOIN T_FRM_HOGAR hog ON (frm.seqFormulario = hog.seqFormulario)
LEFT JOIN T_PRY_PROYECTO pry ON (frm.seqProyecto = pry.seqProyecto) 
LEFT JOIN T_PRY_PROYECTO prh ON (frm.seqProyectoHijo = prh.seqProyecto) 
LEFT JOIN T_PRY_UNIDAD_PROYECTO und ON (frm.seqFormulario = und.seqFormulario) 
LEFT JOIN T_PRY_TECNICO tec ON (und.seqUnidadProyecto = tec.seqUnidadProyecto)
LEFT JOIN T_FRM_ESTADO_PROCESO epr ON (frm.seqEstadoProceso = epr.seqEstadoProceso) 
LEFT JOIN T_FRM_ETAPA eta ON (epr.seqEtapa = eta.seqEtapa)
LEFT JOIN T_CIU_CIUDADANO ciu ON (hog.seqCiudadano = ciu.seqCiudadano)
INNER JOIN T_CIU_TIPO_DOCUMENTO tdo ON (tdo.seqTipoDocumento  = ciu.seqTipoDocumento )
INNER JOIN T_CIU_PARENTESCO par ON (par.seqParentesco = hog.seqParentesco) 
INNER JOIN T_CIU_ESTADO_CIVIL civ ON (civ.seqEstadoCivil = ciu.seqEstadoCivil)  
LEFT JOIN T_AAD_FORMULARIO_ACTO fac ON (frm.seqFormulario = fac.seqFormulario) 
LEFT JOIN T_AAD_HOGARES_VINCULADOS hvi ON (fac.seqFormularioActo = hvi.seqFormularioActo) 
INNER JOIN (SELECT * FROM T_AAD_ACTO_ADMINISTRATIVO ORDER BY T_AAD_ACTO_ADMINISTRATIVO.fchActo desc) AS aad ON ( hvi.numActo = aad.numActo AND hvi.fchActo = aad.fchActo)
WHERE ( tdo.seqTipoDocumento =1 OR tdo.seqTipoDocumento =2 OR tdo.seqTipoDocumento =5 ) AND hog.seqParentesco = 1  AND frm.seqFormulario IN (" . $this->seqFormularios . " )"
                    . "GROUP BY frm.seqFormulario order by frm.seqFormulario";

            try {

                $objRes = $aptBd->execute($sql);

                $this->obtenerReportesGeneral($objRes, "InformacionSolucion");
            } catch (Exception $objError) {
                $arrErrores[] = "Se ha producido un error al consultar los datos";
            }

            if (!empty($arrErrores)) {
                imprimirMensajes($arrErrores, array());
            }
        } else {
            imprimirMensajes($arrErrores, array());
        }
    }

    //Plantilla Estudio Titulos 
    public function plantillaestudiotitulos() {
        plantillaestudiotitulos($this->seqFormularios);
    }

    public function informeProyectos() {
        informeProyectosActo();
    }

}

// fin clase reportes
?>
