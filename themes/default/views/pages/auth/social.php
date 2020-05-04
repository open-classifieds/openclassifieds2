<?if (Core::extra_features() == TRUE):?>
    <?if (core::count($providers = Social::enabled_providers()) > 0 OR core::config('social.oauth2_enabled') == TRUE) :?>
        <ul class="list-inline social-providers">
            <?foreach ($providers as $key => $provider) :?>
                <li>
                    <?if(strtolower($key) == 'live'):?>
                        <a class="zocial <?=strtolower($key) == 'live' ? 'windows' : ''?>" href="<?=Route::url('default',array('controller'=>'social','action'=>'login','id'=>strtolower($key)))?>">
                            <?=$key?>
                        </a>
                    <?elseif(strtolower($key) == 'facebook'):?>
                        <a href="<?=Route::url('default',array('controller'=>'social','action'=>'login','id'=>strtolower($key)))?>" style="display: block;">
                            <div class="_xvm _29o8">
                                <div class="_5h0c _5h0f" style="" role="button" tabindex="0">
                                    <table class="uiGrid _51mz _5h0i _88va _5f0n" cellspacing="0" cellpadding="0">
                                        <tbody>
                                            <tr class="_51mx">
                                                <td class="_51m-">
                                                    <div class="_5h0j">
                                                        <span class="_5h0k">
                                                            <img class="img" src="https://static.xx.fbcdn.net/rsrc.php/v3/y4/r/ps3LEjFUMch.png" alt="" width="16" height="16" style="vertical-align: baseline;">
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="_51m- _51mw">
                                                    <div class="_5h0s">
                                                        <div class="_5h0o _8kto"><?= __('Continue with Facebook') ?></div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </a>
                        <style>
                            ._32qa button {
                                opacity: .4
                            }

                            ._59ov {
                                height: 100%;
                                height: 910px;
                                position: relative;
                                top: -10px;
                                width: 100%
                            }

                            ._5ti_ {
                                background-size: cover;
                                height: 100%;
                                width: 100%
                            }

                            ._5tj2 {
                                height: 900px
                            }

                            ._2mm3 ._5a8u .uiBoxGray {
                                background: #fff;
                                margin: 0;
                                padding: 12px
                            }

                            ._2494 {
                                height: 100vh
                            }

                            ._2495 {
                                margin-top: -10px;
                                top: 10px
                            }
                            ._5h0c {
                                background-color: #e9ebee;
                                cursor: pointer;
                                display: inline-block;
                                vertical-align: middle;
                            }

                            ._5h0c._5h0d {
                                border-radius: 4px;
                                max-width: 400px;
                                min-width: 240px
                            }

                            ._29o8 {
                                display: inline-block
                            }

                            ._xvm ._5h0c._5h0d {
                                min-width: initial
                            }

                            ._5h0c._5h0f {
                                border-radius: 3px;
                                max-width: 320px;
                                min-width: 200px
                            }

                            ._xvm ._5h0c._5h0f {
                                min-width: initial
                            }

                            ._5h0c._5h0g {
                                border-radius: 3px;
                                max-width: 200px
                            }

                            ._5h0c._5h0h {
                                border-radius: 2px;
                                margin: 10px 0;
                                max-width: 268px
                            }

                            ._5h0i {
                                background-color: #4267b2;
                                border-radius: 4px;
                                color: #fff
                            }

                            ._5h0i._88va {
                                background-color: #1877f2
                            }

                            ._5h0g ._5h0i {
                                border-radius: 3px;
                                height: 20px;
                                width: auto
                            }

                            ._5h0g ._5h0i td:first-child,
                            ._5h0g ._xvp._5h0i td:last-child {
                                width: 20px
                            }

                            ._5h0f ._5h0i {
                                border-radius: 3px;
                                height: 28px
                            }

                            ._xvm ._5h0c._5h0f ._5h0i {
                                max-width: 240px;
                                min-width: initial;
                                width: auto
                            }

                            ._5h0f ._5h0i td:first-child,
                            ._5h0f ._xvp._5h0i td:last-child {
                                width: 28px
                            }

                            ._xvm ._5h0c._5h0d ._5h0i {
                                max-width: 272px;
                                min-width: initial;
                                width: auto
                            }

                            ._5h0d ._5h0i {
                                height: 40px
                            }

                            ._5h0d ._5h0i td:first-child,
                            ._5h0d ._xvp._5h0i td:last-child,
                            ._41tb ._5h0d ._5h0i td:nth-child(3) {
                                width: 40px
                            }

                            ._5h0h._5h0c ._5h0i {
                                height: 40px;
                                table-layout: auto;
                                width: 268px
                            }

                            ._5h0c ._5h0j {
                                overflow: none;
                                white-space: nowrap
                            }

                            ._5h0c ._5h0k {
                                float: left
                            }

                            ._5h0h ._5h0k {
                                height: 24px;
                                margin-left: 8px
                            }

                            ._5h0d td:last-child ._5h0k {
                                margin-left: 0
                            }

                            ._5h0d ._5h0k {
                                height: 24px;
                                margin: 8px
                            }

                            ._5h0f ._5h0k {
                                height: 16px;
                                margin: 6px
                            }

                            ._5h0g ._5h0k {
                                height: 12px;
                                margin: 4px
                            }

                            ._5h0d ._88va td:last-child ._5h0k {
                                margin-left: 0
                            }

                            ._5h0d ._88va ._5h0k {
                                margin: 8px 8px 8px 12px
                            }

                            ._5h0f ._88va ._5h0k {
                                margin: 6px 6px 6px 8px
                            }

                            ._5h0g ._88va ._5h0k {
                                margin: 4px 4px 4px 8px
                            }

                            ._89dx ._5h0l,
                            ._5h0h ._5h0m,
                            ._5h0d ._5h0l,
                            ._5h0d ._5h0m {
                                height: 24px;
                                width: 24px
                            }

                            ._5h0f ._5h0l,
                            ._5h0f ._5h0m {
                                height: 16px;
                                width: 16px
                            }

                            ._5h0g ._5h0l,
                            ._5h0g ._5h0m {
                                height: 12px;
                                width: 12px
                            }

                            ._5h0o {
                                border: none;
                                font-family: Helvetica, Arial, sans-serif;
                                letter-spacing: .25px;
                                overflow: hidden;
                                text-align: center;
                                text-overflow: clip;
                                white-space: nowrap
                            }

                            ._5h0g ._5h0o {
                                font-size: 11px;
                                max-width: 150px;
                                padding: 0 8px 0 2px
                            }

                            ._5h0g ._8kto {
                                font-weight: bold;
                                padding-top: 2px
                            }

                            ._5h0f ._5h0o {
                                font-size: 13px;
                                margin-right: 8px
                            }

                            ._5h0f ._8kto {
                                font-size: 13px;
                                font-weight: bold;
                                margin-right: 8px
                            }

                            ._xvm ._5h0f ._5h0q ._5h0o {
                                margin: 0 12px 0 6px;
                                max-width: 166px
                            }

                            ._xvm ._5h0f ._8jam ._5h0o {
                                margin-right: 12px;
                                max-width: 166px
                            }

                            ._5h0d ._5h0o {
                                margin-right: 12px
                            }

                            ._5h0d ._8kto {
                                font-weight: bold;
                                padding-top: 1px
                            }

                            ._29o8._41tb ._5h0d td:only-child ._5h0o {
                                margin: auto 16px
                            }

                            ._xvm ._5h0d ._5h0o {
                                margin: 0 24px 0 12px
                            }

                            ._xvm ._5h0d ._88va ._5h0o {
                                margin-left: 0;
                                margin-right: 12px
                            }

                            ._xvm ._5h0d ._5h0q ._5h0o {
                                margin: 0 12px 0 4px;
                                max-width: 176px
                            }

                            ._xvm ._5h0d ._8jam ._5h0o {
                                margin-right: 12px;
                                max-width: 176px
                            }

                            ._5h0h ._5h0o {
                                font-size: 15px;
                                font-weight: bold;
                                letter-spacing: normal;
                                line-height: 16px;
                                padding: 0 22px;
                                white-space: normal
                            }

                            ._5h0h ._5h0o._4lqf {
                                font-size: 12px;
                                line-height: 14px
                            }

                            ._5h0h ._5h0s {
                                max-width: 100%
                            }

                            .no_svg ._5h0m,
                            .svg ._5h0l {
                                display: none
                            }

                            ._5h0t {
                                float: right;
                                vertical-align: top
                            }

                            ._5h0c._5h0g ._5h0t {
                                border-bottom-right-radius: 3px;
                                border-top-right-radius: 3px;
                                height: 20px;
                                width: 20px
                            }

                            ._5h0c._5h0f ._5h0t {
                                border-bottom-right-radius: 3px;
                                border-top-right-radius: 3px;
                                height: 28px;
                                width: 28px
                            }

                            ._xvm ._5h0c._5h0d td:first-child ._5h0t {
                                margin-right: 8px
                            }

                            ._5h0c._5h0d td:first-child ._5h0t {
                                border-bottom-left-radius: 4px;
                                border-bottom-right-radius: 0;
                                border-top-left-radius: 4px;
                                border-top-right-radius: 0
                            }

                            ._5h0c._5h0d ._5h0t {
                                border-bottom-right-radius: 4px;
                                border-top-right-radius: 4px;
                                height: 40px;
                                width: 40px
                            }

                            ._5h0c._5h0h ._5h0t {
                                border-bottom-right-radius: 2px;
                                border-top-right-radius: 2px;
                                height: 40px;
                                width: 40px
                            }

                            ._5h0c._5h0g ._88va ._5h0t {
                                height: 20px;
                                width: 20px
                            }

                            ._5h0c._5h0f ._88va ._5h0t {
                                height: 28px;
                                width: 28px
                            }

                            ._5h0c._5h0d ._88va ._5h0t {
                                height: 40px;
                                width: 40px
                            }

                            ._5h0c._5h0h ._88va ._5h0t {
                                height: 40px;
                                width: 40px
                            }

                            ._29o8 ._2x7x {
                                background-color: #e9ebee;
                                border-bottom-left-radius: 4px;
                                border-bottom-right-radius: 4px;
                                text-align: center
                            }

                            ._29o8 ._2x7y {
                                display: table;
                                margin: auto;
                                padding: 5px 20px
                            }

                            ._29o8 ._2x7x ._37q_ {
                                display: table-cell;
                                vertical-align: middle
                            }

                            ._29o8 ._2x7x ._37r0 {
                                color: #444950;
                                display: table-cell;
                                font-size: 10px;
                                padding-left: 4px;
                                vertical-align: middle
                            }

                            ._8jan {
                                background-color: rgba(9, 30, 66);
                                border-radius: inherit;
                                height: 100%;
                                left: 0;
                                opacity: 0;
                                position: absolute;
                                top: 0;
                                width: 100%
                            }

                            ._8jan:active {
                                opacity: .3
                            }

                            ._51mz {
                                border: 0;
                                border-collapse: collapse;
                                border-spacing: 0
                            }

                            ._5f0n {
                                table-layout: fixed;
                                width: 100%
                            }

                            .uiGrid .vTop {
                                vertical-align: top
                            }

                            .uiGrid .vMid {
                                vertical-align: middle
                            }

                            .uiGrid .vBot {
                                vertical-align: bottom
                            }

                            .uiGrid .hLeft {
                                text-align: left
                            }

                            .uiGrid .hCent {
                                text-align: center
                            }

                            .uiGrid .hRght {
                                text-align: right
                            }

                            ._51mx:first-child>._51m- {
                                padding-top: 0
                            }

                            ._51mx:last-child>._51m- {
                                padding-bottom: 0
                            }

                            ._51mz ._51mw {
                                padding-right: 0
                            }

                            ._51mz ._51m-:first-child {
                                padding-left: 0
                            }

                            ._51mz._4r9u {
                                border-radius: 50%;
                                overflow: hidden
                            }

                            ._4mr9 {
                                -webkit-touch-callout: none;
                                -webkit-user-select: none
                            }
                        </style>
                    <?elseif(strtolower($key) == 'google'):?>
                        <a href="<?=Route::url('default',array('controller'=>'social','action'=>'login','id'=>strtolower($key)))?>" style="height:31px; width:180px; display: inline-block; margin-top: -5px;" class="abcRioButton abcRioButtonLightBlue">
                            <div class="abcRioButtonContentWrapper">
                                <div class="abcRioButtonIcon" style="padding:6px">
                                    <div style="width:18px;height:18px;" class="abcRioButtonSvgImageWithFallback abcRioButtonIconImage abcRioButtonIconImage18">
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 48 48" class="abcRioButtonSvg">
                                            <g>
                                                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
                                                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
                                                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
                                                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
                                                <path fill="none" d="M0 0h48v48H0z"></path>
                                            </g>
                                        </svg>
                                    </div>
                                </div>
                                <span style="font-size:12px;line-height:29px;" class="abcRioButtonContents">
                                    <span id="not_signed_ineid4prr8k1y8"><?= _e('Sign in with Google') ?></span>
                                </span>
                            </div>
                        </a>
                        <style>
                            .abcRioButton {
                                -webkit-border-radius:1px;
                                border-radius:1px;
                                -webkit-box-shadow 0 2px 4px 0px rgba(0,0,0,.25);
                                box-shadow:0 2px 4px 0 rgba(0,0,0,.25);
                                -webkit-box-sizing:border-box;
                                box-sizing:border-box;
                                -webkit-transition:background-color .218s,border-color .218s,box-shadow .218s;
                                transition:background-color .218s,border-color .218s,box-shadow .218s;
                                -webkit-user-select:none;
                                -webkit-appearance:none;
                                background-color:#fff;
                                background-image:none;
                                color:#262626;
                                cursor:pointer;
                                outline:none;
                                overflow:hidden;
                                position:relative;
                                text-align:center;
                                vertical-align:middle;
                                white-space:nowrap;
                                width:auto
                            }
                            .abcRioButton:hover{
                                -webkit-box-shadow:0 0 3px 3px rgba(66,133,244,.3);
                                box-shadow:0 0 3px 3px rgba(66,133,244,.3)
                            }
                            .abcRioButtonLightBlue{
                                background-color:#fff;
                                color:#757575
                            }
                            .abcRioButtonLightBlue:hover{
                                background-color:#fff;
                                color:#757575;
                                text-decoration: none;
                            }
                            .abcRioButtonLightBlue:active{
                                background-color:#eee;
                                color:#6d6d6d
                            }
                            .abcRioButtonIcon{
                                float:left
                            }
                            .abcRioButtonBlue .abcRioButtonIcon{
                                background-color:#fff;
                                -webkit-border-radius:1px;
                                border-radius:1px
                            }
                            .abcRioButtonSvg{
                                display:block
                            }
                            .abcRioButtonContents{
                                font-family:Roboto,arial,sans-serif;
                                font-size:14px;
                                font-weight:500;
                                letter-spacing:.21px;
                                margin-left:6px;
                                margin-right:6px;
                                vertical-align:top
                            }
                            .abcRioButtonContentWrapper{
                                height:100%;
                                width:100%
                            }
                            .abcRioButtonBlue .abcRioButtonContentWrapper{
                                border:1px solid transparent
                            }
                            .abcRioButtonErrorState .abcRioButtonContentWrapper,.abcRioButtonWorkingState .abcRioButtonContentWrapper{
                                display:none
                            }
                        </style>
                    <?else:?>
                        <a class="zocial <?=strtolower($key)?>" href="<?=Route::url('default',array('controller'=>'social','action'=>'login','id'=>strtolower($key)))?>">
                            <?=$key?>
                        </a>
                    <?endif?>
                </li>
            <?endforeach?>
            <?if (core::config('social.oauth2_enabled') == TRUE):?>
                <li>
                    <a class="zocial secondary" href="<?=Route::url('default',array('controller'=>'social','action'=>'oauth','id'=>1))?>">
                        <?=__('OAuth')?>
                    </a>
                </li>
            <?endif?>
        </ul>
    <?endif?>
<?endif?>
