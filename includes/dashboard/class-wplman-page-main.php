<div id="new-shortlink">
	<a href="#" id="add-new-shortlink" class="button button-primary"><?php _e('Add new Short link', WPLMAN_TEXTDOMAIN);?></a>
</div>

<div id="list-shortlink">

    <form id="shortlink-posts-filter">
        <p class="search-box">
            <label class="screen-reader-text" for="post-search-input">Search Short links:</label>
            <input type="search" id="post-search-input" name="s" value="">
            <input type="submit" id="search-submit" class="button" value="Search Short links">
        </p>

        <div class="tablenav top">
            <div class="alignleft actions">

                <?php wplman_dropdown_shortlink_groups();?>

            </div>



            <div class="tablenav-pages" id="pagination-shortlinks">
                <!-- Ajax pagination placed here -->
            </div>

        </div>
    </form>

    <div id="mask">
        <div class="loading-msg">
            <span class="spinner is-active"></span>
            <p><?php _e('Loading data ...', WPLMAN_TEXTDOMAIN);?></p>
        </div>
    </div>

    <table class="wp-list-table widefat fixed striped posts">
        <thead>
            <tr>
                <th class="wplman-table-label"><?php _e('Link', WPLMAN_TEXTDOMAIN);?></th>
                <th class="wplman-table-label"><?php _e('Target link', WPLMAN_TEXTDOMAIN);?></th>
                <th class="wplman-table-label"><?php _e('Hits', WPLMAN_TEXTDOMAIN);?></th>
                <th class="wplman-table-label"><?php _e('Description', WPLMAN_TEXTDOMAIN);?></th>
                <th style="width: 10%;" class="wplman-table-label"><?php _e('Date', WPLMAN_TEXTDOMAIN);?></th>
            </tr>

        </thead>

        <tbody>

            <!-- data placed with ajax request here -->
        </tbody>

    </table>


    <?php

    ?>
</div>