<?php defined('SYSPATH') or die('No direct script access.');?>
<h1 class="page-header page-title" id="crud-<?=$name?>"><?=__('Update')?> <?=Text::ucfirst(__($name))?></h1>
<hr>
  <p>
    <?$controllers = Model_Access::list_controllers()?>
    <a target="_blank" href="<?=Route::url('oc-panel',array('controller'=>'order','action'=>'index'))?>?email=<?=$form->object->email?>">
      <?=__('Orders')?>
    </a>
    <?if (array_key_exists('ticket', $controllers)) :?>
      - <a target="_blank" href="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'index','id'=>'admin'))?>?search=<?=$form->object->email?>">
          <?=__('Tickets')?></a>
      </a>
    <?endif?>
    <?if (array_key_exists('ad', $controllers)) :?>
      - <a target="_blank" href="<?=Route::url('profile',array('seoname'=>$form->object->seoname))?>">
          <?=__('Ads')?>
      </a>
    <?endif?>
    <?if (core::config('advertisement.reviews')==1 OR core::config('product.reviews')==1):?>
      - <a target="_blank" href="<?=Route::url('oc-panel',array('controller'=>'review','action'=>'index'))?>?email=<?=$form->object->email?>">
          <?=__('Reviews')?>
      </a>
    <?endif?>
  </p>
<div class="flex flex-wrap">
    <div class="md:w-2/3 pr-4 pl-4">
        <div class="panel panel-default">
            <div class="panel-body">
                <?=$form->render()?>
            </div>
        </div>
        <?if (Auth::instance()->get_user()->is_admin()):?>
          <div class="panel panel-default">
              <div class="panel-heading" id="page-edit-profile">
                  <h3 class="panel-title"><?=__('Change password')?></h3>
              </div>
              <div class="panel-body">
                  <div class="flex flex-wrap">
                      <div class="md:w-2/3 pr-4 pl-4">
                          <form class="form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('controller'=>'user','action'=>'changepass','id'=>$form->object->id_user))?>">         
                              <?=Form::errors()?>  
                                    
                              <div class="mb-4">
                                  <label class="sm:w-1/3 pr-4 pl-4 control-label"><?=__('New password')?></label>
                                  <div class="sm:w-2/3 pr-4 pl-4">
                                  <input class="block appearance-none w-full py-1 px-2 mb-1 text-base leading-normal bg-white text-grey-800 border border-gray-500 rounded" type="password" name="password1" placeholder="<?=__('Password')?>">
                                  </div>
                              </div>
                                
                              <div class="mb-4">
                                  <label class="sm:w-1/3 pr-4 pl-4 control-label"><?=__('Repeat password')?></label>
                                  <div class="sm:w-2/3 pr-4 pl-4">
                                  <input class="block appearance-none w-full py-1 px-2 mb-1 text-base leading-normal bg-white text-grey-800 border border-gray-500 rounded" type="password" name="password2" placeholder="<?=__('Password')?>">
                                      <p class="help-block">
                                            <?=__('Type your password twice to change')?>
                                      </p>
                                  </div>
                              </div>
                                    
                              <div class="mb-4">
                                  <div class="col-md-offset-4 md:w-2/3 pr-4 pl-4">
                                      <button type="submit" class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-blue-100 bg-blue-500 hover:bg-blue-400"><?=__('Update')?></button>
                                  </div>
                              </div>
                                    
                          </form>
                      </div>
                  </div>
              </div>
          </div>
        <?endif?>
    </div>
</div>
