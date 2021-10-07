/*
    封装一个基于jquery的ajax
*/
function ajaxPost(url,params,callBack){
    // let headers = {
    //     'Content-Type': 'application/json', 
    // }
    let headers = {
        'Content-Type' : 'application/x-www-form-urlencoded;charset=utf-8' 
    }
    // Authorization
    $.ajax({
        url:url,
        data:params,
        type:"post",
        headers : headers, 
        success:function(res){
            if(res.code == 200){
                callBack(res)
            }else{
                layer.alert("请求失败");
            }
        }
    })
}