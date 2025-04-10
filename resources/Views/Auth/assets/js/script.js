document.addEventListener("DOMContentLoaded", () => {
    const forms = document.querySelector(".forms");
    const pwShowHide = document.querySelectorAll(".eye-icon");
    const loginLink = document.querySelector(".login-link");
    const signupLink = document.querySelector(".signup-link");
    const forgotLink = document.querySelector(".forgot-link");
    const backLoginLink = document.querySelector(".back-login-link");

    // Show correct form on load
    const activeForm = document.body.dataset.activeForm;
    if (activeForm === "register") {
        forms.classList.add("show-signup");
    } else if (activeForm === "forgotpass") {
        forms.classList.add("show-forgot");
    }

    // Toggle password visibility
    pwShowHide.forEach(icon => {
        icon.addEventListener("click", () => {
            const passwordFields = icon.closest(".input-field").querySelectorAll(".password");
            passwordFields.forEach(field => {
                const isHidden = field.type === "password";
                field.type = isHidden ? "text" : "password";
                icon.classList.replace(isHidden ? "bx-hide" : "bx-show", isHidden ? "bx-show" : "bx-hide");
            });
        });
    });

    // Switch between forms
    if (signupLink) {
        signupLink.addEventListener("click", e => {
            e.preventDefault();
            forms.classList.add("show-signup");
            forms.classList.remove("show-forgot");
        });
    }

    if (loginLink) {
        loginLink.addEventListener("click", e => {
            e.preventDefault();
            forms.classList.remove("show-signup", "show-forgot");
        });
    }

    if (forgotLink) {
        forgotLink.addEventListener("click", e => {
            e.preventDefault();
            forms.classList.add("show-forgot");
            forms.classList.remove("show-signup");
        });
    }

    if (backLoginLink) {
        backLoginLink.addEventListener("click", e => {
            e.preventDefault();
            forms.classList.remove("show-forgot", "show-signup");
        });
    }
});