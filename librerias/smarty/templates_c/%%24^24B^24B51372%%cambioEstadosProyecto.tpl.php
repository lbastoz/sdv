<?php /* Smarty version 2.6.26, created on 2017-03-21 11:44:15
         compiled from proyectos/cambioEstadosProyecto.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'proyectos/cambioEstadosProyecto.tpl', 96, false),)), $this); ?>


	<form id="frmCambioEstados">

		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'proyectos/pedirSeguimiento.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<br>
		<table cellspacing="0" cellpadding="2" border="0" width="100%">
			<tr>
				<td colspan="2"></td>
				<td rowspan="4" width="300px" align="center" valign="top"
					style="padding-top:5px; border-left: 1px dotted #999999; border-right: 1px dotted #999999; border-bottom: 1px dotted #999999"
				>
					<table cellpadding="0" cellspacing="2" border="0" width="99%" align="justify">
						<tr><td style="padding-top: 10px"><b>Para el cambio de estado individual</b></td></tr>
						<tr><td style="padding-left: 15px">
							<li>Puede buscar el Proyecto por el nombre</li>
						</td></tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="tituloTabla" height="20px">
					Cambio de estado individual
				</td>
			</tr>

			<!-- SOLO PARA UNA CEDULA -->
			<tr>
				<td colspan="2" style="border-bottom: 1px dotted #999999; border-left: 1px dotted #999999;" valign="top">
					<table cellspacing="" cellpadding="0" border="0" width="100%">
						<tr>
							<td class="tituloCampo" width="200px">
								Buscar por nombre del proyecto:
							</td>
							<td height="17px" valign="top">
								<div id="buscarNombreProyecto">
									<input type="hidden" id="myHidden" name="myHidden">
									<input	id="nombre" 
											name="nombre" 
											type="text" 
											style="width:233px" 
											onFocus="this.style.backgroundColor = '#ADD8E6'; " 
											onBlur="this.style.backgroundColor = '#FFFFFF';" 
									/>
									<div id="contenedor"></div>
								</div>
							</td>
						</tr>
						<tr>
							<td class="tituloCampo">
								Estado del Proceso
							</td>
							<td>
								<select name="seqPryEstadoProceso" style="width:310px">
									<option value="0">Seleccione un estado</option>
									<?php $_from = $this->_tpl_vars['arrPryEstados']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['seqEstado'] => $this->_tpl_vars['txtEstado']):
?>
									<option value="<?php echo $this->_tpl_vars['seqEstado']; ?>
"><?php echo $this->_tpl_vars['txtEstado']; ?>
</option>
									<?php endforeach; endif; unset($_from); ?> 
								</select>
							</td>
						</tr>
					</table>
				</td>
			</tr>

			<!-- BOTON -->
			<tr>
				<td colspan="2" height="25px" align="right" style="padding-right:20px;" bgcolor="#F9F9F9">
						<input type="button" 
								value="Cambiar Estados" 
								onClick="someterFormulario( 
										'mensajes', 
										this.form, 
										'./contenidos/proyectos/cambioEstadosProyectoSalvar.php', 
										true, 
										true
									); 
								"
						/>
				</td>
			</tr>
		</table>
	</form>

	<div id="listenerBuscarNombreProyecto"></div>
	<!--<div id="cambioEstadosPosibles" style="display:none">
		<div class="hd">Listado de Estados Validos</div>
		<div class="bd">
			<center>
				<table cellpadding="2" cellspacing="0" border="0" width="90%">
					<tr>
						<td class="tituloTabla">ID</td>
						<td class="tituloTabla">Descripción</td>
					</tr>
					<?php $_from = $this->_tpl_vars['arrEstados']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['seqEstado'] => $this->_tpl_vars['txtEstado']):
?>
						<tr><td width="30px" bgcolor="<?php echo smarty_function_cycle(array('name' => 'c1','values' => "#FFFFFF,#E4E4E4"), $this);?>
"><?php echo $this->_tpl_vars['seqEstado']; ?>
</td>
							<td bgcolor="<?php echo smarty_function_cycle(array('name' => 'c2','values' => "#FFFFFF,#E4E4E4"), $this);?>
"><?php echo $this->_tpl_vars['txtEstado']; ?>
</td>
						</tr>
					<?php endforeach; endif; unset($_from); ?>
				</table>
			</center>
		</div>
	</div>-->