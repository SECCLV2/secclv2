<?php

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Este controlador se encargara de gestionar el control sobre la vista del registro de nuevos usuarios.
 * ---------------------------------------------------------------------------------------------------------------------
 */

class registroController extends usuariosController {

    public function __construct()
    {
        parent::__construct();
    }

    public function registrar()
    {
        if (Session::get('logueado'))
        {
            $this->redireccionar();
        }
        $this->_view->titulo = 'Registrar Usuario';

        $this->_view->ddlTDocumento = $this->_view->setWidget('ddl', 'cargar', array('Tipo de Documento', 'TDocumento', 'T_TIPOS_DOCS', 'TIP_DOCU_ID_ESTADO'));

        $this->_view->ddlPNacimiento = $this->_view->setWidget('ddl', 'cargar', array('Pais de Nacimiento', 'PNacimiento', 'T_PAISES', 'PAIS_ID_ESTADO'));
//        $condicion = array('TIP_DOCU_ESTADO' => '1');
//        $this->_view->ddlTDocumento = $this->_master->masterSelect('*', 'T_TIPS_DOCS', $condicion);
//
//		$condicion = array('PAIS_ESTADO' => '1');
//        $this->_view->ddlPais = $this->_master->masterSelect('*','T_PAISES', $condicion);
//
//        $condicion = array('GENERO_ESTADO' => '1');
//        $this->_view->ddlGenero = $this->_master->masterSelect('*','T_GENEROS', $condicion);
//
//        $condicion = array('TIP_SAN_ESTADO' => '1');
//        $this->_view->ddlGSanguineo = $this->_master->masterSelect('*','T_TIPS_SANGS', $condicion);
//
//        $condicion = array('EST_CIV_ESTADO' => '1');
//        $this->_view->ddlECivil = $this->_master->masterSelect('*','T_EST_CIVILES', $condicion);


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
                    'V202',
                    'V301'
                ),
                'mensaje' => 'Documento:',
                'max' => 20,
                'min' => 6,
                'table' => 'T_DOCUMENTS',
                'campo' => 'DOC_NUMERO_DOCUMENTO',
                'extra' => array(
                    'DOC_TIPO_DOCUMENTO' => $this->getPostParam('ddlTDocumento'),
                    'DOC_EST_REG' => '1'
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
                    'V202',
                    'V301'
                ),
                'mensaje' => 'Correo Electronico:',
                'max' => 320,
                'min' => 5,
                'table' => 'T_EMAILS',
                'campo' => 'EMAIL_DIRECCION',
                'extra' => array(
                    'EMAIL_EST_REG' => '1'
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

            $parametros['txtNickName'] = array(
                'requerido' => true,
                'valCode' => array(
                    'V001',
                    'V105',
                    'V201',
                    'V202',
                    'V301'
                ),
                'mensaje' => 'Nombre de Usuario:',
                'max' => 20,
                'min' => 6,
                'table' => 'T_USUARIOS',
                'campo' => 'USU_NICK_NAME'
            );

            $parametros['txtPassword'] = array(
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


            if ($this->getPostParam('txtPassword') != $this->getPostParam('txtConfirmar'))
            {
                $this->_view->_error = 'Las contraseñas no coinciden';
                $this->_view->renderizar('registrar', 'registrarse');
                exit;
            }

            $this->getLibrary('phpMailer', 'class.phpmailer');
            $this->getLibrary('phpMailer', 'class.smtp');
            $mail = new PHPMailer();

            $transac = $this->_master->transac();
            if ($transac)
            {
                $campos = array(
                    'EST_REG_TIP_EST' => 3,
                    'EST_REG_DESCRIPCION' => 'INSERT - Registrar nuevo usuario',
                    'EST_REG_TABLA' => 1
                );
                $idEstado['T_USUARIOS'] = $this->_reg->registroInsert($campos, 0);

                $campos['T_USUARIOS'] = array(
                    'USU_NOMBRE' => $this->getPostParam('txtNombre'),
                    'USU_PRIMER_APELLIDO' => $this->getPostParam('txtPApellido'),
                    'USU_SEGUNDO_APELLIDO' => $this->getPostParam('txtSApellido'),
                    'USU_PAIS_NACIMIENTO' => $this->getPostParam('ddlPNacimiento'),
                    'USU_NACIONALIDAD' => $this->getPostParam('ddlNacionalidad'),
                    'USU_GENERO' => $this->getPostParam('ddlGenero'),
                    'USU_GRUPO_SANGUINEO' => $this->getPostParam('ddlGSanguineo'),
                    'USU_ESTADO_CIVIL' => $this->getPostParam('ddlECivil'),
                    'USU_NICK_NAME' => $this->getPostParam('txtNickName'),
                    'USU_PASSWORD' => Hash::getHash($this->getPostParam('txtPassword')),
                    'USU_EST_REG' => $idEstado['T_USUARIOS'],
                    'USU_FECHA_NACIMIENTO' => $this->getPostParam('txtFNacimiento')
                );
                $T_USUARIOS = $this->_master->masterInsert(false, 'T_USUARIOS', $campos['T_USUARIOS'], 'USU_ID');

                $this->_reg->registroUpdate($T_USUARIOS, $idEstado['T_USUARIOS']);

                $campos = array(
                    'EST_REG_TIP_EST' => 3,
                    'EST_REG_DESCRIPCION' => 'INSERT - Registrar nuevo documento para el usuario' . $T_USUARIOS,
                    'EST_REG_TABLA' => 7
                );
                $idEstado['T_DOCUMENTS'] = $this->_reg->registroInsert($campos, 0);

                $campos['T_DOCUMENTS'] = array(
                    'DOC_ID_USUARIO' => $T_USUARIOS,
                    'DOC_NUMERO_DOCUMENTO' => $this->getPostParam('txtDocumento'),
                    'DOC_TIPO_DOCUMENTO' => $this->getPostParam('ddlTDocumento'),
                    'DOC_LUGAR_EXPEDICION' => $this->getPostParam('txtLExpedicion'),
                    'DOC_RUTA_ACRCHIVO' => '0',
                    'DOC_EST_REG' => $idEstado['T_DOCUMENTS']
                );
                $T_DOCUMENTS = $this->_master->masterInsert(false, 'T_DOCUMENTS', $campos['T_DOCUMENTS'], 'DOC_ID');

                $this->_reg->registroUpdate($T_DOCUMENTS, $idEstado['T_DOCUMENTS']);

                $campos = array(
                    'EST_REG_TIP_EST' => 3,
                    'EST_REG_DESCRIPCION' => 'INSERT - Registrar nuevo email para el usuario' . $T_USUARIOS,
                    'EST_REG_TABLA' => 8
                );
                $idEstado['T_EMAILS'] = $this->_reg->registroInsert($campos, 0);

                $campos['T_EMAILS'] = array(
                    'EMAIL_ID_USUARIO' => $T_USUARIOS,
                    'EMAIL_DIRECCION' => $this->getPostParam('txtEmail'),
                    'EMAIL_TIPO_EMAIL' => 1,
                    'EMAIL_EST_REG' => $idEstado['T_EMAILS']
                );
                $T_EMAILS = $this->_master->masterInsert(false, 'T_EMAILS', $campos['T_EMAILS'], 'EMAIL_ID');

                $this->_reg->registroUpdate($T_EMAILS, $idEstado['T_EMAILS']);

                $campos = array(
                    'EST_REG_TIP_EST' => 3,
                    'EST_REG_DESCRIPCION' => 'INSERT - Registrar nueva cuenta para el usuario' . $T_USUARIOS,
                    'EST_REG_TABLA' => 4
                );
                $idEstado['T_CUENTAS'] = $this->_reg->registroInsert($campos, 0);

                $campos['T_CUENTAS'] = array(
                    'CUENTA_ID_USUARIO' => $T_USUARIOS,
                    'CUENTA_ID_ROL' => 3,
                    'CUENTA_EST_REG' => $idEstado['T_CUENTAS']
                );
                $T_CUENTAS = $this->_master->masterInsert(true, 'T_CUENTAS', $campos['T_CUENTAS'], 'CUENTA_ID');

                $this->_reg->registroUpdate($T_CUENTAS, $idEstado['T_CUENTAS']);
            }
            else
            {
                throw new Exception('Error al crear la transacción');
            }

            if (!is_int($T_USUARIOS) || !is_int($T_DOCUMENTS) || !is_int($T_EMAILS) || !is_int($T_CUENTAS))
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
