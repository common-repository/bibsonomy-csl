<?php
/*
  Plugin Name: BibSonomy/PUMA CSL - Publications & Tag Cloud Widget
  Plugin URI: https://www.bibsonomy.org/help_en/wordpress
  Description: Plugin to create tag clouds from BibSonomy or PUMA.
  Author: Kevin Choong
  Author URI: https://www.academic-puma.de
  Version: 2.2.1
 */

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

use AcademicPuma\RestClient\Authentication\BasicAuthAccessor;
use AcademicPuma\RestClient\RESTClient;

require_once 'lib/bibsonomy/BibsonomyAPI.php';

/**
 * Description of BibsonomyTagWidget
 *
 * @author Sebastian Böttger
 */
class BibsonomyTagCloudWidget extends WP_Widget
{

    /**
     * Register widget with WordPress.
     */
    public function __construct()
    {
        parent::__construct(
            'bibsonomy_tag_cloud_widget', // Base ID
            'Bibsonomy Tag Cloud', // Name
            array('description' => __('Widget to generate BibSonomy Tag Cloud', 'text_domain'),) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {

        extract($args);
        $header = '<h3 class="widget-title">' . $instance['header'] . '</h3>';
        $type = $instance['type'];
        $value = $instance['value'];
        $layout = $instance['layout'];
        $end = $instance['end'];
        echo $before_widget;
        if (!empty($type) && !empty($value)) {
            echo "<h2>$header</h2>";
            echo $this->renderTagCloud($type, $value, $layout, $end);
        }
        echo $after_widget;
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $header = ($instance['header'] ?? __('', 'text_domain'));
        $type = ($instance['type'] ?? __('user', 'text_domain'));
        $value = ($instance['value'] ?? __('', 'text_domain'));
        $layout = ($instance['layout'] ?? __('simple', 'text_domain'));
        $end = ($instance['end'] ?? __('', 'text_domain'));

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('header'); ?>">Headline</label>
            <input
                    id="<?php echo $this->get_field_id('header'); ?>"
                    name="<?php echo $this->get_field_name('header'); ?>"
                    type="text"
                    value="<?php echo esc_attr($header); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('type'); ?>">Bibsonomy source type</label>
            <select
                    id="<?php echo $this->get_field_id('type'); ?>"
                    name="<?php echo $this->get_field_name('type'); ?>"
            >
                <option value="user" <?php echo ($type == 'user') ? 'selected="selected"' : '' ?>>user
                </option>
                <option value="group" <?php echo ($type == 'group') ? 'selected="selected"' : '' ?>>group
                </option>
                <option value="viewable" <?php echo ($type == 'viewable') ? 'selected="selected"' : '' ?>>
                    viewable
                </option>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('value'); ?>">BibSonomy source type value</label>
            <input
                    id="<?php echo $this->get_field_id('value'); ?>"
                    name="<?php echo $this->get_field_name('value'); ?>"
                    type="text"
                    value="<?php echo esc_attr($value); ?>"/>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('end'); ?>">Number of tags</label>
            <input
                    id="<?php echo $this->get_field_id('end'); ?>"
                    name="<?php echo $this->get_field_name('end'); ?>"
                    type="text"
                    value="<?php echo (!empty($end)) ? esc_attr($end) : 30; ?>"/>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('layout'); ?>">BibSonomy Tag Cloud Layout</label>
            <select
                    id="<?php echo $this->get_field_id('layout'); ?>"
                    name="<?php echo $this->get_field_name('layout') ?>">
                <option value="simple" <?php echo ($layout == 'simple') ? 'selected="selected"' : '' ?>>
                    Simple
                </option>
                <option value="decorated" <?php echo ($layout == 'decorated') ? 'selected="selected"' : '' ?>>
                    Decorated
                </option>
                <option value="button" <?php echo ($layout == 'button') ? 'selected="selected"' : '' ?>>
                    Button Style
                </option>
            </select>
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['header'] = ( !empty( $new_instance['header'] ) ) ? strip_tags( $new_instance['header'] ) : '';
        $instance['type'] = ( !empty( $new_instance['type'] ) ) ? strip_tags( $new_instance['type'] ) : '';
        $instance['value'] = ( !empty( $new_instance['value'] ) ) ? strip_tags( $new_instance['value'] ) : '';
        $instance['layout'] = ( !empty( $new_instance['layout'] ) ) ? strip_tags( $new_instance['layout'] ) : '';
        $instance['end'] = ( !empty( $new_instance['end'] ) ) ? strip_tags( $new_instance['end'] ) : '';

        return $instance;
    }

    public function renderTagCloud($type, $value, $layout, $end)
    {

        //print_r($layout);

        switch ($layout) {

            case 'decorated':
                return $this->decoratedTagCloud($type, $value, $end);

            case 'button':
                return $this->buttonstyleTagCloud($type, $value, $end);

            case 'simple':
            default:
                return $this->simpleTagCloud($type, $value, $end);
        }
    }

    public function fetchTags($type, $value, $end = 30)
    {
        global $BIBSONOMY_OPTIONS;

        $accessor = new BasicAuthAccessor($BIBSONOMY_OPTIONS['hostselection'], $BIBSONOMY_OPTIONS['user'], $BIBSONOMY_OPTIONS['apikey']);
        $restclient = new RESTClient($accessor, ['verify' => true]);
        $restclient->getTags($type, $value, '', 'frequency', 0, $end);
        $tagList = $restclient->model()->toArray();

        return $tagList;
    }

    /**
     *
     * @param string $type
     * @param string $value
     * @param string $end
     * @return string rendered TagCloud
     */
    public function simpleTagCloud($type, $value, $end)
    {
        global $BIBSONOMY_OPTIONS;
        $tags = $this->fetchTags($type, $value, $end);

        $maxcount = $tags[0]->getUsercount();
        $out = array();

        foreach ($tags as $tag) {

            $max = 2;
            $min = 0.7;

            $count = $tag->getUsercount();

            $size = ($count / $maxcount) * ($max - $min) + $min;

            $out[$tag->getName()] = '<a href="' . $BIBSONOMY_OPTIONS['hostselection'] . '/' . $type . '/' . $value . '/' . urlencode($tag->getName()) . '" target="_blank" style="font-size: ' . sprintf("%01.2f", $size) . 'em;">' . $tag->getName() . '</a> ';
        }
        sort($out);
        $str = '<div class="bibsonomycsl_tagcloud bibsonomycsl_tagcloud_simplestyle">';

        foreach ($out as $key => $val) {
            $str .= $val;
        }
        $str .= '<p style="clear:left;"><!-- --></p>';
        $str .= '</div>';

        return $str;
    }

    public function decoratedTagCloud($type, $value, $end)
    {
        global $BIBSONOMY_OPTIONS;
        $tags = $this->fetchTags($type, $value, $end);

        //$maxcount = $json->tags->tag[0]->usercount;
        $out = array();

        $path_l = plugins_url('/bibsonomy-csl/img/bg_tag_left.png');
        $path_r = plugins_url('/bibsonomy-csl/img/bg_tag_right.png');

        foreach ($tags as $tag) {
            $out[$tag->getName()] = '<span><a href="' . $BIBSONOMY_OPTIONS['hostselection'] . '/' . $type . '/' . $value . '/' . urlencode($tag->getName()) . '" target="_blank">' . $tag->getName() . '</a><span>&nbsp;</span></span>';
        }

        sort($out);
        $str = '<div class="bibsonomycsl_tagcloud bibsonomycsl_tagcloud_decoratedstyle">';

        foreach ($out as $key => $val) {
            $str .= $val;
        }

        $str .= '<p style="clear:left;"><!-- --></p>';
        $str .= '</div>';

        return $str;
    }

    public function buttonstyleTagCloud($type, $value, $end)
    {
        global $BIBSONOMY_OPTIONS;
        $tags = $this->fetchTags($type, $value, $end);
        $first_tag = $tags[0];
        $maxcount = $first_tag->getUsercount();
        $out = array();

        foreach ($tags as $tag) {
            $min = 10;
            $max = 15;

            $href = $tag->getHref();

            // Temporary fix for REST-API returning wrong tag-links currently
            if (strpos($href, "/api/tags/") > 0) {
                $href = substr($href, strpos($href, "/api/tags/") + 10);
                $href = "https://www.bibsonomy.org/tag/" . $href;
            }
            $count = $tag->getUsercount();

            $size = ($count / $maxcount) * ($max - $min) + $min;
            $size = ceil($size);
            $weight = ceil(($count / $maxcount) * 8 + 1) * 100;
            $color = ceil(($count / $maxcount) * 10 + 3);
            $color = dechex(16 - $color);
            $color = $color . $color . $color;

            $out[$tag->getName()] = '<a href="' . $href . '" target="_blank" style="
                    color: \#' . $color . ';
					font-size: ' . $size . 'px;
					font-weight: ' . $weight . '"
				>' . $tag->getName() . '</a> ';
        }
        sort($out);
        $str = '<div class="bibsonomycsl_tagcloud bibsonomycsl_tagcloud_buttonstyle">';

        foreach ($out as $key => $val) {
            $str .= $val;
        }
        $str .= '<p style="clear:left;"><!-- --></p>';
        $str .= '</div>';
        return $str;
    }
}

add_action('widgets_init', function () {
    return register_widget('BibsonomyTagCloudWidget');
});

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('bibsonomycsl-tagcloud', plugins_url('', __FILE__) . '/css/bibsonomycsl-tagcloud.css');
});
