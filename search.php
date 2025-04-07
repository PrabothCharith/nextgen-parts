<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Your Product</title>
    <link rel="stylesheet" href="./src/css/output.css">
</head>

<body>

    <div class="w-full flex gap-2 relative">

        <!-- Filter Sidebar -->
        <div class="w-full min-w-72 max-w-96 h-screen sticky top-0 left-0 hidden lg:block p-8 border-r-2">
            <div class="w-full h-full flex flex-col justify-center items-center">

                <!-- Filter Title -->
                <div class="w-full flex justify-between items-center">
                    <p class="text-lg font-semibold">Filter</p>
                    <button class="text-gray-500 hover:text-red-500">Clear All</button>
                </div>

                <!-- Sort By -->
                <div class="w-full p-2 py-7 border-b">
                    <p class="text-lg font-semibold">Sort By</p>
                    <select class="w-full p-2 border border-gray-300 rounded-lg mt-2">
                        <option value="relevance">Relevance</option>
                        <option value="price-low-to-high">Price: Low to High</option>
                        <option value="price-high-to-low">Price: High to Low</option>
                        <option value="newest">Newest Arrivals</option>
                    </select>
                </div>

                <!-- Price Range -->
                <div class="w-full p-2 py-71 border-b">
                    <p class="text-lg font-semibold">Price Range</p>
                    <div class="w-full flex flex-col gap-2 mt-2">
                        <input type="range" min="0" max="1000" value="500" class="w-full">
                        <p>Rs. 0 - Rs. 1000</p>
                    </div>
                </div>

                <!-- Availability -->
                <div class="w-full p-2 py-7 border-b">
                    <p class="text-lg font-semibold">Availability</p>
                    <div class="w-full flex gap-2 mt-2">
                        <input type="checkbox" id="in-stock" name="availability" value="in-stock">
                        <label for="in-stock">In Stock</label>
                    </div>
                </div>

                <!-- Category -->
                <div class="w-full p-2 py-7 border-b">
                    <p class="text-lg font-semibold">Category</p>
                    <select class="w-full p-2 border border-gray-300 rounded-lg mt-2">
                        <option value="all">All Categories</option>
                        <option value="electronics">Electronics</option>
                        <option value="clothing">Clothing</option>
                        <option value="home-appliances">Home Appliances</option>
                    </select>
                </div>


                <!-- Brand -->
                <div class="w-full p-2 py-7 border-b">
                    <p class="text-lg font-semibold">Brand</p>
                    <select class="w-full p-2 border border-gray-300 rounded-lg mt-2">
                        <option value="all">All Brands</option>
                        <option value="brand-a">Brand A</option>
                        <option value="brand-b">Brand B</option>
                        <option value="brand-c">Brand C</option>
                    </select>
                </div>


            </div>
        </div>

        <div class="w-full py-5">
            <div class="w-full 2xl:container mx-auto flex flex-col justify-center items-center gap-y-5">
                <!-- Search Bar -->
                <div class="w-full flex gap-2">
                    <input type="text" placeholder="Search for products"
                        class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button class="w-fit bg-blue-500 text-white p-2 px-8 rounded-lg">Search</button>
                </div>

                <!-- Body -->
                <div class="w-full grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 2xl:grid-cols-4 gap-3">

                    <?php

                    for ($i = 0; $i < 30; $i++) {
                    ?>

                        <!-- Product Card -->
                        <div class="w-full shadow-sm rounded-lg p-2 flex flex-col border">

                            <!-- Product Image -->
                            <img src="https://images.pexels.com/photos/31352325/pexels-photo-31352325/free-photo-of-warm-indoor-scene-with-potted-plants-and-shadows.jpeg"
                                alt="Product Image" class="w-full aspect-video object-cover rounded-lg">

                            <!-- Product Details -->
                            <div class="grid grid-cols-2 mt-2">
                                <p class="text-lg col-span-full">Product Name here</p>

                                <p>Rs. 100</p>
                                <button>Cart</button>

                            </div>

                        </div>

                    <?php
                    }

                    ?>

                </div>

            </div>
        </div>
    </div>

</body>

</html>