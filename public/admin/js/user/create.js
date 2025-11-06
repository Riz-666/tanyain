// Password strength checker
function checkPasswordStrength(password) {
    let strength = 0;
    let feedback = [];

    // Length check
    if (password.length >= 8) {
        strength += 1;
    } else {
        feedback.push("Minimal 8 karakter");
    }

    // Uppercase check
    if (/[A-Z]/.test(password)) {
        strength += 1;
    } else {
        feedback.push("Tambahkan huruf besar");
    }

    // Lowercase check
    if (/[a-z]/.test(password)) {
        strength += 1;
    } else {
        feedback.push("Tambahkan huruf kecil");
    }

    // Number check
    if (/[0-9]/.test(password)) {
        strength += 1;
    } else {
        feedback.push("Tambahkan angka");
    }

    // Special character check
    if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) {
        strength += 1;
    } else {
        feedback.push("Tambahkan karakter khusus");
    }

    return { strength, feedback };
}

function updatePasswordStrength() {
    const password = document.getElementById("password").value;
    const strengthFill = document.getElementById("strength-fill");
    const strengthText = document.getElementById("strength-text");

    if (!password) {
        strengthFill.style.width = "0%";
        strengthFill.className = "strength-fill";
        strengthText.textContent = "Masukkan password";
        strengthText.className = "strength-text";
        return;
    }

    const result = checkPasswordStrength(password);
    const strength = result.strength;
    const feedback = result.feedback;

    // Update bar width and color
    const widthPercent = (strength / 5) * 100;
    strengthFill.style.width = widthPercent + "%";

    // Remove previous strength classes
    strengthFill.className = "strength-fill";
    strengthText.className = "strength-text";

    // Set strength level
    let strengthLevel = "";
    let strengthClass = "";
    let textClass = "";

    switch (strength) {
        case 0:
        case 1:
            strengthLevel = "Sangat Lemah";
            strengthClass = "strength-very-weak";
            textClass = "text-very-weak";
            break;
        case 2:
            strengthLevel = "Lemah";
            strengthClass = "strength-weak";
            textClass = "text-weak";
            break;
        case 3:
            strengthLevel = "Cukup";
            strengthClass = "strength-fair";
            textClass = "text-fair";
            break;
        case 4:
            strengthLevel = "Bagus";
            strengthClass = "strength-good";
            textClass = "text-good";
            break;
        case 5:
            strengthLevel = "Sangat Kuat";
            strengthClass = "strength-strong";
            textClass = "text-strong";
            break;
    }

    strengthFill.classList.add(strengthClass);
    strengthText.classList.add(textClass);
    strengthText.textContent =
        strengthLevel + (feedback.length ? " - " + feedback.join(", ") : "");
}

// Toggle password visibility
function togglePassword(fieldId) {
    const passwordField = document.getElementById(fieldId);
    const eyeIcon = document.getElementById(fieldId + "-eye");

    if (passwordField.type === "password") {
        passwordField.type = "text";
        eyeIcon.classList.remove("fa-eye");
        eyeIcon.classList.add("fa-eye-slash");
    } else {
        passwordField.type = "password";
        eyeIcon.classList.remove("fa-eye-slash");
        eyeIcon.classList.add("fa-eye");
    }
}

// Event listeners
document
    .getElementById("password")
    .addEventListener("input", updatePasswordStrength);
document
    .getElementById("password")
    .addEventListener("keyup", updatePasswordStrength);
document
    .getElementById("password")
    .addEventListener("focus", updatePasswordStrength);

document.addEventListener("DOMContentLoaded", function () {
    const namaInput = document.getElementById("nama");
    const usernameInput = document.getElementById("username");

    namaInput.addEventListener("input", function () {
        const nama = namaInput.value.trim();
        if (nama.length === 0) {
            usernameInput.value = "";
            return;
        }

        // Ambil kata pertama dari nama
        let firstWord = nama
            .split(" ")[0]
            .toLowerCase()
            .replace(/[^a-z0-9]/g, "");
        // Tambah angka random 4 digit
        let randomNum = Math.floor(1000 + Math.random() * 9000);
        usernameInput.value = firstWord + randomNum;
    });
});


//LIVECHECK
const passwordInput = document.getElementById("password");
const confirmInput = document.getElementById("confirm_password");
const submitBtn = document.getElementById("submitBtn");
const passwordAlert = document.getElementById("password-alert");

function checkPasswords() {
    if (confirmInput.value === "") {
        passwordAlert.style.display = "none";
        submitBtn.disabled = false;
        return;
    }

    if (passwordInput.value !== confirmInput.value) {
        passwordAlert.style.display = "block";
        submitBtn.disabled = true; // disable submit
    } else {
        passwordAlert.style.display = "none";
        submitBtn.disabled = false; // enable submit
    }
}

passwordInput.addEventListener("input", checkPasswords);
confirmInput.addEventListener("input", checkPasswords);
