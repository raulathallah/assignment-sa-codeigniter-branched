<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>
Dashboard
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">

  <div class="row">

    <!-- Pie Chart: Credit distribution by grade -->
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <div class="chart-container">
            <canvas id="gradeChart" height="200"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Bar Chart: Credits taken vs. credits required -->
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <div class="chart-container">
            <canvas id="creditChart" height="200"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Line Chart: GPA per Semester -->
    <div class="col-md-12 mt-4">
      <div class="card">
        <div class="card-body">
          <div class="chart-container">
            <canvas id="gpaChart" height="200"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>



  <div class="container mt-5">
    <h2 class="text-center mb-4">PDF REPORT</h2>
    <div class="card mb-4">
      <div class="card-body">
        <form class="row" action="<?= base_url('report/studentsbyprogram') ?>"
          method="post" target="_blank">
          <div class="col-md-3">
            <select class="form-control" name="study_program" required>
              <option value="">Pilih Program Studi</option>
              <?php foreach (['Computer Science', 'Finance'] as $program): ?>
                <option value="<?= $program ?>"> <?= $program ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-3">
            <select class="form-control" name="entry_year">
              <option value="">Pilih Tahun Masuk</option>
              <?php foreach (['2021', '2022', '2023', '2024', '2025'] as $year): ?>
                <option value="<?= $year ?>"> <?= $year ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">
              Generate Report</button>
          </div>
        </form>
      </div>
    </div>
  </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script>
  // Data dari controller

  const gpaData = <?= $gpaData ?>;

  const creditsByGrade = <?= $creditsByGrade ?>;

  const creditComparison = <?= $creditComparison ?>;

  const gradeChart = new Chart(

    document.getElementById('gradeChart'),

    {

      type: 'pie',

      data: creditsByGrade,

      options: {

        responsive: true,

        maintainAspectRatio: false,

        plugins: {

          title: {

            display: true,

            text: 'Credit Distribution by Grade'

          },

          legend: {

            position: 'right'

          }

        }

      }

    }

  );

  const creditChart = new Chart(

    document.getElementById('creditChart'),

    {

      type: 'bar',

      data: creditComparison,

      options: {

        responsive: true,

        maintainAspectRatio: false,

        scales: {

          y: {

            beginAtZero: true,

            title: {

              display: true,

              text: 'Credits'

            }

          },

          x: {

            title: {

              display: true,

              text: 'Semester'

            }

          }

        },

        plugins: {

          title: {

            display: true,

            text: 'Credits Taken vs. Credits Required by Semester'

          }

        }

      }

    }

  );

  const gpaChart = new Chart(

    document.getElementById('gpaChart'),

    {

      type: 'line',

      data: gpaData,

      options: {

        responsive: true,

        maintainAspectRatio: false,

        scales: {

          y: {

            min: 0,

            max: 4,

            title: {

              display: true,

              text: 'GPA'

            }

          },

          x: {

            title: {

              display: true,

              text: 'Semester'

            }

          }

        },
        plugins: {

          title: {

            display: true,

            text: 'Academic Progress (GPA per Semester)'

          },

          tooltip: {

            callbacks: {

              label: function(context) {

                return `GPA: ${context.raw}`;

              }

            }

          }

        }

      }

    }

  );
</script>

<?= $this->endSection() ?>