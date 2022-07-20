<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //Exit if accessed directly


/**
 * Display Metabox.
 *
 * @var $post
 * @var $metabox
 * @var $args_id
 *
 */

$vue_id = '';

if ( empty( $metabox_id ) ) {
	/*We are on a post*/
	$sections  = $metabox['args'][ $metabox['id'] ];
	$active    = '';
	$vue_id    = "data-vue='" . $metabox['id'] . "'";
	$source_id = "data-source='" . $post->ID . "'";
} else {
	if ( apply_filters( 'wpcfto_enable_export_import', true ) ) {
		$sections['wpcfto_import_export'] = array(
			'name'   => esc_html__( 'Import/Export', 'wpcfto' ),
			'icon'   => 'fa fa-sync',
			'fields' => array(
				'wpcfto_import_export_field' => array(
					'type' => 'import_export',
					'id'   => $metabox['id']
				)
			)
		);
	}
}

?>

<div v-cloak
	class="stm_metaboxes_grid <?php echo esc_attr( 'sections_count_' . count( $sections ) ); ?>" <?php echo wp_kses( $vue_id . ' ' . $source_id, [] ); ?>>

	<div class="stm_metaboxes_grid__inner" v-if="data !== ''">

		<div class="container">

			<?php
			// Hide Tab Nav if Menu Items == 1
			$hide_tab_nav = false;
			if ( count( $sections ) === 1 ) {
				foreach ( $sections as $section_name => $section ) {
					$submenus = array_unique( array_column( $section['fields'], 'submenu' ) );
					if ( count( $submenus ) <= 1 ) {
						$hide_tab_nav = true;
					}
				}
			}
			?>

			<div class="wpcfto-tab-nav <?php echo ( $hide_tab_nav ) ? 'hide' : ''; ?>">
				<div class="wpcfto-tab-nav--inner">
					<?php
					foreach ( $sections as $section_name => $section ) :

						$section_order = array_search( $section_name, array_keys( $sections ), true );

						if ( 0 === $section_order ) {
							$active = $section_name;
						}

						$submenus = array_column( $section['fields'], 'submenu' );

						$section_classes = array();
						if ( $active === $section_name ) {
							$section_classes[] = 'active';
						}
						if ( empty( $section['icon'] ) ) {
							$section_classes[] = 'no-icon';
						}
						if ( ! empty( $submenus ) ) {
							$section_classes[] = 'has-submenu';
						}

						$submenus = array_unique( $submenus );

						?>
						<div class="wpcfto-nav <?php echo esc_attr( implode( ' ', $section_classes ) ); ?>">

							<div class="wpcfto-nav-title"
								data-section="<?php echo esc_attr( $section_name ); ?>"
								@click="changeTab('<?php echo esc_attr( $section_name ); ?>')">
								<?php if ( ! empty( $section['icon'] ) ) : ?>
									<i class="<?php echo esc_attr( $section['icon'] ); ?>"></i>
								<?php endif; ?>

								<?php echo esc_html( $section['name'] ); ?>
							</div>

							<?php if ( ! empty( $submenus ) ) : ?>
								<div class="wpcfto-submenus">
									<?php
									foreach ( $submenus as $key => $_submenu ) :
										$submenu_classes = array();
										if ( in_array( 'active', $section_classes, true ) && empty( $key ) ) {
											$submenu_classes[] = 'active';
										}
										?>
										<div
											data-submenu="<?php echo esc_attr( $section_name . '_' . wpcfto_sanitize_string( $_submenu ) ); ?>"
											class="<?php echo esc_attr( implode( ' ', $submenu_classes ) ); ?>"
											@click="changeSubMenu('<?php echo esc_attr( $section_name . '_' . wpcfto_sanitize_string( $_submenu ) ); ?>')">
											<?php echo esc_attr( $_submenu ); ?>
											<i class="fa fa-chevron-right"></i>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>

						</div>
					<?php endforeach; ?>
					<div class="wpcfto-nav registrationbox" onclick="showregistration()">
						<div class="wpcfto-nav-title">
							Product registration
						</div>
					</div>
					<?php do_action( 'wpcfto_after_tab_nav' ); ?>
				</div>
			</div>

			<?php
			foreach ( $sections as $section_name => $section ) :

				$submenus        = array_column( $section['fields'], 'submenu' );
				$section_classes = array();
				if ( $section_name === $active ) {
					$section_classes[] = 'active';
				}
				if ( ! empty( $submenus ) ) {
					$section_classes[] = 'has-submenu-items';
				}

				?>
				<div id="<?php echo esc_attr( $section_name ); ?>"
					class="wpcfto-tab <?php echo esc_attr( implode( ' ', $section_classes ) ); ?>">
					<div class="container container-constructed">
						<div class="row">

							<div class="column">

								<?php if ( ! empty( $section['label'] ) ) : ?>
									<div data-notice="enable_courses_filter_notice"
										class="wpcfto_generic_field wpcfto_generic_field__notice first opened">
										<label><?php echo esc_html( $section['label'] ); ?></label>
									</div>
								<?php endif; ?>

								<?php $is_group_item = false; ?>

								<?php
								foreach ( $section['fields'] as $field_name => $field ) {

									if ( isset( $field['group'] ) && 'started' === $field['group'] ) {
										$is_group_item = true;
									}

									$field['is_group_item'] = $is_group_item;

									if ( ! empty( $field['pre_open'] ) && $field['pre_open'] ) {
										wpcfto_metaboxes_preopen_field( $section, $section_name, $field, $field_name );
										continue;
									}

									if ( ! empty( $field['group'] ) ) {
										wpcfto_metaboxes_display_group_field( $section, $section_name, $field, $field_name );
										if ( 'ended' === $field['group'] ) {
											$is_group_item = false;
										}
										continue;
									}

									wpcfto_metaboxes_display_single_field( $section, $section_name, $field, $field_name );

								}
								?>

							</div>

						</div>
					</div>
				</div>
			<?php endforeach; ?>



			<div class="registration" style="display:none">
			<div class="closebox" onclick="closeregistration()">[ 닫기 ]</div>

				<form
				method="post">
					<?php

						global $wpdb, $post;
						// 등록한 제품 list 불러오기
						$results = $wpdb->get_results($wpdb->prepare("SELECT * from wp_product_list"));
						$num = count($results);
						
						
						// 첫 등록할시 !  
						if(!$results){
						?> 
						<div class="registration-container">
							<p>판매 상품 리스트</p>
							<div class="registration-title">
								<div class="registration-title-name"> < 제품명 > </div>
								<div class="registration-title-link"> < 제품 링크 > </div>
							</div>
							<div class="registration-list">
								<div class="registration-div">
									<input type="checkbox" name="deletecheck">
									<div class="registration-num">1</div>
									<input type="hidden" name="registrationNum[]" value="1">
									<input type="hidden" name="registrationID[]" value="0">
									<div class="registration-name">
										<input type="text" id="" name="registrationname[]" placeholder="제품명 입력(40)" value="">
									</div>
									<div class="registration-link">
										<input type="text" id="" name="registrationlink[]" placeholder="제품 링크(40)" value="">
									</div>
								</div>
							</div>
							<div class="registration-add" onclick="create_registration_Tag()">
								<div>+</div>
								<div>신규</div>
							</div>
						</div>
						<?php $num++; ?>
						<!-- 추가로 등록할시 먼저 db에서 list 출력-->
						<?php } else { 
							$registration_box = '';
							for($i = 0; $i < $num; $i++){
								$add_registration_box = ' <div class="registration-div">
													<input type="checkbox" name="deletecheck[]" value="'.$results[$i]->ID.'">
													<div class="registration-num">'.($i+1).'</div>
													<input type="hidden" name="registrationNum[]" value="'.($i+1).'">
													<input type="hidden" name="registrationID[]" value="'.$results[$i]->ID.'">
													<div class="registration-name">
														<input type="text" id="" name="registrationname[]" placeholder="제품명 입력란(40)" value="'.$results[$i]->product_name.'">
													</div>
													<div class="registration-link">
														<input type="text" id="" name="registrationlink[]" placeholder="제품링크(40)" value="'.$results[$i]->product_link.'">
													</div>
												</div>';
								$registration_box = $registration_box.$add_registration_box ;
							}
								echo ('<div class="registration-container">
											<p>판매 상품 리스트</p>
											<div class="registration-title">
												<div class="registration-title-name"> < 제품명 > </div>
												<div class="registration-title-link"> < 제품 링크 > </div>
											</div>
											<div class="registration-list">
												'.$registration_box.'
											</div>
											<div class="registration-add" onclick="create_registration_Tag()">
												<div>+</div>
												<div>신규</div>
											</div>
										</div>');
						
							}
					?>
				<input class="registration_delete_btn" type="submit" onclick="deletebtn()"
				value="DELETE" formaction="/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-delete.php">
				<input class="registration_save_btn" type="submit" onclick="savebtn()"
				value="SAVE" formaction="/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-display-save.php" >

				</form>
			</div>

		</div>
	</div>
</div>


<script src="https://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript">

    var Count = <?php echo $num; ?>+1;

    function create_registration_Tag(){
    let registrationList = document.querySelector('.registration-list');
    let new_pTag = document.createElement('div');
    
    new_pTag.setAttribute('class', 'registration-div');
    new_pTag.innerHTML = 
                `<input type="checkbox" name="deletecheck">
				<div class="registration-num">${Count}</div>
                <input type="hidden" name="registrationNum[]" value=${Count}>
				<input type="hidden" name="registrationID[]" value="0">
                <div class="registration-name">
                    <input type="text" id="" name="registrationname[]" placeholder="제품명 입력란(40)" value="<?php echo esc_attr( $post->playname ); ?>" required>
                </div>
                <div class="registration-link">
                    <input type="text" id="" name="registrationlink[]" placeholder="제품링크(40)" value="<?php echo esc_attr( $post->playlink ); ?>" required>
                </div>
				<div class="playbox-trash2" onclick="close_registrationTag(this)" style="font-size:23px;">✖︎</div>`
    
	registrationList.appendChild(new_pTag);
    
     Count++;
    }
	function close_registrationTag(e){
        let registrationList = document.querySelector('.registration-list');
        registrationList.removeChild(e.parentNode);
        Count = Count-1;
    }

	function savebtn() {
		alert("저장합니다.");
	}

	function deletebtn() {
		alert("삭제합니다.");
	}

	function showregistration() {
		let registration = document.querySelector('.registration');
		registration.style.display="block";
	}
	function closeregistration(){
		let registration = document.querySelector('.registration');
		registration.style.display="none";
	}

</script>

