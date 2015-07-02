<?php

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Este controlador se encargara de gestionar el control sobre la vista del registro de nuevos usuarios.
 * ---------------------------------------------------------------------------------------------------------------------
 */

class registroController extends usuariosController {

    private $_registro;

    public function __construct()
    {
        parent::__construct();
        $this->_registro = $this->loadModel('registro', 'usuarios');
    }

    public function registrar()
    {
        if (Session::get('logueado'))
        {
            $this->redireccionar();
        }
        $this->_view->titulo = 'Registrar Usuario';

        $this->_view->ddlTDocumento = $this->_view->setWidget('ddl', 'cargar', array('Tipo de Documento', 'TDocumento', 'T_TIPOS_DOCS', 'TIP_DOCU_ID_ESTADO', 'TIP_DOCU_ID', 'TIP_DOCU_NOMBRE'));

        $this->_view->ddlPNacimiento = $this->_view->setWidget('ddl', 'cargar', array('Pais de Nacimiento', 'PNacimiento', 'T_PAISES', 'PAIS_ID_ESTADO', 'PAIS_ID', 'PAIS_NOMBRE'));

        $this->_view->ddlNacionalidad = $this->_view->setWidget('ddl', 'cargar', array('Nacionalidad', 'Nacionalidad', 'T_PAISES', 'PAIS_ID_ESTADO', 'PAIS_ID', 'PAIS_NOMBRE'));

        $this->_view->ddlGenero = $this->_view->setWidget('ddl', 'cargar', array('Género', 'Genero', 'T_GENEROS', 'GENERO_ID_ESTADO', 'GENERO_ID', 'GENERO_DESCRIPCION'));

        $this->_view->ddlGSanguineo = $this->_view->setWidget('ddl', 'cargar', array('Grupo Sanguineo', 'GSanguineo', 'T_TIPOS_SANGS', 'TIP_SAN_ID_ESTADO', 'TIP_SAN_ID', 'TIP_SAN_DESCRIPCION'));

        $this->_view->ddlECivil = $this->_view->setWidget('ddl', 'cargar', array('Estado Civil', 'ECivil', 'T_EST_CIVILES', 'EST_CIV_ID_ESTADO', 'EST_CIV_ID', 'EST_CIV_NOMBRE'));

        if ($this->getInt('enviar') == 1)
        {

            $this->_view->datos = $_POST;

            $parametros['ddlTDocumento'] = array(
                'requerido' => true,
                'valCode' => array(
                    'V001',
                    'V108'
                ),
                'mensaje' => 'Tipo de Documento:'
            );

            $parametros['txtDocumento'] = array(
                'requerido' => true,
                'valCode' => array(
                    'V001',
                    'V106',
                    'V201',
                    'V202'
                //'V301'
                ),
                'mensaje' => 'Documento:',
                'max' => 20,
                'min' => 6,
                'table' => 'T_DOCUMENTOS',
                'campo' => 'DOC_NUMERO_DOCUMENTO',
                'extra' => array(
                    'DOC_ID_TIPO_DOCUMENTO' => $this->getPostParam('ddlTDocumento'),
                    'DOC_ID_HIST_EST' => '1'
                )
            );

            $parametros['txtLExpedicion'] = array(
                'requerido' => true,
                'valCode' => array(
                    'V001',
                    'V102',
                    'V201',
                    'V202'
                ),
                'mensaje' => 'Lugar de Expedición:',
                'max' => 255,
                'min' => 2
            );

            $parametros['txtNombre'] = array(
                'requerido' => true,
                'valCode' => array(
                    'V001',
                    'V102',
                    'V201',
                    'V202'
                ),
                'mensaje' => 'Nombre:',
                'max' => 100,
                'min' => 2
            );

            $parametros['txtPApellido'] = array(
                'requerido' => true,
                'valCode' => array(
                    'V001',
                    'V102',
                    'V201',
                    'V202'
                ),
                'mensaje' => 'Primer Apellido:',
                'max' => 50,
                'min' => 2
            );

            $parametros['txtSApellido'] = array(
                'requerido' => false,
                'valCode' => array(
                    'V001',
                    'V102',
                    'V201',
                    'V202'
                ),
                'mensaje' => 'Segundo Apellido:',
                'max' => 50,
                'min' => 2
            );

            $parametros['txtEmail'] = array(
                'requerido' => true,
                'valCode' => array(
                    'V001',
                    'V104',
                    'V201',
                    'V202'
                //'V301'
                ),
                'mensaje' => 'Correo Electronico:',
                'max' => 320,
                'min' => 5,
                'table' => 'T_CORR_ELECS',
                'campo' => 'CORR_ELEC_DIRECCION',
                'extra' => array(
                    'CORR_ELEC_ID_HIST_EST' => '1'
                )
            );

            $parametros['ddlPNacimiento'] = array(
                'requerido' => true,
                'valCode' => array(
                    'V001',
                    'V108'
                ),
                'mensaje' => 'País de Nacimiento:'
            );

            $parametros['txtFNacimiento'] = array(
                'requerido' => true,
                'valCode' => array(
                    'V001',
                    'V107',
                    'V201',
                    'V202'
                ),
                'mensaje' => 'Fecha de Nacimiento:',
                'max' => 10,
                'min' => 10
            );

            $parametros['ddlNacionalidad'] = array(
                'requerido' => true,
                'valCode' => array(
                    'V001',
                    'V108'
                ),
                'mensaje' => 'Nacionalidad:'
            );

            $parametros['ddlGenero'] = array(
                'requerido' => true,
                'valCode' => array(
                    'V001',
                    'V108'
                ),
                'mensaje' => 'Genero:'
            );

            $parametros['ddlGSanguineo'] = array(
                'requerido' => true,
                'valCode' => array(
                    'V001',
                    'V108'
                ),
                'mensaje' => 'Grupo Sanguineo:'
            );

            $parametros['ddlECivil'] = array(
                'requerido' => true,
                'valCode' => array(
                    'V001',
                    'V108'
                ),
                'mensaje' => 'Estado Civil:'
            );

            $parametros['txtNombreUsu'] = array(
                'requerido' => true,
                'valCode' => array(
                    'V001',
                    'V105',
                    'V201',
                    'V202'
                //'V301'
                ),
                'mensaje' => 'Nombre de Usuario:',
                'max' => 20,
                'min' => 6,
                'table' => 'T_USUARIOS',
                'campo' => 'USU_NOMBRE_USU'
            );

            $parametros['txtClave'] = array(
                'requerido' => true,
                'valCode' => array(
                    'V001',
                    'V105',
                    'V201',
                    'V202'
                ),
                'mensaje' => 'Contraseña:',
                'max' => 20,
                'min' => 8
            );

            $parametros['txtConfirmar'] = array(
                'requerido' => true,
                'valCode' => array(
                    'V001',
                    'V105',
                    'V201',
                    'V202'
                ),
                'mensaje' => 'Confirmar Contraseña:',
                'max' => 20,
                'min' => 8
            );
            $val = $this->validar($parametros);

            if ($val != 1)
            {
                $this->_view->_error = $val;
                $this->_view->renderizar('registrar', 'registrarse');
                exit;
            }

            if ($this->getPostParam('txtClave') != $this->getPostParam('txtConfirmar'))
            {
                $this->_view->_error = 'Las contraseñas no coinciden';
                $this->_view->renderizar('registrar', 'registrarse');
                exit;
            }

            $this->getLibrary('phpMailer', 'class.phpmailer');
            $this->getLibrary('phpMailer', 'class.smtp');
            $mail = new PHPMailer();

            $campos['T_USUARIOS'] = array(
                $this->getPostParam('txtNombre'),
                $this->getPostParam('txtPApellido'),
                $this->getPostParam('txtSApellido'),
                $this->getPostParam('txtFNacimiento'),
                $this->getPostParam('ddlPNacimiento'),
                $this->getPostParam('ddlNacionalidad'),
                $this->getPostParam('ddlGenero'),
                $this->getPostParam('ddlGSanguineo'),
                $this->getPostParam('ddlECivil'),
                $this->getPostParam('txtNombreUsu'),
                Hash::getHash($this->getPostParam('txtClave'))
            );

            $campos['T_DOCUMENTOS'] = array(
                1 => $this->getPostParam('txtDocumento'),
                2 => $this->getPostParam('ddlTDocumento'),
                3 => $this->getPostParam('txtLExpedicion'),
                4 => '0',
            );

            $campos['T_CORR_ELECS'] = array(
                1 => $this->getPostParam('txtEmail'),
                2 => 1,
            );

            $campos['T_USUS_ROLES'] = array(
                1 => 3,
            );

            $T_USUARIOS = $this->_registro->registro($campos);


            if (!is_int($T_USUARIOS) || !is_int($T_DOCUMENTOS) || !is_int($T_CORR_ELECS) || !is_int($T_USUS_ROLES))
            {
                $this->_view->_error = 'Error al registrar el usuario';
                $this->_view->renderizar('registrar', 'registrarse');
                exit;
            }

            $mail->IsSMTP();
            $mail->CharSet = 'UTF-8';
            $mail->Host = 'ssl://smtp.gmail.com';
            $mail->Port = 465;
            $mail->SMTPAuth = true;
            $mail->Username = "david.a.dominguez.r@gmail.com";
            $mail->Password = "Dom_A1032467056";

            $mail->From = 'www.seccl.sena.edu.co';
            $mail->FromName = 'SECCLV2';
            $mail->Subject = 'Activación de cuenta de usuario';
            $mail->Body = '<p>Hola, para activar su cuenta haga click en el siguiente enlace:'
                    . '<br/><a href="' . BASE_URL
                    . Hash::urlEncrypt('usuarios/registro/activar/' . $T_USUARIOS) . '">Activar'
                    . '</a></p>';
            $mail->AltBody = 'Su servidor de correo no soporta html';
            $mail->AddAddress($this->getPostParam('txtEmail'));
            $mail->Send();

            $this->_view->datos = false;
            $this->_view->_mensaje = 'Registro Completado, revise su email para activar su cuenta';
        }

        $this->_view->renderizar('registrar', 'registrarse');
    }

    public function activar($id)
    {
        $id = (int) $id;
        if (!is_int($id))
        {
            $this->_view->_error = 'Error, esta cuenta no se encuentra registrada';
            $this->_view->renderizar('activar', 'registrarse');
            exit;
        }

        $condicion = array('USU_ID' => $id);
        $val = $this->_master->masterSelect('T_USUARIOS', $condicion);
        if ($val['numRows'] != 1)
        {
            $this->_view->_error = 'Error, esta cuenta no se encuentra registrada';
            $this->_view->renderizar('activar', 'registrarse');
            exit;
        }
        else if ($val['USU_EST_REG'][0] != 0)
        {
            $this->_view->_error = 'Esta cuenta ya se encuentra activada';
            $this->_view->renderizar('activar', 'registrarse');
            exit;
        }

        $transac = $this->_master->transac();
        if ($transac)
        {
            $campos['T_USUARIOS'] = array(
                'USU_EST_REG' => 1
            );
            $condicion = array('USU_ID' => $id);
            $T_USUARIOS = $this->_master->masterUpdate(true, 'T_USUARIOS', $campos['T_USUARIOS'], $condicion, 'USU_ID');
        }
        else
        {
            throw new Exception('Error al crear la transacción');
        }

        $condicion = array('USU_ID' => $T_USUARIOS);
        $val = $this->_master->masterSelect('T_USUARIOS', $condicion);
        if ($val['numRows'] == 1 && $val['USU_EST_REG'][0] == 0)
        {
            $this->_view->_error = 'Error al activar la cuenta, por favor intentelo mas tarde';
            $this->_view->renderizar('activar', 'registrarse');
            exit;
        }

        $this->_view->_mensaje = 'Su cuenta ha sido activada';
        $this->_view->renderizar('activar', 'registrarse');
    }

}
