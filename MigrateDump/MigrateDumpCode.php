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
			array('tableName,migrateName', 'required'),
			array('migrateName', 'match', 'pattern' => '/^\w+$/'),
			array('tableName','tableExists'),
			array('undoTrancate', 'boolean'),
		));
	}

	public function tableExists($attribute,$params)
	{
		$t = $this->$attribute;
		$table = H::tblWithPrefix($t);
		if (!in_array($table, Yii::app()->db->schema->getTableNames()))
		{
			$this->addError($attribute,'Указанная таблица не существует');
		}
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