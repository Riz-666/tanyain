// Password strength checker (sama seperti create)
function checkPasswordStrength(password) {
    let strength = 0;
    let feedback = [];

    if (password.length >= 8) strength += 1; else feedback.push("Minimal 8 karakter");
    if (/[A-Z]/.test(password)) strength += 1; else feedback.push("Tambahkan huruf besar");
    if (/[a-z]/.test(password)) strength += 1; else feedback.push("Tambahkan huruf kecil");
    if (/[0-9]/.test(password)) strength += 1; else feedback.push("Tambahkan angka");
    if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) strength += 1; else feedback.push("Tambahkan karakter khusus");

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

    const widthPercent = (strength / 5) * 100;
    strengthFill.style.width = widthPercent + "%";

    strengthFill.className = "strength-fill";
    strengthText.className = "strength-text";

    let strengthLevel = "";
    let strengthClass = "";
    let textClass = "";

    switch (strength) {
        case 0:
        case 1: strengthLevel = "Sangat Lemah"; strengthClass = "strength-very-weak"; textClass = "text-very-weak"; break;
        case 2: strengthLevel = "Lemah"; strengthClass = "strength-weak"; textClass = "text-weak"; break;
        case 3: strengthLevel = "Cukup"; strengthClass = "strength-fair"; textClass = "text-fair"; break;
        case 4: strengthLevel = "Bagus"; strengthClass = "strength-good"; textClass = "text-good"; break;
        case 5: strengthLevel = "Sangat Kuat"; strengthClass = "strength-strong"; textClass = "text-strong"; break;
    }

    strengthFill.classList.add(strengthClass);
    strengthText.classList.add(textClass);
    strengthText.textContent = strengthLevel + (feedback.length ? " - " + feedback.join(", ") : "");
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
document.getElementById("password").addEventListener("input", updatePasswordStrength);
document.getElementById("password").addEventListener("keyup", updatePasswordStrength);
document.getElementById("password").addEventListener("focus", updatePasswordStrength);

// Live check password & konfirmasi
const passwordInput = document.getElementById("password");
const confirmInput = document.getElementById("confirm_password");
const submitBtn = document.getElementById("submitBtn");
const passwordAlert = document.getElementById("password-alert");

function checkPasswords() {
    if (!passwordInput.value && !confirmInput.value) {
        // Password kosong, tidak ganti password → tidak perlu alert
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
