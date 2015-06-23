<?php

/*
 * -----------------------------------------------------------------------------
 * Creador: Liliana Galeano Cruz.
 * Uso: Este modelo se encargara de realizar el registro de los usuarios.
 * -----------------------------------------------------------------------------
 */

class registroModel extends Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function registro($valores)
    {
        $transac = $this->_registro->transac();
        if ($transac)
        {
            $resp['insHisUsu'] = $this->histEstInsert(1, 3, 'INSERT - Registrar nuevo usuario');
            $tablas = 'T_USUARIOS';
            array_push($valores['T_USUARIOS'], $resp['insHisUsu']);
            $resp['insertUsu'] = $this->masterInsert($tablas, $valores['T_USUARIOS'], 'USU_ID');
            $resp['updateHisUsu'] = $this->histEstUpdate($resp['insHisUsu'], $resp['insertUsu']);
            
            $resp['insHisDoc'] = $this->histEstInsert(7, 3, 'INSERT - Registrar nuevo documento para el usuario' . $resp['insertUsu']);
            $tablas = 'T_DOCUMENTOS';
            $valores['T_DOCUMENTOS'][0] = $resp['insertUsu'];
            array_push($valores['T_DOCUMENTOS'],$resp['insHisDoc']);
            $resp['insertDoc'] = $this->masterInsert($tablas, $valores['T_DOCUMENTOS'], 'DOC_ID');
            $resp['updateHisDoc'] = $this->histEstUpdate($resp['insHisDoc'], $resp['insertDoc']);
            
            $resp['insHisCorreo'] = $this->histEstInsert(8, 3, 'INSERT - Registrar nuevo correo electronico para el usuario' . $resp['insertUsu']);
            $tablas = 'T_CORR_ELECS';
            $valores['T_CORR_ELECS'][0] = $resp['insertUsu'];
            array_push($valores['T_CORR_ELECS'], $resp['insHisCorreo']);
            $resp['insertCorreo'] = $this->masterInsert($tablas, $valores['T_CORR_ELECS'], 'CORR_ELEC_ID');
            $resp['updateHisCorreo'] = $this->histEstUpdate($resp['insHisCorreo'], $resp['insertCorreo']);
            
            $resp['insHisUsusR'] = $this->histEstInsert(4, 3, 'INSERT - Registrar nueva cuenta para el usuario' . $resp['insertUsu']);
            $tablas = 'T_USUS_ROLES';
            $valores['T_USUS_ROLES'][0] = $resp['insertUsu'];
            array_push($valores['T_USUS_ROLES'], $resp['insHisUsusR']);
            $resp['insertUsusR'] = $this->masterInsert($tablas, $valores['T_USUS_ROLES'], 'USU_ROL_ID');
            $resp['updateHisUsusR'] = $this->histEstUpdate($resp['insHisUsusR'], $resp['insertUsusR']);
        }
        else
        {
            throw new Exception('Error al crear la transacción');
        }


        return $resp;
    }

}