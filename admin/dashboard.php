<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
<div class="bg-white p-10 rounded-lg shadow-lg w-full max-w-3xl">
    <h1 class="text-3xl font-bold text-center text-gray-800 mb-10">Add New Product</h1>
    <div class="space-y-6">
        <div>
            <label for="pName" class="block text-gray-700 font-semibold mb-2">Product Name</label>
            <input
                    type="text"
                    id="pName"
                    name="name"
                    placeholder="Enter product name"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none"
            >
        </div>

        <div>
            <label for="pDescription" class="block text-gray-700 font-semibold mb-2">Product Description</label>
            <textarea
                    id="pDescription"
                    name="description"
                    placeholder="Enter product description"
                    rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none"
            ></textarea>
        </div>

        <div>
            <label for="pPrice" class="block text-gray-700 font-semibold mb-2">Product Price</label>
            <input
                    type="text"
                    id="pPrice"
                    name="price"
                    placeholder="Enter product price"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none"
            >
        </div>

        <div>
            <label for="pImage" class="block text-gray-700 font-semibold mb-2">Product Images</label>
            <input
                    type="file"
                    id="pImage"
                    name="image"
                    multiple
                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none"
            >
        </div>

        <div class="flex justify-center">
            <button
                    id="pSubmitBtn"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-8 rounded-md transition duration-300"
            >
                Submit Product
            </button>
        </div>
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

        $('#pSubmitBtn').click(async () => {
            productDetails = {
                name: $('#pName').val(),
                description: $('#pDescription').val(),
                price: $('#pPrice').val(),
                images: []
            }

            let imageData = $('#pImage')[0].files;
            let convertedImages = await imageConvertor(imageData);
            productDetails.images = convertedImages;

            const result = await fetch('http://localhost/nextgen-parts/admin/api/product_manage.php?t=i', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(productDetails)
            });

            const data = await result.json();

            if (data.status === 'success') {
                swal("Success!", "Product Inserted Successfully!", "success");
            } else {
                swal("Error!", "Product Insertion Failed!", "error");
            }
        });

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