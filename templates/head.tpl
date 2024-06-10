<?php echo $Main->outFavicon(); ?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="csrf-token" content="<?php echo csrf_token(); ?>">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&family=Roboto:wght@100;300&display=swap" rel="stylesheet">
<link rel="manifest" href="<?php echo $config['urlPath'] ?>/manifest.json">
<?php echo $settings["header_meta"]; ?>
<?php echo $Main->assets($config["css_styles"], 'css'); ?>