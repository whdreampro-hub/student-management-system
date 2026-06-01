<?php
$pageTitle = 'Add New Student';
require_once APP . '/views/layouts/header.php';
?>

<div class="page-header mb-4">
    <div>
        <h3 class="page-title">Add New Student</h3>
        <p class="page-subtitle">Fill in the form to register a new student</p>
    </div>
    <a href="?page=students" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back
    </a>
</div>

<form id="createStudentForm" enctype="multipart/form-data">
<div class="row g-4">

    <!-- Left Column: Photo -->
    <div class="col-lg-4">
        <div class="card-glass h-100">
            <div class="card-glass-header"><h5><i class="bi bi-image me-2"></i>Photo</h5></div>
            <div class="card-glass-body text-center">
                <div class="photo-upload-area" id="photoArea" onclick="document.getElementById('photoInput').click()">
                    <img id="photoPreview" src="" alt="" style="display:none; width:100%; height:100%; object-fit:cover; border-radius:12px;">
                    <div id="photoPlaceholder">
                        <i class="bi bi-camera-fill"></i>
                        <p>Click to upload photo</p>
                        <small>JPG, PNG, WEBP — max 2MB</small>
                    </div>
                </div>
                <input type="file" id="photoInput" name="photo" accept="image/*" class="d-none" onchange="previewPhoto(this)">
            </div>
        </div>
    </div>

    <!-- Right Column: Personal Info -->
    <div class="col-lg-8">
        <div class="card-glass">
            <div class="card-glass-header"><h5><i class="bi bi-person-fill me-2"></i>Personal Information</h5></div>
            <div class="card-glass-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label required">First Name</label>
                        <input type="text" class="form-control" name="first_name" id="first_name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">Last Name</label>
                        <input type="text" class="form-control" name="last_name" id="last_name" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">Gender</label>
                        <select class="form-select" name="gender" id="gender" required>
                            <option value="">Select</option>
                            <option>Male</option>
                            <option>Female</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">Date of Birth</label>
                        <input type="date" class="form-control" name="date_of_birth" id="date_of_birth" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nationality</label>
                        <input type="text" class="form-control" name="nationality" value="Rwandan">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">Admission Date</label>
                        <input type="date" class="form-control" name="admission_date" id="admission_date"
                               value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-glass mt-4">
            <div class="card-glass-header"><h5><i class="bi bi-geo-alt-fill me-2"></i>Location</h5></div>
            <div class="card-glass-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" name="address">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Village</label>
                        <input type="text" class="form-control" name="village">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Sector</label>
                        <input type="text" class="form-control" name="sector">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">District</label>
                        <input type="text" class="form-control" name="district">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Parent/Guardian -->
    <div class="col-lg-6">
        <div class="card-glass">
            <div class="card-glass-header"><h5><i class="bi bi-people me-2"></i>Parent Information</h5></div>
            <div class="card-glass-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Parent Name</label>
                        <input type="text" class="form-control" name="parent_name">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Parent Phone</label>
                        <input type="tel" class="form-control" name="parent_phone">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card-glass">
            <div class="card-glass-header"><h5><i class="bi bi-person-check me-2"></i>Guardian Information</h5></div>
            <div class="card-glass-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Guardian Name</label>
                        <input type="text" class="form-control" name="guardian_name">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Guardian Phone</label>
                        <input type="tel" class="form-control" name="guardian_phone">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Class Enrollment -->
    <div class="col-12">
        <div class="card-glass">
            <div class="card-glass-header"><h5><i class="bi bi-building-fill me-2"></i>Class Enrollment</h5></div>
            <div class="card-glass-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Class</label>
                        <select class="form-select" name="class_id" id="class_id">
                            <option value="">— Select Class —</option>
                            <?php foreach ($classes as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['class_name']) ?> (<?= $c['level'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Academic Year</label>
                        <select class="form-select" name="academic_year_id" id="academic_year_id">
                            <option value="">— Select Year —</option>
                            <?php foreach ($years as $y): ?>
                            <option value="<?= $y['id'] ?>" <?= $activeYear && $y['id']==$activeYear['id']?'selected':'' ?>>
                                <?= htmlspecialchars($y['year_name']) ?><?= $y['status']==='active'?' (Active)':'' ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Remarks</label>
                        <input type="text" class="form-control" name="remarks" placeholder="Optional notes">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit -->
    <div class="col-12 text-end">
        <a href="?page=students" class="btn btn-outline-secondary me-2">Cancel</a>
        <button type="submit" class="btn btn-primary px-5" id="submitBtn">
            <i class="bi bi-check2-circle me-2"></i>Register Student
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

document.getElementById('createStudentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('submitBtn');
    btn.disabled = true; btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

    const fd = new FormData(this);
    $.ajax({
        url: '?page=students&action=store',
        method: 'POST', data: fd,
        processData: false, contentType: false, dataType: 'json',
        success: function(res) {
            if (res.success) {
                Swal.fire({ icon: 'success', title: 'Registered!', text: res.message, timer: 1500, showConfirmButton: false })
                    .then(() => window.location.href = '?page=students&action=view&id=' + res.id);
            } else {
                showToast('error', res.message);
                btn.disabled = false; btn.innerHTML = '<i class="bi bi-check2-circle me-2"></i>Register Student';
            }
        }
    });
});
</script>

<?php require_once APP . '/views/layouts/footer.php'; ?>
