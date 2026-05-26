# Student Management System - Implementation TODO

## Priority 1: Data integrity (must-do first)
- [x] Refactor promotion/transfer logic to use a shared “close active record + create new active record” method
- [x] Wrap promotion/transfer operations in database transactions
- [x] Add invariant checks: ensure exactly one active history row per student at a time

- [ ] Verify current queries for dashboard/recent admissions/transfers align with `student_class_history.status` and `reason`

## Priority 2: MVC completeness + UI
- [ ] Implement missing controllers: AcademicYearController, ClassController, StudentClassHistoryController
- [ ] Add missing views: students/view.php (student profile + timeline)
- [ ] Add timeline/history view(s)
- [ ] Ensure sidebar routes work (academic years, classes, history)

## Priority 3: UX improvements
- [ ] Integrate SweetAlert2 for delete confirmation (replace JS confirm)
- [ ] Add AJAX search/filter endpoints (optional) or improve pagination

## Priority 4: Reporting
- [ ] Add print student profile/card route
- [ ] Add export baseline (CSV/Excel) and later PDF

## Priority 5: Schema alignment
- [ ] Verify schema enums/fields match the task spec exactly
- [ ] Add helpful indexes for performance
- [ ] Decide on soft delete implementation (schema vs app-level)

