var number = 1/768;

console.log(number);

number = Math.floor(number*100000000000000)/100000000000000;

console.log(number);

number = String(number);

console.log(number);
	
var decimal=number.substr(number.lastIndexOf('.'));
var whole_number = number.substring(0,number.indexOf('.'));
var zeroes = decimal.length-1;
var den = Math.pow(10,zeroes);
var num=decimal*den;

console.log(num);
console.log(den);

if (String(num).indexOf('333') != -1) {
	whole_number = (String(num).substring(0,String(num).indexOf('333');
	fraction = "1/3";
}

else if (String(num).indexOf('666') != -1) {
	whole_number = (String(num).substring(0,String(num).indexOf('666');
	fraction = "2/3";
}


for(i=2;num>den?i<num:i<den;i++) {
	if(num%i==0 && den%i==0) {
		den/=i;
		num/=i;
		i=1;
  }
}

console.log(num);
console.log(den);

var fraction = num+"/"+den;
if (whole_number!=="0") {
	fraction = whole_number+" "+fraction;
}

console.log(fraction);
