<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Your Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                    <input type="text" placeholder="Search for products" id="searchInput"
                        class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button class="w-fit bg-blue-500 text-white p-2 px-8 rounded-lg" id="advancedSearchTrigger">Search</button>
                </div>

                <!-- Body -->
                <div class="w-full grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 2xl:grid-cols-4 gap-3" id="productContainer">
                    <!-- Product Cards will be dynamically inserted here -->
                </div>

                <!-- Pagination -->
                 <div class="w-full flex justify-center items-center mt-5">
                    <button class="bg-blue-500 text-white p-2 px-4 rounded-lg">Previous</button>
                    <div class="w-fit flex justify-center items-center mt-5" id="pagination">
                        <!-- Pagination will be dynamically inserted here -->
                    </div>
                    <button class="bg-blue-500 text-white p-2 px-4 rounded-lg">Next</button>
                 </div>

            </div>
        </div>
    </div>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            
            let allProducts = [];
            let filteredProducts = [];
            let itemsPerPage = 2;

            async function setProducts(products) {
                const productContainer = $('#productContainer');
                        productContainer.empty(); // Clear existing products

                        if (products.length === 0) {
                            productContainer.append('<p class="text-center text-gray-500">No products found.</p>');
                            return;
                        }

                        products.forEach(product => {
                            const productCard = `
                                <div class="w-full shadow-sm rounded-lg p-2 flex flex-col border cursor-pointer hover:shadow-lg transition duration-300 ease-in-out" onclick="window.location.href='view.php?id=${product.id}'">
                                    <img src="${product.images[0]}" alt="${product.name}" class="w-full aspect-video object-cover rounded-lg">
                                    <div class="grid grid-cols-2 mt-2">
                                        <p class="text-lg col-span-full">${product.name}</p>
                                        <p>Rs. ${product.price}</p>
                                        <button class="bg-blue-500 text-white p-2 rounded-lg">Add to Cart</button>
                                    </div>
                                </div>
                            `;
                            productContainer.append(productCard);
                        });
                
            }

            async function fetchProducts() {
                try {
                    const response = await fetch('http://localhost/nextgen-parts/admin/api/product_manage.php?t=f');
                    const data = await response.json();
                    
                    if (data.status === 'success' && data.data && data.data.length > 0) {
                        const products = data.data;
                        allProducts = products; 
                        filterProducts('');
                    } else {
                        productContainer.empty(); // Clear existing products
                        console.error('Error fetching products:', data.message);
                    }

                } catch (error) {
                    console.error('Error fetching products:', error);
                }
            }

            fetchProducts();
            
            async function handlePaginations(currentPage) {

                let totalPages = filteredProducts.length;
                if (totalPages === 0) {
                    $('#pagination').empty(); // Clear pagination if no products
                    return;
                }

                if (!currentPage) {
                    currentPage = 1; // Default to the first page
                }

                const pagination = $('#pagination');
                pagination.empty(); // Clear existing pagination
            
                let paginationCount = 0;
                paginationCount = Math.ceil(totalPages / itemsPerPage);

                for (let i = 1; i <= paginationCount; i++) {
                    const pageButton = $(`<button class="bg-blue-500 text-white p-2 px-4 rounded-lg">${i}</button>`);
                    pageButton.on('click', function() {
                        handlePaginations(i); 
                    });
                    pagination.append(pageButton);
                }

                setProducts(filteredProducts.slice((currentPage - 1) * itemsPerPage, currentPage * itemsPerPage));

            }

            $('#searchInput').on('input', function() {
                filterProducts($(this).val());
                if ($(this).val() === '') {
                    allProducts = [];
                    fetchProducts();
                }
            });

            async function filterProducts (query){
               const filter = allProducts.filter(product => {
                    return product.name.toLowerCase().includes(query.toLowerCase());
                });

                filteredProducts = filter;
                handlePaginations();
            }

            $('#advancedSearchTrigger').click(async () => {
                advancedSearch();
            });

            async function advancedSearch (){
                let query = $('#searchInput').val();

                const response = await fetch(`http://localhost/nextgen-parts/admin/api/product_manage.php?t=f&q=${query}`);
                const data = await response.json();

                if (data.status === 'success' && data.data && data.data.length > 0) {
                    const products = data.data;
                    allProducts = products; 
                    filterProducts('');
                } else {
                    productContainer.empty(); // Clear existing products
                    console.error('Error fetching products:', data.message);
                }
                
            }

        });
    </script>
</body>

</html>