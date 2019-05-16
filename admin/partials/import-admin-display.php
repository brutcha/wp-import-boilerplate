<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/brutcha/
 * @since      1.0.0
 *
 * @package    Import
 * @subpackage Import/admin/partials
 */

$feed = isset( $_GET['feed'] ) ? $_GET['feed'] : null;
?>

<div class="wrap">
    <h2><?php _e('Available Imports', 'Import') ?></h2>

    <ul>
        <?php foreach ( $drivers as $driver ) :
            $driver_name = $driver['name']; ?>

            <a
                href="<?= sprintf('%s&feed=%s', $this->base_URI, $driver_name) ?>"
                class="button<?= $driver_name === $feed ? ' button-primary' : '' ?>"
            >
                <?= $driver_name ?>
            </a>
        <?php endforeach; ?>
    </ul>

    <?php if ( $feed ): ?>
        <?php $this->process( $feed ) ?>
    <?php endif; ?>
</div>
