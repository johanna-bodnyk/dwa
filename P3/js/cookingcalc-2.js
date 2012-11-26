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

// function d_to_f(number) {

	// var decimal=number.substr(number.lastIndexOf('.'));
	// var zeroes = decimal.length-1;
	// var den = Math.pow(10,zeroes);
	// var num=number*den;

	// for(i=2;num>den?i<num:i<den;i++) {
		// if(num%i==0 && den%i==0) {
			// den/=i;
			// num/=i;
			// i=1;
	  // }
	// }

	// var remainder=num%den;
	// var whole_number=(num-remainder)/den;
	// var fraction = remainder+"/"+den;
	// if (whole_number!==0) {
		// fraction = whole_number+" "+fraction;
	// }
	
	// return fraction;
// };


/*----------------------------------
	Convert fraction to decimal - DELETE?
-----------------------------------*/
// function f_to_d(number) {
	
	// var whole_number = number.substring(0,number.indexOf(' '));
	// var num = number.substring((number.indexOf(' ')+1),number.indexOf('/'));
	// var den = number.substring((number.indexOf('/')+1));
	// var decimal = Number(whole_number)+num/den;
	// return decimal;
	
// };

/*------------------------------------------------------
	Convert fraction input as a string to a fraction
	object by extracting numerator and denominator to
	feed in seperately to avoid errors with rounding
	and repeating decimals (this part of the fraction.js
	library does not seem to work as described)
-------------------------------------------------------*/
function fractionFromString(number) {
	
	var whole_number = number.substring(0,number.indexOf(' '));
	console.log(whole_number);
	var num = number.substring((number.indexOf(' ')+1),number.indexOf('/'));
	console.log(num);
	var den = number.substring((number.indexOf('/')+1));
	console.log(den);
	num = whole_number*den+(Number(num));
	console.log(num);
	var fraction = new Fraction(Number(num),Number(den));
	return fraction;
	
};


/*----------------------------------
	Standardize unit name
-----------------------------------*/
function standardizeUnitName(unit_entered) {
	
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

	console.log("Amount in tsps is:");
	console.log(amt_in_tsps);
	
	var conversion_list = "";

	for(i in units) {
		
		//Divide amount in teaspoons (as a fraction) by number of teaspoons in this unit (as a fraction) to get equivalent amount in this unit (as a fraction)
		var unit_in_tsps = new Fraction(units[i]);
		var amount = amt_in_tsps.divide(unit_in_tsps);
		
		var unit_name = i;

		//If amount is greater than 1, make the unit label plural
		if (amount.numerator > amount.denominator) {
			unit_name = unit_name+"s";
		}
		
		//Convert amount fraction object to a string for display
		amount = amount.toString();
		
		conversion_list += "<p>"+amount + " " + unit_name +"</p>";
		// conversion_list += "<p>"+ amt_in_tsps + " / " + unit_in_tsps +"</p>";
	}
	
	return conversion_list;
};
	
/*----------------------------------
	Find best answer
-----------------------------------*/

function findBestAnswer(amt_in_tsps) {
	
	var best_answer = "";
	var remainder; 
	var decimal_amount = amt_in_tsps.numerator/amt_in_tsps.denominator;
	
	while (decimal_amount > 0) {
				
		//Find amount in cups to 1/4 cups
		if (decimal_amount >= 12) {
			
			//Isolate the portion of the total that divides evenly into quarter cups and create a Fraction object of that portion
			remainder = decimal_amount % 12;
			decimal_amount -= remainder;
			var cup_portion = new Fraction(decimal_amount);
			//Fraction object for conversion into cups
			var cup_conversion = new Fraction(1,48);
			//Convert portion that divides evenly into cups into cups and add to string that will display best answer
			best_answer += cup_portion.multiply(cup_conversion).toString()+" cup";
			//If we have more than 1 cup, make cups plural
			if (decimal_amount >= 60) {
				best_answer += "s";
			}
			//If there's anything left over, add a + to the string
			if (remainder != 0){
				best_answer += " +";
			}
			best_answer += "<br>";
			
			//Subtract cup portion from fraction object holding amount in tsps
			amt_in_tsps = amt_in_tsps.subtract(cup_portion);
			//Update decimal amount based on new amount in tsps fraction
			decimal_amount = amt_in_tsps.numerator/amt_in_tsps.denominator
		}
		
		//Find amount in tablespoons
		else if (decimal_amount >= 3) {
			
			//Isolate and create fraction object for portion that divides evenly into tablespoons
			remainder = decimal_amount % 3;
			decimal_amount -= remainder;
			var tb_portion = new Fraction(decimal_amount);
			//Fraction object for conversion into tablespoons
			var tb_conversion = new Fraction(1,3);
			//Convert portion that divides evenly into tablespoons into tablespoons and add to string that will display best answer
			best_answer += tb_portion.multiply(tb_conversion).toString()+" tablespoon";
			//If we have more than 1 tablespoon, make cups plural
			if (decimal_amount >= 6) {
				best_answer += "s";
			}
			//If there's anything left over, add a + to the string
			if (remainder != 0){
				best_answer += " +";
			}
			best_answer += "<br>";		
			//Subtract tablespoon portion from fraction object holding amount in tsps
			amt_in_tsps = amt_in_tsps.subtract(tb_portion);
			//Update decimal amount based on new amount in tsps fraction
			decimal_amount = amt_in_tsps.numerator/amt_in_tsps.denominator
		}
		
		else {
			best_answer += amt_in_tsps.toString() + " teaspoon"
			if (decimal_amount > 1) {
				best_answer += "s";
			}
			best_answer += "<br>";
			decimal_amount = 0;
		}
	}
	
	return best_answer;
}


/*----------------------------------
	Document ready
-----------------------------------*/

$(document).ready(function() {
	
	var scale;
	var scale_fraction = new Fraction(1);
	var amt_entered;
	var amt_fraction;
	var unit_entered;
	
	$('input[name=amount]').keyup(function() {
		amt_entered = $(this).val();
		//Create Fraction object using amount entered
		if (amt_entered.indexOf('/')!=-1) {
			amt_fraction = fractionFromString(amt_entered);
		}
		else {
			amt_fraction = new Fraction(Number(amt_entered));
		}
		
		console.log("Amount entered as fraction is "+amt_fraction.numerator+" / "+amt_fraction.denominator);
		
		if (amt_fraction.numerator != amt_fraction.numerator  || amt_fraction.numerator <= 0) {
			$('#amount-error').show();
			amt_entered = "";
			$('#best-answer').html("");
			$('#conversions').html("");
		}
		else {
			$('#amount-error').hide();
			displayResults();
		}
	});
	
	$('input[name=unit]').keyup(function() {
		unit_entered = $(this).val();
		//Standardize unit name
		unit_entered = standardizeUnitName(unit_entered);	
		//Check whether a valid unit was entered
		if (unit_entered == "") {
			$('#unit-error').show();
			$('#best-answer').html("");
			$('#conversions').html("");
		}
		else {
			displayResults();
			$('#unit-error').hide();
		}
	});
	
	$('input[name=scale]').keyup(function() {
		scale = $(this).val();
		//Create Fraction object using scale entered (or reset to 1 if scale was deleted)
		if (scale == "") {
			scale_fraction = new Fraction(1);
		}
		else if (scale.indexOf('/')!=-1) {
			scale_fraction = fractionFromString(scale);
		}
		else {
			scale_fraction = new Fraction(Number(scale));
		}
		//If scale fraction does not validate, show error and reset to scale to 1
		if (scale_fraction.numerator != scale_fraction.numerator || scale_fraction.numerator <= 0) {
			$('#scale-error').show();
			scale_fraction = new Fraction(1);
		}
		else {
			$('#scale-error').hide();
		}
		displayResults();
	});
	
	function displayResults() {
		if (amt_entered && unit_entered) {
			
			console.log("Amount entered is "+amt_entered);
			console.log("Unit entered is "+unit_entered);
			console.log("Scale is "+scale);			
			
			//Multiply amount entered by scale entered (as fractions) to get scaled amount entered
			var scaled_amt_fraction = amt_fraction.multiply(scale_fraction);
			
			//Create Fraction object with appropriate multiplier for conversion to teaspoons based on unit name entered
			var conversion_fraction = new Fraction(units[unit_entered]);
			
			//Multiply scaled amount entered by conversion amount (as fractions) to get amount entered in teaspoons
			var amt_in_tsps = scaled_amt_fraction.multiply(conversion_fraction);
			
			// For testing - DELETE
			// $('#testing').html('Amount in teaspoons as a fraction: ' +amt_in_tsps.numerator+' / '+amt_in_tsps.denominator);
			
			// if (amt_in_tsps >= 1) {
			// $('#testing').append('<br>Amount in teaspoons is greater than or equal to 1.');
			// }
			
			// console.log(amt_in_tsps % 12);
			
			//Call function to find best combination of common cooking measurements for scaled amount entered and display results in #best-answer div 	
			$('#best-answer').html("<p>Best answer: "+findBestAnswer(amt_in_tsps)+"</p>");
			
			//Call function to convert amount entered to each unit and display results in #conversions div
			$('#conversions').html(convert(amt_in_tsps));
		}
	};

});