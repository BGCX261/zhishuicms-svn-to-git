//��������     
  self.onError=null;           
  currentX   =   currentY   =   0;           
  whichIt   =   null;           
  lastScrollX   =   0;   //�������߾���ĸ�ֵ   
  lastScrollY   =0;     //����붥���ĸ߶ȵĸ�ֵ   
        
  //----------------------start   fun��ִ��һ��         
  function   heartBeat(id1,id2)   {           
  diffY   =   document.body.scrollTop;   
  diffX   =   document.body.scrollLeft;     
    
  if(diffY   !=   lastScrollY)   {           
  percent   =   .1   *   (diffY   -   lastScrollY);           
  if(percent   >   0)   percent   =   Math.ceil(percent);           
  else   percent   =   Math.floor(percent);           
  id1.style.pixelTop   +=   percent;   
  id2.style.pixelTop   +=   percent;         
  lastScrollY   =   lastScrollY   +   percent;           
  }           
    
  if(diffX   !=   lastScrollX)   {           
  percent   =   .1   *   (diffX   -   lastScrollX);           
  if(percent   >   0)   percent   =   Math.ceil(percent);           
  else   percent   =   Math.floor(percent);           
  id1.style.pixelTop   +=   percent;   
  id2.style.pixelTop   +=   percent;               
  lastScrollX   =   lastScrollX   +   percent;           
  }           
  }           
  //-----------------------end   fun   
  scr=screen.width   
  left_1=(scr>800)?10:145   
  right_1=(scr>800)?880:580   
    
  //���ͼƬ   
  document.write("<DIV   id=f1   style='left:   "+left_1+"px;   top:   215px;   POSITION:   absolute;'><a   href=glxj.html   target=_blank><img   src=template/home/images/junxunwang.jpg   border=0   width=180   height=400></a></div>")   
    

  action   =   window.setInterval("heartBeat(f1,f2)",50);           
