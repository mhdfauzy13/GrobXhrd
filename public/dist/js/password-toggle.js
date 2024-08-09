$(document).ready(function () {
    // Mengatur visibilitas password untuk field password
    $("#togglePassword").on("click", function () {
        const passwordField = $("#password");
        const type =
            passwordField.attr("type") === "password" ? "text" : "password";
        passwordField.attr("type", type);
        $(this).find("i").toggleClass("fa-eye-slash");
    });

    // Mengatur visibilitas password untuk field konfirmasi password
    $("#togglePasswordConfirmation").on("click", function () {
        const passwordField = $("#password_confirmation");
        const type =
            passwordField.attr("type") === "password" ? "text" : "password";
        passwordField.attr("type", type);
        $(this).find("i").toggleClass("fa-eye-slash");
    });
});
