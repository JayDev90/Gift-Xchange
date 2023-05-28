$(document).ready(function() {
  function addGift() {
    var giftItem = $('<div class="gift-item">')
        .append($('<input type="text" name="gift" placeholder="Gift Name" class="gift-input">'))
        .append($('<button type="button" class="delete-gift"><i class="fa-solid fa-trash"></i></button>'));
    $('#giftList').append(giftItem);
  }

  function updateButtons() {
    if ($('#giftList .gift-item').length === 0) {
      $('#createGiftListBtn').text('Create Gift List');
      $('#saveGiftListBtn').hide();
      $('#resetWishlist').hide();
      $('#cancelWishlist').hide();
      $('h1').text('Hi ' + firstName + ', create your gift list here!');
    } else {
      $('#createGiftListBtn').text('Add Gift');
      $('#saveGiftListBtn').show();
      $('#resetWishlist').show();
      $('#cancelWishlist').show();
      $('h1').text(firstName + '\'s Gift List');
    }
  }

  function saveGiftList() {
    var gifts = $('#giftList .gift-item input').map(function () {
      return $(this).val();
    }).get();

    // Check if any input fields are empty
    var emptyFields = gifts.some(function (gift) {
      return gift.trim() === '';
    });

    if (emptyFields) {
      // Show an error message if any input field is empty
      $('#emptyFieldsError').remove(); // Remove any existing error message
      $('<p id="emptyFieldsError" style="color: red; font-size: large; font-weight: bold">Fields cannot be empty.</p>').insertBefore('#saveGiftListBtn');
    } else {
      // Proceed with saving the gift list
      $.ajax({
        url: 'gift_list.php',
        method: 'POST',
        data: {
          gifts: JSON.stringify(gifts),
          userId: userId
        },
        success: function (response) {
          console.log(response);
          if (response === 'success') {
            alert('Gift list saved successfully!');
            clearGiftList();
            updateButtons();
          } else {
            alert('Error saving gift list. Please try again.');
          }
        },
        error: function () {
          alert('Error saving gift list. Please try again.');
        }
      });
    }
  }

  function clearGiftList() {
    $('#giftList').empty();
  }

  $('#createGiftListBtn').click(function() {
    addGift();
    updateButtons();
  });

  $('#giftList').on('click', '.delete-gift', function() {
    $(this).parent().remove();
    updateButtons();
  });

  // You can add your code for saving the gift list, resetting, and canceling here.
  $('#saveGiftListBtn').click(function() {
    saveGiftList();
  });

  $('#resetWishlist').click(function() {
    $('#giftList .gift-item input').val('');
  });

  $('#cancelWishlist').click(function() {
    clearGiftList();
    updateButtons();
    $('#emptyFieldsError').remove();
  });

  $('#goToGiftListBtn').click(function() {
    location.href = "/giftXchange/section/my_gift_list.php";
  });
});
