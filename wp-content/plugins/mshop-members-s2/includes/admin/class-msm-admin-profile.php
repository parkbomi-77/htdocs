<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'MSM_Admin_Profile' ) ) :

	class MSM_Admin_Profile {
		public static function add_form_tag() {
			echo 'enctype="multipart/form-data"';
		}
		public static function add_members_fields( $user ) {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$fields = apply_filters( 'msm_users_custom_column', array( 'msm_register_fields' ) );

			foreach ( $fields as $field ) {
				$form_fields = array();
				$form_info   = get_user_meta( $user->ID, '_' . $field, true );

				if ( ! empty( $form_info ) && is_array( $form_info ) ) {
					$metas = MSM_Meta::get_user_meta( $user->ID, '_' . $field );

					foreach ( $form_info['forms'] as $form_data ) {
						$form_fields = array_merge( $form_fields, MSM_Meta::filter_fields( mfd_get_form_fields( $form_data['data'] ), $form_info['args'] ) );
					}

					if ( ! empty( $metas ) ) {
						?>
                        <h2><?php echo __( '멤버스 필드', 'mshop-members-s2' ) ?></h2>
                        <table class="form-table">
							<?php foreach ( $metas as $meta ) : ?>
								<?php if ( ! empty( $meta['title'] ) ) : ?>
									<?php
									$form_field = msm_get( $form_fields, $meta['name'] );
									?>
									<?php if ( ! empty( $form_field ) ) : ?>
                                        <tr>
                                            <th>
                                                <label for="<?php echo esc_attr( $meta['name'] ); ?>"><?php echo esc_html( $meta['title'] ); ?></label>
                                            </th>
                                            <td>
												<?php if ( 'file' == msm_get( $form_field->property, 'type' ) ) : ?>
													<?php echo $meta['label']; ?>
													<?php
													echo sprintf( '<input type="file" name="%s[]" %s>', $form_field->get_name(), 'yes' == msm_get( $form_field->property, 'multiple' ) ? 'multiple' : '' );
													?>
												<?php else : ?>
                                                    <input type="text" name="<?php echo esc_attr( $meta['name'] ); ?>" id="<?php echo esc_attr( $meta['name'] ); ?>" value="<?php echo esc_html( $meta['value'] ); ?>" class="regular-text"/>
												<?php endif; ?>
                                            </td>
                                        </tr>
									<?php endif; ?>
								<?php endif; ?>
							<?php endforeach; ?>
                        </table>
						<?php
					}
				}
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

				if ( ! empty( $form_info ) && is_array( $form_info ) ) {
					foreach ( $form_info['forms'] as $form_data ) {
						$fields = MSM_Meta::filter_fields( mfd_get_form_fields( $form_data['data'] ), $form_info['args'] );

						foreach ( $fields as $field ) {
							if ( ! empty( $field->name ) ) {
								if ( 'file' == msm_get( $field->property, 'type' ) ) {
									$files = msm_get( $_FILES, $field->get_name() );

									if ( ! empty( $files ) ) {
										$file_count = count( array_filter( $files['name'] ) );

										if ( $file_count > 0 ) {
											$upload_dir = MSM_Meta::get_upload_dir( array( 'type' => 'user', 'id' => $user_id ) );
											$metas      = array();
											$labels     = array();

											for ( $i = 0; $i < $file_count; $i++ ) {
												$file_name = $files['name'][ $i ];

												if ( apply_filters( 'msm_url_encode_to_upload_filename', true ) ) {
													$file_name = urlencode( $file_name );
												}

												$destination = $upload_dir . basename( $file_name );

												if ( move_uploaded_file( $files['tmp_name'][ $i ], $destination ) ) {
													$meta_key = uniqid();

													$metas[ $meta_key ] = array(
														'field_key' => $field->get_name(),
														'filename'  => $destination
													);

													$url      = sprintf( '%s/?msm_file_download=%d&key=%s&type=%s&meta_name=%s', site_url(), $user_id, $meta_key, 'user', $field->get_name() );
													$labels[] = '<a href="' . $url . '">' . urldecode( $file_name ) . '</a>';
												} else {
													throw new Exception( __( '파일 업로드중 오류가 발생했습니다.', 'mshop-members-s2' ) );
												}
											}

											update_user_meta( $user_id, $field->get_name(), $metas );
											update_user_meta( $user_id, $field->get_name() . '_label', implode( '<br>', $labels ) );
										}
									}
								} else {
									update_user_meta( $user_id, $field->name, $_POST[ $field->get_name() ] );
								}
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
