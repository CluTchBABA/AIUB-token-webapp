function validateDets() {
    // Use RegExp objects and anchors so the whole string must match
    let teacher_account_pattern = /^\d{4}-\d{4}-[1-3]$/;

    let id = document.getElementById('id').value
    let pass = document.getElementById('password').value

    let errors = 0
    let error_msg = "";
 
    if (id === "") {
        errors ++;
        error_msg += "ID is empty";
    } else if (!teacher_account_pattern.test(id)) {
        errors ++;
        error_msg += "Invalid ID format";
    }

    if (pass.length === 0) {
        if (errors > 0) error_msg += " and ";
        errors++;
        error_msg += "Password cannot be empty."
    } 

    if (errors > 0) {
        alert(error_msg);
    }
    return (errors <= 0);
}
