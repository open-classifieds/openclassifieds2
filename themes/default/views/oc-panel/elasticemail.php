<?if (core::cookie('elastic_alert')!=1  AND Auth::instance()->get_user()->is_admin()):?>
<div class="relative px-3 py-3 mb-4 border rounded text-yellow-800 border-yellow-600 bg-yellow-200 opacity-0 in">
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" data-dismiss="relative px-3 py-3 mb-4 border rounded" aria-hidden="true" onclick='setCookie("elastic_alert",1,365)'>×</button>
        <p>
            <strong class="text-green-500">PRO Tip:</strong> 
            Do you want your emails to reach users inbox? Do you want to trace your e-mails? 
            Try <a href="http://j.mp/elasticemailoc" class="font-bold " target="_blank">ElasticEmail!</a> $0.09/1,000 emails.
        </p>
        <p>
            <a class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-green-100 bg-green-500 hover:bg-green-400" href="http://j.mp/elasticemailoc" target="_blank" onclick='setCookie("elastic_alert",1,365)' >Sign Up</a>
        </p>
    </div>
<?endif?>