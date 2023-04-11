// Get the + and - buttons and the input field
const plusBtn = document.querySelector('#btn-add-quantity');
const minusBtn = document.querySelector('#btn-minus-quantity');
const inputField = document.querySelector('input[name="order-quantity"]');

// Add event listener for the + button
plusBtn.addEventListener('click', () => {
  // Get the current value of the input field
  let currentValue = parseInt(inputField.value);
  // Get the maximum value of the input field
  let maxValue = parseInt(inputField.getAttribute('max'));
  // If the current value is less than the maximum value, increase the value by 1
  if (currentValue < maxValue) {
    inputField.value = currentValue + 1;
  }
});

// Add event listener for the - button
minusBtn.addEventListener('click', () => {
  // Get the current value of the input field
  let currentValue = parseInt(inputField.value);
  // If the current value is greater than 1, decrease the value by 1
  if (currentValue > 1) {
    inputField.value = currentValue - 1;
  }
});

const maxValue = parseInt(inputField.getAttribute('max'));

// Add event listener for the input field
inputField.addEventListener('input', () => {
  // Get the current value of the input field
  let currentValue = parseInt(inputField.value);
  if (isNaN(currentValue) || currentValue > maxValue || currentValue.indexOf('e') !== -1) {
    // If the entered value is invalid, set the input field value to the maximum value
    inputField.value = maxValue;
  }
});