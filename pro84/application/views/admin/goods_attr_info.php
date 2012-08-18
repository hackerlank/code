<div>
    <h2><?php echo $title;?>管理</h2>
    <p class="page_info">对<?php echo $title;?>的添加，删除，编辑</p>
</div>
<div class="s_box">
<label>请选择商品分类:</label>
<select name="goodsattr"><option value='0'>请选择</option><?php echo $attr_option;?></select>
<label><?php echo $title;?>:</label>
<input type="text" name='attrinfo' value='' />
<input type="hidden" name="atype" value="<?php echo $type; ?>" />
<input type="button" value="添加" onclick="javascript:addAttrInfo();" /> 
</div>
<table class="datelist-1" style="width:30%;" id="attrinfolists">
<tbody>
</tbody>
</table>
<script type="text/javascript">
function addAttrInfo(type)
{
    var aid = parseInt($("select[name='goodsattr']").val());
    if (!aid) {jsex.dialog.showmsg('请先选择商品分类','温馨提示');return false;}
    
    var attrinfo = $("input[name='attrinfo']").val();
    attrinfo = attrinfo.replace(/\s+/gm, '');
    if ('' == attrinfo) {jsex.dialog.showmsg('请填写要添加的商品属性值');return false;}
    
    var atype = $("input[name='atype']").val();
    
    $.post('/admingoods/addAttrInfo', {'aid': aid, 'attrinfo': attrinfo, 'atype': atype}, function(data){
        jsex.dialog.showmsg(data.msg, '温馨提示');
        if (0 == data.err) getAttrInfoList(aid, atype);
    }, 'json');
}
function getAttrInfoList(aid, atype)
{
    if (aid && atype) {
        $.post('/admingoods/getAttrInfoList', {'aid': aid, 'atype': atype}, function(data){
            var str = '';
            if (data['list'].length> 0) {
                for (var i = 0 , iMax = data['list'].length; i < iMax; i++){ 
                    str += "<tr><td><input type='text' aid='"+data['list'][i]['id']+"' class='goodsattrinfo' value='"+data['list'][i]['val']+"' /></td><td><a href='javascript:;' onclick='javascript:delGoodsAttrInfo("+data['list'][i]['id']+");'>删除</a></td></tr>";
                }
            }
            $('#attrinfolists').html(str);
        },'json');
    }
}
function delGoodsAttrInfo(id)
{
    var sure = confirm("确定要删除吗？");
    if (sure && id) {
        $.post("/admingoods/delAttrinfo", {'id': id}, function(data){
            jsex.dialog.showmsg(data.msg, '温馨提示');
            var aid = $("select[name='goodsattr']").val();
            var atype = $("input[name='atype']").val();
            getAttrInfoList(aid, atype);
        }, 'json');
    }
}
$(function(){
    $("select[name='goodsattr']").live('change',function(){
        var aid = $(this).val();
        var atype = $("input[name='atype']").val();
        getAttrInfoList(aid, atype);
    });
    $(".goodsattrinfo").live('change',function(){
        var aid = parseInt($(this).attr('aid'));
        var attrinfo = $(this).val();
        attrinfo = attrinfo.replace(/\s+/gm, '');
        if ('' == attrinfo) {jsex.dialog('<?php echo $title;?>不能为空', '温馨提示');return false;}
        
        if (aid && attrinfo) {
            $.post('/admingoods/updateAttrInfo', {'id': aid, 'attrinfo': attrinfo}, function(data){
                jsex.dialog.showmsg(data.msg, '温馨提示');
            }, 'json');
        }
    });
});
</script>
