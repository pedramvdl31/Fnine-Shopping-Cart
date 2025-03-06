
// Updating the quantity of the existing item in the cart
function updateCartQuantity(productId, change) {
    let quantityInput = document.getElementById(`cart-qty-${productId}`);
    let minusButton = document.querySelector(`#minus-btn-${productId}`);
    let plusButton = document.querySelector(`#plus-btn-${productId}`);

    let newQuantity = parseInt(quantityInput.value) + change;
    if (isNaN(newQuantity) || newQuantity < 1) {
        newQuantity = 1;
    }

    let maxStock = parseInt(quantityInput.max);
    if (newQuantity > maxStock) {
        newQuantity = maxStock;
    }

    quantityInput.value = newQuantity; // Update input field

    // Disable minus button if quantity is 1
    minusButton.disabled = newQuantity <= 1;

    // Disable plus button if quantity is equal to stock
    plusButton.disabled = newQuantity >= maxStock;

    // Send request
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
            //Update the total amount in UI
            updateFinalTotal(data.cart);
        } else {
            showToast(data.error || "Failed to update quantity.", "error");
        }
    })
    .catch(error => console.error("Error updating cart:", error));
}

// Update the server if the user change the quantity manually
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

    quantityInput.value = newQuantity; // Correct invalid input values

    // Disable minus button if quantity is 1
    minusButton.disabled = newQuantity <= 1;

    // Disable plus button if quantity reaches stock
    plusButton.disabled = newQuantity >= maxStock;

    // Send update request to backend in real-time
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
            updateFinalTotal(data.cart);
        } else {
            showToast(data.error || "Failed to update quantity.", "error");
        }
    })
    .catch(error => console.error("Error updating cart:", error));
}


// Function to update final total
function updateFinalTotal(cart) {

    // For an empty cart
    if (!cart || cart.length === 0) {
        document.getElementById("subtotal-value").textContent = "$0.00";
        document.getElementById("gst-value").textContent = "$0.00";
        document.getElementById("qst-value").textContent = "$0.00";
        document.getElementById("final-total").textContent = "$0.00";
        return;
    }

    let subtotal = cart.reduce((sum, item) => sum + (item.quantity * item.price), 0);
    let gst = parseFloat(document.querySelector("#gst-value").dataset.gst || 0);
    let qst = parseFloat(document.querySelector("#qst-value").dataset.qst || 0);

    let gstAmount = subtotal * (gst / 100);
    let qstAmount = subtotal * (qst / 100);
    let total = subtotal + gstAmount + qstAmount;

    document.getElementById("subtotal-value").textContent = `$${subtotal.toFixed(2)}`;
    document.getElementById("gst-value").textContent = `$${gstAmount.toFixed(2)}`;
    document.getElementById("qst-value").textContent = `$${qstAmount.toFixed(2)}`;
    document.getElementById("final-total").textContent = `$${total.toFixed(2)}`;
}

// Remove an item from the cart
function removeCart(productId) {
    fetch('/cart/remove', {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        },
        body: JSON.stringify({ product_id: Number(productId) }) // Ensure product ID is an integer
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast("Item removed from cart.", "success");

            // Remove the row from the table
            let row = document.querySelector(`tr[data-product-id="${productId}"]`);
            if (row) {
                row.remove();
            }

            updateFinalTotal(data.cart);

            // If cart is empty show a message in UI
            let cartTable = document.querySelector(".cart-table tbody");
            if (cartTable.children.length === 0) {
                document.querySelector(".cart-items-container").innerHTML = "<p class='text-center'>Your cart is empty</p>";
            }
        } else {
            showToast(data.error || "Failed to remove item.", "error");
        }
    })
    .catch(error => console.error("Error removing from cart:", error));
}



