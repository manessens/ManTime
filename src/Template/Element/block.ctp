<?php if ($user->role >= $auth): ?>
    <div class="col-xs-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <?= $title ?>
            </div>
            <div class="panel-body">
                <?php
                    if (isset($img)) {
                        echo $this->Html->image($img, ['alt' => $content]);
                    }else{
                        echo $content;
                    }
                 ?>
            </div>
        </div>
    </div>
<?php endif; ?>
