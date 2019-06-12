<?php


abstract class BaseLogHistory extends BaseObject  implements Persistent {


	
	protected static $peer;


	
	protected $id;


	
	protected $date = 0;


	
	protected $host = 0;


	
	protected $hits = 0;


	
	protected $download = 0;


	
	protected $files = '|';

	
	protected $alreadyInSave = false;

	
	protected $alreadyInValidation = false;

	
	public function getId()
	{

		return $this->id;
	}

	
	public function getDate()
	{

		return $this->date;
	}

	
	public function getHost()
	{

		return $this->host;
	}

	
	public function getHits()
	{

		return $this->hits;
	}

	
	public function getDownload()
	{

		return $this->download;
	}

	
	public function getFiles()
	{

		return $this->files;
	}

	
	public function setId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = LogHistoryPeer::ID;
		}

	} 
	
	public function setDate($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->date !== $v || $v === 0) {
			$this->date = $v;
			$this->modifiedColumns[] = LogHistoryPeer::DATE;
		}

	} 
	
	public function setHost($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->host !== $v || $v === 0) {
			$this->host = $v;
			$this->modifiedColumns[] = LogHistoryPeer::HOST;
		}

	} 
	
	public function setHits($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->hits !== $v || $v === 0) {
			$this->hits = $v;
			$this->modifiedColumns[] = LogHistoryPeer::HITS;
		}

	} 
	
	public function setDownload($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->download !== $v || $v === 0) {
			$this->download = $v;
			$this->modifiedColumns[] = LogHistoryPeer::DOWNLOAD;
		}

	} 
	
	public function setFiles($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->files !== $v || $v === '|') {
			$this->files = $v;
			$this->modifiedColumns[] = LogHistoryPeer::FILES;
		}

	} 
	
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->id = $rs->getInt($startcol + 0);

			$this->date = $rs->getInt($startcol + 1);

			$this->host = $rs->getInt($startcol + 2);

			$this->hits = $rs->getInt($startcol + 3);

			$this->download = $rs->getInt($startcol + 4);

			$this->files = $rs->getString($startcol + 5);

			$this->resetModified();

			$this->setNew(false);

						return $startcol + 6; 
		} catch (Exception $e) {
			throw new PropelException("Error populating LogHistory object", $e);
		}
	}

	
	public function delete($con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(LogHistoryPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			LogHistoryPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	
	public function save($con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(LogHistoryPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			$affectedRows = $this->doSave($con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	
	protected function doSave($con)
	{
		$affectedRows = 0; 		if (!$this->alreadyInSave) {
			$this->alreadyInSave = true;


						if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = LogHistoryPeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += LogHistoryPeer::doUpdate($this, $con);
				}
				$this->resetModified(); 			}

			$this->alreadyInSave = false;
		}
		return $affectedRows;
	} 
	
	protected $validationFailures = array();

	
	public function getValidationFailures()
	{
		return $this->validationFailures;
	}

	
	public function validate($columns = null)
	{
		$res = $this->doValidate($columns);
		if ($res === true) {
			$this->validationFailures = array();
			return true;
		} else {
			$this->validationFailures = $res;
			return false;
		}
	}

	
	protected function doValidate($columns = null)
	{
		if (!$this->alreadyInValidation) {
			$this->alreadyInValidation = true;
			$retval = null;

			$failureMap = array();


			if (($retval = LogHistoryPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}



			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = LogHistoryPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->getByPosition($pos);
	}

	
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getId();
				break;
			case 1:
				return $this->getDate();
				break;
			case 2:
				return $this->getHost();
				break;
			case 3:
				return $this->getHits();
				break;
			case 4:
				return $this->getDownload();
				break;
			case 5:
				return $this->getFiles();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = LogHistoryPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getDate(),
			$keys[2] => $this->getHost(),
			$keys[3] => $this->getHits(),
			$keys[4] => $this->getDownload(),
			$keys[5] => $this->getFiles(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = LogHistoryPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setDate($value);
				break;
			case 2:
				$this->setHost($value);
				break;
			case 3:
				$this->setHits($value);
				break;
			case 4:
				$this->setDownload($value);
				break;
			case 5:
				$this->setFiles($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = LogHistoryPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setDate($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setHost($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setHits($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setDownload($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setFiles($arr[$keys[5]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(LogHistoryPeer::DATABASE_NAME);

		if ($this->isColumnModified(LogHistoryPeer::ID)) $criteria->add(LogHistoryPeer::ID, $this->id);
		if ($this->isColumnModified(LogHistoryPeer::DATE)) $criteria->add(LogHistoryPeer::DATE, $this->date);
		if ($this->isColumnModified(LogHistoryPeer::HOST)) $criteria->add(LogHistoryPeer::HOST, $this->host);
		if ($this->isColumnModified(LogHistoryPeer::HITS)) $criteria->add(LogHistoryPeer::HITS, $this->hits);
		if ($this->isColumnModified(LogHistoryPeer::DOWNLOAD)) $criteria->add(LogHistoryPeer::DOWNLOAD, $this->download);
		if ($this->isColumnModified(LogHistoryPeer::FILES)) $criteria->add(LogHistoryPeer::FILES, $this->files);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(LogHistoryPeer::DATABASE_NAME);

		$criteria->add(LogHistoryPeer::ID, $this->id);

		return $criteria;
	}

	
	public function getPrimaryKey()
	{
		return $this->getId();
	}

	
	public function setPrimaryKey($key)
	{
		$this->setId($key);
	}

	
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setDate($this->date);

		$copyObj->setHost($this->host);

		$copyObj->setHits($this->hits);

		$copyObj->setDownload($this->download);

		$copyObj->setFiles($this->files);


		$copyObj->setNew(true);

		$copyObj->setId(NULL); 
	}

	
	public function copy($deepCopy = false)
	{
				$clazz = get_class($this);
		$copyObj = new $clazz();
		$this->copyInto($copyObj, $deepCopy);
		return $copyObj;
	}

	
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new LogHistoryPeer();
		}
		return self::$peer;
	}

} 