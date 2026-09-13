function validateStudentLogin() {
    var sid = document.getElementById("id").value;
    var password = document.getElementById("password").value;

    if (sid === ""||password === "") {
        alert("Please fill in both Student ID and Password.");
        return false;
    }
    return true;
}