<!DOCTYPE html>
<html lang="<?php echo $root['temp']['temp_lang']; ?>" dir="ltr">

<head>
    <title><?php echo $root['link']['link_meta_title']; ?></title>
    <link rel="icon" type="image/x-icon" href="/assets/default/image/primoexecutive.ico">
    <link rel="icon" type="image/png" href="/assets/default/image/primoexecutive.ico">
    <meta http-equiv="content-language" content="<?php echo $root['temp']['temp_lang']; ?>">
    <meta charset="UTF-8">

    <!-- Responsive -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <!--[if lt IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script><![endif]-->
    <!--[if lt IE 9]><script src="js/respond.js"></script><![endif]-->

    <meta property="og:url" content="<?php
    echo $_SERVER['REQUEST_SCHEME'];
    echo "://";
    echo $_SERVER['SERVER_NAME'];
    echo $_SERVER['REQUEST_URI'];
    ?>" />

    <meta property="og:type" content="website" />
    <meta property="og:locale" content="en_GB" />

    <?php

    if (!isset($root['shemas']))
        $root['shemas'] = array();
    foreach ($root['shemas'] as $key => $shema) {
        echo '<script type="application/ld+json">';
        echo json_encode($shema, JSON_UNESCAPED_SLASHES);
        echo '</script>';
    }

    ?>

    <?php
    print_css_js("header");
    ?>

</head>

<body>

    <!-- PAGE LOADER / START -->
    <?php include_once (__DIR__ . '/../default/assets/page-loader.php'); ?>
    <!-- PAGE LOADER / END -->

    <div class="boxcar-wrapper v2">

        <!-- HEADER / START -->
        <?php
        if ($root['user']['user_group_id'] === 1)
            include_once ('assets/header-admin.php');
        else
            include_once (__DIR__ . '/../client/assets/header-user.php');
        ?>
        <!-- HEADER / END -->


        <!-- DYNAMIC PAGE CONTENT / START -->
        {{pagesContent}}
        <!-- DYNAMIC PAGE CONTENT / END -->


        <!-- FOOTER / START -->
        <?php include_once ('assets/footer.php'); ?>
        <!-- FOOTER / END -->

    </div>

    <!-- DEFAULT ASSETS / START -->
    <?php include_once (__DIR__ . '/../default/assets/modal-alert.php') ?>
    <!-- DEFAULT ASSETS / END -->

    <?php

    print_css_js("footer");

    ?>

    <script>
        //get globals from the server
        lang = <?= json_encode($lang, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
        if (typeof A1 === 'undefined') A1 = {};
        {
            {
                A1DATA
            }
        }
    </script>

</body>

</html>