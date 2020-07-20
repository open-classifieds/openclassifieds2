<button id="safinsa-pay" type="submit" class="btn btn-info pay-btn full-w" action="<?=Route::url('default', ['controller' => 'serfinsa', 'action' => 'pay', 'id' => $order->id_order])?>">
    <span class="fa fa-cart"></span> <?=_e('Pay Now')?>
</button>

<script>
    var win = false;
    var timercheck;

    window.addEventListener("load", function(){
        $('#safinsa-pay').on('click',function(e){
            e.preventDefault();

            $.ajax({
                type: "POST",
                cache: false,
                url: $(this).attr('action'),
                success: function(data) {
                    if(data && data.status == 1){
                        popupwindow(data.path, '', 600, 850);
                    }
                }
            });
        });
    })

    function popupwindow(url, title, w, h) {
        //checks to see if window is open
        if(win && !win.closed){
            win.focus();
        } else {
            var left = (screen.width / 2) - (w / 2);
            var top = (screen.height / 2) - (h / 2);
            win = window.open(url, title, 'toolbar=no, location=0, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,width='+w+',height='+h+',top='+top+',left='+left+'');

            //check if the window was created
            if(!win){
                alert('Enable pop up windows!!!!');
            }else{
                //function that checks if the pop-up window is open
                timercheck = setInterval('checkwindows()',1000);
                document.getElementById("safinsa-pay").disabled = true;
                return win;
            }
        }
    }

    function checkwindows(){
        //if the pop-up window is closed we clear the interval and enable the button again
        if (win && win.closed) {
            clearInterval(timercheck);
            document.getElementById("safinsa-pay").disabled = false;
        }
    }
</script>
