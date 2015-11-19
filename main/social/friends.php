<?php
/* For licensing terms, see /license.txt */

/**
 * @package chamilo.social
 * @author Julio Montoya <gugli100@gmail.com>
 */

$cidReset = true;
//require_once '../inc/global.inc.php';

api_block_anonymous_users();
if (api_get_setting('social.allow_social_tool') != 'true') {
    api_not_allowed();
}

$this_section = SECTION_SOCIAL;

$htmlHeadXtra[] = '<script>

function delete_friend (element_div) {
	id_image=$(element_div).attr("id");
	user_id=id_image.split("_");
	if (confirm("'.get_lang('Delete', '').'")) {
		 $.ajax({
			contentType: "application/x-www-form-urlencoded",
			type: "POST",
			url: "'.api_get_path(WEB_AJAX_PATH).'social.ajax.php?a=delete_friend",
			data: "delete_friend_id="+user_id[1],
			success: function(datos) {
			 $("div#"+"div_"+user_id[1]).hide("slow");
			 $("div#"+"div_"+user_id[1]).html("");
			 clear_form ();
			}
		});
	}
}

function search_image_social()  {
	var name_search = $("#id_search_image").val();
	 $.ajax({
		contentType: "application/x-www-form-urlencoded",
		type: "POST",
		url: "'.api_get_path(WEB_AJAX_PATH).'social.ajax.php?a=show_my_friends",
		data: "search_name_q="+name_search,
		success: function(data) {
			$("#friends").html(data);
		}
	});
}

function show_icon_delete(element_html) {
	elem_id=$(element_html).attr("id");
	id_elem=elem_id.split("_");
	ident="#img_"+id_elem[1];
	$(ident).attr("src","../img/delete.png");
	$(ident).attr("alt","'.get_lang('Delete', '').'");
	$(ident).attr("title","'.get_lang('Delete', '').'");
}

function hide_icon_delete(element_html)  {
	elem_id=$(element_html).attr("id");
	id_elem=elem_id.split("_");
	ident="#img_"+id_elem[1];
	$(ident).attr("src","../img/blank.gif");
	$(ident).attr("alt","");
	$(ident).attr("title","");
}

function clear_form () {
	$("input[@type=radio]").attr("checked", false);
	$("div#div_qualify_image").html("");
	$("div#div_info_user").html("");
}

</script>';

$interbreadcrumb[] = array('url' => 'profile.php', 'name' => get_lang('SocialNetwork'));
$interbreadcrumb[] = array('url' => '#', 'name' => get_lang('Friends'));

//Block Social Menu
$social_menu_block = SocialManager::show_social_menu('friends');
$user_id = api_get_user_id();
$name_search = isset($_POST['search_name_q']) ? $_POST['search_name_q'] : null;
$number_friends = 0;

if (isset($name_search) && $name_search != 'undefined') {
    $friends = SocialManager::get_friends($user_id, null, $name_search);
} else {
    $friends = SocialManager::get_friends($user_id);
}

$social_right_content = '<div class="col-md-12">';

if (count($friends) == 0) {
    $social_right_content .= Display::return_message(
        Display::tag('p', get_lang('NoFriendsInYourContactList')),
        'warning',
        false
    );
    $social_right_content .= Display::toolbarButton(
        get_lang('TryAndFindSomeFriends'),
        'search.php',
        'search',
        'success'
    );
} else {
    $filterForm = new FormValidator('filter');
    $filterForm->addText(
        'id_search_image',
        get_lang('Search'),
        false,
        [
            'onkeyup' => 'search_image_social()',
            'id' => 'id_search_image'
        ]
    );

    $social_right_content .= $filterForm->returnForm();

    $friend_html = '<div id="friends" class="row">';

    $number_friends = count($friends);
    $j = 0;

    for ($k = 0; $k < $number_friends; $k++) {
        while ($j < $number_friends) {
            if (isset($friends[$j])) {
                $friend = $friends[$j];
                $user_name = api_xml_http_response_encode($friend['firstName'].' '.$friend['lastName']);
				$userPicture = UserManager::getUserPicture($friend['friend_user_id']);

                $friend_html .= '
                    <div class="col-md-3">
                        <div class="thumbnail text-center" id="div_' . $friends[$j]['friend_user_id'] . '">
                            <img src="' . $userPicture . '" class="img-responsive" id="imgfriend_' . $friend['friend_user_id'] . '" title="$user_name">
                            <div class="caption">
                                <h3>
                                    <a href="profile.php?u=' . $friend['friend_user_id'] . '">' . $user_name . '</a>
                                </h3>
                                <p>
                                    <button class="btn btn-danger" onclick="delete_friend(this)" id=img_' . $friend['friend_user_id'] . '>
                                        ' . get_lang('Delete') . '
                                    </button>
                                </p>
                            </div>
                        </div>
                    </div>
                ';
            }
            $j++;
        }
    }
    $friend_html .= '</div>';
    $social_right_content .= $friend_html;
}
$social_right_content .= '</div>';

//$tpl = new Template(get_lang('Social'));

$tpl = \Chamilo\CoreBundle\Framework\Container::getTwig();
SocialManager::setSocialUserBlock($tpl, api_get_user_id(), 'friends');
$tpl->addGlobal('social_menu_block', $social_menu_block);
$tpl->addGlobal('social_right_content', $social_right_content);
$tpl->addGlobal('social_auto_extend_link', '');
$tpl->addGlobal('social_right_information', '');


echo $tpl->render('@template_style/social/friends.html.twig');
