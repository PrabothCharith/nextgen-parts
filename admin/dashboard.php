<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

<div class="w-full h-screen">

    <div class="w-full flex flex-col">

        <h1 class="text-3xl font-bold text-center mt-10">Product Insert</h1>

        <!-- Product Name -->
        <div class="max-w-4xl mx-auto mt-10 bg-white shadow-lg rounded-lg p-5 w-full flex flex-col gap-y-4">
            <div class="w-full flex flex-col justify-start>
                    <label for=" pName
            " class="font-semibold">Product Name</label>
            <input type="text" name="name" id="pName" placeholder="Product Name"
                   class="w-full h-10 border-2 border-gray-300 rounded-md p-2 mx-auto block">
        </div>

        <!-- Product Description -->

        <div class="w-full flex flex-col justify-start">
            <label for="pDescription" class="font-semibold">Product Description</label>
            <textarea name="description" id="pDescription" placeholder="Product Description"
                      class="w-full h-20 border-2 border-gray-300 rounded-md p-2 mx-auto block"></textarea>
        </div>

        <!-- Product Price -->

        <div class="w-full flex flex-col justify-start">
            <label for="pPrice" class="font-semibold">Product Price</label>
            <input type="text" name="price" id="pPrice" placeholder="Product Price"
                   class="w-full h-10 border-2 border-gray-300 rounded-md p-2 mx-auto block">
        </div>

        <!-- Product Image -->
        <div class="w-full flex flex-col justify-start">
            <label for="pImage" class="font-semibold">Product Image</label>
            <input type="file" name="image" id="pImage" placeholder="Product Image" multiple
                   class="w-full h-10 border-2 border-gray-300 rounded-md p-2 mx-auto block">
        </div>
    </div>

    <button class="h-10 bg-blue-500 text-white rounded-md mt-5 hover:bg-blue-600 transition duration-300 w-fit px-10 mx-auto" id="pSubmitBtn">
        Submit
    </button>

</div>

</div>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {

        let productDetails = {
            name: '',
            description: '',
            price: '',
            images: []
        }

        // Method 1
        // $('#pName').change(function() {
        //     productDetails.name = $(this).val();
        // });
        // $('#pDescription').change(function() {
        //     productDetails.description = $(this).val();
        // });
        // $('#pPrice').change(function() {
        //     productDetails.price = $(this).val();
        // });

        $('#pSubmitBtn').click(async () => {

            productDetails = {
                name: '',
                description: '',
                price: '',
                images: []
            }

            // Method 2
            productDetails.name = $('#pName').val();
            productDetails.description = $('#pDescription').val();
            productDetails.price = $('#pPrice').val();
            let imageData = $('#pImage')[0].files;

            // Convert image data to base64
            let convertedImages = await imageConvertor(imageData);
            productDetails.images = convertedImages;
            console.log(productDetails);

            const result = await fetch('http://localhost/nextgen-parts/admin/api/product_manage.php?t=i', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(productDetails)
            })

            const date = await result.json();

            if (date.status === 'success') {
                swal("Success!", "Product Inserted Successfully!", "success");
            } else {
                swal("Error!", "Product Insertion Failed!", "error");
            }

        })

        async function imageConvertor(imageData) {
            let convertedImages = [];
            for (let i = 0; i < imageData.length; i++) {
                const file = imageData[i];
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = function () {
                    convertedImages.push(reader.result);
                }
            }

            // Wait for all images to be converted
            await new Promise((resolve) => {
                const interval = setInterval(() => {
                    if (convertedImages.length === imageData.length) {
                        clearInterval(interval);
                        resolve();
                    }
                }, 100);
            });
            return convertedImages;
        }

    });
</script>
</body>

</html>