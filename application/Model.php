<?php

/*
 * ------------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.									|
 * Uso: Esta clase gestionara la conexión a la base de datos (BD) y la ejecución|
 * de consultas en la misma, extiende de la clase PDO de php para utilizar sus	|
 * funciones de conección a la BD y realizar consultas.							|
 * ------------------------------------------------------------------------------
 */

abstract class Model extends PDO {

    /*
     * Constructor que crea la conexión a la BD y crea los objetos de las calses de PDO para ser usados.
     * este constructor llama al contructor de la padre clase PDO el cual se sencarga de gestionar la conexión a la BD
     * y se implementan las constantes de configuración creadas para la BD.
     */

    public function __construct()
    {
        parent::__construct("oci:dbname=" . DB_HOST . ';' . DB_CHAR, DB_NAME, DB_PASS);
    }

    protected function transac()
    {
        $this->setAttribute(PDO::ATTR_AUTOCOMMIT, FALSE);
        return $this->beginTransaction();
    }

    protected function exeCommit()
    {
        parent::commit();
    }

    protected function exeRollback()
    {
        parent::rollBack();
    }

    protected function masterSelect(array $select)
    {
        if (PRUEBAS_SELECT)
        {
            echo '<pre>';
            echo 'Master Select: <br/><hr/>';
            echo 'Parametro Select: <br/>';
            print_r($select);
            echo '</pre>';
        }

        extract($select);
        unset($select);
        if (!isset($campos) || !is_string($campos) || empty($campos))
            return array('campos' => false);

        if (!isset($tablas) || !is_string($tablas) || empty($tablas))
            return array('tablas' => false);

        if (isset($condiciones) && (!is_string($condiciones) || empty($condiciones)))
            return array('condiciones' => false);
        else if (!isset($condiciones))
            $condiciones = '';

        if (isset($extra) && (!is_string($extra) || empty($extra)))
            return array('extra' => false);
        else
            $extra = '';

        $select = parent::prepare("SELECT $campos "
                        . "FROM $tablas "
                        . "$condiciones "
                        . "$extra");

        if (PRUEBAS_SELECT)
        {
            echo '<pre>';
            echo 'Master Select: <br/><hr/>';
            echo 'Prepare Select: <br/>';
            print_r($select);
            echo '</pre>';
        }

        return $this->queryGet($select);
    }

    protected function masterInsert($table, array $valores, $id)
    {
        if (!is_string($table) || empty($table))
            return array('table' => false);
        if (!is_string($id) || empty($id))
            return array('id' => false);

        $select = array(
            'campos' => 'ACC.COLUMN_NAME',
            'tablas' => 'ALL_COL_COMMENTS  ACC',
            'condiciones' => "WHERE ACC.TABLE_NAME = '$table' AND ACC.COMMENTS NOT LIKE '%(Trigger)%'"
        );

        $resp = $this->masterSelect($select);

        if (PRUEBAS_INSERT)
        {
            echo '<pre>';
            echo 'Master Insert: <br/><hr/>';
            echo 'Resp Select: <br/>';
            print_r($resp);
            echo '</pre>';
        }

        if (count($valores) != $resp['numRows'])
            return array('valores' => false);

        for ($i = 0; $i < $resp['numRows']; $i++)
        {
            $campos .= $resp['COLUMN_NAME'][$i] . ',';
            $valor .= "'$valores[$i]',";
        }
        $campos = substr($campos, 0, -1);
        $valor = substr($valor, 0, -1);

        $insert = parent::prepare("INSERT INTO $table($campos)"
                        . "VALUES ($valor)returning $id into :id");

        return $this->querySet($insert);
    }

    protected function masterUpdate(array $update, $id)
    {
        if (PRUEBAS_UPDATE)
        {
            echo '<pre>';
            echo 'Master Update: <br/><hr/>';
            echo 'Parametro Update: <br/>';
            print_r($update);
            echo '</pre>';
        }

        extract($update);
        unset($update);

        if (!isset($valores) || !is_string($valores) || empty($valores))
            return array('valores' => false);
        if (!isset($tablas) || !is_string($tablas) || empty($tablas))
            return array('tablas' => false);
        if (isset($condiciones) && (!is_string($condiciones) || empty($condiciones)))
            return array('condiciones' => false);
        if (!is_string($id) || empty($id))
            return array('id' => false);

        $update = parent::prepare("UPDATE $tablas "
                        . "SET $valores "
                        . "$condiciones "
                        . "returning $id into :id");

        return $this->querySet($sql);
    }

    /*
     * Función que organiza el resultado de una consulta en un arreglo de dos dimención con la estructura 
     * $array['Nombre_Campo']['Registro_Tabla'].
     * Recibe como parametro el arreglo a organizar, este arreglo debe estar en el formato
     */

    private function organizar($array)
    {
        $rows = array();

        for ($i = 0; $i < count($array); $i++)
        {
            foreach ($array[$i] as $key => $value)
            {
                $rows[$key][$i] = $value;
            }
        }

        $rows = array_filter($rows, function($key) {
            if (!is_int($key))
                return $key;
        }, ARRAY_FILTER_USE_KEY);

        return $rows;
    }

    /*
     * Función que ejecuta una consulta de tipo SELECT y retorna organizado el resultado.
     */

    private function queryGet($statement)
    {
        if ($statement)
        {
            $resp = $statement->execute();
            $rows = $statement->fetchAll();

            if ($resp != 1)
            {
                if (PRUEBAS_QUERYGET)
                    throw new PDOException();
            }
            else if (count($rows) > 0)
            {
                $numRows = count($rows);
                $rows = $this->organizar($rows);
                $rows['numRows'] = $numRows;
            }
            else
            {
                $rows['numRows'] = 0;
            }

            return $rows;
        }
        else
        {
            if (PRUEBAS_QUERYGET)
                throw new PDOException();
        }
    }

    /*
     * Función que ejecuta una consulta de tipo INSERT, UPDATE, DELETE y retorna el id de los registros afectados.
     */

    private function querySet($statement)
    {
        if ($statement)
        {
            $statement->bindParam(':id', $id, PDO::PARAM_INT, 8);
            $resp = $statement->execute();
            if (!$resp)
            {
                parent::rollBack();
                if (PRUEBAS_QUERYSET)
                    throw new PDOException();
            }
            else
            {
                $datos = (int) $id;
            }

            return $datos;
        }
        else
        {
            parent::rollBack();
            if (PRUEBAS_QUERYSET)
                throw new PDOException();
        }
    }

    public function histEstInsert($idTabla, $estado, $descripcion, $usuario = false)
    {
        if (!is_int($idTabla) || empty($idTabla))
            return array('idTabla' => false);
        if (!is_int($estado) || empty($estado))
            return array('estado' => false);
        if (!is_string($descripcion) || empty($descripcion))
            return array('descripcion' => false);
        if ((!$usuario && $usuario != 0) || !is_int($usuario) || empty($usuario))
            return array('usuario' => false);

        $valores = array(
            ($usuario) ? Session::get('id') : $usuario,
            $estado,
            $descripcion,
            $idTabla,
            0
        );
        $resp = $this->masterInsert('T_HIST_ESTS', $valores, $id);

        if (PRUEBAS_HIST_EST)
        {
            echo '<pre>';
            echo 'Historico Estados Insert: <br/><hr/>';
            echo 'Resp: <br/>';
            print_r($resp);
            echo '</pre>';
        }

        return $resp;
    }

    /*
     * Recibe el id($id) del registro y el id de la tabla T_HIST_ESTS ($idReg)
     */

    public function histEstUpdate($id, $idReg)
    {
        if (!is_int($id) || empty($id))
            return array('id' => false);
        if (!is_int($idReg) || empty($idReg))
            return array('idReg' => false);

        $update = array(
            'tablas' => 'T_HIST_ESTS',
            'valores' => "HIST_EST_ID_REGISTRO = $id",
            'condiciones' => "HIST_EST_ID = $idReg"
        );
        $resp = $this->masterUpdate($update, 'HIST_EST_ID');

        if (PRUEBAS_HIST_EST)
        {
            echo '<pre>';
            echo 'Historico Estados Update: <br/><hr/>';
            echo 'Resp: <br/>';
            print_r($resp);
            echo '</pre>';
        }
    }

}
