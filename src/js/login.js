function validateEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

function validatePassword(password) {
    return password.length >= 8;
}

function updateLoginButton() {
    if (
        validateEmail($("#email").val()) &&
        validatePassword($("#password").val())
    ) {
        $("button[type='submit']").prop("disabled", false);
    } else {
        $("button[type='submit']").prop("disabled", true);
    }
}

$("#email").on("input", function () {
    let value = $(this).val();

    if (value === "") {
        $(this).css("outline-color", "");
    } else if (!validateEmail(value)) {
        $(this).css("outline-color", "var(--danger)");
    } else {
        $(this).css("outline-color", "var(--success)");
    }
    updateLoginButton();
});

$("#password").on("input", function () {
    let value = $(this).val();

    if (value === "") {
        $(this).css("outline-color", "");
    } else if (!validatePassword(value)) {
        $(this).css("outline-color", "var(--danger)");
    } else {
        $(this).css("outline-color", "var(--success)");
    }
    updateLoginButton();
});

updateLoginButton();
