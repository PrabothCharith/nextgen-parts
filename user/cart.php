<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NextGen Parts - Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold mb-6">Shopping Cart</h1>
        <div id="cart-items" class="space-y-4">
            <!-- Cart items will be dynamically inserted here -->
        </div>

        <div id="cart-total" class="text-lg font-semibold mt-4">
            <!-- Total price will be displayed here -->
        </div>

        <button id="checkout"
            class="mt-4 bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600 transition duration-200">
            Checkout
        </button>

    </div>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
    let cartItems = [];
    const localStorage = window.localStorage;

    $(document).ready(function() {

        $('#checkout').click(async function() {
            if (cartItems.length === 0) {
                swal({
                    title: "Empty Cart",
                    text: "Your cart is empty.",
                    icon: "info",
                    button: "OK",
                });
                return;
            } else {

                let token = '';

                if (document.cookie.split('jwt=')[1] !== undefined) {
                    token = document.cookie.split('jwt=')[1].split(';')[0];
                }

                try {
                    const response = await fetch('http://localhost/nextgen-parts/api/transactions.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer ' + token
                        },
                        body: JSON.stringify(cartItems)
                    });
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    const data = await response.json();

                    if (data.status === 'success') {
                        swal({
                            title: "Success",
                            text: "Your order has been placed successfully.",
                            icon: "success",
                            button: "OK",
                        }).then(() => {
                            localStorage.removeItem('cart');
                            cartItems = [];
                            renderCart();
                        });
                    } else {
                        swal({
                            title: "Error",
                            text: data.message,
                            icon: "error",
                            button: "OK",
                        });
                    }

                } catch (error) {
                    console.error('Error:', error);
                    swal({
                        title: "Error",
                        text: "An error occurred while processing your request.",
                        icon: "error",
                        button: "OK",
                    });
                }
            }
        });

        function loadCart() {
            if (!localStorage) {
                swal({
                    title: "Error",
                    text: "Local storage is not supported in this browser.",
                    icon: "error",
                    button: "OK",
                });
                return;
            }
            const cart = localStorage.getItem('cart') || null;
            if (cart) {
                cartItems = JSON.parse(cart);
                renderCart();
            } else {
                swal({
                    title: "Empty Cart",
                    text: "Your cart is empty.",
                    icon: "info",
                    button: "OK",
                });
            }
        }

        loadCart();

    })

    function renderCart() {
        const cartContainer = document.getElementById('cart-items');
        cartContainer.innerHTML = '';
        const cartTotal = document.getElementById('cart-total');
        cartTotal.innerHTML = '';
        let totalPrice = 0;

        if (cartItems.length === 0) {
            cartContainer.innerHTML = '<p class="text-gray-500">Your cart is empty.</p>';
            return;
        }

        cartItems.forEach(item => {
            const productDiv = document.createElement('div');
            productDiv.className = 'flex items-center justify-between bg-white p-4 rounded-md border mb-4';
            productDiv.innerHTML = `
                <div class="flex items-center">
                    <img src="${item.image}" alt="Product Image" class="h-full aspect-square max-h-24 rounded-md mr-4">
                    <div>
                        <h2 class="text-lg font-semibold">${item.name}</h2>
                        <p class="text-gray-600">Rs.${item.price.toFixed(2)}</p>
                        <small class="text-gray-500">${item.description}</small>
                    </div>
                </div>
                <div class="flex items-center">
                    <input type="number" id="product-quantity" value="${item.quantity}" min="1"
                        class="w-16 border rounded-md px-2 py-1 mr-4" onchange="cartUpdate(${item.id}, this.value)"/>
                    <button class="remove-item bg-red-500 text-white py-1 px-3 rounded-md hover:bg-red-600"
                        onclick="event.stopPropagation(); removeItem(${item.id})">
                        Remove
                    </button>
                </div>
            `;
            cartContainer.appendChild(productDiv);

            totalPrice += item.price * item.quantity;
            cartTotal.innerHTML = `<h2 class="text-lg font-semibold">Total: Rs.${totalPrice.toFixed(2)}</h2>`;
        });
    }

    function removeItem(productId) {
        cartItems = cartItems.filter(item => item.id !== productId);
        localStorage.setItem('cart', JSON.stringify(cartItems));
        renderCart();
    }

    function cartUpdate(id, quantity) {
        const cart = localStorage.getItem('cart') || null;
        if (cart) {
            cartItems = JSON.parse(cart);
            const itemIndex = cartItems.findIndex(item => item.id === id);
            if (itemIndex !== -1) {
                cartItems[itemIndex].quantity = quantity;
                localStorage.setItem('cart', JSON.stringify(cartItems));
                renderCart();
            } else {
                swal({
                    title: "Error",
                    text: "Item not found in cart.",
                    icon: "error",
                    button: "OK",
                });
            }
        } else {
            swal({
                title: "Error",
                text: "Cart not found.",
                icon: "error",
                button: "OK",
            });
        }

        renderCart();

    }
    </script>
</body>

</html>