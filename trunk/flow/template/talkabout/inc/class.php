<?php
class ExecTime{
	private $startTime;
	private $endTime;
	function __construct(){
		$this->startTime=$this->getTime();
	}
	private function getTime(){
		list($msec, $sec) = explode(" ",microtime());
		return ((float)$msec + (float)$sec);
	}
	private  function calTime(){
		return $this->endTime-$this->startTime;
	}
	public function costTime(){
		$this->endTime=$this->getTime();
		$costTime=$this->calTime();
		printf("%.4f",$costTime);
	}
}

class showPage
{
	var $title='';
	/*
	* ÿҳ��ʾ��¼��
	* @var integer
	*/
	var $psize                =  10;

	/*
	* ҳ��ƫ����
	* @var integer
	*/
	var $pernum               =  5;

	/*
	* Ҫ���ݵı�������
	* @var string
	*/
	var $varstr               =  '';

	/*
	* ��ҳ��
	* @var integer
	*/
	var $tpage                =  0;

	/*
	* ��¼������
	* @var integer
	*/
	var $pers                 =  0;

	/*
	* SQL server ��ҳ֧��
	* @var integer
	*/
	var $sid                  = 0;

	/*
	* ��ǰҳ��
	* @var integer
	*/
	var $page                 = 0;

	/*
	* MySQL��ҳ�������
	* @var string
	*/
	var $limit                =  ' limit ';


	/*
	* ȡ�ô��ݱ���������,��������Ƿ�Ϊ��
	* @return v
	*/
	function get()
	{
		$i = 0;
		foreach($_GET as $k => $v) {
			$i++;
			$str          = ($i==1)      ? '?'            : '&';
			$this->varstr = ($k<>'page') ? $this->varstr.$str.$k.'='.$v : $this->varstr;
		}
		$this->varstr = $this->varstr ? $this->varstr.'&' : '?';
		$this->page   = (isset($_GET['page']) && is_numeric($_GET['page'])) ? intval($_GET['page']) : 1;


		// ���� SQL server ��ҳ��¼��ȡֵ //
		$this->sid    = ($this->page-1)*$this->psize;

		// ���� MySQL ��ҳ������� //
		$this->limit  .= ($this->page -1)*$this->psize.','.$this->psize;
	}


	/*
	* ͳ��ҳ����
	*/
	function total($number)
	{
		$this->tpage = ceil($number / $this->psize);
		$this->pers  = ceil($this->tpage / $this->pernum);
	}

	/*
	* ���ຯ��PP(parse page ),$pageΪ��ҳ��
	* $number Ϊ��¼����, $psize Ϊÿҳ��ʾ��Ŀ
	* @return string
	*/
	function PP($number=0 ,$psize=0)
	{
		$this->psize  = $psize ? $psize : $this->psize;
		$this->get();
		$this->total($number);

		$setpage   = $this->page ? ceil($this->page/$this->pernum) : 1;

		$pagenum   = ($this->tpage > $this->pernum) ? $this->pernum : $this->tpage;
		if ($number  <= $this->psize) {
			$text  = '1ҳ (��'.$number.'��)';
		} else {
			$text ="&nbsp;";
			if ($this->page > 1) {
				$text .= '<a class="page" title=��һҳ href='.$this->varstr.'page=1>��ҳ</a>';
			}
			if ($setpage > 1) {
				$lastsetid = ($setpage-1)*$this->pernum;
				$text .= '<a class="page" title=��һ�� href='.$this->varstr.'page='.$lastsetid.'><<��һ��</a>';
			}
			if ($this->page > 1) {
				$pre = $this->page-1;
				$text .= '<a class="page" title=��һҳ href='.$this->varstr.'page='.$pre.'><��һҳ</a>';
			}
			$i = ($setpage-1)*$this->pernum;
			for($j=$i; $j<($i+$pagenum) && $j<$this->tpage; $j++) {
				$newpage = $j+1;
				if ($this->page == $j+1) {
					$text .= '<span style="color:red!important">['.($j+1).']</span>';
				} else {
					$text .= '<a class="page" href='.$this->varstr.'page='.$newpage.'>['.($j+1).']</a>';
				}
			}
			if ($this->page < $this->tpage){
				$next = $this->page+1;
				$text .= '<a class="page" title=��һҳ href='.$this->varstr.'page='.$next.'>��һҳ></a> ';
			}
			if ($setpage < $this->pers) {
				$nextpre = $setpage*($this->pernum+1);
				$text .= '<a class="page" title=��һ�� href='.$this->varstr.'page='.$nextpre.'>��һ��>></a>';
			}
			if ($this->page < $this->tpage) {
				$text .= '<a class="page" title=���һҳ href='.$this->varstr.'page='.$this->tpage.'>ĩҳ</a>';
			}
			$text.='��'.$this->tpage.'ҳ(ÿҳ'.$this->psize.'�� ��'.$number.'��)';
		}
		return $text;
	}


}


/**
 * @package Attachment
 */
class Attachment extends showPage {
	/**
	 * resource
	 *
	 * @var mysqlresource
	 */
	private $conn;

	/**
	 * Constructor...
	 *
	 * @param MysqlResource $conn
	 */
	function __construct($conn){
		$this->conn=$conn;
	}
	function returnAttachmentByAttachmentID($aid){
		$sql="select * from `ss_attachment` where `aid`=".$aid;
		return $this->conn->myquery1($sql,1);
	}
	function returnAttachmentByArticleID($tid){
		$sql="select * from `ss_attachment` where `tid`=".$tid;
		return $this->conn->myquery1($sql,1);
	}
	function editAttachment($attachment,$originName,$filetype,$filesize,$isimage,$tid,$new=1){
		$sql="";
		if (!$new) {
			$sql="delete from `ss_attachment` where `tid`=$tid";
			$this->conn->myquery2($sql);
			$sql="";
		}
		for ($i=0;$i<count($attachment);$i++){
			$sql.="insert into `ss_attachment` (`tid`,`filename`,`originname`,`filetype`,`filesize`,`isimage`) values
		   ($tid,'{$attachment[$i]}','{$originName[$i]}','{$filetype[$i]}',{$filesize[$i]},{$isimage[$i]});";
		}
		if($sql!=""){
		return $this->conn->multi_query($sql);
		}
		else{
		return true;
		}
		
	}
	public function deleteAttachment($aid){
		$sql="delete from `ss_attachment` where aid=".$aid;
		return $this->conn->myquery2();
	}
	function addClicks($aid){
		$sql="update ss_attachment set `clicks`=`clicks`+1 where aid=$aid ";
		return $this->conn->myquery2($sql);
	}
}
?>