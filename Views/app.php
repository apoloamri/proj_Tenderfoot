<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tenderfoot</title>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.5.13/dist/vue.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <!-- <link rel="stylesheet" type="text/css" href="/Resources/css/style.css"> -->
    <link rel="stylesheet" type="text/css" href="/Resources/css/admin.css">
</head>
<body>
    <div id="container">
        <?php $model->RenderPage(); ?>
    </div>
    <div id="loading">
        <div class="display">Please wait...</div>
    </div>
</body>
</html>