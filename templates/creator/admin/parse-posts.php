<div class="wrap">
    <h2><?php echo get_admin_page_title(); ?></h2>
    <div class="button-row" style="margin-top: 10px;">
        <button
                class="button"
                data-ajax="ImportPost"
                data-fields="issues_fields"
                data-input="input_import"
                data-result="result_import"
        ><?= __( 'Import' ) ?></button>

        <!--        <input data-role="test-input" value="Test issue" style="margin-left: 20px;" />-->
    </div>

    <div class="params">
        <form class="row"
              data-id="issues_fields"
              data-role="fields"
              style="margin-top: 10px; display: flex; justify-content: space-between;">

            <label>
                <span>Post type</span>

                <select data-role="post_type" name="post_type">
                    <option>Post type</option>

			        <?php
			        foreach ($types as $key => $type) {
				        $selected = ($type === 'apartments') ? 'selected' : '';
				        ?>
                        <option value="<?= $type ?>" <?= $selected ?>><?= ucfirst($type) ?></option>
			        <?php } ?>
                </select>
            </label>

            <label>
                <span>Site</span>
                <input data-role="site_url" name="site_url" value="https://bagovutivska.com.ua/" />
            </label>

            <label>
                <span>Route</span>
                <input data-role="route" name="route" value="apartments" />
            </label>

            <label>
                <span>Post id</span>
                <input data-role="post_id" name="post_id" value="4893" />
            </label>
        </form>
    </div>


    <div class="page_body">
        <div class="input-area" style="display: none">
            <textarea
                    class="widefat result_import"
                    data-id="input_import"
                    data-role="inputs"
                    style="margin-top: 20px; min-height: 400px"
            >Test | Тест</textarea>
        </div>

        <div class="input-area">
            <textarea
                    class="widefat result_import"
                    data-id="result_import"
                    data-role="results"
                    style="margin-top: 20px; min-height: 400px"
                    readonly
            >

				<?php  ?>

            </textarea>
        </div>
    </div>

    <div class="test_area">
        <pre>
			<?php
			//             var_dump(wpm_get_lang_option());
//            $fields = [
//                'post_type' => 'apartments',
//                'site_url' => 'https://bagovutivska.com.ua/',
//                'route' => 'apartments',
//                'post_id' => '4893',
//            ];
//
//            $response = \Creator\admin\ParsePosts::sendApiRequest($fields["site_url"], $fields["route"], $fields["post_id"]);
//            var_dump($response);
//
//            $post_id = \Creator\helpers\PostCreator::createPost($fields["post_type"], $response);
//            var_dump($post_id);
			?>
        </pre>
    </div>

    <div id="app"></div>
</div>


<style>
    .params label span {
        display: block;
    }
</style>