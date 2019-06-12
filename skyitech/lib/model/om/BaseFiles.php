<?php


abstract class BaseFiles extends BaseObject  implements Persistent {


	
	protected static $peer;


	
	protected $id;


	
	protected $file_name;


	
	protected $category_id = 0;


	
	protected $description;


	
	protected $singer;


	
	protected $tags;


	
	protected $size = 0;


	
	protected $today = 0;


	
	protected $download = 0;


	
	protected $extension;


	
	protected $ord = 0;


	
	protected $status = 'A';


	
	protected $url;


	
	protected $created_at;

	
	protected $aCategory;

	
	protected $collDownloadHistorys;

	
	protected $lastDownloadHistoryCriteria = null;

	
	protected $alreadyInSave = false;

	
	protected $alreadyInValidation = false;

	
	public function getId()
	{

		return $this->id;
	}

	
	public function getFileName()
	{

		return $this->file_name;
	}

	
	public function getCategoryId()
	{

		return $this->category_id;
	}

	
	public function getDescription()
	{

		return $this->description;
	}

	
	public function getSinger()
	{

		return $this->singer;
	}

	
	public function getTags()
	{

		return $this->tags;
	}

	
	public function getSize()
	{

		return $this->size;
	}

	
	public function getToday()
	{

		return $this->today;
	}

	
	public function getDownload()
	{

		return $this->download;
	}

	
	public function getExtension()
	{

		return $this->extension;
	}

	
	public function getOrd()
	{

		return $this->ord;
	}

	
	public function getStatus()
	{

		return $this->status;
	}

	
	public function getUrl()
	{

		return $this->url;
	}

	
	public function getCreatedAt($format = 'Y-m-d H:i:s')
	{

		if ($this->created_at === null || $this->created_at === '') {
			return null;
		} elseif (!is_int($this->created_at)) {
						$ts = strtotime($this->created_at);
			if ($ts === -1 || $ts === false) { 				throw new PropelException("Unable to parse value of [created_at] as date/time value: " . var_export($this->created_at, true));
			}
		} else {
			$ts = $this->created_at;
		}
		if ($format === null) {
			return $ts;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $ts);
		} else {
			return date($format, $ts);
		}
	}

	
	public function setId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = FilesPeer::ID;
		}

	} 
	
	public function setFileName($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->file_name !== $v) {
			$this->file_name = $v;
			$this->modifiedColumns[] = FilesPeer::FILE_NAME;
		}

	} 
	
	public function setCategoryId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->category_id !== $v || $v === 0) {
			$this->category_id = $v;
			$this->modifiedColumns[] = FilesPeer::CATEGORY_ID;
		}

		if ($this->aCategory !== null && $this->aCategory->getId() !== $v) {
			$this->aCategory = null;
		}

	} 
	
	public function setDescription($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->description !== $v) {
			$this->description = $v;
			$this->modifiedColumns[] = FilesPeer::DESCRIPTION;
		}

	} 
	
	public function setSinger($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->singer !== $v) {
			$this->singer = $v;
			$this->modifiedColumns[] = FilesPeer::SINGER;
		}

	} 
	
	public function setTags($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->tags !== $v) {
			$this->tags = $v;
			$this->modifiedColumns[] = FilesPeer::TAGS;
		}

	} 
	
	public function setSize($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->size !== $v || $v === 0) {
			$this->size = $v;
			$this->modifiedColumns[] = FilesPeer::SIZE;
		}

	} 
	
	public function setToday($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->today !== $v || $v === 0) {
			$this->today = $v;
			$this->modifiedColumns[] = FilesPeer::TODAY;
		}

	} 
	
	public function setDownload($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->download !== $v || $v === 0) {
			$this->download = $v;
			$this->modifiedColumns[] = FilesPeer::DOWNLOAD;
		}

	} 
	
	public function setExtension($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->extension !== $v) {
			$this->extension = $v;
			$this->modifiedColumns[] = FilesPeer::EXTENSION;
		}

	} 
	
	public function setOrd($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->ord !== $v || $v === 0) {
			$this->ord = $v;
			$this->modifiedColumns[] = FilesPeer::ORD;
		}

	} 
	
	public function setStatus($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->status !== $v || $v === 'A') {
			$this->status = $v;
			$this->modifiedColumns[] = FilesPeer::STATUS;
		}

	} 
	
	public function setUrl($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->url !== $v) {
			$this->url = $v;
			$this->modifiedColumns[] = FilesPeer::URL;
		}

	} 
	
	public function setCreatedAt($v)
	{

		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) { 				throw new PropelException("Unable to parse date/time value for [created_at] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->created_at !== $ts) {
			$this->created_at = $ts;
			$this->modifiedColumns[] = FilesPeer::CREATED_AT;
		}

	} 
	
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->id = $rs->getInt($startcol + 0);

			$this->file_name = $rs->getString($startcol + 1);

			$this->category_id = $rs->getInt($startcol + 2);

			$this->description = $rs->getString($startcol + 3);

			$this->singer = $rs->getString($startcol + 4);

			$this->tags = $rs->getString($startcol + 5);

			$this->size = $rs->getInt($startcol + 6);

			$this->today = $rs->getInt($startcol + 7);

			$this->download = $rs->getInt($startcol + 8);

			$this->extension = $rs->getString($startcol + 9);

			$this->ord = $rs->getInt($startcol + 10);

			$this->status = $rs->getString($startcol + 11);

			$this->url = $rs->getString($startcol + 12);

			$this->created_at = $rs->getTimestamp($startcol + 13, null);

			$this->resetModified();

			$this->setNew(false);

						return $startcol + 14; 
		} catch (Exception $e) {
			throw new PropelException("Error populating Files object", $e);
		}
	}

	
	public function delete($con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(FilesPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			FilesPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	
	public function save($con = null)
	{
    if ($this->isNew() && !$this->isColumnModified(FilesPeer::CREATED_AT))
    {
      $this->setCreatedAt(time());
    }

		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(FilesPeer::DATABASE_NAME);
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


												
			if ($this->aCategory !== null) {
				if ($this->aCategory->isModified()) {
					$affectedRows += $this->aCategory->save($con);
				}
				$this->setCategory($this->aCategory);
			}


						if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = FilesPeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += FilesPeer::doUpdate($this, $con);
				}
				$this->resetModified(); 			}

			if ($this->collDownloadHistorys !== null) {
				foreach($this->collDownloadHistorys as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

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


												
			if ($this->aCategory !== null) {
				if (!$this->aCategory->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aCategory->getValidationFailures());
				}
			}


			if (($retval = FilesPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collDownloadHistorys !== null) {
					foreach($this->collDownloadHistorys as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}


			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = FilesPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->getByPosition($pos);
	}

	
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getId();
				break;
			case 1:
				return $this->getFileName();
				break;
			case 2:
				return $this->getCategoryId();
				break;
			case 3:
				return $this->getDescription();
				break;
			case 4:
				return $this->getSinger();
				break;
			case 5:
				return $this->getTags();
				break;
			case 6:
				return $this->getSize();
				break;
			case 7:
				return $this->getToday();
				break;
			case 8:
				return $this->getDownload();
				break;
			case 9:
				return $this->getExtension();
				break;
			case 10:
				return $this->getOrd();
				break;
			case 11:
				return $this->getStatus();
				break;
			case 12:
				return $this->getUrl();
				break;
			case 13:
				return $this->getCreatedAt();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = FilesPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getFileName(),
			$keys[2] => $this->getCategoryId(),
			$keys[3] => $this->getDescription(),
			$keys[4] => $this->getSinger(),
			$keys[5] => $this->getTags(),
			$keys[6] => $this->getSize(),
			$keys[7] => $this->getToday(),
			$keys[8] => $this->getDownload(),
			$keys[9] => $this->getExtension(),
			$keys[10] => $this->getOrd(),
			$keys[11] => $this->getStatus(),
			$keys[12] => $this->getUrl(),
			$keys[13] => $this->getCreatedAt(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = FilesPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setFileName($value);
				break;
			case 2:
				$this->setCategoryId($value);
				break;
			case 3:
				$this->setDescription($value);
				break;
			case 4:
				$this->setSinger($value);
				break;
			case 5:
				$this->setTags($value);
				break;
			case 6:
				$this->setSize($value);
				break;
			case 7:
				$this->setToday($value);
				break;
			case 8:
				$this->setDownload($value);
				break;
			case 9:
				$this->setExtension($value);
				break;
			case 10:
				$this->setOrd($value);
				break;
			case 11:
				$this->setStatus($value);
				break;
			case 12:
				$this->setUrl($value);
				break;
			case 13:
				$this->setCreatedAt($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = FilesPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setFileName($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setCategoryId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setDescription($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setSinger($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setTags($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setSize($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setToday($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setDownload($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setExtension($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setOrd($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setStatus($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setUrl($arr[$keys[12]]);
		if (array_key_exists($keys[13], $arr)) $this->setCreatedAt($arr[$keys[13]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(FilesPeer::DATABASE_NAME);

		if ($this->isColumnModified(FilesPeer::ID)) $criteria->add(FilesPeer::ID, $this->id);
		if ($this->isColumnModified(FilesPeer::FILE_NAME)) $criteria->add(FilesPeer::FILE_NAME, $this->file_name);
		if ($this->isColumnModified(FilesPeer::CATEGORY_ID)) $criteria->add(FilesPeer::CATEGORY_ID, $this->category_id);
		if ($this->isColumnModified(FilesPeer::DESCRIPTION)) $criteria->add(FilesPeer::DESCRIPTION, $this->description);
		if ($this->isColumnModified(FilesPeer::SINGER)) $criteria->add(FilesPeer::SINGER, $this->singer);
		if ($this->isColumnModified(FilesPeer::TAGS)) $criteria->add(FilesPeer::TAGS, $this->tags);
		if ($this->isColumnModified(FilesPeer::SIZE)) $criteria->add(FilesPeer::SIZE, $this->size);
		if ($this->isColumnModified(FilesPeer::TODAY)) $criteria->add(FilesPeer::TODAY, $this->today);
		if ($this->isColumnModified(FilesPeer::DOWNLOAD)) $criteria->add(FilesPeer::DOWNLOAD, $this->download);
		if ($this->isColumnModified(FilesPeer::EXTENSION)) $criteria->add(FilesPeer::EXTENSION, $this->extension);
		if ($this->isColumnModified(FilesPeer::ORD)) $criteria->add(FilesPeer::ORD, $this->ord);
		if ($this->isColumnModified(FilesPeer::STATUS)) $criteria->add(FilesPeer::STATUS, $this->status);
		if ($this->isColumnModified(FilesPeer::URL)) $criteria->add(FilesPeer::URL, $this->url);
		if ($this->isColumnModified(FilesPeer::CREATED_AT)) $criteria->add(FilesPeer::CREATED_AT, $this->created_at);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(FilesPeer::DATABASE_NAME);

		$criteria->add(FilesPeer::ID, $this->id);

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

		$copyObj->setFileName($this->file_name);

		$copyObj->setCategoryId($this->category_id);

		$copyObj->setDescription($this->description);

		$copyObj->setSinger($this->singer);

		$copyObj->setTags($this->tags);

		$copyObj->setSize($this->size);

		$copyObj->setToday($this->today);

		$copyObj->setDownload($this->download);

		$copyObj->setExtension($this->extension);

		$copyObj->setOrd($this->ord);

		$copyObj->setStatus($this->status);

		$copyObj->setUrl($this->url);

		$copyObj->setCreatedAt($this->created_at);


		if ($deepCopy) {
									$copyObj->setNew(false);

			foreach($this->getDownloadHistorys() as $relObj) {
				$copyObj->addDownloadHistory($relObj->copy($deepCopy));
			}

		} 

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
			self::$peer = new FilesPeer();
		}
		return self::$peer;
	}

	
	public function setCategory($v)
	{


		if ($v === null) {
			$this->setCategoryId('0');
		} else {
			$this->setCategoryId($v->getId());
		}


		$this->aCategory = $v;
	}


	
	public function getCategory($con = null)
	{
		if ($this->aCategory === null && ($this->category_id !== null)) {
						include_once 'lib/model/om/BaseCategoryPeer.php';

			$this->aCategory = CategoryPeer::retrieveByPK($this->category_id, $con);

			
		}
		return $this->aCategory;
	}

	
	public function initDownloadHistorys()
	{
		if ($this->collDownloadHistorys === null) {
			$this->collDownloadHistorys = array();
		}
	}

	
	public function getDownloadHistorys($criteria = null, $con = null)
	{
				include_once 'lib/model/om/BaseDownloadHistoryPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collDownloadHistorys === null) {
			if ($this->isNew()) {
			   $this->collDownloadHistorys = array();
			} else {

				$criteria->add(DownloadHistoryPeer::FILE_ID, $this->getId());

				DownloadHistoryPeer::addSelectColumns($criteria);
				$this->collDownloadHistorys = DownloadHistoryPeer::doSelect($criteria, $con);
			}
		} else {
						if (!$this->isNew()) {
												

				$criteria->add(DownloadHistoryPeer::FILE_ID, $this->getId());

				DownloadHistoryPeer::addSelectColumns($criteria);
				if (!isset($this->lastDownloadHistoryCriteria) || !$this->lastDownloadHistoryCriteria->equals($criteria)) {
					$this->collDownloadHistorys = DownloadHistoryPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastDownloadHistoryCriteria = $criteria;
		return $this->collDownloadHistorys;
	}

	
	public function countDownloadHistorys($criteria = null, $distinct = false, $con = null)
	{
				include_once 'lib/model/om/BaseDownloadHistoryPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(DownloadHistoryPeer::FILE_ID, $this->getId());

		return DownloadHistoryPeer::doCount($criteria, $distinct, $con);
	}

	
	public function addDownloadHistory(DownloadHistory $l)
	{
		$this->collDownloadHistorys[] = $l;
		$l->setFiles($this);
	}

} 