<?php
/* For licensing terms, see /license.txt */
/**
*	@package chamilo.messages
*/
//require_once '../inc/global.inc.php';
if (api_get_setting('social.allow_social_tool') == 'true' &&
	api_get_setting('message.allow_message_tool') == 'true'
) {
	header('Location:inbox.php?f=social');
} elseif (api_get_setting('message.allow_message_tool') == 'true') {
	header('Location:inbox.php');
}
exit;
