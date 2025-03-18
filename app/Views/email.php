<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Email Template</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
        }

        .header {
            background-color: #f5f5f5;
            padding: 10px;
            text-align: center;
        }

        .content {
            padding: 20px;
        }

        .footer {
            background-color: #f5f5f5;
            padding: 10px;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Pesan untuk Anda</h1>
        </div>
        <div class="content">
            <h2>Halo, <?= $name ?></h2>
            <p><?= $content ?></p>
            <p>Terima kasih telah membaca email ini.</p>
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Course Information</strong>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <?php foreach ($features as $feature): ?>
                            <li><?= $feature ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer">
            <p>Email ini dikirim otomatis. Mohon jangan membalas email ini.</p>
            <p>&copy; <?= date('Y') ?> Nama Perusahaan Anda</p>
        </div>
    </div>

</body>

</html>