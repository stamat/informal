<div class="input-field" <?php if($field->hidden): ?>style="display: none"<?php endif; ?>>
    <label for="name"><?= $field->label; ?></label>
    <input id="<?= $field->name; ?>" name="<?= $field->name; ?>" type="text">
    <div class="err err-<?= $field->name; ?>"></div>
</div>
