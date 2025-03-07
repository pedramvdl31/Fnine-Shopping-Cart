
// Add an item to the cart
function addToCart(productId, name, price, stock) {
    let quantityInput = document.getElementById(`quantity-${productId}`);
    let addButton = document.getElementById(`add-to-cart-${productId}`);
    let quantityToAdd = parseInt(quantityInput.value);

    if (isNaN(quantityToAdd) || quantityToAdd <= 0) {
        showToast("Please enter a valid quantity.", "error");
        return;
    }

    checkStockLimit(productId, quantityToAdd, stock);

    fetch('/cart/add', {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        },
        body: JSON.stringify({ 
            product_id: productId, 
            quantity: quantityToAdd,
            price: price 
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartUI(data.cart); // Update UI with new cart data

            // Check if the new quantity matches the stock
            let cartItem = data.cart.find(item => item.product_id === productId);
            if (cartItem && cartItem.quantity >= stock) {
                addButton.disabled = true;
                quantityInput.disabled = true;
            }

            showToast(`${name} added to cart!`, "success");

        } else {
            showToast(data.error || "Failed to add to cart.", "error");
        }
    })
    .catch(error => console.error("Error adding to cart:", error));
}

// Remove an item from the cart
function removeCart(productId) {
    fetch('/cart/remove', {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        },
        body: JSON.stringify({ product_id: Number(productId) }) // Ensure it's an integer
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartUI(data.cart); // This already updates the badge count
            enableProductControls(productId); // Re-enable button and quantity input
            showToast("Item removed from cart.", "success");
        } else {
            showToast(data.error || "Failed to remove item.", "error");
        }
    })
    .catch(error => console.error("Error removing from cart:", error));
}

//Enabling buttons of a product
function enableProductControls(productId) {
    let addButton = document.getElementById(`add-to-cart-${productId}`);
    let quantityInput = document.getElementById(`quantity-${productId}`);

    if (addButton) addButton.disabled = false;
    if (quantityInput) quantityInput.disabled = false;
}

//Update the exisiting cart item
function updateCartQuantity(productId, change) {
    let quantityInput = document.getElementById(`cart-qty-${productId}`);
    let newQuantity = parseInt(quantityInput.value) + change;

    if (isNaN(newQuantity) || newQuantity < 1) {
        newQuantity = 1;
    }

    // Prevent exceeding stock
    let maxStock = parseInt(quantityInput.max);
    if (newQuantity > maxStock) {
        newQuantity = maxStock;
    }

    quantityInput.value = newQuantity; // Update input field

    checkStockLimit(productId, newQuantity, maxStock);

    // Send request to backend
    fetch('/cart/update', {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        },
        body: JSON.stringify({ product_id: productId, quantity: newQuantity })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartUI(data.cart); // Refresh UI
        } else {
            showToast(data.error || "Failed to update quantity.", "error");
        }
    })
    .catch(error => console.error("Error updating cart:", error));
}

//Update the exisiting cart item if the user typed in the qty
function manualUpdateQuantity(productId) {
    let quantityInput = document.getElementById(`cart-qty-${productId}`);
    let minusButton = document.querySelector(`#minus-btn-${productId}`);
    let plusButton = document.querySelector(`#plus-btn-${productId}`);

    let newQuantity = parseInt(quantityInput.value);
    if (isNaN(newQuantity) || newQuantity < 1) {
        newQuantity = 1;
    }

    let maxStock = parseInt(quantityInput.max);
    if (newQuantity > maxStock) {
        newQuantity = maxStock;
    }

     // Correct invalid input values if the user typed in a larger qty
    quantityInput.value = newQuantity;

    // Disable minus button if quantity is 1
    minusButton.disabled = newQuantity <= 1;

    // Disable plus button if quantity reaches stock
    plusButton.disabled = newQuantity >= maxStock;

    checkStockLimit(productId, newQuantity, maxStock);

    // Send update request to backend
    fetch('/cart/update', {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        },
        body: JSON.stringify({ product_id: productId, quantity: newQuantity })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartUI(data.cart);
        } else {
            showToast(data.error || "Failed to update quantity.", "error");
        }
    })
    .catch(error => console.error("Error updating cart:", error));
}

// Check if quantity matches stock qty
function checkStockLimit(productId, quantity, stock) {
    let addButton = document.getElementById(`add-to-cart-${productId}`);
    let quantityInput = document.getElementById(`quantity-${productId}`);

    if (quantity >= stock) {
        if (addButton) addButton.disabled = true;
        if (quantityInput) quantityInput.disabled = true;
    } else {
        if (addButton) addButton.disabled = false;
        if (quantityInput) quantityInput.disabled = false;
    }
}

