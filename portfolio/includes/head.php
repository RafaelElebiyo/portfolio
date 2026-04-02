<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= Sanitizer::output($page_title ?? t('meta.title')) ?></title>
<meta name="description" content="<?= Sanitizer::output(t('meta.description')) ?>">
<meta name="theme-color" content="#121212">

<!-- Preconnect for performance -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<!-- Fonts: Outfit (headings) + Plus Jakarta Sans (body) -->
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<!-- Main CSS -->
<link href="assets/css/main.css" rel="stylesheet">

<!-- Favicon -->
<link rel="icon" href="assets/img/favicon.png" type="image/png">
