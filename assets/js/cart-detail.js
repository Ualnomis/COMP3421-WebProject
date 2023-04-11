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

    // Event listeners
    plusBtn.addEventListener('click', (event) => {
        event.preventDefault();
        changeQuantity(1);
    });

    minusBtn.addEventListener('click', (event) => {
        event.preventDefault();
        changeQuantity(-1);
    });

    inputField.addEventListener('input', () => {
        const currentValue = getCurrentValue();
        if (isNaN(currentValue) || currentValue > maxValue || currentValue.toString().indexOf('e') !== -1) {
            inputField.value = maxValue;
        }
    });
}
