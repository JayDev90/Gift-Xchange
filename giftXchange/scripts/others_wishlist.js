$(document).ready(function() {
    var currentOpen = null; // Variable to store currently opened item

    $('.user-container').on('click', '.user-item', function() {
        const userId = $(this).data('userid');
        const giftList = $(this).next('.gift-list');
        const giftListButton = giftList.next('.gift-list-button');

        // If a different item is clicked, hide the currently opened item
        if (currentOpen !== null && currentOpen.get(0) !== giftList.get(0)) {
            currentOpen.slideUp();
            currentOpen.next('.gift-list-button').hide();
        }

        if (!giftList.hasClass('loaded')) {
            $.ajax({
                url: 'fetch_gifts.php',
                method: 'POST',
                data: { userId: userId },
                dataType: 'json',
                success: function(gifts) {
                    if (gifts.length > 0) {
                        giftList.append('<div class="gift-header"><span>Gift</span><span>Status</span></div>');
                        for (let gift of gifts) {
                            let displayStatus = gift.status === null ? "Available" : gift.status;
                            let statusClass = 'gift-status-' + displayStatus.toLowerCase().replace(' ', '-');
                            giftList.append('<div class="gift-item"><span>' + gift.name + '</span><span class="' + statusClass + '">' + displayStatus + '</span></div>');
                        }
                    } else {
                        giftList.append('<div class="gift-item-empty"><span>Gift list is empty</span></div>');
                    }
                    giftList.addClass('loaded');
                }
            });
        }

        // Toggle display of clicked item and update currentOpen
        giftList.slideToggle(function() {
            giftListButton.toggle();
            currentOpen = giftList.is(":visible") ? giftList : null;
        });
    });

    $('.gift-list-button').click(function(event) {
        event.stopPropagation();
        const userId = $(this).prev('.gift-list').prev('.user-item').data('userid');
        window.location.href = 'others_gifts.php?userId=' + userId;
    });
});
