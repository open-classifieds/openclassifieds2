<?defined('SYSPATH') or exit('Install must be loaded from within index.php!');?>

<div class="page-header">
    <a class="btn btn-default pull-right" id="advanced-options" ><?=__("Advanced options")?></a>
    <h1><?=__("Welcome to")?> Yclas Self-Hosted <?=__("installation")?></h1>
    <p>
        <?=__("Welcome to the super easy and fast installation")?>. 
            <a href="https://selfhosted.yclas.com/" target="_blank">
            <?=__("If you need any help please check our professional services")?></a>.
    </p>
    
    <div class="clearfix"></div>       
</div>

<?if (!empty(install::$msg) OR !empty(install::$error_msg)):?>
    <?install::view('hosting')?>
<?endif?>

<form method="post" action="" class=" form-horizontal" >
    <div class="col-md-6" style="padding-left:0px;">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3>1. <?=__('Site Configuration')?></h3>
            </div>
            <div class="panel-body">
                <div class="form-group">                
                    <div class="col-md-12">
                        <label class="control-label"><?=__("Site Language")?></label>
                        <select class="form-control" data-toggle="tooltip" title="<?=__("Site Language")?>" name="LANGUAGE" onchange="window.location.href='?LANGUAGE='+this.options[this.selectedIndex].value" required>
                            <?php 
                            $languages = scandir("languages");
                            foreach ($languages as $lang) 
                            {    
                                if( strpos($lang,'.')==false && $lang!='.' && $lang!='..' )
                                {
                                    $sel = ( strtolower($lang)==strtolower(install::$locale)) ? ' selected="selected"' : '';
                                    echo "<option$sel value=\"$lang\">$lang</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group adv">                
                    <div class="col-md-12">
                    <label class="control-label"><?=__("Site URL");?>:</label>
                    <input type="text" size="75" name="SITE_URL" value="<?=core::request('SITE_URL',install::$url)?>"  class="form-control" data-toggle="tooltip" title="<?=__("Site URL");?>" required />
                    </div>
                </div>

                <div class="form-group adv">                
                    <div class="col-md-12">
                    <label class="control-label"><?=__("Installation Folder");?>:</label>
                    <input  type="text" size="75" name="SITE_FOLDER" value="<?=core::request('SITE_FOLDER',install::$folder)?>"  class="form-control" data-toggle="tooltip" title="<?=__("Installation Folder");?>" required />
                    </div>
                </div>

                <div class="form-group">                
                    <div class="col-md-12">
                    <label class="control-label"><?=__("Site Name")?>:</label>
                    <input  type="text" name="SITE_NAME" placeholder="<?=__("Site Name")?>" value="<?=core::request('SITE_NAME')?>" class="form-control" data-toggle="tooltip" title="<?=__("Site Name")?>" required />
                    </div>
                </div>

                <div class="form-group mb-10">                
                    <div class="col-md-12">
                    <label class="control-label"><?=__("Time Zone")?>:</label>
                    <?=install::get_select_timezones('TIMEZONE',core::request('TIMEZONE',date_default_timezone_get()))?>
                    </div>
                </div>
            
                <ul class="nav nav-tabs" id="myTab" style="display:none;">
                  <li class="active"><a href="#install" data-toggle="tab"><?=__('New Install')?></a></li>
                  <li><a href="#upgrade" data-toggle="tab"><?=__('Reinstall System')?></a></li>
                </ul>
                 
                <div class="tab-content">

                    <div class="tab-pane active" id="install">
                        <div class="form-group">
                        
                            <div class="col-md-12">
                                <label class="control-label"><?=__("Administrator email")?>:</label>
                                <input type="email" name="ADMIN_EMAIL" value="<?=core::request('ADMIN_EMAIL')?>" placeholder="your@email.com" class="form-control" data-toggle="tooltip" title="<?=__("Administrator email")?>" required />
                            </div>
                        </div>

                        <div class="form-group">                        
                            <div class="col-md-12">
                                <label class="control-label"><?=__("Admin Password")?>:</label>
                                <input type="text" name="ADMIN_PWD" value="<?=core::request('ADMIN_PWD')?>" class="form-control" data-toggle="tooltip" title="<?=__("Admin Password")?>" required />   
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="upgrade">
                        <div class="form-group">
                        
                            <div class="col-md-12">
                                <label class="control-label"><?=__("Hash Key")?>:</label>
                                <input type="text" name="HASH_KEY" value="<?=core::request('HASH_KEY')?>" class="form-control" data-toggle="tooltip" title="<?=__("Hash Key")?>" />   
                                <span class="help-block"><?=__('You need the Hash Key to re-install. You can find this value if you lost it at')?> <code>/oc/config/auth.php</code></span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6" style="padding-right:0px;">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3>2. <?=__('Database Configuration')?></h3>
            </div>
            <div class="panel-body">
                <p><a target="_blank" href="https://docs.yclas.com/create-mysql-database/"><?=__("How to create a MySQL database?")?></a></p>
                <div class="form-group">                
                    <div class="col-md-12">
                    <label class="control-label"><?=__("Host name")?>:</label>
                    <input  type="text" name="DB_HOST" value="<?=core::request('DB_HOST','localhost')?>" class="form-control" data-toggle="tooltip" title="<?=__("Host name")?>" required />
                    </div>
                </div>

                <div class="form-group">                
                    <div class="col-md-12">
                    <label class="control-label"><?=__("User name")?>:</label>
                    <input  type="text" name="DB_USER"  value="<?=core::request('DB_USER','root')?>" class="form-control" data-toggle="tooltip" title="<?=__("User name")?>"  required />
                    </div>
                </div>

                <div class="form-group">                
                    <div class="col-md-12">
                    <label class="control-label"><?=__("Password")?>:</label>
                    <input type="text" name="DB_PASS" value="<?=core::request('DB_PASS')?>" class="form-control" data-toggle="tooltip" title="<?=__("Password")?>"  />       
                    </div>
                </div>

                <div class="form-group">                
                    <div class="col-md-12">
                    <label class="control-label"><?=__("Database name")?>:</label>
                    <input type="text" name="DB_NAME" value="<?=core::request('DB_NAME','openclassifieds')?>" class="form-control" data-toggle="tooltip" title="<?=__("Database name")?>" required />
                    </div>
                </div>

                <div class="checkbox">
                    <label><input type="checkbox" name="SAMPLE_DB" checked data-toggle="tooltip" title="<?=__("Creates few sample categories to start with")?>"/><?=__("Sample data")?></label>
                </div>

                <div class="form-group adv">
                    <div class="col-sm-12">
                    <div class="checkbox">
                    <label>
                        <input type="checkbox" name="DB_CREATE"  data-toggle="tooltip" title="<?=__("Will try to create the DB if doesn't exists. Root permissions required.")?>"/>
                                <?=__("Create DB.")?>
                                <br>
                                
                        </label>
                    </div>
                    </div>
                </div>
                <div class="form-group adv">                
                    <div class="col-md-12">
                    <label class="control-label"><?=__("Database charset")?>:</label>
                    <input type="text" name="DB_CHARSET" value="<?=core::request('DB_CHARSET','utf8mb4')?>" class="form-control" data-toggle="tooltip" title="<?=__("Database charset")?>" required />
                    </div>
                </div>

                <div class="form-group adv">                
                    <div class="col-md-12">
                    <label class="control-label"><?=__("Table prefix")?>:</label>
                    <input type="text" name="TABLE_PREFIX" value="<?=core::request('TABLE_PREFIX','yc4_')?>" class="form-control" data-toggle="tooltip" title="<?=__("Allows multiple installations in one database if you give each one a unique prefix")?>. <?=__("Only numbers, letters, and underscores")?>." required />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="form-actions">
        <input type="submit" name="action" id="submit" value="<?=__("Install")?>" class="btn btn-primary btn-lg " />
    </div>
    <div class="clearfix"></div>
          
</form>
