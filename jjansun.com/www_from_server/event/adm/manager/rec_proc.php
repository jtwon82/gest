<?
	include("../inc/head.php");

	$d1= $_REQUEST[d1];
	$d1= urldecode($d1);
	parse_str($d1, $data);


	switch($data["act"]){
	case "addct":			#카테고리추가

		mysql_query("INSERT INTO tbl_common (codes, pcodes, step, title) select * from (	select 1 codes, 0 pcodes, 0 step, 'Root' title	 ) a where not exists (select codes from tbl_common where codes=1)");

		for($i=0; $i<=$data["loop"]; $i++){
			$sql = "
				select max(step)+1 step
				from tbl_common
				where pcodes = ". $data["pcode"] ."
			";

			$st		= mysql_query($sql);
			$iRows	= mysql_num_rows($st);
			$rs		= gArrFetch($st);

			if($data["loop"]==1)
				$val = $data["val"];
			else{
				$val = split('\r', $data["val"]);
				$val = $val[$i];
			}

			$sql = "
				insert into tbl_common(pcodes, title, step)
				select
					". $data["pcode"] ." pcodes,
					'". $val ."' title,
					". ($rs[0][step]?$rs[0][step]:0) ." step
			";

			if($val!='')	mysql_query($sql);
		}
		script("parent.iframeReload('iFrm_type". $data["area"] ."');parent.clearSubDepth(". $data["area"] .");");

	break;

	Case "swaporder":		#step 값 변경
		$sql = "
			update tbl_common set step = ". gAt($data["step2"], 0, "_") ."
			where codes = ". gAt($data["step2"], 1, "_") ."
		";
		mysql_query($sql);

		$sql = "
			update tbl_common set step = ". gAt($data["step1"], 0, "_") ."
			where codes = ". gAt($data["step1"], 1, "_") ."
		";
		mysql_query($sql);

	break;

	Case "ditem":			#아이템 삭제(하위개체 있으면 삭제하지않음)

		$sql	= "select pcodes from tbl_common where pcodes = ". $data["item"] ." limit 1 ";

		$st		= mysql_query($sql);
		$iRows	= mysql_num_rows($st);;

		$sql = "
			delete
			from tbl_common
			where codes = ". $data["item"] ."
		";

		if($iRows<1){
			$st		= mysql_query($sql);
			script("parent.parent.iframeReload('iFrm_type". $data["area"] ."');");
		}else{
			script("parent.runProc('v', ". $data["n"] .", null);","서브조직이 있음.");
		}

	break;

	Case "uitem":			#아이템 update
		if($data["gubun"] == "title" ) $sql = "update tbl_common set title = '". $data["title"] ."' ";
		if($data["gubun"] == "value" ) $sql = "update tbl_common set val = '". $data["title"] ."' ";
		if($data["gubun"] == "value2" ) $sql = "update tbl_common set val2 = '". $data["title"] ."' ";
		$sql .= "where codes = ". $data["code"] ." ";
		mysql_query($sql);

	break;

	}

?>