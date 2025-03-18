{students}
<tr>
  <td>{student_id}</td>
  <td>{name}</td>
  <td>{study_program}</td>
  <td>{gpa}</td>
  <td>{current_semester}</td>
  <td>{entry_year}</td>
  <td>{academic_status}</td>

  <!--  
     <td>{academic_status}</td>
      <td>
        <ul>
          {courses}
            <li>{courseName}</li>
          {/courses}
        </ul>
      </td>
    -->

  <td>
    <a class="btn btn-sm btn-primary" href="/student/detail/{id}">Detail</a>
    <a class="btn btn-sm btn-warning" href="/student/edit/{id}">Edit</a>
    <a class="btn btn-sm btn-danger" href="/student/delete/{id}">Delete</a>
  </td>
</tr>
{/students}