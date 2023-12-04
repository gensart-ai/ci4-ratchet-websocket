<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="container my-5">
        <div class="card bg-primary text-white">
            <div class="card-header">
                <div class="row d-flex align-items-center">
                    <div class="col-9">
                        <h4 class="card-title">Too-Simple Chat Application</h4>
                        <p class="card-text">Made by gensart</p>
                        <p class="card-text">
                            Username anda : <span class="badge bg-success"><?= session('username'); ?></span>
                        </p>
                    </div>
                    <div class="col-3 text-end">
                        <a href="<?= base_url('logout'); ?>" class="btn btn-danger">Keluar</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="card">
                    <div class="card-body">
                        <div id="message-dock" class="overflow-auto"></div>
                        <footer class="footer mt-auto">
                            <div class="row">
                                <div class="col-9">
                                    <input type="text" class="form-control" id="message" placeholder="Type some message...">
                                </div>
                                <div class="col-3 d-grid">
                                    <button type="button" class="btn btn-success" onclick="sendMessage()">Kirim</button>
                                </div>
                            </div>
                        </footer>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script>
    var websocket = new WebSocket('wss://192.168.0.103:81?username=<?= session('username') ?>');
    // websocket.onopen
    websocket.onmessage = e => {
        var data = JSON.parse(e.data);
        if ('message' in data && 'username' in data) {
            document.getElementById('message-dock').innerHTML += `
            <p>
                <strong>${data.username}</strong> : ${data.message} (${data.time})
            </p>
            `
        }
    }

    // Detect if Enter key is pressed
    document.getElementById('message').onkeydown = e => {
        if (e.keyCode == 13) {
            sendMessage()
        }
    }

    const sendMessage = _ => {
        const message = document.getElementById('message').value
        websocket.send((message == '' ? '-' : message))
        document.getElementById('message').value = ''
    }

    window.Notification.requestPermission().then(permission => {
        console.log(permission)
        alert('idk what to do')
    })
</script>

</html>