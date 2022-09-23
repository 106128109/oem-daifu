<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>
	批付3
</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
    <script>
        $(function(){
            $("#Button1").click(function(){
                if($("#agentId").val()!="" &&$("#keyDES").val()!="" &&$("#keyMD5").val()!="" &&$("#billId").val()!="" &&$("#cardType").val()!="" &&$("#cardData").val()!="" &&$("#payAmt").val()!="")
                {document.form1.submit();}
                else
                {alert("*必填项，请正确填写！");return false;}
                return false;
            });
			
         
			
        });
        
    </script>

    <style>
        .tab
        {
            border-collapse: collapse;
            width: 700px;
            border: #999 1px dashed;
        }
        .tab td
        {
            padding: 5px;
            border: #999 1px dashed;
        }
    </style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>

    <form name="form1" method="post" action="PostAction.php" id="form1" target="_blank">
    <div style="height: 50px">
    </div>
    <div>
        <table class="tab" width="100%" border="0" align="center">
		<tr>
                <td align="center" colspan="3">
                    <a href="Index.php">批付</a>
                  
                </td>
            </tr>
            <tr>
                <td align="center" colspan="3">
                    批付版本3（<span style="color: Red;">*</span>必填项）<span style="color:#F00">测试案例</span>
                </td>
            </tr>
            <tr>
                <td align="left">
                    商家ID：<span style="color: Red">*</span>
                </td>
                <td>
                    <input name="agentId" type="text" id="agentId" value="" />
                </td>
				<td>商家ID (必填)</td>
            </tr>
			<tr>
                <td align="left">
                    3DES加密密匙：<span style="color: Red">*</span>
                </td>
                <td>
                    <input name="keyDES" type="text" id="keyDES" value="" />
                </td>
				<td>与商户有关</td>
            </tr>
			<tr>
                <td align="left">
                    MD5加密密匙：<span style="color: Red">*</span>
                </td>
                <td>
                    <input name="keyMD5" type="text" id="keyMD5" value="" />
                </td>
				<td>与商户有关</td>
            </tr>
			<tr>
                <td align="left">
                    批付订单号：<span style="color: Red" >*</span>
                </td>
                <td>
                    <input name="batchNo" type="text" id="batchNo" value='<?php echo date('YmdHis', time()) ?>' />
                </td>
				<td>商家提交的唯一订单号（必填）必须唯一6到50位</td>
            </tr>
            <tr>
                <td align="left">
                   付款金额：<span style="color: Red" >*</span>
                </td>
                <td>
                    <input name="batchAmt" type="text" id="batchAmt" value='0.01' />
                </td>
				<td></td>
            </tr>
			<tr>
                <td align="left">
                    银行编号：<span style="color: Red">*</span>
                </td>
                <td>
                    <input name="bankNo" type="text" id="bankNo" value="1" />
                </td>
				<td>见文档</td>
            </tr>
			<tr>
                <td align="left">
                    银行卡号：<span style="color: Red">*</span>
                </td>
                <td>
					<textarea name="bankCardNum" id="bankCardNum" rows="3" cols="30">卡号</textarea>
                </td>
				
            </tr>
			<tr>
                <td align="left">
                    姓名：<span style="color: Red">*</span>
                </td>
                <td>
                    <input name="idCardName" type="text" id="idCardName" value="0.01" />
                </td>
				<td></td>
            </tr>
			<tr>
                <td align="left">
                    商户流水号：<span style="color: Red">*</span>
                </td>
                <td>
                    <input name="billNo" type="text" id="billNo" value="0.01" />
                </td>
				<td></td>
            </tr>
			<tr>
                <td align="left">
                   理由：<span style="color: Red"></span>
                </td>
                <td>
                    <input name="answer" type="text" id="answer" value="" />
                </td>
				<td></td>
            </tr>
			<tr>
                <td align="left">
                    对公对私：<span style="color: Red"></span>
                </td>
                <td>
                    <input name="toWho" type="text" id="toWho" value="" />
                </td>
				<td></td>
            </tr>
			<tr>
                <td align="left">
                    异步通知地址：<span style="color: Red"></span>
                </td>
                <td>
                    <input name="notifyUrl" type="text" id="notifyUrl" value="http://2022.juhe.fk08.cn/Public/heepay/notify.php" />
                </td>
				<td></td>
            </tr>
			<tr>
                <td align="left">
                    自定义备注：<span style="color: Red"></span>
                </td>
                <td>
                    <input name="extParam1" type="text" id="extParam1" value="" />
                </td>
				<td>商户自定义参数或扩展参数，接口按原值返回</td>
            </tr>
            <tr>
                <td align="center" colspan="3">
                    <input type="submit" name="Button1" value="鉴权" id="Button1" />
                </td>
            </tr>
        </table>
    </div>
    </form>
</body>
</html>

