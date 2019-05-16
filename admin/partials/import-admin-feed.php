<?php

/**
 * Single driver controlls
 *
 * @link       https://github.com/brutcha/
 * @since      1.0.0
 *
 * @package    Import
 * @subpackage Import/admin/partials
 * 
 * TODO: use vanila js
 */

$is_running = isset($_GET['step']);

$driver = $_GET['feed'];
$step = (int) $_GET['step'];
?>

<?php if ($is_running): ?>
    <div id="feed__progress" class="feed__progress feed__progress--loading">
        <strong class="feed__name"><?= $driver ?></strong>
        <progress id="feed__progress-bar" class="feed__progress-bar" max="1" value="<?= $step ?>"><?= $step ?></progress>
        <span id="feed__progress-status" class="feed__progress-status"><?= $step ?>/<?= $step + 1 ?></span>
    </div>
    <?php else: ?>
        <a
            class="feed__controll button button-fullwidth"
            href="<?= sprintf('%s&feed=%s&step=0', $this->base_URI, $driver) ?>"
        >
            Run <?= $driver ?> feed
        </a>
    <?php endif; ?>

    <div>
        <textarea disabled rows="20" id="feed__info"></textarea>
    </div>

<script type="text/javascript">
    var progressNode = document.getElementById('feed__progress');
    var progressBarNode = document.getElementById('feed__progress-bar');
    var progresStatusNode = document.getElementById('feed__progress-status');
    var progresInfoNode = document.getElementById('feed__info');

    function writeProgress(step, totalSteps, info) {
        progressBarNode.max = totalSteps;
        progressBarNode.value = step;
        progressBarNode.innerText = step;
        progresStatusNode.innerText = step + '/' + totalSteps;
        progresInfoNode.value += info + '\n';
        progressNode.className = 'feed__progress';
    }

    function storeProgress(step) {
        var querySearch = '?feed=<?= $driver ?>&step=' + step;
        var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + querySearch;

        window.history.pushState({ path: newurl }, '', newurl);
    }

    function scrape(nextStep) {
        jQuery.post(
            ajaxurl,
            {
                'action':  'driver_run',
                'driver':  '<?= $driver ?>',
                'step':    nextStep
            },
            function(res) {
                var data = JSON.parse(res);

                writeProgress(data.step, data.totalSteps, data.info);

                if (data.step < data.totalSteps)
                    scrape(data.step + 1);

            }
        );
    }

    <?php
    if ($is_running)
        echo sprintf('scrape(%n)', $step);
    ?>
</script>
