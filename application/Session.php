<?php

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Esta clase gestionara la creación, manejo y eliminación de sesiones.
 * ---------------------------------------------------------------------------------------------------------------------
 */

class Session {

	/*
	 * Función que inicia una sesión.
	 */
    public static function init()
    {
        session_start();
    }

	/*
	 * Función que destruye una sesión o una variable de sesión.
	 */
    public static function destroy($clave = false)
    {
        if ($clave)
        {
            if (is_array($clave))
            {
                for ($i = 0; $i < count($clave); $i++)
                {
                    if (isset($_SESSION[$clave[$i]]))
                    {
                        unset($_SESSION[$clave[$i]]);
                    }
                }
            }
            else
            {
                if (isset($_SESSION[$clave]))
                {
                    unset($_SESSION[$clave]);
                }
            }
        }
        else
        {
            session_destroy();
        }
    }

	/*
	 * Función que setea una variable se sesión.
	 */
    public static function set($clave, $valor)
    {
        if (!empty($clave))
        {
            $_SESSION[$clave] = $valor;
        }
    }

	/*
	 * Función que obtiene el valor de una variable se sesión.
	 */
    public static function get($clave)
    {
        if (isset($_SESSION[$clave]))
        {
            return $_SESSION[$clave];
        }
    }
	
	/*
	 * Funcion que gestiona los tiempo de la sesión.
	 */
	public static function tiempo()
    {
        if (!Session::get('tiempo') || !defined('SESSION_TIME'))
        {
            throw new Exception('No se ha definido el tiempo de sesion');
        }

        if (SESSION_TIME == 0)
        {
            return;
        }

        if (time() - Session::get('tiempo') > (SESSION_TIME * 60))
        {
            Session::destroy();
            header('location:' . BASE_URL . 'error/access/8080');
        }
        else
        {
            Session::set('tiempo', time());
        }
    }

	/*
	 * Funcionas a descartar // ----------------------------------------------------------------------------------------
	 */
    public static function acceso($level)
    {
        if (!Session::get('logueado'))
        {
            header('location:' . BASE_URL . 'error/access/5050');
            exit;
        }

        Session::tiempo();

        if (Session::getLevel($level) > Session::getLevel(Session::get('level')))
        {
            header('location:' . BASE_URL . 'error/access/5050');
            exit;
        }
    }

    public static function accesoView($level)
    {
        if (!Session::get('logueado'))
        {
            return false;
        }

        if (Session::getLevel($level) > Session::getLevel(Session::get('level')))
        {
            return false;
        }

        return true;
    }

    public static function getLevel($level)
    {
        $rol['admin'] = 3;
        $rol['especial'] = 2;
        $rol['usuario'] = 1;

        if (!array_key_exists($level, $rol))
        {
            throw new Exception('Error de acceso');
        }
        else
        {
            return $rol[$level];
        }
    }

    public static function accesoEstricto(array $level, $noAdmin = false)
    {
        if (!Session::get('logueado'))
        {
            header('location:' . BASE_URL . 'error/access/5050');
            exit;
        }

        Session::tiempo();

        if ($noAdmin == false)
        {
            if (Session::get('level') == 'admin')
            {
                return;
            }
        }

        if (count($level))
        {
            if (in_array(Session::get('level'), $level))
            {
                return;
            }
        }

        header('location:' . BASE_URL . 'error/access/5050');
    }

    public static function accesoViewEstricto(array $level, $noAdmin = false)
    {
        if (!Session::get('logueado'))
        {
            return false;
        }

        if ($noAdmin == false)
        {
            if ($Session::get('level') == 'admin')
            {
                return true;
            }
        }

        if (count($level))
        {
            if (in_array(Session::get('level'), $level))
            {
                return true;
            }
        }

        return false;
    }

}
