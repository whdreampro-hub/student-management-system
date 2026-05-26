<?php
$page_title = "Student History | Student Management System";
$active_page = "students";
require_once '../views/layouts/header.php';
require_once '../views/layouts/sidebar.php';
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-history"></i> Academic History - 
            <?php echo $student['first_name'] . ' ' . $student['last_name']; ?>
        </h5>
        <a href="index.php?controller=student&action=profile&id=<?php echo $student['id']; ?>" 
           class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Profile
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Class</th>
                        <th>Academic Year</th>
                        <th>Status</th>
                        <th>Reason</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($history = $history_stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><strong><?php echo $history['class_name']; ?></strong></td>
                        <td><?php echo $history['year_name']; ?></td>
                        <td>
                            <span class="badge-status bg-<?php 
                                echo $history['status'] == 'active' ? 'success' : 
                                    ($history['status'] == 'promoted' ? 'info' : 
                                    ($history['status'] == 'transferred' ? 'warning' : 'secondary')); 
                            ?> text-white">
                                <?php echo ucfirst($history['status']); ?>
                            </span>
                        </td>
                        <td><?php echo $history['reason']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($history['start_date'])); ?></td>
                        <td><?php echo $history['end_date'] ? date('d/m/Y', strtotime($history['end_date'])) : 'Present'; ?></td>
                        <td><?php echo $history['remarks'] ?? '-'; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
        <div class="alert alert-info mt-3">
            <i class="fas fa-info-circle"></i> 
            <strong>Note:</strong> This timeline shows the complete academic journey including promotions, transfers, and class changes.
        </div>
    </div>
</div>

<?php require_once '../views/layouts/footer.php'; ?>