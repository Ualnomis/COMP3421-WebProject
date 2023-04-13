<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col-12 d-flex">
                    <h2 class="page-title">
                        <?php echo $page_title; ?>
                    </h2>


                </div>
                <?php
                if (isset($search_field)) {
                    echo $search_field;
                }
                ?>
            </div>
        </div>
    </div>