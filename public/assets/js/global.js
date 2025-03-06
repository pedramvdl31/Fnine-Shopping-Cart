// Global functions shared with every page

function formatCurrency(value) {
    return `$${parseFloat(value).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
}

// Toast message
function showToast(message, type = "success") {
    let toastContainer = document.getElementById("toast-container");
    let toast = document.createElement("div");
    toast.classList.add("toast", type);
    toast.innerText = message;

    toastContainer.appendChild(toast);

    setTimeout(() => {
        toast.classList.add("fade-out");
        setTimeout(() => {
            toast.remove();
        }, 500);
    }, 3000);
}