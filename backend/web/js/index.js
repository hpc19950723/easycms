$(function() {
    $(".touxiang").change(function() {
        if(checkPic()){
            $(".tximg").attr("src", preImg(this.id, 'Preview'));
        }
    })

    function checkPic() {
        var picPath = $(".touxiang").val()
        var type = picPath.substring(picPath.lastIndexOf(".") + 1, picPath.length).toLowerCase();
        if (type != "jpg" && type != "bmp" && type != "gif" && type != "png") {
            alert("请上传正确的图片格式");
            return false;
        }
        return true;
    }

    function preImg(sourceId, targetId) {
        if (typeof FileReader === 'undefined') {
            alert('Your browser does not support FileReader...');
            return;
        }
        var reader = new FileReader();

        reader.onload = function(e) {
            var img = document.getElementById(targetId);
            img.src = this.result;
            //						$(".aaa").html(img.src);
            return img.src;
        }
        reader.readAsDataURL(document.getElementById(sourceId).files[0]);
    }
    
    /* checkbox switch */
    if ($('[data-toggle="switch"]').length) {
      $('[data-toggle="switch"]').bootstrapSwitch();
    }
    
    $('[data-toggle="switch"]').on('switchChange.bootstrapSwitch', function(event, state) {
//          console.log(this); // DOM element
//  console.log(event); // jQuery event
//  console.log(state); // true | false
        var $this = $(this);
        $.ajax({
            type: 'GET',
            url: $this.data('ajax-url'),
            dataType: 'json'
        });
    });
    
    $(".dw_left").height($(".dw_height").height());
});