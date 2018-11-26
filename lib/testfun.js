function testsex(sex)
{
	if(sex=="男"||sex=="女"||sex=="保密")
		return true;
	return false;
}
function testmailbox(mailbox)
{
	if((/^[\w!#$%&'*+\/=?^_`{|}~-]+(?:\.[\w!#$%&'*+\/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?$/).test(mailbox)
		|| mailbox == "")
		return true;
	else
		return false;
}
function testtel(tel)
{
	if((/^[0-9]{11}$/).test(tel) || tel == "")
		return true;
	else
		return false;
}
function testqq(qq)
{
	if((/^[0-9]{5,10}$/).test(qq) || qq == "")
		return true;
	else
		return false;
}
function testtrue_name(true_name)
{
	if((/^[\u4e00-\u9fa5]{2,6}$/).test(true_name)||true_name == "")
		return true;
	else
		return false;
}