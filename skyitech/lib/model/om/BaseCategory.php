<?php


abstract class BaseCategory extends BaseObject  implements Persistent {


	
	protected static $peer;


	
	protected $id;


	
	protected $category_name;


	
	protected $title;


	
	protected $description;


	
	protected $parents = '|';


	
	protected $parentsarray;


	
	protected $status = 'A';


	
	protected $child = 'N';


	
	protected $list_ord = 0;


	
	protected $ord = 0;


	
	protected $flag_new = 0;


	
	protected $flag_updated = 0;


	
	protected $flag_hot = 0;


	
	protected $files = 0;


	
	protected $url;

	
	protected $collFiless;

	
	protected $lastFilesCriteria = null;

	
	protected $alreadyInSave = false;

	
	protected $alreadyInValidation = false;

	
	public function getId()
	{

		return $this->id;
	}

	
	public function getCategoryName()
	{

		return $this->category_name;
	}

	
	public function getTitle()
	{

		return $this->title;
	}

	
	public function getDescription()
	{

		return $this->description;
	}

	
	public function getParents()
	{

		return $this->parents;
	}

	
	public function getParentsarray()
	{

		return $this->parentsarray;
	}

	
	public function getStatus()
	{

		return $this->status;
	}

	
	public function getChild()
	{

		return $this->child;
	}

	
	public function getListOrd()
	{

		return $this->list_ord;
	}

	
	public function getOrd()
	{

		return $this->ord;
	}

	
	public function getFlagNew()
	{

		return $this->flag_new;
	}

	
	public function getFlagUpdated()
	{

		return $this->flag_updated;
	}

	
	public function getFlagHot()
	{

		return $this->flag_hot;
	}

	
	public function getFiles()
	{

		return $this->files;
	}

	
	public function getUrl()
	{

		return $this->url;
	}

	
	public function setId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = CategoryPeer::ID;
		}

	} 
	
	public function setCategoryName($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->category_name !== $v) {
			$this->category_name = $v;
			$this->modifiedColumns[] = CategoryPeer::CATEGORY_NAME;
		}

	} 
	
	public function setTitle($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->title !== $v) {
			$this->title = $v;
			$this->modifiedColumns[] = CategoryPeer::TITLE;
		}

	} 
	
	public function setDescription($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->description !== $v) {
			$this->description = $v;
			$this->modifiedColumns[] = CategoryPeer::DESCRIPTION;
		}

	} 
	
	public function setParents($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->parents !== $v || $v === '|') {
			$this->parents = $v;
			$this->modifiedColumns[] = CategoryPeer::PARENTS;
		}

	} 
	
	public function setParentsarray($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->parentsarray !== $v) {
			$this->parentsarray = $v;
			$this->modifiedColumns[] = CategoryPeer::PARENTSARRAY;
		}

	} 
	
	public function setStatus($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->status !== $v || $v === 'A') {
			$this->status = $v;
			$this->modifiedColumns[] = CategoryPeer::STATUS;
		}

	} 
	
	public function setChild($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->child !== $v || $v === 'N') {
			$this->child = $v;
			$this->modifiedColumns[] = CategoryPeer::CHILD;
		}

	} 
	
	public function setListOrd($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->list_ord !== $v || $v === 0) {
			$this->list_ord = $v;
			$this->modifiedColumns[] = CategoryPeer::LIST_ORD;
		}

	} 
	
	public function setOrd($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->ord !== $v || $v === 0) {
			$this->ord = $v;
			$this->modifiedColumns[] = CategoryPeer::ORD;
		}

	} 
	
	public function setFlagNew($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->flag_new !== $v || $v === 0) {
			$this->flag_new = $v;
			$this->modifiedColumns[] = CategoryPeer::FLAG_NEW;
		}

	} 
	
	public function setFlagUpdated($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->flag_updated !== $v || $v === 0) {
			$this->flag_updated = $v;
			$this->modifiedColumns[] = CategoryPeer::FLAG_UPDATED;
		}

	} 
	
	public function setFlagHot($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->flag_hot !== $v || $v === 0) {
			$this->flag_hot = $v;
			$this->modifiedColumns[] = CategoryPeer::FLAG_HOT;
		}

	} 
	
	public function setFiles($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->files !== $v || $v === 0) {
			$this->files = $v;
			$this->modifiedColumns[] = CategoryPeer::FILES;
		}

	} 
	
	public function setUrl($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->url !== $v) {
			$this->url = $v;
			$this->modifiedColumns[] = CategoryPeer::URL;
		}

	} 
	
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->id = $rs->getInt($startcol + 0);

			$this->category_name = $rs->getString($startcol + 1);

			$this->title = $rs->getString($startcol + 2);

			$this->description = $rs->getString($startcol + 3);

			$this->parents = $rs->getString($startcol + 4);

			$this->parentsarray = $rs->getString($startcol + 5);

			$this->status = $rs->getString($startcol + 6);

			$this->child = $rs->getString($startcol + 7);

			$this->list_ord = $rs->getInt($startcol + 8);

			$this->ord = $rs->getInt($startcol + 9);

			$this->flag_new = $rs->getInt($startcol + 10);

			$this->flag_updated = $rs->getInt($startcol + 11);

			$this->flag_hot = $rs->getInt($startcol + 12);

			$this->files = $rs->getInt($startcol + 13);

			$this->url = $rs->getString($startcol + 14);

			$this->resetModified();

			$this->setNew(false);

						return $startcol + 15; 
		} catch (Exception $e) {
			throw new PropelException("Error populating Category object", $e);
		}
	}

	
	public function delete($con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(CategoryPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			CategoryPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(CategoryPeer::DATABASE_NAME);
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
					$pk = CategoryPeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += CategoryPeer::doUpdate($this, $con);
				}
				$this->resetModified(); 			}

			if ($this->collFiless !== null) {
				foreach($this->collFiless as $referrerFK) {
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


			if (($retval = CategoryPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collFiless !== null) {
					foreach($this->collFiless as $referrerFK) {
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
		$pos = CategoryPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->getByPosition($pos);
	}

	
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getId();
				break;
			case 1:
				return $this->getCategoryName();
				break;
			case 2:
				return $this->getTitle();
				break;
			case 3:
				return $this->getDescription();
				break;
			case 4:
				return $this->getParents();
				break;
			case 5:
				return $this->getParentsarray();
				break;
			case 6:
				return $this->getStatus();
				break;
			case 7:
				return $this->getChild();
				break;
			case 8:
				return $this->getListOrd();
				break;
			case 9:
				return $this->getOrd();
				break;
			case 10:
				return $this->getFlagNew();
				break;
			case 11:
				return $this->getFlagUpdated();
				break;
			case 12:
				return $this->getFlagHot();
				break;
			case 13:
				return $this->getFiles();
				break;
			case 14:
				return $this->getUrl();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = CategoryPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getCategoryName(),
			$keys[2] => $this->getTitle(),
			$keys[3] => $this->getDescription(),
			$keys[4] => $this->getParents(),
			$keys[5] => $this->getParentsarray(),
			$keys[6] => $this->getStatus(),
			$keys[7] => $this->getChild(),
			$keys[8] => $this->getListOrd(),
			$keys[9] => $this->getOrd(),
			$keys[10] => $this->getFlagNew(),
			$keys[11] => $this->getFlagUpdated(),
			$keys[12] => $this->getFlagHot(),
			$keys[13] => $this->getFiles(),
			$keys[14] => $this->getUrl(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = CategoryPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setCategoryName($value);
				break;
			case 2:
				$this->setTitle($value);
				break;
			case 3:
				$this->setDescription($value);
				break;
			case 4:
				$this->setParents($value);
				break;
			case 5:
				$this->setParentsarray($value);
				break;
			case 6:
				$this->setStatus($value);
				break;
			case 7:
				$this->setChild($value);
				break;
			case 8:
				$this->setListOrd($value);
				break;
			case 9:
				$this->setOrd($value);
				break;
			case 10:
				$this->setFlagNew($value);
				break;
			case 11:
				$this->setFlagUpdated($value);
				break;
			case 12:
				$this->setFlagHot($value);
				break;
			case 13:
				$this->setFiles($value);
				break;
			case 14:
				$this->setUrl($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = CategoryPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setCategoryName($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setTitle($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setDescription($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setParents($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setParentsarray($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setStatus($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setChild($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setListOrd($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setOrd($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setFlagNew($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setFlagUpdated($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setFlagHot($arr[$keys[12]]);
		if (array_key_exists($keys[13], $arr)) $this->setFiles($arr[$keys[13]]);
		if (array_key_exists($keys[14], $arr)) $this->setUrl($arr[$keys[14]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(CategoryPeer::DATABASE_NAME);

		if ($this->isColumnModified(CategoryPeer::ID)) $criteria->add(CategoryPeer::ID, $this->id);
		if ($this->isColumnModified(CategoryPeer::CATEGORY_NAME)) $criteria->add(CategoryPeer::CATEGORY_NAME, $this->category_name);
		if ($this->isColumnModified(CategoryPeer::TITLE)) $criteria->add(CategoryPeer::TITLE, $this->title);
		if ($this->isColumnModified(CategoryPeer::DESCRIPTION)) $criteria->add(CategoryPeer::DESCRIPTION, $this->description);
		if ($this->isColumnModified(CategoryPeer::PARENTS)) $criteria->add(CategoryPeer::PARENTS, $this->parents);
		if ($this->isColumnModified(CategoryPeer::PARENTSARRAY)) $criteria->add(CategoryPeer::PARENTSARRAY, $this->parentsarray);
		if ($this->isColumnModified(CategoryPeer::STATUS)) $criteria->add(CategoryPeer::STATUS, $this->status);
		if ($this->isColumnModified(CategoryPeer::CHILD)) $criteria->add(CategoryPeer::CHILD, $this->child);
		if ($this->isColumnModified(CategoryPeer::LIST_ORD)) $criteria->add(CategoryPeer::LIST_ORD, $this->list_ord);
		if ($this->isColumnModified(CategoryPeer::ORD)) $criteria->add(CategoryPeer::ORD, $this->ord);
		if ($this->isColumnModified(CategoryPeer::FLAG_NEW)) $criteria->add(CategoryPeer::FLAG_NEW, $this->flag_new);
		if ($this->isColumnModified(CategoryPeer::FLAG_UPDATED)) $criteria->add(CategoryPeer::FLAG_UPDATED, $this->flag_updated);
		if ($this->isColumnModified(CategoryPeer::FLAG_HOT)) $criteria->add(CategoryPeer::FLAG_HOT, $this->flag_hot);
		if ($this->isColumnModified(CategoryPeer::FILES)) $criteria->add(CategoryPeer::FILES, $this->files);
		if ($this->isColumnModified(CategoryPeer::URL)) $criteria->add(CategoryPeer::URL, $this->url);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(CategoryPeer::DATABASE_NAME);

		$criteria->add(CategoryPeer::ID, $this->id);

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

		$copyObj->setCategoryName($this->category_name);

		$copyObj->setTitle($this->title);

		$copyObj->setDescription($this->description);

		$copyObj->setParents($this->parents);

		$copyObj->setParentsarray($this->parentsarray);

		$copyObj->setStatus($this->status);

		$copyObj->setChild($this->child);

		$copyObj->setListOrd($this->list_ord);

		$copyObj->setOrd($this->ord);

		$copyObj->setFlagNew($this->flag_new);

		$copyObj->setFlagUpdated($this->flag_updated);

		$copyObj->setFlagHot($this->flag_hot);

		$copyObj->setFiles($this->files);

		$copyObj->setUrl($this->url);


		if ($deepCopy) {
									$copyObj->setNew(false);

			foreach($this->getFiless() as $relObj) {
				$copyObj->addFiles($relObj->copy($deepCopy));
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
			self::$peer = new CategoryPeer();
		}
		return self::$peer;
	}

	
	public function initFiless()
	{
		if ($this->collFiless === null) {
			$this->collFiless = array();
		}
	}

	
	public function getFiless($criteria = null, $con = null)
	{
				include_once 'lib/model/om/BaseFilesPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFiless === null) {
			if ($this->isNew()) {
			   $this->collFiless = array();
			} else {

				$criteria->add(FilesPeer::CATEGORY_ID, $this->getId());

				FilesPeer::addSelectColumns($criteria);
				$this->collFiless = FilesPeer::doSelect($criteria, $con);
			}
		} else {
						if (!$this->isNew()) {
												

				$criteria->add(FilesPeer::CATEGORY_ID, $this->getId());

				FilesPeer::addSelectColumns($criteria);
				if (!isset($this->lastFilesCriteria) || !$this->lastFilesCriteria->equals($criteria)) {
					$this->collFiless = FilesPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastFilesCriteria = $criteria;
		return $this->collFiless;
	}

	
	public function countFiless($criteria = null, $distinct = false, $con = null)
	{
				include_once 'lib/model/om/BaseFilesPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(FilesPeer::CATEGORY_ID, $this->getId());

		return FilesPeer::doCount($criteria, $distinct, $con);
	}

	
	public function addFiles(Files $l)
	{
		$this->collFiless[] = $l;
		$l->setCategory($this);
	}

} 