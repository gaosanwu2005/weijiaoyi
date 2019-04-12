<?php

$password = 'd328ab8f8bcf16888c9a12e480a414d4';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="zh-CN">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ZIP压缩程序 荒野无灯修改版</title>
<!-- css copied from wordpress -->
<style type="text/css">
<!--

html{background:#f9f9f9;}body{background:#fff;color:#333;font-family:sans-serif;margin:2em auto;width:700px;padding:1em 2em;-moz-border-radius:11px;-khtml-border-radius:11px;-webkit-border-radius:11px;border-radius:11px;border:1px solid #dfdfdf;}a{color:#2583ad;text-decoration:none;}a:hover{color:#d54e21;}h1{border-bottom:1px solid #dadada;clear:both;color:#666;font:24px Georgia,"Times New Roman",Times,serif;margin:5px 0 0 -4px;padding:0;padding-bottom:7px;}h2{font-size:16px;}p,li,dd,dt{padding-bottom:2px;font-size:12px;line-height:18px;}code,.code{font-size:13px;}ul,ol,dl{padding:5px 5px 5px 22px;}a img{border:0;}abbr{border:0;font-variant:normal;}#logo{margin:6px 0 14px 0;border-bottom:none;text-align:center;}.step{margin:20px 0 15px;}.step,th{text-align:left;padding:0;}.submit input,.button,.button-secondary{font-family:sans-serif;text-decoration:none;font-size:14px!important;line-height:16px;padding:6px 12px;cursor:pointer;border:1px solid #bbb;color:#464646;-moz-border-radius:15px;-khtml-border-radius:15px;-webkit-border-radius:15px;border-radius:15px;-moz-box-sizing:content-box;-webkit-box-sizing:content-box;-khtml-box-sizing:content-box;box-sizing:content-box;}.button:hover,.button-secondary:hover,.submit input:hover{color:#000;border-color:#666;}textarea{border:1px solid #bbb;-moz-border-radius:3px;-khtml-border-radius:3px;-webkit-border-radius:3px;border-radius:3px;}.form-table{border-collapse:collapse;margin-top:1em;width:100%;}.form-table td{margin-bottom:9px;padding:10px;border-bottom:8px solid #fff;font-size:12px;}.form-table th{font-size:13px;text-align:left;padding:16px 10px 10px 10px;border-bottom:8px solid #fff;width:130px;vertical-align:top;}.form-table tr{background:#f3f3f3;}.form-table code{line-height:18px;font-size:18px;}.form-table p{margin:4px 0 0 0;font-size:11px;}.form-table input{line-height:20px;font-size:15px;padding:2px;}.form-table th p{font-weight:normal;}#error-page{margin-top:50px;}#error-page p{font-size:12px;line-height:18px;margin:25px 0 20px;}#error-page code,.code{font-family:Consolas,Monaco,monospace;}#pass-strength-result{background-color:#eee;border-color:#ddd!important;border-style:solid;border-width:1px;margin:5px 5px 5px 1px;padding:5px;text-align:center;width:200px;display:none;}#pass-strength-result.bad{background-color:#ffb78c;border-color:#ff853c!important;}#pass-strength-result.good{background-color:#ffec8b;border-color:#fc0!important;}#pass-strength-result.short{background-color:#ffa0a0;border-color:#f04040!important;}#pass-strength-result.strong{background-color:#c3ff88;border-color:#8dff1c!important;}.message{border:1px solid #e6db55;padding:.3em .6em;margin:5px 0 15px;background-color:#ffffe0;}
.mydirname {color:#F00;}
.myfilename {color:#649ABE;}
.currentdir {font-size:14px;font-family: Georgia, Consolas;font-weight:bold;}
#footer {text-align:center;margin-top:20px;}
-->
</style>
</head>

<body>
  <form name="myform" id="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<h1 style="color:#2583ad;">在线压缩ZIP文件程序</h1><br>

<div class="message">
      <p>使用方法:选定要压缩的文件或目录（包含子目录），即可开始压缩。压缩的结果保留原来的目录结构。<br />
      <?php if(isset($_REQUEST['pwd']) && md5($_REQUEST['pwd']) == $password)
	  echo '当前目录：<span class="currentdir">'. dirname(__FILE__) . '</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="'.
	  $_SERVER['PHP_SELF']. '">退出</a>';
  		else
			if(isset($_GET['pwd']) )
				echo '密码值：<span class="currentdir">'. md5($_GET['pwd']) . '</span>';
			else
				echo 'Tips:在URL后参加<span class="currentdir">?pwd=密码</span> 查看生成密码.';
  	  ?>
	  </p>
</div>
<?php
if(!isset($_REQUEST["myaction"])):
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
      <td width="11%">验证密码: </td>
      <td width="89%"><input name="pwd" type="password" id="password" size="30" onkeydown="if(event.ctrlKey&&event.keyCode==13){document.getElementById('login').click();return false};"></td>
    </tr>	
    <tr>
      <td><input name="myaction" type="hidden" id="myaction" value="dolist"></td>
      <td><input type="submit" name="Submit" id="login" class="button" value=" 进 入 "></td>
    </tr>
  </table>
  <script type="text/javascript">
  document.getElementById('password').focus();
  </script>
<?php

elseif($_REQUEST["myaction"]=="dolist"):

	if(!isset($_REQUEST['pwd']) || empty($_REQUEST['pwd']) )
		die('请输入密码! <a href="'. $_SERVER['PHP_SELF']. '">Again</a>');
	if(md5($_REQUEST['pwd']) != $password) 
	    die('输入的密码不正确！<a href="'. $_SERVER['PHP_SELF']. '">Again</a>');
echo '选择要排除的目录(相对路径，以英文半角逗号分隔):<br />';
echo '<input type="text" id="toexlude" name="toexlude" size="100" /><br />';
	echo "选择要压缩的文件或目录：<br/>";
  	$fdir = opendir('./');
	while($file=readdir($fdir)){
		if($file=='.'|| $file=='..' ) continue;
		echo "<input name='dfile[]' type='checkbox' value='$file' ".($file==basename(__FILE__)?"":"checked")."> ";
		if(is_file($file)){
			echo "<span class='myfilename'>文件: $file</span><br>";
		}else{
			echo "<span class='mydirname'>目录: $file</span><br>";
		}
	}
?>
<br>
压缩文件保存到目录: 
<input name="todir" type="text" id="todir" value="_zipfiles<?php echo substr(md5(time()),0,8);?>_" size="20"> 
(留空为本目录,必须有写入权限)<br>
压缩文件名称:
<input name="zipname" type="text" id="zipname" value="backup_<?php echo substr(md5(__FILE__),0,8);?>.zip" size="15">
(.zip)<br>
<br> 
<input name="pwd" type="hidden" id="password" value="<?php echo $_POST['pwd'];?>">
<input name="myaction" type="hidden" id="myaction" value="dozip">
<input type='button' value='反选' onclick='selrev();'>
<input type="submit" name="Submit" value=" 开始压缩 ">
<script language='javascript'>
function selrev() {
	with(document.getElementById('myform')){
		for(i=0;i<elements.length;i++) {
			thiselm = elements[i];
			if(thiselm.name.match(/dfile\[]/))	thiselm.checked = !thiselm.checked;
		}
	}
}
</script>
<?php

elseif($_REQUEST["myaction"]=="dozip"):

//  set_time_limit(0);

  class PHPzip{

	var $file_count = 0 ;
	var $datastr_len   = 0;
	var $dirstr_len = 0;
	var $filedata = ''; //该变量只被类外部程序访问
	var $gzfilename;
	var $fp;
	var $dirstr='';
	/*
	返回文件的修改时间格式.
	只为本类内部函数调用.
	*/
    function unix2DosTime($unixtime = 0) {
        $timearray = ($unixtime == 0) ? getdate() : getdate($unixtime);

        if ($timearray['year'] < 1980) {
        	$timearray['year']    = 1980;
        	$timearray['mon']     = 1;
        	$timearray['mday']    = 1;
        	$timearray['hours']   = 0;
        	$timearray['minutes'] = 0;
        	$timearray['seconds'] = 0;
        }

        return (($timearray['year'] - 1980) << 25) | ($timearray['mon'] << 21) | ($timearray['mday'] << 16) |
               ($timearray['hours'] << 11) | ($timearray['minutes'] << 5) | ($timearray['seconds'] >> 1);
    }

	/*
	初始化文件,建立文件目录,
	并返回文件的写入权限.
	*/
	function startfile($path = 'myzip.zip'){
		$this->gzfilename=$path;
		$mypathdir=array();
		do{
			$mypathdir[] = $path = dirname($path);
		}while($path != '.');
		@end($mypathdir);
		do{
			$path = @current($mypathdir);
			@mkdir($path);
		}while(@prev($mypathdir));

		if($this->fp=@fopen($this->gzfilename,"w")){
			return true;
		}
		return false;
	}

	/*
	添加一个文件到 zip 压缩包中.
	*/
    function addfile($data, $name){
        $name     = str_replace('\\', '/', $name);
		
		if(strrchr($name,'/')=='/') return $this->adddir($name);
		
        $dtime    = dechex($this->unix2DosTime());
        $hexdtime = '\x' . $dtime[6] . $dtime[7]
                  . '\x' . $dtime[4] . $dtime[5]
                  . '\x' . $dtime[2] . $dtime[3]
                  . '\x' . $dtime[0] . $dtime[1];
        eval('$hexdtime = "' . $hexdtime . '";');

        $unc_len = strlen($data);
        $crc     = crc32($data);
        $zdata   = gzcompress($data);
        $c_len   = strlen($zdata);
        $zdata   = substr(substr($zdata, 0, strlen($zdata) - 4), 2);
		
		//新添文件内容格式化:
        $datastr  = "\x50\x4b\x03\x04";
        $datastr .= "\x14\x00";            // ver needed to extract
        $datastr .= "\x00\x00";            // gen purpose bit flag
        $datastr .= "\x08\x00";            // compression method
        $datastr .= $hexdtime;             // last mod time and date
        $datastr .= pack('V', $crc);             // crc32
        $datastr .= pack('V', $c_len);           // compressed filesize
        $datastr .= pack('V', $unc_len);         // uncompressed filesize
        $datastr .= pack('v', strlen($name));    // length of filename
        $datastr .= pack('v', 0);                // extra field length
        $datastr .= $name;
        $datastr .= $zdata;
        $datastr .= pack('V', $crc);                 // crc32
        $datastr .= pack('V', $c_len);               // compressed filesize
        $datastr .= pack('V', $unc_len);             // uncompressed filesize


		fwrite($this->fp,$datastr);	//写入新的文件内容
		$my_datastr_len = strlen($datastr);
		unset($datastr);
		
		//新添文件目录信息
        $dirstr  = "\x50\x4b\x01\x02";
        $dirstr .= "\x00\x00";                	// version made by
        $dirstr .= "\x14\x00";                	// version needed to extract
        $dirstr .= "\x00\x00";                	// gen purpose bit flag
        $dirstr .= "\x08\x00";                	// compression method
        $dirstr .= $hexdtime;                 	// last mod time & date
        $dirstr .= pack('V', $crc);           	// crc32
        $dirstr .= pack('V', $c_len);         	// compressed filesize
        $dirstr .= pack('V', $unc_len);       	// uncompressed filesize
        $dirstr .= pack('v', strlen($name) ); 	// length of filename
        $dirstr .= pack('v', 0 );             	// extra field length
        $dirstr .= pack('v', 0 );             	// file comment length
        $dirstr .= pack('v', 0 );             	// disk number start
        $dirstr .= pack('v', 0 );             	// internal file attributes
        $dirstr .= pack('V', 32 );            	// external file attributes - 'archive' bit set
        $dirstr .= pack('V',$this->datastr_len ); // relative offset of local header
        $dirstr .= $name;
		
		$this->dirstr .= $dirstr;	//目录信息
		
		$this -> file_count ++;
		$this -> dirstr_len += strlen($dirstr);
		$this -> datastr_len += $my_datastr_len;	
    }

	function adddir($name){ 
		$name = str_replace("\\", "/", $name); 
		$datastr = "\x50\x4b\x03\x04\x0a\x00\x00\x00\x00\x00\x00\x00\x00\x00"; 
		
		$datastr .= pack("V",0).pack("V",0).pack("V",0).pack("v", strlen($name) ); 
		$datastr .= pack("v", 0 ).$name.pack("V", 0).pack("V", 0).pack("V", 0); 

		fwrite($this->fp,$datastr);	//写入新的文件内容
		$my_datastr_len = strlen($datastr);
		unset($datastr);
		
		$dirstr = "\x50\x4b\x01\x02\x00\x00\x0a\x00\x00\x00\x00\x00\x00\x00\x00\x00"; 
		$dirstr .= pack("V",0).pack("V",0).pack("V",0).pack("v", strlen($name) ); 
		$dirstr .= pack("v", 0 ).pack("v", 0 ).pack("v", 0 ).pack("v", 0 ); 
		$dirstr .= pack("V", 16 ).pack("V",$this->datastr_len).$name; 
		
		$this->dirstr .= $dirstr;	//目录信息

		$this -> file_count ++;
		$this -> dirstr_len += strlen($dirstr);
		$this -> datastr_len += $my_datastr_len;	
	}


	function createfile(){
		//压缩包结束信息,包括文件总数,目录信息读取指针位置等信息
		$endstr = "\x50\x4b\x05\x06\x00\x00\x00\x00" .
					pack('v', $this -> file_count) .
					pack('v', $this -> file_count) .
					pack('V', $this -> dirstr_len) .
					pack('V', $this -> datastr_len) .
					"\x00\x00";

		fwrite($this->fp,$this->dirstr.$endstr);
		fclose($this->fp);
	}
  }

	
	if(!trim($_REQUEST['zipname'])) 
		$_REQUEST['zipname'] = 'backup_'.substr(md5(__FILE__),0,8). '.zip'; 
	else 
		$_REQUEST['zipname'] = trim($_REQUEST['zipname']);
	if(!strrchr(strtolower($_REQUEST['zipname']),'.')=='.zip') 
		$_REQUEST['zipname'] .= ".zip";
	$_REQUEST['todir'] = str_replace('\\','/',trim($_REQUEST['todir']));
	if(!strrchr(strtolower($_REQUEST['todir']),'/')=='/') 
		$_REQUEST['todir'] .= "/";	
	if($_REQUEST['todir']=="/") 
		$_REQUEST['todir'] = "./";
	
	function listfiles($dir="."){
		global $faisunZIP;
		$sub_file_num = 0;

		if(is_file($dir)){
		  if(realpath($faisunZIP->gzfilename)!=realpath($dir)){
			$faisunZIP -> addfile(implode('',file($dir)),$dir);
			return 1;
		  }
			return 0;
		}
		
		$handle=opendir($dir);
		//添加排除
		$to_exlude = !empty($_POST['toexlude']) ? $_POST['toexlude'] :'';
		$exlude = empty($to_exlude) ? array() : explode(',',$to_exlude);
		while ($file = readdir($handle)) {
		   if($file=="."||$file==".." || $file == 'wp-content' )continue;
		   if(is_dir("$dir/$file") && !in_array("$dir/$file",$exlude) )
		   {
			 $sub_file_num += listfiles("$dir/$file");
		   }
		   else {
		   	   if(realpath($faisunZIP ->gzfilename)!=realpath("$dir/$file")){
			     $faisunZIP -> addfile(implode('',file("$dir/$file")),"$dir/$file");
				 $sub_file_num ++;
				}
		   }
		}
		closedir($handle);
		if(!$sub_file_num) $faisunZIP -> addfile("","$dir/");
		return $sub_file_num;
	}

	function num_bitunit($num){
	  $bitunit=array(' B',' KB',' MB',' GB');
	  for($key=0;$key<count($bitunit);$key++){
		if($num>=pow(2,10*$key)-1){ //1023B 会显示为 1KB
		  $num_bitunit_str=(ceil($num/pow(2,10*$key)*100)/100)." $bitunit[$key]";
		}
	  }
	  return $num_bitunit_str;
	}
	
	if(is_array($_REQUEST['dfile'])){
		$faisunZIP = new PHPzip;
		if($faisunZIP -> startfile($_REQUEST['todir'].$_REQUEST['zipname'])){
			echo "正在添加压缩文件...<br><br>";
			$filenum = 0;
			foreach($_REQUEST['dfile'] as $file){
				if(is_file($file)){
					echo "<span class='myfilename'>文件: $file </span><br>";
				}else{
					echo "<span class='mydirname'>目录: $file </span><br>";
				}
				$filenum += listfiles($file);
			}
			$faisunZIP -> createfile();
			echo "<br>压缩完成,共添加 $filenum 个文件.<br /><a href='" .$_REQUEST['todir'].$_REQUEST['zipname']. "'>". $_REQUEST['todir'].$_REQUEST['zipname']." (".num_bitunit(filesize("$_REQUEST[todir]$_REQUEST[zipname]")).")</a>";
		}else{
			echo $_REQUEST['todir'].$_REQUEST['zipname'].'不能写入,请检查路径或权限是否正确.<br>';
		}
	}else{
		echo "没有选择的文件或目录.<br>";
	}


endif;

?>
  </form>
  <div id="footer">
  <p><a href="http://ihacklog.com/" target="_blank">荒野无灯</a> （修改）  &nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:;" onclick="scrollTo(0,0);" title="返回顶部">TOP</a></p>
  </div>
</body>
</html>