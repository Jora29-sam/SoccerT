document.addEventListener("DOMContentLoaded", () => {
    const loginModal = document.getElementById("loginModal");
    const loginForm = loginModal.querySelector("form[method='post']"); // login
    const registerForm = document.getElementById("registerForm");
    const loginErrorModal = document.getElementById("loginErrorModal");

    document.getElementById("openLogin").onclick = () => {
        loginModal.style.display = "block";
        loginForm.style.display = "block";
        registerForm.style.display = "none";
    };

    document.getElementById("closeLogin").onclick = () => {
        loginModal.style.display = "none";
    };

    document.getElementById("openRegisterFromLogin").onclick = () => {
        loginForm.style.display = "none";
        registerForm.style.display = "block";
    };

    document.getElementById("openLoginFromRegister").onclick = () => {
        registerForm.style.display = "none";
        loginForm.style.display = "block";
    };

    const openLoginFromError = document.getElementById("openLoginFromError");
    if (openLoginFromError) {
        openLoginFromError.onclick = () => {
            if (loginErrorModal) loginErrorModal.style.display = "none";
            loginModal.style.display = "block";
            loginForm.style.display = "block";
            registerForm.style.display = "none";
        };
    }

    window.onclick = (event) => {
        if (event.target === loginModal) loginModal.style.display = "none";
        if (event.target === loginErrorModal) loginErrorModal.style.display = "none";
    };
});
