$(document).ready(function () {

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
    function validateUser() {

        let token = '';

        if (document.cookie.split('jwt=')[1] !== undefined) {
            token = document.cookie.split('jwt=')[1].split(';')[0];
        }

        try {
            fetch("http://localhost/nextgen-parts/api/auth.php?t=v", {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": "Bearer " + token
                },
            }).then((response) => {
                return response.json();
            }).then((data) => {

                if (data.status === "success") {
                    jwtUserData = data.data;
                    console.log(jwtUserData);
                } else {
                    // If the token is invalid, delete the cookie and redirect to login page
                    document.cookie = "jwt=;expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                    console.log("Invalid JWT token");
                }

            });
        } catch (error) {
            console.log("No JWT token found");
        }
    }

    validateUser();

});