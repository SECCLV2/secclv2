<?php

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Creador: Oscar Fabian Forero Pineda.
 * Uso: Este controlador se encargara de gestionar el control sobre la vista del registro y actualización de emails de usuarios.
 * ---------------------------------------------------------------------------------------------------------------------
 */

class emailController extends usuariosController {

    public function __construct()
    {
        parent::__construct();
    }

    public function registrar()
    {
        if (!Session::get('logueado'))
        {
            $this->redireccionar();
        }

        $condicion = array('TIP_EMAIL_ESTADO' => '1');

        $this->_view->ddlTEmail = $this->_master->masterSelect('T_TIPS_EMAILS', $condicion);
        $this->_view->titulo = 'Registrar Usuario';
        $this->consultar();

        if ($this->getInt('enviar') == 1)
        {
            $this->_view->datos = $_POST;

            $parametros['ddlTEmail'] = array(
                'requerido' => true,
                'valCode' => array(
                    'V001',
                    'V108'
                ),
                'mensaje' => 'Tipo de Documento:'
            );

            $parametros['txtEmail'] = array(
                'requerido' => true,
                'valCode' => array(
                    'V001',
                    'V104',
                    'V201',
                    'V202',
                    'V301'
                ),
                'mensaje' => 'Correo Electronico:',
                'max' => 320,
                'min' => 5,
                'table' => 'T_EMAILS',
                'campo' => 'EMAIL_DIRECCION',
                'extra' => array(
                    'EMAIL_ESTADO' => '1'
                )
            );

            $val = $this->validar($parametros);

            if ($val != 1)
            {
                $this->_view->_error = $val;
                $this->_view->renderizar('email', 'emails');
                exit;
            }

            $transac = $this->_master->transac();
            if ($transac)
            {
                $campos['T_EMAILS'] = array(
                    'EMAIL_ID_USUARIO' => Session::get('id'),
                    'EMAIL_DIRECCION' => $this->getPostParam('txtEmail'),
                    'EMAIL_TIPO_EMAIL' => $this->getPostParam('ddlTEmail'),
                    'EMAIL_ESTADO' => '1'
                );
                $T_EMAIL = $this->_master->masterInsert(true, 'T_EMAILS', $campos['T_EMAILS'], 'EMAIL_ID');
            }
            else
            {
                throw new Exception('Error al crear la transacción');
            }

            if (!is_int($T_EMAIL))
            {
                $this->_view->error = 'Error durante el registro del email';
                $this->_view->renderizar('email', 'emails');
                exit;
            }

            $this->_view->datos = false;
            $this->_view->_mensaje = 'Registro Completado';
        }
        $this->consultar();
        $this->_view->renderizar('email', 'emails');
    }

    public function consultar()
    {
        $condicion = array(
            'EMAIL_ESTADO' => '1',
            'EMAIL_ID_USUARIO' => Session::get('id')
        );
        $this->_view->consEmails = $this->_master->masterSelect('T_EMAILS EM INNER JOIN T_TIPS_EMAILS TEM ON EM.EMAIL_TIPO_EMAIL = TEM.TIP_EMAIL_ID ', $condicion);
    }

    public function desactivar($id)
    {
        $id = (int) $id;
        if (!Session::get('logueado'))
        {
            $this->redireccionar();
        }

        if (!is_int($id))
        {
            $this->_view->_error = 'Error, en el envio de datos';
            $this->_view->renderizar('email', 'emails');
            exit;
        }

        $transac = $this->_master->transac();
        if ($transac)
        {
            $campos['T_EMAILS'] = array(
                'EMAIL_ESTADO' => 2
            );
            $condicion = array('EMAIL_ID' => $id);
            $T_EMAIL = $this->_master->masterUpdate(true, 'T_EMAILS', $campos['T_EMAILS'], $condicion, 'EMAIL_ID');
        }
        else
        {
            throw new Exception('Error al crear la transacción');
        }

        if (!is_int($T_EMAIL))
        {
            $this->_view->error = 'Error durante el registro del email';
            $this->_view->renderizar('email', 'emails');
            exit;
        }
        
        $this->_view->_mensaje = 'Actualización Completa';
        $this->registrar();
    }
}
