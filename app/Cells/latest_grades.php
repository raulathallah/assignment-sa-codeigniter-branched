<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <td>Course Code</td>
        <td>Course Name</td>
        <td>Grades</td>
    </thead>

    <?php foreach ($dataCourses as $row): ?>

        <tr>
            <td><?= $row['code'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['grade_value'] ?></td>
        </tr>

    <?php endforeach; ?>

</table>