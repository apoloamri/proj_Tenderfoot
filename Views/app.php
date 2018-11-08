<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tenderfoot</title>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.5.13/dist/vue.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://raw.github.com/openexchangerates/accounting.js/master/accounting.min.js"></script>
    <?php 
        if ($model->Environment == "Admin") 
        {
            echo '<link rel="stylesheet" type="text/css" href="/Resources/css/admin.css" />';
        }
        else
        {
            echo '<link rel="stylesheet" type="text/css" href="/Resources/css/style.css" />';
            echo '<link href="https://fonts.googleapis.com/css?family=Varela Round" rel="stylesheet" />';
        }
    ?>
</head>
<body>
    <div id="container">
        <?php $model->RenderPage(); ?>
    </div>
    <div id="loading" style="display:none;">
        <div class="display">Please wait...</div>
    </div>
</body>
</html>