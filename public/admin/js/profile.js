// Password toggle functionality
function setupPasswordToggle(inputId, toggleId) {
    const input = document.getElementById(inputId);
    const toggle = document.getElementById(toggleId);

    toggle.addEventListener("click", function () {
        const type =
            input.getAttribute("type") === "password" ? "text" : "password";
        input.setAttribute("type", type);

        const icon = toggle.querySelector("i");
        icon.className =
            type === "password" ? "fas fa-eye" : "fas fa-eye-slash";
    });
}

setupPasswordToggle("currentPassword", "toggleCurrentPassword");
setupPasswordToggle("newPassword", "toggleNewPassword");
setupPasswordToggle("confirmPassword", "toggleConfirmPassword");

// Password strength checker
function checkPasswordStrength(password) {
    const requirements = {
        length: password.length >= 8,
        upper: /[A-Z]/.test(password),
        lower: /[a-z]/.test(password),
        number: /\d/.test(password),
        special: /[!@#$%^&*(),.?":{}|<>]/.test(password),
    };

    const score = Object.values(requirements).filter((req) => req).length;

    return {
        score: score,
        requirements: requirements,
        strength:
            score < 2
                ? "weak"
                : score < 3
                ? "fair"
                : score < 5
                ? "good"
                : "strong",
    };
}

function updatePasswordStrength(password) {
    const strengthIndicator = document.getElementById("passwordStrength");
    const strengthFill = document.getElementById("strengthFill");
    const strengthText = document.getElementById("strengthText");
    const requirementsDiv = document.getElementById("passwordRequirements");

    if (password.length === 0) {
        strengthIndicator.style.display = "none";
        requirementsDiv.style.display = "none";
        return;
    }

    strengthIndicator.style.display = "block";
    requirementsDiv.style.display = "block";

    const result = checkPasswordStrength(password);

    // Update strength bar
    strengthFill.className = `strength-fill strength-${result.strength}`;

    // Update strength text
    const strengthTexts = {
        weak: "Lemah",
        fair: "Cukup",
        good: "Bagus",
        strong: "Kuat",
    };

    strengthText.textContent = strengthTexts[result.strength];
    strengthText.className = `strength-text text-${result.strength}`;

    // Update requirements
    const reqElements = {
        lengthReq: result.requirements.length,
        upperReq: result.requirements.upper,
        lowerReq: result.requirements.lower,
        numberReq: result.requirements.number,
        specialReq: result.requirements.special,
    };

    Object.keys(reqElements).forEach((reqId) => {
        const element = document.getElementById(reqId);
        const icon = element.querySelector("i");

        if (reqElements[reqId]) {
            element.classList.add("met");
            icon.className = "fas fa-check-circle requirement-icon";
        } else {
            element.classList.remove("met");
            icon.className = "fas fa-times-circle requirement-icon";
        }
    });
}

function checkPasswordMatch() {
    const newPassword = document.getElementById("newPassword").value;
    const confirmPassword = document.getElementById("confirmPassword").value;
    const matchDiv = document.getElementById("passwordMatch");

    if (confirmPassword.length === 0) {
        matchDiv.style.display = "none";
        return;
    }

    matchDiv.style.display = "block";

    if (newPassword === confirmPassword) {
        matchDiv.innerHTML =
            '<i class="fas fa-check-circle me-1" style="color: #059669;"></i> Password cocok';
        matchDiv.className = "form-text text-success";
    } else {
        matchDiv.innerHTML =
            '<i class="fas fa-times-circle me-1" style="color: #ef4444;"></i> Password tidak cocok';
        matchDiv.className = "form-text text-danger";
    }
}

// Event listeners
document.getElementById("newPassword").addEventListener("input", function () {
    updatePasswordStrength(this.value);
    checkPasswordMatch();
});

document
    .getElementById("confirmPassword")
    .addEventListener("input", checkPasswordMatch);

// Form submission
document.getElementById("profileForm").addEventListener("submit", function (e) {
    e.preventDefault();

    // Show success message
    const successAlert = document.getElementById("successAlert");
    successAlert.style.display = "block";

    // Hide after 3 seconds
    setTimeout(() => {
        successAlert.style.display = "none";
    }, 3000);

    // Scroll to top
    window.scrollTo({ top: 0, behavior: "smooth" });
});
