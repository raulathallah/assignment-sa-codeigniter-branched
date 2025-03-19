<div class="custom-primary" style=" width: 13%;">
  <ul class="list-group list-group-flush gap-2 p-1">
    <?php if (logged_in()): ?>
      <li class="">
        <a class="fs-6 btn btn-link text-white text-decoration-none text-start w-100" href="/">
          <i class="me-2 bi bi-house-fill">
          </i>Home</a>
      </li>

      <?php if (in_groups('admin')): ?>
        <li class="">
          <a class="fs-6 btn btn-link text-white text-decoration-none text-start w-100" href="/student">
            <i class="me-2 bi bi-people-fill">
            </i>Mahasiswa</a>
        </li>
        <li class="">
          <a class="fs-6 btn btn-link text-white text-decoration-none text-start w-100" href="/admin/users">
            <i class="me-2 bi bi-gear-fill">
            </i>User Management</a>
        </li>
        <li class="">
          <a class="fs-6 btn btn-link text-white text-decoration-none text-start w-100" href="/admin/enrollments">
            <i class="me-2 bi bi-archive-fill"></i></i>Enrollments</a>
        </li>
      <?php endif; ?>


      <?php if (in_groups('lecturer')): ?>
        <li class="">
          <a class="fs-6 btn btn-link text-white text-decoration-none text-start w-100" href="/course">
            <i class="me-2 bi bi-book-half">
            </i>Course</a>
        </li>
        <li class="">
          <a class="fs-6 btn btn-link text-white text-decoration-none text-start w-100" href="/academic-statistic">
            <i class="me-2 bi bi-bar-chart-fill">
            </i>Academic Statistic</a>
        </li>
      <?php endif; ?>

      <?php if (in_groups('student')): ?>
        <li class="">
          <a class="fs-6 btn btn-link text-white text-decoration-none text-start w-100" href="/my-profile">
            <i class="me-2 bi bi-person-circle"></i>My Profile</a>
        </li>
        <li class="">
          <a class="fs-6 btn btn-link text-white text-decoration-none text-start w-100" href="/enrollments">
            <i class="me-2 bi bi-archive-fill"></i></i>Enrollments</a>
        </li>
        <li class="">
          <a class="fs-6 btn btn-link text-white text-decoration-none text-start w-100" href="/dashboard/student">
            <i class="me-2 bi bi-bar-chart-fill"></i></i></i>Dashboard</a>
        </li>
      <?php endif; ?>


      <li class="">
        <a class="fs-6 btn btn-link text-white text-decoration-none text-start w-100" href="/logout"><i class="me-2 bi bi-box-arrow-in-left"></i>Logout</a>
      </li>
    <?php else: ?>
      <li class="">
        <a class="fs-6 btn btn-link text-white text-decoration-none text-start w-100" href="/login"><i class="me-2 bi bi-box-arrow-in-right"></i>Login</a>
      </li>
    <?php endif; ?>

  </ul>
</div>