/*----------------------------------
	jQuery autocomplete for unit field
-----------------------------------*/
/* $(function() {
        var unit_list = [
            "teaspoon",
			"tablespoon",
			"ounce",
			"fluid ounce",
			"cup",
			"pint",
			"quart",
			"gallon"
        ];
        $( "#unit" ).autocomplete({
            source: unit_list
        });
    }); */


/*----------------------------------
	Conversions and synonyms
-----------------------------------*/

//Units and number of teaspoons in each unit
var units = [];
units['teaspoon'] = 1;
units['tablespoon'] = 3;
units['fluid ounce'] = 6;
units['cup'] = 48;
units['pint'] = 96;
units['quart'] = 192;
units['gallon'] = 768;

//Synonyms for units
var synonyms = [];
synonyms['teaspoon'] = ['teaspoon', 'tsp', 't'];
synonyms['tablespoon'] = ['tablespoon', 'tbsp', 'T'];
synonyms['fluid ounce'] = ['fluid ounce', 'ounce', 'oz'];
synonyms['cup'] = ['cup', 'cp', 'c'];	
synonyms['pint'] = ['pint', 'pt'];
synonyms['quart'] = ['quart', 'qt'];
synonyms['gallon'] = ['gallon', 'gal', 'g'];


/*----------------------------------
	Convert decimal to fraction
-----------------------------------*/
// Code adapted from http://www.dynamicguru.com/javascript/javascript-function-to-convert-decimal-fraction/

function d_to_f(number) {

	var decimal=number.substr(number.lastIndexOf('.'));
	var zeroes = decimal.length-1;
	var den = Math.pow(10,zeroes);
	var num=number*den;

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
	
	return fraction;
};


/*----------------------------------
	Convert fraction to decimal
-----------------------------------*/
function f_to_d(number) {
	
	var whole_number = number.substring(0,number.indexOf(' '));
	var num = number.substring((number.indexOf(' ')+1),number.indexOf('/'));
	var den = number.substring((number.indexOf('/')+1));
	var decimal = Number(whole_number)+num/den;
	return decimal;
	
};


/*----------------------------------
	Standardize unit name
-----------------------------------*/
function standardizeUnit(unit_entered) {
	
	//Trim "s" or "." off unit if needed
	var last_letter = unit_entered.substring(unit_entered.length-1);
	if (last_letter == "s" || last_letter == ".") {
		unit_entered = unit_entered.substring(0,unit_entered.length-1);
	}

	//If unit entered is not "T" or "t", convert to lowercase
	if (unit_entered != "T" && unit_entered != "t")
	{
		unit_entered = unit_entered.toLowerCase();
	}

	var standard_unit = "";
	
	for (i in synonyms) {
		if (synonyms[i].indexOf(unit_entered) != -1) {
			standard_unit = i;
		}
	}
	
	return standard_unit;
}

/*----------------------------------
	Generate list of conversions
-----------------------------------*/
function convert(amt_in_tsps) {
	
	var conversion_list = "";
	
	for(i in units) {
		var unit = i;
		var amount = amt_in_tsps / units[i];
		
		amount = Math.floor(amount*1000000000000)/1000000000000
		
		//If amount is greater than 1, make the unit label plural
		if (amount > 1) {
			unit = unit+"s";
		}
		
		//If amount is not a whole number, convert decimal to a fraction for display
		if (String(amount).indexOf('.')!=-1) {
			var amount = d_to_f(String(amount));
		} 
		
		conversion_list += "<p>"+amount + " " + unit +"</p>";
	}
	
	return conversion_list;
};
	
/*----------------------------------
	Find best answer
-----------------------------------*/

function findBestAnswer(amt_in_tsps) {
	
	var best_answer = "";
	var remainder; 
	
	while (amt_in_tsps > 0) {
		
		//Find amount in cups to 1/4 cups
		if (amt_in_tsps >= 12) {
			remainder = amt_in_tsps % 12;
			best_answer += (amt_in_tsps-remainder)/48+" cup";
			if (amt_in_tsps >= 60) {
				best_answer += "s";
			}
			if (remainder != 0){
				best_answer += "+";
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


/*----------------------------------
	Document ready
-----------------------------------*/

$(document).ready(function() {
	
	var amt_entered;
	var unit_entered;
	var scale = "1";
	
	$('input[name=amount]').keyup(function() {
		amt_entered = $(this).val();
		displayResults();
	});
	
	$('input[name=unit]').keyup(function() {
		unit_entered = $(this).val();
		displayResults();
	});
	
	$('input[name=scale]').keyup(function() {
		scale = $(this).val();
		displayResults();
	});
	
	function displayResults() {
		if (amt_entered && unit_entered) {
			
			console.log("Amount entered is "+amt_entered);
			console.log("Unit entered is "+unit_entered);
			
			console.log("Scale is "+scale);
			
			if (scale.indexOf('/')!=-1) {
				scale = f_to_d(scale);
			}
			
			console.log("Decimal scale is "+scale);
			
 			unit_entered = standardizeUnit(unit_entered);
			
			console.log("Standard unit is "+unit_entered);
			
			if (amt_entered.indexOf('/')!=-1) {
				amt_entered = f_to_d(amt_entered);
			}
			
			console.log("Decimal amount is "+amt_entered);
			
			var scaled_amt = amt_entered * scale;
			
			var amt_in_tsps = scaled_amt * units[unit_entered];
			
			$('#conversions').html("<p>Best answer: "+findBestAnswer(amt_in_tsps)+"</p>");						
			$('#conversions').append(convert(amt_in_tsps));
			
		}
	};

});