<?defined('SYSPATH') or exit('Install must be loaded from within index.php!');?>

<?if (!empty(install::$error_msg)):?>
    <br>
    <div class="alert alert-danger"><?=install::$error_msg?></div>
<?endif?>

<?if(!empty(install::$msg)):?>
    <br>
    <div class="alert alert-warning">
        <?=__("We have detected some incompatibilities, installation may not work as expected but you can try.")?> <br>
        <?=install::$msg?>
    </div>
<?endif?>

<div class="jumbotron well">
    <h2><?=__("Oops! You need a compatible Hosting</h2>
    <p class="text-danger"><?=__("Your hosting seems to be not compatible. Check your settings.")?><p>
    <p><?=__("We have partnership with hosting companies to assure compatibility. And we include:")?></p>
        <ul>
            <li><?=__("100% Compatible High Speed Hosting")?></li>
            <li><?=sprintf(__("%s Premium Theme%s of your choice worth %s"),'1','','$129.99')?></li>
            <li><?=sprintf(__("Professional Installation and Support worth %s"),'$89')?></li>
            <li><?=sprintf(__("Free Domain name, worth %s"),'$10')?></li>
            <div class="clearfix"></div><br>
        <a class="btn btn-primary btn-large" href="http://open-classifieds.com/hosting/">
            <i class=" icon-shopping-cart icon-white"></i> <?=sprintf(__("Get your Hosting now! Less than %s a Month"),'$5')?></a>
    </p>
</div>
