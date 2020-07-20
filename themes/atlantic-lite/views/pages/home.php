<?
    $categories = $categs;
    $auto_located_locations = $auto_locats;
    $hidden_categories = $hide_categories;
?>

<?= View::factory('home/index', compact(
    'ads', 'user_location', 'categories', 'auto_located_locations', 'hidden_categories'
)) ?>
