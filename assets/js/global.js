async function updateCartCount() {
    try {
        const response = await fetch('../includes/get-cart-count.inc.php');
        const count = await response.text();
        const badge = document.getElementById('cart-count');
        if (count > 0) {
            badge.textContent = count;
            badge.style.display = 'inline-block';
        } else {
            badge.style.display = 'none';
        }
    } catch (error) {
        console.error(error);
    }
}

updateCartCount();


