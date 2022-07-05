<?php
$user_fields = STM_LMS_Form_Builder::register_form_fields(false);
$user_id = $user->ID;
if (!empty($user_fields) && isset($user_fields['register'])) {
    $fields = $user_fields['register'];
    $user_meta = get_user_meta($user_id);
    ?>
    <h2><?php esc_html_e('Additional user information', 'masterstudy-lms-learning-management-system-pro'); ?></h2>
    <table class="form-table">
        <tbody>
        <?php foreach ($fields as $field): ?>
            <?php
            $value = !empty($user_meta[$field['id']][0]) ? $user_meta[$field['id']][0] : '';
            $label = !empty($field['label']) ? $field['label'] : $field['field_name'];
            ?>
            <tr>
                <th>
                    <label>
                        <?php echo esc_html($label); ?>
                    </label>
                </th>
                <td>
                    <?php if ($field['type'] === 'text' || $field['type'] === 'email' || $field['type'] === 'tel'): ?>
                        <input type="<?php echo esc_attr($field['type']); ?>"
                               name="<?php echo esc_attr($field['id']); ?>" value="<?php echo esc_attr($value); ?>">
                    <?php elseif ($field['type'] === 'select' && !empty($field['choices'])): ?>
                        <select name="<?php echo esc_attr($field['id']); ?>">
                            <?php foreach ($field['choices'] as $choice): ?>
                                <option <?php if ($value === $choice) echo 'selected'; ?>
                                        value="<?php echo esc_attr($choice); ?>"><?php echo esc_html($choice); ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php elseif ($field['type'] === 'radio' && !empty($field['choices'])): ?>

                        <?php foreach ($field['choices'] as $choice): ?>
                            <label>
                                <span><?php echo esc_html($choice); ?></span>
                                <input name="<?php echo esc_attr($field['id']); ?>"
                                       type="radio" <?php if ($value === $choice) echo 'checked'; ?>
                                       value="<?php echo esc_attr($choice); ?>"/>
                            </label>
                        <?php endforeach; ?>
                    <?php elseif ($field['type'] === 'file'): ?>
                        <a href="<?php echo esc_url($value); ?>"><?php echo esc_html($value); ?></a>
                    <?php elseif ($field['type'] === 'textarea'): ?>
                        <textarea name="<?php echo esc_attr($field['id']); ?>"><?php echo esc_html($value); ?></textarea>
                    <?php elseif ($field['type'] === 'checkbox'): ?>
                        <input type="checkbox" name="<?php echo esc_attr($field['id']); ?>" <?php if(!empty($value) && $value !== 'false') echo 'checked'; ?> />
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php
}