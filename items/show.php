<?php echo head(array('title' => metadata('item', array('Dublin Core', 'Title')),'bodyclass' => 'items show'));
if ($hasimg=metadata($item, 'has thumbnail') ) {
    $img_markup=item_image('fullsize',array(),0, $item);
    preg_match('/<img(.*)src(.*)=(.*)"(.*)"/U', $img_markup, $result);
    $hero_img = array_pop($result);
}
$maptype='story';

$usemap = false;

if (plugin_is_active('Geolocation')) {
    $location = get_db()->getTable('Location')->findLocationByItem($item, true);
    if ($location) {
        $usemap = true;
    }
}
?>

<!-- Page Content -->

    <div class="row row-flow-double">

        <!-- Post Content Column -->
        <div class="col-lg-8">

            <header id="story-header">
                <?php
                echo '<div class="item-hero '.(!$hasimg ? 'hero short' : 'hero').'" '.($hasimg ? 'style="background-image: url('.$hero_img.')"' : null).'>';
                echo '<div class="item-hero-text">'.mh_the_title().mh_the_subtitle().mh_the_byline($item,true).'</div>';
                echo '</div>';
                echo function_exists('tour_nav') ? '<nav class="tour-nav-container top">'.tour_nav(null,mh_tour_label()).'</nav>' : null;
                echo mh_the_lede();
                ?>
            </header>

            <!-- Tour Stops -->
            <?php if (element_exists('Item Type Metadata', 'Story') && metadata('item', array('Item Type Metadata', 'Story')) != ""): ?>
                <div class="lead">
                    <?php echo mh_the_text(); ?>
                </div>
                <hr>
            <?php endif ?>

            <!-- Oral Histories -->
            <?php if (metadata('item', array('Item Type Metadata', 'Transcription')) != ""): ?>
                <div class="accordion" id="accordionTranscript">
                    <div class="card">
                        <div class="card-header" id="transcriptHeading">
                            <h5 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTranscript" aria-expanded="true" aria-controls="collapseTranscript">
                                    Read Transcript
                                </button>
                            </h5>
                        </div>
                        <div id="collapseTranscript" class="collapse" aria-labelledby="transcriptHeading" data-parent="#accordionTranscript">
                            <div class="card-body lead">
                                <p><?php echo metadata('item', array('Item Type Metadata', 'Transcription')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif ?>

            <!-- Files -->
            <section class="media">
                <?php mh_video_files($item);?>
                <?php mh_item_images($item);?>
                <?php mh_audio_files($item);?>
                <?php mh_document_files($item);?>
                <?php mh_private_files($item);?>
            </section>

        </div>

        <!-- Sidebar Widgets Column -->
        <div class="col-lg-4">

            <!-- Search Widget -->
            <div class="card">
                <h5 class="card-header">Search</h5>
                <div class="card-body">
                    <?php echo search_form(); ?>
                </div>
            </div>

            <!-- Map Widget -->
            <?php if ($usemap): ?>
                <div class="card my-4">
                    <h5 class="card-header">Map</h5>
                    <div class="card-body">
                        <figure>
                            <?php echo mh_map_type($maptype,$item); ?>
                            <?php echo mh_map_actions($item);?>
                        </figure>
                        <figcaption><?php echo mh_map_caption();?></figcaption>
                    </div>
                </div>
            <?php endif ?>

            <!-- Word Cloud Widget -->
            <?php if (metadata('item', array('Item Type Metadata', 'Transcription')) != ""): ?>

                <!-- Tallies the frequency of every word in "Transcript" -->
                <script type="text/javascript">
                    var transcript = <?php echo json_encode(metadata('item', array('Item Type Metadata', 'Transcription'))) ?>;
                    var words = search(transcript);
                </script>

                <div class="card my-4">
                    <h5 class="card-header">Word Cloud</h5>
                    <div class="card-body">
                        <div id = "wordcount">
                            <!-- Value generated by wordFreq.js -->
                            <!-- check to see if item is an oral history/has a transcript then
                            creates wordcloud; prints at approximately 17wps -->
                            <script>
                                if (words === undefined) {
                                    console.log("This document is not an oral history.");
                                } else {
                                    // innerHTML --> draws 'word cloud' box
                                    document.write('<div id="wc-container" style="width: 300px; height: 200px; border: 0px solid #ccc;"></div>');

                                    // sorts the array by key values
                                    words.sort(function(a, b) {
                                        return b.weight - a.weight;
                                    });

                                    // prints specific range of values
                                    jQuery("#wc-container").jQCloud(words.sort().slice(1, 102));
                                }
                            </script>
                        </div>
                    </div>
                </div>
            <?php endif ?>

            <!-- Metadata Widget -->
            <div class="card my-4">
                <h5 class="card-header">Metadata</h5>
                <div class="card-body">
                    <table>
                        <?php foreach (all_element_texts($item, array('return_type' => 'array')) as $elementset => $elements): ?>
                            <?php foreach ($elements as $element => $elementtext): ?>
                                <?php if ($elementtext != "" && $element != "Title" && $element != "Transcription" && $element != "Story" && $element != "Private File"): ?>
                                    <div class="row" >
                                        <div class="col-sm-12">
                                            <div class="row element-title"><h5><?php echo $element ?></h5></div>
                                            <ul class="list-group list-group-flush">
                                                <?php foreach ($elementtext as $text): ?>
                                                    <li class="list-group-item"><?php echo $text ?></li>
                                                <?php endforeach ?>
                                            </ul>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <!-- /.row -->

<?php echo foot(); ?>
