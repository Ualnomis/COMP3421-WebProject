// Get the + and - buttons and the input field
const plusBtn = document.querySelector('#btn-add-quantity');
const minusBtn = document.querySelector('#btn-minus-quantity');
const inputField = document.querySelector('input[name="order-quantity"]');
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

function showAlert(alertType, message) {
  const alertHtml = `
    <div class="alert alert-${alertType} alert-dismissible fade show" role="alert">
      ${message}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  `;
  document.getElementById('alert-container').innerHTML = alertHtml;
}

document.getElementById('add-to-cart-form').addEventListener('submit', (event) => {
  event.preventDefault();
  const formData = new FormData(event.target);
  fetch('../includes/add-to-cart.php', {
    method: 'POST',
    body: formData
  }).then(handleResponse)
    .catch(handleError);
});

function handleResponse(response) {
  if (response.ok) {
    showAlert('success', 'Product added to cart!');
    updateCartCount();
  } else {
    response.json().then(data => {
      showAlert('danger', data.error);
    }).catch(() => {
      showAlert('danger', 'Failed to add product to cart.');
    });
  }
}

function handleError(error) {
  console.error(error);
  showAlert('danger', 'An error occurred while adding the product to cart.');
}

updateCartCount();
