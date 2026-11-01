<?php
/**
 * Student form — shared by add_student.php and update_student.php
 * (Section 5 — Styling & Responsiveness)
 * ------------------------------------------------------------------
 * Variables expected from the page:
 *   $formMode  'add' | 'edit'
 *   $student   ['id', 'name', 'email', 'phone', 'address', 'profile_picture']
 *   $errors    ['field' => 'message']  (server-side validation, optional)
 *
 * Field names (backend reads these from $_POST / $_FILES):
 *   name · email · phone · address · profile_picture (file) · id (edit only)
 *
 * Hooks for AJAX validation (Section 4):
 *   form#studentForm  → each input has an id equal to its name
 *   UI.setFieldError('#email', 'message') / UI.clearFieldError('#email')
 */

$formMode = $formMode ?? 'add';
$isEdit   = $formMode === 'edit';
$errors   = $errors ?? [];
$student  = array_merge(
    ['id' => '', 'name' => '', 'email' => '', 'phone' => '', 'address' => '', 'profile_picture' => ''],
    $student ?? []
);

$photoSrc = !empty($student['profile_picture']) ? $student['profile_picture'] : 'assets/img/avatar-placeholder.svg';
?>
<form id="studentForm"
      class="needs-validation"
      action="<?= $isEdit ? 'update_student.php' : 'add_student.php' ?>"
      method="post"
      enctype="multipart/form-data"
      novalidate
      data-loading>

    <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= e($student['id']) ?>">
    <?php endif; ?>

    <div class="row g-4">

        <!-- ============ Profile picture ============ -->
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-body p-4">
                    <h2 class="section-title">Profile picture</h2>

                    <div class="avatar-upload field">
                        <img id="photoPreview" src="<?= e($photoSrc) ?>" alt="Profile picture preview" class="avatar-preview">

                        <div class="upload-drop">
                            <input type="file"
                                   class="visually-hidden<?= invalid_class($errors, 'profile_picture') ?>"
                                   id="profile_picture"
                                   name="profile_picture"
                                   accept="image/jpeg,image/png,image/gif,image/webp"
                                   data-preview="#photoPreview"<?= invalid_attr($errors, 'profile_picture') ?>>
                            <label for="profile_picture" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-upload"></i> <?= $isEdit ? 'Change photo' : 'Choose photo' ?>
                            </label>
                            <div class="file-name mt-2" data-file-name>
                                <?= $isEdit && !empty($student['profile_picture']) ? 'Current photo kept unless you choose a new one' : 'No file selected' ?>
                            </div>
                            <div class="invalid-feedback"><?= error_text($errors, 'profile_picture', 'Please choose a valid image.') ?></div>
                        </div>

                        <p class="form-text mb-0">Optional · JPG, PNG, GIF or WEBP · max 2&nbsp;MB</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============ Student details ============ -->
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-body p-4">
                    <h2 class="section-title">Student details</h2>

                    <div class="row g-3">
                        <div class="col-12 col-md-6 field">
                            <label for="name" class="form-label">Full name<span class="req">*</span></label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control<?= invalid_class($errors, 'name') ?>"
                                       id="name" name="name" value="<?= e($student['name']) ?>"
                                       placeholder="e.g. Ahmad Khalil" maxlength="100" autocomplete="name" required<?= invalid_attr($errors, 'name') ?>>
                                <div class="invalid-feedback" id="name-feedback"><?= error_text($errors, 'name', 'Please enter the student’s name.') ?></div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 field">
                            <label for="email" class="form-label">Email<span class="req">*</span></label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control<?= invalid_class($errors, 'email') ?>"
                                       id="email" name="email" value="<?= e($student['email']) ?>"
                                       placeholder="student@example.com" maxlength="100" autocomplete="email" required<?= invalid_attr($errors, 'email') ?>>
                                <div class="invalid-feedback" id="email-feedback"><?= error_text($errors, 'email', 'Please enter a valid email address.') ?></div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 field">
                            <label for="phone" class="form-label">Phone number<span class="req">*</span></label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="tel" class="form-control<?= invalid_class($errors, 'phone') ?>"
                                       id="phone" name="phone" value="<?= e($student['phone']) ?>"
                                       placeholder="e.g. 0912345678" maxlength="15" autocomplete="tel" required<?= invalid_attr($errors, 'phone') ?>>
                                <div class="invalid-feedback" id="phone-feedback"><?= error_text($errors, 'phone', 'Please enter a phone number (max 15 characters).') ?></div>
                            </div>
                        </div>

                        <div class="col-12 field">
                            <label for="address" class="form-label">Address<span class="req">*</span></label>
                            <textarea class="form-control<?= invalid_class($errors, 'address') ?>"
                                      id="address" name="address" rows="3"
                                      placeholder="City, street, building…" autocomplete="street-address" required<?= invalid_attr($errors, 'address') ?>><?= e($student['address']) ?></textarea>
                            <div class="invalid-feedback" id="address-feedback"><?= error_text($errors, 'address', 'Please enter the address.') ?></div>
                        </div>
                    </div>
                </div>

                <div class="card-footer form-actions">
                    <a href="view_students.php" class="btn btn-light"><i class="bi bi-x-lg"></i> Cancel</a>
                    <button type="submit" class="btn btn-primary" data-loading-text="Saving…">
                        <i class="bi bi-check2-circle"></i> <?= $isEdit ? 'Save changes' : 'Add student' ?>
                    </button>
                </div>
            </div>
        </div>

    </div>
</form>
