$app.qzone={
    sendBlog:function(b,d){
        var e=document.getElementById("formQZoneBlog");
        var c="http://act.qzone.qq.com/user_v3/freereg.php?domain="+document.domain+"&script&callback=parent."+d+"&act_id="+b;
        if(!e){
            var a=$app.G("qzoneBCont");
            if(!a){
                var a=document.createElement("div");
                a.id="qzoneBCont";
                a.style.display="none";
                document.body.appendChild(a)
                }
                a.innerHTML='<form id="formQZoneBlog" action="'+c+'" style="display:none" method="post" target="foobarQZoneBlog"><input type="hidden" name="post_blog" value="0" /></form><iframe id="foobarQZoneBlog" name="foobarQZoneBlog" style="display:none;"></iframe>'
            }
            $app.G("formQZoneBlog").submit()
        },
    sendHang:function(b,d,e){
        var f=$app.G("formQZoneHang");
        var c="http://act.qzone.qq.com/user_v3/freereg.php?domain="+document.domain+"&script&callback=parent."+e+"&act_id="+b;
        if(!f){
            var a=$app.G("qzoneHCont");
            if(!a){
                var a=document.createElement("div");
                a.id="qzoneHCont";
                a.style.display="none";
                document.body.appendChild(a)
                }
                a.innerHTML='<form id="formQZoneHang" action="'+c+'" style="display:none" method="post" target="foobarQZoneHang"><input type="hidden" name="hang_annex" value="'+d+'" /></form><iframe id="foobarQZoneHang" name="foobarQZoneHang" style="display:none;"></iframe>'
            }
            $app.G("formQZoneHang").submit()
        },
    share:function(a){
        var b="http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url="+encodeURIComponent(a);
        window.open(b,"_blank")
        },
    like:function(a){
        var c=$app.G("qzonelike");
        if(!c){
            var b="http://open.qzone.qq.com/like?url=http%3A%2F%2Fuser.qzone.qq.com%2F"+a+"&type=button_num&width=100&height=30";
            var c=document.createElement("iframe");
            c.id="qzonelike";
            c.src=b;
            c.style.display="none";
            document.body.appendChild(c);
            if(c.attachEvent){
                c.attachEvent("onload",function(){
                    document.getElementById("qzonelike").contentWindow.QZONE.TC.Like.clickLikeBtn()
                    })
                }else{
                c.onload=function(){
                    document.getElementById("qzonelike").contentWindow.QZONE.TC.Like.clickLikeBtn()
                    }
                }
        }else{
    document.getElementById("qzonelike").contentWindow.QZONE.TC.Like.clickLikeBtn()
    }
}
};