<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="row">
    <div class="col-lg-12 page-title-container">
        <h1 class="page-header page-title"><?=__('Interactive Map')?></h1>
        <span class="page-description"><?=__('Generate an interactive map for your classifieds website')?> <a target="_blank" href="https://docs.yclas.com/how-to-add-interactive-map/"><?=__('Read more')?></a></span>
    </div>
</div>


<?if (Theme::get('premium')!=1):?>
    <p class="well">
      <span class="label label-info"><?=__('Heads Up!')?></span> 
        <a class="btn btn-success pull-right ajax-load" title="<?=__('Browse Themes')?>" href="<?=Route::url('oc-panel',array('controller'=>'theme'))?>"><?=__('Browse Themes')?></a>
        <?=__('Interactive map is only available with premium themes!').' '.__('Upgrade your Open Classifieds site to activate this feature.')?>
    </p>
<?endif?>
<div class="row">
  <div class="col-md-12 col-lg-8">
    <form id="addimap" name="addimap" action="<?=URL::base()?><?=Request::current()->uri()?>" method="post">
      <div class="panel panel-default">
        <div class="panel-heading"><h4><?=__('General Configuration')?></h4></div>
        <div class="panel-body">
          <div class="form-horizontal" id="default-settings">
            <div class="form-group">
              <?= FORM::label('map_active', __('Map on homepage'), array('class'=>'control-label col-sm-4', 'for'=>'map_active'))?>
              <div class="col-sm-8">
                <div class="onoffswitch">
                  <?= FORM::hidden('map_active', 0);?>
                  <?= FORM::checkbox('map_active', 1, (bool) $map_active, array(
                              'placeholder' => "", 
                              'class' => 'onoffswitch-checkbox', 
                              'id' => 'map_active', 
                              'data-original-title'=> __("Map on homepage"),
                              'data-trigger'=>"hover",
                              'data-placement'=>"right",
                              'data-toggle'=>"popover",
                              'data-content'=>__("Disables generated map on homepage"),
                              ))?>
                  <?= FORM::label('map_active', "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>'map_active'))?>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-4" for="bg_color">
                <?=__('Background Color')?>
              </label>
              <div class="col-sm-8">
                <input type="text" name="bg_color" class="form-control color {hash:true, adjust:false}" value="#FFFFFF" onchange="drawVisualization();">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-4" for="border_color">
                <?=__('Map Border Color')?>
              </label>
              <div class="col-sm-8">
                <input type="text" name="border_color" class="form-control color {hash:true, adjust:false}" value="#FFFFFF" onchange="drawVisualization();">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-4" for="border_stroke">
                <?=__('Map Border Width (px)')?>
              </label>
              <div class="col-sm-8">
                <input type="text" name="border_stroke" class="form-control" value="0" onchange="drawVisualization();">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-4" for="ina_color">
                <?=__('Inactive Region Color')?>
              </label>
              <div class="col-sm-8">
                <input type="text" name="ina_color" class="form-control color {hash:true, adjust:false}" value="#f5f5f5" onchange="drawVisualization();">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-4" for="tooltip_color">
                <?=__('Tooltip Text Color')?>
              </label>
              <div class="col-sm-8">
                <input type="text" name="tooltip_color" class="form-control color {hash:true, adjust:false}" value="#444444" onchange="drawVisualization();">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-4" for="width">
                <?=__('Width (px)')?>
              </label>
              <div class="col-sm-8">
                <input type="text" name="width" class="form-control" value="0" onchange="drawVisualization();">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-4" for="height">
                <?=__('Height (px)')?>
              </label>
              <div class="col-sm-8">
                <input type="text" name="height" class="form-control" value="400" onchange="drawVisualization();">
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-8 col-sm-offset-4">
                <div class="checkbox">
                  <label>
                    <input name="aspect_ratio" type="checkbox" id="aspratio" onChange="drawVisualization();" value="1" checked>
                    <?=__('Keep Aspect Ratio')?>
                  </label>
                </div>
              </div>
            </div>
            <input type="hidden" name="marker_size" value="10">
          </div>
        </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-heading"><h4><?=__('Map Settings')?></h4></div>
        <div class="panel-body">
          <div id="map-settings" class="table-responsive">
            <table class="totalp">
              <tr>
                <td><strong>
                  <?=__('Region to Display')?>
                  </strong><br /></td>
                <td><strong>
                  <?=__('Display Mode')?>
                  </strong><br /></td>
                <td><strong>
                  <?=__('Interactivity')?>
                  </strong><br /></td>
                <td><strong>
                  <?=__('Active Region Action')?>
                  </strong></td>
              </tr>
              <tr>
                <td><select name="region" id="region" onChange="isolinkcheck()">
                    <option value="world,countries">World</option>
                    <option value="world,continents">World - Continent Regions</option>
                    <option value="world,subcontinents">World - Subcontinents Regions</option>
                    <option value="002,countries">Africa</option>
                    <option value="002,subcontinents">Africa - Subcontinents Regions</option>
                    <option value="015,countries">Africa - Northern Africa</option>
                    <option value="011,countries">Africa - Western Africa</option>
                    <option value="017,countries">Africa - Middle Africa</option>
                    <option value="014,countries">Africa - Eastern Africa</option>
                    <option value="018,countries  ">Africa - Southern Africa</option>
                  <option valu  e="150,countries">Europe</option>
                  <option value="150,subcontinents">Europe -   Subcontinents Regions</option>
                  <option value="154,countries">Europ  e - Northern Europe</option>
                  <option value="155,countries">E  urope - Western Europe</option>
                  <option value="151,countries">Euro  pe - Eastern Europe</option>
                  <option value="039,countries">Europ  e - Southern Europe</option>
                  <option value="  019,countries">Americas</option>
                  <option value="019,subcontinents">Ameri  cas - Subcontinents Regions</option>
                  <option value="021,cou  ntries">Americas - Northern America</option>
                  <option value=  "029,countries">Americas - Caribbean</option>
                  <option value=  "013,countries">Americas - Central America</option>
                  <option value  ="005,countries">Americas - South America</option>
                  <option   value="142,countries">Asia</option>
                  <option val  ue="142,subcontinents">Asia - Subcontinents Regions</option>
                  <option value=  "143,countries">Asia - Central Asia</option>
                  <option value="030,countries">  Asia - Eastern Asia</option>
                  <option value="034,countries">  Asia - Southern Asia</option>
                  <option value="035,countries">  Asia - South-Eastern Asia</option>
                  <option value="145,count  ries">Asia - Western Asia</optio  n>
                <option value="009,countries  ">Oceania</option>
                  <option value="009,subcontinents">Oceania - Subcontin  ents Regions</option>
                  <option value="053,countries">Oceania - Australia and New Z  ealand</option>
                  <option value="054,countries">Oceania - Melanesia</option  >
                <option v  alue="057,countries">Oceania - Micronesia</option>  
                <option value  ="061,countries">Oceania - Polynesia</option>
                  <option value="US,countries">U  nited States of America</option>
                    <option value="US,provinces">United States of America - States</option>
                    <option value="US,metros">United States of America - Metropolitan Areas  </option>
                  <option value="US-AL,metros">USA - Alabama - Metropolitan Areas<  /option>
                  <option value="US-AL,provinces">USA - Alabama State</  option>
                  <option value="US-AK,metros">USA - Alaska - Metropolitan Areas</opti  on>
                  <option value="US-AK,provinces">USA - Alaska State</option>
                    <option value="US-AZ,metros">USA - Arizona - Metropolitan Areas</option>
                    <option value="US-AZ,provinces">USA - Arizona State</option>
                    <option value="US-AR,metros">USA - Ark  ansas - Metropolitan Areas</option>
                  <option value="US-AR,provinces">US  A - Arkansas State</option>
                  <option value="US-CA,metros">U  SA - California - Metropolitan Areas</option>
                  <option value="US-CA,provinc  es">USA - California State</option>
                  <option value="US-CO,metro  s">USA - Colorado - Metropolitan Areas</option>
                <o  ption value="US-CO,pro  vinces">USA - Colorado State</option>
                <option   value="US-CT  ,metros">USA - Connecticut - Metropolitan Areas</option>
                <opt  ion value="US-CT,  provinces">USA - Connecticut State</option>
                    <option value="US-DE,metros">USA - Delaware - Metropolitan Areas</option>
                    <option value="US-DE,provinces">USA - Delaware State</option>
                    <option value="US-DC,metros">USA - District of Columbia - Metropolitan Areas</option  >
                  <option value="US-DC,provinces">USA - District of Columbia  </option>
                  <option value="US-FL,metros">USA - Florida - Metropolitan Are  as</option>
                  <option value="US-FL,provinces">USA - Florida S  tate</option>
                  <option value="US-GA,metros">USA - Georgia - Metropolitan Ar  eas</option>
                  <option value="US-GA,provinces">USA - Georgia Sta  te</option>
                  <option value="US-HI,metros">USA - Hawaii - Metropolitan Area  s</option>
                  <option value="US-HI,provinces">USA - Hawaii State  </option>
                  <option value="US-ID,metros">USA - Idaho - Metropolitan Area  s</option>
                  <option value="US-ID,provinces">USA - Idaho Sta  te</option>
                  <option value="US-IL,metros">USA - Illinois - Metropolitan A  reas</option>
                  <option value="US-IL,provinces">USA - Illinois   State</option>
                  <option value="US-IN,metros">USA - Indiana - Metropolitan   Areas</option>
                  <option value="US-IN,provinces">USA - Indiana S  tate</option>
                  <option value="US-IA,metros">USA - Iowa - Metropolitan Areas<  /option>
                  <option value="US-IA,provinces">USA - Iowa State</opti  on>
                  <option value="US-KS,metros">USA - Kansas - Metropolitan Areas</opt  ion>
                  <option value="US-KS,provinces">USA - Kansas State</op  tion>
                  <option value="US-KY,metros">USA - Kentucky - Metropolitan Areas</op  tion>
                  <option value="US-KY,provinces">USA - Kentucky State</op  tion>
                  <option value="US-LA,metros">USA - Louisiana - Metropolitan Areas</option  >
                  <option value="US-LA,provinces">USA - Louisiana State</option>
                    <option value="US-ME,metros">USA - Maine - Metropolitan Areas</option>
                    <option value="US-ME,provinces">USA - Maine State</option>
                    <option value="US-MD,metros">USA - Maryland - Metropolitan Areas</option>
                    <option value="US-MD,provinces">USA - Maryland State</option>
                    <option value="US-MA,metros">USA - Massachusetts - Metropolitan Areas</option>
                    <option value="US-MA,provinces">USA - Massachusetts State</option>
                    <option value="US-MI,metros">USA - Michigan - Metropolitan Areas</option>
                    <option value="US-MI,provinces">USA - Michigan State</option>
                    <option value="US-MN,metros">USA - Minnesota - Metropolitan Areas</option>
                    <option value="US-MN,provinces">USA - Minnesota State</option>
                    <option value="US-MS,metros">USA - Mississippi - Metropolitan Areas  </option>
                  <option value="US-MS,provinces">USA - Mississippi S  tate</option>
                  <option value="US-MO,metros">USA - Missouri - Metropolitan A  reas</option>
                  <option value="US-MO,provinces">USA - Missouri S  tate</option>
                  <option value="US-MT,metros">USA - Montana - Metropolitan   Areas</option>
                  <option value="US-MT,provinces">USA - Montana   State</option>
                  <option value="US-NE,metros">USA - Nebraska - Metropolitan Area  s</option>
                  <option value="US-NE,provinces">USA - Nebraska State</op  tion>
                  <option value="US-NV,metros">USA - Nevada - Metropolitan Areas</option  >
                  <option value="US-NV,provinces">USA - Nevada State</option>
                    <option value="US-NH,metros">USA - New Hampshire - Metropolitan Areas</option>  
                  <option value="US-NH,provinces">USA - New Hampshire State</opti  on>
                  <option value="US-NJ,metros">USA - New Jersey - Metropolitan Areas</op  tion>
                  <option value="US-NJ,provinces">USA - New Jersey State</  option>
                  <option value="US-NM,metros">USA - New Mexico - Metropolitan Areas</opti  on>
                  <option value="US-NM,provinces">USA - New Mexico State</option>
                    <option val  ue="US-NY,metros">USA - New York - Metropolitan Areas</option>
                    <option value="US-NY,provinces">USA - New York State</option  >
                  <option value="US-NC,metros">USA - North Carolina - Metr  opolitan Areas</option  >
                <option value="US-NC,p  rovinces">USA - North Carolina Sta  te</option>
                <option valu  e="US-ND,metros">USA - North Dakota - Metropolitan   Areas</optio  n>
                <option value="US-ND,provinces">USA - North Da  kota State</  option>
                <option value="US-OH,metros">USA - Ohio -   Metropolit  an Areas</option>
                <option value="US-OH,provinces  ">USA - Ohio   State</option>
                <option value="US-  OK,metros">USA - Oklahoma - Metropolitan Are  as</option>
                  <option value="US-OK,provinces">USA - Oklahoma State</  option>
                  <option value="US-OR,metros">USA - Oregon - Metropolitan Areas</option  >
                  <option value="US-OR,provinces">USA - Oregon State</option>
                    <option value="US-PA,metros">USA - Pennsylvania - Metropolitan Areas</option>
                    <option value="US-PA,provinces"  >USA - Pennsylvania State</option>
                  <option value="US-RI,m  etros">USA - Rhode Island - Metropolitan Areas</option>
                    <option value="US-RI,provinces">USA - Rhode Island State</opti  on>
                  <option value="US-SC,metros">USA - South   Carolina - Metropolitan Areas</option>
                  <option value="US-  SC,provinces">USA - South Carolina State</option  >
                  <option value="US-SD,metros">USA - South Dakota - Metropolitan Areas<    /option>
                <option value="US-SD,provinces">USA - South Dakota   State</opti  on>
                <option value="US-TN,metros">USA - Tennessee - Metropo  litan Areas</option>
                  <option value="US-TN,provinces">USA -   Tennessee State</option>
                  <option value="US-TX,metros">USA - Texas - Metr  opolitan Areas</option>
                  <option value="US-TX,provinces">USA -   Texas State</option>
                  <option value="US-UT,metros">USA - Utah - Metropolit  an Areas</option>
                  <option value="US-UT,provinces">USA - Utah S  tate</option>
                  <option value="US-VT,metros">USA - Vermont - Metropolitan Area  s</option>
                  <option value="US-VT,provinces">USA - Vermont State</  option>
                  <option value="US-VA,metros">USA - Virginia - Metropolitan Areas</optio  n>
                  <option value="US-VA,provinces">USA - Virginia State</option>
                    <option value="US-WA,metros">USA - Washington - Metropolitan Areas</option>
                    <option value="US-WA,provinces">USA - Washington State</option>
                    <option value="US-WV,metros">USA - West Virginia - Metropolitan Areas</op  tion>
                  <option value="US-WV,provinces">USA - West Virginia Sta  te</option>
                  <option value="US-WI,metros">USA - Wis  consin - Metropolitan Areas</option>
                  <option value="US-WI,prov  inces">USA - Wisconsin State</option>
                  <option value=  "US-WY,metros">USA - Wyoming - Metropolitan Areas</option>
                  <opti  on value="US-WY,provinces">USA - Wyoming State</option>
                    <option value="AF,countries">Afghanistan</option>
                    <option value="AF,provinces">Afghanistan - Provinces</opti  on>
                  <option value="AX,countries">Aland Islands</option>
                    <option value="AX,provinces">Aland Islands - Provinces<  /option>
                  <option value="AL,countries">Albania</option>
                    <option value="AL,provinces">Albania - Provinces</option  >
                  <option value="DZ,countries">Algeria</option>
                    <option value="DZ,provinces">Algeria - Provinces</optio  n>
                  <option value="AS,countries">American Samoa</option>
                    <option value="AS,provinces">American Samoa - Pr  ovinces</option>
                  <option value="AD,countries">Andorra</opti  on>
                  <option value="AD,provinces">Andorra - Provin  ces</option>
                  <option value="AO,countries">Angola</option>
                    <option value="AO,provinces">Angola - Provinces</option>
                    <option value="AI,countries">Anguilla</option>
                  <option value  ="AI,provinces">Anguilla - Provinces</option>
                  <o  ption value="AQ,countries">Antarctica</option>
                  <option value  ="AQ,provinces">Antarctica - Provinces</option>
                    <option value="AG,countries">Antigua and Barbuda</option>
                    <option value="AG,provinces">Antigua and Barbuda - Provinces</option>
                    <option value="AR,countries">Argentina</option>
                    <option value="AR,provinces">Argentina - Provinces</option>
                    <option value="AM,countries">Armenia</option>
                    <option value="AM,provinces">Armenia - Provinces</option>
                      <option value="AW,countries">Aruba</option>
                  <option va  lue="AW,provinces">Aruba - Provinces</option>
                    <option value="AU,countries">Australia</option>
                  <option va  lue="AU,provinces">Australia - Provinces</option>
                    <option value="AT,countries">Austria</option>
                  <option value=  "AT,provinces">Austria - Provinces</option>
                  <o  ption value="AZ,countries">Azerbaijan</option>
                  <option val  ue="AZ,provinces">Azerbaijan - Provinces</option>
                    <option value="BS,countries">Bahamas</option>
                  <option   value="BS,provinces">Bahamas - Provinces</option>
                    <option value="BH,countries">Bahrain</option>
                  <option value=  "BH,provinces">Bahrain - Provinces</option>
                  <op  tion value="BD,countries">Bangladesh</option>
                  <option value  ="BD,provinces">Bangladesh - Provinces</option>
                    <option value="BB,countries">Barbados</option>
                  <option v  alue="BB,provinces">Barbados - Provinces</option>
                    <option value="BY,countries">Belarus</option>
                  <option   value="BY,provinces">Belarus - Province  s</option>
                  <option value="BE,countries">Belgium</option  >
                <optio  n value="BE,provinces">Belgium - Provi  nces</option>
                  <option value="BZ,countries">Belize</option>  
                <op  tion value="BZ,provinces">Belize - Provin  ces</option>
                  <option value="BJ,countries">Benin</option>
                  <optio  n value="BJ,provinces">Benin - Provinces</optio  n>
                  <option value="BM,countries">Bermuda</option>
                <o  ptio  n value="BM,provinces">Bermuda - Provinces</option>
                  <option value="BT,  countries">Bhutan</option>
                <op  tion value="BT,provinces">Bhutan - Provinces</option  >
                  <option value="BO,countries">Bolivia, Plurinational State of</option  >  
                <option value="BO,provinces">Bolivia, Plurina  tional State of - Provinces</option>
                    <option value="BQ,countries">Bonaire, Sint Eustatius and Saba  </option>
                  <option value="BQ,province  s">Bonaire, Sint Eustatius and Saba -   Provinces</option>
                <o  ption value="BA,countries  ">Bosnia and Herzegovina</option>
                  <option value="BA,provinc  es">Bosnia and Herzegovina - Provinces</option>
                  <opt  ion value="BW,countries">Botswana</option>
                  <option value="BW,pro  vinces">Botswana - Provinces</option>
                  <option   value="BV,countries">Bouvet Island</option>
                  <option valu  e="BV,provinces">Bouvet Island - Provinces</op  tion>
                <option value="BR  ,countries">Brazil</option>
                  <option value="BR,provinces">Brazil - Provinces</opti  on>
                  <option value="IO,countries">British Indian Ocean Te  rritory</o  ption>
                <option value="IO,provinces">British Indian Ocean T  erritory -   Provinces</option>
                  <option value="BN  ,countries">Brunei Darussalam</option>
                  <option value="BN,pr  ovinces">Brunei Darussalam - Provinces</option>
                  <op  tion value="BG,countries">Bulgaria</option>
                  <option value="BG,p  rovinces">Bulgaria - Provinces</option>
                  <optio  n value="BF,countries">Burkina Faso</option>
                  <option value  ="BF,provinces">Burkina Faso - Provinces</option>
                    <option value="BI,countries">Burundi</option>
                  <option va  lue="BI,provinces">Burundi - Provinces</option>
                    <option value="KH,countries">Cambodia</option>
                  <option val  ue="KH,provinces">Cambodia - Provinces</option>
                    <option value="CM,countries">Cameroon</option>
                  <option   value="CM,provinces">Cameroon - Provinces</option>
                    <option value="CA,countries">Canada</option>
                  <option value  ="CA,provinces">Canada - Provinces</option>
                  <option v  alue="CV,countries">Cape Verde</option>
                  <option value="CV,provinc  es">Cape Verde - Provinces</option>
                  <option value="KY,countries  ">Cayman Islands</option>
                  <option value="KY,provinces">Cayman Islands - Pro  vinces</option>
                  <option value="CF,countries  ">Central African Republic</option>
                  <option value="CF,p  rovinces">Central African Republic - Provinces</option>
                    <option value="TD,countries">Chad</option>
                  <  option value="TD,provinces">Chad - Provinces</option>
                    <option value="CL,countries">Chile</option>
                  <o  ption value="CL,provinces">Chile - Provinces</option>
                  <  option value="CN,countries">China</option>
                  <option value="CN,provin  ces">China - Provinces</option>
                  <option value="CX,countries">C  hristmas Island</option>
                  <option value="CX,provinces">Christmas Island - P  rovinces</option>
                  <option value="CC,countries">  Cocos (Keeling) Islands</option>
                  <option value="CC,province  s">Cocos (Keeling) Islands - Provinces</option>
                    <option value="CO,countries">Colombia</option>
                  <option v  alue="CO,provinces">Colombia - Provinces</option>
                    <option value="KM,countries">Comoros</option>
                  <opt  ion value="KM,provinces">Comoros - Provinces</option>
                  <option value="CG,coun  tries">Congo</option>
                  <option value="CG,provinces">Congo - Provinces</option>
                    <option value="CD,countries">Congo, the Democratic Republic o  f the</option>
                  <option value="CD,provinces">Congo, the Democrat  ic Republic of the - Provinces</option>
                  <option v  alue="CK,countries">Cook Islands</option>
                  <option value="CK,p  rovinces">Cook Islands - Provinces</option>
                  <option v  alue="CR,countries">Costa Rica</option>
                  <option value="CR,provinc  es">Costa Rica - Provinces</option>
                    <option value="CI,countries">Cote d'Ivoire </option>
                    <option value="CI,provinces">Cote d'Ivoire  - Provinces</option>
                    <option value="HR,countries">Croatia</option>
                    <option value="HR,provinces">Croatia - Provinces</op  tion>
                  <option value="CU,countries">Cuba</op  tion>
                  <option value="CU,provinces">Cuba - Provinces</op  tion>
                  <option value="CW,countries">Curaçao</op  tion>
                  <option value="CW,provinces">Curaçao - Provinces</op  tion>
                  <option value="CY,countries">Cyprus</op  tion>
                  <option value="CY,provinces">Cyprus - Provinces</op  tion>
                  <option value="CZ,countries">Czech Republic</op  tion>
                  <option value="CZ,provinces">Czech Republic - Provinces</op  tion>
                  <option value="DK,countries">Denmark</op  tion>
                  <option value="DK,provinces">Denmark - Provinces</op  tion>
                  <option value="DJ,countries">Djibouti</op  tion>
                  <option value="DJ,provinces">Djibouti - Provinces</op  tion>
                  <option value="DM,countries">Dominica</op  tion>
                  <option value="DM,provinces">Dominica - Provinces</op  tion>
                  <o  ption value="DO,countries">Dominican Republic</op  tion>
                  <option value="DO,provinces">Dominican Republic - Provinces</op  tion  >
                <option value="EC,countries">Ecuador</op  tion  >
                <option value="EC,provinces">Ecuador - Provinces</op  tion  >
                <option value="EG,countries">Egypt</op  tion>
                  <option value="EG,provinces">Egypt - Provinces</op  tion>
                  <option value="SV,countries">El Salvador</op  ti  on>
                <option value="SV,provinces">El Salvador - Provinces</  op  tion>
                <option value="GQ,countries">Equatorial   Guinea</op  tion>
                <option value="GQ,provinces">Equatorial Guin  ea - Provinces</op  tion>
                <option value="ER,countries">Eritrea</op    tion>
                <option value="ER,provinces">Eritrea - Provinces</op  tion>
                  <option value="EE,countries">Estonia</op  tion>
                <o  ption value="EE,provinces">Estonia - Provinces</op  tion>
                <option value="ET,co  untries">Ethiopia</op  tion>
                <option value="E  T,provinces">Ethiopia - Provinces</op  tion>
                <option valu  e="FK,countries">Falkland Islands (Malvinas)</op  tion>
                  <option value="FK,provinces">Falkland Islands (Malvinas) - Provinces</  op  tion>
                <option value="FO,countries">Faroe Islands  </op  tion>
                <option value="FO,provinces">Faroe Islands - Provinc  es</op  tion>
                <option value="FJ,countries">Fiji  </op  tion>
                <option value="FJ,provinces">Fiji - Provinces</  op  tion>
                <option value="FI,countries">Finland  </op  tion>
                <option value="FI,provinces">Finland - Provinc  es</op  tion>
                <option value="FR,countries">Fra  nce</op  tion>
                <option value="FR,provinces">France - Provinc  es</op  tion>
                  <option value="GF,countries">French Guiana</op  tion>
                  <option value="GF,provinces">French Guiana - Provinces</op  tion>
                  <option value="PF,countries">French Polynesia</op  tion>
                  <option value="PF,provinces">French Polynesia - Provinces</op  tion>
                  <option value="TF,countries">French Southern Territories</op  tion>
                  <option value="TF,provinces">French Southern Territories - Provinces</op  tion>
                  <option value="GA,countries">Gabon</op  tion>
                  <option value="GA,provinces">Gabon - Provinces</op  tion>
                  <option value="GM,countries">Gambia</op  tion>
                  <option value="GM,provinces">Gambia - Provinces</op  tion>
                  <option value="GE,countries">Georgia</op  tion>
                  <option value="GE,provinces">Georgia - Provinces</op  tion>
                  <option value="DE,countries">Germany</op  tion>
                  <option value="DE,provinces">Germany - Provinces</op  tion>
                  <option value="GH,countries">Ghana</op  tion>
                  <option value="GH,provinces">Ghana - Provinces</op  tion>
                  <option value="GI,countries">Gibraltar</op  tion>
                  <option value="GI,provinces">Gibraltar - Provinces</op  tion>
                  <option value="GR,countries">Greece</op  tion>
                  <option value="GR,provinces">Greece - Provinces</op  tion>
                  <option value="GL,countries">Greenland</op  tion>
                  <option value="GL,provinces">Greenland - Provinces</op  tion>
                  <option value="GD,countries">Grenada</op  tion>
                  <option value="GD,provinces">Grenada - Provinces</op  tion>
                  <option value="GP,countries">Guadeloupe</op  tion>
                  <option value="GP,provinces">Guadeloupe - Provinces</op  tion>
                  <option value="GU,countries">Guam</op  tion>
                  <option value="GU,provinces">Guam - Provinces</op  tion>
                  <option value="GT,countries">Guatemala</op  tion>
                  <option value="GT,provinces">Guatemala - Provinces</op  tion>
                  <option value="GG,countries">Guernsey</op  tion>
                  <option value="GG,provinces">Guernsey - Provinces</op  tion>
                  <option value="GN,countries">Guinea</op  tion>
                  <option value="GN,provinces">Guinea - Provinces</op  tion>
                  <option value="GW,countries">Guinea-Bissau</op  tion>
                  <option value="GW,provinces">Guinea-Bissau - Provinces</op  tion>
                  <option value="GY,countries">Guyana</op  tion>
                  <option value="GY,provinces">Guyana - Provinces</op  tion>
                  <option value="HT,countries">Haiti</op  tion>
                  <option value="HT,provinces">Haiti - Provinces</op  tion>
                  <option value="HM,countries">Heard Island and McDonald Islands</op  tion>
                  <option value="HM,provinces">Heard Island and McDonald Islands - Provinces</op  tion>
                  <option value="VA,countries">Holy See (Vatican City State)</op  tion>
                  <option value="HN,countries">Honduras</op  tion>
                  <option value="HN,provinces">Honduras - Provinces</op  tion>
                  <option value="HK,countries">Hong Kong</op  tion>
                  <option value="HK,provinces">Hong Kong - Provinces</op  tion>
                  <option value="HU,countries">Hungary</op  tion>
                    <option value="HU,provinces">Hungary - Provinces</option>
                    <option value="IS,countries">Iceland</option>
                    <option value="IS,provinces">Iceland - Provinces</option>
                    <option value="IN,countries">India</option>
                    <option value="IN,provinces">India - Provinces</option>
                    <option value="ID,countries">Indonesia</option>
                    <option value="ID,provinces">Indonesia - Provinces</option>
                    <option value="IR,countries">Iran, Islamic Republic of</option>
                    <option value="IR,provinces">Iran, Islamic Republic of - Provinces</option>
                    <option value="IQ,countries">Iraq</option>
                    <option value="IQ,provinces">Iraq - Provinces</option>
                    <option value="IE,countries">Ireland</option>
                    <option value="IE,provinces">Ireland - Provinces</op  tion>
                  <option value="IM,countries">Isle of Man</  option>
                  <option value="IM,provinces">Isle of Man - Provinces  </option>
                  <option value="IL,countries">Israel<  /option>
                  <option value="IL,provinces">Israel - Provinces</  option>
                  <option value="IT,countries">Italy</o  ption>
                  <option value="IT,provinces">Italy - Provinces</op  tion>
                  <option value="JM,countries">Jamaica</option>
                  <o  ption value="JM,provinces">Jamaica - Provinces</option>
                  <option value="JP,countrie  s">Japan</option>
                  <option value="JP,pr  ovinces">Japan - Prov  inces</option>
                <option value="JE,countries">Jersey</option>
                    <option value="JE,provinces">Jersey - Provinces</option>  
                  <option value="JO,countries">Jordan</option>
                  <option val  ue="JO,provinces">Jordan - Provinces</option>
                    <option value="KZ,countries">Kazakhstan</option>
                  <option   value="KZ,provinces">Kazakhstan - Provinces</option>  
                  <option value="KE,countries">Kenya</option>
                  <option   value="KE,provinces">Kenya - Provinces</option>
                  <  option value="KI,countries">Kiribati</option>
                  <option value="  KI,provinces">Kiribati - Provinces</option>
                  <opti  on value="KP,countries">Korea, Democratic People's Republic of</option>
                    <option value="KP,provinces">Korea, Democratic People's   Republic of - Provinces</option>
                  <option value="KR,countr  ies">Korea, Republic of</option>
                  <option value="K  R,provinces">Korea, Republic of - Provinces</option>
                  <option   value="KW,countries">Kuwait</option>
                  <option   value="KW,provinces">Kuwait - Provinces</option>
                    <option value="KG,countries">Kyrgyzstan</option>
                    <option value="KG,provinces">Kyrgyzstan - Provinces</option>
                    <option value="LA,countries">Lao People's Democratic Republic</option>
                    <option value="LA,provinces">Lao People's Democratic Republic - Provinces</option>
                    <option value="LV,countries">Latvia</option>
                    <option value="LV,provinces">Latvia - Provinces</option>
                    <option value="LB,countries">Lebanon</option>
                    <option value="LB,provinces">Lebanon - Provinces</option>
                    <option value="LS,countries">Lesotho</option>
                    <option value="LS,provinces">Lesotho - Provinces</option>
                    <option value="LR,countries">Liberia</option>
                    <option value="LR,provinces">Liberia - Provinces</option>
                    <option value="LY,countries">Libya</option>
                    <option value="LY,provinces">Libya - Provinces</option>
                    <option value="LI,countries">Liechtenstein</option>
                    <option value="LI,provinces">Liechtenstein - Provinces</option>
                    <option value="LT,countries">Lithuania</option>
                    <option value="LT,provinces">Lithuania - Provinces</option>
                    <option value="LU,countries">Luxembourg</option>
                    <option value="LU,provinces">Luxembourg - Provinces</option>
                    <option value="MO,countries">Macao</option>
                    <option value="MO,provinces">Macao - Provinces</option>
                    <option value="MK,countries">Macedonia, the former Yugoslav Republic of</option>
                    <option value="MK,provinces">Macedonia, the former Yugoslav Republic of - Provinces</option>
                    <option value="MG,countries">Madagascar</option>
                    <option value="MG,provinces">Madagascar - Provinces</option>
                    <option value="MW,countries">Malawi</option>
                    <option value="MW,provinces">Malawi - Provinces</option>
                    <option value="MY,countries">Malaysia</option>
                    <option value="MY,provinces">Malaysia - Provinces</option>
                    <option value="MV,countries">Maldives</option>
                    <option value="MV,provinces">Maldives - Provinces</option>
                    <option value="ML,countries">Mali</option>
                    <option value="ML,provinces">Mali - Provinces</option>
                    <option value="MT,countries">Malta</option>
                    <option value="MT,provinces">Malta - Provinces</option>
                    <option value="MH,countries">Marshall Islands</option>
                    <option value="MH,provinces">Marshall Islands - Provinces</option>
                    <option value="MQ,countries">Martinique</option>
                    <option value="MQ,provinces">Martinique - Provinces</option>
                    <option value="MR,countries">Mauritania</option>
                    <option value="MR,provinces">Mauritania - Provinces</option>
                    <option value="MU,countries">Mauritius</option>
                    <option value="MU,provinces">Mauritius - Provinces</option>
                    <option value="YT,countries">Mayotte</option>
                    <option value="YT,provinces">Mayotte - Provinces</option>
                    <option value="MX,countries">Mexico</option>
                    <option value="MX,provinces">Mexico - Provinces</option>
                    <option value="FM,countries">Micronesia, Federated States of</option>
                    <option value="FM,provinces">Micronesia, Federated States of - Provinces</option>
                    <option value="MD,countries">Moldova, Republic of</option>
                    <option value="MD,provinces">Moldova, Republic of - Provinces</option>
                    <option value="MC,countries">Monaco</option>
                    <option value="MC,provinces">Monaco - Provinces</option>
                    <option value="MN,countries">Mongolia</option>
                    <option value="MN,provinces">Mongolia - Provinces</option>
                    <option value="ME,countries">Montenegro</option>
                    <option value="ME,provinces">Montenegro - Provinces</option>
                    <option value="MS,countries">Montserrat</option>
                    <option value="  MS,provinces">Montserrat - Provinces</option>
                    <option value="MA,countries">Morocco</option>
                  <option va  lue="MA,provinces">Morocco - Provinces</option>
                  <option   value="MZ,countries">Mozambique</option>
                  <option value="MZ,provinc  es">Mozambique - Provinces</option>
                  <option value="MM,countries">Myanmar</option>
                    <option value="MM,provinces"  >Myanmar - Provinces</option>
                <option value="NA,coun  tries">Namibia</option>
                  <option value="NA,provinces">Namibia   - Provinces</option>
                <option value="NR,countries"  >Nauru</option>
                  <option value="NR,provinces">Nauru - Provinc  es</option>
                  <option value="NP,countries">Nepal</option>
                  <o  ption value="NP,provinces">Nepal - Provinces</option>
                  <option val  ue="NL,countries">Netherlands</option>
                <optio  n value="NL,provinces">Netherlan  ds - Provinces</option>
                <option v  alue="NC,countries">New Caledon  ia</option>
                <option value="NC,provinces">New C  aledonia - Provinces</option>
                  <option value="NZ,countries">New Z  ealand</option>
                <opti  on value="NZ,provinces">New Zealand - Provinces</option  >
                <option value="NI,countrie  s">Nicaragua</option>
                  <op  tion value="NI,provinces">Nicaragua - Provinces</option>
                  <option value="NE,countrie  s">Niger</option>
                  <option value="NE,province  s">Niger - Provinces</option>
                  <option value="NG,countries"  >Nigeria</option>
                  <option value="NG,provinces">Ni  geria - Provinces</option>
                  <option value="NU,countries">Niue<  /option>
                  <option value="NU,provinces">Niue - Provinces</opti  on>
                    <option value="NF,countries">Norfolk Island</option>
                    <option value="NF,provinces">Norfolk Island - Provinces</option>
                    <option value="MP,countries">Northern Mariana Islands</option>
                    <option value="MP,provinces">Northern Mariana Islands - Provinces</option>
                    <option value="NO,countries">Norway</option>
                    <option value="NO,provinces">Norway - Provinces</option>
                    <option value="OM,countries">Oman</option>
                    <option value="OM,provinces">Oman - Provinces</option>
                    <option value="PK,countries">Pakistan</option>
                    <option value="PK,provinces">Pakistan - Provinces</option>
                    <option value="PW,countries">Palau</option>
                    <option value="PW,provinces">Palau - Provinces</option>
                    <option value="PS,countries">Palestinian Territory, Occupied</option>
                    <option value="PS,provinces">Palestinian Territory, Occupied - Provinces</option>
                    <option value="PA,countries">Panama</option>
                    <option value="PA,provinces">Panama - Provinces</option>
                    <option value="PG,countries">Papua New Guinea</option>
                    <option value="PG,provinces">Papua New Guinea - Provinces</option>
                    <option value="PY,countries">Paraguay</option>
                    <option value="PY,provinces">Paraguay - Provinces</option>
                    <option value="PE,countries">Peru</option>
                    <option value="PE,provinces">Peru - Provinces</option>
                    <option value="PH,countries">Philippines</option>
                    <option value="PH,provinces">Philippines - Provinces</option>
                    <option value="PN,countries">Pitcairn</option>
                    <option value="PN,provinces">Pitcairn - Provinces</option>
                    <option value="PL,countries">Poland</option>
                    <option value="PL,provinces">Poland - Provinces</option>
                    <option value="PT,countries">Portugal</option>
                    <option value="PT,provinces">Portugal - Provinces</option>
                    <option value="PR,countries">Puerto Rico</option>
                    <option value="PR,provinces">Puerto Rico - Provinces</option>
                    <option value="QA,countries">Qatar</option>
                    <option value="QA,provinces">Qatar - Provinces</option>
                    <option value="RE,countries">Reunion !Réunion</option>
                    <option value="RE,provinces">Reunion !Réunion - Provinces</option>
                    <option value="RO,countries">Romania</option>
                    <option value="RO,provinces">Romania - Provinces</option>
                    <option value="RU,countries">Russian Federation</option>
                    <option value="RU,provinces">Russian Federation - Provinces</option>
                    <option value="RW,countries">Rwanda</option>
                    <option value="RW,provinces">Rwanda - Provinces</option>
                    <option value="BL,countries">Saint Barthélemy</option>
                    <option value="BL,provinces">Saint Barthélemy - Provinces</option>
                    <option value="SH,countries">Saint Helena, Ascension and Tristan da Cunha</option>
                    <option value="SH,provinces">Saint Helena, Ascension and Tristan da Cunha - Provinces</option>
                    <option value="KN,countries">Saint Kitts and Nevis</option>
                    <option value="KN,provinces">Saint Kitts and Nevis - Provinces</option>
                    <option value="LC,countries">Saint Lucia</option>
                    <option value="LC,provinces">Saint Lucia - Provinces</option>
                    <option value="MF,countries">Saint Martin (French part)</option>
                    <option value="MF,provinces">Saint Martin (French part) - Provinces</option>
                    <option value="PM,countries">Saint Pierre and Miquelon</option>
                    <option value="PM,provinces">Saint Pierre and Miquelon - Provinces</option>
                    <option value="VC,countries">Saint Vincent and the Grenadines</option>
                    <option value="VC,provinces">Saint Vincent and the Grenadines - Provinces</option>
                    <option value="WS,countries">Samoa</option>
                    <option value="WS,pro  vinces">Samoa - Provinces</option>
                  <option value="SM,co  untries">San Marino</option>
                  <option value="SM  ,provinces">San Marino - Provinces</option>
                  <option value=  "ST,countries">Sao Tome and Principe</option>
                    <option value="ST,provinces">Sao Tome and Principe - Provinces</option  >
                  <option value="SA,countries">Saudi Arabia</opt  ion>
                  <option value="SA,provinces">Saudi Arabia - Provinc  es</option>
                  <option value="SN,countries">Senegal</option>
                    <option value="SN,provinces">Senegal - Provinces</option>
                    <option value="RS,countries">Serbia</option>
                    <option value="RS,provinces">Serbia - Provinces</option>
                    <option value="SC,countries">Seychelles</option>
                    <option value="SC,provinces">Seychelles - Provinces</option>
                    <option value="SL,countries">Sierra Leone</option>
                  <option valu  e="SL,provinces">Sierra Leone - Provinces</option>
                  <option value="SG,countr  ies">Singapore</option>
                <opti  on value="SG,pro  vinces">Singapore - Provinces</option>
                <option value="SX,    countries">Sint Maarten (Dutch part)</option>
                    <option value="SX,provinces">Sint Maarten (Dutch part) - Provinces</opti    on>
                <option value="SK,countries">Slovakia</opt    ion>
                <option value="SK,provinces">Slovakia - Provinces</op    tion>
                  <option value="SI,countries">Slovenia<  /option>
                  <option value="SI,provinces">Slovenia - Provinces  </option>
                  <option value="SB,countries">Solomon Islands</opt  ion>
                  <option value="SB,provinces">Solomon Islands - Provinces</option>
                    <option value="SO,countries">Somalia</option>
                    <option value="SO,provinces">Somalia - Provinces</option>
                    <option value="ZA,countries">South Africa</option>
                    <option value="ZA,provinces">South Africa - Provinces</option>
                    <option value="GS,countries">South Georgia and the South Sandwich Islands</option>
                    <option value="GS,provinces">South Georgia and the South Sandwich Islands - Provinces</option>
                    <option value="SS,countries">South Sudan</option>
                    <option value="SS,provinces">South Sudan - Provinces</option>
                    <option value="ES,countries">Spain</option>
                    <option value="ES,provinces">Spain - Provinces</option>
                    <option value="LK,countries">Sri Lanka</option>
                    <option value="LK,provinces">Sri Lanka - Provinces</option>
                    <option value="SD,countries">Sudan</option>
                    <option value="SD,provinces">Sudan - Provinces</option>
                    <option value="SR,countries">Suriname</option>
                    <option value="SR,provinces">Suriname - Provinces</option>
                    <option value="SJ,countries">Svalbard and Jan Mayen</option>
                    <option value="SJ,provinces">Svalbard and Jan Mayen - Provinces</option>
                    <option value="SZ,countries">Swaziland</option>
                    <option value="SZ,provinces">Swaziland - Provinces</option>
                    <option value="SE,countries">Sweden</option>
                    <option value="SE,provinces">Sweden - Provinces</option>
                    <option value="CH,countries">Switzerland</option>
                    <option value="CH,provinces">Switzerland - Provinces</option>
                    <option value="SY,countries">Syrian Arab Republic</option>
                    <option value="SY,provinces">Syrian Arab Republic - Provinces</option>
                    <option value="TW,countries">Taiwan, Province of China</option>
                    <option value="TW,provinces">Taiwan, Province of China - Provinces</option>
                    <option value="TJ,countries">Tajikistan</option>
                    <option value="TJ,provinces">Tajikistan - Provinces</option>
                    <option value="TZ,countries">Tanzania, United Republic of</option>
                    <option value="TZ,provinces">Tanzania, United Republic of - Provinces</option>
                    <option value="TH,countries">Thailand</option>
                    <option value="TH,provinces">Thailand - Provinces</option>
                    <option value="TL,countries">Timor-Leste</option>
                    <option value="TL,provinces">Timor-Leste - Provinces</option>
                    <option value="TG,countries">Togo</option>
                    <option value="TG,provinces">Togo - Provinces</option>
                    <option value="TK,countries">Tokelau</option>
                    <option value="TK,provinces">Tokelau - Provinces</option>
                    <option value="TO,countries">Tonga</option>
                    <option value="TO,provinces">Tonga - Provinces</option>
                    <option value="TT,countries">Trinidad and Tobago</option>
                    <option value="TT,provinces">Trinidad and Tobago - Provinces</option>
                    <option value="TN,countries">Tunisia</option>
                    <option value="TN,provinces">Tunisia - Provinces</option>
                    <option value="TR,countries">Turkey</option>
                    <option value="TR,provinces">Turkey - Provinces</option>
                    <option value="TM,countries">Turkmenistan</option>
                    <option value="TM,provinces">Turkmenistan - Provinces</option>
                    <option value="TC,countries">Turks and Caicos Islands</option>
                    <option value="TC,provinces">Turks and Caicos Islands - Provinces</option>
                    <option value="TV,countries">Tuvalu</option>
                    <option value="TV,provinces">Tuvalu - Provinces</option>
                    <option value="UG,countries">Uganda</option>
                    <option value="UG,provinces">Uganda - Provinces</option>
                    <option value="UA,countries">Ukraine</option>
                    <option value="UA,provinces">Ukraine - Provinces</option>
                    <option value="AE,countries">United Arab Emirates</option>
                    <option value="AE,provinces">United Arab Emirates - Provinces</option>
                    <option value="GB,countries">United Kingdom</option>
                    <option value="GB,provinces">United Kingdom - Provinces</option>
                    <option value="US,countries">United States</option>
                    <option value="US,provinces">United States - Provinces</option>
                    <option value="UM,countries">United States Minor Outlying Islands</option>
                    <option value="UM,provinces">United States Minor Outlying Islands - Provinces</option>
                    <option value="UY,countries">Uruguay</option>
                    <option value="UY,provinces">Uruguay - Provinces</option>
                    <option value="UZ,countries">Uzbekistan</option>
                    <option value="UZ,provinces">Uzbekistan - Provinces</option>
                    <option value="VU,countries">Vanuatu</option>
                    <option value="VU,provinces">Vanuatu - Provinces</option>
                    <option value="VE,countries">Venezuela, Bolivarian Republic of</option>
                    <option value="VE,provinces">Venezuela, Bolivarian Republic of - Provinces</option>
                    <option value="VN,countries">Viet Nam</option>
                    <option value="VN,provinces">Viet Nam - Provinces</option>
                    <option value="VG,countries">Virgin Islands, British</option>
                    <option value="VG,provinces">Virgin Islands, British - Provinces</option>
                    <option value="VI,countries">Virgin Islands, U.S.</option>
                    <option value="VI,provinces">Virgin Islands, U.S. - Provinces</option>
                    <option value="WF,countries">Wallis and Futuna</option>
                    <option value="WF,provinces">Wallis and Futuna - Provinces</option>
                    <option value="EH,countries">Western Sahara</option>
                    <option value="EH,provinces">Western Sahara - Provinces</option>
                    <option value="YE,countries">Yemen</option>
                    <option value="YE,provinces">Yemen - Provinces</option>
                    <option value="ZM,countries">Zambia</option>
                    <option value="ZM,provinces">Zambia - Provinces</option>
                    <option value="ZW,countries">Zimbabwe</option>
                    <option value="ZW,provinces">Zimbabwe - Provinces</option>
                  </select></td>
                <td><select name="display_mode" onChange="isolinkcheck();">
                    <option value="regions">
                    <?=__('Regions')?>
                    </option>
                    <option value="markers">
                    <?=__('Markers (Text Location)')?>
                    </option>
                    <option value="markers02">
                    <?=__('Markers (Coordinates)')?>
                    </option>
                  </select></td>
                <td><?=__('Enable')?>
                  <input name="interactive" type="checkbox"  id="interactive" onchange="drawVisualization();" value="1" checked />
                  <br>
                  <?=__('Show Tooltip')?>
                  <input name="tooltipt" type="checkbox"  id="tooltipt" onchange="drawVisualization();" value="1" checked /></td>
                <td><select name="map_action" onChange="isolinkcheck()">
                    <option value="none" selected="selected">
                    <?=__('None')?>
                    </option>
                    <option value="i_map_action_open_url">
                    <?=__('Open URL (same window)')?>
                    </option>
                    <option value="i_map_action_open_url_new">
                    <?=__('Open URL (new window)')?>
                    </option>
                  </select></td>
              </tr>
            </table>
          </div>
          <span id="iso-code-msg"></span>
          <div id="latlondiv">
            <table width="100%" border="0" cellspacing="5" cellpadding="5" class="latlon">
              <tbody>
                <tr>
                  <td><strong>
                    <?=__('Use the form below to help you get the coordinates values')?>
                    </strong><br>
                    <?=__('Convert Address into Lat/Lon:')?>
                    <label for="mapsearch">
                      <input type="text" name="mapsearch" id="mapsearch">
                      <input type="button" name="convert" id="convert" value="<?=__('Convert')?>" onclick="getAddress()">
                    </label>
                    <span id="latlonvalues"></span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-heading"><h4><?=__('Interactive Regions')?></h4></div>
        <div class="panel-body">
          <div id="map-data">
            <div id="custom-action"></div>
            <input type="hidden" name="places" id="places" value="">
            <input type="hidden" name="custom_action" value="">
            <div id="simple-table" class="table-responsive">
              <table id="add-table">
                <tr>
                  <td>
                    <table id="add-table-simple">
                      <tr>
                        <td><?=__('Region Code:')?></td>
                        <td><?=__('Title:')?></td>
                        <td><?=__('Tooltip:')?></td>
                        <td><?=__('Action Value:')?></td>
                        <td><?=__('Color:')?></td>
                        <td></td>
                      </tr>
                      <tr>
                        <td><input name="cd" type="text" id="cd" size="15" />
                          <br />
                          <small>
                          <?=__('Follow the suggestions above.')?>
                          <br />
                          <?=__('MANDATORY')?>
                          </small></td>
                        <td><input name="c" type="text" id="c" size="15" />
                          <br />
                          <small>
                          <?=__('It will be the first line of the tooltip.')?>
                          </small></td>
                        <td><input name="t" type="text" id="t" size="20" />
                          <br />
                          <small>
                          <?=__('It will be the second line of the tooltip.')?>
                          </small></td>
                        <td><input name="u" type="text" id="u" size="20" />
                          <br />
                          <small>
                          <?=__('Paramater for the action.')?>
                          <br />
                          <?=__('Ex. Url for Open Url Action.')?>
                          <br />
                          <?=__('You can leave it blank for no action.')?>
                          </small></td>
                        <td><input name="cl" type="text" id="cl" size="15" value="#6699CC" class="color {hash:true, adjust:false}"  />
                          <br /></td>
                        <td><input type="button" class="btn btn-primary-outline" value="<?=__('Add')?>" onclick="addPlaceToTable();" />
                          <br /></td>
                      </tr>
                    </table>
                    <div id="htmlplacetable"></div></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-heading"><h4><?=__('Preview')?></h4></div>
        <div class="panel-body">
          <small>
            <?=__('The "Active Region Action" will not work on this preview. When an active region is clicked an alert message with the value inserted will display for debugging.')?>
          </small>
          <div id="visualization" class="table-responsive"></div>
          <div class="clear"></div>
          <hr>
          <div class="form-actions">
            <input type="hidden" id="jscode" name="jscode" value="">
            <input type="hidden" name="current_settings" value="">
            <input type="hidden" name="load_settings" value="<?=htmlentities($map_settings)?>">
            <?= FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary'))?>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
