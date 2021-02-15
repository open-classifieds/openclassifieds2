<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Jslocalization extends Controller {

    function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);

        $this->auto_render = FALSE;
        $this->response->headers('Content-type','application/javascript');
        $this->template = View::factory('js');
    }

    public function action_cookieconsent()
    {
        $localization_rules = ' window.addEventListener("load", function(){
                                window.cookieconsent.initialise({
                                "palette": {
                                    "popup": {
                                    "background": "#000"
                                    },
                                    "button": {
                                    "background": "#f1d600"
                                    }
                                },
                                "content": {
                                    "message": "' . addslashes(__('We use cookies to track usage and preferences')) . '",
                                    "dismiss": "' . addslashes(__('Got it!')) . '",
                                    "link": "' . addslashes(__('Learn more')) . '"
                                }
                                })});';
        $this->template->content = $localization_rules;
    }

    public function action_adi()
    {
        $ret = '$(function(){
                    $.adi({
                        theme: "dark",
                        title: "'.addslashes(__('Adblock detected!')).'",
                        content: "'.addslashes(__('We noticed that you may have an Ad Blocker turned on. Please be aware that our site is best experienced with Ad Blockers turned off.')).'"
                    });
                });';
        $this->template->content = $ret;
    }

    public function action_select2()
    {
        $ret = '(function() {
                    if (jQuery && jQuery.fn && jQuery.fn.select2 && jQuery.fn.select2.amd) var e = jQuery.fn.select2.amd;
                    return e.define("select2/i18n/es", [], function() {
                        return {
                            errorLoading: function() {
                                return "'.addslashes(__('The results could not be loaded.')).'"
                            },
                            inputTooLong: function(e) {
                                var t = e.input.length - e.maximum,
                                    n = "'.addslashes(__('Please delete')).' " + t + " '.addslashes(__('character')).'";
                                return t != 1 && (n += "s"), n
                            },
                            inputTooShort: function(e) {
                                var t = e.minimum - e.input.length,
                                    n = "'.addslashes(__('Please enter')).' " + t + " '.addslashes(__('or more characters')).'";
                                return n
                            },
                            loadingMore: function() {
                                return "'.addslashes(__('Loading more result...')).'"
                            },
                            maximumSelected: function(e) {
                                var t = "'.addslashes(__('You can only select')).' " + e.maximum + " '.addslashes(__('item')).'";
                                return e.maximum != 1 && (t += "s"), t
                            },
                            noResults: function() {
                                return "'.addslashes(__('No results found')).'"
                            },
                            searching: function() {
                                return "'.addslashes(__('Searching...')).'"
                            }
                        }
                    }), {
                        define: e.define,
                        require: e.require
                    }
                })();';
        $ret .= 'function getCFSearchLocalization(text)
                {
                    switch (text)
                    {
                        case "from":
                            return "'.addslashes(__('From')).'";
                            break;
                        case "to":
                            return "'.addslashes(__('To')).'";
                            break;
                        case "upload_file_to_google_drive":
                            return "'.addslashes(__('Upload file to Google Drive')).'";
                            break;
                        case "very_weak":
                            return "'.addslashes(__('very weak')).'";
                            break;
                        case "weak":
                            return "'.addslashes(__('weak')).'";
                            break;
                        case "medium":
                            return "'.addslashes(__('medium')).'";
                            break;
                        case "strong":
                            return "'.addslashes(__('strong')).'";
                            break;
                        case "strength":
                            return "'.addslashes(__('Strength')).'";
                            break;
                    }
                }';
        $this->template->content = $ret;
    }

    public function action_validate()
    {
        $localization_rules=array(
                                  'required'        => addslashes(__('This field is required.')),
                                  'remote'          => addslashes(__('Please fix this field.')),
                                  'email'           => addslashes(__('Please enter a valid email address.')),
                                  'url'             => addslashes(__('Please enter a valid URL.')),
                                  'date'            => addslashes(__('Please enter a valid date.')),
                                  'dateISO'         => addslashes(__('Please enter a valid date (ISO).')),
                                  'number'          => addslashes(__('Please enter a valid number.')),
                                  'digits'          => addslashes(__('Please enter only digits.')),
                                  'creditcard'      => addslashes(__('Please enter a valid credit card number.')),
                                  'equalTo'         => addslashes(__('Please enter the same value again.')),
                                  'accept'          => addslashes(__('Please enter a value with a valid extension.')),
                                  'maxlength'       => addslashes(__('Please enter no more than {0} characters.')),
                                  'minlength'       => addslashes(__('Please enter at least {0} characters.')),
                                  'rangelength'     => addslashes(__('Please enter a value between {0} and {1} characters long.')),
                                  'range'           => addslashes(__('Please enter a value between {0} and {1}.')),
                                  'max'             => addslashes(__('Please enter a value less than or equal to {0}.')),
                                  'min'             => addslashes(__('Please enter a value greater than or equal to {0}.')),
                                  'regex'           => addslashes(__('Please enter a valid format.')),
                                  'required_checkbox_group' => addslashes(__('Please select at least one checkbox.')),
        );

        $this->template->content = '(function ($) {$.extend($.validator.messages, '.json_encode($localization_rules). ');}(jQuery));';
    }

    public function action_chosen()
    {
        $localization_rules = 'function getChosenLocalization(text)
                                {
                                    switch (text)
                                    {
                                        case "no_results_text":
                                            return "'.addslashes(__('No results match')).'";
                                            break;
                                        case "placeholder_text_multiple":
                                            return "'.addslashes(__('Select Some Options')).'";
                                            break;
                                        case "placeholder_text_single":
                                            return "'.addslashes(__('Select an Option')).'";
                                            break;
                                    }
                                }';
        $this->template->content = $localization_rules;
    }

    public function action_bstour()
    {
        $bstour_basepath = explode('/', core::config('general.base_url'));
        $bstour_basepath = array_slice($bstour_basepath, 3);
        $bstour_basepath = '/'.implode('/', $bstour_basepath);

        $localization_rules = 'function getTourLocalization(text)
                                {
                                    switch (text)
                                    {
                                        case "step1_title":
                                            return "'.addslashes(__('Hey!')).'";
                                            break;
                                        case "step1_content":
                                            return "'.addslashes(__('You are now viewing your admin panel, where you can control almost everything in your classifieds site.')).'";
                                            break;
                                        case "step2_content":
                                            return "'.addslashes(__('Get started by creating and editing categories and locations for your site here.')).'";
                                            break;
                                        case "step3_content":
                                            return "'.addslashes(__('Put your website on maintenance mode until you want to launch it, manage other general settings and create custom fields through this tab.')).'";
                                            break;
                                        case "step4_content":
                                            return "'.addslashes(__('Customize your website look and feel by choosing one of the many available themes and changing theme options.')).'";
                                            break;
                                        case "step5_content":
                                            return "'.addslashes(__('When there is something you want to know type your question here or check the full list of our <a href=\'https://docs.yclas.com/\'>guides and faqs</a>.')).'";
                                            break;
                                        case "step6_title":
                                            return "'.addslashes(__('Hey!')).'";
                                            break;
                                        case "step6_content":
                                            return "'.addslashes(sprintf(__('You are now viewing the back panel at %s here you can manage your ads, favorites, payments and more.'), core::config('general.site_name'))).'";
                                            break;
                                        case "step7_content":
                                            return "'.addslashes(__('Manage ads you published and edit them through this tab, you can also ask to feature or place your ad to top here.')).'";
                                            break;
                                        case "step8_content":
                                            return "'.addslashes(__('Customize your profile, upload a photo, description and change your password.')).'";
                                            break;
                                        case "step9_content":
                                            return "'.addslashes(__('You can check payments you made and see your favorites list here')).'";
                                            break;
                                        case "step10_content":
                                            return "'.addslashes(sprintf(__('To continue your experience with %s you can get back to the main website by clicking here.'), core::config('general.site_name'))).'";
                                            break;
                                    }
                                }';
        $localization_rules .= 'function getTourBasePath()
                                {
                                    return "'.$bstour_basepath.'";
                                }
                              ';
        $this->template->content = $localization_rules;
    }

    public function action_faq()
    {
        $localization_rules = 'function getFAQLocalization(text)
                                {
                                    switch (text)
                                    {
                                        case "Search by Keyword":
                                            return "'.addslashes(__('Search by Keyword')).'";
                                            break;
                                    }
                                }';
        $this->template->content = $localization_rules;
    }

    public function action_dropzone()
    {
        $this->template->content = '
            Dropzone.prototype.defaultOptions.dictDefaultMessage = "'.addslashes(__('Drop images here to upload')).'";
            Dropzone.prototype.defaultOptions.dictFallbackMessage = "'.addslashes(__('Your browser does not support drag and drop file uploads.')).'";
            Dropzone.prototype.defaultOptions.dictFallbackText = "'.addslashes(__('Please use the fallback form below to upload your files like in the olden days.')).'";
            Dropzone.prototype.defaultOptions.dictFileTooBig = "'.addslashes(__('File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.')).'";
            Dropzone.prototype.defaultOptions.dictInvalidFileType = "'.addslashes(__('You can not upload files of this type.')).'";
            Dropzone.prototype.defaultOptions.dictResponseError = "'.addslashes(__('Server responded with {{statusCode}} code.')).'";
            Dropzone.prototype.defaultOptions.dictCancelUpload = "'.addslashes(__('Cancel upload')).'";
            Dropzone.prototype.defaultOptions.dictCancelUploadConfirmation = "'.addslashes(__('Are you sure you want to cancel this upload?')).'";
            Dropzone.prototype.defaultOptions.dictRemoveFile = "'.addslashes(__('Remove file')).'";
            Dropzone.prototype.defaultOptions.dictMaxFilesExceeded = "'.addslashes(__('You can not upload any more files.')).'";
        ';
    }

    public function action_infinitescroll()
    {
        $localization_rules= [
            'finishedMsg' => '<em>' . addslashes(__('No more advertisements')) . '</em>',
            'msgText'     => '<em>' . addslashes(__('Loading the next set of advertisements...')) . '</em>',
        ];

        $this->template->content = '(function ($) {$.extend($.infinitescroll.defaults.loading, '.json_encode($localization_rules). ');}(jQuery));';
    }

}// End Jslocalization Controller
