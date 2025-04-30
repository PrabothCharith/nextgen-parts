<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Next Gen Parts - Register</title>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
<div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Register</h2>
    <div class="space-y-4">
        <div>
            <label for="email" class="block mb-1 text-gray-600">Email</label>
            <input
                    type="text"
                    id="email"
                    placeholder="Enter your email"
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
            >
        </div>
        <div>
            <label for="password" class="block mb-1 text-gray-600">Password</label>
            <input
                    type="password"
                    id="password"
                    placeholder="Enter your password"
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
            >
        </div>
        <button
                id="register"
                class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 transition duration-200"
        >
            Register
        </button>
        <div class="text-center">
            <a
                    href="http://localhost/nextgen-parts/login.php"
                    class="text-blue-500 hover:underline text-sm"
            >
                Already have an account? Login
            </a>
        </div>
    </div>
</div>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="./src/js/app.js"></script>
<script>
    $(document).ready(function () {
        $("#register").click(function () {
            let email = $("#email").val();
            let password = $("#password").val();
            fetch("http://localhost/nextgen-parts/api/auth.php?t=r", {
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
                console.log(data);
            });
        });
    });
</script>
</body>

</html>