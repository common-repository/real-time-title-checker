//console.log('loaded!');
jQuery( function(){
	searchWord = function(){
		var searchText = $(this).val().toLowerCase(), // 検索ボックスに入力された値
			textLength = jQuery(this).val().length,
        	targetText,
        	regex,
        	splitArray,
        	flg,
        	boxId = '#rt-title-wrap';

        if(jQuery("input[name='wordsflg']").length){
        	regex = (/\s|[[:blank:]]/g);
        }else{
        	regex = " ";
        }
        splitArray = searchText.split(regex);
        
        //エリア全体の表示非表示
		if( textLength > 0 ){
			jQuery(boxId).removeClass('hidden');
		}else{
			jQuery(boxId).addClass('hidden');
		}


		//候補の絞り込み
		jQuery(boxId + " li").each(function() {
			
			targetText = jQuery(this).children('.target').text().toLowerCase();

			if(splitArray.length == 1){
			//語句が1つの場合
				if (targetText.indexOf(searchText) == -1) {
					jQuery(this).addClass('hidden');
				} else {
					jQuery(this).removeClass('hidden');
				}
			}else{
			//語句が2つ以上の場合
				flg = 1;
				jQuery.each(splitArray,function(index, elem) {
			    	if (targetText.indexOf(elem) == -1) {
			    		flg = 0;
			    	}
				});
			    if(flg == 0){
			    	jQuery(this).addClass('hidden');
			    }else{
			    	jQuery(this).removeClass('hidden');
			    }
			}
		});
	};
	jQuery("input#title").on('input', searchWord);
	jQuery("#rt-title-wrap .button").click(function() {
        if(jQuery("#rt-title").hasClass('hidden')){
        	jQuery("#rt-title").removeClass('hidden');
        	jQuery(".rt-button .button .off").removeClass('hidden');
        	jQuery(".rt-button .button .on").addClass('hidden');
        }else{
        	jQuery("#rt-title").addClass('hidden');
        	jQuery(".rt-button .button .on").removeClass('hidden');
        	jQuery(".rt-button .button .off").addClass('hidden');
        }
    })
	
});
