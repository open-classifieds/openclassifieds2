<div id="pusher-subscribe"
    class="hidden"
    data-user="<?= Auth::instance()->get_user()->email ?>"
    data-key="<?= Core::config('general.pusher_notifications_key') ?>"
    data-cluster="<?= Core::config('general.pusher_notifications_cluster') ?>">
</div>
