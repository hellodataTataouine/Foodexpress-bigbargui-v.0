<style>

	.cart-sidebar-item-meta span {
    display: block; /* Set to block-level element to ensure wrapping */
   max-width:      350px; /* Limit the maximum width of the description */
    word-wrap: break-word; /* Allow long words to be wrapped to the next line */
}
.media {
    display: block; /* Set to block-level element to ensure wrapping */
   max-width:      75px; /* Limit the maximum width of the description */
    word-wrap: break-word; /* Allow long words to be wrapped to the next line */
}  
.btn-custom.btnconfirm[disabled] {
  opacity: .4;
  cursor: not-allowed;
  pointer-events: none;
} 
</style>
<div class="cart-sidebar-wrapper" >
  <aside class="cart-sidebar">
    <div class="cart-sidebar-header">
      <h3>Votre Panier</h3>
      <div class="close-btn cart-trigger close-dark" > <!-- Add ID to the close button -->
   <span></span>
        <span></span>
      </div>
    </div>
    <div class="cart-sidebar-body">
      <div class="cart-sidebar-scroll">
      @php
          $totalPrice = 0; // Initialize total price
        @endphp
        @if(session()->has('cart'))
          @foreach(session('cart') as $index => $cartItem)
            <div class="cart-sidebar-item">
              <div class="media">
                <a><img src="{{ asset($cartItem['image']) }}" alt="product"></a>
                <div class="media-body">
                  <h5> <a title="{{ $cartItem['name'] }}">{{ $cartItem['name'] }}</a> </h5>
                  <span>{{ $cartItem['quantity'] }}x {{ $cartItem['unityPrice'] }}€</span>
                </div>
              </div>
              <div class="cart-sidebar-item-meta">
  <span>
    @if(is_string($cartItem['options']))
      {{ htmlspecialchars($cartItem['options']) }}
    @endif
  </span>
				
</div>
				  
              <div class="cart-sidebar-price">
                {{ $cartItem['price'] }}€
              </div>
			
				 <div class="customizeBtnedit" data-bs-toggle="modal" data-bs-target="#customizeModal"
                        data-product-id-edit="{{ $cartItem['id'] }}" data-product-name="{{ $cartItem['name'] }}"
                        data-product-image="{{ $cartItem['image'] }}" data-product-item="{{ $cartItem['idItem'] }}" data-product-price="{{ $cartItem['price'] }}" data-product-qauntity="{{ $cartItem['quantity'] }}"  data-product-options='{{ json_encode($cartItem['options']) }}' >
                <i class="fas fa-edit"></i> 
               
              </div>
              <div class="remove-btn" data-item-id="{{ $cartItem['id'] }}" >
                <i class="fas fa-times"></i> 
                <span></span>
                <span></span>
              </div>
				
            </div>
            @php
              $totalPrice += $cartItem['price']; // Update total price for each item
            @endphp
          @endforeach
        @else
          <p>Aucun article dans votre panier..</p>
        @endif
      </div>
    </div>
    <div class="cart-sidebar-footer">
    <h4>Total: <span>{{ $totalPrice }}€</span> </h4>
    <input type="text" id="mincmd" value="{{ session('mincmd')}}" disabled hidden>
    <input type="text" id="totalepricetext" disabled hidden>
    @if(session()->has('mincmderror'))
        <div class="alert alert-danger d-flex align-items-center" role="alert">
          <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
          <div>
            {{session('mincmderror')}}
          </div>
        </div>
    @endif
    <div class="alert alert-warning" role="alert" id="alert-msg-mincmd">
      Votre commande n'a pas atteint le montant minimum requis ({{ session('mincmd')}}) €.
    </div>
      <a href="{{ url('/checkout') }}" class="btn-custom btnconfirm" disabled="disabled">Confirmer</a>
    </div>
  </aside>
  <div class="cart-sidebar-overlay cart-trigger">
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>


$(document).ready(function() {
  disableBTN(0);
  
$(document).on("click", ".remove-btn", function () {
             var button = $(this); // Get the clicked button
        var productId = button.data('item-id');

            
            $.ajax({
                url: '{{ route("remove.CartItem") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            productId: productId,
        },
                success: function (response) {
                    // Update the UI and total price based on the response
                    updateCartSidebar();
                     // Update the cart item count in the header
     var cartItemCount = $('.cart-item-count');
    cartItemCount.text(response.cartItemCount);
                    var totalPriceElement = $('.totalprice span');
                     // Remove the row from the table
                     totalPriceElement.text(response.totalPrice + '€'); // Update total price
                     disableBTN(parseFloat(response.totalPrice));
                     
                },
                error: function (error) {
                    console.error('Error removing item:', error);
                    // Handle error gracefully
                }
            });
        });

        function updateCartSidebar() {

$('body').addClass('disable-interaction');
  // Make an AJAX request to fetch the updated cart data
  $.ajax({
    url: '{{ route("cart.fetch") }}',
    method: 'GET',
    success: function(response) {
      $('body').removeClass('disable-interaction');
      //console.log(response.totalPrice);
      



    // Update the cart sidebar with the updated cart items
  var cartSidebarScroll = $('.cart-sidebar-scroll');
  cartSidebarScroll.empty(); // Clear existing content

  // Loop through the cart items and add them to the sidebar
  $.each(response.cartItems, function(index, cartItem) {
  var itemHTML = '<div class="cart-sidebar-item">';
  itemHTML += '<div class="media">';
  itemHTML += '<a href="menu-item-v1.html"><img src="' + cartItem.image + '" alt="product"></a>';
  itemHTML += '<div class="media-body">';
  itemHTML += '<h5><a href="menu-item-v1.html" title="' + cartItem.name + '">' + cartItem.name + '</a></h5>';
  itemHTML += '<span>' + cartItem.quantity + 'x ' + cartItem.unityPrice + '€</span>';
  itemHTML += '</div></div>';
  itemHTML += '<div class="cart-sidebar-item-meta">';
  
  // Check if cartItem.options is a non-empty string
  if (typeof cartItem.options === 'string' && cartItem.options.trim() !== '') {
      itemHTML += '<span>' + cartItem.options + '</span>';
  }
  
  itemHTML += '</div>';
  itemHTML += '<div class="cart-sidebar-price">';
  itemHTML += cartItem.price + '€';
  itemHTML += '</div>';
	
  itemHTML += '<div class="customizeBtnedit" data-bs-toggle="modal" data-bs-target="#customizeModal" ' +
    'data-product-id-edit="' + cartItem.id + '" ' +
	  
    'data-product-name="' + cartItem.name + '" ' +
    'data-product-image="' + cartItem.image + '" ' +
    'data-product-price="' + cartItem.price + '" ' +
	  'data-product-qauntity="' + cartItem.quantity + '" ' +
	    'data-product-item="' + cartItem.idItem + '" ' +
    'data-product-options=\'' + JSON.stringify(cartItem.options) + '\'>';

// Add the button icon
itemHTML += '<i class="fas fa-edit"></i>';

// Close the customizeBtn div element
itemHTML += '</div>';
  itemHTML += '<div class="remove-btn" data-item-id="' + cartItem.id + '">';
  itemHTML +=  '<i class="fas fa-times"></i>';
  itemHTML += '<span></span>';
	  
	  
	  
	
	
	   
  itemHTML += '</div></div>';

  cartSidebarScroll.append(itemHTML);
});
   // Update the cart item count in the header
   var cartItemCount = $('.cart-item-count');
  cartItemCount.text(response.cartItemCount);

  var totalPriceElement = $('.cart-sidebar-footer span');
totalPriceElement.text(response.totalPrice + '€');
disableBTN(parseFloat(response.totalPrice));
      // Show the cart sidebar
      $('#cartSidebarWrapper&').addClass('active');
    },
    error: function(error) {
      // Handle the error response from the server
      console.error('Error fetching cart data:', error);
      $('body').removeClass('disable-interaction');
      // Show an error message or handle the error gracefully
    }
  });
} 
  
});
function disableBTN(tp){
    var mincmd = parseFloat($('#mincmd').val());
    if (tp==0){
      
    
      var totprice= parseFloat(($('.cart-sidebar-footer span').text()).replace('€',''));
      if(totprice>mincmd){
        $('.btn-custom.btnconfirm').removeAttr('disabled');
        $('.alert.alert-danger.d-flex.align-items-center').hide();
      }else {
    
        $('.btn-custom.btnconfirm').prop('disabled',true);
      }
    }else{
      if(tp>mincmd){
        $('.btn-custom.btnconfirm').removeAttr('disabled');
        $('.alert.alert-danger.d-flex.align-items-center').hide();
      }else{
    
          $('.btn-custom.btnconfirm').prop('disabled',true);
        }
      
      
    }
  }
	
</script>