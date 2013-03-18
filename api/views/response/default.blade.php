<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>MyApp: Confirm your register</title>
	<style type="text/css">
		body{font: 12px HelveticaNeue,Arial,sans-serif;}
		a, a:hover {color:#2f5a67;text-decoration:none;}
		#content a{ background:#2f5a67;text-decoration:none;color:white; font-weight: bold; padding: 1px;}
		#content p{text-align: justify;}		
    </style>
</head>

<body bgcolor="whiteSmoke" style="background:whiteSmoke;font: 12px HelveticaNeue,Arial,sans-serif;">
<table style="color:#333;background:#fff; border: none; width:600px; margin-left:auto;margin-right:auto;" align="center" cellspacing="0" cellpadding="0" border="0">
	<tbody>
    	<tr width="100%">
			<td valign="top" align="center" style="background:whiteSmoke;" >
				<table style="border: none; padding: 0 20px; margin: 30px auto 50px; width:600px;" cellspacing="0" cellpadding="0" border="0" border="0">
                	<tbody>
                    	<tr width="100%" border="0"> 
							<td valign="top" align="left" style="background: white; padding:10px; background: #888;"> 
                            	<!-- <img src="top.jpg" title="MyApp's Slogan"/> -->
                            	<p> You should rather use a nice image! </p>
                             </td>
                        </tr>
                        <tr width="100%" border="0">
                        	<td valign="top" align="left" style="background:#fff; padding:20px;font-size:13px;line-height:20px;">
								<h1 style="margin-top: 0;line-height:28px;">Hello <?=$name;?>,</h1>
								<p id="content">Thank you for joining us, but in order to complete the registration processs, please activate your account by clicking the following button</p>
                                
                                <p>
                                	<a href="<?=$link;?>" style="-webkit-appearance: none; border-radius: 3px; -moz-border-radius:3px; -webkit-border-radius: 3px; background: #888; border: 1px solid =#2F7D; color: #fff;cursor: pointer; display: inline-block; font-weight:700; line-height: 20px; margin: 0 auto 30px; padding: 6px 15px; text-decoration: none;">
                                		Activate * 
                                	</a>
                                </p>

                                <sub> * If the button above does not work, copy and paste this link in your browser: <?=$link;?></sub>
                                
                                <div style="border:1px dotted #dddddd;width:100%;margin-top:25px;margin-bottom:25px;"></div>
                                
								<p>
									<span style="font-size:11px;">
										<b>MyApp.com</b> <?=date('Y');?> &copy; All rights reserved.
									</span>
								</p>
							</td>								
                        </tr>
                        
                    </tbody>
               </table>
			</td>
		</tr>
	</tbody>
</table>
</body>
</html>