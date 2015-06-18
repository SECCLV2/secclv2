<?php

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Esta clase sera la encargada de administrar y construir las visas.
 * ---------------------------------------------------------------------------------------------------------------------
 */

class View {

    private $_request;
    private $_js;
    private $_plugin;
    private $_acl;
    private $_rutas;
    private $_db;

    /*
     * Constructor que prepara los atributos para ser usados en la construcción de las vistas.
     */

    public function __construct(Request $peticion, Acl $acl, Model $db)
    {
        $this->_request = $peticion;
        $this->_js = array();
        $this->_plugin = array();
        $this->_acl = $acl;
        $this->_rutas = array();
        $this->_db = $db;

        $modulo = $this->_request->getModulo();
        $controlador = $this->_request->getControlador();

        if ($modulo)
        {
            $this->_rutas['view'] = ROOT . 'modules' . DS . $modulo . DS . 'views' . DS . $controlador . DS;
            $this->_rutas['js'] = BASE_URL . 'modules/' . $modulo . '/views/' . $controlador . '/js/';
        }
        else
        {
            $this->_rutas['view'] = ROOT . 'views' . DS . $controlador . DS;
            $this->_rutas['js'] = BASE_URL . 'views/' . $controlador . '/js/';
        }
    }

    /*
     * Función que construye las vistas.
     */

    public function renderizar($vista, $item = false, $layout = DEFAULT_LAYOUT, $mod = false)
    {
        $js = array();
        if (count($this->_js))
        {
            $js = $this->_js;
        }

        $plugins = array();
        if (count($this->_plugin))
        {
            $plugins = $this->_plugin;
        }

        $_layoutParams = array(
            'ruta_css' => BASE_URL . 'views/layout/' . $layout . '/css/',
            'ruta_img' => BASE_URL . 'views/layout/' . $layout . '/img/',
            'ruta_js' => BASE_URL . 'views/layout/' . $layout . '/js/',
            'js' => $js,
            'menu' => $this->menu(),
            'plugins' => $plugins,
            'acl' => $this->_acl
        );

        if ($mod)
            $rutaView = ROOT . 'modules' . DS . $mod . DS . $vista . '.phtml';
        else
            $rutaView = $this->_rutas['view'] . $vista . '.phtml';

        if (is_readable($rutaView))
        {
            include_once ROOT . 'views' . DS . 'layout' . DS . $layout . DS . 'header.php';
            include_once $rutaView;
            include_once ROOT . 'views' . DS . 'layout' . DS . $layout . DS . 'footer.php';
        }
        else
        {
            if (PRUEBAS == 'On')
                throw new Exception('Error en la ruta de la vista: </br> is_readable = false </br> Ruta Vista: ' . $rutaView);
        }
    }

    /*
     * Función que crea el menu.
     */

    private function menu()
    {
        if (Session::get('logueado'))
        {
            $menuRol = array();
            for ($i = 0; $i < Session::get('rol')['numRows']; $i++)
            {
                unset($T_CUENTAS);
				unset($menuCentroRol);

                $T_CUENTAS = $this->_db->menuRol($i);

                if ($T_CUENTAS['numRows'] > 0)
                    $submenu = true;
                else
                {
                    $submenu = false;
                    $menuCentroRol = false;
                }

                for ($j = 0; $j < $T_CUENTAS['numRows']; $j++)
                {
                    $menuCentroRol[] = array(
                        'id' => Session::get('rol')['ROL_DESCRIPCION'][$i] . ':' . $T_CUENTAS['CEN_USU_ROL_ID_CENTRO'][$j],
                        'titulo' => $T_CUENTAS['CEN_USU_ROL_ID_CENTRO'][$j],
                        'enlace' => BASE_URL . Hash::urlEncrypt('sistem/index/rol/' . Session::get('rol')['USU_ROL_ID_ROL'][$i] . '/' . $T_CUENTAS['CEN_USU_ROL_ID_CENTRO'][$j]),
                        'submenu' => false,
                        'imagen' => ''
                    );
                }

                $menuRol[] = array(
                    'id' => Session::get('rol')['ROL_DESCRIPCION'][$i],
                    'titulo' => Session::get('rol')['ROL_DESCRIPCION'][$i],
                    'enlace' => BASE_URL . Hash::urlEncrypt('sistem/index/rol/' . Session::get('rol')['USU_ROL_ID_ROL'][$i]),
                    'submenu' => $submenu,
                    'subArray' => $menuCentroRol,
                    'imagen' => ''
                );
            }

            $menu[] = array(
                'id' => 'Roles',
                'titulo' => 'Roles',
                'submenu' => true,
                'subArray' => $menuRol,
                'imagen' => ''
            );

            switch (Session::get('rolAct'))
            {
                case 1:
                    $menuAdmin[] = array(
                        'id' => '1-Administrador',
                        'titulo' => 'Información',
                        'enlace' => BASE_URL . Hash::urlEncrypt('sistem/administrador')
                    );
                    $menuAdmin[] = array(
                        'id' => '2-Administrador',
                        'titulo' => 'Administrar Permisos',
                        'enlace' => BASE_URL . Hash::urlEncrypt('administrador/permisos/permisos/1')
                    );
                    $menuAdmin[] = array(
                        'id' => '3-Administrador',
                        'titulo' => 'Permisos Roles',
                        'enlace' => BASE_URL . Hash::urlEncrypt('administrador/permiRol/roles/1')
                    );
                    $menuAdmin[] = array(
                        'id' => '3-Administrador',
                        'titulo' => 'Permisos Usuarios',
                        'enlace' => BASE_URL . Hash::urlEncrypt('administrador/permisos/permisos/1')
                    );
                    $menu[] = array(
                        'id' => 'admin',
                        'titulo' => 'administrador',
                        'submenu' => true,
                        'subArray' => $menuAdmin,
                        'imagen' => ''
                    );
                    break;
                default:
                    break;
            }
            $menu[] = array(
                'id' => 'login',
                'titulo' => 'cerrar sesión',
                'enlace' => BASE_URL . Hash::urlEncrypt('usuarios/login/logout')
            );
        }
        else
        {
            $menu[] = array(
                'id' => 'Inicio',
                'titulo' => 'Inicio',
                'enlace' => BASE_URL . Hash::urlEncrypt('sistem')
            );
            $menu[] = array(
                'id' => 'Q_CCL',
                'titulo' => '¿Qué es CCL?',
                'enlace' => BASE_URL . Hash::urlEncrypt('sistem/index/Q_CCL')
            );
            $menu[] = array(
                'id' => 'PC_ECCL',
                'titulo' => 'Principios y Características ECCL',
                'enlace' => BASE_URL . Hash::urlEncrypt('sistem/index/PC_ECCL')
            );
            $menu[] = array(
                'id' => 'registrarse',
                'titulo' => 'registrarse',
                'enlace' => BASE_URL . Hash::urlEncrypt('usuarios/registro/registrar')
            );
            $menu[] = array(
                'id' => 'login',
                'titulo' => 'iniciar sesión',
                'enlace' => BASE_URL . Hash::urlEncrypt('usuarios/login/login')
            );
        }

        return $menu;
    }

    /*
     * Funcion que llama los archivos de javascript.
     */

    public function setJs(array $js)
    {
        if (is_array($js) && count($js))
        {
            for ($i = 0; $i < count($js); $i++)
            {
                $this->_js[] = $this->_rutas['js'] . $js[$i] . '.js';
            }
        }
        else
        {
            if (PRUEBAS == 'On')
                throw new Exception('Error en la ruta de la javascript: </br> is_readable = false </br> Ruta Javascript: ' . $libreria);
        }
    }

    /*
     * Función que llama los plugins.
     */

    public function setPlugins(array $plugins)
    {
        if (is_array($plugins) && count($plugins))
        {
            for ($i = 0; $i < count($plugins); $i++)
            {
                $this->_plugin[] = BASE_URL . 'public/js/' . $plugins[$i] . '.js';
            }
        }
        else
        {
            if (PRUEBAS == 'On')
                throw new Exception('Error de plugin');
        }
    }

    public function setWidget($widget, $metodo, $opciones = array())
    {
        if (!is_array($opciones))
        {
            $opciones = array($opciones);
        }

        if (is_readable(ROOT . 'widgets' . DS . $widget . '.php'))
        {
            include ROOT . 'widgets' . DS . $widget . '.php';

            $widgetClass = $widget . 'Widget';
			
            if (!class_exists($widgetClass))
            {
                if (PRUEBAS == 'On')
                    throw new Exception('View // Error en la clase: </br> class_exists = false </br> Nombre Clase: ' . $widgetClass);
            }

            if (is_callable($widgetClass, $metodo))
            {
                if (count($opciones))
                {
                    return call_user_func_array(array(new $widgetClass, $metodo), $opciones);
                }
                else
                {
                    return call_user_func(array(new $widgetClass, $metodo));
                }
            }
            if (PRUEBAS == 'On')
                throw new Exception('View // Error en el metodo del widget: </br> is_callable = false </br> Classe, Metodo Widget: ' . 'Clase - ' . $widgetClass . ' ' . 'Metodo - ' . $metodo);
        }
        if (PRUEBAS == 'On')
            throw new Exception('View // Error en la ruta del widget: </br> is_readable = false </br> Ruta Widget: ' . ROOT . 'widgets' . DS . $widget . '.php');
    }

}
