<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ShopPin</title>
    
    <?php if ($model->Deployment == "production") { ?>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/vue"></script>
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <?php } else { ?>
        <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
        <script src="https://unpkg.com/axios/dist/axios.js"></script>
    <?php } ?>

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.11/lodash.min.js"></script>
    <script src="/Resources/js/common.js"></script>

    <?php if ($model->Environment == "admin") { ?>
        <link rel="stylesheet" type="text/css" href="/Resources/css/admin.css" />
    <?php } else { ?>
        <link rel="stylesheet" type="text/css" href="/Resources/css/default.css" />
        <link rel="stylesheet" type="text/css" href="/Resources/css/default-cart.css" />
        <link rel="stylesheet" type="text/css" href="/Resources/css/default-contents.css" />
        <link rel="stylesheet" type="text/css" href="/Resources/css/default-navigation.css" />
        <link href="https://fonts.googleapis.com/css?family=Arimo" rel="stylesheet">
    <?php } ?>
</head>
<body>
    <div id="container">
        <?php $model->RenderPage(); ?>
    </div>

    <?php if ($model->Environment == "admin") { ?>
        <div id="loading" style="display:none;">
            <div class="display">Please wait...</div>
        </div>
    <?php } else { ?>
        <div id="loading" style="display:none;"></div>
    <?php } ?>
</body>
</html>