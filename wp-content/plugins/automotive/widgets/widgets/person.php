<?php

class person_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'person-widget',
	'description' => '',
);

parent::__construct( 'person-widget', 'Staff Person', $widget_ops );

    add_action('widgets_init', function(){
      register_widget('person_widget');
    });
  }

    public $args = array(
        'before_title'  => '<h4 class="widgettitle">',
        'after_title'   => '</h4>',
        'before_widget' => '<div class="widget-wrap">',
        'after_widget'  => '</div></div>'
    );

    public function widget( $args, $instance ) {
        echo $args['before_widget'];

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        echo '<div class="person-widget">';


$widget_shortcode = '[person name="' . $instance['name']
 . '" position="' . $instance['position']
 . '" phone="' . $instance['phone']
 . '" cell_phone="' . $instance['cell_phone']
 . '" email="' . $instance['email']
 . '" img="' . mergepress_normalize_image($instance['img'], 'widget')
 . '" hoverimg="' . mergepress_normalize_image($instance['hoverimg'], 'widget')
 . '" facebook="' . $instance['facebook']
 . '" twitter="' . $instance['twitter']
 . '" youtube="' . $instance['youtube']
 . '" vimeo="' . $instance['vimeo']
 . '" linkedin="' . $instance['linkedin']
 . '" rss="' . $instance['rss']
 . '" flickr="' . $instance['flickr']
 . '" skype="' . $instance['skype']
 . '" google="' . $instance['google']
 . '" pinterest="' . $instance['pinterest']
 . '" instagram="' . $instance['instagram']
 . '" yelp="' . $instance['yelp']
 . '" pagebuilder="widget"]' . $instance['content'] . '[/person]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        $name = ! empty( $instance['name'] ) ? $instance['name'] : esc_html__( '', 'MergePress' );
        $position = ! empty( $instance['position'] ) ? $instance['position'] : esc_html__( '', 'MergePress' );
        $phone = ! empty( $instance['phone'] ) ? $instance['phone'] : esc_html__( '', 'MergePress' );
        $cell_phone = ! empty( $instance['cell_phone'] ) ? $instance['cell_phone'] : esc_html__( '', 'MergePress' );
        $email = ! empty( $instance['email'] ) ? $instance['email'] : esc_html__( '', 'MergePress' );
        $img = ! empty( $instance['img'] ) ? $instance['img'] : esc_html__( '', 'MergePress' );
        $hoverimg = ! empty( $instance['hoverimg'] ) ? $instance['hoverimg'] : esc_html__( '', 'MergePress' );
        $content = ! empty( $instance['content'] ) ? $instance['content'] : esc_html__( '', 'MergePress' );
        $facebook = ! empty( $instance['facebook'] ) ? $instance['facebook'] : esc_html__( '', 'MergePress' );
        $twitter = ! empty( $instance['twitter'] ) ? $instance['twitter'] : esc_html__( '', 'MergePress' );
        $youtube = ! empty( $instance['youtube'] ) ? $instance['youtube'] : esc_html__( '', 'MergePress' );
        $vimeo = ! empty( $instance['vimeo'] ) ? $instance['vimeo'] : esc_html__( '', 'MergePress' );
        $linkedin = ! empty( $instance['linkedin'] ) ? $instance['linkedin'] : esc_html__( '', 'MergePress' );
        $rss = ! empty( $instance['rss'] ) ? $instance['rss'] : esc_html__( '', 'MergePress' );
        $flickr = ! empty( $instance['flickr'] ) ? $instance['flickr'] : esc_html__( '', 'MergePress' );
        $skype = ! empty( $instance['skype'] ) ? $instance['skype'] : esc_html__( '', 'MergePress' );
        $google = ! empty( $instance['google'] ) ? $instance['google'] : esc_html__( '', 'MergePress' );
        $pinterest = ! empty( $instance['pinterest'] ) ? $instance['pinterest'] : esc_html__( '', 'MergePress' );
        $instagram = ! empty( $instance['instagram'] ) ? $instance['instagram'] : esc_html__( '', 'MergePress' );
        $yelp = ! empty( $instance['yelp'] ) ? $instance['yelp'] : esc_html__( '', 'MergePress' );
 ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'name' ) ); ?>"><?php echo esc_html__( 'Name:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('name'), 'input_name' => $this->get_field_name('name'), 'data_name' => 'name', 'type' => 'text', 'input_value' => $name) ); ?>
        <p class='widget-help-description'><?php echo __('Name of person', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'position' ) ); ?>"><?php echo esc_html__( 'Position:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('position'), 'input_name' => $this->get_field_name('position'), 'data_name' => 'position', 'type' => 'text', 'input_value' => $position) ); ?>
        <p class='widget-help-description'><?php echo __('Position of person', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>"><?php echo esc_html__( 'Phone:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('phone'), 'input_name' => $this->get_field_name('phone'), 'data_name' => 'phone', 'type' => 'text', 'input_value' => $phone) ); ?>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'cell_phone' ) ); ?>"><?php echo esc_html__( 'Cell Phone:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('cell_phone'), 'input_name' => $this->get_field_name('cell_phone'), 'data_name' => 'cell_phone', 'type' => 'text', 'input_value' => $cell_phone) ); ?>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>"><?php echo esc_html__( 'Email:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('email'), 'input_name' => $this->get_field_name('email'), 'data_name' => 'email', 'type' => 'text', 'input_value' => $email) ); ?>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'img' ) ); ?>"><?php echo esc_html__( 'Image:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_image_field(array('id' => $this->get_field_id('img'), 'input_name' => $this->get_field_name('img'), 'data_name' => 'img', 'input_value' => $img)); ?>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'hoverimg' ) ); ?>"><?php echo esc_html__( 'Larger Image:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_image_field(array('id' => $this->get_field_id('hoverimg'), 'input_name' => $this->get_field_name('hoverimg'), 'data_name' => 'hoverimg', 'input_value' => $hoverimg)); ?>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>"><?php echo esc_html__( 'Description:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_wysiwyg_field(array('id' => $this->get_field_id('content'), 'input_name' => $this->get_field_name('content'), 'data_name' => 'content',  'input_value' => $content)); ?>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>"><?php echo esc_html__( 'Facebook:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('facebook'), 'input_name' => $this->get_field_name('facebook'), 'data_name' => 'facebook', 'type' => 'text', 'input_value' => $facebook) ); ?>
        <p class='widget-help-description'><?php echo __('URL to Facebook profile', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>"><?php echo esc_html__( 'Twitter:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('twitter'), 'input_name' => $this->get_field_name('twitter'), 'data_name' => 'twitter', 'type' => 'text', 'input_value' => $twitter) ); ?>
        <p class='widget-help-description'><?php echo __('URL to Twitter profile', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'youtube' ) ); ?>"><?php echo esc_html__( 'YouTube:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('youtube'), 'input_name' => $this->get_field_name('youtube'), 'data_name' => 'youtube', 'type' => 'text', 'input_value' => $youtube) ); ?>
        <p class='widget-help-description'><?php echo __('URL to YouTube profile', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'vimeo' ) ); ?>"><?php echo esc_html__( 'Linkedin:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('vimeo'), 'input_name' => $this->get_field_name('vimeo'), 'data_name' => 'vimeo', 'type' => 'text', 'input_value' => $vimeo) ); ?>
        <p class='widget-help-description'><?php echo __('URL to Linkedin profile', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>"><?php echo esc_html__( 'Vimeo:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('linkedin'), 'input_name' => $this->get_field_name('linkedin'), 'data_name' => 'linkedin', 'type' => 'text', 'input_value' => $linkedin) ); ?>
        <p class='widget-help-description'><?php echo __('URL to Vimeo profile', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'rss' ) ); ?>"><?php echo esc_html__( 'RSS:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('rss'), 'input_name' => $this->get_field_name('rss'), 'data_name' => 'rss', 'type' => 'text', 'input_value' => $rss) ); ?>
        <p class='widget-help-description'><?php echo __('URL to RSS profile', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'flickr' ) ); ?>"><?php echo esc_html__( 'Flickr:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('flickr'), 'input_name' => $this->get_field_name('flickr'), 'data_name' => 'flickr', 'type' => 'text', 'input_value' => $flickr) ); ?>
        <p class='widget-help-description'><?php echo __('URL to Flickr profile', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'skype' ) ); ?>"><?php echo esc_html__( 'Skype:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('skype'), 'input_name' => $this->get_field_name('skype'), 'data_name' => 'skype', 'type' => 'text', 'input_value' => $skype) ); ?>
        <p class='widget-help-description'><?php echo __('URL to Skype profile', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'google' ) ); ?>"><?php echo esc_html__( 'Google:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('google'), 'input_name' => $this->get_field_name('google'), 'data_name' => 'google', 'type' => 'text', 'input_value' => $google) ); ?>
        <p class='widget-help-description'><?php echo __('URL to Google profile', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'pinterest' ) ); ?>"><?php echo esc_html__( 'Pinterest:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('pinterest'), 'input_name' => $this->get_field_name('pinterest'), 'data_name' => 'pinterest', 'type' => 'text', 'input_value' => $pinterest) ); ?>
        <p class='widget-help-description'><?php echo __('URL to Pinteret profile', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>"><?php echo esc_html__( 'Instagram:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('instagram'), 'input_name' => $this->get_field_name('instagram'), 'data_name' => 'instagram', 'type' => 'text', 'input_value' => $instagram) ); ?>
        <p class='widget-help-description'><?php echo __('URL to Instagram profile', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'yelp' ) ); ?>"><?php echo esc_html__( 'Yelp:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('yelp'), 'input_name' => $this->get_field_name('yelp'), 'data_name' => 'yelp', 'type' => 'text', 'input_value' => $yelp) ); ?>
        <p class='widget-help-description'><?php echo __('URL to Yelp profile', 'MergePress'); ?></p>
        </div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['name'] = ( !empty( $new_instance['name'] ) ) ? $new_instance['name'] : '';
        $instance['position'] = ( !empty( $new_instance['position'] ) ) ? $new_instance['position'] : '';
        $instance['phone'] = ( !empty( $new_instance['phone'] ) ) ? $new_instance['phone'] : '';
        $instance['cell_phone'] = ( !empty( $new_instance['cell_phone'] ) ) ? $new_instance['cell_phone'] : '';
        $instance['email'] = ( !empty( $new_instance['email'] ) ) ? $new_instance['email'] : '';
        $instance['img'] = ( !empty( $new_instance['img'] ) ) ? $new_instance['img'] : '';
        $instance['hoverimg'] = ( !empty( $new_instance['hoverimg'] ) ) ? $new_instance['hoverimg'] : '';
        $instance['content'] = ( !empty( $new_instance['content'] ) ) ? $new_instance['content'] : '';
        $instance['facebook'] = ( !empty( $new_instance['facebook'] ) ) ? $new_instance['facebook'] : '';
        $instance['twitter'] = ( !empty( $new_instance['twitter'] ) ) ? $new_instance['twitter'] : '';
        $instance['youtube'] = ( !empty( $new_instance['youtube'] ) ) ? $new_instance['youtube'] : '';
        $instance['vimeo'] = ( !empty( $new_instance['vimeo'] ) ) ? $new_instance['vimeo'] : '';
        $instance['linkedin'] = ( !empty( $new_instance['linkedin'] ) ) ? $new_instance['linkedin'] : '';
        $instance['rss'] = ( !empty( $new_instance['rss'] ) ) ? $new_instance['rss'] : '';
        $instance['flickr'] = ( !empty( $new_instance['flickr'] ) ) ? $new_instance['flickr'] : '';
        $instance['skype'] = ( !empty( $new_instance['skype'] ) ) ? $new_instance['skype'] : '';
        $instance['google'] = ( !empty( $new_instance['google'] ) ) ? $new_instance['google'] : '';
        $instance['pinterest'] = ( !empty( $new_instance['pinterest'] ) ) ? $new_instance['pinterest'] : '';
        $instance['instagram'] = ( !empty( $new_instance['instagram'] ) ) ? $new_instance['instagram'] : '';
        $instance['yelp'] = ( !empty( $new_instance['yelp'] ) ) ? $new_instance['yelp'] : '';

        return $instance;
    }
}

$person_widget = new person_widget();