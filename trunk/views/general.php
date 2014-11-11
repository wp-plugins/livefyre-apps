<style>
    <?php if(Livefyre_Apps::get_option('package_type') === 'community'): ?>
    .enterprise-only {display: none;}
    <?php else: ?>
    .community-only {display: none;}
    <?php endif; ?>
</style>
<div id="lfapps-general-metabox-holder" class="metabox-holder clearfix">
    <?php wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false);
    wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false);
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            jQuery('.if-js-closed').removeClass('if-js-closed').addClass('closed');
            if (typeof postboxes !== 'undefined')
                postboxes.add_postbox_toggles('plugins_page_livefyre_apps');
        });
    </script>    
    
    <div class='postbox-large'>
        <div class="postbox-container">
            <div id="normal-sortables" class="meta-box-sortables ui-sortable">                
                <div id="referrers" class="postbox">
                    <div class="handlediv" title="Click to toggle"><br></div>
                    <h3 class="hndle"><span><?php esc_html_e('Livefyre Access Details', 'lfapps'); ?></span></h3>
                    <form name="livefyre_apps_general" id="livefyre_apps_general" action="<?php echo esc_url(Livefyre_Apps_Admin::get_page_url('livefyre_apps')); ?>" method="POST">
                        <?php wp_nonce_field( 'form-livefyre_apps_general' ); ?>
                        <input type="hidden" id="package_type" name="package_type" value="<?php echo esc_attr(Livefyre_Apps::get_option('package_type', 'community')); ?>"/>
                        <div class='inside'>
                            <table cellspacing="0" class="lfapps-form-table <?php echo Livefyre_Apps::get_option('package_type') === 'community' ? 'lfapps-form-table-left' : ''; ?>">
                                <tbody>                      
                                    <tr>
                                        <th align="left" scope="row"><?php esc_html_e('Site ID', 'lfapps'); ?></th>
                                        <td align="left">
                                            <input id="livefyre_site_id" name="livefyre_site_id" type="text" size="15" value="<?php echo esc_attr(Livefyre_Apps::get_option('livefyre_site_id')); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th align="left" scope="row"><?php esc_html_e('Site Key', 'lfapps'); ?></th>
                                        <td align="left">
                                            <input id="livefyre_site_key" name="livefyre_site_key" type="text" value="<?php echo esc_attr(Livefyre_Apps::get_option('livefyre_site_key')); ?>" class='regular-text'>
                                        </td>
                                    </tr>                                    
                                    <tr class="enterprise-only">
                                        <th align="left" scope="row"><?php esc_html_e('Network Name', 'lfapps'); ?></th>
                                        <td align="left">
                                            <input id="livefyre_domain_name" name="livefyre_domain_name" type="text" value="<?php echo esc_attr(Livefyre_Apps::get_option('livefyre_domain_name')); ?>" class='regular-text'>
                                        </td>
                                    </tr>
                                    <tr class="enterprise-only">
                                        <th align="left" scope="row"><?php esc_html_e('Network Key', 'lfapps'); ?></th>
                                        <td align="left">
                                            <input id="livefyre_domain_key" name="livefyre_domain_key" type="text" value="<?php echo esc_attr(Livefyre_Apps::get_option('livefyre_domain_key')); ?>" class='regular-text'>
                                        </td>
                                    </tr>
                                    <tr class="enterprise-only">
                                        <th align="left" scope="row"><?php esc_html_e('User Auth Type', 'lfapps'); ?></th>
                                        <td align="left" class="spacer">
                                            <input id="wp_auth_type_wordpress" name="auth_type" type="radio" value="wordpress" <?php echo Livefyre_Apps::get_option('auth_type') === 'wordpress' ? 'checked' : ''; ?>>
                                            <label for='wp_auth_type_wordpress'><?php esc_html_e('Native Wordpress', 'lfapps'); ?></label>
                                            <input id="wp_auth_type_custom" name="auth_type" type="radio" value="custom" <?php echo Livefyre_Apps::get_option('auth_type') === 'custom' ? 'checked' : ''; ?>>
                                            <label for='wp_auth_type_custom'><?php esc_html_e('Custom', 'lfapps'); ?></label>
                                            <input id="wp_auth_type_delegate" name="auth_type" type="radio" value="auth_delegate" <?php echo Livefyre_Apps::get_option('auth_type') === 'auth_delegate' ? 'checked' : ''; ?>>
                                            <label for='wp_auth_type_delegate'><?php esc_html_e('Legacy Delegate', 'lfapps'); ?></label>
                                        </td>
                                    </tr>
                                    <tr class="enterprise-only authdelegate-only">
                                        <th align="left" scope="row"><?php esc_html_e('AuthDelegate Name', 'lfapps'); ?></th>
                                        <td align="left">
                                            <input id="livefyre_auth_delegate_name" name="livefyre_auth_delegate_name" type="text" value="<?php echo esc_attr(Livefyre_Apps::get_option('livefyre_auth_delegate_name')); ?>" class='regular-text'>
                                        </td>
                                    </tr>
                                    <tr class="enterprise-only">
                                        <th align="left" scope="row"><?php esc_html_e('Environment', 'lfapps'); ?></th>
                                        <td align="left">
                                            <input id="livefyre_environment" name="livefyre_environment" type="checkbox" value="production" <?php echo Livefyre_Apps::get_option('livefyre_environment') == 'production' ? 'checked' : ''; ?>>
                                            <label for="livefyre_environment"><?php esc_html_e('Check this if you are using Production Credentials', 'lfapps'); ?></label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <?php if(Livefyre_Apps::get_option('package_type') === 'community'): ?>
                            <div class="lfapps-community-signup">
                                <p><?php esc_html_e('New to Livefyre or forgotten your Site ID/Key?', 'lfapps'); ?><br/>
                                    <a href="http://livefyre.com/installation/logout/?site_url=<?php echo urlencode(home_url())?>&domain=rooms.livefyre.com&version=4&type=wordpress&lfversion=apps&postback_hook=<?php urlencode(home_url())?>&transport=http" target="_blank"><?php esc_html_e('Click here', 'lfapps'); ?></a> and we can help!</p>
                            </div>
                            <div class="clear"></div>
                            <?php endif; ?>
                        </div>
                        <div id="major-publishing-actions">									
                            <div id="publishing-action">
                                <input type="hidden" name="livefyre_app_general" value=""/> 
                                <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_html_e('Save Changes'); ?>">
                            </div>
                            <div class="clear"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="postbox-container">
            <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                <div id="referrers" class="postbox ">
                    <div class="handlediv" title="Click to toggle"><br></div>
                    <h3 class="hndle"><span><?php esc_html_e('Livefyre App Management', 'lfapps'); ?></span></h3>        
                    <form name="livefyre_apps_management" id="livefyre_apps_management" action="<?php echo esc_url(Livefyre_Apps_Admin::get_page_url('livefyre_apps')); ?>" method="POST">
                        <?php wp_nonce_field( 'form-livefyre_apps_management' ); ?>
                        <div class='inside'>
                            <p><?php esc_html_e('Using the options below you can enable/disable the Livefyre Apps available to you.', 'lfapps'); ?></p>
                            <div class='lfapps-appmgt-row clearfix'>
                                <div class='lfapps-appmgt-box'>                                    
                                    <label for='lfapps_comments_enable'>
                                        <?php
                                        $icon_src = Livefyre_Apps::is_app_enabled('comments') ? 'lf-comments-icon.png' : 'lf-comments-icon-grey.png';
                                        ?>
                                        <img id="lfapps_comments_icon" src="<?php echo LFAPPS__PLUGIN_URL . 'assets/img/' . $icon_src; ?>"/>                                        
                                    </label> 
                                    <div class="lfapps-appmgt-controls">
                                        <input id="lfapps_comments_enable" name="lfapps_comments_enable" type="checkbox" value="true" <?php echo Livefyre_Apps::is_app_enabled('comments') ? 'checked' : ''; ?>>
                                        <label for='lfapps_comments_enable'>
                                            <span><?php esc_html_e('LiveComments™', 'lfapps'); ?></span>                                     
                                        </label>
                                        <p><a target="_blank" href="http://web.livefyre.com/comments/">Click here</a> for more information.</p>
                                    </div>
                                </div>
                                <div class='lfapps-appmgt-box'>                                    
                                    <label for='lfapps_sidenotes_enable'> 
                                        <?php
                                        $icon_src = Livefyre_Apps::is_app_enabled('sidenotes') ? 'lf-sidenotes-icon.png' : 'lf-sidenotes-icon-grey.png';
                                        ?>
                                        <img id="lfapps_sidenotes_icon" src="<?php echo LFAPPS__PLUGIN_URL . 'assets/img/' . $icon_src; ?>"/>                                        
                                    </label>
                                    <div class="lfapps-appmgt-controls">
                                        <input id="lfapps_sidenotes_enable" name="lfapps_sidenotes_enable" type="checkbox" value="true" <?php echo Livefyre_Apps::is_app_enabled('sidenotes') ? 'checked' : ''; ?>>
                                        <label for='lfapps_sidenotes_enable'>                                        
                                            <span><?php esc_html_e('Sidenotes™', 'lfapps'); ?></span>
                                        </label>
                                        <p><a target="_blank" href="http://web.livefyre.com/streamhub/#liveSidenotes">Click here</a> for more information.</p>
                                    </div>
                                </div>
                                <div class='lfapps-appmgt-box enterprise-only'>                                    
                                    <label for='lfapps_blog_enable'> 
                                        <?php
                                        $icon_src = Livefyre_Apps::is_app_enabled('blog') ? 'lf-blog-icon.png' : 'lf-blog-icon-grey.png';
                                        ?>
                                        <img id="lfapps_blog_icon" src="<?php echo LFAPPS__PLUGIN_URL . 'assets/img/' . $icon_src; ?>"/>                                        
                                    </label>
                                    <div class="lfapps-appmgt-controls">
                                        <input id="lfapps_blog_enable" name="lfapps_blog_enable" type="checkbox" value="true" <?php echo Livefyre_Apps::is_app_enabled('blog') ? 'checked' : ''; ?>>
                                        <label for='lfapps_blog_enable'>                                        
                                            <span><?php esc_html_e('LiveBlog™', 'lfapps'); ?></span>
                                        </label>
                                        <p><a target="_blank" href="http://web.livefyre.com/streamhub/#liveBlog">Click here</a> for more information.</p>
                                    </div>
                                </div>
                                <div class='lfapps-appmgt-box enterprise-only'>                                    
                                    <label for='lfapps_chat_enable'> 
                                        <?php
                                        $icon_src = Livefyre_Apps::is_app_enabled('chat') ? 'lf-chat-icon.png' : 'lf-chat-icon-grey.png';
                                        ?>
                                        <img id="lfapps_chat_icon" src="<?php echo LFAPPS__PLUGIN_URL . 'assets/img/' . $icon_src; ?>"/>                                        
                                    </label>
                                    <div class="lfapps-appmgt-controls">
                                        <input id="lfapps_chat_enable" name="lfapps_chat_enable" type="checkbox" value="true" <?php echo Livefyre_Apps::is_app_enabled('chat') ? 'checked' : ''; ?>>
                                        <label for='lfapps_chat_enable'>                                        
                                            <span><?php esc_html_e('LiveChat™', 'lfapps'); ?></span>
                                        </label>
                                        <p><a target="_blank" href="http://web.livefyre.com/streamhub/#liveChat">Click here</a> for more information.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="major-publishing-actions">									
                            <div id="publishing-action">
                                <input type="hidden" name="livefyre_app_management" value=""/>
                                <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
                            </div>
                            <div class="clear"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class='postbox-side'>
        <div class="postbox-container lfapps-environment-container">
            <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                <div id="referrers" class="postbox ">
                    <div class="handlediv" title="Click to toggle"><br></div>
                    <h3 class="hndle"><span><?php esc_html_e('Environment Type', 'lfapps'); ?></span></h3>                   
                    
                    <div class='inside'>
                        <p><?php esc_html_e('You are currently using:', 'lfapps'); ?></p>
                        <?php if(Livefyre_Apps::get_option('package_type') === 'community'): ?>
                        <span class="lfapps-community"><?php esc_html_e('Community', 'lfapps'); ?></span>
                        <?php else: ?>
                        <span class="lfapps-enterprise"><?php esc_html_e('Enterprise', 'lfapps'); ?></span>
                        <?php endif; ?>       
                        (<a href="#" class="lfapps-change-env-btn"><?php esc_html_e('Change?', 'lfapps'); ?></a>)
                    </div>
                </div>
            </div>
        </div>
        <div class="postbox-container lfapps-links">
            <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                <div id="referrers" class="postbox ">
                    <div class="handlediv" title="Click to toggle"><br></div>
                    <h3 class="hndle"><span><?php esc_html_e('Links', 'lfapps'); ?></span></h3>
                    <div class='inside'>
                        <a href="http://livefyre.com/admin" target="_blank">Livefyre Admin</a>
                        <br/>
                        <a href="http://support.livefyre.com" target="_blank">Livefyre Support</a>
                    </div>
                </div>
            </div>
        </div>
        <?php /*
        <div class="postbox-container">
            <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                <div id="referrers" class="postbox ">
                    <div class="handlediv" title="Click to toggle"><br></div>
                    <h3 class="hndle"><span><?php esc_html_e('Language', 'lfapps'); ?></span></h3>
                    <form name="livefyre_language" id="livefyre_language" action="<?php echo esc_url(Livefyre_Apps_Admin::get_page_url('livefyre_apps')); ?>" method="POST">
                        <?php wp_nonce_field( 'form-livefyre_language' ); ?>
                        <div class='inside'>
                            <p><?php esc_html_e('I would like my language to be:', 'lfapps'); ?></p>
                            <select id='lf_language' name='lf_language'>
                                <?php foreach(Livefyre_Apps::$languages as $lang_index=>$lang_name): ?>
                                <?php $selected = Livefyre_Apps::get_option('livefyre_language') === $lang_index ? 'selected="selected"' : ''; ?>
                                <option value='<?php echo esc_attr($lang_index); ?>' <?php echo esc_attr($selected); ?>><?php echo esc_html($lang_name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div id="major-publishing-actions">									
                            <div id="publishing-action">
                                <input type="hidden" name="livefyre_language" value=""/> 
                                <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_html_e('Save Changes'); ?>">
                            </div>
                            <div class="clear"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        */ ?>
    </div>        
</div>

<?php add_thickbox(); ?>

<?php if(!Livefyre_Apps::get_option('initial_modal_shown', false)): ?>
<script>
    jQuery(document).ready(function(){
        tb_show("","#TB_inline?inlineId=lfapps-initial-modal&width=680&height=310");        
    });
</script>
<?php endif; ?>
<div id='lfapps-initial-modal' style='display:none'>
    <?php LFAPPS_View::render_partial('initial_modal'); ?>
</div>