
var number = 1/6;

console.log(number);

number = Math.floor(number*1000000000000)/1000000000000;

console.log(number);

number = String(number);

console.log(number);

var decimal=number.substr(number.lastIndexOf('.'));

console.log(decimal);

var zeroes = decimal.length-1;
var den = Math.pow(10,zeroes);
var num=number*den;

console.log(num);
console.log(den);

var whole_number = "";
var fraction = "";

if (String(num).indexOf('333') != -1) {
	whole_number = (String(num).substring(0,String(num).indexOf('333');
	fraction = "1/3";
}

else if (String(num).indexOf('666') != -1) {
	whole_number = (String(num).substring(0,String(num).indexOf('666');
	fraction = "2/3";
}


console.log(String(num).indexOf('666'));

for(i=2;num>den?i<num:i<den;i++) {
	if(num%i==0 && den%i==0) {
		den/=i;
		num/=i;
		i=1;
  }
}

var remainder=num%den;
var whole_number=(num-remainder)/den;
var fraction = remainder+"/"+den;
if (whole_number!==0) {
	fraction = whole_number+" "+fraction;
}


console.log(fraction);






function findBestAnswer(amt_in_tsps) {
	
	var best_answer = "<p>";
	var remainder; 
	
	while (amt_in_tsps > 0) {
		
		//Find amount in cups to 1/4 cups
		if (amt_in_tsps >= 12) {
			remainder = amt_in_tsps % 12;
			best_answer += "(amt_in_tsps-remainder)/12+" cup";
			if (amt_in_tsps >= 60) {
				best_answer += "s";
			}
			best_answer += "<br>";		
			amt_in_tsps = remainder;
		}
		
		//Find amount in cups to 1/4 cups
		else if (amt_in_tsps >= 3) {
			remainder = amt_in_tsps % 3;
			best_answer += (amt_in_tsps-remainder)/3+" tablespoon";
			if (amt_in_tsps >= 6) {
				best_answer += "s";
			}
			best_answer += "<br>";		
			amt_in_tsps = remainder;
		}
		
		else {
			best_answer += amt_in_tsps + "teaspoon"
			if (amt_in_tsps > 1) {
				best_answer += "s";
			}
			best_answer += "<br>";
			amt_in_tsps = 0;
		}
	}
	
	return best_answer;
}

console.log(findBestAnswer(60);


cups 1/4 and up 					--> >= 12 teaspoons

tablespoons between 1 and 3.9999 	--> >= 3 teaspoons && < 12 teaspoons

teaspoons less than 3 --> < 3 teaspoons
