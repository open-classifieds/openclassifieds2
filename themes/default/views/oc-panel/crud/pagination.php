<?php defined('SYSPATH') or die('No direct script access.');
/*
    First Previous 1 2 3 ... 22 23 24 25 26 [27] 28 29 30 31 32 ... 48 49 50 Next Last
*/

// Number of page links in the begin and end of whole range
$count_out = ( ! empty($config['count_out'])) ? (int) $config['count_out'] : 2;
// Number of page links on each side of current page
$count_in = ( ! empty($config['count_in'])) ? (int) $config['count_in'] : 4;

// Beginning group of pages: $n1...$n2
$n1 = 1;
$n2 = min($count_out, $total_pages);

// Ending group of pages: $n7...$n8
$n7 = max(1, $total_pages - $count_out + 1);
$n8 = $total_pages;

// Middle group of pages: $n4...$n5
$n4 = max($n2 + 1, $current_page - $count_in);
$n5 = min($n7 - 1, $current_page + $count_in);
$use_middle = ($n5 >= $n4);

// Point $n3 between $n2 and $n4
$n3 = (int) (($n2 + $n4) / 2);
$use_n3 = ($use_middle && (($n4 - $n2) > 1));

// Point $n6 between $n5 and $n7
$n6 = (int) (($n5 + $n7) / 2);
$use_n6 = ($use_middle && (($n7 - $n5) > 1));

// Links to display as array(page => content)
$links = array();

// Generate links data in accordance with calculated numbers
for ($i = $n1; $i <= $n2; $i++)
{
    $links[$i] = $i;
}
if ($use_n3)
{
    $links[$n3] = '&hellip;';
}
for ($i = $n4; $i <= $n5; $i++)
{
    $links[$i] = $i;
}
if ($use_n6)
{
    $links[$n6] = '&hellip;';
}
for ($i = $n7; $i <= $n8; $i++)
{
    $links[$i] = $i;
}

?>

<?if(!Theme::get('rtl')):?>
    <div class="my-6">
        <span class="relative z-0 inline-flex shadow-sm">
            <a
                href="<?= HTML::chars($page->url($previous_page)) ?>"
                rel="prev"
                id="prev"
                class="
                    relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-500 hover:text-gray-400 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150
                    <?= ! $previous_page ? 'opacity-75' : '' ?>
                "
            >
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            </a>
            <? foreach ($links as $number => $content): ?>
                <a
                    href="<?= HTML::chars($page->url($number)) ?>"
                    class="
                        -ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm leading-5 font-medium focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150
                        <?= $number == $current_page ? 'bg-blue-100 text-blue-700 hover:text-blue-500' : 'text-gray-700 hover:text-gray-500'?>
                    "
                    title="<?=__('Page')?> <?=$number?> <?=$page->title()?>"
                >
                    <?= $content ?>
                </a>
            <? endforeach ?>
            <a
                href="<?= HTML::chars($page->url($next_page)) ?>"
                rel="next"
                id="next"
                class="
                    -ml-px relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-500 hover:text-gray-400 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150
                    <?= ! $last_page ? 'opacity-75' : '' ?>
                "
                title="<?= __('Next') ?> <?= $page->title() ?>"
            >
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
            </a>
        </span>
    </div>
<?else:?>
    <div class="my-6">
        <span class="relative z-0 inline-flex shadow-sm">
            <a
                href="<?= HTML::chars($page->url($previous_page)) ?>"
                rel="prev"
                id="prev"
                class="
                    relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-500 hover:text-gray-400 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150
                    <?= ! $previous_page ? 'opacity-75' : '' ?>
                "
            >
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            </a>
            <? foreach ($links as $number => $content): ?>
                <a
                    href="<?= HTML::chars($page->url($number)) ?>"
                    class="
                        -ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm leading-5 font-medium focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150
                        <?= $number == $current_page ? 'bg-blue-100 text-blue-700 hover:text-blue-500' : 'text-gray-700 hover:text-gray-500'?>
                    "
                    title="<?=__('Page')?> <?=$number?> <?=$page->title()?>"
                >
                    <?= $content ?>
                </a>
            <? endforeach ?>
            <a
                href="<?= HTML::chars($page->url($next_page)) ?>"
                rel="next"
                id="next"
                class="
                    -ml-px relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-500 hover:text-gray-400 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150
                    <?= ! $last_page ? 'opacity-75' : '' ?>
                "
                title="<?= __('Next') ?> <?= $page->title() ?>"
            >
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
            </a>
        </span>
    </div>
<?endif?>
