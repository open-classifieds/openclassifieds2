<section>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-7">
                <div class="card card-lg">
                    <div class="card-body">
                        <div class="mb-5">
                            <h1 class="h2 mb-2 text-center"><?= _e('2 Step Authentication') ?></h1>
                        </div>
                        <div class="row no-gutters justify-content-center">
                            <div class="col-lg-8">
                                <?= View::factory('auth/_2step-form', compact('form_action')) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
