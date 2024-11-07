<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Réserver</title>
    <meta name="title" content="{{ $seo->title }}">
    <meta name="description" content="{{ $seo->description }}">
    <meta name="keywords" content="{{ $seo->keywords }}">
    <meta name="robots" content="{{ $seo->robots }}, {{ $seo->follow_links}}">
    <meta http-equiv="Content-Type" content="{{ $seo->content_type }}">
    <meta name="language" content="{{ $seo->language }}">
    <meta property="og:image" content="uploads/seo/{{ $seo->image}}">
    <meta name="author" content="cipherlink">
    <link rel="icon" type="image/x-icon" href="images/img/bigbargui.png">
</head>
<body>

@include('client.layouts.top_menu_client')
<!-- Cart Sidebar Start -->
@include('client.layouts.cart_client')
<!-- Aside (Mobile Navigation) -->
@include('client.layouts.header_menu')
<br>
<br>
<!-- Customize Modal Start -->
<div class="modal fade" id="customizeModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">

      <div class="modal-header modal-bg">
          <button type="button" class="close-btn" data-dismiss="modal" aria-label="Close">
        <span></span>
        <span></span>
      </button>
      </div>
      <div class="modal-body">
        <div class="customize-meta">

          <p ></p>
        </div>
        <div class="customize-variations">
          <div class="customize-size-wrapper">
            <!-- Size variations -->
          </div>
          <div class="row">
          </div>
          <!-- Other customization options -->
        </div>
        <div class="customize-controls">

          </div>

        </div>


       </div>

    </div>
  </div>
<br>
<br>
<!-- Customize Modal End -->
<div class="section">

  <div class="imgs-wrapper">
    <img src="assets/img/veg/11.png" alt="veg" class="d-none d-lg-block">
    <img src="assets/img/prods/3.png" alt="veg" class="d-none d-lg-block">
  </div>

  <div class="container">
    <div class="auth-wrapper">

      <div class="auth-description bg-cover bg-center dark-overlay dark-overlay-2" style="background-image: url(&quot;https://foodexpress.site/uploads//reservationbanner.png?v=v2.7.0&quot;);">
        <div class="auth-description-inner">
          <i class="fa fa-table"></i>
          <h2>Big Bargui Reservation!</h2>
                 <p>Réservez une table et profitez d'une expérience culinaire exceptionnelle dans notre restaurant. Choisissez le nombre de personnes, l'heure, la date, et découvrez nos tables disponibles.</p>

        </div>
      </div>
         <form method="post" action="{{ route('reservation.client.store') }}">

          @csrf
   <div class="auth-form">
  <h2>Réserver une Table</h2>

  @csrf
<div class="user-info-section">
  <div class="row">
      <div class="form-group col-xl-6">
          <label for="FirstName">Nom</label>
          <input type="text" class="form-control" id="FirstName" name="FirstName" required value="{{ optional($clientRestaurant)->FirstName }}">
          @error('FirstName')
              <div class="alert alert-danger">{{ $message }}</div>
          @enderror
      </div>

      <div class="form-group col-xl-6">
          <label for="LastName">Prénom</label>
          <input type="text" class="form-control" id="LastName" name="LastName" required value="{{ optional($clientRestaurant)->LastName }}">
          @error('LastName')
              <div class="alert alert-danger">{{ $message }}</div>
          @enderror
      </div>
  </div>
<div class="row">
   <div class="form-group col-xl-6">
      <label for="Email">Email</label>
      <input type="email" class="form-control" id="Email" name="Email" required value="{{ optional($clientRestaurant)->email }}">
      @error('Email')
          <div class="alert alert-danger">{{ $message }}</div>
      @enderror
  </div>
  <div class="form-group col-xl-6">
      <label for="phoneNum1">N° Téléphone</label>
      <input type="text" class="form-control" id="phoneNum1" name="phoneNum1" required value="{{ optional($clientRestaurant)->phoneNum1 }}">
      @error('Téléphone')
          <div class="alert alert-danger">{{ $message }}</div>
      @enderror
  </div>


   </div>
</div>

        <br>
       <div class="booking-info-section">
  <div class="row">
      <div class="form-group col-xl-6">
          <label for="nbre_personnes">Nombre de Personnes</label>
          <input type="number" class="form-control" id="nbre_personnes" name="nbre_personnes" min="1" max="10" value="1" required>
          @error('nbre_personnes')
              <div class="alert alert-danger">{{ $message }}</div>
          @enderror
      </div>
       <div class="form-group col-xl-6">
      <label for="date">Date</label>
      <input type="date" class="form-control" id="date" name="date" required>
      @error('date')
          <div class="alert alert-danger">{{ $message }}</div>
      @enderror
  </div>
  </div>

  <div class="row">
      <div class="form-group col-xl-6">
          <label for="heure_debut">Heure Début</label>
          <input type="time" class="form-control" id="heure_debut" name="heure_debut" required>
          @error('heure_debut')
              <div class="alert alert-danger">{{ $message }}</div>
          @enderror
      </div>

      <div class="form-group col-xl-6">
          <label for="heure_fin">Heure Fin</label>
          <input type="time" class="form-control" id="heure_fin" name="heure_fin" required>
          @error('heure_fin')
              <div class="alert alert-danger">{{ $message }}</div>
          @enderror
      </div>
  </div>


</div>
 <!-- <a class="btn-custom btn-sm shadow-none customizeBtn" data-bs-toggle="modal" data-bs-target="#customizeModal"
      data-nbre-personne="{{ old('nbre_personnes') }}" data-heure-debut="{{ old('heure_debut') }}"
      data-heure-fin="{{ old('heure_fin') }}" data-date="{{ old('date') }}" style="background-color: #0F9D58;" hidden>
      Verifier <i class="fas fa-table"></i>
  </a>-->
                   <button type="submit" class="btn-custom primary">Réserver</button>

</div>


  </div>
</div>







@include('client.layouts.footer_client')



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<style>
  .customize-variation-wrapper.selectable {
      cursor: pointer; /* Change the cursor to a pointer when hovering */
  }

  .customize-variation-wrapper.selected {
      border: 2px solid #007bff; /* Style the selected element with a border, change color as needed */
  }
</style>
<script>
  document.getElementById('date').valueAsDate = new Date();
  const d = new Date();
  document.getElementById('heure_debut').value = (d.getHours()+1)+":"+d.getMinutes();
  document.getElementById('heure_fin').value = (d.getHours()+2)+":"+(d.getMinutes()+30);
</script>
</body>
</html>
