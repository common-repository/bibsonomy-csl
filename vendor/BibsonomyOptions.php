<?php
/*
    This file is part of BibSonomy/PUMA CSL for WordPress.

    BibSonomy/PUMA CSL for WordPress is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    BibSonomy/PUMA CSL for WordPress is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with BibSonomy/PUMA CSL for WordPress.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Description of BibsonomyOptions
 *
 * @author Sebastian Böttger
 */
class BibsonomyOptions
{

    public function bibsonomy_add_settings_page()
    {
        add_options_page('Bibsonomy CSL', 'Bibsonomy CSL', 'manage_options', 'bibsonomy_csl', array(&$this, 'bibsonomy_options_page'));
        add_action('admin_init', array(&$this, 'bibsonomy_admin_init'));
    }

    public function bibsonomy_admin_init()
    {

        //register settings
        register_setting(
            'bibsonomy_options',
            'bibsonomy_options',
            array(&$this, 'bibsonomy_validate_options')
        );

        //add setting sections
        add_settings_section(
            'bibsonomy_main',
            'Bibsonomy API settings',
            array(&$this, 'bibsonomy_section_text'),
            'bibsonomy'
        );

        //adds bibsonomy host selection
        add_settings_field(
            'bibsonomy_select_hostselection',
            'Choose your preferred Host System here',
            array(&$this, 'bibsonomy_setting_hostselection'),
            'bibsonomy',
            'bibsonomy_main'
        );

        //adds user id field
        add_settings_field(
            'bibsonomy_text_user',
            'Enter your BibSonomy/PUMA user ID here',
            array(&$this, 'bibsonomy_setting_user'),
            'bibsonomy',
            'bibsonomy_main'
        );

        //adds user API key field
        add_settings_field(
            'bibsonomy_text_apikey',
            'Enter your BibSonomy API key here',
            array(&$this, 'bibsonomy_setting_apikey'),
            'bibsonomy',
            'bibsonomy_main'
        );

    }

    public function bibsonomy_setting_hostselection()
    {
        $options = get_option('bibsonomy_options');
        $hostselection = $options['hostselection'];
        $response = wp_remote_get('https://www.bibsonomy.org/resources_puma/addons/list.json');
        $i = 0;
        $hosts[$i] = array('BibSonomy', 'https://www.bibsonomy.org/');
        if (is_array($response) && !is_wp_error($response)) {
            $headers = $response['headers']; // array of http header lines
            $body = $response['body']; // use the content
            $serverList = json_decode($body);
            foreach ($serverList->server as $server) {
                $hosts[++$i] = array($server->instanceName, $server->instanceUrl);
            }
        }
        echo '<select id="hostselection" name="bibsonomy_options[hostselection]">';
        foreach ($hosts as $host) {
            if ($hostselection === $host[1]) {
                echo '<option value="' . $host[1] . '" selected>' . $host[0] . '</option>';
            } else {
                echo '<option value="' . $host[1] . '">' . $host[0] . '</option>';
            }
        }
        echo '</select>';
    }

    public function bibsonomy_setting_bibsonomyhost()
    {
        $options = get_option('bibsonomy_options');
        $bibsonomyhost = $options['bibsonomyhost'];
        $host = (isset($bibsonomyhost) && !empty($bibsonomyhost) ? $bibsonomyhost : "https://www.bibsonomy.org");
        echo '<input type="text" id="bibsonomyhost" name="bibsonomy_options[bibsonomyhost]" value="' . $host . '"/>';
    }

    public function bibsonomy_setting_user()
    {
        $options = get_option('bibsonomy_options');
        $user = $options['user'];
        echo '<input type="text" id="userid" name="bibsonomy_options[user]" value="' . $user . '" />';
    }

    public function bibsonomy_setting_apikey()
    {
        $options = get_option('bibsonomy_options');
        $apikey = $options['apikey'];
        echo '<input type="text" id="apikey" name="bibsonomy_options[apikey]" value="' . $apikey . '" />';
    }


    public function bibsonomy_section_text()
    {
        echo '<p>Enter your BibSonomy API settings.</p>';
    }


    public function bibsonomy_options_page()
    {
        ?>
        <div class="wrap">
            <h2>Bibsonomy CSL</h2>
            <form action="options.php" method="post">
                <?php settings_fields('bibsonomy_options'); ?>
                <?php do_settings_sections('bibsonomy'); ?>
                <input name="submit" type="submit" value="Save changes"/>
            </form>
        </div>

        <?php
    }

    public function bibsonomy_validate_options($input)
    {

        $valid = array();
        $valid['user'] = $input['user'];
        $valid['apikey'] = $input['apikey'];
        $valid['hostselection'] = $input['hostselection'];

        return $valid;
    }


}

