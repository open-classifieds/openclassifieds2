<?= View::factory('components/pagination', compact(
    'page', 'first_page', 'previous_page', 'next_page', 'last_page',
    'total_pages', 'current_page'
)) ?>
