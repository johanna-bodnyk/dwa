$(document).ready(function() {
	a = 48;
	b = 1;
	x = [a,b];
	f1 = new Fraction(16);
	f2 = new Fraction(48);
	console.log(f1);
	console.log(f2);
	$('body').html(f1.toString()+' /<br>');
	$('body').append(f2.toString()+' =<br>');
	$('body').append(f1.divide(f2).toString());
});