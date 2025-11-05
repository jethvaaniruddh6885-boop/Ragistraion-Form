<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Space-Themed Registration</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>
<style>
.iti { width: 100%; }

/* Fade-in animation */
@keyframes fadeIn {
    from {opacity: 0; transform: translateY(-20px);}
    to {opacity: 1; transform: translateY(0);}
}
.animate-fadeIn { animation: fadeIn 0.6s ease-in-out; }

/* Dark/space theme */
body {
    background: linear-gradient(135deg, #0a0a0a, #1e1e2f, #0a0a0a);
    font-family: 'Inter', sans-serif;
}

/* Input focus glow */
input:focus {
    box-shadow: 0 0 0 3px rgba(139,92,246,0.6);
    border-color: #8B5CF6;
}

/* Button hover effect */
button:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 20px rgba(139,92,246,0.5);
}
</style>
</head>
<body class="min-h-screen flex items-center justify-center">

<div class="w-full max-w-md bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 bg-opacity-90 rounded-2xl shadow-2xl p-8 animate-fadeIn backdrop-blur-md">
    <h2 class="text-2xl md:text-3xl font-bold text-center text-purple-400 mb-6">Create Your Account</h2>

    <div id="alert" class="hidden mb-4 p-3 rounded text-white font-semibold"></div>

    <form id="registerForm" class="space-y-4">
        <div>
            <label class="block font-semibold text-gray-300">Full Name</label>
            <input type="text" id="fullName" name="fullName" class="mt-1 w-full rounded-lg border border-gray-600 bg-gray-900 text-white px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:outline-none"/>
            <p id="fullNameError" class="text-red-500 text-sm mt-1"></p>
        </div>

        <div>
            <label class="block font-semibold text-gray-300">Username</label>
            <input type="text" id="username" name="username" class="mt-1 w-full rounded-lg border border-gray-600 bg-gray-900 text-white px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:outline-none"/>
            <p id="usernameError" class="text-red-500 text-sm mt-1"></p>
        </div>

        <div>
            <label class="block font-semibold text-gray-300">Email</label>
            <input type="email" id="email" name="email" class="mt-1 w-full rounded-lg border border-gray-600 bg-gray-900 text-white px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:outline-none"/>
            <p id="emailError" class="text-red-500 text-sm mt-1"></p>
        </div>

        <div>
            <label class="block font-semibold text-gray-300">Phone Number</label>
            <input type="tel" id="phone" name="phone" class="mt-1 w-full rounded-lg border border-gray-600 bg-gray-900 text-white px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:outline-none"/>
            <p id="phoneError" class="text-red-500 text-sm mt-1"></p>
        </div>

        <div>
            <label class="block font-semibold text-gray-300">Password</label>
            <input type="password" id="password" name="password" class="mt-1 w-full rounded-lg border border-gray-600 bg-gray-900 text-white px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:outline-none"/>
            <p id="passwordError" class="text-red-500 text-sm mt-1"></p>
        </div>

        <div>
            <label class="block font-semibold text-gray-300">Confirm Password</label>
            <input type="password" id="confirmPassword" name="confirmPassword" class="mt-1 w-full rounded-lg border border-gray-600 bg-gray-900 text-white px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:outline-none"/>
            <p id="confirmPasswordError" class="text-red-500 text-sm mt-1"></p>
        </div>

        <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-blue-500 text-white py-2 rounded-lg font-semibold shadow-md transition-transform">Register</button>
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
<script src="script.js"></script>
<script>
const phoneInput = document.querySelector("#phone");
window.intlTelInput(phoneInput, {
    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
    initialCountry: "us",
    preferredCountries: ["us", "gb", "in"],
    separateDialCode: true
});
</script>
</body>
</html>
