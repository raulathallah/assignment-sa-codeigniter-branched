<div class="card">
  <div class="card-header fs-4">
    {title_profile}
  </div>
  <div class="card-body">
    <span class="fw-bold fs-3">{student_id} - {name}</span>
    {!academic_status_cell!}

    <div class="d-flex flex-column gap-1">
      <span class="text-muted">{study_program}</span>
      <span class="">Semester {current_semester}</span>
    </div>
  </div>

  <div class="m-3">
    <h5>Grades</h5>
    {!latest_grades_cell!}
  </div>


</div>