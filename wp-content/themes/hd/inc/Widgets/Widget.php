<?php

namespace Webhd\Widgets;

\defined( '\WPINC' ) || die;

use Webhd\Helpers\Cast;
use Webhd\Helpers\Str;

abstract class Widget extends \WP_Widget {

	/**
	 * @var string
	 */
	protected string $prefix = 'w-';

    /**
     * @var string
     */
    protected string $widget_id;

    /**
     * @var string
     */
    protected string $widget_classname;

    /**
     * @var string
     */
    protected string $widget_name = 'Unknown Widget';

    /**
     * @var string
     */
    protected string $widget_description = '';

    /**
     * @var array
     */
    protected array $settings;

    /**
     * __construct
     */
	public function __construct()
    {
		$className = ( new \ReflectionClass( $this ) )->getShortName();
        $this->widget_classname = str_replace( [ '_Widget', 'Widget' ], '', $className );
        $this->widget_id = $this->prefix . Str::dashCase( $className );

		parent::__construct( $this->widget_id, $this->widget_name, $this->widget_options() );

        add_action( 'save_post', [ &$this, 'flush_widget_cache' ] );
        add_action( 'deleted_post', [ &$this, 'flush_widget_cache' ] );
        add_action( 'switch_theme', [ &$this, 'flush_widget_cache' ] );
	}

	/**
	 * @param $id
	 *
	 * @return object|null
	 */
	protected function acfFields( $id ): ?object {
		if ( ! class_exists( '\ACF' ) ) {
			return null;
		}

		return Cast::toObject( get_fields( $id ) );
	}

	/**
	 * @return array
	 */
	protected function widget_options() {
		return [
            'classname'                   => $this->widget_classname,
			'description'                 => $this->widget_description,
			'customize_selective_refresh' => true,
			'show_instance_in_rest'       => true,
		];
	}

    /**
     * Flush the cache
     * @return void
     */
    public function flush_widget_cache() {
        foreach ( [ 'https', 'http' ] as $scheme ) {
            wp_cache_delete( $this->get_widget_id_for_cache( $this->widget_id, $scheme ), 'widget' );
        }
    }

    /**
     * @param $widget_id
     * @param string $scheme
     * @return mixed|void
     */
    protected function get_widget_id_for_cache($widget_id, string $scheme = '' ) {
        if ( $scheme ) {
            $widget_id_for_cache = $widget_id . '-' . $scheme;
        } else {
            $widget_id_for_cache = $widget_id . '-' . ( is_ssl() ? 'https' : 'http' );
        }

        return apply_filters( 'w_cached_widget_id', $widget_id_for_cache );
    }

    /**
     * Cache the widget
     *
     * @param  array  $args Arguments
     * @param  string $content Content
     * @return string the content that was cached
     */
    public function cache_widget( $args, $content )
    {
        // Don't set any cache if widget_id doesn't exist
        if ( empty( $args['widget_id'] ) ) {
            return $content;
        }

        $cache = wp_cache_get( $this->get_widget_id_for_cache( $this->widget_id ), 'widget' );
        if ( ! is_array( $cache ) ) {
            $cache = array();
        }

        $cache[ $this->get_widget_id_for_cache( $args['widget_id'] ) ] = $content;
        wp_cache_set( $this->get_widget_id_for_cache( $this->widget_id ), $cache, 'widget' );

        return $content;
    }

    /**
     * Get cached widget
     *
     * @param  array $args Arguments
     * @return bool true if the widget is cached otherwise false
     */
    public function get_cached_widget( $args )
    {
        // Don't get cache if widget_id doesn't exists
        if ( empty( $args['widget_id'] ) ) {
            return false;
        }

        $cache = wp_cache_get( $this->get_widget_id_for_cache( $this->widget_id ), 'widget' );
        if ( ! is_array( $cache ) ) {
            $cache = array();
        }

        if ( isset( $cache[ $this->get_widget_id_for_cache( $args['widget_id'] ) ] ) ) {
            echo $cache[ $this->get_widget_id_for_cache( $args['widget_id'] ) ]; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
            return true;
        }

        return false;
    }

    /**
     * @param array $instance Array of instance options.
     * @return string
     */
    protected function get_instance_title( $instance ) {
        if ( isset( $instance['title'] ) ) {
            return $instance['title'];
        }

        if ( isset( $this->settings, $this->settings['title'], $this->settings['title']['std'] ) ) {
            return $this->settings['title']['std'];
        }

        return '';
    }

    /**
     * @param $new_instance
     * @param $old_instance
     * @return array
     */
    public function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;
        if ( empty( $this->settings ) ) {
            return $instance;
        }

        // Loop settings and get values to save
        foreach ( $this->settings as $key => $setting ) {
            if ( ! isset( $setting['type'] ) ) {
                continue;
            }

            // Format the value based on settings type.
            switch ( $setting['type'] ) {
                case 'number':
                    $instance[ $key ] = absint( $new_instance[ $key ] );

                    if ( isset( $setting['min'] ) && '' !== $setting['min'] ) {
                        $instance[ $key ] = max( $instance[ $key ], $setting['min'] );
                    }

                    if ( isset( $setting['max'] ) && '' !== $setting['max'] ) {
                        $instance[ $key ] = min( $instance[ $key ], $setting['max'] );
                    }
                    break;
                case 'textarea':
                    $instance[ $key ] = wp_kses( trim( wp_unslash( $new_instance[ $key ] ) ), wp_kses_allowed_html( 'post' ) );
                    break;
                case 'checkbox':
                    $instance[ $key ] = empty( $new_instance[ $key ] ) ? 0 : 1;
                    break;
                default:
                    $instance[ $key ] = isset( $new_instance[ $key ] ) ? sanitize_text_field( $new_instance[ $key ] ) : $setting['std'];
                    break;
            }

            /**
             * Sanitize the value of a setting.
             */
            $instance[ $key ] = apply_filters( 'w_widget_settings_sanitize_option', $instance[ $key ], $new_instance, $key, $setting );
        }

        $this->flush_widget_cache();
        return $instance;
    }

    /**
     * @param $instance
     * @return void
     */
    public function form( $instance )
    {
        if ( empty( $this->settings ) ) {
            return;
        }

        foreach ( $this->settings as $key => $setting ) {

            $class = $setting['class'] ?? '';
            $value = $instance[$key] ?? $setting['std'];

            switch ( $setting['type'] ) {
                case 'text':
                    ?>
                    <p>
                        <label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo wp_kses_post( $setting['label'] ); ?></label><?php // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped ?>
                        <input class="widefat <?php echo esc_attr( $class ); ?>" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" type="text" value="<?php echo esc_attr( $value ); ?>" />
                    </p>
                    <?php
                    break;

                case 'number':
                    ?>
                    <p>
                        <label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo $setting['label']; /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></label>
                        <input class="widefat <?php echo esc_attr( $class ); ?>" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" type="number" step="<?php echo esc_attr( $setting['step'] ); ?>" min="<?php echo esc_attr( $setting['min'] ); ?>" max="<?php echo esc_attr( $setting['max'] ); ?>" value="<?php echo esc_attr( $value ); ?>" />
                    </p>
                    <?php
                    break;

                case 'select':
                    ?>
                    <p>
                        <label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo $setting['label']; /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></label>
                        <select class="widefat <?php echo esc_attr( $class ); ?>" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>">
                            <?php foreach ( $setting['options'] as $option_key => $option_value ) : ?>
                                <option value="<?php echo esc_attr( $option_key ); ?>" <?php selected( $option_key, $value ); ?>><?php echo esc_html( $option_value ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </p>
                    <?php
                    break;

                case 'textarea':
                    ?>
                    <p>
                        <label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo $setting['label']; /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></label>
                        <textarea class="widefat <?php echo esc_attr( $class ); ?>" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" cols="20" rows="3"><?php echo esc_textarea( $value ); ?></textarea>
                        <?php if ( isset( $setting['desc'] ) ) : ?>
                            <small><?php echo esc_html( $setting['desc'] ); ?></small>
                        <?php endif; ?>
                    </p>
                    <?php
                    break;

                case 'checkbox':
                    ?>
                    <p>
                        <input class="checkbox <?php echo esc_attr( $class ); ?>" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" type="checkbox" value="1" <?php checked( $value, 1 ); ?> />
                        <label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo $setting['label']; /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></label>
                    </p>
                    <?php
                    break;

                // Default: run an action.
                default:
                    do_action( 'w_widget_field_' . $setting['type'], $key, $value, $setting, $instance );
                    break;
            }
        }
    }
}