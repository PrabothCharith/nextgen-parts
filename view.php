<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product View</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./src/css/output.css">
</head>
<body>
    
<div>
    <h1 class="text-3xl font-bold text-center mt-10">Product View</h1>
    
    <!-- Product Images-->
    <div class="flex flex-wrap justify-center mt-5" id="product_images">
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
<script>
    $(document).ready(function() {
        
    async function fetchProduct() {
        const productImages =  $('#product_images');
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
                    const imgElement = `<img src="${image}" alt="${product.name}" class="w-1/4 h-auto m-2 rounded-lg">`;
                    productImages.append(imgElement);
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