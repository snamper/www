
var getEleHeight=(function() {
	
		return function getEleHeight (obj, btn) {
				//$('#answer')的高度的函数
				function getAnswerHeight () {
					
					//获取屏幕可视区的高度
					var pageHeight=window.innerHeight||document.documentElement.clientHeight||document.body.clientHeight;
					
					//每次设置高度前先清空$('#answer')的高度
					obj.css('height','');		
					
					//获取$('#answer')计算后的高度（包括style和link的高度的样式）
					var answerHeight=obj.height()+300;

					console.log(answerHeight, pageHeight);

					//如果answer的高度小于屏幕的高度	
					if (answerHeight<pageHeight) {
						
						//返回屏幕的高度
						return pageHeight;
						
					}else{
						//返回自己的高度
						return answerHeight;
						
					}								
					
				};
				
				
				//页面刚加载完毕执行一次
				var $answerHeight=getAnswerHeight();
				
				$(window).scrollTop(0);
				
				//设置$('#answer')的高度
				obj.css('height',$answerHeight);
			
				//当点击确定或者下一题的时候执行函数
				btn.click(function() {
					
					//延迟一秒钟执行才能获取到当前页面的高度
					setTimeout(function () {
						
						//再次执行
						var $answerHeight=getAnswerHeight();			
						
						//重新设置$('#answer')的高度
						obj.css('height',$answerHeight);

						$(window).scrollTop(0);
						
					},600);
					
				});
		
					
				
			}
		
	})();