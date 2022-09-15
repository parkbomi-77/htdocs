<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'MSM_Admin_Profile' ) ) :

	class MSM_Admin_Profile {
		public static function add_members_fields( $user ) {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$fields = apply_filters( 'msm_users_custom_column', array( 'msm_register_fields' ) );

			foreach ( $fields as $field ) {
				$value = array();
				$metas = MSM_Meta::get_user_meta( $user->ID, '_' . $field );

				?>
                <h2><?php echo __( '멤버스 필드', 'mshop-members-s2' ) ?></h2>
                <table class="form-table">
					<?php foreach ( $metas as $meta ) : ?>
						<?php if ( ! empty( $meta['title'] ) ) : ?>
                            <tr>
                                <th>
                                    <label for="<?php echo esc_attr( $meta['name'] ); ?>"><?php echo esc_html( $meta['title'] ); ?></label>
                                </th>
                                <td>
									<?php if ( is_array( $meta['value'] ) && ! empty( $meta['label'] ) ) : ?>
										<?php echo $meta['label']; ?>
									<?php else : ?>
                                        <input type="text" name="<?php echo esc_attr( $meta['name'] ); ?>" id="<?php echo esc_attr( $meta['name'] ); ?>" value="<?php echo $meta['value']; ?>" class="regular-text"/>
									<?php endif; ?>
                                </td>
                            </tr>
						<?php endif; ?>
					<?php endforeach; ?>
                </table>
				<?php
			}

			$user_status    = array(
				'1' => __( '탈퇴', 'mshop-members-s2' ),
				'2' => __( '휴면', 'mshop-members-s2' ),
				'0' => __( '정상', 'mshop-members-s2' )
			);
			$current_status = get_user_meta( $user->ID, 'is_unsubscribed', true );
			if ( empty( $current_status ) ) {
				$current_status = 0;
			}

			?>
            <h2><?php echo __( '회원상태', 'mshop-members-s2' ) ?></h2>
            <table class="form-table">
                <tr>
                    <th>
                        <label for="is_unsubscribed"><?php _e( '회원상태', 'mshop-members-s2' ); ?></label>
                    </th>
                    <td>
                        <select name="is_unsubscribed">
							<?php foreach ( $user_status as $key => $label ) : ?>
                                <option value="<?php echo $key; ?>" <?php echo $current_status == $key ? 'selected' : ''; ?>><?php echo $label; ?></option>
							<?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            </table>
			<?php
		}
		public static function save_members_fields( $user_id ) {

			$fields = apply_filters( 'msm_users_custom_column', array( 'msm_register_fields' ) );

			foreach ( $fields as $field ) {
				$form_info = get_user_meta( $user_id, '_' . $field, true );

				if ( ! empty( $form_info ) ) {
					foreach ( $form_info['forms'] as $form_data ) {
						$fields = MSM_Meta::filter_fields( mfd_get_form_fields( $form_data['data'] ), $form_info['args'] );

						foreach ( $fields as $field ) {
							if ( ! empty( $field->name ) ) {
								update_user_meta( $user_id, $field->name, $_POST[ $field->name ] );
							}
						}
					}
				}
			}

			if ( isset( $_POST['is_unsubscribed'] ) ) {
				update_user_meta( $user_id, 'is_unsubscribed', $_POST['is_unsubscribed'] );
			}
		}
	}

endif;
