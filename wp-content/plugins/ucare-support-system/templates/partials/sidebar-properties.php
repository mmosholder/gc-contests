<?php
/**
 * Template for ticket properties sidebar.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;

?>

<!-- panel-body -->

    <div class="message-area"></div>

    <form class="ticket-status-form" method="post">

        <?php // TODO Replace this with regular HTML Form ?>
        <?php $form = include $form_config; ?>

        <?php foreach ( $form->fields as $field ) : ?>

            <div class="form-group">

                <label><?php echo $field->label; ?></label>

                <?php $field->render(); ?>

            </div>

        <?php endforeach; ?>

        <input type="hidden" name="id" value="<?php echo $ticket->ID; ?>"/>
        <input type="hidden" name="<?php echo $form->id; ?>"/>

        <hr class="sidebar-divider">

        <div class="bottom text-right">

            <button type="submit" class="button button-submit">

                <span class="glyphicon glyphicon-floppy-save button-icon"></span>

                <span><?php _e( get_option( Options::SAVE_BTN_TEXT, Defaults::SAVE_BTN_TEXT ) ); ?></span>

            </button>

        </div>

    </form>

<!-- /panel-body -->