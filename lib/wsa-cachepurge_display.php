<?php

/**
 *  __      _____   _
 *  \ \    / / __| /_\
 *   \ \/\/ /\__ \/ _ \
 *    \_/\_/ |___/_/ \_\
 *
 * WSA - Website Accelerator Cache Purge - Admin area display
 *
 * @author          Astral Internet inc. <support@astralinternet.com>
 * @version         1.1.1
 * @copyright       2021 Copyright (C) 2021, Astral Internet inc. - support@astralinternet.com
 * @license         https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or higher
 * @link            https://www.astralinternet.com/en Astral Internet inc.
 *
 * WSA : The Astral Internet Website Accelerator is a tool that allows you to place
 * certain elements of a site in buffer memory (cache) inside the server. Once the
 * elements are placed buffer of the server, they can be served much faster to people
 * viewing a website.
 *
 * This page is the actual on-screen display of the module in the admin area. It
 * provides information about the caching module and give the option to purge the
 * cache immediately.
 *
 * You may also disable the automatic cache purge from this page.
 *
 */

// If this file is called directly, abort.
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Load the WSA class
 *
 * @since 1.0.0
 */
require_once dirname(WSA_CACHEPURGE_FILE) . '/vendor/wsa/wsa.class.php';

/**
 * Load the display class
 *
 * @since 1.1.0
 */
require_once dirname(WSA_CACHEPURGE_FILE) . '/lib/wsa-cachepurge_display.class.php';

// Disable extended validation by default
$extendedValidation = false;
// Default message tipe
$messageType = "";

/**
 * Post trust verification
 *
 * @since 1.1.0
 */
if (isset($_POST['action']) && isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'wsa-cachepurge')) {

    switch ($_POST['action']) {

        // Update the "auto purge" setting in Wordpress
        // Trigger when user click on the auto purge checkbox
        case 'autoPurge':
            if (isset($_POST['wsa-cachepurge-cachepurge_save']) && $_POST['wsa-cachepurge-cachepurge_save'] == "on") {
                update_option('wsa-cachepurge_auto-purge', 1);
            } else {
                update_option('wsa-cachepurge_auto-purge', 0);
            }

            $messageType = "autoPurge";
            break;

        // If a request was made to purge the cache, process to the cache purge ans display the success message.
        // Trigger when user press on the clear cache button
        case 'purgeCache':
            // Purge the WSA server cache
            WSA_Cachepurge_WP::purge_cache();

            // Fetch the informative box messages
            $messageType = "emptyCache";
            break;

        // Check if the WSA is installed with the extended validation.
        // Trigger when user press on the advance verification button
        case 'extendedValidation':
            // force extended validation
            $extendedValidation = true;

            // Return the informative text box once the verification is done, with the verification result
            $messageType = "advanceValidation";
            break;

        default:
            break;
    }
}

// Build the WSA status box, function will also check the current status of the WSA with the advance validation
$statusbox = WSA_Display::build_status_box($extendedValidation);
$messageBox = WSA_Display::build_message_box($messageType);

?>

<style>
/*
The styling has been place in the main display page to reduce the amount of items being loaded each time the backend pages are loaded
 */
.wsa-cachepurge {max-width: 1000px; margin: 0 auto;transition: all ease 0.3s;padding:0 20px; position:relative}
.wsa-cachepurge .wsa-message-box {border:1px solid;padding: .75rem 1.25rem; font-size: 20px; text-align: center; display:none}
.wsa-cachepurge h1 {font-size: 36px;line-height: 1.1; border-left: 4px solid #ef6c45;font-weight: lighter;padding: 0 0 0 50px;}
.wsa-cachepurge p {text-align:justify; font-size: 14px;}
.wsa-cachepurge h1 img{height: 42px; left: 30px; position: absolute;}
.wsa-cachepurge a {color: #ef6c45; text-decoration: none;}
.wsa-cachepurge a:hover {color: #ec4e1f;}
.wsa-cachepurge .flex_base {display:flex;justify-content:flex-start;align-items:center;}
.wsa-cachepurge .clearcache_button {color: #fff; background-color: #ef6c45; position: relative; padding: 5px 15px; border: 0; border-radius: 2px;font-size: 20px}
.wsa-cachepurge .clearcache_button:hover {background-color: #ec4e1f; cursor: pointer;}
.wsa-cachepurge .clearcache_button img {height: 20px; margin-right: 10px;}
.wsa-cachepurge .validation_button {color: #fff; background-color: #ef6c45; position: relative; padding: 5px 15px; border: 0; border-radius: 2px; display: inline;}
.wsa-cachepurge .validation_button:hover {background-color: #ec4e1f; cursor: pointer;}
.wsa-cachepurge .wsa_status_box, .wsa-cachepurge .white_box {background-color: #fff; border: 1px solid #ccc; padding: 15px;}
.wsa-cachepurge .good {background-color: #d4edda; color: #155724;}
.wsa-cachepurge .good p {text-align: center;}
.wsa-cachepurge .good span {font-weight: bold;}
.wsa-cachepurge .warning {background-color: #fff3cd; color: #856404;}
.wsa-cachepurge .warning p {text-align: center;}
.wsa-cachepurge .warning span {font-weight: bold;}
.wsa-cachepurge .bad {background-color: #f8d7da; color: #721c24;}
.wsa-cachepurge .bad p {text-align: center;}
.wsa-cachepurge .bad span {font-weight: bold;}
.wsa-cachepurge .white_box {display: flex; flex-direction: column; align-items: center; margin: 20px 0;}
.wsa-cachepurge .wsa_status_box p {font-weight: 600; padding: 15px; font-size: 18px; padding: 0; margin: 0;}
.wsa-cachepurge .wsa_status_box p a {color: #caced1;text-decoration: none;}
.wsa-cachepurge .wsa_status_box p a:hover {color: #989da1;}
.wsa-cachepurge .exceptions {color: #989da1;padding: 10px; font-size: 12px;}
.wsa-cachepurge .exceptions h3, .wsa-cachepurge .exceptions p, .wsa-cachepurge .exceptions ol {margin: 0;}
.wsa-cachepurge .exceptions h4 {margin-bottom: 0;}
.wsa-cachepurge .options_grp {width: 90%; text-align:left; padding:10px 0; align-items: baseline;}
.wsa-cachepurge .options_grp .options_check {min-width: 50px; text-align: center;}
.wsa-cachepurge .options_check input[type=checkbox] {visibility: hidden;}
.wsa-cachepurge .options_check label {display: block;position: relative; cursor: pointer;}
.wsa-cachepurge .options_check .checkmark {position: absolute; top: 0; left: 0; height: 20px; width: 20px; border: 1px solid #ccc; border-radius: 2px; background-color: #fff;}
.wsa-cachepurge .options_check label:hover input ~ .checkmark {background-color: #efcdc3;}
.wsa-cachepurge .options_check label input:checked ~ .checkmark {background-color: #ef6c45;}
.wsa-cachepurge .options_check .checkmark:after {content: "";  position: absolute; display: none;}
.wsa-cachepurge .options_check label input:checked ~ .checkmark:after {display: block;}
.wsa-cachepurge .options_check label .checkmark:after {left: 7px; bottom: 5px; width: 4px; height: 8px; border: solid white; border-width: 0 3px 3px 0; -webkit-transform: rotate(45deg); -ms-transform: rotate(45deg); transform: rotate(45deg);}
#wsa-progress {transition: all 5s ease; opacity: 0.1; position: absolute; z-index: 1; top: 0; right: 0; border: solid; border-right-width: 0px; border-top-width: 58px;}
#wsa-close {position: absolute; top: 0px; right: 0px; height: 18px; width: 18px; z-index: 4; cursor: pointer; font-size: 16px;padding-bottom: 2px;}
#wsa-close:hover { background-color: #a9a9a9;}
</style>
<div class="wsa-cachepurge">
    <div class="flex_base" style="justify-content:space-between">
        <h1><img src="<?=plugins_url('ressources/wsa-cachepurge_logo.svg', dirname(__FILE__))?>"><?=__("Website Accelerator - Vidage de cache", "wsa-cachepurge");?></h1>
        <!-- START status box -->
        <div class="wsa_status_box <?=$statusbox['styleColor']?>">
            <div>
                <p><?=__("Le module est", "wsa-cachepurge")?> <span> <?=$statusbox['status']?></span></p>
                <p style="font-weight: lighter;font-size: 12px; max-width: 250px;"><?=$statusbox['information']?></p>
            </div>
            <div style="text-align: center;">
                <form method="post">
                    <input type="hidden" name="action" value="extendedValidation">
                    <input type="hidden" name="nonce" value="<?=wp_create_nonce('wsa-cachepurge')?>">
                    <button class="flex_base validation_button">
                    <div><?=__("Vérification avancée", "wsa-cachepurge");?></div>
                </button>
                </form>
            </div>
        </div>
<!-- END status box -->
    </div>

    <p><?=__("L’accélérateur de site web d’<a href=\"https://www.astralinternet.com/\">Astral Internet</a> est un outil qui permet de placer certains éléments d’un site Web dans une mémoire tampon (cache) à l’intérieur du serveur. Une fois les éléments du site dans la mémoire tampon du serveur, ceux-ci seront servis beaucoup plus rapidement aux visiteurs visionnant votre site.", "wsa-cachepurge");?></p>
    <p><?=__("Pour plus d'information concernant ce module, veuillez lire l’article suivant « <a href=\"https://www.astralinternet.com/produit/fini-les-sites-trop-lents/\">Fini les sites web trop lents!</a> ».", "wsa-cachepurge");?></p>
    <p><?=__("La documentation complète du module est également disponible <a href=\"https://docs.astral360.com/\">ici</a>.", "wsa-cachepurge");?></p>

    <?php
// If a message needs to be displayed in the informative box, it will be displayed here.
if ($messageBox['gotMessage']) {?>
    <div id="wsa-message" class="wsa-message-box <?=$messageBox['styleColor']?>" style="display:flex;z-index:0;position:relative;flex-direction: column;">
        <div id="wsa-close" onClick="removeDiv()">×</div>
        <?php if ($messageBox['animation']) {?>
            <div id="wsa-progress"></div>
        <?php }?>
        <div style="display:block;z-index:2;">
            <?=$messageBox['title'];?>
        </div>
        <div style="display:block;z-index:2;font-size:small;">
            <?=$messageBox['message'];?>
        </div>
    </div>
<?php
}?>

    <div class="white_box">
        <h2><?=__("Vider la mémoire cache", "wsa-cachepurge");?></h2>
        <p><?=__("Il est possible qu'après avoir modifié une page de votre site, le changement ne soit pas visible instantanément. Lorsque c’est le cas, cela signifie que le serveur possède toujours en mémoire l’ancienne version de votre site. Vider la cache forcera le serveur à récupérer une nouvelle version de votre site, à jour, ainsi votre modification sera visible pour tous.", "wsa-cachepurge");?></p>


        <form method="post">
            <input type="hidden" name="action" value="purgeCache">
            <input type="hidden" name="nonce" value="<?=wp_create_nonce('wsa-cachepurge')?>">
            <button class="flex_base clearcache_button">
                <div><img src="<?=plugins_url('ressources/clear-single-user-cache-white.png', dirname(__FILE__))?>"></div>
                <div><?=__("Vider la cache", "");?></div>
            </button>
        </form>
        <div class="exceptions">
            <h3><?=__("Notes :", "");?></h3>
            <p><?=__("En aucun cas, vider la mémoire cache ne peut affecter le bon fonctionnement de votre site.", "");?></p>

            <h4><?=__("Si les changements ou les modifications dans votre site ne sont toujours pas visibles :", "");?></h4>
            <ol>
                <li><?=__("Assurez-vous d’avoir bien enregistré les modifications dans vos pages/articles.", "");?></li>
                <li><?=__("Si vous utilisez une extension de mise en cache comme WP Rocket, Swift, W3 Total Cache, WP Super Cache ou autre, assurez-vous de bien vider la mémoire cache de celles-ci avant de vider la cache du serveur.", "");?></li>
                <li><?=__("Si vous utilisez un CDN (Content Delivery Network) comme CloudFlare, assurez-vous que celui-ci est en mode de développement et/ou que la cache du CDN est également vidée.", "");?></li>
            </ol>
        </div>
    </div>

    <div class="white_box">
        <div class="options_grp flex_base">
            <div class="options_check">
                <label>
                    <form method="post">
                        <input type="hidden" name="action" value="autoPurge">
                        <input type="hidden" name="nonce" value="<?=wp_create_nonce('wsa-cachepurge')?>">
                        <input type="checkbox" name="wsa-cachepurge-cachepurge_save" onChange="submit();" <?=get_option('wsa-cachepurge_auto-purge') == 1 ? "checked" : ""?>>
                        <span class="checkmark"></span>
                    </form>
                </label>
            </div>
            <div class=""><?=__("Lorsqu’activée, la cache du serveur sera vidée automatiquement à chaque changement effectué dans une page ou un article.", "");?></div>
        </div>
    </div>
</div>
<script>

// Fade the "success" message once the cache has been purged.
var popupBloc = document.getElementById("wsa-message");
var popupMessage = document.getElementById("wsa-progress");
if (popupBloc !== null) {
    var popupMessageSize = popupBloc.offsetWidth - 4;
    if (popupMessage !== null) {
        popupMessage.style.borderRightWidth = popupMessageSize + "px";
    }
}

function removeDiv(){
    document.getElementById("wsa-message").remove();
}
</script>
