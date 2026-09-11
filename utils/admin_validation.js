function validateRoomForm() {
    let roomName = document.getElementById("room_name").value.trim();
    let capacity = document.getElementById("capacity").value.trim();

    if (roomName === "") {
        alert("Room name is required.");
        return false;
    }
    if (capacity === "" || isNaN(capacity) || parseInt(capacity) <= 0) {
        alert("Please enter a valid capacity.");
        return false;
    }
    return true;
}