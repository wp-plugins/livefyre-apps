<?php
/*
Author: Livefyre, Inc.
Version: 4.2.0
Author URI: http://livefyre.com/
*/

if(!defined('WP_CONTENT_URL')) {
    die('Direct access is forbidden');
}

require_once( dirname( __FILE__ ) . "/Livefyre_Settings.php" );

$livefyre_settings = new Livefyre_Settings();

?>

<script type="text/javascript">
settings_toggle_less = function() {
    var info = document.getElementById('settings_information');
    info.style.display = 'none';
    var button = document.getElementById('settings_toggle_button');
    button.onclick = settings_toggle_more;
    var toggle_text = document.getElementById('settings_toggle_text');
    toggle_text.innerHTML = 'More Info';
}

settings_toggle_more = function() {
    var info = document.getElementById('settings_information');
    info.style.display = 'block';
    var button = document.getElementById('settings_toggle_button');
    button.onclick = settings_toggle_less;
    var toggle_text = document.getElementById('settings_toggle_text');
    toggle_text.innerHTML = 'Less Info';
}
</script>

<div id="fyresettings">
    <div id="fyreheader" style="<?php echo 'background-image: url(' .LF_PLUGIN_URL . '/images/header-bg.png'. ')' ?>">
        <img src="<?php echo LF_PLUGIN_URL . '/images/logo.png'; ?>" rel="Livefyre" style="padding: 5px; padding-left: 15px;" />
    </div>
    <div id="fyrebody">
        <div id="fyrebodycontent">
            <?php
            $bad_plugins = Array();
            $all_bad_plugins = Array(
                    'disqus-comment-system/disqus.php' => 'Disqus: Commenting plugin.',
                    'cloudflare/cloudflare.php' => 'Cloudflare: Impacts the look of the widget on the page.',
                    'spam-free-wordpress/tl-spam-free-wordpress.php' => 'Spam Free: Disables 3rd party commenting widgets.',
            );
            $need_deactivation = false;
            foreach ( $all_bad_plugins as $key => $value ) {
                if ( is_plugin_active( $key ) ) {
                    array_push($bad_plugins, $value);
                }
            }
            if( isset($_GET['allow_comments_id']) ) {
                $allow_id = sanitize_text_field( $_GET['allow_comments_id'] );
                $livefyre_settings->update_posts( $allow_id, false );
            }
            global $wpdb;
            $db_prefix = $wpdb->base_prefix;
            $comments_disabled_posts = $livefyre_settings->select_posts( 'post' );
            $comments_disabled_pages = $livefyre_settings->select_posts( 'page' );
            ?>

            <div id="fyrestatus">
                <?php
                $plugins_count = count($bad_plugins);
                $disabled_posts_count = count($comments_disabled_posts);
                $disabled_pages_count = count($comments_disabled_pages);

                $need_settings = 0;
                if ( $this->ext->get_option( 'livefyre_domain_name', '' ) == ''
                    || $this->ext->get_option( 'livefyre_domain_key', '' ) == ''
                    || $this->ext->get_option( 'livefyre_site_id', '' ) == ''
                    || $this->ext->get_option( 'livefyre_site_key', '' ) == ''
                ) {
                    $need_settings = 1;
                }

                $good_status = ( $disabled_posts_count + $disabled_pages_count + $plugins_count + $need_settings < 1 );
                $bad_status = $plugins_count >= 1 || $need_settings >= 1;
                $status = Array('Warning, potential issues', 'yellow');
                if( $bad_status ) {
                    $status = Array('Error, missing settings', 'red');
                    if ( $plugins_count >= 1 && $need_settings >= 1 ) {
                        $status = Array('Multiple things need attention', 'red');
                    }
                    if ( $plugins_count >= 1 ) {
                        $status = Array('Error, conflicting plugins', 'red');
                    }
                }
                else if ( $good_status ) {
                    $status = Array('All systems go!', 'green');
                }
                echo '<h1><span class="statuscircle' .esc_attr($status[1]). '"></span>Livefyre Status: <span>' .esc_html($status[0]). '</span></h1>';
                echo "<h3>Using your " .esc_html(( 1 == get_option('livefyre_environment', '0') ?  "production" : "development" )). " environment.<h3>";

                $total_errors = ( $plugins_count + /*$disabled_pages_count*/ + $disabled_posts_count + $need_settings);
                if ( $total_errors > 0 ) {
                    echo '<h2>' . esc_html($total_errors . (($total_errors == 1 ) ? ' issue requires' : ' issues require')) . ' your attention, please see below</h2>';
                }
                ?>
            </div>
            <div id="fyrenetworksettings">
                <h1>Livefyre Settings</h1>
                <div id="settings_toggle_button" onclick="settings_toggle_less()" cursor="pointer">
                    <img id="settings_toggle" src="<?php echo LF_PLUGIN_URL . '/images/more-info.png'; ?>" rel="Info">
                    <div id='settings_toggle_text'>Less Info</div>
                </div>
                <div id="settings_information">
                    <form method="post" action="options.php">
                        <?php
                            settings_fields( 'livefyre_site_options' );
                            do_settings_sections( 'livefyre' );
                        ?>
                        <p class="submit">
                            <input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ) ?>" />
                        </p>
                    </form>
                </div>
            </div>
            <div id="fyrepotentials" class="clearfix">
                <div id="fyreconflictplugs">
                    <?php echo '<h1>Conflicting Plugins (' .esc_html($plugins_count). ')</h1>';
                    if ( $plugins_count ) {
                    ?>
                    <p>We found that the following plugins are active on your site, and unfortunately they will conflict with Livefyre Comments 3 and break our widget’s functionality. 
                        To be sure that Comments 3 is running without a hitch, it will be necessary to deactivate the following plugins:</p>
                    <ul>
                    <?php
                        foreach ( $bad_plugins as $plugin ) {
                            $plugin_data = explode( ':', $plugin, 2 );
                            echo '<li><div class="plugincirclered"></div>' .esc_html($plugin_data[0]). ": <span>" .esc_html($plugin_data[1]);?></span></li><?php
                        }
                    ?>
                    </ul>
                    <?php
                    }
                    else {
                        echo '<p>There are no conflicting plugins</p>';
                    }
                ?>
                </div>

                <div id="fyreallowcomments">
                    <?php echo '<h1>Allow Comments Status (' .esc_html(($disabled_posts_count /*+ $disabled_pages_count*/)). ')</h1>';
                    if ( $disabled_posts_count || $disabled_pages_count) {
                        ?>
                        <p>We've automatically found that you do not have the "Allow Comments" box in WordPress checked on the posts and pages listed below, which means that the Livefyre widget will not be present on them. 
                            To be sure that the Livefyre Comments widget is visible on these posts or pages, simply click on the "enable" button next to each.</p>
                        <p>If you'd like to simply close commenting on any post or page with the Livefyre widget still present, you can do so from your Livefyre admin panel by clicking the "Livefyre Admin" link to the right, 
                            clicking "Conversations", and then clicking "Stream Settings."</p>
                        <?php
                        if ( $disabled_posts_count ) {
                            $livefyre_settings->display_no_allows( 'post', $comments_disabled_posts);
                        }
                        // if ( $disabled_pages_count ) {
                        //     $livefyre_settings->display_no_allows( 'page', $comments_disabled_pages);
                        // }
                    }
                    else {
                        echo '<p>There are no posts with comments not allowed</p>';
                    }
                    ?>
                </div>
            </div>

            <div id="fyresidepanel">
                <div id="fyresidesettings">
                    <h1>Network Settings</h1>
                        <p class="lf_label">Livefyre Network: </p>
                        <?php echo '<p class="lf_text">' .esc_html($this->ext->get_option('livefyre_domain_name')). '</p>'; ?>
                        <br />
                        <p class="lf_label">Livefyre Network Key: </p>
                        <?php echo '<p class="lf_text">' .esc_html($this->ext->get_option('livefyre_domain_key')). '</p>'; ?>
                        <br />
                        <p class="lf_label">Livefyre Auth Delegate Name: </p>
                        <?php echo '<p class="lf_text">' .esc_html($this->ext->get_option('livefyre_auth_delegate_name')). '</p>'; ?>
                    <h1>Site Settings</h1>
                        <p class="lf_label">Livefyre Site ID: </p>
                        <?php echo '<p class="lf_text">' .esc_html($this->ext->get_option('livefyre_site_id')). '</p>'; ?>
                        <br />
                        <p class="lf_label">Livefyre Site Key: </p>
                        <?php echo '<p class="lf_text">' .esc_html($this->ext->get_option('livefyre_site_key')). '</p>'; ?>
                    <h1>Links</h1>
                        <a href='http://livefyre.com' target="_blank">Livefyre</a>
                        <br />
                        <a href="http://support.livefyre.com" target="_blank">Livefyre Support</a>
                </div>
                <div id="fyredisplayinfo">
                    <h1>Display Comments</h1>
                    <p class="lf_text">I would like comments displayed on:</p>
                    <?php

                    $excludes = array( '_builtin' => false );
                    $post_types = get_post_types( $args = $excludes );

                    if( isset( $_GET['save_display_settings']) ) {
                        check_admin_referer( 'save_display_settings');
                        if ( isset( $_GET['display_posts'] ) ) {
                            update_option( 'livefyre_display_posts', sanitize_text_field( $_GET['display_posts'] ) );
                        }
                        else {
                            update_option( 'livefyre_display_posts', 'false' );
                        }
                        if ( isset( $_GET['display_pages'] ) ) {
                            update_option( 'livefyre_display_pages', sanitize_text_field( $_GET['display_pages'] ) );
                        }
                        else {
                            update_option( 'livefyre_display_pages', 'false' );
                        }

                        foreach ($post_types as $post_type ) {
                            $post_type_name = 'livefyre_display_' .$post_type;
                            if ( isset( $_GET[$post_type] ) ) {
                                update_option( $post_type_name, sanitize_text_field( $_GET[$post_type] ) );
                            }
                            else {
                                update_option( $post_type_name, 'false' );
                            }
                        }
                    }

                    $posts_checkbox = "";
                    $pages_checkbox = "";
                    if ( get_option('livefyre_display_posts', 'true') == 'true' ) {
                        $posts_checkbox = 'checked="yes"';
                    }
                    if ( get_option('livefyre_display_pages', 'true') == 'true' ) {
                        $pages_checkbox = 'checked="yes"';
                    }
                    
                    ?>
                    <form id="fyredisplayform" action="options-general.php?page=livefyre">
                        <input type="hidden" name="page" value="livefyre" />
                        <input type="checkbox" class="checkbox" name="display_posts" value="true" <?php echo esc_html( $posts_checkbox );?> />Posts<br />
                        <input type="checkbox" class="checkbox" name="display_pages" value="true" <?php echo esc_html( $pages_checkbox );?> />Pages<br />
                        <?php 
                        foreach ($post_types as $post_type ) {
                            $post_type_name = 'livefyre_display_' .$post_type;
                            if ( get_option($post_type_name, 'true') == 'true' ) {
                                $post_type_checkbox = 'checked="yes"';
                            }
                            ?>
                            <input type="checkbox" class="checkbox" name=<?php echo '"' .esc_attr( $post_type ). '"';?> value="true" <?php echo esc_html( $post_type_checkbox );?> /><?php echo esc_html( $post_type ); ?><br />
                            <?php
                        }
                        wp_nonce_field( 'save_display_settings');
                        ?>
                        <input type="submit" class="fyrebutton" name="save_display_settings" value="Submit" />
                    </form>
                </div>
                <div id="fyrelanguages">
                    <?php
                    if( isset( $_GET['lf_language']) ) {
                        check_admin_referer( 'save_languages');
                        update_option( 'livefyre_language', sanitize_text_field( $_GET['lf_language'] ) );
                    }
                    ?>
                    <h1>Languages</h1>
                    <p class="lf_text">I would like my language to be: </p>
                    <form id="fyrelanguagesform" action="options-general.php?page=livefyre">
                        <input type="hidden" name="page" value="livefyre" />
                        <select name="lf_language">
                            <option value="English" <?php echo esc_html( $livefyre_settings->checkSelected('livefyre_language', 'English') ); ?> >English</option>
                            <option value="Spanish" <?php echo esc_html( $livefyre_settings->checkSelected('livefyre_language', 'Spanish') ); ?> >Spanish</option>
                            <option value="French" <?php echo esc_html( $livefyre_settings->checkSelected('livefyre_language', 'French') ); ?> >French</option>
                            <option value="Portuguese" <?php echo esc_html( $livefyre_settings->checkSelected('livefyre_language', 'Portuguese') ); ?> >Portuguese</option>
                        </select><br />
                        <?php wp_nonce_field( 'save_languages'); ?>
                        <input type="submit" class="fyrebutton" name="save_languages" value="Submit" />
                    </form><br>
                    <p class="lf_text">Note: If you are implementing your own custom strings, the selection in this section will be overwritten.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    <?php wp_enqueue_style("livefyre-e-css", LF_PLUGIN_URL . '/src/admin/settings-template.css' );  ?>
</style>
