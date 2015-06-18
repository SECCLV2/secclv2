<?php

class ddlWidget extends Widget {

    private $_modelo;

    public function __construct()
    {
        $this->_modelo = $this->loadModel('ddl');
    }

    public function cargar($nombreCampo, $nombreDdl, $tabla, $campo, $value, $texto)
    {
        $datos['datos'] = $this->_modelo->cargar($tabla, $campo);
        $datos['nombreCampo'] = $nombreCampo;
        $datos['nombreDdl'] = $nombreDdl;
        $datos['value'] = $value;
        $datos['texto'] = $texto;
        return $this->render('ddl', $datos);
    }

}
