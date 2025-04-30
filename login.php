<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Next Gen Parts - Login</title>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md mt-8">
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Login</h2>
    <div class="space-y-4">
        <div>
            <label for="login-email" class="block mb-1 text-gray-600">Email</label>
            <input
                    type="text"
                    id="login-email"
                    placeholder="Email"
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-400"
            >
        </div>
        <div>
            <label for="login-password" class="block mb-1 text-gray-600">Password</label>
            <input
                    type="password"
                    id="login-password"
                    placeholder="Password"
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-400"
            >
        </div>
        <button
                id="login"
                class="w-full bg-green-500 text-white py-2 rounded-md hover:bg-green-600 transition duration-200"
        >
            Login
        </button>
        <div class="text-center">
            <a
                    href="http://localhost/nextgen-parts/register.php"
                    class="text-blue-500 hover:underline text-sm"
            >
                Don't have an account? Register
            </a>
        </div>
    </div>
</div>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="./src/js/app.js"></script>
<script>
    $(document).ready(function () {
        $("#login").click(function () {

            let email = $("#login-email").val();
            let password = $("#login-password").val();

            // t = type and l = login
            fetch("http://localhost/nextgen-parts/api/auth.php?t=l", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    email: email,
                    password: password
                })
            }).then((response) => {
                return response.json();
            }).then((data) => {

                // Display the response message using SweetAlert
                swal({
                    title: data.status,
                    text: data.message,
                    icon: data.status
                });

                // If the login is successful, Create a cookie for JWT token
                if (data.status === "success") {
                    let token = data.token;

                    // expires in 2 days
                    let expires = new Date();
                    expires.setTime(expires.getTime() + (3600 * 48 * 100));
                    document.cookie = "jwt=" + token + ";expires=" + expires.toUTCString() + ";path=/";

                    // Redirect to the dashboard
                    window.location.href = "http://localhost/nextgen-parts/profile.php";

                }

            });

        });
    })
</script>

</body>

</html>