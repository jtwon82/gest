<?
	include("../../common/function.php");
	include("../../common/db.php");
	include("../../common/config.php");
	include("./board_config.php");
	include "../session.php";

	if($mode=="GET_BLOGID"){
		$title= anti_injection($_POST['title']);
		$etc1	=explode('/',$title)[3];

		$sql= "select * from tbl_board where b_id='$b_id' and etc1='$etc1'";
		$select= db_select($sql);

		echo json_encode( array('mode'=>$mode, 'select'=>$select) );
	}
	if($mode=="write2"){
		$field['b_id']		=anti_injection($b_id);
		$field['userno']	=$_SESSION['LOGIN_NO'];
		$field['title']		=addslashes(cutstr($_POST['title'],100));
		$field['content']	=addslashes($_POST['content']);
		$field['link']		=anti_injection($_POST['link']);
		$field['notice']	=($_POST['notice'] ? $_POST['notice'] : "N");
		$field['secret']	=($_POST['secret'] ? $_POST['secret'] : "N");
		$field['ip']		=$_SERVER["REMOTE_ADDR"];
		$field['reg_date']	=date('Y-m-d H:i:s');
		$field['reg_dates']	=date('Y-m-d');
		$field['etc1']	=explode('/',$field['title'])[3];

//		$field['from_name']		=anti_injection($_POST['from_name']);
//		$field['to_name']		=anti_injection($_POST['to_name']);
//		$field['letterType']		=anti_injection($_POST['letterType']);
//		$field['pno']		=anti_injection($_POST['pno']);

		//글번호 생성
		$rList = db_select("select ref from tbl_board order by ref desc");
		$ref = ($rList['ref'] ? $rList['ref']+1 : "1");
		$field['ref'] = $ref;

		db_insert("tbl_board",$field);

		//저장된 글번호
		$b_idx=mysql_insert_id();

		//첨부파일 등록
		for($i=0; $i<count($_FILES['filename']['name']); $i++){

			if($_FILES['filename']['name'][$i]) {
				$upfile = file_upload($_FILES['filename']['tmp_name'][$i], $_FILES['filename']['name'][$i], $bbs_dir);
				$upfield['b_idx'] = $b_idx;
				$upfield['filename'] = $upfile;
				$upfield['filesize'] = $_FILES['filename']['size'][$i];
				$upfield['orgname'] = $_FILES['filename']['name'][$i];
				$upfield['sortnum'] = $i+1;

				db_insert("tbl_board_file",$upfield);

				if($bbs_thumbnail=="Y" && $i==0){
					thumbnail($bbs_dir."/".$upfile, $bbs_dir."/thumb_".$upfile, $bbs_thumbwidth, $bbs_thumbheight, $bbs_thumbtype);
				}
			}
		}

		move_page("board_list.php?b_id=$b_id&b_code=$b_code");
	}
	if($mode=="write"){
		$field['b_id']		=anti_injection($b_id);
		$field['b_code']	=anti_injection($_POST['b_code']);
		$field['userno']	=$_SESSION['LOGIN_NO'];
		$field['name']		=anti_injection($_POST['name']);
		$field['title']		=addslashes(cutstr($_POST['title'],100));
		$field['content']	=addslashes($_POST['content']);
		$field['link']		=anti_injection($_POST['link']);
		$field['notice']	=($_POST['notice'] ? $_POST['notice'] : "N");
		$field['secret']	=($_POST['secret'] ? $_POST['secret'] : "N");
		$field['ip']		=$_SERVER["REMOTE_ADDR"];
		$field['reg_date']	=date('Y-m-d H:i:s');
		$field['reg_dates']	=date('Y-m-d');
		$field['etc1']		=anti_injection($_POST['etc1']);
		$field['etc2']		=anti_injection($_POST['etc2']);
		$field['etc3']		=anti_injection($_POST['etc3']);
		$field['etc4']		=anti_injection($_POST['etc4']);
		$field['etc5']		=anti_injection($_POST['etc5']);
		$field['etc6']		=anti_injection($_POST['etc6']);
		$field['etc7']		=anti_injection($_POST['etc7']);
		$field['etc8']		=anti_injection($_POST['etc8']);
		$field['etc9']		=anti_injection($_POST['etc9']);
		$field['etc0']		=anti_injection($_POST['etc0']);

//		$field['from_name']		=anti_injection($_POST['from_name']);
//		$field['to_name']		=anti_injection($_POST['to_name']);
//		$field['letterType']		=anti_injection($_POST['letterType']);
//		$field['pno']		=anti_injection($_POST['pno']);

		//글번호 생성
		$rList = db_select("select ref from tbl_board order by ref desc");
		$ref = ($rList['ref'] ? $rList['ref']+1 : "1");
		$field['ref'] = $ref;

		db_insert("tbl_board",$field);

		//저장된 글번호
		$b_idx=mysql_insert_id();

		//첨부파일 등록
		for($i=0; $i<count($_FILES['filename']['name']); $i++){

			if($_FILES['filename']['name'][$i]) {
				$upfile = file_upload($_FILES['filename']['tmp_name'][$i], $_FILES['filename']['name'][$i], $bbs_dir);
				$upfield['b_idx'] = $b_idx;
				$upfield['filename'] = $upfile;
				$upfield['filesize'] = $_FILES['filename']['size'][$i];
				$upfield['orgname'] = $_FILES['filename']['name'][$i];
				$upfield['sortnum'] = $i+1;

				db_insert("tbl_board_file",$upfield);

				if($bbs_thumbnail=="Y" && $i==0){
					thumbnail($bbs_dir."/".$upfile, $bbs_dir."/thumb_".$upfile, $bbs_thumbwidth, $bbs_thumbheight, $bbs_thumbtype);
				}
			}
		}

		move_page("board_list.php?b_id=$b_id&b_code=$b_code");
	}


	if($mode=="reply"){

		//원글 정보 불러오기
		$idx = anti_injection($_POST['idx']);
		$org = db_select("select ref, re_level, re_step, passwd, notice from tbl_board where idx='".$_POST['idx']."'");

		//댓글 순서 한 칸씩 밀기
		db_query("update tbl_board set re_step=re_step-1 where ref='".$org['ref']."' and re_step>'".$org['re_level']."'");

		//글저장
		$field['b_id']=anti_injection($b_id);
		$field['b_code']=anti_injection($_POST['b_code']);
		$field['userno']=$_SESSION['LOGIN_NO'];
		$field['name']=anti_injection($_POST['name']);
		$field['title']		=addslashes(cutstr($_POST['title'],100));
		$field['content']	=addslashes($_POST['content']);
		$field['passwd']=$org['passwd'];
		$field['notice']=$org['notice'];
		$field['secret']=($_POST['secret'] ? $_POST['secret'] : "N");
		$field['ip']=$_SERVER["REMOTE_ADDR"];
		$field['reg_date']=date('Y-m-d H:i:s');
		$field['reg_dates']	=date('Y-m-d');
		$field['ref'] = $org['ref'];
		$field['re_level'] = $org['re_level'] + 1;
		$field['re_step'] = $org['re_step'] - 1;
		$field['link']		=anti_injection($_POST['link']);

//		$field['from_name']		=anti_injection($_POST['from_name']);
//		$field['to_name']		=anti_injection($_POST['to_name']);
//		$field['letterType']		=anti_injection($_POST['letterType']);
//		$field['pno']		=anti_injection($_POST['pno']);

		db_insert("tbl_board",$field);

		//저장된 글번호
		$b_idx=mysql_insert_id();

		//첨부파일 등록
		for($i=0; $i<count($_FILES['filename']['name']); $i++){
			if($_FILES['filename']['name'][$i]) {
				$upfile = file_upload($_FILES['filename']['tmp_name'][$i], $_FILES['filename']['name'][$i], $bbs_dir);
				$upfield['b_idx'] = $b_idx;
				$upfield['filename'] = $upfile;
				$upfield['filensize'] = $_FILES['filename']['size'][$i];
				$upfield['orgname'] = $_FILES['filename']['name'][$i];
				$upfield['sortnum'] = $i+1;

				db_insert("tbl_board_file",$upfield);

				if($bbs_thumbnail=="Y" && $i==0){
					thumbnail($bbs_dir."/".$upfile, $bbs_dir."/thumb_".$upfile, $bbs_thumbwidth, $bbs_thumbheight, $bbs_thumbtype);
				}
			}
		}

		move_page("board_list.php?b_id=$b_id&b_code=$_POST[b_code]&page=$_POST[page]&search_key=$_POST[search_key]&search_val=$_POST[search_val]");
	}


	if($mode=="modify"){

		$field['b_code']	=anti_injection($_POST['b_code']);
		$field['name']		=anti_injection($_POST['name']);
		$field['title']		=addslashes(cutstr($_POST['title'],100));
		$field['content']	=addslashes($_POST['content']);
		$field['notice']	=($_POST['notice'] ? $_POST['notice'] : "N");
		$field['secret']	=anti_injection($_POST['secret']);
		$field['link']		=anti_injection($_POST['link']);
		$field['etc1']		=anti_injection($_POST['etc1']);
		$field['etc2']		=anti_injection($_POST['etc2']);
		$field['etc3']		=anti_injection($_POST['etc3']);
		$field['etc4']		=anti_injection($_POST['etc4']);
		$field['etc5']		=anti_injection($_POST['etc5']);
		$field['etc6']		=anti_injection($_POST['etc6']);
		$field['etc7']		=anti_injection($_POST['etc7']);
		$field['etc8']		=anti_injection($_POST['etc8']);
		$field['etc9']		=anti_injection($_POST['etc9']);
		$field['etc0']		=anti_injection($_POST['etc0']);

//		$field['from_name']		=anti_injection($_POST['from_name']);
//		$field['to_name']		=anti_injection($_POST['to_name']);
//		$field['letterType']		=anti_injection($_POST['letterType']);
//		$field['pno']		=anti_injection($_POST['pno']);

		db_update("tbl_board", $field, "idx='".$_POST['idx']."'");

		//첨부파일 개별 삭제
		for($i=0; $i<count($_POST['filedel']); $i++){
			$fList=db_select("select filename from tbl_board_file where b_idx='".$_POST['idx']."' and sortnum='".$_POST['filedel'][$i]."'");
			file_delete($fList['filename'], $bbs_dir);
			db_query("delete from tbl_board_file where b_idx='".$_POST['idx']."' and sortnum='".$_POST['filedel'][$i]."'");
		}

		//첨부파일 수정
		for($i=0; $i<count($_FILES['filename']['name']); $i++){

			if($_FILES['filename']['name'][$i]) {
				$upfile = file_upload($_FILES['filename']['tmp_name'][$i], $_FILES['filename']['name'][$i], $bbs_dir);
				$upfield['filename'] = $upfile;
				$upfield['filesize'] = $_FILES['filename']['size'][$i];
				$upfield['orgname'] = $_FILES['filename']['name'][$i];
				$upfield['b_idx'] = $_POST['idx'];
				$upfield['sortnum'] = $i+1;

				if($bbs_thumbnail=="Y" && $i==0){
					thumbnail($bbs_dir."/".$upfile, $bbs_dir."/thumb_".$upfile, $bbs_thumbwidth, $bbs_thumbheight, $bbs_thumbtype);
				}

				db_query("delete from tbl_board_file where b_idx='".$_POST['idx']."' and sortnum='".($i+1)."'");
				db_insert("tbl_board_file",$upfield);
			}
		}

		move_page("board_list.php?b_id=$b_id&b_code=$_POST[b_code]&page=$_POST[page]&search_key=$_POST[search_key]&search_val=$_POST[search_val]");
	}


	if($mode=="check_del"){

		foreach($_POST['idx'] as $idx){

			//삭제할 첨부파일
			$fRs=db_query("select filename from tbl_board_file where b_idx='".$idx."'");
			while($fList=db_fetch($fRs)){
				file_delete($fList['filename'], $bbs_dir);
			}
//			db_query("delete from tbl_board where idx='".$idx."'");
			db_query("update tbl_board set delete_date=now() where idx='".$idx."'");
			db_query("delete from tbl_board_file where b_idx='".$idx."'");
		}
		move_page("board_list.php?b_id=$b_id&b_code=$_POST[b_code]&page=$_POST[page]&search_key=$_POST[search_key]&search_val=$_POST[search_val]");
	}


	if($mode=="del"){

		//삭제할 첨부파일
		$fRs=db_query("select filename from tbl_board_file where b_idx='".$_GET['idx']."'");
		while($fList=db_fetch($fRs)){
			file_delete($fList['filename'], $bbs_dir);
		}
//		db_query("delete from tbl_board where idx='".$_GET['idx']."'");
		db_query("update tbl_board set delete_date=now() where idx='".$_GET['idx']."'");
		db_query("delete from tbl_board_file where b_idx='".$_GET['idx']."'");

		move_page("board_list.php?b_id=$b_id&b_code=$_GET[b_code]&page=$_GET[page]&search_key=$_GET[search_key]&search_val=$_GET[search_val]");
	}


	if($mode=="comment"){

		$field['b_idx']=anti_injection($_POST['idx']);
		$field['userno']=$_SESSION['LOGIN_NO'];
		$field['name']=anti_injection($_POST['cname']);
		$field['ip']=$_SERVER["REMOTE_ADDR"];
		$field['content']=$_POST['content'];
		$field['reg_date']=date('Y-m-d H:i:s');

		db_insert("tbl_board_comment",$field);
		db_query("update tbl_board set comment=comment+1 where idx='".$field['b_idx']."'");

		move_page(urldecode($_POST['reurl']));
	}


	if($mode=="comment_del"){

		db_query("delete from tbl_board_comment where idx='".$_GET['cidx']."'");
		db_query("update tbl_board set comment=comment-1 where idx='".$_GET['idx']."'");

		move_page(urldecode($_GET['reurl']));
	}


	if($mode=="copyto"){
		$idx= anti_injection($_REQUEST['idx']);
		$sql= "
			insert into tbl_board(reg_dates,reg_date,b_id,name,ip,title,content,comment,hit,link,tag,etc1,etc2,etc3,etc4,etc5,etc6,etc7,etc8,etc9,etc0)
				select left(now(),10)reg_dates,now() reg_date,'019' b_id,name,ip,title,content,comment,hit,link,tag,etc1,etc2,etc3,etc4,etc5,etc6,etc7,etc8,etc9,etc0 from tbl_board where idx='$idx'
		";
		db_query($sql);
	}
	
	if($mode=="contractaptwrite"){

		//첨부파일 등록
		for($i=0; $i<count($_FILES['file1']['name']); $i++){
			if($_FILES['file1']['name'][$i]) {
				$upfile = file_upload($_FILES['file1']['tmp_name'][$i], $_FILES['file1']['name'][$i], $bbs_dir);


				if ( $file = fopen("$bbs_dir/$upfile", "r")) {
					$idx=0;

					while(!feof($file)) {
						$line= fgets($file);
						$line= iconv("EUC-KR", "UTF-8", $line);

						if( strpos($line, "시군구")>-1 )	continue;
						if( strpos($line, "전월세구분")>-1 )	continue;

						if( strpos($line, "월세")>-1 || strpos($line, "전세")>-1 ){
							$line= explode('"', $line);
							if( count($line)<5 ) continue;
							$list2= "$line[1]^^$line[3]^^$line[5]^^$line[7]^^$line[9]^^$line[11]^^$line[13]^^$line[15]^^$line[17]^^$line[19]^^$line[21]^^$line[23]^^$line[25]^^$line[27]^^$line[29]^^$line[31]^^$line[33]^^$line[35]";
							$list2= explode('^^', $list2);
							list($signgu, $bungi, $preno, $postno, $apt_name, $junsae, $size, $ym, $d, $price, $floor, $year, $etc1, $etc2, $etc3, $etc4) = $list2;
						}
						else{
							$line= explode('"', $line);
							if( count($line)<5 ) continue;
							$list2= "$line[1]^^$line[3]^^$line[5]^^$line[7]^^$line[9]^^$line[11]^^$line[13]^^$line[15]^^$line[17]^^$line[19]^^$line[21]^^$line[23]^^$line[25]^^$line[27]^^$line[29]^^$line[31]^^$line[33]";
							$list2= explode('^^', $list2);
							list($signgu, $bungi, $preno, $postno, $apt_name, $size, $ym, $d, $price, $floor, $year, $etc1, $etc2, $etc3, $etc4) = $list2;
							$junsae= '매매';
						}
						#echo "$signgu, $bungi, $preno, $postno, $apt_name, $size, $ym, $d, $price, $floor, $year, $etc1, $etc2, $etc3, $etc4, $junsae" ."\n";

						$sql[]= "select now() reg_date, trim('$signgu')signgu, trim('$bungi')bungi, trim('$preno')preno, trim('$postno')postno, trim('$apt_name')apt_name, round(trim('$size'),1)size, trim('$ym')ym, trim('$d')d, replace(trim('$price'),',','')price, trim('$floor')floor, trim('$year')year, trim('$etc1')etc1, trim('$etc2')etc2, trim('$etc3')etc3, trim('$etc4')etc4, trim('$junsae')etc5";

						if(++$idx % 1000 == 0 ) {
							db_query("insert into tbl_board_content_apt(reg_date, signgu, bungi, preno, postno, apt_name, size, ym, d, price, floor, year, etc1, etc2, etc3, etc4, etc5) select * from ( ". implode(" union all ", $sql) ." )a ; ");
							$sql= "";
						}
					}

					if(count($sql)>0){
						db_query("insert into tbl_board_content_apt(reg_date, signgu, bungi, preno, postno, apt_name, size, ym, d, price, floor, year, etc1, etc2, etc3, etc4, etc5) select * from ( ". implode(" union all ", $sql) ." )a ; ");
					}

					fclose($file);
					file_delete($upfile,$bbs_dir);
					$file='';
				}
			}
		}

//		$list= explode("\r\n", $_REQUEST[contractaptlist]);
//		foreach($list as $row){
//			$list2= explode("	", $row);
//			list($signgu, $bungi, $preno, $postno, $apt_name, $size, $ym, $d, $price, $floor, $year, $etc1, $etc2, $etc3, $etc4) = $list2;
//
//			$sql = "insert into tbl_board_content_apt(reg_date, signgu, bungi, preno, postno, apt_name, size, ym, d, price, floor, year, etc1, etc2, etc3, etc4)
//				select now() reg_date, trim('$signgu'), trim('$bungi'), trim('$preno'), trim('$postno'), trim('$apt_name'), round(trim('$size'),1), trim('$ym'), trim('$d'), replace(trim('$price'),',',''), trim('$floor'), trim('$year'), trim('$etc1'), trim('$etc2'), trim('$etc3'), trim('$etc4')
//			";
//			if($signgu!=""){
//				db_query($sql);
//			}
//		}

		move_page("board_list.php?b_id=$b_id&b_code=$b_code");
		
	}
?>