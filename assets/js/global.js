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

const body = document.querySelector('body');
const home_li = document.getElementById('home_li');
const gift_li = document.getElementById('gift_li');

function checkUrl() {
    const currentUrl = window.location.href;

    try{
        if (currentUrl.includes('product')) {
            gift_li.classList.add('active');
            home_li.classList.remove('active');
            body.classList.remove('overflow-hidden');
        } else {
            home_li.classList.add('active');
            gift_li.classList.remove('active');
            body.classList.add('overflow-hidden');
        }  
    }catch(error){

    }
  }

checkUrl();

window.addEventListener('popstate', function(event) {checkUrl();});
document.addEventListener('click', function(event) {
    try{
        const url = event.target.href;
        window.history.pushState(null, null, url);
        checkUrl();  
    }catch(error){

    }
  });

updateCartCount();


