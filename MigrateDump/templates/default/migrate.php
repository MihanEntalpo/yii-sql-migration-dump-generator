<?php echo '<?php'; ?>

class <?php echo $this->_migrateName; ?> extends CDbMigration
{
	public function up()
	{
		<?php
			$cmd = Yii::app()->db->createCommand("SELECT * FROM " . $this->tableName);
			$data = $cmd->queryAll();

			?>

			Yii::app()->db->createCommand("TRUNCATE TABLE <?=$this->tableName?>;" )->execute();


			<?php

			if (count($data)>0) $row = $data[0];
			$fields = array_keys($row);

			$vals = array();
			foreach ($data as $row)
			{
				$cmd = "INSERT INTO " . $this->tableName . " ( `" .  implode("`,`",$fields) . "`) VALUES ";
				$vals = array();
				foreach($row as $k=>$v)
				{
					$val = $v;
					if (!is_integer($val))
					{
						$val = "'" . str_replace(array('$','"',"'"),array('\$','\"','\''),$val) . "'";
					}

					$vals[] = $val;
				}
				$cmd .= "(" . implode(',',$vals) . ")";
				?>
				Yii::app()->db->createCommand("<?=$cmd?>;\n\n" )->execute();
				<?php
			}
			?>
	}

	public function down()
	{
	  <?php if ($this->undoTrancate):?>
	  	echo "Truncating table <?=$this->tableName?>\n";
		echo "Yii::app()->db->createCommand('TRUNCATE TABLE <?=$this->tableName?>')->execute();";
	  <?php elseif ($this->undoSql):?>
		echo "Executing Sql rollback statement\n";
		$sql = <<<TEXT
				<?=$this->undoSql?>


TEXT;
		Yii::app()->db->createCommand($sql)->execute();
		<?php
	endif;
?>



	}


}
