<?php
include '../../../recursos/archivos/verificarSesion.php';
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <!-- Estilos CSS -->
        <link href="../../../librerias/bootstrap/css/bootstrap.css" rel="stylesheet">
        <link href="../../../librerias/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
        <link href="../../../librerias/bootstrap/css/bootstrap-theme.css" rel="stylesheet">
    </head>
    <body>

        <div id="contenidos" class="container">
            <div class="hero-unit-header" style="background-color: #289bae; color: white; text-align: center">
                Modulo Migraci&oacute;n De Estudios T&eacute;cnicos De Proyectos A Desembolso
            </div>

            <div class="well">
                <?php
                include "../lib/mysqli/shared/ez_sql_core.php";
                include "../lib/mysqli/ez_sql_mysqli.php";
                include "generarConsolidado.php";
                //include "generarLinksImpresion.php";

                date_default_timezone_set('America/Bogota');
                $arrDocumentosArchivo = array();

                $db = new ezSQL_mysqli('sdht_usuario', 'Ochochar*1', 'sdht_subsidios', 'localhost');
               // $db = new ezSQL_mysqli('sdht_usuario', 'Ochochar*1', 'sdth_subsidiosentrega', 'localhost');

                $camposTecnico = "numLargoMultiple, numAnchoMultiple, numAreaMultiple, txtMultiple, numLargoAlcoba1, numAnchoAlcoba1, numAreaAlcoba1, txtAlcoba1, numLargoAlcoba2, numAnchoAlcoba2, numAreaAlcoba2, txtAlcoba2, numLargoAlcoba3, numAnchoAlcoba3, numAreaAlcoba3, txtAlcoba3, numLargoCocina, numAnchoCocina, numAreaCocina, txtCocina, numLargoBano1, numAnchoBano1, numAreaBano1, txtBano1, numLargoBano2, numAnchoBano2, numAreaBano2, txtBano2, numLargoLavanderia, numAnchoLavanderia, numAreaLavanderia, txtLavanderia, numLargoCirculaciones, numAnchoCirculaciones, numAreaCirculaciones, txtCirculaciones, numLargoPatio, numAnchoPatio, numAreaPatio, txtPatio, numAreaTotal, txtEstadoCimentacion, txtCimentacion, txtEstadoPlacaEntrepiso, txtPlacaEntrepiso, txtEstadoMamposteria, txtMamposteria, txtEstadoCubierta, txtCubierta, txtEstadoVigas, txtVigas, txtEstadoColumnas, txtColumnas, txtEstadoPanetes, txtPanetes, txtEstadoEnchapes, txtEnchapes, txtEstadoAcabados, txtAcabados, txtEstadoHidraulicas, txtHidraulicas, txtEstadoElectricas, txtElectricas, txtEstadoSanitarias, txtSanitarias, txtEstadoGas, txtGas, txtEstadoMadera, txtMadera, txtEstadoMetalica, txtMetalica, numLavadero, txtLavadero, numLavaplatos, txtLavaplatos, numLavamanos, txtLavamanos, numSanitario, txtSanitario, numDucha, txtDucha, txtEstadoVidrios, txtVidrios, txtEstadoPintura, txtPintura, txtOtros, txtObservacionOtros, numContadorAgua, txtEstadoConexionAgua, txtDescripcionAgua, numContadorEnergia, txtEstadoConexionEnergia, txtDescripcionEnergia, numContadorAlcantarillado, txtEstadoConexionAlcantarillado, txtDescripcionAlcantarillado, numContadorGas, txtEstadoConexionGas, txtDescripcionGas, numContadorTelefono, txtEstadoConexionTelefono, txtDescripcionTelefono, txtEstadoAndenes, txtDescripcionAndenes, txtEstadoVias, txtDescripcionVias, txtEstadoServiciosComunales, txtDescripcionServiciosComunales, txtDescripcionVivienda, txtNormaNSR98, txtRequisitos, txtExistencia, txtDescipcionNormaNSR98, txtDescripcionRequisitos, txtDescripcionExistencia, fchVisita, txtAprobo, fchCreacion, fchActualizacion";
                $camposAdjuntosTecnicos = "seqTipoAdjunto, txtNombreAdjunto, txtNombreArchivo";

                if (isset($_FILES["archivo"]) && is_uploaded_file($_FILES['archivo']['tmp_name'])) {
                    $nombreArchivo = $_FILES['archivo']['tmp_name'];
                    $lineas = file($nombreArchivo);
                    foreach ($lineas as $linea_num => $linea) {
                        array_push($arrDocumentosArchivo, trim($linea));
                    }
                } else {
                    exit('debe seleccionar un archivo. <img border="0" src="../lib/back.png" width="30" height="30" style="cursor: pointer" onClick="history.back()">Volver');
                }


                $separado_por_comas = implode(",", $arrDocumentosArchivo);

                // Valida si el documento ya tiene un estudio tecnico, devuelve true si es posible migrar, false si hay algun con estudio.

                function validarEstudioTecnico() {
                    global $separado_por_comas;
                    global $db;
                    $varRet = false;
                    $documentos = "";

                    $sql = "SELECT T_DES_TECNICO.seqTecnico, numDocumento
						FROM (((T_DES_TECNICO
						INNER JOIN T_DES_DESEMBOLSO ON (T_DES_TECNICO.seqDesembolso = T_DES_DESEMBOLSO.seqDesembolso))
						INNER JOIN T_FRM_FORMULARIO ON (T_DES_DESEMBOLSO.seqFormulario = T_FRM_FORMULARIO.seqFormulario))
						INNER JOIN T_FRM_HOGAR ON (T_FRM_HOGAR.seqFormulario = T_FRM_FORMULARIO.seqFormulario))
						INNER JOIN T_CIU_CIUDADANO ON (T_FRM_HOGAR.seqCiudadano = T_CIU_CIUDADANO.seqCiudadano)
						WHERE T_CIU_CIUDADANO.numDocumento IN ($separado_por_comas);";

                    $resultado = $db->get_results($sql);
                    if ($resultado) {
                        foreach ($resultado as $res) {
                            $documentos .= $res->numDocumento . ", ";
                        }
                        $varRet = false;
                        //exit("Revise el contenido de las cedulas, una de estas ya tiene un estudio tecnico.");
                    } else {
                        //echo"listo para subida";
                        $varRet = true;
                    }
                    if ($documentos != "")
                        echo "<p class='alert alert-success'>Los estudios tecnicos de los documentos: $documentos ya existen por favor verifique y elimine del archivo</p>";
                    return $varRet;
                }

                function obtenerValoresUtiles($documento, $campo) {
                    $valor;
                    $consulta_pre = "SELECT ciu.numDocumento AS numDocumento,
									   des.seqDesembolso AS seqDesembolso,
									   destec.seqTecnico AS seqTecnico,
									   frm.txtFormulario AS txtFormulario,
									   frm.seqFormulario AS seqFormulario,
									   und.seqUnidadProyecto AS unidadseqUnidadProyecto,
									   hog.seqParentesco AS seqParentesco,
									   prytec.seqUnidadProyecto seqUnidadProyecto,
									   und.txtNombreUnidad txtNombreUnidad,
									   pry.txtNombreProyecto AS txtNombreProyecto
								FROM ((((((T_PRY_UNIDAD_PROYECTO und
								INNER JOIN T_FRM_FORMULARIO frm ON (und.seqFormulario = frm.seqFormulario))
								INNER JOIN T_FRM_HOGAR hog ON (hog.seqFormulario = frm.seqFormulario))
								INNER JOIN T_CIU_CIUDADANO ciu ON (hog.seqCiudadano = ciu.seqCiudadano))
								LEFT OUTER JOIN T_DES_DESEMBOLSO des ON (des.seqFormulario = frm.seqFormulario))
								LEFT OUTER JOIN T_DES_TECNICO destec ON (destec.seqDesembolso = des.seqDesembolso))
								INNER JOIN T_PRY_PROYECTO pry ON (und.seqProyecto = pry.seqProyecto))
								INNER JOIN T_PRY_TECNICO prytec ON (prytec.seqUnidadProyecto = und.seqUnidadProyecto)
								WHERE (ciu.numDocumento IN ($documento));";
                    global $db;

                    $resultados = $db->get_results($consulta_pre);

                    foreach ($resultados as $resultado) {
                        $numDocumento = $resultado->numDocumento;
                        $seqDesembolso = $resultado->seqDesembolso;
                        $seqTecnico = $resultado->seqTecnico;
                        $txtFormulario = $resultado->txtFormulario;
                        $seqFormulario = $resultado->seqFormulario;
                        $unidadseqUnidadProyecto = $resultado->unidadseqUnidadProyecto;
                        $seqParentesco = $resultado->seqParentesco;
                        $seqUnidadProyecto = $resultado->seqUnidadProyecto;
                        $txtNombreUnidad = $resultado->txtNombreUnidad;
                        $txtNombreProyecto = $resultado->txtNombreProyecto;
                    }
                    switch ($campo) {
                        case "numDocumento":
                            $valor = $numDocumento;
                            break;
                        case "seqDesembolso":
                            $valor = $seqDesembolso;
                            break;
                        case 'seqTecnico':
                            $valor = $seqTecnico;
                            break;
                        case 'txtFormulario':
                            $valor = $txtFormulario;
                            break;
                        case 'seqFormulario':
                            $valor = $seqFormulario;
                            break;
                        case 'unidadseqUnidadProyecto':
                            $valor = $unidadseqUnidadProyecto;
                            break;
                        case 'seqParentesco':
                            $valor = $seqParentesco;
                            break;
                        case 'seqUnidadProyecto':
                            $valor = $seqUnidadProyecto;
                            break;
                        case 'txtNombreUnidad':
                            $valor = $txtNombreUnidad;
                            break;
                        case 'txtNombreProyecto':
                            $valor = $txtNombreProyecto;
                            break;
                    }
                    return $valor;
                }

                if (validarEstudioTecnico()) {
                    $registros = 0;
                    $adjuntos = 0;
                    foreach ($arrDocumentosArchivo as $linea_doc => $documento) {
                        $seqDesembolso = obtenerValoresUtiles($documento, "seqDesembolso");
                        $seqUnidadProyecto = obtenerValoresUtiles($documento, "seqUnidadProyecto");

                        $sqlTecnico = "INSERT INTO T_DES_TECNICO (seqDesembolso, $camposTecnico)
									SELECT '$seqDesembolso', $camposTecnico
									FROM T_PRY_TECNICO
									WHERE seqTecnicoUnidad = $seqUnidadProyecto;";
                        if ($db->query($sqlTecnico)) {
                            $seqTecnico = $db->insert_id;

                            $sqlAdjuntosTecnicos = "INSERT INTO T_DES_ADJUNTOS_TECNICOS (seqTecnico, $camposAdjuntosTecnicos)
									SELECT '$seqTecnico', $camposAdjuntosTecnicos
									FROM T_PRY_ADJUNTOS_TECNICOS
									WHERE seqTecnicoUnidad = $seqUnidadProyecto;";
                            if (!$db->query($sqlAdjuntosTecnicos)) {
                                echo "<p class='alert alert-danger'>No se pudo insertar el registro, por favor contacte al administrador.</p>";
                            }
                        } else {
                            echo "<p class='alert alert-danger'>No se pudo insertar el registro, por favor contacte al administrador.</p>";
                        }
                        $registros++;
                    }

                    echo "<p class='alert alert-success'>Se han migrado $registros estudios t&eacute;cnicos</p>";
                    generarLinksImpresion($separado_por_comas);
                } else {
                    echo "<p class='alert alert-danger'>Ya existe un estudio t&eacute;cnico para el desembolso</p>";
                }
                ?>
                <p align="center"><a href="javascript:history.back(1)" class="btn btn-primary" role="button">Volver</a></p>
            </div>
        </div> <!-- /container -->
        <?php

        function generarLinksImpresion($arraydocs) {
            $db = new ezSQL_mysqli('sdht_usuario', 'Ochochar*1', 'sdht_subsidios', 'localhost');
            //$db = new ezSQL_mysqli('sdht_usuario', 'Ochochar*1', 'sdth_subsidiosentrega', 'localhost');          


            $consulta_pre = "SELECT ciu.numDocumento AS numDocumento,
       des.seqDesembolso AS seqDesembolso,
       destec.seqTecnico AS seqTecnico,
       frm.txtFormulario AS txtFormulario,
       frm.seqFormulario AS seqFormulario,
       und.seqUnidadProyecto AS unidadseqUnidadProyecto,
       hog.seqParentesco AS seqParentesco,
       prytec.seqUnidadProyecto seqUnidadProyecto,
       und.txtNombreUnidad txtNombreUnidad,
       pry.txtNombreProyecto AS txtNombreProyecto
  FROM ((((((sdht_subsidios.t_pry_unidad_proyecto und
             INNER JOIN sdht_subsidios.t_frm_formulario frm
                ON (und.seqFormulario = frm.seqFormulario))
            INNER JOIN sdht_subsidios.t_frm_hogar hog
               ON (hog.seqFormulario = frm.seqFormulario))
           INNER JOIN sdht_subsidios.t_ciu_ciudadano ciu
              ON (hog.seqCiudadano = ciu.seqCiudadano))
          LEFT OUTER JOIN sdht_subsidios.t_des_desembolso des
             ON (des.seqFormulario = frm.seqFormulario))
         LEFT OUTER JOIN sdht_subsidios.t_des_tecnico destec
            ON (destec.seqDesembolso = des.seqDesembolso))
        INNER JOIN sdht_subsidios.t_pry_proyecto pry
           ON (und.seqProyecto = pry.seqProyecto))
       INNER JOIN sdht_subsidios.t_pry_tecnico prytec
          ON (prytec.seqUnidadProyecto = und.seqUnidadProyecto)
 WHERE (ciu.numDocumento IN ($arraydocs));";
            ?>
            <div class="table-responsive">
                <table class="table">    
                    <tr>
                        <th>ITEM</th>     
                        <th>CC Ppal</th>    
                        <th>Proyecto</th>    
                        <th>Unidad</th>    
                        <th>Link_Impresion</th>
                    <tr>    
                        <?php
                        $resultados = $db->get_results($consulta_pre);
                        $item = 0;

                        foreach ($resultados as $resultado) {
                            $item++;
                            echo
                            '<tr>
            <td>' . $item . '</td>
            <td>' . $resultado->numDocumento . '</td>
            <td>' . $resultado->txtNombreProyecto . '</td>
            <td>' . $resultado->txtNombreUnidad . '</td>
            <td> <a href="http://'.$_SERVER['HTTP_HOST'].'/sdv/contenidos/desembolso/formatoRevisionTecnica.php?seqFormulario=' . $resultado->seqFormulario . '">http://'.$_SERVER['HTTP_HOST'].'/sdv/contenidos/desembolso/formatoRevisionTecnica.php?seqFormulario=' . $resultado->seqFormulario . '</a> </td>
        <tr>';
                        }
                        ?>
                </table>
            </div>
            <?php
        }
        ?>
    </body>
</html>