<h1>MigrateDump generator</h1>

<?php $form=$this->beginWidget('CCodeForm', array('model'=>$model)); ?>


	<?php echo $form->hiddenField($model,'_migrateName'); ?>

	<!--<div class="row">-->
        <?php echo $form->labelEx($model,'tableName'); ?>
        <?php echo $form->textField($model,'tableName',array('size'=>65)); ?>
        <?php echo $form->error($model,'tableName'); ?>
    <!--</div>-->

    <!--<div class="row">-->
        <?php echo $form->labelEx($model,'migrateName'); ?>
        <?php echo $form->textField($model,'migrateName',array('size'=>65,'placeholder'=>'Можно не писать')); ?>
        <?php echo $form->error($model,'migrateName'); ?>
    <!--</div>-->

    <!--<div class="row">-->
        <?php echo $form->labelEx($model,'undoTrancate'); ?>
        <?php echo $form->checkBox($model,'undoTrancate'); ?>
        <?php echo $form->error($model,'undoTrancate'); ?>
    <!--</div>-->

   <!--<div class="row">-->
        <?php echo $form->labelEx($model,'undoSql'); ?>
        <?php echo $form->textArea($model,'undoSql',array('style'=>'width:500px;height:70px;')); ?>
        <?php echo $form->error($model,'undoSql'); ?>
    <!--</div>-->


<?php $this->endWidget(); ?>