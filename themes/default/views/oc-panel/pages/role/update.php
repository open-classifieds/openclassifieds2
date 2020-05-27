<?php defined('SYSPATH') or die('No direct script access.');?>
<h1 class="page-header page-title"><?=__('Update')?> <?=Text::ucfirst($role->name)?></h1>
<hr>
<form action="<?=Route::url('oc-panel',array('controller'=>'role','action'=>'update','id'=>$role->id_role))?>" method="post" accept-charset="utf-8" class="form form-horizontal" >  

    <div class="flex flex-wrap">
        <div class="md:w-full pr-4 pl-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <input type="hidden" name="id_role" value="<?=$role->id_role?>" />
                    <div class="mb-4 ">
                        <label for="name" class="md:w-1/4 pr-4 pl-4 control-label"><?=__('Name')?></label>
                        <div class="md:w-2/5 pr-4 pl-4 sm:w-2/5 pr-4 pl-4 sm:w-full pr-4 pl-4">
                            <input type="text" id="name" name="name" value="<?=$role->name?>" maxlength="45" />
                        </div>
                    </div>
                
                    <div class="mb-4">
                        <label for="description" class="md:w-1/4 pr-4 pl-4 control-label"><?=__('Description')?></label>
                        <div class="md:w-2/5 pr-4 pl-4 sm:w-2/5 pr-4 pl-4 sm:w-full pr-4 pl-4">
                            <input type="text" name="description" maxlength="245" value="<?=$role->description?>" />
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="col-md-offset-3 col-md-offset-3 md:w-2/5 pr-4 pl-4 sm:w-2/5 pr-4 pl-4 sm:w-full pr-4 pl-4">
                            <button type="submit" name="submit" class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-blue-100 bg-blue-500 hover:bg-blue-400"><?=__('Update')?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap">
        <?$i=0; foreach ($controllers as $controller=>$actions):?>
            <?if ($i%3==0):?></div><div class="flex flex-wrap"><?endif?>
                <div class="md:w-1/3 pr-4 pl-4">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h4>
                                <div class="checkbox check-success">
                                    <?=FORM::checkbox($controller.'|*', 'on', (bool) in_array($controller.'.*',$access_in_use), ['id' => $controller.'|*'])?>
                                    <label for="<?=$controller.'|*'?>"><?=$controller?>.*</label>
                                </div>
                            </h4>
                            <p>
                                <?foreach ($actions as $action):?>
                                <div class="checkbox check-success">
                                    <?=FORM::checkbox($controller.'|'.$action, 'on', (bool) in_array($controller.'.'.$action,$access_in_use), ['id' => $controller.'|'.$action])?>
                                    <label for="<?=$controller.'|'.$action?>"><?=$action?></label>
                                </div>
                                <?endforeach?>
                            </p>
                        </div>
                    </div>
                </div>
            <?$i++;
        endforeach?>
    </div><!--/row-->

    <div class="form-actions">
        <button type="submit" name="submit" class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-blue-100 bg-blue-500 hover:bg-blue-400"><?=__('Update')?></button>
    </div>

</form>