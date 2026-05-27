<?php
$pageTitle = 'Edit Student';
require_once APP . '/views/layouts/header.php';
?>
<div class="page-header mb-4">
    <div>
        <h3 class="page-title">Edit Student</h3>
        <p class="page-subtitle"><?= htmlspecialchars($student['registration_number']) ?></p>
    </div>
    <a href="?page=students&action=view&id=<?= $student['id'] ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Profile
    </a>
</div>

<form id="editStudentForm" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?= $student['id'] ?>">
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card-glass">
            <div class="card-glass-header"><h5><i class="bi bi-image me-2"></i>Photo</h5></div>
            <div class="card-glass-body text-center">
                <div class="photo-upload-area" onclick="document.getElementById('photoInput').click()">
                    <?php if (!empty($student['photo'])): ?>
                        <img id="photoPreview" src="<?= uploadUrl($student['photo']) ?>" alt=""
                             style="width:100%;height:100%;object-fit:cover;border-radius:12px;">
                        <div id="photoPlaceholder" style="display:none">
                    <?php else: ?>
                        <img id="photoPreview" src="" alt="" style="display:none;width:100%;height:100%;object-fit:cover;border-radius:12px;">
                        <div id="photoPlaceholder">
                    <?php endif; ?>
                            <i class="bi bi-camera-fill"></i><p>Click to change photo</p>
                        </div>
                </div>
                <input type="file" id="photoInput" name="photo" accept="image/*" class="d-none" onchange="previewPhoto(this)">
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card-glass mb-4">
            <div class="card-glass-header"><h5><i class="bi bi-person-fill me-2"></i>Personal Information</h5></div>
            <div class="card-glass-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label required">First Name</label>
                        <input type="text" class="form-control" name="first_name" value="<?= htmlspecialchars($student['first_name']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">Last Name</label>
                        <input type="text" class="form-control" name="last_name" value="<?= htmlspecialchars($student['last_name']) ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Gender</label>
                        <select class="form-select" name="gender" required>
                            <?php foreach (['Male','Female','Other'] as $g): ?>
                            <option <?= $student['gender']===$g?'selected':'' ?>><?= $g ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" name="date_of_birth" value="<?= $student['date_of_birth'] ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nationality</label>
                        <input type="text" class="form-control" name="nationality" value="<?= htmlspecialchars($student['nationality'] ?? 'Rwandan') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($student['email'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Admission Date</label>
                        <input type="date" class="form-control" name="admission_date" value="<?= $student['admission_date'] ?>">
                    </div>
                    <div class="col-12"><label class="form-label">Address</label>
                        <input type="text" class="form-control" name="address" value="<?= htmlspecialchars($student['address'] ?? '') ?>">
                    </div>
                    <div class="col-md-4"><label class="form-label">Village</label>
                        <input type="text" class="form-control" name="village" value="<?= htmlspecialchars($student['village'] ?? '') ?>">
                    </div>
                    <div class="col-md-4"><label class="form-label">Sector</label>
                        <input type="text" class="form-control" name="sector" value="<?= htmlspecialchars($student['sector'] ?? '') ?>">
                    </div>
                    <div class="col-md-4"><label class="form-label">District</label>
                        <input type="text" class="form-control" name="district" value="<?= htmlspecialchars($student['district'] ?? '') ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card-glass">
                    <div class="card-glass-header"><h5><i class="bi bi-people me-2"></i>Parent</h5></div>
                    <div class="card-glass-body">
                        <label class="form-label">Parent Name</label>
                        <input type="text" class="form-control mb-3" name="parent_name" value="<?= htmlspecialchars($student['parent_name'] ?? '') ?>">
                        <label class="form-label">Parent Phone</label>
                        <input type="tel" class="form-control" name="parent_phone" value="<?= htmlspecialchars($student['parent_phone'] ?? '') ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card-glass">
                    <div class="card-glass-header"><h5><i class="bi bi-person-check me-2"></i>Guardian</h5></div>
                    <div class="card-glass-body">
                        <label class="form-label">Guardian Name</label>
                        <input type="text" class="form-control mb-3" name="guardian_name" value="<?= htmlspecialchars($student['guardian_name'] ?? '') ?>">
                        <label class="form-label">Guardian Phone</label>
                        <input type="tel" class="form-control" name="guardian_phone" value="<?= htmlspecialchars($student['guardian_phone'] ?? '') ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 text-end">
        <a href="?page=students&action=view&id=<?= $student['id'] ?>" class="btn btn-outline-secondary me-2">Cancel</a>
        <button type="submit" class="btn btn-warning px-5" id="updateBtn">
            <i class="bi bi-check2-circle me-2"></i>Save Changes
        </button>
    </div>
</div>
</form>
<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('photoPreview').src = e.target.result;
            document.getElementById('photoPreview').style.display = 'block';
            document.getElementById('photoPlaceholder').style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
document.getElementById('editStudentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('updateBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
    const fd = new FormData(this);
    $.ajax({
        url: '?page=students&action=update', method: 'POST', data: fd,
        processData: false, contentType: false, dataType: 'json',
        success: function(res) {
            if (res.success) {
                showToast('success', res.message);
                setTimeout(() => window.location.href = '?page=students&action=view&id=<?= $student['id'] ?>', 1000);
            } else {
                showToast('error', res.message);
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check2-circle me-2"></i>Save Changes';
            }
        }
    });
});
</script>
<?php require_once APP . '/views/layouts/footer.php'; ?>
