
<link rel="stylesheet" type="text/css" href="http://<?php echo $_SERVER['SERVER_NAME']; ?>/zabbix/styles/blue-theme.css" />
<?php 


/*require_once '../../include/config.inc.php';
require_once '../../include/forms.inc.php';
require_once '../../include/users.inc.php';
require_once '../../include/classes/user/CWebUser.inc.php';

// the user is not logged in, display the login form
if (!CWebUser::$data['alias'] || CWebUser::$data['alias'] == ZBX_GUEST_USER) {
	switch ($config['authentication_type']) {
		case ZBX_AUTH_HTTP:
			echo _('User name does not match with DB');
			break;
		case ZBX_AUTH_LDAP:
		case ZBX_AUTH_INTERNAL:
			if (isset($_REQUEST['enter'])) {
				$_REQUEST['autologin'] = getRequest('autologin', 0);
			}

			if ($messages = clear_messages()) {
				$messages = array_pop($messages);
				$_REQUEST['message'] = $messages['message'];
			}
			$loginForm = new CView('general.login');
			$loginForm->render();
	}
}
else {
	redirect(zbx_empty(CWebUser::$data['url']) ? ZBX_DEFAULT_URL : CWebUser::$data['url']);
}*/

echo $_COOKIE["zbx_sessionid"];

	if(!$_COOKIE["zbx_sessionid"]) {
		echo '
			<table class="warningTable" align="center">
				<tbody>
					<tr class="header">
						<td>Voc&ecirc; n&atilde;o est&aacute; logado.</td>
					</tr>
					<tr class="content">
						<td>
							<span>Voc&ecirc; deve entrar para ver est&aacute; p&aacute;gina.<br>
Se voc&ecirc; acha que esta mensagem &eacute; errado, por favor consulte os administradores sobre como obter as permiss&otilde;es necess&aacute;rias.</span>
						</td>
					<tr class="footerx">
						<td>
							<div class="buttons">
								<input class="input formlist" type="button" id="login" name="login" value="Login" onclick="javascript: document.location=\'http://'.$_SERVER['SERVER_NAME'].'/zabbix/index.php\'">
							</div>
						</td>
					</tr>
				</tbody>
			</table>';
	//break;		
	}
?>