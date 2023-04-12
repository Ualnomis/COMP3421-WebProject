import * as THREE from 'three';
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';
import { DRACOLoader } from 'three/addons/loaders/DRACOLoader.js';
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';

const swup = new Swup();

function init() {
    if (document.querySelector('#home_canvas')) {
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
}

// run once when page loads
if (document.readyState === 'complete') {
  init();
} else {
  document.addEventListener('DOMContentLoaded', () => init());
}

// run after every additional navigation by swup
swup.on('contentReplaced', init);