<?php


abstract class BaseDownloadHistory extends BaseObject  implements Persistent {


	
	protected static $peer;


	
	protected $id;


	
	protected $date;


	
	protected $file_id;


	
	protected $extension;


	
	protected $hits = 0;

	
	protected $aFiles;

	
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

	
	public function getFileId()
	{

		return $this->file_id;
	}

	
	public function getExtension()
	{

		return $this->extension;
	}

	
	public function getHits()
	{

		return $this->hits;
	}

	
	public function setId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = DownloadHistoryPeer::ID;
		}

	} 
	
	public function setDate($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->date !== $v) {
			$this->date = $v;
			$this->modifiedColumns[] = DownloadHistoryPeer::DATE;
		}

	} 
	
	public function setFileId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->file_id !== $v) {
			$this->file_id = $v;
			$this->modifiedColumns[] = DownloadHistoryPeer::FILE_ID;
		}

		if ($this->aFiles !== null && $this->aFiles->getId() !== $v) {
			$this->aFiles = null;
		}

	} 
	
	public function setExtension($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->extension !== $v) {
			$this->extension = $v;
			$this->modifiedColumns[] = DownloadHistoryPeer::EXTENSION;
		}

	} 
	
	public function setHits($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->hits !== $v || $v === 0) {
			$this->hits = $v;
			$this->modifiedColumns[] = DownloadHistoryPeer::HITS;
		}

	} 
	
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->id = $rs->getInt($startcol + 0);

			$this->date = $rs->getString($startcol + 1);

			$this->file_id = $rs->getInt($startcol + 2);

			$this->extension = $rs->getString($startcol + 3);

			$this->hits = $rs->getInt($startcol + 4);

			$this->resetModified();

			$this->setNew(false);

						return $startcol + 5; 
		} catch (Exception $e) {
			throw new PropelException("Error populating DownloadHistory object", $e);
		}
	}

	
	public function delete($con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(DownloadHistoryPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			DownloadHistoryPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(DownloadHistoryPeer::DATABASE_NAME);
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


												
			if ($this->aFiles !== null) {
				if ($this->aFiles->isModified()) {
					$affectedRows += $this->aFiles->save($con);
				}
				$this->setFiles($this->aFiles);
			}


						if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = DownloadHistoryPeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += DownloadHistoryPeer::doUpdate($this, $con);
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


												
			if ($this->aFiles !== null) {
				if (!$this->aFiles->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFiles->getValidationFailures());
				}
			}


			if (($retval = DownloadHistoryPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}



			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = DownloadHistoryPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getFileId();
				break;
			case 3:
				return $this->getExtension();
				break;
			case 4:
				return $this->getHits();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = DownloadHistoryPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getDate(),
			$keys[2] => $this->getFileId(),
			$keys[3] => $this->getExtension(),
			$keys[4] => $this->getHits(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = DownloadHistoryPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setFileId($value);
				break;
			case 3:
				$this->setExtension($value);
				break;
			case 4:
				$this->setHits($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = DownloadHistoryPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setDate($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setFileId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setExtension($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setHits($arr[$keys[4]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(DownloadHistoryPeer::DATABASE_NAME);

		if ($this->isColumnModified(DownloadHistoryPeer::ID)) $criteria->add(DownloadHistoryPeer::ID, $this->id);
		if ($this->isColumnModified(DownloadHistoryPeer::DATE)) $criteria->add(DownloadHistoryPeer::DATE, $this->date);
		if ($this->isColumnModified(DownloadHistoryPeer::FILE_ID)) $criteria->add(DownloadHistoryPeer::FILE_ID, $this->file_id);
		if ($this->isColumnModified(DownloadHistoryPeer::EXTENSION)) $criteria->add(DownloadHistoryPeer::EXTENSION, $this->extension);
		if ($this->isColumnModified(DownloadHistoryPeer::HITS)) $criteria->add(DownloadHistoryPeer::HITS, $this->hits);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(DownloadHistoryPeer::DATABASE_NAME);

		$criteria->add(DownloadHistoryPeer::ID, $this->id);

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

		$copyObj->setFileId($this->file_id);

		$copyObj->setExtension($this->extension);

		$copyObj->setHits($this->hits);


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
			self::$peer = new DownloadHistoryPeer();
		}
		return self::$peer;
	}

	
	public function setFiles($v)
	{


		if ($v === null) {
			$this->setFileId(NULL);
		} else {
			$this->setFileId($v->getId());
		}


		$this->aFiles = $v;
	}


	
	public function getFiles($con = null)
	{
		if ($this->aFiles === null && ($this->file_id !== null)) {
						include_once 'lib/model/om/BaseFilesPeer.php';

			$this->aFiles = FilesPeer::retrieveByPK($this->file_id, $con);

			
		}
		return $this->aFiles;
	}

} 