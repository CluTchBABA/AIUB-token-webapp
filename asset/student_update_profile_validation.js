alert("JS file loaded!");
function validateProfileUpdate() {
    var name = document.getElementById("fullname").value;
    var password = document.getElementById("password").value;
    var cpassword = document.getElementById("cpassword").value;

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