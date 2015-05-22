<!DOCTYPE html>
<html>
    <head>
        <title><?php if (isset($this->titulo)) echo $this->titulo; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
        <div id="main">
            <div id="header">
                <h1><?php echo APP_NAME; ?></h1>
            </div>
            <div id="top_menu">
                <ul id="menuPrincipal" class="ulMenuPrincipal">
					<?php if (isset($_layoutParams['menu'])): ?>
						<?php for ($i = 0; $i < count($_layoutParams['menu']); $i++): ?>
							<?php
							if ($item && $_layoutParams['menu'][$i]['id'] == $item)
							{
								$_item_style = 'current';
							}
							else
							{
								$_item_style = '';
							}
							?>        
							<li class="liPrin liPrin-<?php echo $i; ?>">
								<?php
								unset($url);
								unset($href);
								$url = (isset($_layoutParams['menu'][$i]['enlace'])) ? $_layoutParams['menu'][$i]['enlace'] : '';
								$href = (!empty($url)) ? 'href="' . $url . '"' : '';
								?>
								<a class="<?php echo $_item_style; ?>" <?php echo $href; ?>>
									<?php echo $_layoutParams['menu'][$i]['titulo']; ?>
								</a>	
								<?php if (isset($_layoutParams['menu'][$i]['submenu']) && $_layoutParams['menu'][$i]['submenu']): ?>
									<ul class="ulSubMenu oculto">
										<?php for ($j = 0; $j < count($_layoutParams['menu'][$i]['subArray']); $j++): ?>
											<?php
											if ($item && $_layoutParams['menu'][$i]['subArray'][$j]['id'] == $item)
											{
												$_item_style = 'current';
											}
											else
											{
												$_item_style = '';
											}
											?>        
											<li class="liSubMenu liSubMenu-<?php echo $j; ?>">
												<?php
												unset($url);
												unset($href);
												$url = (isset($_layoutParams['menu'][$i]['subArray'][$j]['enlace'])) ? $_layoutParams['menu'][$i]['subArray'][$j]['enlace'] : '';
												$href = (!empty($url)) ? 'href="' . $url . '"' : '';
												?>
												<a class="<?php echo $_item_style; ?> " <?php echo $href; ?>>
													<?php echo $_layoutParams['menu'][$i]['subArray'][$j]['titulo']; ?>
												</a>	
												<?php if (isset($_layoutParams['menu'][$i]['subArray'][$j]['submenu']) && $_layoutParams['menu'][$i]['subArray'][$j]['submenu']): ?>
													<ul class="ulSubMenu2 ulSubMenu2-<?php echo $j; ?> oculto">
														<?php for ($k = 0; $k < count($_layoutParams['menu'][$i]['subArray'][$j]['subArray']); $k++): ?>
															<?php
															if ($item && $_layoutParams['menu'][$i]['subArray'][$j]['subArray'][$k]['id'] == $item)
															{
																$_item_style = 'current';
															}
															else
															{
																$_item_style = '';
															}
															?>        
															<li class="liSubMenu2-<?php echo $k; ?>">
																<?php
																unset($url);
																unset($href);
																$url = (isset($_layoutParams['menu'][$i]['subArray'][$j]['subArray'][$k]['enlace'])) ? $_layoutParams['menu'][$i]['subArray'][$j]['subArray'][$k]['enlace'] : '';
																$href = (!empty($url)) ? 'href="' . $url . '"' : '';
																?>
																<a class="<?php echo $_item_style; ?> " <?php echo $href; ?>>
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
            </div>
            <div id="content">
				<?php if (isset($this->_error)): ?>
					<div id="error"><?php echo $this->_error; ?></div>
				<?php endif; ?>

				<?php if (isset($this->_mensaje)): ?>
					<div id="mensaje"><?php echo $this->_mensaje; ?></div>
				<?php endif; ?>