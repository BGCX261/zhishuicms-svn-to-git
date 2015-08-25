function IndexBuy()
{
	this.Speed=10;
	this.StopSpeed=5000;
	this.MoveInterval=null;
	this.StopTimeOut=null;
	this.MoveImgInterval=null;
	this.BizListData=[
					  {"id":32963,"name":"折扣信息"},
					  {"id":32965,"name":"兜转杭城"},
					  {"id":139909,"name":"食尚"},
					  {"id":33023,"name":"旅游"},
					  {"id":139910,"name":"文驿"},
					  {"id":139911,"name":"美颜"},
					  {"id":139912,"name":"服饰"},
					  {"id":139913,"name":"数码"},
					  {"id":139914,"name":"影视"},
					  {"id":139915,"name":"音乐"},
					  {"id":139916,"name":"动漫"},
					  {"id":139917,"name":"flash"},
					  {"id":139918,"name":"下载专区"}
					  ];
	this.BizProductData=null;
	this.BizListLen=0;
	this.Main=null;
	this.ImgMain=null;
	this.UL1=null;
	this.UL2=null;
	this.ULLineHeight=0;
	this.ULImg1=null;
	this.ULImg2=null;
	this.ULImgLineHeight=0;
	this.IsPause=false;
	this.Clicking=false;
	this.MoveNum=0;
	this.MoveImgNum=0;
	this.BizList=function(){
		var items = "";
		var imgs = "";
		if(this.BizListData.length > 0 && this.BizProductData.length > 0){
			this.BizListLen=this.BizListData.length;
			for (var i=0;i < this.BizListData.length ;i++ )
			{
				items += "<li onclick=\"javascript:indexbuy.ClickUp("+i+",this);\">"+
					this.BizListData[i].name+
					"</li>";
			}
			document.getElementById("recommend-item").getElementsByTagName("ul")[0].innerHTML=items;
			for(var j = 0;j < this.BizProductData.length; j++){
				var tmp = this.BizProductData[j];
				for (var x = 0; x < 3 ; x++)
				{
					imgs +="<li class=\"imgList\"><a href=\""+"redir.php?catalog_id="+
							this.BizListData[j].id+
							"&object_id="+
							tmp[x].id+
							"\" target=\"_blank\" title=\""+
							tmp[x].name+
							"\"><img src=\""+
							tmp[x].cover+
							"\" height=\"92\" alt=\"\" width=\"86\" /></a></li>";
				}
			}
			document.getElementById("recommend-item-img").getElementsByTagName("ul")[0].innerHTML=imgs;
			this.copyUl();
		}
	};
	this.copyUl=function(){
		try{
			var uls=document.getElementById("recommend-item").getElementsByTagName("ul");
			document.getElementById("recommend-item").appendChild(uls[0].cloneNode(true));
			var ulImgs=document.getElementById("recommend-item-img").getElementsByTagName("ul");
			document.getElementById("recommend-item-img").appendChild(ulImgs[0].cloneNode(true));
			this.Main=document.getElementById("recommend-item");
			this.ImgMain=document.getElementById("recommend-item-img");
			this.UL1=uls[0];
			this.UL2=uls[1];
			this.ULImg1=ulImgs[0];
			this.ULImg2=ulImgs[1];
			this.ULImg1.style.height=(92*this.BizListLen)+"px";
			this.ULImg2.style.height=(92*this.BizListLen)+"px";
			this.ImgMain.scrollTop=184;
			this.Main.scrollTop = 0;
			this.ULLineHeight=parseInt(this.UL1.offsetHeight/this.BizListLen);
			this.ULImgLineHeight=parseInt(this.ULImg1.offsetHeight/this.BizListLen);
			this.MoveTimeoutStart();
		}catch(e){alert(e);}
	};
	this.Move=function(num){
		if(this.UL2.offsetTop - this.Main.scrollTop < 0) 
		{
			this.Main.scrollTop -= this.UL1.offsetHeight;
		}
		else
		{
			this.Main.scrollTop+=num;
			if(this.Main.scrollTop % this.ULLineHeight == 0){
				this.MoveNum++;
				if(this.MoveNum == num){
					try{
						clearInterval(this.MoveInterval);
						this.MoveInterval = null;
					}catch(e){}
					if(!this.Clicking){
						this.MoveTimeoutStart();
					}
					this.MoveImgStart(num);
				}
			}
		}
	};
	this.MoveStart=function(num){
		if(indexbuy.MoveInterval == null){
			indexbuy.MoveNum = 0;
			indexbuy.MoveInterval = setInterval("indexbuy.Move("+String(num)+");",indexbuy.Speed);
		}
	};
	this.MoveDown=function(num){
		if(this.Main.scrollTop <= 0) 
		{
			this.Main.scrollTop += this.UL1.offsetHeight;
		}
		else
		{
			this.Main.scrollTop-=num;
			if(this.Main.scrollTop % this.ULLineHeight == 0){
				this.MoveNum++;
				if(this.MoveNum == num){
					try{
						clearInterval(this.MoveInterval);
						this.MoveInterval = null;
					}catch(e){}
					if(!this.Clicking){
						this.MoveTimeoutStart();
					}
					this.MoveDownImgStart(num);
				}
			}
		}
	};
	this.MoveDownStart=function(num){
		if(indexbuy.MoveInterval == null){
			indexbuy.MoveNum = 0;
			indexbuy.MoveInterval = setInterval("indexbuy.MoveDown("+String(num)+");",indexbuy.Speed);
		}
	};
	this.MoveTimeoutStart=function(){
		if(!indexbuy.IsPause){
			indexbuy.StopTimeOut = setTimeout("indexbuy.MoveStart(1);",this.StopSpeed);
		}
	};
	this.MoveTimeoutPause=function(){
		try{
				clearTimeout(indexbuy.StopTimeOut);
				indexbuy.StopTimeOut = null;
			}catch(e){}
	};
	this.MoveImg=function(num){
		if(this.ULImg2.offsetTop - this.ImgMain.scrollTop < 0) 
		{
			this.ImgMain.scrollTop -= this.ULImg1.offsetHeight;
		}
		else
		{
			this.ImgMain.scrollTop+=num;
			if(this.ImgMain.scrollTop % this.ULImgLineHeight == 0){
				this.MoveImgNum++;
				if(this.MoveImgNum == num){
					try{
						clearInterval(this.MoveImgInterval);
						this.MoveImgInterval = null;
					}catch(e){}
					this.Clicking = false;
				}
			}
		}
	};
	this.MoveImgStart=function(num){
		if(indexbuy.MoveImgInterval == null){
			indexbuy.MoveImgNum = 0;
			indexbuy.MoveImgInterval = setInterval("indexbuy.MoveImg("+String(num)+");",indexbuy.Speed);
		}
	};
	this.MoveDownImg=function(num){
		if(this.ImgMain.scrollTop <= 0) 
		{
			this.ImgMain.scrollTop += this.ULImg1.offsetHeight;
		}
		else
		{
			this.ImgMain.scrollTop-=num;
			if(this.ImgMain.scrollTop % this.ULImgLineHeight == 0){
				this.MoveImgNum++;
				if(this.MoveImgNum == num){
					try{
						clearInterval(this.MoveImgInterval);
						this.MoveImgInterval = null;
					}catch(e){}
					this.Clicking = false;
				}
			}
		}
	};
	this.MoveDownImgStart=function(num){
		if(indexbuy.MoveImgInterval == null){
			indexbuy.MoveImgNum = 0;
			indexbuy.MoveImgInterval = setInterval("indexbuy.MoveDownImg("+String(num)+");",indexbuy.Speed);
		}
	};
	this.ClickUp=function(index,obj){
		if(this.Clicking)
			return;
		var nowIndex = this.Main.scrollTop / this.ULLineHeight;
		if(document.getElementById("recommend-item").getElementsByTagName("ul")[1] == obj.parentNode){
			index += this.BizListLen;
		}
		if(index - nowIndex - 2 == 0)
			return;
		this.Clicking = true;
		switch(index - nowIndex){
			case 0:
				this.MoveDownStart(2);
				break;
			case 1:
				this.MoveDownStart(1);
				break;
			case 3:
				this.MoveStart(1);
				break;
			case 4:
				this.MoveStart(2);
				break;
			default:
				this.Clicking = false;
				break;
		}
	};
};