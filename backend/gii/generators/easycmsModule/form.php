<div class="module-form">
<?php
    echo $form->field($generator, 'name');
    echo $form->field($generator, 'title');
    echo $form->field($generator, 'type')->dropDownList(backend\gii\generators\easycmsModule\Generator::getTypes());
?>
</div>
