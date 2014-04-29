<h1><?php echo __d('i18n_search', 'Search Results: ') . h($q); ?></h1>

<?php if (count($nodes) == 0): ?>
    <p><?php echo __d('i18n_search', 'No items found.'); ?></p>
<?php else: ?>
    <div class="paging"><?php echo $this->Paginator->numbers(
        array('separator' => ' • ')); ?></div>
    <?php foreach ($this->params['named'] as $nn => $nv) {
                $this->Paginator->options['url'][$nn] = $nv;
            }
    ?>
<?php endif; ?>

<?php foreach ($nodes as $node): ?>
    <?php $this->Layout->setNode($node); ?>
        <h4><?php echo $this->Html->link($this->Layout->node('title'), $this->Layout->node('url')); ?></h4>
        <p><?php echo $this->Layout->node('excerpt'); ?></p>
<?php endforeach; ?>

