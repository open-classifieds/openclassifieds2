<div class="row justify-content-center">
    <div class="col-12 col-md-9">
        <div class="mb-3">
            <h1 class="h2"><?=__("New Forum Topic")?></h1>
        </div>

        <? if ($errors): ?>
            <div class="alert alert-warning">
                <?=__('Some errors were encountered, please check the details you entered.')?>

                <ul class="list-unstyled">
                    <? foreach ($errors as $message): ?>
                        <li><?= $message ?></li>
                    <? endforeach ?>
                </ul>
            </div>
        <? endif ?>

        <?=FORM::open(Route::url('forum-new'), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
            <div class="form-group">
                <?= FORM::label('id_forum', __('Forum'), array('for'=>'id_forum' ))?>
                <select name="id_forum" id="id_forum" class="form-control" required>
                    <option><?=__('Select a forum')?></option>
                    <?foreach ($forums as $f):?>
                        <option value="<?=$f['id_forum']?>" <?=(core::request('id_forum')==$f['id_forum'])?'selected':''?>>
                            <?=$f['name']?></option>
                    <?endforeach?>
                </select>
            </div>

            <div class="form-group">
                <?= FORM::label('title', __('Title'), array('for'=>'title'))?>
                <?= FORM::input('title', core::post('title'), array('placeholder' => __('Title'), 'class' => 'form-control', 'id' => 'title', 'required'))?>
            </div>

            <div class="form-group">
                <?= FORM::label('description', __('Description'), array('for'=>'description'))?>
                <?= FORM::textarea('description', core::post('description'), array('placeholder' => __('Description'), 'class' => 'form-control', 'name'=>'description', 'id'=>'description', 'required'))?>
            </div>

            <?if (core::config('advertisement.captcha') != FALSE):?>
                <div class="form-group">
                    <?if (Core::config('general.recaptcha_active')):?>
                        <?=View::factory('recaptcha', ['id' => 'recaptcha1'])?>
                    <?else:?>
                        <?=__('Captcha')?>*:<br />
                        <?=captcha::image_tag('new-forum')?><br />
                        <?= FORM::input('captcha', "", array('class' => 'form-control', 'id' => 'captcha', 'required'))?>
                    <?endif?>
                </div>
            <?endif?>

            <?= FORM::button(NULL, __('Publish new topic'), array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('forum-new')))?>
        <?= FORM::close()?>
    </div>

    <div class="col-12 col-md-3">
        <? foreach (Widgets::render('sidebar') as $widget) : ?>
            <?= $widget ?>
        <? endforeach ?>
    </div>
</div>
