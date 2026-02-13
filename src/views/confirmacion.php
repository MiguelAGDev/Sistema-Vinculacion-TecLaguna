<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <link rel="stylesheet" href="/assets/css/global.css">
     <link rel="stylesheet" href="/assets/css/confirmacion.css">

</head>
<body>
    <div class="confirm-box">
    <h2>Confirm</h2>

    <p>
        You are already logged in as
        <strong>
            <?= $resultado['usuario']['id'] ?>
            <?= $resultado['usuario']['name'] ?>
        </strong>,
        you need to log out before logging in as a different user.
    </p>

    <div class="actions">
        <form method="POST" action="index.php?url=auth/logout">
            <button type="submit" class="btn-primary">Log out</button>
        </form>

        <a href="index.php" class="btn-secondary">Cancel</a>
    </div>
</div>

</body>
</html>