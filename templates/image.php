<form class="upload" action="upload.php" enctype="multipart/form-data" method="post" <?php if($field->hidden): ?>style="display: none"<?php endif; ?>>
    <div class="input-field file file-image">
        <label><?= $field->label; ?></label>
        <input class="file-mock" disabled="disabled" type="text">
        <div class="file-button button gray">
            <i class="icon-upload"></i> Choose File...
            <input id="<?= $field->name; ?>" name="<?= $field->name; ?>" type="file" accept=".jpg, .jpeg, .png">
        </div>
        <input class="hidden" type="hidden" />
        <div class="err err-<?= $field->name; ?>"></div>
    </div>
    <div class="image-display clearfix">
        <div class="image-box">
            <div class="loader">

            </div>
        </div>
    </div>
</form>
