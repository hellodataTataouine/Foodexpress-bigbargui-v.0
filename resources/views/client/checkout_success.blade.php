<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="title" content="{{ $seo->title }}">
    <meta name="description" content="{{ $seo->description }}">
    <meta name="keywords" content="{{ $seo->keywords }}">
    <meta name="robots" content="{{ $seo->robots }}, {{ $seo->follow_links}}">
    <meta http-equiv="Content-Type" content="{{ $seo->content_type }}">
    <meta name="language" content="{{ $seo->language }}">
    <meta property="og:image" content="uploads/seo/{{ $seo->image}}">
  <meta name="author" content="cipherlink">

    <title>Big Time F</title>
    <link rel="icon" type="image/x-icon" href="images/img/bigtime-icon.png">
</head>
<body>
    @include('client.layouts.top_menu_client')
<!-- Aside (Mobile Navigation) -->
@include('client.layouts.header_menu')

<!-- Cart Items Start -->
<div class="section">
    <div class="container">
        @if (Session::has('success'))
    <div class="alert alert-success">
        {{ Session::get('success') }}
    </div>
@endif

@if (Session::has('error'))
    <div class="alert alert-danger">
        {{ Session::get('error') }}
    </div>
@endif
		<br>
		<br>
		<br>
		<br>

            <p>Votre Commande est confirmée, merci pour votre confiance.</p>
    </div>
</div>
<!-- Cart Items End -->

@include('client.layouts.footer_client')

</body>
</html>
