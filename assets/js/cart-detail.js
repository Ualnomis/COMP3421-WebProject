const plusBtns = document.querySelectorAll('.btn-add-quantity');
const minusBtns = document.querySelectorAll('.btn-minus-quantity');
const inputFields = document.querySelectorAll('input[name="order-quantity"]');

// Loop over all elements and add event listeners
for (let i = 0; i < plusBtns.length; i++) {
    const plusBtn = plusBtns[i];
    const minusBtn = minusBtns[i];
    const inputField = inputFields[i];
    const maxValue = parseInt(inputField.getAttribute('max'));

    // Helper functions
    function getCurrentValue() {
        return parseInt(inputField.value);
    }

    function changeQuantity(delta) {
        const currentValue = getCurrentValue();
        const newValue = currentValue + delta;

        if (newValue >= 1 && newValue <= maxValue) {
            inputField.value = newValue;
        }
    }

    function triggerChangeEvent() {
        const event = new Event('change');
        inputField.dispatchEvent(event);
    }

    // Event listeners
    plusBtn.addEventListener('click', (event) => {
        event.preventDefault();
        changeQuantity(1);
        triggerChangeEvent();
    });

    minusBtn.addEventListener('click', (event) => {
        event.preventDefault();
        changeQuantity(-1);
        triggerChangeEvent();
    });


    inputField.addEventListener('change', () => {
        const cartItemId = inputField.closest('tr').querySelector('.cart-item-id').value;
        let newQuantity = getCurrentValue();
        const sumPriceElement = inputField.closest('tr').querySelector('.cart-item-sum-price');
        if (isNaN(newQuantity) || newQuantity > maxValue || newQuantity.toString().indexOf('e') !== -1) {
            inputField.value = maxValue;
            newQuantity = maxValue;
        }
        
        // Disable buttons
        plusBtn.disabled = true;
        minusBtn.disabled = true;

        // Send an AJAX request to update the quantity in the database
        fetch('../includes/update-cart-item.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                cart_item_id: cartItemId,
                quantity: newQuantity,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    // Update the sum price
                    sumPriceElement.textContent = data.new_sum_price;

                    // Update the total price
                    const totalPriceElement = document.querySelector('.total-price');
                    totalPriceElement.textContent = data.new_total_price;
                } else {
                    // Handle error
                    alert('Error updating quantity.');
                }
            })
            .catch((error) => {
                // Handle error
                alert('Error updating quantity: ' + error);
            })
            .finally(() => {
                // Re-enable buttons
                plusBtn.disabled = false;
                minusBtn.disabled = false;
            });
    });
}
