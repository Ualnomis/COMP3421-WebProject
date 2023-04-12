import * as THREE from 'three';
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';
import { DRACOLoader } from 'three/addons/loaders/DRACOLoader.js';
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';

const swup = new Swup();

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
    }
}

function globalInit() {
    console.log("load global.js")
    
    const body = document.querySelector('body');
    const home_li = document.getElementById('home_li');
    const gift_li = document.getElementById('gift_li');
    
    function checkUrl() {
        const currentUrl = window.location.href;
    
        try{
            if (currentUrl.includes('php')) {
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
            checkUrl();  
        }catch(error){
    
        }
      });

    updateCartCount();
}

function product_details() {
    
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
    fetch('../includes/add-to-cart.inc.php', {
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

}

function home_canvas() {
        // something like new Carousel('#carousel')
        const canvas = document.getElementById('home_canvas');
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera( 75, canvas.offsetWidth / canvas.offsetHeight, 0.1, 1000 );

        const loader = new GLTFLoader();
        const dracoLoader = new DRACOLoader();
        dracoLoader.setDecoderPath( '/examples/jsm/libs/draco/' );
        loader.setDRACOLoader( dracoLoader );

        const renderer = new THREE.WebGLRenderer({ alpha: true });
        renderer.setSize( canvas.offsetWidth, canvas.offsetHeight );
        renderer.toneMapping = THREE.ReinhardToneMapping;
        renderer.toneMappingExposure = 2.3;
        renderer.shadowMap.enabled = true;

        canvas.appendChild( renderer.domElement );
        let model;

        const pointLight = new THREE.PointLight(0xffffff, 10);
        pointLight.position.set(0, 0, 0);
        pointLight.castShadow = true;
        scene.add(pointLight);

        const ambientLight = new THREE.AmbientLight(0xffffff, 50);
        scene.add(ambientLight);

        // const pointLightHelper = new THREE.PointLightHelper(pointLight, 1);
        // scene.add(pointLightHelper);


        const gridHelper = new THREE.GridHelper( 200, 50 );
        scene.add( gridHelper );
        // scene.add(new THREE.AxesHelper(500));

        const spotLight = new THREE.SpotLight(0xffffff, 1);
        spotLight.position.set(10, 10, 10);
        scene.add(spotLight);

        const spotLight2 = new THREE.SpotLight(0xffffff, 1);
        spotLight2.position.set(-10, 10, -10);
        scene.add(spotLight2);

        const spotLight3 = new THREE.SpotLight(0xffffff, 1);
        spotLight3.position.set(10, 10, -10);
        scene.add(spotLight3);

        const spotLight4 = new THREE.SpotLight(0xffffff, 1);
        spotLight4.position.set(-10, 10, 10);
        scene.add(spotLight4);

        const spotLight5 = new THREE.SpotLight(0xffffff, 1);
        spotLight5.position.set(0, -10, 0);
        scene.add(spotLight5);

        const spotLight6 = new THREE.SpotLight(0xffffff, 1);
        spotLight6.position.set(0, 10, 0);
        scene.add(spotLight6);

        const spotLight7 = new THREE.SpotLight(0xffffff, 1);
        spotLight7.position.set(0, 0, 10);
        scene.add(spotLight7);

        const spotLight8 = new THREE.SpotLight(0xffffff, 1);
        spotLight8.position.set(0, 0, -10);
        scene.add(spotLight8);

        loader.load('../assets/model/ring.glb',
            function ( gltf ) {
                console.log(gltf);
                model = gltf.scene

                // model.rotation.x = Math.PI / 8;
                // model.position.y = 1
                model.scale.set(1,1,1)
                model.traverse(n =>{
                    if(n.isMesh){
                        n.castShadow = true;
                        n.receiveShadow = true;
                        if(n.material.map){
                            n.material.map.anisotropy = 16;
                        }
                    }
                })
                scene.add(model);
            },
            // called while loading is progressing
            function ( xhr ) {console.log( ( xhr.loaded / xhr.total * 100 ) + '% loaded' );},
            function ( error ) {console.log( 'An error happened' );}
        );

        const controls = new OrbitControls( camera, renderer.domElement);
        controls.addEventListener( 'change', function() { renderer.render( scene, camera ); } );


        camera.position.z = 10
        camera.position.y = 10
        camera.position.x = 10

        const addStar = () => {
            const geometry = new THREE.SphereGeometry(0.25, 24, 24);
            const material = new THREE.MeshStandardMaterial({color: 0xffffff});
            const star = new THREE.Mesh(geometry, material);
            const [x, y, z] = Array(3).fill().map(() => THREE.MathUtils.randFloatSpread(100));
            star.position.set(x, y, z);
            scene.add(star);
        }

        Array(200).fill().forEach(addStar);

        // const spaceTexture = new THREE.TextureLoader().load('../assets/images/space.jpg');
        // scene.background = spaceTexture;

        function onWindowResize() {
            renderer.setSize(window.innerWidth, window.innerHeight);
        }
        
        window.addEventListener('resize', onWindowResize);

        function animate() {
            requestAnimationFrame( animate );
            controls.update();
            // spotLight.position.set(camera.position.x +10, camera.position.y+10, camera.position.z+10);
            if(model){
                model.rotation.y += 0.01;
                model.rotation.x += 0.01;
                model.rotation.z += 0.01;
            }
            renderer.render( scene, camera );
        }

        animate();

}

function cart_details() {
    const plusBtns = document.querySelectorAll('.btn-add-quantity');
    const minusBtns = document.querySelectorAll('.btn-minus-quantity');
    const inputFields = document.querySelectorAll('input[name="order-quantity"]');
    
    const clearCartButtons = document.querySelectorAll('[data-confirm]');
    clearCartButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            const confirmed = confirm(button.dataset.confirm);
            if (!confirmed) {
                event.preventDefault();
            }
        });
    });
    
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
            fetch('../includes/update-cart-item.inc.php', {
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
}

function add_Product(){

    
}

function edit_Product(){

}

function init() {
    globalInit();

    if (document.querySelector('#home_canvas')) {
        console.log("load home canvas js");
        home_canvas();
    }

    if (document.querySelector("#add-to-cart-form")){
        console.log("load product_details js");
        product_details();
    }

    if (document.querySelector("#cart_detail")){
        console.log("load cart_details js");
        cart_details();
    }

    if (document.querySelector("#edit_product")){
        console.log("load edit_product js");
        edit_Product();
    }

    if (document.querySelector("#add_product")){
        console.log("load add_product js");
        add_Product();
    }

}

// run once when page loads
if (document.readyState === 'complete') {
  init();
} else {
  document.addEventListener('DOMContentLoaded', () => init());
}

// run after every additional navigation by swup
swup.on('contentReplaced', init);