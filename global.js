function logout(path,redirect)
{
	$.get(path);
	if(redirect)
		location.href = redirect;
	else
		location.reload(true);
}