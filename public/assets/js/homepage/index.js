// Toggle Cart's Dropdown
function toggleCartDropdown() {
    let dropdown = document.querySelector('.cart-dropdown');
    dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
}

// Refresh/Update The Carts UI in the Dropdown
function updateCartUI(cart) {
    let cartItemsContainer = document.querySelector('.cart-items');
    let checkoutButton = document.querySelector('.view-cart-btn');
    cartItemsContainer.innerHTML = '';

    // Update cart budge count
    updateCartCount(cart);

    if (cart.length === 0) {
        cartItemsContainer.innerHTML = '<p class="text-center">Cart is empty</p>';
        checkoutButton.disabled = true;
        checkoutButton.classList.add("disabled-btn");
        return;
    }

    checkoutButton.disabled = false;
    checkoutButton.classList.remove("disabled-btn");

    cart.forEach(item => {

        // Exit If Product Does Not Exist
        if (!item.product) return;

        let itemTotal = formatCurrency(item.price * item.quantity);
        let isMinQuantity = item.quantity <= 1;
        let isMaxQuantity = item.quantity >= item.product.stock;

        let cartItem = document.createElement('div');
        cartItem.classList.add('cart-item');
        cartItem.innerHTML = `
            <img src="${item.product.image_url}" alt="${item.product.name}" class="cart-item-img">
            <div class="cart-info">
                <p>${item.product.name}</p>
                <div class="cart-quantity">
                    <button id="minus-btn-${item.product_id}" 
                            onclick="updateCartQuantity(${item.product_id}, -1)" 
                            ${isMinQuantity ? 'disabled' : ''}>-</button>
                    
                    <input type="number" id="cart-qty-${item.product_id}" value="${item.quantity}" 
                           min="1" max="${item.product.stock}" 
                           oninput="manualUpdateQuantity(${item.product_id})">
                    
                    <button id="plus-btn-${item.product_id}" 
                            onclick="updateCartQuantity(${item.product_id}, 1)" 
                            ${isMaxQuantity ? 'disabled' : ''}>+</button>
                </div>
                <br>
                <small class="cart-item-total">Total: ${itemTotal}</small>
            </div>
            <button onclick="removeCart(${item.product_id})" class="remove-btn">X</button>
        `;
        cartItemsContainer.appendChild(cartItem);
    });
}



// Update cart count in UI
function updateCartCount(cart) {
    let totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    let cartBadge = document.querySelector('.cart-badge');
    if (cartBadge) {
        cartBadge.textContent = totalItems;
    }
}

// Check the available stock when adding an item to the stock
function checkStock(productId, stock) {
    let quantityInput = document.getElementById(`quantity-${productId}`);
    let addButton = document.getElementById(`add-to-cart-${productId}`);

    if (!quantityInput || !addButton) return;

    let quantity = parseInt(quantityInput.value);

    if (isNaN(quantity) || quantity < 1) {
        quantityInput.value = 1;
        addButton.disabled = false;
    } else if (quantity > stock) {
        addButton.disabled = true;
    } else {
        addButton.disabled = false;
    }
}

