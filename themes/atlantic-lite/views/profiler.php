<?php defined('SYSPATH') or die('No direct script access.') ?>

<style type="text/css">
<?php include Kohana::find_file('views', 'profiler/style', 'css') ?>
</style>

<?php
$group_stats      = Profiler::group_stats();
$group_cols       = array('min', 'max', 'average', 'total');
$application_cols = array('min', 'max', 'average', 'current');
?>
<h2 id="profiler-header" class="profiler_header">Profiler</h2>
<a href="#kohana_error"><button type="button" class="btn btn-primary pull-right">jump to ENVIRONMENT</button></a>
<div class="kohana">
    <?php foreach (Profiler::groups() as $group => $benchmarks): ?>
    <table class="profiler">
        <tr class="group">
            <th class="name" rowspan="2"><?=__(ucfirst($group)) ?></th>
            <td class="time" colspan="4"><?=number_format($group_stats[$group]['total']['time'], 6) ?> <abbr title="seconds">s</abbr></td>
        </tr>
        <tr class="group">
            <td class="memory" colspan="4"><?=number_format($group_stats[$group]['total']['memory'] / 1024, 4) ?> <abbr title="kilobyte">kB</abbr></td>
        </tr>
        <tr class="headers">
            <th class="name"><?=__('Benchmark') ?></th>
            <?php foreach ($group_cols as $key): ?>
            <th class="<?=$key?>"><?=__(ucfirst($key)) ?></th>
            <?php endforeach ?>
        </tr>
        <?php foreach ($benchmarks as $name => $tokens): ?>
        <tr class="mark time">
            <?php $stats = Profiler::stats($tokens) ?>
            <th class="name" rowspan="2" scope="rowgroup"><?=HTML::chars($name), ' (', core::count($tokens), ')' ?></th>
            <?php foreach ($group_cols as $key): ?>
            <td class="<?=$key ?>">
                <div>
                    <div class="value"><?=number_format($stats[$key]['time'], 6) ?> <abbr title="seconds">s</abbr></div>
                    <?php if ($key === 'total'): ?>
                        <div class="graph" style="left: <?=max(0, 100 - $stats[$key]['time'] / $group_stats[$group]['max']['time'] * 100) ?>%"></div>
                    <?php endif ?>
                </div>
            </td>
            <?php endforeach ?>
        </tr>
        <tr class="mark memory">
            <?php foreach ($group_cols as $key): ?>
            <td class="<?=$key ?>">
                <div>
                    <div class="value"><?=number_format($stats[$key]['memory'] / 1024, 4) ?> <abbr title="kilobyte">kB</abbr></div>
                    <?php if ($key === 'total'): ?>
                        <div class="graph" style="left: <?=max(0, 100 - $stats[$key]['memory'] / $group_stats[$group]['max']['memory'] * 100) ?>%"></div>
                    <?php endif ?>
                </div>
            </td>
            <?php endforeach ?>
        </tr>
        <?php endforeach ?>
    </table>
    <?php endforeach ?>

    <table class="profiler">
        <?php $stats = Profiler::application() ?>
        <tr class="final mark time">
            <th class="name" rowspan="2" scope="rowgroup"><?=__('Application Execution').' ('.$stats['count'].')' ?></th>
            <?php foreach ($application_cols as $key): ?>
            <td class="<?=$key ?>"><?=number_format($stats[$key]['time'], 6) ?> <abbr title="seconds">s</abbr></td>
            <?php endforeach ?>
        </tr>
        <tr class="final mark memory">
            <?php foreach ($application_cols as $key): ?>
            <td class="<?=$key ?>"><?=number_format($stats[$key]['memory'] / 1024, 4) ?> <abbr title="kilobyte">kB</abbr></td>
            <?php endforeach ?>
        </tr>
    </table>
</div>
<a href="#profiler-header"><button type="button" class="btn btn-primary pull-right">^ PROFILER ^</button></a>
<a href="#"><button type="button" class="btn btn-primary pull-right">^ TOP ^</button></a>

<style type="text/css">
#kohana_error { background: #ddd; font-size: 1em; font-family:sans-serif; text-align: left; color: #111; }
#kohana_error h1,
#kohana_error h2 { margin: 0; padding: 1em; font-size: 1em; font-weight: normal; background: #911; color: #fff; }
    #kohana_error h1 a,
    #kohana_error h2 a { color: #fff; }
#kohana_error h2 { background: #222; }
#kohana_error h3 { margin: 0; padding: 0.4em 0 0; font-size: 1em; font-weight: normal; }
#kohana_error p { margin: 0; padding: 0.2em 0; }
#kohana_error a { color: #1b323b; }
#kohana_error pre { overflow: auto; white-space: pre-wrap; }
#kohana_error table { width: 100%; display: block; margin: 0 0 0.4em; padding: 0; border-collapse: collapse; background: #fff; }
    #kohana_error table td { border: solid 1px #ddd; text-align: left; vertical-align: top; padding: 0.4em; }
#kohana_error div.content { padding: 0.4em 1em 1em; overflow: hidden; }
#kohana_error pre.source { margin: 0 0 1em; padding: 0.4em; background: #fff; border: dotted 1px #b7c680; line-height: 1.2em; }
    #kohana_error pre.source span.line { display: block; }
    #kohana_error pre.source span.highlight { background: #f0eb96; }
        #kohana_error pre.source span.line span.number { color: #666; }
#kohana_error ol.trace { display: block; margin: 0 0 0 2em; padding: 0; list-style: decimal; }
    #kohana_error ol.trace li { margin: 0; padding: 0; }
</style>
<script type="text/javascript">
document.documentElement.className = document.documentElement.className + ' js';
function koggle(elem)
{
    elem = document.getElementById(elem);

    if (elem.style && elem.style['display'])
        // Only works with the "style" attr
        var disp = elem.style['display'];
    else if (elem.currentStyle)
        // For MSIE, naturally
        var disp = elem.currentStyle['display'];
    else if (window.getComputedStyle)
        // For most other browsers
        var disp = document.defaultView.getComputedStyle(elem, null).getPropertyValue('display');

    // Toggle the state of the "display" style
    elem.style.display = disp == 'block' ? 'none' : 'block';
    return false;
}
</script>

<div id="kohana_error">
    <h2><?=__('Environment') ?></h2>
    <div class="content">
        <?php $included = get_included_files() ?>
        <h3><a href="#<?=$env_id = 'environment_included'?>" onclick="return koggle('<?=$env_id?>')"><?=__('Included files') ?></a> (<?=core::count($included)?>)</h3>
        <div id="<?=$env_id?>" class="collapsed">
            <table cellspacing="0">
                <?php foreach ($included as $file): ?>
                <tr>
                    <td><code><?=Debug::path($file)?></code></td>
                </tr>
                <?php endforeach ?>
            </table>
        </div>
        <?php $included = get_loaded_extensions() ?>
        <h3><a href="#<?=$env_id = 'environment_loaded' ?>" onclick="return koggle('<?=$env_id?>')"><?=__('Loaded extensions')?></a> (<?=core::count($included)?>)</h3>
        <div id="<?=$env_id?>" class="collapsed">
            <table cellspacing="0">
                <?php foreach ($included as $file): ?>
                <tr>
                    <td><code><?=Debug::path($file)?></code></td>
                </tr>
                <?php endforeach ?>
            </table>
        </div>
        <?php foreach (array('_SESSION', '_GET', '_POST', '_FILES', '_COOKIE', '_SERVER') as $var): ?>
        <?php if (empty($GLOBALS[$var]) OR ! is_array($GLOBALS[$var])) continue ?>
        <h3><a href="#<?=$env_id = 'environment'.strtolower($var)?>" onclick="return koggle('<?=$env_id?>')">$<?=$var?></a></h3>
        <div id="<?=$env_id ?>" class="collapsed">
            <table cellspacing="0">
                <?php foreach ($GLOBALS[$var] as $key => $value): ?>
                <tr>
                    <td><code><?=HTML::chars($key)?></code></td>
                    <td><pre><?=Debug::dump($value)?></pre></td>
                </tr>
                <?php endforeach ?>
            </table>
        </div>
        <?php endforeach ?>
    </div>
</div>

<a href="#profiler-header"><button type="button" class="btn btn-primary pull-right">^ PROFILER ^</button></a>
<a href="#kohana_error"><button type="button" class="btn btn-primary pull-right">^ ENVIRONMENT ^</button></a>
<a href="#"><button type="button" class="btn btn-primary pull-right">^ TOP ^</button></a>
