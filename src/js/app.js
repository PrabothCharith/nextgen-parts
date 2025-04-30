$(document).ready(function () {

    let jwtUserData = null;
    let protectedRoutes = ['profile.php'];
    let currentPage = window.location.pathname.split('/').pop();
    let isProtectedRoute = protectedRoutes.includes(currentPage);
    let isLoggedIn = false;

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

    // Call the validateUser function to check the user's authentication status
    validateUser();

});