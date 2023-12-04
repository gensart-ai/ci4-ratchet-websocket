<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body style="height: 100%;">
    <div style="height:100vh;" class="container d-flex flex-column justify-content-center align-items-center">
        <h4 class="mb-3">Welcome to Room Chat</h4>
        <div class="card">
            <div class="card-header bg-secondary">
                <h6 class="text-white">
                    Daftarkan Username anda untuk join Room Chat
                </h6>
            </div>
            <div class="card-body">
                <form action="<?= base_url('login'); ?>" method="POST">
                    <div class="mb-3">
                        <input type="text" required class="form-control" name="username" placeholder="Isikan username baru anda...">
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Join</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</html>