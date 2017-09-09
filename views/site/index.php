<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="flex-container">
        <?php foreach ($model as $item): ?>
            <div class="flex-item">
                <h2><?=$item->title?></h2>

                <img src="/web/<?=$item->img?>" alt="">

                <p><?=$item->desc?></p>
                <?php if(!Yii::$app->user->isGuest): ?>
                    <p><a class="btn btn-default" href="/web/index.php?r=articles%2Fview&id=<?=$item->id?>">more &raquo;</a></p>
                <?php endif; ?>

            </div>
        <?php endforeach;?>
    </div>

</div>
