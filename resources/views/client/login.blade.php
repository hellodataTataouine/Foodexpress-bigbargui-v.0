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

  <title>Big Time F | Se connecter</title>
  <link rel="icon" type="image/x-icon" href="images/img/bigtime-icon.png">
</head>
<body>
  @include('client.layouts.top_menu_client')
  <!-- Cart Sidebar Start -->
  @include('client.layouts.cart_client')
  <!-- Cart Sidebar End -->

  @include('client.layouts.header_menu')
  <!-- Header End -->
<style>
.fa-google {
  background: conic-gradient(from -45deg, #ea4335 110deg, #4285f4 90deg 180deg, #34a853 180deg 270deg, #fbbc05 270deg) 73% 55%/150% 150% no-repeat;
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
  -webkit-text-fill-color: transparent;

}
	</style>
  <!-- Login Form Start -->
  <div class="section">

    <div class="imgs-wrapper">
     <!-- <img src="{{ asset('assetsClients/img/veg/11.png') }}" alt="veg" class="d-none d-lg-block"> -->
    </div>

    <div class="container">
        <a href="/" class="btn-custom">Retour</a>
        <br></br>
      <div class="auth-wrapper">

        <div class="auth-description bg-cover bg-center dark-overlay dark-overlay-2" style="background-image: url('/assetsClients/img/bg-login.jpg')">
          <div class="auth-description-inner">
            <i class="flaticon-chili"></i>
            <h2>Bienvenue!</h2>

          </div>
        </div>
        <div class="auth-form">
          @if ($errors->any())
          <div class="alert alert-danger">
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
      @endif
          <h2>Se connecter</h2>


          <form method="post" action="{{ route('client.login.submit') }}">
            @csrf
            <div class="form-group">
              <input type="text" class="form-control form-control-light" placeholder="email"  id="email" name="email">
            </div>
            <div class="form-group">
              <input type="password" class="form-control form-control-light" placeholder="Password" id="password" name="password" >
            </div>
          <!--  <a href="#">Mot de passe oublié?</a> -->
            <button type="submit" class="btn-custom primary">Se connecter</button>



			  <div class="form-group">
                  <a href="{{ route('register.google') }}" class="btn  btn-social">
            <i class="fab fa-google  fa-2x"></i> Se connecter avec Google
        </a>


</div>




            <p>Vous n'avez pas déjà un compte ? <a href="{{ url('client/register') }}">Créez-en un</a> </p>

          </form>
        </div>

      </div>
    </div>
  </div>
  <!-- Login Form End -->
@include('client.layouts.footer_client')
</body>
</html>
