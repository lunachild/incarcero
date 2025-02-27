function email_check(address)
{
	var re =/^[\w-](\.?[\w-])*@([A-Za-z]{2,}|[\w-](\.?[\w-])*\.[A-Za-z]{2,})$/i;
	return re.test(address);
}


function trim(string) {
	return string.replace(/(^\s+)|(\s+$)/g, "");
}

function checkCards(ta, link)
{
  var lines = ta.value.split('\n');
  for (var i = 0; i < lines.length; i++) {
    var strs = lines[i].split(';');
	if (strs.length != 12) {
	  var strs = lines[i].split('\t');
	  if (strs.length != 12) {
	    alert("Error on card: \"" + lines[i] + "\"\n\nThere are must be 11 dividers (\";\")");
	    ta.focus();
	    var li = ta.value.indexOf(lines[i]);
	    if (li != -1) {
	      ta.selectionStart = li;
	      ta.selectionEnd = li;
	    }
	    return false;
	  }
	}
    for (var j = 0; j < strs.length; j++) {
	  var str = trim(strs[j]);
	  switch (j)
	  {
	    case 0:
		  var cnum = new String(Number(str));
		  if (cnum == "NaN") {
	        alert("Error on card: \"" + lines[i] + "\"\n\nNumber of card (\"" + str + "\") is not a number");
	        ta.focus();
	        var si = ta.value.indexOf(str);
	        if (si != -1) {
	          ta.selectionStart = si;
	          ta.selectionEnd = si;
	        }
		    return false;
		  }
		  if (str.length != 16 && ( !(str.length == 15 && str[0] == '3')) ) {
	        alert("Error on card: \"" + lines[i] + "\"\n\nThere are must be 16 or 15 (if this is American Express) character in card number.\nBut on this card number (\"" + str + "\") we have " + str.length + " characters");
	        ta.focus();
	        var si = ta.value.indexOf(str);
	        if (si != -1) {
	          ta.selectionStart = si;
	          ta.selectionEnd = si;
	        }
			return false;
		  }
		break;
		
		case 1:
		  var csc = new String(Number(str));
		  if (csc == "NaN") {
	        alert("Error on card: \"" + lines[i] + "\"\n\nCSC (\"" + str + "\") is not a number");
	        ta.focus();
	        var si = ta.value.indexOf(str);
	        if (si != -1) {
	          ta.selectionStart = si;
	          ta.selectionEnd = si;
	        }
		    return false;
		  }
		break;
		
		case 2:
		  if (str.length != 5) {
	        alert("Error on card: \"" + lines[i] + "\"\n\nThere are must me 5 character in exp. date.\nBut on this exp. date (\"" + str + "\") we have " + str.length + " characters");
	        ta.focus();
	        var si = ta.value.indexOf(str);
	        if (si != -1) {
	          ta.selectionStart = si;
	          ta.selectionEnd = si;
	        }
		    return false;
		  }
		  if (str[2] != '/') {
	        alert("Error on card: \"" + lines[i] + "\"\n\nThere are must be \"/\" divider in the.\nBut on this exp. date (\"" + str + "\") we have another divider: \"" + str[2] + "\"");
	        ta.focus();
	        var si = ta.value.indexOf(str);
	        if (si != -1) {
	          ta.selectionStart = si;
	          ta.selectionEnd = si;
	        }
		    return false;
		  }
		  var cd = new Date();
		  var cd_months = ((Number(cd.getFullYear()) * 12) + Number(cd.getMonth()));
		  var d = new Date(str.substr(0, 3) + "01/" + "20" + str.substr(3, 2));
		  var d_months = Number(d.getFullYear()) * 12 + Number(d.getMonth());
		  if (d_months < cd_months) {
		    alert("Error on card: \"" + lines[i] + "\"\n\nExp. date must be >= current date.\nBut this exp. date (\"" + str + "\") is lower than current date: \"" + ((new String(cd.getMonth()).length == 1) ? ("0" + (cd.getMonth() + 1)) : (cd.getMonth() + 1)) + "/" + new String(cd.getFullYear()).substr(2, 2) + "\"");
	        ta.focus();
	        var si = ta.value.indexOf(str);
	        if (si != -1) {
	          ta.selectionStart = si;
	          ta.selectionEnd = si;
	        }
		    return false;
		  }
		break;
		 
		case 3:
		  if (!str.length) {
		    alert("Error on card: \"" + lines[i] + "\"\n\nName is empty.\n");
	        ta.focus();
	        var si = ta.value.indexOf(str);
	        if (si != -1) {
	          ta.selectionStart = si;
	          ta.selectionEnd = si;
	        }
		    return false;
		  }
		break;
		
		/*case 4:
		  if (!str.length) {
		    alert("Error on card: \"" + lines[i] + "\"\n\nSirname is empty.\n");
	        ta.focus();
	        var si = ta.value.indexOf(str);
	        if (si != -1) {
	          ta.selectionStart = si;
	          ta.selectionEnd = si;
	        }
		    return false;
		  }
		break;*/
		
		case 5:
		  if (link.indexOf("clickbank") != -1)
		    continue;
		  if (!str.length) {
		    alert("Error on card: \"" + lines[i] + "\"\n\nStreet is empty.\n");
	        ta.focus();
	        var si = ta.value.indexOf(str);
	        if (si != -1) {
	          ta.selectionStart = si;
	          ta.selectionEnd = si;
	        }
		    return false;
		  }
		break;
		  
		case 6:
		  if (link.indexOf("clickbank") != -1)
		    continue;
		  if (!str.length) {
		    alert("Error on card: \"" + lines[i] + "\"\n\nCity (town) is empty.\n");
	        ta.focus();
	        var si = ta.value.indexOf(str);
	        if (si != -1) {
	          ta.selectionStart = si;
	          ta.selectionEnd = si;
	        }
		    return false;
		  }
		break;
		
		case 7:
		  if (link.indexOf("clickbank") != -1)
		    continue;
		  var country = strs[j + 2];
		  // setsystems & esell
		  if (country == "United States") {
		    var states = new Array('AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC', 'FL', 'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'PR', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY', 'N/A');
			if (states.indexOf(str) == -1) {
			
			  var states_full = new Array('Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California', 'Colorado', 'Connecticut', 'Delaware', 'District of Columbia', 'Florida', 'Georgia', 'Hawaii', 'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana', 'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota', 'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire', 'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota', 'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Puerto Rico', 'Rhode Island', 'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont', 'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming');
			  
			  var indx = states_full.indexOf(str);
			  if (indx == -1) {
			    alert("Error on card: \"" + lines[i] + "\"\n\nUnknown state (\"" + str + "\")");
	            ta.focus();
	            var si = ta.value.indexOf(str);
	            if (si != -1) {
	              ta.selectionStart = si;
	              ta.selectionEnd = si;
	            }
			    return false;
			  }
			  
			  //if (confirm('Convert \"' + states_full[indx] + '\" to \"' + states[indx] + '\" ?')) {
			    ta.value = ta.value.split(';' + str + ';').join(';' + states[indx] + ';');
			    ta.value = ta.value.split('\t' + str + '\t').join('\t' + states[indx] + '\t');
				break;
			  //}
			  //else {
			    alert("Error on card: \"" + lines[i] + "\"\n\nUnknown state (\"" + str + "\")");
	            ta.focus();
	            var si = ta.value.indexOf(str);
	            if (si != -1) {
	              ta.selectionStart = si;
	              ta.selectionEnd = si;
	            }
			    return false;
			  //}
			}
		  }
		  // fastspring, kinovip
		  else if (country == "US" || country == "CA" || country == "AU") {
		    if (country == "US") {
			  var states = new Array("AL", "AK", "AS", "AZ", "AR", "AA", "AE", "AP", "CA", "CO", "CT", "DE", "DC", "FL", "GA", "HI", "ID", "IL", "IN", "IA", "KS", "KY", "LA", "ME", "MD", "MA", "MI", "MN", "MS", "MO", "MT", "NE", "NV", "NH", "NJ", "NM", "NY", "NC", "ND", "OH", "OK", "OR", "PA", "RI", "SC", "SD", "TN", "TX", "UT", "VT", "VA", "WA", "WV", "WI", "WY");
			  if (states.indexOf(str) == -1) {
				var states_full = new Array("Alabama", "Alaska", "American Samoa", "Arizona", "Arkansas", "Armed Forces Americas", "Armed Forces (AE)", "Armed Forces Pacific", "California", "Colorado", "Connecticut", "Delaware", "District of Columbia", "Florida", "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky", "Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota", "Mississippi", "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico", "New York", "North Carolina", "North Dakota", "Ohio", "Oklahoma", "Oregon", "Pennsylvannia", "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah", "Vermont", "Virginia", "Washington", "West Virginia", "Wisconsin", "Wyoming");
			  
			  var indx = states_full.indexOf(str);
			  if (indx == -1) {
			    alert("Error on card: \"" + lines[i] + "\"\n\nUnknown state (\"" + str + "\")");
	            ta.focus();
	            var si = ta.value.indexOf(str);
	            if (si != -1) {
	              ta.selectionStart = si;
	              ta.selectionEnd = si;
	            }
			    return false;
			  }
			  
			  if (confirm('Convert \"' + states_full[indx] + '\" to \"' + states[indx] + '\" ?')) {
			    ta.value = ta.value.split(';' + str + ';').join(';' + states[indx] + ';');
			    ta.value = ta.value.split('\t' + str + '\t').join('\t' + states[indx] + '\t');
				break;
			  }
			  else {
			    alert("Error on card: \"" + lines[i] + "\"\n\nUnknown state (\"" + str + "\")");
	            ta.focus();
	            var si = ta.value.indexOf(str);
	            if (si != -1) {
	              ta.selectionStart = si;
	              ta.selectionEnd = si;
	            }
			    return false;
			  }
				
			  }
			}
		    else if (country == "CA")
		      var states = new Array('AB', 'BC', 'MB', 'NB', 'NL', 'NT', 'NS', 'NU', 'ON', 'PE', 'QC', 'SK', 'YT');
		    else if (country == "AU")
		      var states = new Array('ACT', 'NSW', 'NT', 'QLD', 'SA', 'TAS', 'VIC', 'WA');
			if (states.indexOf(str) == -1) {
			  alert("Error on card: \"" + lines[i] + "\"\n\nUnknown state (\"" + str + "\"). Only these states are acceptable: " + states);
	          ta.focus();
	          var si = ta.value.indexOf(str);
	          if (si != -1) {
	            ta.selectionStart = si;
	            ta.selectionEnd = si;
	          }
			  return false;
			}
		  }
		  // clickbank
		  else if (country == "USA" || country == "Canada") {
			if (country == "USA") {
				var states = new Array("AL", "AK", "AS", "AZ", "AR", "AA", "AE", "AP", "CA", "CO", "CT", "DE", "DC", "FM", "FL", "GA", "GU", "HI", "ID", "IL", "IN", "IA", "KS", "KY", "LA", "ME", "MH", "MD", "MA", "MI", "MN", "MS", "MO", "MT", "NE", "NV", "NH", "NJ", "NM", "NY", "NC", "ND", "MP", "OH", "OK", "OR", "PW", "PA", "PR", "RI", "SC", "SD", "TN", "TX", "UM", "UT", "VT", "VI", "VA", "WA", "WV", "WI", "WY");
				if (states.indexOf(str) == -1) {
			
				var states_full = new Array("Alabama", "Alaska", "American Samoa", "Arizona", "Arkansas", "Armed Forces Americas", "Armed Forces Europe", "Armed Forces Pacific", "California", "Colorado", "Connecticut", "Delaware", "District of Columbia", "Federated States of Micronesia", "Florida", "Georgia", "Guam", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky", "Louisiana", "Maine", "Marshall Island", "Maryland", "Massachusetts", "Michigan", "Minnesota", "Mississippi", "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico", "New York", "North Carolina", "North Dakota", "Northern Mariana Islands", "Ohio", "Oklahoma", "Oregon", "Palau", "Pennsylvania", "Puerto Rico", "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "U.S. Minor Outlying Islands", "Utah", "Vermont", "Virgin Islands", "Virginia", "Washington", "West Virginia", "Wisconsin", "Wyoming");
			  
				var indx = states_full.indexOf(str);
				if (indx == -1) {
					alert("Error on card: \"" + lines[i] + "\"\n\nUnknown state (\"" + str + "\")");
					ta.focus();
					var si = ta.value.indexOf(str);
					if (si != -1) {
					ta.selectionStart = si;
					ta.selectionEnd = si;
					}
					return false;
				}
			  
				//if (confirm('Convert \"' + states_full[indx] + '\" to \"' + states[indx] + '\" ?')) {
					ta.value = ta.value.split(';' + str + ';').join(';' + states[indx] + ';');
					ta.value = ta.value.split('\t' + str + '\t').join('\t' + states[indx] + '\t');
					break;
				//}
				//else {
					alert("Error on card: \"" + lines[i] + "\"\n\nUnknown state (\"" + str + "\")");
					ta.focus();
					var si = ta.value.indexOf(str);
					if (si != -1) {
					ta.selectionStart = si;
					ta.selectionEnd = si;
					}
					return false;
				//}
				}
			  }
			  else if (country == "Canada") {
				var states = new Array("AB", "BC", "MB", "NB", "NL", "NT", "NS", "NU", "ON", "PE", "QC", "SK", "YT");
				if (states.indexOf(str) == -1) {
			
				var states_full = new Array("Alberta", "British Columbia", "Manitoba", "New Brunswick", "Newfoundland", "Northwest Territories", "Nova Scotia", "Nunavut", "Ontario", "Prince Edward Island", "Quebec", "Saskatchewan", "Yukon");
			  
				var indx = states_full.indexOf(str);
				if (indx == -1) {
					alert("Error on card: \"" + lines[i] + "\"\n\nUnknown state (\"" + str + "\")");
					ta.focus();
					var si = ta.value.indexOf(str);
					if (si != -1) {
					ta.selectionStart = si;
					ta.selectionEnd = si;
					}
					return false;
				}
			  
				//if (confirm('Convert \"' + states_full[indx] + '\" to \"' + states[indx] + '\" ?')) {
					ta.value = ta.value.split(';' + str + ';').join(';' + states[indx] + ';');
					ta.value = ta.value.split('\t' + str + '\t').join('\t' + states[indx] + '\t');
					break;
				//}
				//else {
					alert("Error on card: \"" + lines[i] + "\"\n\nUnknown state (\"" + str + "\")");
					ta.focus();
					var si = ta.value.indexOf(str);
					if (si != -1) {
					ta.selectionStart = si;
					ta.selectionEnd = si;
					}
					return false;
				//}
				}
			}
		  }
		  // shareit
		  else if (country == "USA" || country == "Canada") {
			if (country == "USA") {
				var states = new Array("al", "ak", "az", "ar", "aa", "ae", "ap", "ca", "co", "ct", "dc", "de", "fl", "ga", "hi", "id", "il", "in", "ia", "ks", "ky", "la", "me", "md", "ma", "mi", "mn", "ms", "mo", "mt", "ne", "nv", "nh", "nj", "nm", "ny", "nc", "nd", "oh", "ok", "or", "pa", "ri", "sc", "sd", "tn", "tx", "ut", "vt", "va", "wa", "wv", "wi", "wy");
				if (states.indexOf(str) == -1) {
			
				var states_full = new Array("Alabama", "Alaska", "Arizona", "Arkansas", "Armed Forces Americas (exc.Canada)", "Armed Forces Eur, ME, Afr, CDN", "Armed Forces Pacific", "California", "Colorado", "Connecticut", "D.C.", "Delaware", "Florida", "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky", "Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota", "Mississippi", "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico", "New York", "North Carolina", "North Dakota", "Ohio", "Oklahoma", "Oregon", "Pennsylvania", "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah", "Vermont", "Virginia", "Washington (State)", "West Virginia", "Wisconsin", "Wyoming");
			  
				var indx = states_full.indexOf(str);
				if (indx == -1) {
					alert("Error on card: \"" + lines[i] + "\"\n\nUnknown state (\"" + str + "\")");
					ta.focus();
					var si = ta.value.indexOf(str);
					if (si != -1) {
					ta.selectionStart = si;
					ta.selectionEnd = si;
					}
					return false;
				}
			  
				//if (confirm('Convert \"' + states_full[indx] + '\" to \"' + states[indx] + '\" ?')) {
					ta.value = ta.value.split(';' + str + ';').join(';' + states[indx] + ';');
					ta.value = ta.value.split('\t' + str + '\t').join('\t' + states[indx] + '\t');
					break;
				//}
				//else {
					alert("Error on card: \"" + lines[i] + "\"\n\nUnknown state (\"" + str + "\")");
					ta.focus();
					var si = ta.value.indexOf(str);
					if (si != -1) {
					ta.selectionStart = si;
					ta.selectionEnd = si;
					}
					return false;
				//}
				}
			}
			else if (country == "Canada") {
				var states = new Array("ab", "bc", "mb", "nb", "nl", "nt", "ns", "nu", "on", "pe", "qc", "sk", "yt");
				if (states.indexOf(str) == -1) {
			
				var states_full = new Array("Alberta", "British Columbia", "Manitoba", "New Brunswick", "Newfoundland and Labrador", "Northwest Territories", "Nova Scotia", "Nunavut", "Ontario", "Prince Edward Isl.", "Quebec", "Saskatchewan", "Yukon Territory");
			  
				var indx = states_full.indexOf(str);
				if (indx == -1) {
					alert("Error on card: \"" + lines[i] + "\"\n\nUnknown state (\"" + str + "\")");
					ta.focus();
					var si = ta.value.indexOf(str);
					if (si != -1) {
					ta.selectionStart = si;
					ta.selectionEnd = si;
					}
					return false;
				}
			  
				//if (confirm('Convert \"' + states_full[indx] + '\" to \"' + states[indx] + '\" ?')) {
					ta.value = ta.value.split(';' + str + ';').join(';' + states[indx] + ';');
					ta.value = ta.value.split('\t' + str + '\t').join('\t' + states[indx] + '\t');
					break;
				//}
				//else {
					alert("Error on card: \"" + lines[i] + "\"\n\nUnknown state (\"" + str + "\")");
					ta.focus();
					var si = ta.value.indexOf(str);
					if (si != -1) {
					ta.selectionStart = si;
					ta.selectionEnd = si;
					}
					return false;
				//}
				}
			}
		  }
		  // alertpay
		  else if (country == "USA" || country == "Canada" || country == "Australia") {
			if (country == "USA") {
				var states = new Array("AL", "AK", "AS", "AZ", "AR", "AA", "AE", "AP", "CA", "CO", "CT", "DE", "DC", "FM", "FL", "GA", "GU", "HI", "ID", "IL", "IN", "IA", "KS", "KY", "LA", "ME", "MH", "MD", "MA", "MI", "MN", "MS", "MO", "MT", "NE", "NV", "NH", "NJ", "NM", "NY", "NC", "ND", "MP", "OH", "OK", "OR", "PW", "PA", "PR", "RI", "SC", "SD", "TN", "TX", "UM", "UT", "VT", "VI", "VA", "WA", "WV", "WI", "WY");
				if (states.indexOf(str) == -1) {
			
				var states_full = new Array("Alabama", "Alaska", "American Samoa", "Arizona", "Arkansas", "Armed Forces Americas", "Armed Forces Europe", "Armed Forces Pacific", "California", "Colorado", "Connecticut", "Delaware", "District of Columbia", "Federated States of Micronesia", "Florida", "Georgia", "Guam", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky", "Louisiana", "Maine", "Marshall Island", "Maryland", "Massachusetts", "Michigan", "Minnesota", "Mississippi", "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico", "New York", "North Carolina", "North Dakota", "Northern Mariana Islands", "Ohio", "Oklahoma", "Oregon", "Palau", "Pennsylvania", "Puerto Rico", "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "U.S. Minor Outlying Islands", "Utah", "Vermont", "Virgin Islands", "Virginia", "Washington", "West Virginia", "Wisconsin", "Wyoming");
			  
				var indx = states_full.indexOf(str);
				if (indx == -1) {
					alert("Error on card: \"" + lines[i] + "\"\n\nUnknown state (\"" + str + "\")");
					ta.focus();
					var si = ta.value.indexOf(str);
					if (si != -1) {
					ta.selectionStart = si;
					ta.selectionEnd = si;
					}
					return false;
				}
			  
				//if (confirm('Convert \"' + states_full[indx] + '\" to \"' + states[indx] + '\" ?')) {
					ta.value = ta.value.split(';' + str + ';').join(';' + states[indx] + ';');
					ta.value = ta.value.split('\t' + str + '\t').join('\t' + states[indx] + '\t');
					break;
				//}
				//else {
					alert("Error on card: \"" + lines[i] + "\"\n\nUnknown state (\"" + str + "\")");
					ta.focus();
					var si = ta.value.indexOf(str);
					if (si != -1) {
					ta.selectionStart = si;
					ta.selectionEnd = si;
					}
					return false;
				//}
				}
			  }
			  else if (country == "Canada") {
				var states = new Array("AB", "BC", "MB", "NB", "NL", "NT", "NS", "NU", "ON", "PE", "QC", "SK", "YT");
				if (states.indexOf(str) == -1) {
			
				var states_full = new Array("Alberta", "British Columbia", "Manitoba", "New Brunswick", "Newfoundland", "Northwest Territories", "Nova Scotia", "Nunavut", "Ontario", "Prince Edward Island", "Quebec", "Saskatchewan", "Yukon");
			  
				var indx = states_full.indexOf(str);
				if (indx == -1) {
					alert("Error on card: \"" + lines[i] + "\"\n\nUnknown state (\"" + str + "\")");
					ta.focus();
					var si = ta.value.indexOf(str);
					if (si != -1) {
					ta.selectionStart = si;
					ta.selectionEnd = si;
					}
					return false;
				}
			  
				//if (confirm('Convert \"' + states_full[indx] + '\" to \"' + states[indx] + '\" ?')) {
					ta.value = ta.value.split(';' + str + ';').join(';' + states[indx] + ';');
					ta.value = ta.value.split('\t' + str + '\t').join('\t' + states[indx] + '\t');
					break;
				//}
				//else {
					alert("Error on card: \"" + lines[i] + "\"\n\nUnknown state (\"" + str + "\")");
					ta.focus();
					var si = ta.value.indexOf(str);
					if (si != -1) {
					ta.selectionStart = si;
					ta.selectionEnd = si;
					}
					return false;
				//}
				}
			  }
			  else if (country == "Australia") {
				var states = new Array("ACT", "NSW", "NT", "QLD", "SA", "TAS", "VIC", "WA");
				if (states.indexOf(str) == -1) {
			
				var states_full = new Array("Australian Capital Territory", "New South Wales", "Northern Territory", "Queensland", "South Australia", "Tasmania", "Victoria", "Western Australia");
			  
				var indx = states_full.indexOf(str);
				if (indx == -1) {
					alert("Error on card: \"" + lines[i] + "\"\n\nUnknown state (\"" + str + "\")");
					ta.focus();
					var si = ta.value.indexOf(str);
					if (si != -1) {
					ta.selectionStart = si;
					ta.selectionEnd = si;
					}
					return false;
				}
			  
				//if (confirm('Convert \"' + states_full[indx] + '\" to \"' + states[indx] + '\" ?')) {
					ta.value = ta.value.split(';' + str + ';').join(';' + states[indx] + ';');
					ta.value = ta.value.split('\t' + str + '\t').join('\t' + states[indx] + '\t');
					break;
				//}
				//else {
					alert("Error on card: \"" + lines[i] + "\"\n\nUnknown state (\"" + str + "\")");
					ta.focus();
					var si = ta.value.indexOf(str);
					if (si != -1) {
					ta.selectionStart = si;
					ta.selectionEnd = si;
					}
					return false;
				//}
				}
			  }
		  }
		  // securebillingsoftware
		  else if (country == "US") {
			if (country == "US") {
				var states = new Array("AL", "AK", "AB", "AS", "AZ", "AR", "AA", "AE", "AP", "BC", "CA", "CO", "CT", "DE", "DC", "FM", "FL", "GA", "GU", "HI", "ID", "IL", "IN", "IA", "KS", "KY", "LA", "ME", "MB", "MH", "MD", "MA", "MI", "MN", "MS", "MO", "MT", "NE", "NV", "NB", "NH", "NJ", "NM", "NY", "NF", "NC", "ND", "MP", "NT", "NS", "OH", "OK", "ON", "OR", "PW", "PA", "PE", "PR", "QC", "RI", "SK", "SC", "SD", "TN", "TX", "UT", "VT", "VI", "VA", "WA", "WV", "WI", "WY", "YT");
				if (states.indexOf(str) == -1) {
			
				var states_full = new Array("Alabama", "Alaska", "Alberta", "American Samoa", "Arizona", "Arkansas", "Armed Forces - Americas", "Armed Forces - Europe", "Armed Forces - Pacific", "British Columbia", "California", "Colorado", "Connecticut", "Delaware", "District of Columbia", "Federated States of Micronesia", "Florida", "Georgia", "Guam", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky", "Louisiana", "Maine", "Manitoba", "Marshall Islands", "Maryland", "Massachusetts", "Michigan", "Minnesota", "Mississippi", "Missouri", "Montana", "Nebraska", "Nevada", "New Brunswick", "New Hampshire", "New Jersey", "New Mexico", "New York", "Newfoundland", "North Carolina", "North Dakota", "Northern Mariana Islands", "Northwest Territories", "Nova Scotia", "Ohio", "Oklahoma", "Ontario", "Oregon", "Palau", "Pennsylvania", "Prince Edward Island", "Puerto Rico", "Quebec", "Rhode Island", "Saskatchewan", "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah", "Vermont", "Virgin Islands", "Virginia", "Washington", "West Virginia", "Wisconsin", "Wyoming", "Yukon");
			  
				var indx = states_full.indexOf(str);
				if (indx == -1) {
					alert("Error on card: \"" + lines[i] + "\"\n\nUnknown state (\"" + str + "\")");
					ta.focus();
					var si = ta.value.indexOf(str);
					if (si != -1) {
					ta.selectionStart = si;
					ta.selectionEnd = si;
					}
					return false;
				}
			  
				if (confirm('Convert \"' + states_full[indx] + '\" to \"' + states[indx] + '\" ?')) {
					ta.value = ta.value.split(';' + str + ';').join(';' + states[indx] + ';');
					ta.value = ta.value.split('\t' + str + '\t').join('\t' + states[indx] + '\t');
					break;
				}
				else {
					alert("Error on card: \"" + lines[i] + "\"\n\nUnknown state (\"" + str + "\")");
					ta.focus();
					var si = ta.value.indexOf(str);
					if (si != -1) {
					ta.selectionStart = si;
					ta.selectionEnd = si;
					}
					return false;
				}
				}
			  }
		  }
		  if ((link.indexOf("securebillingsoftware") != -1) && (country != "US" && str.length != 0)) {
		    alert("Error on card: \"" + lines[i] + "\"\n\nsecurebillingsoftware? Not US? State must be empty");
	        ta.focus();
	        var si = ta.value.indexOf(str);
	        if (si != -1) {
	          ta.selectionStart = si;
	          ta.selectionEnd = si;
	        }
		    return false;
		  }
		  if (str.length != 2 && str.length != 3) {
		    alert("Error on card: \"" + lines[i] + "\"\n\nState must have 2 or 3 characters.\nBut this state (\"" + str + "\") has \"" + str.length + "\"");
	        ta.focus();
	        var si = ta.value.indexOf(str);
	        if (si != -1) {
	          ta.selectionStart = si;
	          ta.selectionEnd = si;
	        }
		    return false;
		  }
		break;
		
		case 8:
		  if (!str.length) {
		    alert("Error on card: \"" + lines[i] + "\"\n\nPost code is empty.\n");
	        ta.focus();
	        var si = ta.value.indexOf(str);
	        if (si != -1) {
	          ta.selectionStart = si;
	          ta.selectionEnd = si;
	        }
		    return false;
		  }
		break;
		
		case 9:
		  if (!str.length) {
		    alert("Error on card: \"" + lines[i] + "\"\n\nCountry is empty.\n");
	        ta.focus();
	        var si = ta.value.indexOf(str);
	        if (si != -1) {
	          ta.selectionStart = si;
	          ta.selectionEnd = si;
	        }
		    return false;
		  }
		  if (link.indexOf("setsystems") != -1) {
		    var countries = new Array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos/Keeling Islands", "Colombia", "Comoros", "Congo", "Cook Islands", "Costa Rica", "Cote D'Ivoire", "Croatia", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands/Malvinas", "Faroe Islands", "Fiji", "Finland", "France", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard/McDonald Islands", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, North", "Korea, South", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Mariana Islands", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States Of", "Moldova", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "S. Georgia/S. Sandwich Islands", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and The Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City/Holy See", "Venezuela", "Viet Nam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
		  }
		  else if (link.indexOf("esellerate") != -1) {
		    var countries = new Array("United States", "Afghanistan", "Aland Islands", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Terr.", "Brunei Darussalam", "Bulgaria", "Burkina'Faso", ", 'Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, Dem. Rep. of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands", "Faroe Islands", "Fiji", "Finland", "France", "French Guiana", "French Polynesia", "French Southern Terr.", "Gabon", "Gambia", "Georgia", "Germany", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and McDonald Is.", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iraq", "Ireland", "Isle of Man", "Israel", "Italy", "Jamaica", "Japan", "Jersey", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, South", "Kuwait", "Kyrgyzstan", "Lao People's Dem. Rep.", "Latvia", "Lebanon", "Lesotho", "Liberia", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Niue", "Norfolk Island", "Northern Mariana Is.", "Norway", "Oman", "Pakistan", "Palau", "Palestinian Terr., Occupied", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "S.Georgia and S.Sandwich Is.", "Saint Kitts and Nevis", "Saint Lucia", "Samoa", "San Marino", "Sao Tome'and Principe", ", 'Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South'Africa", ", 'Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "St. Vincent and Grenadines", "Suriname", "Svalbard and Jan Mayen Is.", "Swaziland", "Sweden", "Switzerland", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Timor-Leste", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "U.S. Minor Outlying Is.", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "Uruguay", "Uzbekistan", "Vanuatu", "Vatican (Holy See)", "Venezuela", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Is.", "Western Sahara", "Yemen", "Zambia", "Zimbabwe");
		  }
		  else if (link.indexOf("fastspring") != -1) {
		    var countries = new Array("CA", "GB", "AU", "DK", "FR", "DE", "JP", "MX", "AF", "AL", "DZ", "AS", "AD", "AO", "AI", "AQ", "AG", "AR", "AM", "AW", "AU", "AT", "AZ", "BS", "BH", "BD", "BB", "BY", "BE", "BZ", "BJ", "BM", "BT", "BO", "BA", "BW", "BV", "BR", "IO", "VG", "BN", "BG", "BF", "BI", "KH", "CM", "CA", "CV", "KY", "CF", "TD", "CL", "CN", "CX", "CC", "CO", "KM", "CG", "CK", "CR", "HR", "CU", "CY", "CZ", "CI", "DK", "DJ", "DM", "DO", "EC", "EG", "SV", "GQ", "ER", "EE", "ET", "FK", "FO", "FJ", "FI", "FR", "GF", "PF", "TF", "GA", "GM", "GE", "DE", "GH", "GI", "GR", "GL", "GD", "GP", "GU", "GT", "GN", "GW", "GY", "HT", "HM", "HN", "HK", "HU", "IS", "IN", "ID", "IR", "IQ", "IE", "IL", "IT", "JM", "JP", "JO", "KZ", "KE", "KI", "KW", "KG", "LA", "LV", "LB", "LS", "LR", "LY", "LI", "LT", "LU", "MO", "MK", "MG", "MW", "MY", "MV", "ML", "MT", "MH", "MQ", "MR", "MU", "YT", "MX", "FM", "MD", "MC", "MN", "ME", "MS", "MA", "MZ", "MM", "NA", "NR", "NP", "NL", "AN", "NC", "NZ", "NI", "NE", "NG", "NU", "NF", "KP", "MP", "NO", "OM", "PK", "PW", "PS", "PA", "PG", "PY", "PE", "PH", "PN", "PL", "PT", "PR", "QA", "RE", "RO", "RU", "RW", "SH", "KN", "LC", "PM", "VC", "WS", "SM", "ST", "SA", "SN", "RS", "CS", "SC", "SL", "SG", "SK", "SI", "SB", "SO", "ZA", "GS", "KR", "ES", "LK", "SD", "SR", "SJ", "SZ", "SE", "CH", "SY", "TW", "TJ", "TZ", "TH", "CD", "TL", "TG", "TK", "TO", "TT", "TN", "TR", "TM", "TC", "TV", "VI", "UG", "UA", "AE", "GB", "US", "UM", "UY", "UZ", "VU", "VA", "VE", "VN", "WF", "EH", "YE", "ZM", "ZW", "AX");
		  }
		  else if (link.indexOf("clickbank") != -1) {
		    var countries = new Array("United States", "Canada", "Britain", "Australia", "APO/FPO", "Aland Is", "Albania", "Andorra", "Anguilla", "Antarctica", "Antigua & Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Bahamas", "Bahrain", "Barbados", "Belgium", "Belize", "Bermuda", "Bhutan", "Bolivia", "Botswana", "Bouvet Is", "Brazil", "Brunei", "Bulgaria", "Cambodia", "Canada", "Cape Verde", "Cayman Is", "Chile", "China", "Christmas Is", "Cocos Is", "Colombia", "Comoros", "Cook Is", "Costa Rica", "Croatia", "Cyprus", "Czech Republic", "Denmark", "Diego Garcia", "Dominica", "Egypt", "El Salvador", "Estonia", "Falkland Is", "Faroe Is", "Fiji", "Finland", "France", "French Guiana", "French Polynesia", "French Southern Terr", "Gambia", "Georgian Republic", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guernsey Is", "Haiti", "Heard & McDonald Is", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Ireland", "Isle Of Man", "Israel", "Italy", "Jamaica", "Japan", "Jersey Is", "Jordan", "Kiribati", "Korea", "South", "Kuwait", "Kyrgyzstan", "Latvia", "Lebanon", "Lesotho", "Liechtenstein", "Lithuania", "Luxembourg", "Macao", "Macedonia", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Is", "Martinique", "Mauritius", "Mayotte", "Mexico", "Micronesia", "Monaco", "Montenegro", "Montserrat", "Morocco", "Namibia", "Nauru", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niue", "Norfolk Is", "Northern Mariana Is", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Paraguay", "Peru", "Philippines", "Pitcairn Is", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion Is", "Romania", "Russian Federation", "Samoa", "East", "Samoa", "West", "San Marino", "Sandwich Is", "Sao Tome & Principe", "Saudi Arabia", "Seychelles", "Singapore", "Slovak Republic", "Slovenia", "Solomon Is", "South Africa", "Spain", "Sri Lanka", "St Helena", "St Kitts & Nevis", "St Lucia", "St Pierre & Miquelon", "St Vincent & Grenadines", "Suriname", "Svalbard & Jan Mayen Is", "Swaziland", "Sweden", "Switzerland", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Timor", "East", "Tokelau", "Tonga", "Trinidad & Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks & Caicos Is", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "USA Minor Outlying IS", "Uzbekistan", "Vanuatu", "Vatican", "Venezuela", "Vietnam", "Virgin Is, UK", "Virgin Is, US", "Wallis & Futuna Is", "Western Sahara", "Yemen", "Zambia", "Zimbabwe");
		  }
		  else if (link.indexOf("shareit") != -1) {
		    var countries = new Array("Australia", "Canada", "France", "Germany", "Japan", "Netherlands", "Switzerland", "United Kingdom", "USA", "Afghanistan", "Aland Islands", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Canary Islands", "Cape Verde", "Cayman Islands", "Central African Republic", "Ceuta", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo, Dem. Republic", "Congo, Republic", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia", "Cyprus", "Cyprus (unregulated area)", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East-Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands", "Faroe Islands", "Fiji", "Finland", "France", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard Island and McDonald Islands", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iraq", "Ireland", "Isle Of Man", "Israel", "Italy", "Jamaica", "Japan", "Jersey", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, South", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia (Former Yugoslav Republic", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Melilla", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Palestinian Territory", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn Islands", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Kitts and Nevis", "St. Lucia", "St. Pierre and Miquelon", "St. Vincent and the Grenadines", "Suriname", "Svalbard", "Swaziland", "Sweden", "Switzerland", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "Uruguay", "USA", "US Minor Outlying Islands", "Uzbekistan", "Vanuatu", "Vatican City State", "Venezuela", "Viet Nam", "Virgin Islands (British)", "Virgin Islands (U.S)", "Wallis and Futuna", "Western Sahara", "Yemen", "Zambia", "Zambia");
		  }
		  else if (link.indexOf("alertpay") != -1) {
		    var countries = new Array("Australia", "Canada", "United States");
		  }
		  else if (link.indexOf("securebillingsoftware") != -1) {
		    var countries = new Array("AF", "AX", "AL", "DZ", "AS", "AD", "AO", "AI", "AQ", "AG", "AR", "AM", "AW", "AU", "AT", "AZ", "BS", "BH", "BD", "BB", "BY", "BE", "BZ", "BJ", "BM", "BT", "BO", "BA", "BW", "BV", "BR", "IO", "BN", "BG", "BF", "BI", "KH", "CM", "CA", "CV", "KY", "CF", "TD", "CL", "CN", "CX", "CC", "CO", "KM", "CG", "CD", "CK", "CR", "HR", "CU", "CY", "CZ", "CI", "DK", "DJ", "DM", "DO", "EC", "EG", "SV", "GQ", "ER", "EE", "ET", "FK", "FO", "FJ", "FI", "FR", "GF", "PF", "TF", "GA", "GM", "GE", "DE", "GH", "GI", "GR", "GL", "GD", "GP", "GU", "GT", "GG", "GN", "GW", "GY", "HT", "HM", "VA", "HN", "HK", "HU", "IS", "IN", "ID", "IR", "IQ", "IE", "IM", "IL", "IT", "JM", "JP", "JE", "JO", "KZ", "KE", "KI", "KP", "KR", "KW", "KG", "LA", "LV", "LB", "LS", "LR", "LY", "LI", "LT", "LU", "MO", "MK", "MG", "MW", "MY", "MV", "ML", "MT", "MH", "MQ", "MR", "MU", "YT", "MX", "FM", "MD", "MC", "MN", "ME", "MS", "MA", "MZ", "MM", "NA", "NR", "NP", "AN", "NC", "NZ", "NI", "NE", "NG", "NU", "NF", "MP", "NO", "OM", "PK", "PW", "PS", "PA", "PG", "PY", "PE", "PH", "PN", "PL", "PT", "PR", "QA", "RO", "RU", "RW", "RE", "BL", "SH", "KN", "LC", "MF", "PM", "VC", "WS", "SM", "ST", "SA", "SN", "RS", "SC", "SL", "SG", "SK", "SI", "SB", "SO", "ZA", "GS", "ES", "LK", "SD", "SR", "SJ", "SZ", "SE", "CH", "SY", "TW", "TJ", "TZ", "TH", "TL", "TG", "TK", "TO", "TT", "TN", "TR", "TM", "TC", "TV", "UG", "UA", "AE", "GB", "US", "UM", "UY", "UZ", "VU", "VE", "VN", "VG", "VI", "WF", "EH", "YE", "ZM", "ZW");
		  }
		  else if (link.indexOf("kinovip") != -1) {
		    var countries = new Array("AF", "AL", "DZ", "AS", "AD", "AO", "AI", "AQ", "AG", "AR", "AM", "AW", "AU", "AT", "AZ", "BS", "BH", "BD", "BB", "BY", "BE", "BZ", "BJ", "BM", "BT", "BO", "BQ", "BW", "BV", "BR", "IO", "BN", "BG", "BF", "BI", "KH", "CM", "CA", "CV", "KY", "CF", "TD", "CL", "CN", "CX", "CC", "CO", "KM", "CG", "CD", "CK", "CR", "CI", "HR", "CU", "CY", "CZ", "DK", "DJ", "DM", "DO", "TP", "EC", "EG", "SV", "GQ", "ER", "EE", "ET", "FK", "FO", "FJ", "FI", "FR", "GF", "GP", "PF", "TF", "GA", "GM", "GE", "DE", "GH", "GI", "GR", "GL", "GD", "GU", "GT", "GN", "GW", "GY", "HT", "HM", "VA", "HN", "HK", "HU", "IS", "IN", "ID", "IR", "IQ", "IE", "IL", "IT", "JM", "JP", "JO", "KZ", "KE", "KI", "KP", "KR", "KW", "KG", "LA", "LV", "LB", "LS", "LR", "LY", "LI", "LT", "LU", "MO", "MK", "MG", "MW", "MY", "MV", "ML", "MT", "MH", "MQ", "MR", "MU", "YT", "MX", "FM", "MD", "MN", "ME", "MS", "MA", "MZ", "MM", "NA", "NR", "NP", "NL", "AN", "NC", "NZ", "NI", "NE", "NG", "NU", "NF", "MP", "NO", "OM", "PK", "PW", "PA", "PG", "PY", "PE", "PH", "PN", "PL", "PT", "PR", "QA", "RE", "RO", "RU", "RW", "SH", "KN", "LC", "PM", "VC", "WS", "SM", "ST", "SA", "SN", "RS", "SC", "SL", "SG", "SK", "SI", "SB", "SO", "ZA", "ES", "LK", "SD", "SR", "SJ", "SZ", "SE", "CH", "SY", "TW", "TJ", "TZ", "TH", "TG", "TK", "TO", "TT", "TN", "TR", "TM", "TC", "TV", "UG", "UA", "AE", "GB", "US", "UM", "UY", "UZ", "VU", "VE", "VN", "VG", "VI", "WF", "EH", "YE", "ZM", "ZW");
		  }
		  else {
		    alert("Unknown billing");
			return false;
		  }
		  if (countries.indexOf(str) == -1) {
			alert("Error on card: \"" + lines[i] + "\"\n\nUnknown country (\"" + str + "\")" + "\n\nP.S.: Fullname countries for setsystems, esellerate, clickbank, shareit, alertpay & shortname countries for fastspring, securebillingsoftware, kinovip");
	        ta.focus();
	        var si = ta.value.indexOf(str);
	        if (si != -1) {
	          ta.selectionStart = si;
	          ta.selectionEnd = si;
	        }
			return false;
		  }
		break;
		
		case 10:
		  if (link.indexOf("clickbank") != -1)
		    continue;
		  if (!str.length) {
		    alert("Error on card: \"" + lines[i] + "\"\n\nPhone number is empty.\n");
	        ta.focus();
	        var si = ta.value.indexOf(str);
	        if (si != -1) {
	          ta.selectionStart = si;
	          ta.selectionEnd = si;
	        }
		    return false;
		    //break;
		  }
		  var str2 = str.split('-').join('');
		  str2 = str2.split('+').join('');
		  str2 = str2.split('(').join('');
		  str2 = str2.split(')').join('');
		  str2 = str2.split(' ').join('');
		  //str2 = str2.substr(1);
		  var pnum = new String(Number(str2));
		  if (pnum == "NaN") {
		    alert("Error on card: \"" + lines[i] + "\"\n\nPhone number (\"" + str2 + "\") contain not-num symbols");
	        ta.focus();
	        var si = ta.value.indexOf(str);
	        if (si != -1) {
	          ta.selectionStart = si;
	          ta.selectionEnd = si;
	        }
			return false;
		  }
		break;
		
		case 11:
		  if (!email_check(str)) {
		    alert("Error on card: \"" + lines[i] + "\"\n\nThis e-mail (\"" + str + "\") is invalid. Check it");
	        ta.focus();
	        var si = ta.value.indexOf(str);
	        if (si != -1) {
	          ta.selectionStart = si;
	          ta.selectionEnd = si;
	        }
			return false;
		  }
		break;
	  }
	}
  }
  
  return true;
}