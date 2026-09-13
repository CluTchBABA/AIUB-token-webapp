function validateProfileUpdate() {
    var name = document.getElementById("fullname").value.trim();
    var password = document.getElementById("password").value.trim();
    var cpassword = document.getElementById("cpassword").value.trim();

    if (name === ""||password === ""||cpassword === "") {
        alert("Please fill in all fields.");
        return false;
    }
    if (password !== cpassword) {
        alert("Passwords do not match.");
        return false;
    }
    if(password.length < 3) {
        alert("Password must be at least 3 characters long.");
        return false;
    }

    return true;
}