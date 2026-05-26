$(document).ready(function() {
    // Initialize DataTables
    if($('#studentsTable').length) {
        $('#studentsTable').DataTable({
            pageLength: 10,
            responsive: true,
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries"
            }
        });
    }
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
    
    // Confirm delete
    $('.delete-confirm').click(function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });
    
    // AJAX Search
    $('#searchStudent').on('keyup', function() {
        let searchTerm = $(this).val();
        if(searchTerm.length > 2) {
            $.ajax({
                url: 'index.php?controller=student&action=search',
                type: 'GET',
                data: {term: searchTerm},
                dataType: 'json',
                success: function(data) {
                    let results = '';
                    $.each(data, function(key, student) {
                        results += '<tr>';
                        results += '<td>' + student.registration_number + '</td>';
                        results += '<td>' + student.first_name + ' ' + student.last_name + '</td>';
                        results += '<td>' + student.gender + '</td>';
                        results += '<td>' + (student.class_name || 'Not Assigned') + '</td>';
                        results += '<td>';
                        results += '<a href="index.php?controller=student&action=profile&id=' + student.id + '" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a> ';
                        results += '<a href="index.php?controller=student&action=edit&id=' + student.id + '" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>';
                        results += '</td>';
                        results += '</tr>';
                    });
                    $('#studentsTable tbody').html(results);
                }
            });
        }
    });
    
    // Photo preview
    $('#photoInput').change(function(e) {
        let reader = new FileReader();
        reader.onload = function(e) {
            $('#photoPreview').attr('src', e.target.result).show();
        }
        reader.readAsDataURL(e.target.files[0]);
    });
    
    // Print function
    window.printProfile = function() {
        window.print();
    }
    
    // Export functions
    window.exportToExcel = function() {
        window.location.href = 'index.php?controller=report&action=export&format=excel';
    }
    
    window.exportToPDF = function() {
        window.location.href = 'index.php?controller=report&action=export&format=pdf';
    }
    
    // Filter by class
    $('#filterClass').change(function() {
        let classId = $(this).val();
        window.location.href = 'index.php?controller=student&action=index&class_id=' + classId;
    });
    
    // Tooltips initialization
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});