<div class="modal fade" id="myLocation" tabindex="-1" role="dialog" aria-labelledby="myLocationLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="input-group">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-distance btn-default dropdown-toggle" data-toggle="dropdown">
                            <span class="label-icon"><?=i18n::format_measurement(Core::cookie('mydistance', Core::config('advertisement.auto_locate_distance', 2)))?></span>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu pull-left" role="menu">
                            <li>
                                <a href="#" data-value="2"><?=i18n::format_measurement(2)?></a>
                            </li>
                            <li>
                                <a href="#" data-value="5"><?=i18n::format_measurement(5)?></a>
                            </li>
                            <li>
                                <a href="#" data-value="10"><?=i18n::format_measurement(10)?></a>
                            </li>
                            <li>
                                <a href="#" data-value="20"><?=i18n::format_measurement(20)?></a>
                            </li>
                            <li>
                                <a href="#" data-value="50"><?=i18n::format_measurement(50)?></a>
                            </li>
                            <li>
                                <a href="#" data-value="250"><?=i18n::format_measurement(250)?></a>
                            </li>
                            <li>
                                <a href="#" data-value="500"><?=i18n::format_measurement(500)?></a>
                            </li>
                        </ul>
                    </div>
                    <input type="hidden" name="distance" id="myDistance" value="<?=Core::cookie('mydistance', Core::config('advertisement.auto_locate_distance', 2))?>" disabled>
                    <input type="hidden" name="latitude" id="myLatitude" value="" disabled>
                    <input type="hidden" name="longitude" id="myLongitude" value="" disabled>
                    <?=FORM::input('myAddress', Request::current()->post('address'), array('class'=>'form-control', 'id'=>'myAddress', 'placeholder'=>__('Where do you want to search?')))?>
                    <span class="input-group-btn">
                        <button id="setMyLocation" class="btn btn-default" type="button"><?=_e('Ok')?></button>
                    </span>
                </div>
                <br>
                <div id="mapCanvas"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?=_e('Close')?></button>
                <?if (core::request('userpos') == 1) :?>
                    <a class="btn btn-danger" href="?<?=http_build_query(['userpos' => NULL] + Request::current()->query())?>"><?=_e('Remove')?></a>
                <?endif?>
            </div>
        </div>
    </div>
</div>
