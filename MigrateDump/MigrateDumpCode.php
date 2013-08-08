<?php

/**
 * Description of MigrateCode
 *
 * @author MihanEntalpo
 */
class MigrateDumpCode extends CCodeModel
{

	//public $_migrateName;
	public $migrateName;
	public $tableName;
	public $undoSql;
	public $undoTrancate=true;
	public $_migrateName;

	public function rules()
	{
		return array_merge(parent::rules(), array(
			array('tableName', 'required'),
			array('migrateName', 'autoFill'),
			array('_migrateName','match','pattern' => '#m[0-9]{6}_[0-9]{6}_[a-zA-Z0-9\._]+#i'),
			array('tableName','tableExists'),
			array('undoTrancate', 'boolean'),
			array('undoSql' , 'safe'),
		));
	}

	public function autoFill($attribute,$params)
	{
		$v = $this->$attribute;
		if (!preg_match('#[^a-zA-Z_][a-zA-Z]+#i',$v))
		{
			$this->$attribute = "fill_table_" . $this->tableName;
		}
	}

	public function tableExists($attribute,$params)
	{
		$t = $this->$attribute;
		$table = H::tblWithPrefix($t);
		if (!in_array($table, Yii::app()->db->schema->getTableNames()))
		{
			$table = H::tblWithPrefix("{{" . $t . "}}");
			if (!in_array($table, Yii::app()->db->schema->getTableNames()))
			{
				$this->addError($attribute,'Указанная таблица не существует');
				return false;
			}
		}
		$this->$attribute = $table;

	}

	public function attributeLabels()
	{
		return array_merge(parent::attributeLabels(), array(
			'migrateName' => 'Имя класса создаваемой миграции',
			'undoSql'=>'Sql выполняемый при откате миграции (если не выбрано стирание)',
			'undoTrancate' => 'При стирании полностью очистить таблицу',
			'tableName'=>'Таблица',
		));
	}

	public function prepare()
	{
		//$this->_migrateName = $this->_migrateName ?: ('m' . date('ymd_His_')
		//. ($this->migrateName ?: "load_dump_of_" . H::tblWithPrefix($this->tableName)));

		$this->_migrateName = $this->_migrateName
			? $this->_migrateName
			: 'm' . date('ymd_His_') . $this->migrateName;

		$path = Yii::getPathOfAlias('application.migrations.' . $this->_migrateName) . '.php';
		$code = $this->render($this->templatepath . '/migrate.php');

//		Y::dump($path);

		$this->files[] = new CCodeFile($path, $code);
	}

}