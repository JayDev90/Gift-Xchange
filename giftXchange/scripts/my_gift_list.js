$(document).ready(function() {

    let isEditing = false;
    let userId = $('body').data('user-id');

    $("#returnToDashboard").click(function () {
        window.location.href = "/giftXchange/section/user_dashboard.php";
    });

    $("#createGiftList").click(function () {
        window.location.href = "/giftXchange/section/add_gift.php";
    });

    $("#editGifts").click(function () {
        isEditing = !isEditing;
        $(this).text(isEditing ? "Return" : "Edit");
        $("#addGift").toggle(isEditing);
        $("#saveGifts").toggle(isEditing);
        $(".gift-item").toggleClass("editing", isEditing);
        $(".gift-item span").attr("contenteditable", isEditing);
        if (!isEditing) {
            removeNewGiftInputFields();
        }
    });

    $("#addGift").click(function () {
        $("#giftList").append('<div class="gift-item new-gift-item"><input type="text" class="new-gift-input new-gift-input-style" placeholder="Type Gift or Paste URL"></div>');
    });

    $("#saveGifts").click(function () {
        const updatedGifts = $(".gift-item span").map(function () {
            return $(this).text();
        }).get();

        const newGifts = $(".new-gift-input").map(function () {
            return $(this).val();
        }).get();

        // Check if any new gift input field is empty
        if (newGifts.some(giftName => giftName.trim() === "")) {
            showModal("Please enter a gift or click return.");
            return;
        }

        $.ajax({
            type: "POST",
            url: "/giftXchange/section/update_gifts.php",
            data: {
                userId: userId,
                updatedGifts: updatedGifts,
                newGifts: newGifts
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    showModal(response.success);
                    $(".new-gift-item").remove();
                } else if (response.error) {
                    showModal(response.error);
                }
            },
            error: function (xhr, status, error) {
                showModal("An error occurred while updating the gift list. Status: " + status + ", error: " + error + ", response: " + xhr.responseText);
            }
        });
    });

    $("#giftList").on("click", ".delete-btn", function () {
        let giftItem = $(this).closest(".gift-item");
        let giftId = giftItem.data("gift-id");

        $.ajax({
            type: "POST",
            url: "/giftXchange/section/delete_gift.php",
            data: {
                giftId: giftId
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    giftItem.remove();
                    showModal(response.success);
                } else if (response.error) {
                    showModal(response.error);
                }
            },
            error: function (xhr, status, error) {
                showModal("An error occurred while deleting the gift. Please try again.");
            }
        });
    });

    function removeNewGiftInputFields() {
        $(".new-gift-item").remove();
    }

    // Function to display the modal with the specified message
    function showModal(message) {
        const modal = document.getElementById("myModal");
        const modalMessage = document.getElementById("modalMessage");
        modalMessage.innerHTML = message;
        modal.style.display = "block";
    }

    // Function to close the modal
    function closeModal() {
        const modal = document.getElementById("myModal");
        modal.style.display = "none";
    }

    // Event listener for the close button
    document.getElementsByClassName("close")[0].addEventListener("click", closeModal);

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        const modal = document.getElementById("myModal");
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
});
