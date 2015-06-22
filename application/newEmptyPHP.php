<?php
session_start();

if ($_SESSION['logged'] == 'yes')
{
    $nom = $_SESSION["NOMBRE"];
    $ape = $_SESSION["PRIMER_APELLIDO"];
    $id = $_SESSION["USUARIO_ID"];
}
else
{
    echo '<script>window.location = "../index.php"</script>';
}

include("../Clase/conectar.php");
$connection = conectar($bd_host, $bd_usuario, $bd_pwd);

$idca = $_POST['idca'];
$norma = $_POST['norma'];
$proyecto = $_POST['proyecto'];
$plan = $_POST['plan'];
$nivel = $_POST['nivel'];


$queryEvidencias = ("SELECT estado FROM evidencias_candidato WHERE id_plan='$plan' and id_norma='$norma' and id_candidato='$idca'");
$statementEvidencias = oci_parse($connection, $queryEvidencias);
$respEvidencias = oci_execute($statementEvidencias);
$estado = oci_fetch_array($statementEvidencias, OCI_BOTH);

//----

$queryCertificacion = ("SELECT count(*) FROM certificacion WHERE id_candidato='$idca' and id_norma='$norma'");
$statementCertificacion = oci_parse($connection, $queryCertificacion);
$respCertificado = oci_execute($statementCertificacion);
$certificado = oci_fetch_array($statementCertificacion, OCI_BOTH);



if (($estado['ESTADO'] == 1 || $estado['ESTADO'] == 3 || $estado['ESTADO'] == 4) && $certificado['COUNT(*)'] == 0)
{
//actualizo el estado del instrumento
    $strSQL1 = "INSERT INTO CERTIFICACION
        (
        ID_CANDIDATO,
        ID_NORMA,
        ID_PROYECTO,
        NIVEL)
        VALUES 
        ('$idca','$norma','$proyecto','$nivel') returning id_certificacion into :id ";

    $objParse1 = oci_parse($connection, $strSQL1);
    OCIBindByName($objParse1, ":id", $id_certificacion, 32);
    $objExecute1 = oci_execute($objParse1);
    if ($objExecute1)
    {
        oci_commit($connection); //*** Commit Transaction ***//
    }
    else
    {
        oci_rollback($connection); //*** RollBack Transaction ***//
        $e = oci_error($objParse1);
        echo "Error Save [" . $e['message'] . "]";
    }

//    $queryDatosCertificacion = ("SELECT 
//UPPER(U.NOMBRE || ' ' || U.PRIMER_APELLIDO || ' ' || U.SEGUNDO_APELLIDO) NOMBRE_APRENDIZ,
//TD.DESCRIPCION DESC_TIPO_IDENT,
//U.DOCUMENTO NRO_IDENT,
//'Certificado Competencia Laboral' TITULO_OBTENIDO,
//UPPER(TN.NIVEL  || ' - ' ||  N.TITULO_NORMA) PROGRAMA,  
//'0' DURACION,
//M.NOMBRE_MUNICIPIO CIUDAD,
//FECHALARGOTA (CF.FECHA_REGISTRO) FECHA_EXPEDICION,
//UPPER(SD.NOMBRE) NOMBRE_FIRMA,
//SD.CARGO CARGO,
//C.NOMBRE_CENTRO CENTRO,
//R.NOMBRE_REGIONAL REGIONAL,
//CF.FECHA_REGISTRO FECHA_CERTIFICACION,
//N.CODIGO_NORMA CLCODIGO,
//N.VERSION_NORMA CLNIVEL,
//(SELECT VIGENCIANORMA(CF.FECHA_REGISTRO) FROM DUAL) CLVIGENCIA,
//TD.TIPO_IDENT TIPO_IDENT,
//CONCAT(C.CODIGO_CENTRO,'00') CENTRO_ID_CENTRO,
//--CF.ID_PROYECTO NRO_ORDEN,
//U.DOCUMENTO NRO_ID,
//'C' TIPO_ARCHIVO,
//'CE-12A' PLANTILLA,
//U.EMAIL EMAIL,
//'0' ESTADO_FIRMA,
//'12' SALIDA_TIPO,
//'NC' TIPO_CERTIFICADO,
//N.CODIGO_NORMA SALIDA_CODIGO,
//N.VERSION_NORMA SALIDA_VERSION,
//C.CODIGO_REGIONAL CENTRO_REGIONAL_ID_REGIONAL,
//TO_CHAR(CF.FECHA_REGISTRO, 'DD/MM/YYYY') FECHA,
//'1500' MRCODDOCUMENTO,
//'SECCL' SISTEMA,
//U.USUARIO_ID NIS,
//'0' TIPO_NOVEDAD,
//U.DOCUMENTO NRO_IDENT_C,
//(select TO_DATE(CF.FECHA_REGISTRO,'dd/mm/YY') + interval '3' year from dual) CLVIGENCIA_DATE
//FROM CERTIFICACION CF
//INNER JOIN USUARIO U
//ON U.USUARIO_ID=CF.ID_CANDIDATO
//INNER JOIN NORMA N
//ON N.ID_NORMA=CF.ID_NORMA
//INNER JOIN PROYECTO PR
//ON CF.ID_PROYECTO=PR.ID_PROYECTO
//INNER JOIN CENTRO C
//ON C.CODIGO_CENTRO=PR.ID_CENTRO
//INNER JOIN REGIONAL R
//ON PR.ID_REGIONAL=R.CODIGO_REGIONAL
//INNER JOIN MUNICIPIO M
//ON M.ID_MUNICIPIO=C.ID_MUNICIPIO AND M.ID_DEPARTAMENTO=C.CODIGO_REGIONAL
//INNER JOIN DIRECTORIO SD
//ON SD.CODIGO_CENTRO=C.CODIGO_CENTRO
//INNER JOIN TIPO_DOC TD
//ON TD.ID_TIPO_DOC = U.TIPO_DOC
//INNER JOIN T_NIVELES_CERTIFICACION TN
//ON TN.ID_NIVELES_CERTIFICACION=CF.NIVEL
//WHERE SD.T_USUARIO=3 AND SD.PERIODO=2015 AND CF.ID_CERTIFICACION = $id_certificacion");
//    $statementDatosCertificacion = oci_parse($connection, $queryDatosCertificacion);
//    $respDatosCertificacion = oci_execute($statementDatosCertificacion);
//    $DatosCertificacion = oci_fetch_array($statementDatosCertificacion, OCI_BOTH);


    $insertFirma = "Insert into CE_FIRMA_DIGITAL (NOMBRE_APRENDIZ,DESC_TIPO_IDENT,NRO_IDENT,TITULO_OBTENIDO,PROGRAMA,DURACION,CIUDAD,FECHA_EXPEDICION,NOMBRE_FIRMA,CARGO,CENTRO,REGIONAL,FECHA_CERTIFICACION,CLCODIGO,CLNIVEL,CLVIGENCIA,TIPO_IDENT,CENTRO_ID_CENTRO,NRO_ID,TIPO_ARCHIVO,PLANTILLA,EMAIL,ESTADO_FIRMA,SALIDA_TIPO,TIPO_CERTIFICADO,SALIDA_CODIGO,SALIDA_VERSION,CENTRO_REGIONAL_ID_REGIONAL,FECHA,MRCODDOCUMENTO,SISTEMA,NIS,TIPO_NOVEDAD,NRO_IDENT_C,CLVIGENCIA_DATE) "
            . "SELECT 
UPPER(U.NOMBRE || ' ' || U.PRIMER_APELLIDO || ' ' || U.SEGUNDO_APELLIDO) NOMBRE_APRENDIZ,
TD.DESCRIPCION DESC_TIPO_IDENT,
U.DOCUMENTO NRO_IDENT,
'Certificado Competencia Laboral' TITULO_OBTENIDO,
UPPER(TN.NIVEL  || ' - ' ||  N.TITULO_NORMA) PROGRAMA,  
'0' DURACION,
M.NOMBRE_MUNICIPIO CIUDAD,
FECHALARGOTA (CF.FECHA_REGISTRO) FECHA_EXPEDICION,
UPPER(SD.NOMBRE) NOMBRE_FIRMA,
SD.CARGO CARGO,
C.NOMBRE_CENTRO CENTRO,
R.NOMBRE_REGIONAL REGIONAL,
CF.FECHA_REGISTRO FECHA_CERTIFICACION,
N.CODIGO_NORMA CLCODIGO,
N.VERSION_NORMA CLNIVEL,
(SELECT VIGENCIANORMA(CF.FECHA_REGISTRO) FROM DUAL) CLVIGENCIA,
TD.TIPO_IDENT TIPO_IDENT,
CONCAT(C.CODIGO_CENTRO,'00') CENTRO_ID_CENTRO,
--CF.ID_PROYECTO NRO_ORDEN,
U.DOCUMENTO NRO_ID,
'C' TIPO_ARCHIVO,
'CE-12A' PLANTILLA,
U.EMAIL EMAIL,
'0' ESTADO_FIRMA,
'12' SALIDA_TIPO,
'NC' TIPO_CERTIFICADO,
N.CODIGO_NORMA SALIDA_CODIGO,
N.VERSION_NORMA SALIDA_VERSION,
C.CODIGO_REGIONAL CENTRO_REGIONAL_ID_REGIONAL,
TO_CHAR(CF.FECHA_REGISTRO, 'DD/MM/YYYY') FECHA,
'1500' MRCODDOCUMENTO,
'SECCL' SISTEMA,
U.USUARIO_ID NIS,
'0' TIPO_NOVEDAD,
U.DOCUMENTO NRO_IDENT_C,
(select TO_DATE(CF.FECHA_REGISTRO,'dd/mm/YY') + interval '3' year from dual) CLVIGENCIA_DATE
FROM CERTIFICACION CF
INNER JOIN USUARIO U
ON U.USUARIO_ID=CF.ID_CANDIDATO
INNER JOIN NORMA N
ON N.ID_NORMA=CF.ID_NORMA
INNER JOIN PROYECTO PR
ON CF.ID_PROYECTO=PR.ID_PROYECTO
INNER JOIN CENTRO C
ON C.CODIGO_CENTRO=PR.ID_CENTRO
INNER JOIN REGIONAL R
ON PR.ID_REGIONAL=R.CODIGO_REGIONAL
INNER JOIN MUNICIPIO M
ON M.ID_MUNICIPIO=C.ID_MUNICIPIO AND M.ID_DEPARTAMENTO=C.CODIGO_REGIONAL
INNER JOIN DIRECTORIO SD
ON SD.CODIGO_CENTRO=C.CODIGO_CENTRO
INNER JOIN TIPO_DOC TD
ON TD.ID_TIPO_DOC = U.TIPO_DOC
INNER JOIN T_NIVELES_CERTIFICACION TN
ON TN.ID_NIVELES_CERTIFICACION=CF.NIVEL
WHERE SD.T_USUARIO=3 AND SD.PERIODO=2015 AND CF.ID_CERTIFICACION = $id_certificacion";
}

$objParse2 = oci_parse($connection, $insertFirma);
$objExecute2 = oci_execute($objParse2);
if ($objExecute2)
{
    oci_commit($connection); //*** Commit Transaction ***//
}
else
{
    oci_rollback($connection); //*** RollBack Transaction ***//
    $e = oci_error($objExecute2);
    echo "Error Save [" . $e['message'] . "]";
}

oci_close($connection);


echo("<SCRIPT>window.alert(\"Certificado Generado\")</SCRIPT>");
?>
<script type="text/javascript">
    window.location = "../Presentacion/listar_certificacion.php?idplan=<?php echo $plan ?>";
</script>