<?php
$collectionTitle = metadata('collection', 'display_title');
$totalItems = metadata('collection', 'total_items');
?>

<?php echo head(array('title' => $collectionTitle, 'bodyclass' => 'collections show')); ?>

<!-- Page Content -->
<div class="row row-flow">

    <div class="col-lg-8">

        <h1 class="my-4"><?php echo $collectionTitle; ?></h1>

        <!-- Items -->

        <?php foreach (loop('items') as $item): ?>
            <div class="card mb-4">
                <?php if ($itemImage = record_image($item, 'fullsize', array('class' => 'card-img-top'))): ?>
                    <div class="image-container-flow">
                        <?php echo $itemImage; ?>
                    </div>
                <?php endif; ?>
                <div class="card-body">
                    <h2 class="card-title"><?php echo metadata($item, array('Dublin Core', 'Title')) ?></h2>
                    <?php $desc = strip_tags(htmlspecialchars_decode(metadata($item, array('Dublin Core', 'Description')))); ?>
                    <p class="card-text"><?php echo snippet($desc,0,250) ?></p>
                    <?php echo link_to_item("Read More &rarr;",array('class'=>'btn btn-primary')); ?>
                </div>
            </div>

        <?php endforeach ?>

        <?php echo pagination_links(); ?>

    </div>

    <!-- Sidebar Widgets Column -->
    <div class="col-lg-4">

        <!-- Search Widget -->
        <div class="card my-4">
            <h5 class="card-header">Search</h5>
            <div class="card-body">
                <?php echo search_form(); ?>
            </div>
        </div>

        <!-- Side Widget -->
        <div class="card my-4">
            <h5 class="card-header">Description</h5>
            <div class="card-body">
                <?php echo metadata($collection, array('Dublin Core', 'Description')) ?>
            </div>
        </div>

    </div>
    
</div>

<?php echo foot(); ?>
