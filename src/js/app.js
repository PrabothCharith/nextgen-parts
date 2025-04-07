$(document).ready(function () {

    let jwtUserData = null;
    let protectedRoutes = ['profile.php', 'index.php', ''];
    let currentPage = window.location.pathname.split('/').pop();
    let isProtectedRoute = protectedRoutes.includes(currentPage);
    let isLoggedIn = false;

    $("#register").click(function () {

        let email = $("#email").val();
        let password = $("#password").val();

        // t = type and r = register
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

    $("#login").click(function () {

        let email = $("#email").val();
        let password = $("#password").val();

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


    // $("#test").click(function () {
    //     let time = new Date().getTime();
    //     let exp_time = 36000 * 48 * 100;
    //     let data = {
    //         "created": time,
    //         "exp": time + exp_time,
    //         "gap": exp_time
    //     }
    //     console.table(data);

    // });

    // validate user with jwt token
    async function validateUser() {

        let token = '';

        if (document.cookie.split('jwt=')[1] !== undefined) {
            token = document.cookie.split('jwt=')[1].split(';')[0];
        }

        try {
            await fetch("http://localhost/nextgen-parts/api/auth.php?t=v", {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": "Bearer " + token
                },
            }).then((response) => {
                return response.json();
            }).then((data) => {

                if (data.status === "success") {
                    isLoggedIn = true;
                    jwtUserData = data.data;
                } else {
                    isLoggedIn = false;
                }

            });
        } catch (error) {
            console.log("No JWT token found");
        }

        // If the user is not logged in and the current page is a protected route, redirect to the login page
        if (!isLoggedIn && isProtectedRoute) {

            // Display the response message using SweetAlert
            swal({
                title: "Unauthorized",
                text: "You are not authorized to access this page.",
                icon: "warning"
            });

            setTimeout(() => {
                window.location.href = "http://localhost/nextgen-parts/login.php";
            }, 2000);
        }
    }

    validateUser();

});