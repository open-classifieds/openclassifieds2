<?php defined('SYSPATH') or die('No direct script access.');?>

<script type="application/ld+json">
<?
    $structured_data = [
        '@context' => 'http://schema.org/',
        '@type' => 'Product',
        'name' => $ad->title,
    ];

    if ($ad->get_first_image() !== NULL)
        $structured_data['image'] = $ad->get_first_image();

    if (Core::config('advertisement.description') != FALSE AND ! empty($ad->description))
        $structured_data['description'] = $ad->description;

    if ($ad->price > 0)
    {
        $structured_data['offers'] = [
            '@type' => 'Offer',
            'priceCurrency' => i18n::get_intl_currency_symbol()?i18n::get_intl_currency_symbol():'USD',
            'price' => number_format($ad->price,2,".",""),
        ];

        if (Core::config('payment.stock') AND $ad->stock > 0)
            $structured_data['offers']['availability'] = 'http://schema.org/InStock';
    }

    if (Core::config('advertisement.reviews') == 1 AND $ad->rate !== NULL)
    {
        $structured_data['aggregateRating'] = [
            '@type' => 'AggregateRating',
            'ratingValue' => $ad->rate,
            'ratingCount' => Model_Review::get_ad_count_rates($ad),
        ];
    }

    echo json_encode($structured_data);
?>
</script>
