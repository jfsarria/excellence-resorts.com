<?
/*
 * Revised: Apr 25, 2011
 *          Feb 01, 2017
 */
?>
<div class="s_success top_msg">
    Data have been saved successfully.
    <?
        $isMetaIO = false;//isset($isMetaIO) ? $isMetaIO : false;
        if ($isMetaIO) {
            ?>
                <div>
                    <a href="javascript:void(0)" onclick="ibe.updateMetaIO(<?=$PROP_ID?>)">Update Meta IO files</a>
                    <div id='metaIO_msg'></div>
                </div>
            <?
        }
    ?>
</div>
