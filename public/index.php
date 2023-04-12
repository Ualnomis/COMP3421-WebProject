<?php
$title = "Index";
$styles = "";
$page_title = "";

include_once('../includes/header.inc.php');
include_once('../includes/navbar.inc.php');
// include_once('../includes/page-wrapper-start.inc.php');
?>

<!-- Page body -->
<script type="module" src="../assets/js/home.js"></script>
<div class="page-body m-0 p-0 overflow-hidden">
    <div class="w-fit xl:h-[70%] flex xl:justify-between justify-start items-start flex-col xl:py-8 xl:px-36 sm:p-8 p-6 max-xl:gap-7 absolute z-10 select-none">
        <!-- Content here -->
        <div class=" flex-1 xl:justify-center justify-start flex flex-col gap-10 z-10">
            <div data-aos="fade-down-right">
                <h1 class="xl:text-[6rem] text-[3rem] xl:leading-[7rem] leading-[3rem] font-black text-white">
                    Find <br class="xl:block hidden" /> Your Perfect Fit.
                </h1> 
            </div>
            <div class="flex flex-col gap-5 z-10" data-aos="fade-up-right">
                <p class="max-w-md font-normal text-white-600 text-base text-justify">
                    <strong>Giftify</strong>  is an online gift store that offer Unique Gifts for your loved ones. We have a wide range of products to choose from. 
                </p>
            </div>

            <a href="product.php" class="w-fit bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded z-20" data-aos="fade-up-right">
                View Gifts
            </a>
        </div>
    </div>
    <div id="home_canvas" class=" absolute w-full h-[calc(100%_-_56px)]" ></div>
</div>

<?php
include_once('../includes/page-wrapper-end.inc.php');
$scripts = "";
include_once('../includes/footer.inc.php');
?>
