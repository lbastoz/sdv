<?php

include_once "../lib/mysqli/shared/ez_sql_core.php";
include_once "../lib/mysqli/ez_sql_mysqli.php";
//include_once "../generarExcel.php";

$observacion1 = 'PROPIETARIOS SON BENEFICIARIOS DEL SDV';
$observacion2 = 'ESTADO CIVIL COINCIDENTE';
$observacion3 = utf8_decode('CONSTITUCIÓN PATRIMONIO DE FAMILIA');
$observacion4 = 'RESTRICCIONES';
$observacion5 = 'PATRIMONIO DE FAMILIA REGISTRADO';
$observacion6 = utf8_decode('NOMBRE Y CÉDULA DE LOS PROPIETARIOS');
$observacion7 = 'COMPRAVENTA REALIZADA CON SDV';
$documentos1 = utf8_decode('ESCRITURA PÚBLICA');
$documentos2 = utf8_decode('FOLIO DE MATRÍCULA INMOBILIARIA');
$documentos3 = 'CERTIFICADO DE EXISTENCIA Y HABITABILIDAD VIABILIZADO';


$arrViabilizados = Array();
$arrNoViabilizados = Array();
$idHogar = "";

if (isset($_FILES["archivo"]) && is_uploaded_file($_FILES['archivo']['tmp_name'])) {
    $nombreArchivo = $_FILES['archivo']['tmp_name'];

    $lineas = file($nombreArchivo);
    //var_dump($lineas);    exit();
    $registros = 0;
    $db = new ezSQL_mysqli('sdht_usuario', 'Ochochar*1', 'sdht_subsidios', 'localhost');
    //$db = new ezSQL_mysqli('sdht_usuario', 'Ochochar*1', 'sdht_subsidios_feb10', 'localhost');
    $intV = 1;
    $intNV = 1;
    $band = 0;
    $cant = count($lineas);

    foreach ($lineas as $linea_num => $linea) {

        $datos = explode("\t", $linea);
        $casilla = "";

        $seqFormulario = trim($datos [0]);
        if ($registros < $cant - 1) {
            $idHogar .= $seqFormulario . ",";
        } else
            $idHogar .= $seqFormulario;

        //$seqDesembolso = obtenerDesembolso(trim($datos [0]));
        $numEscrituraIdentificacion = trim($datos [10]);
        $fchEscrituraIdentificacion = trim($datos [11]);
        $numNotariaIdentificacion = trim($datos [12]);
        $numEscrituraTitulo = trim($datos [10]);
        $fchEscrituraTitulo = trim($datos [11]);
        $numNotariaTitulo = trim($datos [12]);
        $numFolioMatricula = trim($datos [34]);
        $txtZonaMatricula = trim($datos [23]);
        $fchMatricula = trim($datos [25]);
        $bolSubsidioSDHT = 1;
        $bolSubsidioFonvivienda = 0;
        $numResolucionFonvivienda = 'null';
        $numAnoResolucionFonvivienda = 'null';
        $txtAprobo = trim($datos [40]);
        $fchCreacion = 'now()';
        $fchActualizacion = 'null';
        $txtCiudadTitulo = 'Bogota';
        $txtCiudadIdentificacion = 'Bogota';
        $txtCiudadMatricula = 'Bogota';
        $txtElaboro = trim($datos [39]);
        $txtConcepto = trim(str_replace('"', '', $datos [42]));
        $viabilizado = (trim($datos [41]) == 'SI') ? true : false;
        $numDocumento = trim($datos [1]);

        if ($seqFormulario == "" || $numEscrituraIdentificacion == "" || $numNotariaIdentificacion == "" || $numEscrituraTitulo == "" || trim($datos [41]) == "") {

            $casilla .= ($seqFormulario == '') ? "1," : '';
            $casilla .= ($numEscrituraIdentificacion == '') ? "11," : '';
            $casilla .= ($numNotariaIdentificacion == '') ? "13," : '';
            $casilla .= ($numEscrituraTitulo == '') ? "12," : '';
            $casilla .= (trim($datos [41]) == '') ? "42," : '';
            $band = 1;
        }
        if ($numNotariaTitulo == "" || $numFolioMatricula == "" || $txtZonaMatricula == "" || $txtAprobo == "" || $txtElaboro == "" || $txtConcepto == "") {
            $casilla .= ($numNotariaTitulo == '') ? "13," : '';
            $casilla .= ($numFolioMatricula == '') ? "35," : '';
            $casilla .= ($txtZonaMatricula == '') ? "24," : '';
            $casilla .= ($txtAprobo == '') ? "41," : '';
            $casilla .= ($txtElaboro == '') ? "40," : '';
            $txtConcepto = ($txtElaboro == '') ? "43," : '';
            $band = 1;
        }


        $CfchEscrituraIdentificacion = explode("/", $fchEscrituraIdentificacion);
        if ($CfchEscrituraIdentificacion[1] != "") {
            $fchEscrituraIdentificacion = $CfchEscrituraIdentificacion[2] . "-" . $CfchEscrituraIdentificacion[1] . "-" . $CfchEscrituraIdentificacion[0];
        }
        $CfchMatricula = explode("/", $fchMatricula);
        if ($CfchMatricula[1] != "") {
            $fchMatricula = $CfchMatricula[2] . "-" . $CfchMatricula[1] . "-" . $CfchMatricula[0];
        }
        if ($datos [27] == "" || $datos [32] == "" || $datos [29] == "" || $datos [31] == "" || $datos [36] == "" || $datos [28] == "") {
            $band = 1;
        }
        if ($datos [18] == "" || $datos [14] == "" || $datos [42] == "") {
            $band = 1;
        }
//        echo "<br>***" . $registros . " fchEscrituraIdentificacion -> " . $fchEscrituraIdentificacion . " fchMatricula-> " . $fchMatricula;
//        echo "<br>***" . $registros . " fchEscrituraIdentificacion -> " . strtotime($fchEscrituraIdentificacion) . " fchMatricula-> " . strtotime($fchMatricula);
        if (strtotime($fchEscrituraIdentificacion) == "" || strtotime($fchMatricula) == "") {
            $casilla .= (strtotime($fchEscrituraIdentificacion) == '') ? "12" : '';
            $casilla .= (strtotime($fchMatricula) == '') ? "26" : '';
            $band = 1;
        }

        if ($viabilizado && $band == 0) {
            if ($seqFormulario != "") {
                $arrViabilizados['seqFormulario'][$intV] = $seqFormulario;
                $arrViabilizados['numEscrituraIdentificacion'][$intV] = $numEscrituraIdentificacion;
                $arrViabilizados['fchEscrituraIdentificacion'][$intV] = $fchEscrituraIdentificacion;
                $arrViabilizados['numNotariaIdentificacion'][$intV] = $numNotariaIdentificacion;
                $arrViabilizados['numEscrituraTitulo'][$intV] = $numEscrituraTitulo;
                $arrViabilizados['fchEscrituraTitulo'][$intV] = $fchEscrituraTitulo;
                $arrViabilizados['numNotariaTitulo'][$intV] = $numNotariaTitulo;
                $arrViabilizados['numFolioMatricula'][$intV] = $numFolioMatricula;
                $arrViabilizados['txtZonaMatricula'][$intV] = $txtZonaMatricula;
                $arrViabilizados['fchMatricula'][$intV] = $fchMatricula;
                $arrViabilizados['bolSubsidioSDHT'][$intV] = $bolSubsidioSDHT;
                $arrViabilizados['bolSubsidioFonvivienda'][$intV] = $bolSubsidioFonvivienda;
                $arrViabilizados['numResolucionFonvivienda'][$intV] = $numResolucionFonvivienda;
                $arrViabilizados['numAnoResolucionFonvivienda'][$intV] = $numAnoResolucionFonvivienda;
                $arrViabilizados['txtAprobo'][$intV] = $txtAprobo;
                $arrViabilizados['fchCreacion'][$intV] = $fchCreacion;
                $arrViabilizados['fchActualizacion'][$intV] = $fchActualizacion;
                $arrViabilizados['txtCiudadTitulo'][$intV] = $txtCiudadTitulo;
                $arrViabilizados['txtCiudadIdentificacion'][$intV] = $txtCiudadIdentificacion;
                $arrViabilizados['txtCiudadMatricula'][$intV] = $txtCiudadMatricula;
                $arrViabilizados['txtElaboro'][$intV] = $txtElaboro;
                $arrViabilizados['numdocumento'][$intV] = $numDocumento;
                $arrViabilizados['beneficiarios'][$intV] = $observacion1;
                $arrViabilizados['estado'][$intV] = $observacion2;
                $arrViabilizados['constitucion'][$intV] = $observacion3;
                $arrViabilizados['resticciones'][$intV] = $observacion4;
                $arrViabilizados['patrimonio'][$intV] = $observacion5;
                $arrViabilizados['propietarios'][$intV] = $observacion6;
                $arrViabilizados['compraVenta'][$intV] = $observacion7;
                $arrViabilizados['noEscritura'][$intV] = $documentos1;
                $arrViabilizados['folio'][$intV] = $documentos2;
                $arrViabilizados['certificado'][$intV] = $documentos3;
                $arrViabilizados['observacion'][$intV] = utf8_decode($txtConcepto);

                $intV++;
            }
        } else if ($viabilizado == false) {
            if ($seqFormulario != "") {
                $arrNoViabilizados['seqFormulario'][$intNV] = $seqFormulario;
                $arrNoViabilizados['numEscrituraIdentificacion'][$intNV] = $numEscrituraIdentificacion;
                $arrNoViabilizados['fchEscrituraIdentificacion'][$intNV] = $fchEscrituraIdentificacion;
                $arrNoViabilizados['numNotariaIdentificacion'][$intNV] = $numNotariaIdentificacion;
                $arrNoViabilizados['numEscrituraTitulo'][$intNV] = $numEscrituraTitulo;
                $arrNoViabilizados['fchEscrituraTitulo'][$intNV] = $fchEscrituraTitulo;
                $arrNoViabilizados['numNotariaTitulo'][$intNV] = $numNotariaTitulo;
                $arrNoViabilizados['numFolioMatricula'][$intNV] = $numFolioMatricula;
                $arrNoViabilizados['txtZonaMatricula'][$intNV] = $txtZonaMatricula;
                $arrNoViabilizados['fchMatricula'][$intNV] = $fchMatricula;
                $arrNoViabilizados['bolSubsidioSDHT'][$intNV] = $bolSubsidioSDHT;
                $arrNoViabilizados['bolSubsidioFonvivienda'][$intNV] = $bolSubsidioFonvivienda;
                $arrNoViabilizados['numResolucionFonvivienda'][$intNV] = $numResolucionFonvivienda;
                $arrNoViabilizados['numAnoResolucionFonvivienda'][$intNV] = $numAnoResolucionFonvivienda;
                $arrNoViabilizados['txtAprobo'][$intNV] = $txtAprobo;
                $arrNoViabilizados['fchCreacion'][$intNV] = $fchCreacion;
                $arrNoViabilizados['fchActualizacion'][$intNV] = $fchActualizacion;
                $arrNoViabilizados['txtCiudadTitulo'][$intNV] = $txtCiudadTitulo;
                $arrNoViabilizados['txtCiudadIdentificacion'][$intNV] = $txtCiudadIdentificacion;
                $arrNoViabilizados['txtCiudadMatricula'][$intNV] = $txtCiudadMatricula;
                $arrNoViabilizados['txtElaboro'][$intNV] = $txtElaboro;
                $arrNoViabilizados['numdocumento'][$intNV] = $numDocumento;
                $arrNoViabilizados['beneficiarios'][$intNV] = (trim($datos [27]) == 'SI') ? $observacion1 : '';
                $arrNoViabilizados['estado'][$intNV] = (trim($datos [32]) == 'SI') ? $observacion2 : '';
                $arrNoViabilizados['constitucion'][$intNV] = (trim($datos [29]) == 'SI') ? $observacion3 : '';
                $arrNoViabilizados['resticciones'][$intNV] = (trim($datos [31]) == 'SI') ? $observacion4 : '';
                $arrNoViabilizados['patrimonio'][$intNV] = (trim($datos [36]) == 'SI') ? $observacion5 : '';
                $arrNoViabilizados['propietarios'][$intNV] = (trim($datos [28]) == 'SI') ? $observacion6 : '';
                $arrNoViabilizados['compraVenta'][$intNV] = $observacion7;
                $arrNoViabilizados['noEscritura'][$intNV] = (trim($datos [18]) == 'SI') ? $documentos1 : '';
                $arrNoViabilizados['folio'][$intNV] = (trim($datos [22]) == 'SI') ? $documentos2 : '';
                $arrNoViabilizados['certificado'][$intNV] = (trim($datos [9]) == 'SI') ? $documentos3 : '';
                $arrNoViabilizados['observacion'][$intNV] = ($txtConcepto);
                $intNV++;
            }
        } else if ($band == 1) {
            echo "Por favor verifique el registro # " . ($registros + 1) . " todos los campos en el hogar " . $seqFormulario . " en la casilla(s) # " . $casilla . " con valor vacio o el formato de fecha no es el indicado";
            exit();
        }

        $registros++;
    }
//echo "<br>".$idHogar;
    $arrSeqDesembolso = obtenerDesembolso($idHogar);

    asignarDesembolso($arrViabilizados, $arrSeqDesembolso, $intV, 1);
    //var_dump($arrNoViabilizados); exit();
    asignarDesembolso($arrNoViabilizados, $arrSeqDesembolso, $intNV, 2);
} else {
    echo "Error de subida";
}

function obtenerDesembolso($numFormulario) {
    $db = new ezSQL_mysqli('sdht_usuario', 'Ochochar*1', 'sdht_subsidios', 'localhost');
//$db = new ezSQL_mysqli('sdht_usuario', 'Ochochar*1', 'sdth_subsidiosentrega', 'localhost');
    $consulta = "
        SELECT seqFormulario, seqDesembolso
            FROM T_DES_DESEMBOLSO
        WHERE seqFormulario in ($numFormulario)";
    $resultado = $db->get_results($consulta);
    //var_dump($resultado);
    $dato = Array();
    $intD = 0;

    // var_dump($resultado);
    foreach ($resultado as $res) {
        $dato[$res->seqDesembolso] = $res->seqFormulario;
        $intD++;
    }

    return $dato;
}

function asignarDesembolso($arreglo, $desembolso, $cantidad, $tipo) {

    $int = 1;
    $cantF = count($arreglo['seqFormulario']);
    $idSeqDesembolso = "";
    foreach ($arreglo['seqFormulario'] as $key => $value) {
        $seqFormulario = $arreglo['seqFormulario'][$int];
        $seqDesembolso = array_search($seqFormulario, $desembolso);
        if ($seqDesembolso != "") {
            $arreglo['seqDesembolso'][$int] = $seqDesembolso;
            $idSeqDesembolso .= $seqDesembolso . ",";
        }
        // echo "<br>**".$arreglo['seqFormulario'][$int]."- ".$seqDesembolso;   
        $int++;
    }
    $idSeqDesembolso = substr_replace($idSeqDesembolso, '', -1, 1);
    // print_r($arreglo);
    verificarRegistrosExistentes($arreglo, $idSeqDesembolso, $cantF, $tipo);
}

function verificarRegistrosExistentes($arreglo, $idSeqDesembolso, $cantF, $tipo) {
    //$db = new ezSQL_mysqli('sdht_usuario', 'Ochochar*1', 'sdth_subsidiosentrega', 'localhost');
    $db = new ezSQL_mysqli('sdht_usuario', 'Ochochar*1', 'sdht_subsidios', 'localhost');
    $consulta = " SELECT seqDesembolso, seqEstudioTitulos FROM t_des_estudio_titulos WHERE seqDesembolso IN(" . $idSeqDesembolso . ")";
    $resultado = $db->get_results($consulta);
    $dato = Array();
    $intD = 1;
    if ($resultado) {
        foreach ($resultado as $res) {
            $dato[$intD] = $res->seqDesembolso;
            $intD++;
        }
    }

    insertarEstudiosTitulos($arreglo, $cantF, $tipo, $intD, $dato, $idSeqDesembolso);
}

function insertarEstudiosTitulos($arreglo, $cantF, $tipo, $intD, $dato, $idSeqDesembolso) {

    $campos = " INSERT INTO t_des_estudio_titulos(seqDesembolso,
                numEscrituraIdentificacion,
                fchEscrituraIdentificacion,
                numNotariaIdentificacion,
                numEscrituraTitulo,
                fchEscrituraTitulo,
                numNotariaTitulo,
                numFolioMatricula,
                txtZonaMatricula,
                fchMatricula,
                bolSubsidioSDHT,
                bolSubsidioFonvivienda,
                numResolucionFonvivienda,
                numAnoResolucionFonvivienda,
                txtAprobo,
                fchCreacion,
                fchActualizacion,
                txtCiudadTitulo,
                txtCiudadIdentificacion,
                txtCiudadMatricula,
                txtElaboro) VALUES ";

    $int = 1;
    $ex = 1;
    $ArrImpresion = Array();
    if (count($dato) == 0) {
        foreach ($arreglo['seqFormulario'] as $key => $value) {
            if ($arreglo['seqDesembolso'][$int] != "") {
                $valores .= "(
                       " . $arreglo['seqDesembolso'][$int] . ",
                       '" . $arreglo['numEscrituraIdentificacion'][$int] . "',
                       '" . $arreglo['fchEscrituraIdentificacion'][$int] . "',
                       '" . $arreglo['numNotariaIdentificacion'][$int] . "',
                       '" . $arreglo['numEscrituraTitulo'][$int] . "',
                       '" . $arreglo['fchEscrituraTitulo'][$int] . "',
                       '" . $arreglo['numNotariaTitulo'][$int] . "',
                       '" . $arreglo['numFolioMatricula'][$int] . "',
                       '" . $arreglo['txtZonaMatricula'][$int] . "',
                       '" . $arreglo['fchMatricula'][$int] . "',
                       '" . $arreglo['bolSubsidioSDHT'][$int] . "',
                       '" . $arreglo['bolSubsidioFonvivienda'][$int] . "',
                       '" . $arreglo['numResolucionFonvivienda'][$int] . "',
                       '" . $arreglo['numAnoResolucionFonvivienda'][$int] . "',
                       '" . $arreglo['txtAprobo'][$int] . "',
                       '" . $arreglo['fchCreacion'][$int] . "',
                       '" . $arreglo['fchActualizacion'][$int] . "',
                       '" . $arreglo['txtCiudadTitulo'][$int] . "',
                       '" . $arreglo['txtCiudadIdentificacion'][$int] . "',
                       '" . $arreglo['txtCiudadMatricula'][$int] . "',
                       '" . $arreglo['txtElaboro'][$int] . "'";

                $valores .= "),";
                $ArrImpresion['seqFormulario'][$int] = $value;
                $ArrImpresion['seqDesembolso'][$int] = $arreglo['seqDesembolso'][$int];
                $ArrImpresion['txtElaboro'][$int] = $arreglo['txtElaboro'][$int];
                $ArrImpresion['txtAprobo'][$int] = $arreglo['txtAprobo'][$int];
                $ArrImpresion['numdocumento'][$int] = $value;
                $ArrImpresion['numdocumento'][$int] = $value;
            }

            $int++;
        }
    } else {
        $existen = '';
        $existen1 = '';
        foreach ($arreglo['seqFormulario'] as $key => $value) {
            $seqDesembolso = array_search(trim($arreglo['seqDesembolso'][$int]), $dato);
            if ($seqDesembolso == "") {
                if ($arreglo['seqDesembolso'][$int] != "") {
                    $valores .= "(
                       " . $arreglo['seqDesembolso'][$int] . ",
                       '" . $arreglo['numEscrituraIdentificacion'][$int] . "',
                       '" . $arreglo['fchEscrituraIdentificacion'][$int] . "',
                       '" . $arreglo['numNotariaIdentificacion'][$int] . "',
                       '" . $arreglo['numEscrituraTitulo'][$int] . "',
                       '" . $arreglo['fchEscrituraTitulo'][$int] . "',
                       '" . $arreglo['numNotariaTitulo'][$int] . "',
                       '" . $arreglo['numFolioMatricula'][$int] . "',
                       '" . $arreglo['txtZonaMatricula'][$int] . "',
                       '" . $arreglo['fchMatricula'][$int] . "',
                       '" . $arreglo['bolSubsidioSDHT'][$int] . "',
                       '" . $arreglo['bolSubsidioFonvivienda'][$int] . "',
                       '" . $arreglo['numResolucionFonvivienda'][$int] . "',
                       '" . $arreglo['numAnoResolucionFonvivienda'][$int] . "',
                       '" . $arreglo['txtAprobo'][$int] . "',
                       '" . $arreglo['fchCreacion'][$int] . "',
                       '" . $arreglo['fchActualizacion'][$int] . "',
                       '" . $arreglo['txtCiudadTitulo'][$int] . "',
                       '" . $arreglo['txtCiudadIdentificacion'][$int] . "',
                       '" . $arreglo['txtCiudadMatricula'][$int] . "',
                       '" . $arreglo['txtElaboro'][$int] . "'";
                    $valores .= "),";
                    $ArrImpresion['seqFormulario'][$ex] = $value;
                    $ArrImpresion['seqDesembolso'][$ex] = $arreglo['seqDesembolso'][$int];
                    $ArrImpresion['txtElaboro'][$ex] = $arreglo['txtElaboro'][$int];
                    $ArrImpresion['txtAprobo'][$ex] = $arreglo['txtAprobo'][$int];
                    $ArrImpresion['numdocumento'][$ex] = $value;
                    $ArrImpresion['numdocumento'][$ex] = $value;
                    $ex++;
                }
            } else {
                $existen1 = $arreglo['seqFormulario'][$int] . ", ";
                $existen .= $existen1;
            }
           
            
            $int++;
        }
    }
    $db = new ezSQL_mysqli('sdht_usuario', 'Ochochar*1', 'sdht_subsidios', 'localhost');
    //$db = new ezSQL_mysqli('sdht_usuario', 'Ochochar*1', 'sdth_subsidiosentrega', 'localhost');
    //echo $valores;
    if ($valores != "") {
        $valores = substr_replace($valores, ';', -1, 1);
        $query = $campos . $valores;
        $result = $db->query($query);
    }

    //  echo "<br>*".$query ."<br>";
    if ($existen != "") {
        $cantidaE =($int) - $ex;
        // $existen = substr_replace(trim($existen), ';', -1, 1);
        echo "<p> Los formularios que se muestran a continuaci&oacute;n se encuentr&aacute;n previamente almacenados: <br><b> " . $existen . " </b><br> son en total <b>" . $cantidaE . " Registros </b> de un total de ".($int-1)." Registros </p>";
    }

//var_dump($dato);exit();
    if ($result > 0) {
        insertarAdjuntosTitulos($arreglo, $cantF, $tipo, $intD, $dato, $idSeqDesembolso, $ArrImpresion);
    }
}
function insertarAdjuntosTitulos($arreglo, $cantF, $tipo, $intD, $dato, $idSeqDesembolso, $ArrImpresion) {


    //$db = new ezSQL_mysqli('sdht_usuario', 'Ochochar*1', 'sdth_subsidiosentrega', 'localhost');
    $db = new ezSQL_mysqli('sdht_usuario', 'Ochochar*1', 'sdht_subsidios', 'localhost');
    $consulta = " SELECT seqDesembolso, seqEstudioTitulos FROM T_DES_ESTUDIO_TITULOS WHERE seqDesembolso IN(" . $idSeqDesembolso . ")";

    $resultado = $db->get_results($consulta);
    $dato = Array();
    $intD = 0;
    $seqEstudioTitulosSearch = "";
    if ($resultado) {
        foreach ($resultado as $res) {
            $dato[$res->seqEstudioTitulos] = $res->seqDesembolso;
            $seqEstudioTitulosSearch .= $res->seqEstudioTitulos . ",";
        }
    }

    $seqEstudioTitulosSearch = substr_replace($seqEstudioTitulosSearch, '', -1, 1);
    //$db = new ezSQL_mysqli('sdht_usuario', 'Ochochar*1', 'sdth_subsidiosentrega', 'localhost');
    $db = new ezSQL_mysqli('sdht_usuario', 'Ochochar*1', 'sdht_subsidios', 'localhost');
    $consulta = " SELECT seqAdjuntoTitulos, seqEstudioTitulos FROM t_des_adjuntos_titulos WHERE seqEstudioTitulos IN(" . $seqEstudioTitulosSearch . ")";
    $resultado1 = $db->get_results($consulta);
    $datoET = Array();
    $intD = 0;
    $seqEstudioTitulosSearch = "";
    if ($resultado1) {
        foreach ($resultado1 as $res1) {
            $datoET[$res1->seqAdjuntoTitulos] = $res1->seqEstudioTitulos;
            $seqAdjuntosTitulosSearch .= $res1->seqEstudioTitulos . ",";
        }
    }

    $txtqueryAdjuntos = "INSERT INTO t_des_adjuntos_titulos (seqTipoAdjunto ,seqEstudioTitulos ,txtAdjunto) VALUES";
    $valueObs1 = '';
    $valueObs2 = '';
    $valueObs3 = '';
    $int = 1;
    $existen = '';
    foreach ($arreglo['seqDesembolso'] as $key => $value) {
        $seqDesembolso = $arreglo['seqDesembolso'][$int];
        $seqEstudioTitulos = array_search($seqDesembolso, $dato);
        $seqAdjuntosTitulo = array_search($seqEstudioTitulos, $datoET);
        if ($seqAdjuntosTitulo == "") {
            if ($seqEstudioTitulos != "") {
                if ($arreglo['beneficiarios'][$int] != "") {
                    $valueObs1 .= "(4," . $seqEstudioTitulos . ",'" . $arreglo['beneficiarios'][$int] . "'),";
                }
                if ($arreglo['estado'][$int] != "") {
                    $valueObs1 .= "(4," . $seqEstudioTitulos . ",'" . $arreglo['estado'][$int] . "'),";
                }
                if ($arreglo['constitucion'][$int] != "") {
                    $valueObs1 .= "(4," . $seqEstudioTitulos . ",'" . $arreglo['constitucion'][$int] . "'),";
                }
                if ($arreglo['resticciones'][$int] != "") {
                    $valueObs1 .= "(4," . $seqEstudioTitulos . ",'" . $arreglo['resticciones'][$int] . "'),";
                }
                if ($arreglo['patrimonio'][$int] != "") {
                    $valueObs1 .= "(4," . $seqEstudioTitulos . ",'" . $arreglo['patrimonio'][$int] . "'),";
                }
                if ($arreglo['propietarios'][$int] != "") {
                    $valueObs1 .= "(4," . $seqEstudioTitulos . ",'" . $arreglo['propietarios'][$int] . "'),";
                }
                if ($arreglo['compraVenta'][$int] != "") {
                    $valueObs1 .= "(4," . $seqEstudioTitulos . ",'" . $arreglo['compraVenta'][$int] . "'),";
                }
                if ($arreglo['noEscritura'][$int] != "") {
                    $valueObs2 .= "(1," . $seqEstudioTitulos . ",'" . $arreglo['noEscritura'][$int] . "'),";
                }
                if ($arreglo['folio'][$int] != "") {
                    $valueObs2 .= "(1," . $seqEstudioTitulos . ",'" . $arreglo['folio'][$int] . "'),";
                }
                if ($arreglo['certificado'][$int] != "") {
                    $valueObs2 .= "(1," . $seqEstudioTitulos . ",'" . $arreglo['certificado'][$int] . "'),";
                }

                if ($arreglo['observacion'][$int] != "") {
                    $valueObs3 .= "(2," . $seqEstudioTitulos . ",'" . $arreglo['observacion'][$int] . "'),";
                }
            } else {
                $existen .= $arreglo['seqFormulario'][$int] . ", ";
            }
        }
        $int++;
    }

    if ($valueObs1 != "") {
        $insert1 = substr_replace($valueObs1, ';', -1, 1);
        $result1 = $db->query($txtqueryAdjuntos . "" . $insert1);
    }
    if ($valueObs2 != "") {
        $insert2 = substr_replace($valueObs2, ';', -1, 1);
        $result2 = $db->query($txtqueryAdjuntos . "" . $insert2);
    }

    if ($valueObs3 != "") {
        $insert3 = substr_replace($valueObs3, ';', -1, 1);
        $result3 = $db->query($txtqueryAdjuntos . "" . $insert3);
    }
    $totalReg = $result1 + $result1 + $result1;
    if ($existen != "") {
        echo " Los formularios que se muestran a continuación se encontraban previamente almacenados en los adjuntos titulos" . $existen;
    }
    generarLinks($ArrImpresion, $tipo);
}

function generarLinks($arreglo, $tipo) {
    $titulo = " Impresion ";
    if ($tipo == 1) {
        $titulo .=" Impresion Aprobados ";
    } else
        $titulo .=" Impresion NO Aprobados ";

    $tabla = "<p><table>";
    $tabla .= "<tr><td colspan='5'>" . $titulo . "</td></tr>";
    $tabla .= "<tr>";
    $tabla .= "<th>SegFormulario</th>";
    $tabla .= "<th>SeqDesembolso</th>";
    $tabla .= "<th>Elaboro</th>";
    $tabla .= "<th>Aprobo</th>";
    $tabla .= "<th>Link</th>";
    $tabla .= "</tr>";
    $int = 1;
    foreach ($arreglo['seqDesembolso'] as $key => $value) {
        $tabla .= "<tr>";
        $tabla .= "<td>" . $arreglo['seqFormulario'][$int] . "</td>";
        $tabla .= "<td>" . $arreglo['seqDesembolso'][$int] . "</td>";
        $tabla .= "<td>" . $arreglo['txtElaboro'][$int] . "</td>";
        $tabla .= "<td>" . $arreglo['txtAprobo'][$int] . "</td>";
        $tabla .= "<td><a href='http://www.habitatbogota.gov.co/sdv/contenidos/desembolso/formatoEstudioTitulos.php?seqFormulario=" . $arreglo['seqFormulario'][$int] . "' target='_blank'>http://www.habitatbogota.gov.co/sdv/contenidos/desembolso/formatoEstudioTitulos.php?seqFormulario=" . $arreglo['seqFormulario'][$int] . "</a></td>";
        $tabla .= "</tr>";
        $int++;
    }
    echo $tabla .= "</table></p>";
   // crearExcel();
}

?>