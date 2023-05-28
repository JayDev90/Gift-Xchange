let selectedGiftName = '';
let selectedStatus = '';
let selectedDropdown = null;

function showSaveButton(giftName, dropdown) {
    selectedGiftName = giftName;
    selectedDropdown = dropdown;
    document.getElementById("saveButton").style.display = "inline-block";
}

function updateStatus(giftName, newStatus) {
    selectedGiftName = giftName;
    selectedStatus = newStatus;
}

function replaceDropdownWithText(text) {
    const textNode = document.createTextNode(text);
    selectedDropdown.parentNode.replaceChild(textNode, selectedDropdown);
}

function saveUpdatedStatus() {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "update_gift_status.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            alert(this.responseText);
            document.getElementById("saveButton").style.display = "none";
            if (selectedStatus !== "Available") {
                selectedDropdown.style.display = "none";
            }
        }
    };

    xhr.send("giftName=" + encodeURIComponent(selectedGiftName) + "&status=" + encodeURIComponent(selectedStatus));
}