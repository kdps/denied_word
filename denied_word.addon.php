<?php
if(!defined('__XE__')) exit();
if($called_position=='before_display_content')
{
	if($addon_info->delete == 'Y'){
		if (Context::get('act') == 'procBoardDeleteDocument'){
			$oDocumentController = &getController('document');
			$obj = Context::getRequestVars();
			$obj->title = '삭제된 게시글입니다.';
			$obj->content = '삭제된 게시글입니다.';
			$obj->allow_comment = 'N';
			$obj->allow_trackback = 'N';
			$obj->mid = $this->mid;
			$obj->document_srl = $this->document_srl;
			$obj->module_srl = $this->module_srl;
			$oDocumentController->insertDocument($obj);
		}
	}
}

if($called_position=='before_module_init'){
	if (Context::get('act') == 'dispBoardContent'){ 
		if($addon_info->denied_level == 'Y'){
			$oModuleModel = getModel('module');
			$logged_info = Context::get('logged_info');
			$oPointModel = getModel('point');
			$point = $oPointModel->getPoint($logged_info->member_srl);
			$config = $oModuleModel->getModuleConfig('point');
			$level = $oPointModel->getLevel($point,$config->level_step);
			if($level <= $addon_info->denied_levelup){
				if($addon_info->denied_alert == 'Y'){
					exit('<a>'.$level.'에서는 게시글 조회가 불가능합니다</a>');
				}else{
					exit();
				}
			}
		}
		
	}
	
	if (Context::get('act') == 'procBoardInsertDocument'){ 
		$obj = Context::getRequestVars();
		if($addon_info->denied_content == 'Y'){
			$word_content = $addon_info->denied_word_content;
			$split_content = $addon_info->explode_word;
			$arr_content = explode($split_content, $word_content);
			foreach($arr_content as $value){
				if($addon_info->denied_smart == 'Y'){
					$strbool = NULL;
					$sucbool = NULL;
					preg_match_all('/./u',$value,$matches); 
					foreach($matches[0] as $value2){
						if($strbool===NULL){
							if(strpos(strip_tags($obj->content),$value2)!==false){
								$strins = strpos(strip_tags($obj->content),$value2);
								$strbool = true;
							}else{
								$sucbool = 's';
							}
						}
						elseif($strbool!==NULL){
							if ($sucbool===NULL){
								if ($addon_info->denied_smart_len){
									$sp=$addon_info->denied_smart_len;
								}else{
									$sp=15;
								}
								
								if(strpos(substr(strip_tags($obj->content),$strins,$sp),$value2)!==false){
									$strins = strpos($value2,substr(strip_tags($obj->content),$strins));
								}else{
									$sucbool = 's';
								}
							}
						}
					}
					if ($sucbool===NULL){
						if($addon_info->denied_alert == 'Y'){
							exit('<a>'.$value.'단어가 금지되어있습니다</a>');
						}else{
							exit();
						}
					}
				}
				elseif(strpos($obj->content,$value) !== false)
				{
					if($addon_info->denied_alert == 'Y'){
						exit('<a>'.$value.'단어가 금지되어있습니다</a>');
					}else{
						exit();
					}
				}
				
			}
		}
		
		if($addon_info->denied_same_instr == 'Y'){
			if(strpos(trim(strip_tags($obj->content)),trim($obj->title)) !== false){
				if($addon_info->denied_alert == 'Y'){
					exit('<a>제목과 내용이 같은글은 금지되어있습니다</a>');
				}else{
					exit();
				}
			}
		}
		
		if($addon_info->denied_same_len == 'Y'){
			if(trim($obj->title) === substr(trim(strip_tags($obj->content)),0,strlen(($obj->title)))){
				if($addon_info->denied_alert == 'Y'){
					exit('<a>제목과 내용이 같은글은 금지되어있습니다</a>');
				}else{
					exit();
				}
			}
		}
		
		if($addon_info->denied_same == 'Y'){
			if(trim(strip_tags($obj->content)) === trim($obj->title)){
				if($addon_info->denied_alert == 'Y'){
					exit('<a>제목과 내용이 같은글은 금지되어있습니다</a>');
				}else{
					exit();
				}
			}
		}
		
		if($addon_info->denied_nick == 'Y'){
			$word_nick = $addon_info->denied_word_nick;
			$split_nick = $addon_info->explode_word;
			$arr_nick = explode($split_nick, $word_nick);
			foreach($arr_nick as $value){
				if(strpos($obj->nick_name,$value) !== false){
					if($addon_info->denied_alert == 'Y'){
						exit('<a>'.$value.'단어가 금지되어있습니다</a>');
					}else{
						exit();
					}
				}
			}
		}
		
		if($addon_info->denied_title == 'Y'){
			$word_title = $addon_info->denied_word_title;
			$split_title = $addon_info->explode_word;
			$arr_title = explode($split_title, $word_title);
			foreach($arr_title as $value){
				if($addon_info->denied_smart == 'Y'){
					$strbool = NULL;
					$sucbool = NULL;
					$strlen = NULL;
					preg_match_all('/./u',$value,$matches); 
					foreach($matches[0] as $value2){
						if($strbool===NULL){
							if(strpos(strip_tags($obj->title),$value2)!==false){
								$strins = strpos(strip_tags($obj->title),$value2);
								$strbool = true;
							}else{
								$sucbool = 's';
							}
						}
						elseif($strbool!==NULL){
							if ($sucbool===NULL){
								if ($addon_info->denied_smart_len){
									$sp=$addon_info->denied_smart_len;}
								else{
									$sp=15;
								}
								if(strpos(substr(strip_tags($obj->title),$strins,$sp),$value2)!==false){
									$strins = strpos($value2,substr(strip_tags($obj->title),$strins));
								}else{
									$sucbool = 's';
								}
							}
						}
					}
					if ($sucbool===NULL){
						if($addon_info->denied_alert == 'Y'){
							exit('<a>'.$value.'단어가 금지되어있습니다</a>');
						}else{
							exit();
						}
					}
				}
				elseif(strpos($obj->title,$value) !== false){
					if($addon_info->denied_alert == 'Y'){
						exit('<a>'.$value.'단어가 금지되어있습니다</a>');
					}else{
						exit();
					}
				}
			}
		}
	}
	
	
	if (Context::get('act') == 'procBoardInsertComment'){
		$obj = Context::getRequestVars();
		if($addon_info->denied_title == 'Y'){
			$word_comment = $addon_info->denied_word_comment;
			$split_comment = $addon_info->explode_word;
			$arr_comment = explode($split_comment, $word_comment);
			foreach($arr_comment as $value){
				if($addon_info->denied_alert == 'Y'){
					exit('<a>'.$value.'단어가 금지되어있습니다</a>');
				}else{
					exit();
				}
			}
		}
		if($addon_info->denied_nick == 'Y'){
			$word_nick = $addon_info->denied_word_nick;
			$split_nick = $addon_info->explode_word;
			$arr_nick = explode($split_nick, $word_nick);
			foreach($arr_nick as $value){
				if(strpos($obj->nick_name,$value) !== false){
					if($addon_info->denied_alert == 'Y'){
						exit('<a>'.$value.'단어가 금지되어있습니다</a>');
					}else{
						exit();
					}
				}
			}
		}
	}
}
