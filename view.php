<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product View</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" href="./src/css/output.css">
</head>
<body>

<div class="bg-white bg-opacity-90 p-5 rounded-lg shadow-lg max-w-2xl mx-auto mt-10">
    <a href="index.php" class="text-blue-500 hover:text-blue-700">Back to Products</a>
</div>

<div>
    <h1 class="text-3xl font-bold text-center mt-10">Product View</h1>

    <!-- Product Images-->
    <div class="mt-5 w-full px-10 max-w-7xl mx-auto" id="product_images">
        <!-- Loop through images and display them -->
    </div>

    <!-- Product Name -->
    <h2 class="text-2xl font-bold text-center mt-5" id="product_name"></h2>

    <!-- Product Price -->
    <p class="text-xl font-semibold text-center mt-2" id="product_price"></p>

    <!-- Product Description -->
    <p class="text-lg text-center mt-2" id="product_description"></p>

</div>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script>
    $(document).ready(function () {

        $('#product_images').slick({
            dots: true,
            infinite: true,
            speed: 500,
            slidesToShow: 3,
            autoplay: true,
            responsive: [
                {
                    breakpoint: 1500,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 1000,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }]
        });

        async function fetchProduct() {
            const productImages = $('#product_images');
            const productName = $('#product_name');
            const productPrice = $('#product_price');
            const productDescription = $('#product_description');

            const productId = new URLSearchParams(window.location.search).get('id');
            if (!productId) {
                swal("Error", "Product ID not found", "error");
                return;
            }

            const response = await fetch(`http://localhost/nextgen-parts/admin/api/product_manage.php?t=f&id=${productId}`);
            const data = await response.json();

            if (data.status === 'success' && data.data && data.data.length > 0) {
                const product = data.data[0];

                // Set product details
                productName.text(product.name);
                productPrice.text(`Rs. ${product.price}`);
                productDescription.text(product.description);

                // Display product images
                product.images.forEach(image => {
                    $('#product_images').slick('slickAdd',
                        '<div class="w-full bg-center aspect-video bg-cover bg-no-repeat rounded-lg" style="background-image: url(' + image + ');"></div>'
                    );
                });
            } else {
                swal("Error", "Product not found", "error");
            }
        }

        fetchProduct();

    });
</script>
</body>
</html>


<!-- Slick Carousel 
https://kenwheeler.github.io/slick/ -->