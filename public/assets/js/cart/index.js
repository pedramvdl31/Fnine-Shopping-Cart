
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

    quantityInput.value = newQuantity;

    // Disable buttons smaller than 1 or equal to stock
    minusButton.disabled = newQuantity <= 1;
    plusButton.disabled = newQuantity >= maxStock;

    // Get the row of the product
    let row = document.querySelector(`tr[data-product-id="${productId}"]`);
    if (row) {
        let priceElement = row.querySelector("td:nth-child(3)"); // Price column
        let totalElement = row.querySelector("td:nth-child(6)"); // Total column

        if (priceElement && totalElement) {
            // Extract price correctly by removing commas and dollar signs
            let priceText = priceElement.textContent.replace(/[$,]/g, ""); 
            let price = parseFloat(priceText); 

            if (!isNaN(price)) {
                let newTotal = price * newQuantity;
                totalElement.textContent = `$${newTotal.toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
            } else {
                console.error(`Error parsing price for product ${productId}:`, priceText);
            }
        }
    }

    // Send request to update the cart in the backend
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
            updateFinalTotal(data.cart); // Update total at bottom
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

// Show the final prices
function updateFinalTotal(cart) {
    // Get all theelements
    let subtotalElement = document.getElementById("subtotal-value");
    let gstElement = document.getElementById("gst-value");
    let qstElement = document.getElementById("qst-value");
    let totalElement = document.getElementById("final-total");

    // reset
    if (!cart || cart.length === 0) {
        subtotalElement.textContent = "$0.00";
        if (gstElement) gstElement.textContent = "$0.00";
        if (qstElement) qstElement.textContent = "$0.00";
        totalElement.textContent = "$0.00";
        return;
    }

    let subtotal = cart.reduce((sum, item) => sum + (item.quantity * item.price), 0);

    let gst = gstElement ? parseFloat(gstElement.dataset.gst || 0) : 0;
    let qst = qstElement ? parseFloat(qstElement.dataset.qst || 0) : 0;

    let gstAmount = subtotal * (gst / 100);
    let qstAmount = subtotal * (qst / 100);
    let total = subtotal + gstAmount + qstAmount;

    function formatNumber(num) {
        return num.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    subtotalElement.textContent = `$${formatNumber(subtotal)}`;
    if (gstElement) gstElement.textContent = `$${formatNumber(gstAmount)}`;
    if (qstElement) qstElement.textContent = `$${formatNumber(qstAmount)}`;
    totalElement.textContent = `$${formatNumber(total)}`;
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



