<?php

class categoryActions extends sfActions
{
  public function executeIndex()
  {
    return $this->forward('category', 'list');
  }

  public function executeFullinfo()
  {
  	if($this->getUser()->getAttribute('fullInfoOnOff') == 'show')
			$this->getUser()->setAttribute('fullInfoOnOff', 'hide');
		else
			$this->getUser()->setAttribute('fullInfoOnOff', 'show');

    return $this->redirect($_SERVER['HTTP_REFERER']);
  }

  public function executeSetOrder()
  {
  	if($this->getUser()->getAttribute('setOrderOnOff') == 'show')
			$this->getUser()->setAttribute('setOrderOnOff', 'hide');
		else
			$this->getUser()->setAttribute('setOrderOnOff', 'show');

    return $this->redirect($_SERVER['HTTP_REFERER']);
  }

  public function executeMoveoption()
  {
  	if($this->getUser()->getAttribute('MoveOnOff') == 'show')
			$this->getUser()->setAttribute('MoveOnOff', 'hide');
		else
			$this->getUser()->setAttribute('MoveOnOff', 'show');

    return $this->redirect($_SERVER['HTTP_REFERER']);
  }


  public function executeList()
  {
		if(!stristr($_SERVER['HTTP_HOST'],'frashmusic.club'))
			if(!stristr($_SERVER['HTTP_HOST'],'frashmusic.sky'))
				exit;

		if($this->getUser()->hasAttribute('recperpage_cl') == FALSE)
			$this->getUser()->setAttribute('recperpage_cl', 10);
		if($this->getUser()->hasAttribute('fullInfoOnOff') == FALSE)
			$this->getUser()->setAttribute('fullInfoOnOff', 'hide');
		if($this->getUser()->hasAttribute('MoveOnOff') == FALSE)
			$this->getUser()->setAttribute('MoveOnOff', 'hide');
		if($this->getUser()->hasAttribute('setOrderOnOff') == FALSE)
			$this->getUser()->setAttribute('setOrderOnOff', 'hide');

	  $this->recperpage = $this->getUser()->getAttribute('recperpage_cl');
	  $this->fullInfoOnOff = $this->getUser()->getAttribute('fullInfoOnOff');
	  $this->setOrderOnOff = $this->getUser()->getAttribute('setOrderOnOff');
	  $this->MoveOnOff = $this->getUser()->getAttribute('MoveOnOff');

		$this->parent = '';
		$this->parentName = '';
		$this->categoryPath = '';
  	if($this->getRequestParameter('parent')):
  		$this->parent = $this->getRequestParameter('parent');
  		$this->parentCategory = CategoryPeer::retrieveByPk($this->getRequestParameter('parent'));
		 	$c = new Criteria();
			$c->add(CategoryPeer::PARENTS, '%|'.$this->getRequestParameter('parent').'|', Criteria::LIKE);
	 		$c->addAscendingOrderByColumn(CategoryPeer::ORD);
	 		$c->addDescendingOrderByColumn(CategoryPeer::ID);
  	elseif($this->getRequestParameter('categorysearch')):
		 	$c = new Criteria();
			$c->add(CategoryPeer::CATEGORY_NAME, '%'.$this->getRequestParameter('categorysearch').'%', Criteria::LIKE);
	 		$c->addAscendingOrderByColumn(CategoryPeer::ORD);
	 		$c->addDescendingOrderByColumn(CategoryPeer::ID);
	 		//print_r(CategoryPeer::doSelectRs($c)); exit;
  	elseif($this->getRequestParameter('newupdatedhot')):
		 	$c = new Criteria();
				$cton1 = $c->getNewCriterion(CategoryPeer::FLAG_NEW, 0, Criteria::NOT_EQUAL);
				$cton2 = $c->getNewCriterion(CategoryPeer::FLAG_UPDATED, 0, Criteria::NOT_EQUAL);
				$cton3 = $c->getNewCriterion(CategoryPeer::FLAG_HOT, 0, Criteria::NOT_EQUAL);
				// combine them
				$cton1->addOr($cton2);
				$cton1->addOr($cton3);
	 		$c->add($cton1);
	 		$c->addDescendingOrderByColumn(CategoryPeer::ID);
	 		//print_r(CategoryPeer::doSelectRs($c)); exit;
  	else:
		 	$c = new Criteria();
			$c->add(CategoryPeer::PARENTS, '|');
			$c->addAscendingOrderByColumn(CategoryPeer::ORD);
			$c->addDescendingOrderByColumn(CategoryPeer::ID);
  	endif;

		if($this->setOrderOnOff == "hide"){
	    $pager = new sfPropelPager('Category', $this->getUser()->getAttribute('recperpage_cl'));
	    $pager->setCriteria($c);
	    $pager->setPage($this->getRequestParameter('page', 1));
	    $pager->init();
	    $this->pager = $pager;
	    $this->categorys = $pager->getResults();
  	}
  	else
  		$this->categorys = CategoryPeer::doSelect($c);

  }

	public function executeEditInLine()
	{
	    //load the proper page to work with
	    $page = CategoryPeer::retrieveByPk($this->getRequestParameter('id'));
	
	    //determine the proper get and set methods to be used
	    $set_func = 'set'.$this->getRequestParameter('attribute');
	    $get_func = 'get'.$this->getRequestParameter('attribute');
	
	    //same the current version of your page before performing the update
	    //$page->saveCurrentVersion();
	
	    //set the new value, save it and return it back for page display
	    $page->$set_func($this->getRequestParameter('value'));
	    $page->save();
	    die($page->$get_func());
	}

  public function executeShow()
  {
    $this->category = CategoryPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($this->category);
  }

  public function executeCreate()
  {
    $this->category = new Category();
		$this->parent = '';
    $this->category->setOrd(0);
  	if($this->getRequestParameter('parent')):
  		$this->parent = $this->getRequestParameter('parent');
	    $c = CategoryPeer::retrieveByPk($this->getRequestParameter('parent'));
	    $this->category->setParents($c->getParents().$this->getRequestParameter('parent').'|');
	    $this->category->setParentsarray( $c->getParentsarray().'||'.$this->getRequestParameter('parent').'||'.CategoryPeer::getCategoryName($this->getRequestParameter('parent')) );
		endif;
	    $this->setTemplate('edit');
  }

  public function executeEdit()
  {
    $this->category = CategoryPeer::retrieveByPk($this->getRequestParameter('id'));
		$this->parent = CategoryPeer::getParentId($this->category->getParents());
    $this->forward404Unless($this->category);
  }

  public function executeUpdate()
  {
    if (!$this->getRequestParameter('id'))
    {
      $category = new Category();
	    $category->setParents($this->getRequestParameter('parents'));
	    $category->setParentsarray($this->getRequestParameter('parentsarray'));
    }
    else
    {
      $category = CategoryPeer::retrieveByPk($this->getRequestParameter('id'));
      $this->forward404Unless($category);
    }

    $category->setId($this->getRequestParameter('id'));
    if($this->getRequestParameter('dirCatch')==true){
	    $category->setTitle('');
	    $category->setDescription('');
    	$category->setCategoryName( base64_decode($this->getRequestParameter('category_name')) );
	    $category->setListOrd(0);
	    $category->setOrd(0);
	    $category->setStatus('A');
	    $category->setFlagNew(0);
	    $category->setFlagUpdated(0);
	    $category->setFlagHot(0);
    }
    else{
    	$catOldName = $category->getCategoryName();
	    $category->setCategoryName($this->getRequestParameter('category_name'));
	    $category->setTitle($this->getRequestParameter('title'));
	    $category->setDescription($this->getRequestParameter('description'));
	    $category->setListOrd($this->getRequestParameter('list_ord') ? $this->getRequestParameter('list_ord') : 0);
	    $category->setOrd($this->getRequestParameter('ord') ? $this->getRequestParameter('ord') : 0);
	    $category->setStatus($this->getRequestParameter('status')=='A'?'A':'B');
	    $category->setFlagNew($this->getRequestParameter('flag_new'));
	    $category->setFlagUpdated($this->getRequestParameter('flag_updated'));
	    $category->setFlagHot($this->getRequestParameter('flag_hot'));

	    $category->setUrl($this->getRequestParameter('url'));
	    if($this->getRequestParameter('url')!='')
		    $category->setChild('U');
	    if($category->getChild()=='U' && $this->getRequestParameter('url')=='')
		    $category->setChild('N');
		    
		  if($category->getChild()=="D" && $catOldName != $this->getRequestParameter('category_name')){
	 	    $sql = "UPDATE category SET parentsarray=replace(parentsarray , '||".$category->getId()."||".$catOldName."' , '||".$category->getId()."||".$this->getRequestParameter('category_name')."') WHERE parents like '".$category->getParents().$category->getId()."|%'";
		    //echo $sql; exit;
				$con = Propel::getConnection();
		    $rs = $con->executeQuery($sql, ResultSet::FETCHMODE_NUM);
		  }

	  }
    $category->save();


		/*
		* SKYiTech :: update parent category
		*			hasChild->true
		*/
		if($this->getRequestParameter('parent'))
		{
		    $parentCategory = CategoryPeer::retrieveByPk($this->getRequestParameter('parent'));
		    $parentCategory->setChild('D');
		    $parentCategory->save();
		}

		/*
		* SKYiTech :: create thumb images if file type is not wallpaper and thumb image uploaded
		*/
		$position = "center";
		if($this->getRequestParameter('position')=='TL')
			$position = "top-left";
		if($this->getRequestParameter('position')=='TC')
			$position = "top-center";
		if($this->getRequestParameter('position')=='TR')
			$position = "top-right";

		if($this->getRequestParameter('position')=='ML')
			$position = "middle-left";
		if($this->getRequestParameter('position')=='M')
			$position = "middle-center";
		if($this->getRequestParameter('position')=='MR')
			$position = "middle-right";

		if($this->getRequestParameter('position')=='BL')
			$position = "bottom-left";
		if($this->getRequestParameter('position')=='B')
			$position = "bottom-center";
		if($this->getRequestParameter('position')=='BR')
			$position = "bottom-right";

		if($this->getRequestParameter('logo'))
			$logo = $this->getRequestParameter('logo');
		else
			$logo = 'logo_c1.png';

		$thumbSave = sfConfig::get('sf_upload_dir').'/thumb/c/'.$category->getId().'_0.jpg';

		if($this->getRequest()->getFileSize('thumb_name') > 0)
		{
			if(!is_dir(sfConfig::get('sf_upload_dir').'/thumb/c'))
				mkdir(sfConfig::get('sf_upload_dir').'/thumb/c');

			$this->getRequest()->moveFile('thumb_name', $thumbSave);
			myUser::catThumb($thumbSave, $category->getId(), $this->getRequestParameter('thumb_square'), $logo, $position);

		}
		elseif($this->getRequestParameter('thumb_url_path'))
		{
			if(!is_dir(sfConfig::get('sf_upload_dir').'/thumb/c'))
				mkdir(sfConfig::get('sf_upload_dir').'/thumb/c');
				
			myUser::urlCopy($this->getRequestParameter('thumb_url_path'), $thumbSave);
			myUser::catThumb($thumbSave, $category->getId(), $this->getRequestParameter('thumb_square'), $logo, $position);
		}
		elseif($this->getRequestParameter('position_force')=='yes'){
			myUser::catThumb($thumbSave, $category->getId(), $this->getRequestParameter('thumb_square'), $logo, $position);
		}
		if($this->getRequestParameter('dirCatch')==true){
			echo $this->getRequestParameter('category_name');
			exit;
		}

			/* write mobile settings
			**/
			$writeFile = '<?php '.chr(13);
			$c = new Criteria();
			$c->add(CategoryPeer::PARENTS, '|');
			//$c->add(SettingPeer::DEVICE, 'm', Criteria::NOT_EQUAL);
			foreach(CategoryPeer::doSelect($c) as $categoryRow):
				$catIds[] = $categoryRow->getId();
				$catNames[] = $categoryRow->getCategoryName();
				$writeFile .= '$categoryCombo['.$categoryRow->getId().']="'.$categoryRow->getCategoryName().'";'.chr(13);
			endforeach;
			$writeFile .= '?>';

			$selectFile = 'category.php';
			$filenm = sfConfig::get('sf_upload_dir')."/".$selectFile;
			if(file_exists($filenm))
				unlink($filenm);
			$handle = fopen($filenm, 'w');
			fwrite($handle, $writeFile);
			fclose($handle);
	
  	return $this->redirect('category/list?parent='.$this->getRequestParameter('parent'));
  }


  public function executeMove()
  {
  }
  public function executeMoveCategory()
  {
  	if(!$this->getRequestParameter('id')){
  		echo '<span class="msg error">Source category is not defined...</span>';
  		exit;
  	}
    $category = CategoryPeer::retrieveByPk($this->getRequestParameter('id'));
   	$this->forward404Unless($category);
   	if($category->getParents()=='|' && $this->getRequestParameter('movetoid')==0){
   		echo '<span class="msg error">Ohh man... Source Category is Already Home Category...</span>';
   		exit;
   	}
   	if($this->getRequestParameter('id')==$this->getRequestParameter('movetoid')){
   		echo '<span class="msg error">Are you kidding with me?? your source and taregt category both are same man..!!!?</span>';
   		exit;
   	}

   	if($category->getParents()!='|')
			$parentId = CategoryPeer::getParentId($category->getParents());
		else
			$parentId = false;

   	/* 
   		move to root
   	*/
   	if($this->getRequestParameter('movetoid')==0){
	  	myUser::updateFilesTotal(0,$category->getParents(),'remove',$category->getFiles());
	    $sql = "UPDATE category SET parents=replace(parents,'".$category->getParents()."','|'), parentsarray=replace(parentsarray,'".$category->getParentsarray()."','') WHERE parents like '".$category->getParents().$category->getId()."|%'";
	    //echo $sql; exit;
			$con = Propel::getConnection();
	    $rs = $con->executeQuery($sql, ResultSet::FETCHMODE_NUM);
	    
  		$category->setParents('|');
  		$category->setParentsarray('');
  		$category->save();

  		if(CategoryPeer::hasChild($parentId) == 0)
  		{
		    $parent = CategoryPeer::retrieveByPk($parentId);
		    $parent->setChild('N');
		    $parent->save();
			}
	    echo 'Category with id <b>'.$this->getRequestParameter('id').'</b> is now <b>Main Home Category</b>';
	    //$this->redirect($_SERVER['HTTP_REFERER'].'/msg/categoryMoved');
	    exit;

   	}
   	
   	/* 
   		move to any category 
   	*/
   	$MoveToCategory = CategoryPeer::retrieveByPk($this->getRequestParameter('movetoid'));
   	$this->forward404Unless($MoveToCategory);
    if(in_array($this->getRequestParameter('id'), explode('|',$MoveToCategory->getParents())) ){
    	echo '<span class="msg error">Category can not be moved to inner category</span>'; exit;
    }
    else{
    	if($MoveToCategory->getChild()=='F'){
    		echo '<span class="msg error">target category having file... can\'t move there</span>';
    		exit;
    	}
    	elseif($MoveToCategory->getChild()=='U'){
    		echo '<span class="msg error">target category is url targeted category... can\'t move there</span>';
    		exit;
    	}
    	else{
	    	myUser::updateFilesTotal(0,$category->getParents(),'remove',$category->getFiles());
	    	myUser::updateFilesTotal($MoveToCategory->getId(),$MoveToCategory->getParents(),'add',$category->getFiles());
	    	/* SKYiTech :: if category has no inner categories */
	    	if($category->getChild()=='F' || $category->getChild()=='N'){
	    		$category->setParents($MoveToCategory->getParents().$MoveToCategory->getId().'|');
	    		$category->setParentsarray($MoveToCategory->getParentsarray().'||'.$MoveToCategory->getId().'||'.CategoryPeer::getCategoryName($MoveToCategory->getId()) );
	    		$category->save();
	    	}	/* SKYiTech :: if category has inner categories */
	    	elseif($category->getChild()=='U'){
	    		$category->setParents($MoveToCategory->getParents().$MoveToCategory->getId().'|');
	    		$category->setParentsarray($MoveToCategory->getParentsarray().'||'.$MoveToCategory->getId().'||'.CategoryPeer::getCategoryName($MoveToCategory->getId()) );
	    		$category->save();
	    	}	/* SKYiTech :: if category has inner categories */
	    	elseif($category->getChild()=='D'){
						if($category->getParents()=='|'){
					    $sql = "UPDATE category SET parents='".$MoveToCategory->getParents().$MoveToCategory->getId()."|".$category->getId()."|', parentsarray='".$MoveToCategory->getParentsarray()."||".$MoveToCategory->getId()."||".$MoveToCategory->getCategoryName()."' WHERE parents like '".$category->getParents().$category->getId()."|%'";
					  }
					  else
					    $sql = "UPDATE category SET parents=replace(parents,'".$category->getParents()."','".$MoveToCategory->getParents().$MoveToCategory->getId()."|'), parentsarray=replace(parentsarray,'".$category->getParentsarray()."','".$MoveToCategory->getParentsarray()."||".$MoveToCategory->getId()."||".$MoveToCategory->getCategoryName()."') WHERE parents like '".$category->getParents().$category->getId()."|%'";
				    //echo $sql;
						$con = Propel::getConnection();
				    $rs = $con->executeQuery($sql, ResultSet::FETCHMODE_NUM);
				    
 		    		$category->setParents($MoveToCategory->getParents().$MoveToCategory->getId().'|');
 		    		$category->setParentsarray($MoveToCategory->getParentsarray().'||'.$MoveToCategory->getId().'||'.$MoveToCategory->getCategoryName());
		    		$category->save();
	    			
	    	}
	  		if($parentId && CategoryPeer::hasChild($parentId) == 0) // 1 is the self category which is going to delete
	  		{
				    $parent = CategoryPeer::retrieveByPk($parentId);
				    $parent->setChild('N');
				    $parent->save();
				}
				// if target directory is empty set it as dir
	  		if($MoveToCategory->getChild()=='N')
	  		{
				    $MoveToCategory->setChild('D');
				    $MoveToCategory->save();
				}
	    }
    }
    //myUser::recalculateCategory();
    echo 'Category with id <b>'.$this->getRequestParameter('id').'</b> is moved to id <b>'.$this->getRequestParameter('movetoid').'</b>';
    //$this->redirect($_SERVER['HTTP_REFERER'].'/msg/categoryMoved');
    exit;
  }

  public function executeMoveFile()
  {
  	if(!$this->getRequestParameter('id') || !$this->getRequestParameter('movetoid')){
  		echo '<span class="msg error">Source or Target File Category is not defined...</span>';
  		exit;
  	}
    $category = CategoryPeer::retrieveByPk($this->getRequestParameter('id'));
   	$this->forward404Unless($category);
   	if($this->getRequestParameter('id')==$this->getRequestParameter('movetoid')){
   		echo '<span class="msg error">Are you kidding with me?? your source and taregt category both are same man..!!!?</span>';
   		exit;
   	}

   	$MoveToCategory = CategoryPeer::retrieveByPk($this->getRequestParameter('movetoid'));
   	$this->forward404Unless($MoveToCategory);

  	if($MoveToCategory->getChild()=='D'){
  		echo '<span class="msg error">target category having sub directory... can\'t move there</span>';
  		exit;
  	}
  	elseif($MoveToCategory->getChild()=='U'){
  		echo '<span class="msg error">target category is url targeted category... can\'t move there</span>';
  		exit;
  	}
   	elseif($category->getFiles() > 0){
   		$totalFiles = $category->getFiles();
	  	myUser::updateFilesTotal($category->getId(),$category->getParents(),'remove',$category->getFiles());
    	myUser::updateFilesTotal($MoveToCategory->getId(),$MoveToCategory->getParents(),'add',$category->getFiles());
    	$con = Propel::getConnection();
    	$con->executeQuery("update files set category_id=".$MoveToCategory->getId()." where category_id=".$category->getId());
  		$category->setChild('N');
  		$category->save();

  		if($MoveToCategory->getChild()=='N')
  		{
		    $MoveToCategory->setChild('F');
		    $MoveToCategory->save();
			}
	    echo 'Total <b>'.$totalFiles.'</b> Files of Category id <b>'.$this->getRequestParameter('id').'</b> is moved to category id <b>'.$this->getRequestParameter('movetoid').'</b>';
	    exit;
   	}
    echo 'No Action to take...';
    exit;
  }

  public function executeGetpath()
  {
  	//echo $this->getRequestParameter('id');
  	if($this->getRequestParameter('id')==0)
  		echo 'Home';
  	elseif($id = $this->getRequestParameter('id')){
	 		$category = CategoryPeer::retrieveByPk($id);
	  	echo '&nbsp;'.myUser::getCategoryPath($category->getParentsarray());
	  	echo ($category->getCategoryName()).' ';
  	}
  	else
  		echo 'please enter id of category...';
  	exit;
  }

  public function executeFindcategory()
	{
		$typed = $this->getRequestParameter('find_category');
	  echo '<ul style="height:300px;overflow:auto;z-index:99999;">';
		 	$c = new Criteria();
			$c->addSelectColumn(CategoryPeer::ID);
			$c->addSelectColumn(CategoryPeer::CATEGORY_NAME);
			$c->add( CategoryPeer::CATEGORY_NAME, $typed.'%', Criteria::LIKE );
			$c->addAscendingOrderByColumn(CategoryPeer::CATEGORY_NAME);
			$results = CategoryPeer::doSelectRs($c);
			$firstArray = array();
	    while($results->next()){
	    	echo '<li id="'.$results->getString(1).'">'.$results->getString(1).' => '.$results->getString(2).'</li>';
	    	$firstArray[] = $results->getString(1);
	    }

		 	$c = new Criteria();
			$c->addSelectColumn(CategoryPeer::ID);
			$c->addSelectColumn(CategoryPeer::CATEGORY_NAME);
			$c->add( CategoryPeer::CATEGORY_NAME, '%'.$typed.'%', Criteria::LIKE );
			$c->addAscendingOrderByColumn(CategoryPeer::CATEGORY_NAME);
			$results = CategoryPeer::doSelectRs($c);
	    while($results->next()){
	    	if(!in_array($results->getString(1),$firstArray))
		      echo '<li id="'.$results->getString(1).'">'.$results->getString(1).' => '.$results->getString(2).'</li>';
	    }

	  echo '</ul>';
	  exit;
	}

  public function executeActivation()
  {
  	if($this->getRequestParameter('status') == 'B')
  		$status = 'A';
  	else
  		$status = 'B';
    $c = CategoryPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($c);
    $c->setStatus($status);
    $c->save();
  	if($this->getRequestParameter('status') == 'B')
  		echo '<a onclick="new Ajax.Updater(\'active_'.$this->getRequestParameter('id').'\', \'/mm-admin4589/category/activation/id/'.$this->getRequestParameter('id').'/status/A\', {asynchronous:true, evalScripts:false});; return false;" href="#">Active</a>';
  	else
  		echo '<a onclick="new Ajax.Updater(\'active_'.$this->getRequestParameter('id').'\', \'/mm-admin4589/category/activation/id/'.$this->getRequestParameter('id').'/status/B\', {asynchronous:true, evalScripts:false});; return false;" href="#"><span class="red">Block</span></a>';
		return sfView::NONE;
  }

  public function executeDelete()
  {
  	/*
  	* SKYiTech :: check that we get command from admin, not from direct url typing
  	*/
  	if(isset($_SERVER['HTTP_REFERER'])==false)
  		$this->redirect('category','list');

    $this->category = CategoryPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($this->category);

		$this->id = $this->getRequestParameter('id');

    /* SKYiTech :: get parent category */
    $this->pid = CategoryPeer::getParentId($this->category->getParents());

	    /* SKYiTech :: get childs count of parent category */
		$this->hasChild = CategoryPeer::hasChild($this->getRequestParameter('id'));
	
	    /* SKYiTech :: get childs count of parent category */
		$this->hasFiles = CategoryPeer::hasFiles($this->getRequestParameter('id'),$this->category->getChild());
	
		/*
		* SKYiTech :: Check whether delete command is confirmed or not
		*/
		if($this->getRequestParameter('commit'))
		{
		  	if($this->pid)
		  	{
		  		if(CategoryPeer::hasChild($this->pid) == 1) // 1 is the self category which is going to delete
		  		{
				    $parent = CategoryPeer::retrieveByPk($this->pid);
				    $parent->setChild('N');
				    $parent->save();
					}
				}
	
				/* Remove all Sub Category & Files */
	//			if($this->category->getChild()=='D')
				{
					//echo $this->getRequestParameter('id'); exit;
					myUser::removeAllChild($this->getRequestParameter('id'));
					//echo ' Child Deleted'; exit;
				}

				$thumbServer = 'c';
				sfToolkit::clearGlob(sfConfig::get('sf_upload_dir').'/thumb/'.$thumbServer.'/'.$this->getRequestParameter('id').'_*.jpg');
	
				// remove self directory
		    $this->category->delete();
		   // myUser::recalculateCategory();
		    return $this->redirect('category/list?parent='.$this->pid);
		}
  }
  
  
}
