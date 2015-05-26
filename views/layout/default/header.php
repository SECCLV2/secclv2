<!DOCTYPE html>
<html>
    <head>
        <title><?php if (isset($this->titulo)) echo $this->titulo; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="<?php echo BASE_URL; ?>public/js/jquery.js"></script>
        <script src="<?php echo BASE_URL; ?>public/bootstrap/js/bootstrap.js"></script>
        <link href="<?php echo BASE_URL; ?>public/bootstrap/css/bootstrap.css" rel="stylesheet"/>
        <script src="<?php echo BASE_URL; ?>public/bootstrap_switch/js/bootstrap-switch.js" type="text/javascript"></script>
        <link href="<?php echo BASE_URL; ?>public/bootstrap_switch/css/bootstrap-switch.css" rel="stylesheet" type="text/css"/>
        <link rel='stylesheet' href='<?php echo $_layoutParams['ruta_css']; ?>estilos.css'/>
        <?php if (isset($_layoutParams['plugins']) && count($_layoutParams['plugins'])): ?>
            <?php for ($i = 0; $i < count($_layoutParams['plugins']); $i++): ?>
                <script src="<?php echo $_layoutParams['plugins'][$i]; ?>"></script>
            <?php endfor; ?>
        <?php endif; ?>
        <?php if (isset($_layoutParams['js']) && count($_layoutParams['js'])): ?>
            <?php for ($i = 0; $i < count($_layoutParams['js']); $i++): ?>
                <script src="<?php echo $_layoutParams['js'][$i]; ?>"></script>
            <?php endfor; ?>
        <?php endif; ?>
    </head>
    <body>
        <div id="main" class="container-fluid">
            <div id="header">
                <img src="<?php echo BASE_URL . 'public/img/header.jpg'; ?>" class="img-thumbnail" width="100%">
            </div>
            <nav class="navbar navbar-default">
                <a class="navbar-brand"><?php echo APP_NAME; ?></a>
                <ul id="menuPrincipal" class="ulMenuPrincipal nav nav-tabs">
                    <?php if (isset($_layoutParams['menu'])): ?>
                        <?php for ($i = 0; $i < count($_layoutParams['menu']); $i++): ?>
                            <?php
                            if ($item && $_layoutParams['menu'][$i]['id'] == $item)
                            {
                                $_item_style = 'active';
                            }
                            else
                            {
                                $_item_style = '';
                            }
                            ?>        
                            <li class="liPrin liPrin-<?php echo $i . ' ' . $_item_style; ?>">
                                <a href="<?php echo $_layoutParams['menu'][$i]['enlace']; ?>">
                                    <?php echo $_layoutParams['menu'][$i]['titulo']; ?>
                                </a>	
                                <?php if (isset($_layoutParams['menu'][$i]['submenu']) && $_layoutParams['menu'][$i]['submenu']): ?>
                                    <ul class="ulSubMenu oculto">
                                        <?php for ($j = 0; $j < count($_layoutParams['menu'][$i]['subArray']); $j++): ?>
                                            <?php
                                            if ($item && $_layoutParams['menu'][$i]['subArray'][$j]['id'] == $item)
                                            {
                                                $_item_style = 'active';
                                            }
                                            else
                                            {
                                                $_item_style = '';
                                            }
                                            ?>        
                                            <li class="liSubMenu liSubMenu-<?php echo $j . ' ' . $_item_style; ?>">
                                                <a href="<?php echo $_layoutParams['menu'][$i]['subArray'][$j]['enlace']; ?>">
                                                    <?php echo $_layoutParams['menu'][$i]['subArray'][$j]['titulo']; ?>
                                                </a>
                                                <?php if (isset($_layoutParams['menu'][$i]['subArray'][$j]['submenu']) && $_layoutParams['menu'][$i]['subArray'][$j]['submenu']): ?>
                                                    <ul class="ulSubMenu2 ulSubMenu2-<?php echo $j; ?> oculto">
                                                        <?php for ($k = 0; $k < count($_layoutParams['menu'][$i]['subArray'][$j]['subArray']); $k++): ?>
                                                            <?php
                                                            if ($item && $_layoutParams['menu'][$i]['subArray'][$j]['subArray'][$k]['id'] == $item)
                                                            {
                                                                $_item_style = 'active';
                                                            }
                                                            else
                                                            {
                                                                $_item_style = '';
                                                            }
                                                            ?>        
                                                            <li class="liSubMenu2-<?php echo $k . ' ' . $_item_style; ?>">
                                                                <a href="<?php echo $_layoutParams['menu'][$i]['subArray'][$j]['subArray'][$k]['enlace']; ?>">
                                                                    <?php echo $_layoutParams['menu'][$i]['subArray'][$j]['subArray'][$k]['titulo']; ?>
                                                                </a>
                                                            </li>
                                                        <?php endfor; ?>
                                                    </ul>
                                                <?php endif; ?>
                                            </li>
                                        <?php endfor; ?>
                                    </ul>
                                <?php endif; ?>
                            </li>
                        <?php endfor; ?>
                    <?php endif; ?>
                </ul>
            </nav>
            <div id="content" class="container-fluid contenido">
                <?php if (isset($this->_error)): ?>
                    <div id="error"><?php echo $this->_error; ?></div>
                <?php endif; ?>

                <?php if (isset($this->_mensaje)): ?>
                    <div id="mensaje"><?php echo $this->_mensaje; ?></div>
                <?php endif; ?>