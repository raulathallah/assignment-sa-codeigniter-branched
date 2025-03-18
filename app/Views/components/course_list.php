{courses}
<tr>
  <td>{code}</td>
  <td>{name}</td>
  <td>{credits}</td>
  <td>{semester}</td>
  <!-- 
      <td>{created_at}</td>
      <td>{updated_at}</td>
    -->
  <td>
    <a class="btn btn-sm btn-primary" href="/course/detail/{id}">Detail</a>
    <a class="btn btn-sm btn-warning" href="/course/edit/{id}">Edit</a>
    <a class="btn btn-sm btn-danger" href="/course/delete/{id}">Delete</a>
  </td>
</tr>
{/courses}