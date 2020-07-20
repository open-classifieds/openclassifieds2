<span class='st_whatsapp_large' displayText='WhatsApp'></span>
<span class='st_facebook_large' displayText='Facebook'></span>
<span class='st_twitter_large' displayText='Tweet'></span>
<span class='st_linkedin_large' displayText='LinkedIn'></span>
<span class='st_pinterest_large' displayText='Pinterest'></span>
<span class='st_googleplus_large' displayText='Google +'></span>
<span class='st_vkontakte_large' displayText='Vkontakte'></span>
<span class='st_odnoklassniki_large' displayText='Odnoklassniki'></span>
<span class='st_email_large' displayText='Email'></span>
<span class='st_print_large' displayText='Print'></span>

<script type="text/javascript">var switchTo5x=true;</script>
<script type="text/javascript" src="//ws.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "12d591ee-d8ab-456d-807f-f11fb504d15b", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>

<?if (core::config('advertisement.logbee')==1 AND class_exists('Model_Ad') AND Model_Ad::current()!==NULL AND Model_Ad::current()->loaded()):?>
    <script type="text/javascript">function logbee_wopen(url){ var win = window.open(url, 'logbee', 'width=1200, height=1000, location=no, menubar=no, status=no, toolbar=no, scrollbars=yes, resizeable=yes'); win.resizeTo(w, h); win.focus(); }</script>

    <div class="logbee">
        <a href="" class="log-it-button" onclick="javascript:logbee_wopen('http://www.logbee.com/add?url=<?=URL::current()?>');return false;"><img src="//www.logbee.com/img/affiliation/logbee_portal_button_logit_60x25.png" border="0" title="log it"></a>

        <div class="clear"></div>
    </div>
<?endif?>
