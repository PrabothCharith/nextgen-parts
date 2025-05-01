<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div class="w-full h-screen flex gap-2 p-1 relative">

        <!--Sidebar Nav-->
        <div class="w-1/6 p-4 rounded-lg border-r relative">
            <div>
                <h2 class="text-xl font-bold mb-4">Admin Dashboard</h2>

                <ul class="space-y-2 w-full">

                    <li class="w-full">
                        <button
                            class="w-full flex items-center p-2 px-4 rounded-lg hover:bg-gray-800 hover:text-white transition duration-300 bg-gray-100"
                            data-page="home">
                            <i class="bi bi-house-door"></i>
                            <span class="ml-2">Dashboard</span>
                        </button>
                    </li>

                    <li class="w-full">
                        <button
                            class="w-full flex items-center p-2 px-4 rounded-lg hover:bg-gray-800 hover:text-white transition duration-300"
                            data-page="users">
                            <i class="bi bi-person"></i>
                            <span class="ml-2">Users</span>
                        </button>
                    </li>


                    <li class="w-full">
                        <button
                            class="w-full flex items-center p-2 px-4 rounded-lg hover:bg-gray-800 hover:text-white transition duration-300"
                            data-page="products">
                            <i class="bi bi-box"></i>
                            <span class="ml-2">Products</span>
                        </button>
                    </li>


                    <li class="w-full">
                        <button
                            class="w-full flex items-center p-2 px-4 rounded-lg hover:bg-gray-800 hover:text-white transition duration-300"
                            data-page="orders">
                            <i class="bi bi-file-earmark-text"></i>
                            <span class="ml-2">Orders</span>
                        </button>
                    </li>

                    <li class="w-full">
                        <button
                            class="w-full flex items-center p-2 px-4 rounded-lg hover:bg-gray-800 hover:text-white transition duration-300"
                            data-page="contact">
                            <i class="bi bi-graph-up-arrow"></i>
                            <span class="ml-2">Contact Forms</span>
                        </button>
                    </li>
                </ul>
            </div>
            <div class="absolute bottom-0 left-0 w-full p-4">
                <h2 class="text-lg font-bold mb-4">Account</h2>
                <ul class="space-y-2 w-full">
                    <li class="w-full flex gap-2 items-center justify-center">
                        <div class="w-full">
                            <button
                                class="w-full flex items-center p-2 px-4 rounded-lg hover:bg-gray-800 hover:text-white transition duration-300">
                                <i class="bi bi-person-circle"></i>
                                <span class="ml-2">Profile</span>
                            </button>
                        </div>
                        <div class="w-fit">
                            <button
                                class="w-full bg-red-100 text-red-600 rounded-lg p-3 hover:bg-red-200 transition duration-300">
                                <i class="bi bi-box-arrow-right"></i>
                            </button>
                        </div>
                    </li>

                    <li class="w-full">
                        <button
                            class="w-full flex items-center p-2 px-4 rounded-lg hover:bg-gray-800 hover:text-white transition duration-300">
                            <i class="bi bi-question-circle"></i>
                            <span class="ml-2">Help</span>
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <!--Main Content-->
        <div class="w-full overflow-y-auto">

            <!--Dashboard Main Content-->
            <div class="w-full h-full bg-red-100 hidden" id="home">
                Dashboard Content
            </div>

            <!--Users-->
            <div class="w-full h-full bg-red-200 hidden" id="users">
                Users Content
            </div>

            <!--Products-->
            <div class="w-full h-full hidden" id="products">
                <div class="w-full h-fit flex items-center justify-center p-10 gap-10 sticky bg-white top-0">
                    <button
                        class="w-full flex items-center justify-center p-2 px-4 rounded-lg hover:bg-gray-800 hover:text-white transition duration-300"
                        id="productsAddBtn">
                        <i class="bi bi-plus-circle"></i>
                        <span class="ml-2">Add Products</span>
                    </button>
                    <button
                        class="w-full flex items-center justify-center p-2 px-4 rounded-lg hover:bg-gray-800 hover:text-white transition duration-300"
                        id="productsUpdateBtn">
                        <i class="bi bi-pencil-square"></i>
                        <span class="ml-2">Update Products</span>
                    </button>
                </div>
                <div id="productsAddContent" class="flex items-center justify-center">
                    <?php require_once 'components/products/add_products.php'; ?>
                </div>
                <div id="productsUpdateContent" class="flex items-center justify-center">
                    <?php require_once 'components/products/manage_products.php'; ?>
                </div>
            </div>

            <!--Orders-->
            <div class="w-full h-full bg-red-400 hidden" id="orders">
                Orders Content
            </div>

            <!--Contact Forms-->
            <div class="w-full h-full bg-red-500 hidden" id="contact">
                Contact Forms Content
            </div>

        </div>

    </div>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
    $(document).ready(function() {

        // Defaults
        // Show the home page by default
        function defaultPage() {
            $('#products').show();
            $('#productsAddBtn').addClass('bg-gray-100');
            $('#productsUpdateBtn').removeClass('bg-gray-100');
            $('#productsUpdateContent').hide();
            $('#productsAddContent').show();
        }
        defaultPage();

        // Handle sidebar button clicks
        $('button[data-page]').on('click', function() {
            const page = $(this).data('page');

            // Remove active class and disable from all buttons
            $('button[data-page]').removeClass('bg-gray-100');

            // Add active class to the clicked button
            $(this).addClass('bg-gray-100');

            // Hide all pages
            $('.w-full.h-full').hide();

            // Show the selected page
            $('#' + page).show();
        });

        // Handle Add Products button click
        $('#productsAddBtn').on('click', function() {
            $(this).addClass('bg-gray-100');
            $('#productsUpdateBtn').removeClass('bg-gray-100');
            $('#productsUpdateContent').hide();
            $('#productsAddContent').show();
        });
        // Handle Update Products button click
        $('#productsUpdateBtn').on('click', function() {
            $(this).addClass('bg-gray-100');
            $('#productsAddBtn').removeClass('bg-gray-100');
            $('#productsAddContent').hide();
            $('#productsUpdateContent').show();
        });

    });
    </script>
</body>

</html>