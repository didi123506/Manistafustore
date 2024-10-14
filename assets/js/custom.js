$(document).ready(function () {

    // Increment button functionality
    $('.increment-btn').on('click', function(e) {
        e.preventDefault();
        var qty = $(this).closest('.product_data').find('.input-qty');
        var value = parseInt(qty.val(), 10) || 1;
        if (value < 10) {
            qty.val(value + 1);
        }
    });

    // Decrement button functionality
    $('.decrement-btn').on('click', function(e) {
        e.preventDefault();
        var qty = $(this).closest('.product_data').find('.input-qty');
        var value = parseInt(qty.val(), 10) || 1;
        if (value > 1) {
            qty.val(value - 1);
        }
    });

    // Add to Cart functionality
    $('.addTocartBtn').on('click', function(e) {
        e.preventDefault();
        var product_data = $(this).closest('.product_data');
        var qty = product_data.find('.input-qty').val();
        var prod_id = $(this).val();
        $.ajax({
            method: "POST",
            url: "functions/handlecart.php",
            data: {
                'prod_id': prod_id,
                'prod_qty': qty,
                'scope': 'add'
            },
            success: function(response) {
                if (response == 201) {
                    alertify.success("Product added to cart");
                } else if (response == "existing") {
                    alertify.success("Product already in cart");
                } else if (response == 401) {
                    alertify.error("Login to continue");
                } else {
                    alertify.error("Something went wrong");
                }
            }
        });
    });

    // Update quantity
    
    $('.removeFromCartBtn').click(function() {
        var prodId = $(this).val(); // Get the product ID from the button value
        var $row = $(this).closest('tr'); // Get the row to remove

        $.ajax({
            url: 'functions/handlecart.php',
            type: 'POST',
            data: {
                prod_id: prodId,
                scope: 'remove'
            },
            success: function(response) {
                if(response == 200) {
                    $row.fadeOut(500, function() {
                        $(this).remove();
                        updateTotal();  // Update the total when a row is removed
                    });
                } else {
                    alert('Error removing item.');
                }
            }
    });
    });
    });


    // Remove from Cart functionality
   
        
  
    

    

