<?php 
class Geography extends Object {
	
	// App::import('Lib', 'O2form.Geography');
	
	var $regions = array(
		'CA' => array(
			'AB' => 'Alberta',
			'BC' => 'British Columbia',
			'MB' => 'Manitoba',
			'NB' => 'New Brunswick',
			'NL' => 'Newfoundland and Labrador',
			'NS' => 'Nova Scotia',
			'NT' => 'Northwest Territories',
			'NU' => 'Nunavut',
			'ON' => 'Ontario',
			'PE' => 'Prince Edward Island',
			'QC' => 'Quebec',
			'SK' => 'Saskatchewan',
			'YT' => 'Yukon'
		),
		'US' => array(
			'AL' => 'Alabama',
			'AK' => 'Alaska',
			'AZ' => 'Arizona',
			'AR' => 'Arkansas',
			'CA' => 'California',
			'CO' => 'Colorado',
			'CT' => 'Connecticut',
			'DE' => 'Delaware',
			'FL' => 'Florida',
			'GA' => 'Georgia',
			'HI' => 'Hawaii',
			'ID' => 'Idaho',
			'IL' => 'Illinois',
			'IN' => 'Indiana',
			'IA' => 'Iowa',
			'KS' => 'Kansas',
			'KY' => 'Kentucky',
			'LA' => 'Louisiana',
			'ME' => 'Maine',
			'MD' => 'Maryland',
			'MA' => 'Massachusetts',
			'MI' => 'Michigan',
			'MN' => 'Minnesota',
			'MS' => 'Mississippi',
			'MO' => 'Missouri',
			'MT' => 'Montana',
			'NE' => 'Nebraska',
			'NV' => 'Nevada',
			'NH' => 'New Hampshire',
			'NJ' => 'New Jersey',
			'NM' => 'New Mexico',
			'NY' => 'New York',
			'NC' => 'North Carolina',
			'ND' => 'North Dakota',
			'OH' => 'Ohio',
			'OK' => 'Oklahoma',
			'OR' => 'Oregon',
			'PA' => 'Pennsylvania',
			'RI' => 'Rhode Island',
			'SC' => 'South Carolina',
			'SD' => 'South Dakota',
			'TN' => 'Tennessee',
			'TX' => 'Texas',
			'UT' => 'Utah',
			'VT' => 'Vermont',
			'VA' => 'Virginia',
			'WA' => 'Washington',
			'WV' => 'West Virginia',
			'WI' => 'Wisconsin',
			'WY' => 'Wyoming'
		)
	);
		
	var $localeTerms = array(
		'region' => array(
			'CA' => 'Province',
			'US' => 'State',
		),
	);
	
	var $countries = array(
		'North America' => array(
			'AI' => 'Anguilla',
			'AG' => 'Antigua and Barbuda',
			'AW' => 'Aruba',
			'BS' => 'Bahamas',
			'BB' => 'Barbados',
			'BZ' => 'Belize',
			'BM' => 'Bermuda',
			'CA' => 'Canada',
			'KY' => 'Cayman Islands',
			'CR' => 'Costa Rica',
			'CU' => 'Cuba',
			'DM' => 'Dominica',
			'DO' => 'Dominican Republic',
			'SV' => 'El Salvador',
			'GL' => 'Greenland',
			'GD' => 'Grenada',
			'GP' => 'Guadeloupe',
			'GT' => 'Guatemala',
			'HT' => 'Haiti',
			'HN' => 'Honduras',
			'JM' => 'Jamaica',
			'MQ' => 'Martinique',
			'MX' => 'Mexico',
			'MS' => 'Montserrat',
			'AN' => 'Netherlands Antilles',
			'NI' => 'Nicaragua',
			'PA' => 'Panama',
			'PR' => 'Puerto Rico',
			'BL' => 'Saint Barthélemy',
			'KN' => 'Saint Kitts and Nevis',
			'LC' => 'Saint Lucia',
			'MF' => 'Saint Martin (French part)',
			'PM' => 'Saint Pierre and Miquelon',
			'VC' => 'Saint Vincent and the Grenadines',
			'TT' => 'Trinidad and Tobago',
			'TC' => 'Turks and Caicos Islands',
			'US' => 'United States',
			'UM' => 'United States Minor Outlying Islands',
			'VG' => 'Virgin Islands, British',
			'VI' => 'Virgin Islands, U.S.',
		),
		'South America' => array(
			'AR' => 'Argentina',
			'BO' => 'Bolivia',
			'BR' => 'Brazil',
			'CL' => 'Chile',
			'CO' => 'Colombia',
			'EC' => 'Ecuador',
			'FK' => 'Falkland Islands (Malvinas)',
			'GF' => 'French Guiana',
			'GY' => 'Guyana',
			'PY' => 'Paraguay',
			'PE' => 'Peru',
			'SR' => 'Suriname',
			'UY' => 'Uruguay',
			'VE' => 'Venezuela',
		),
		'Africa' => array(
			'DZ' => 'Algeria',
			'AO' => 'Angola',
			'BJ' => 'Benin',
			'BW' => 'Botswana',
			'BF' => 'Burkina Faso',
			'BI' => 'Burundi',
			'CM' => 'Cameroon',
			'CV' => 'Cape Verde',
			'CF' => 'Central African Republic',
			'TD' => 'Chad',
			'KM' => 'Comoros',
			'CG' => 'Congo',
			'CD' => 'Congo, The Democratic Republic of the',
			'CI' => 'Côte d\'Ivoire',
			'DJ' => 'Djibouti',
			'EG' => 'Egypt',
			'GQ' => 'Equatorial Guinea',
			'ER' => 'Eritrea',
			'ET' => 'Ethiopia',
			'GA' => 'Gabon',
			'GM' => 'Gambia',
			'GH' => 'Ghana',
			'GN' => 'Guinea',
			'GW' => 'Guinea-Bissau',
			'KE' => 'Kenya',
			'LS' => 'Lesotho',
			'LR' => 'Liberia',
			'LY' => 'Libya',
			'MG' => 'Madagascar',
			'MW' => 'Malawi',
			'ML' => 'Mali',
			'MR' => 'Mauritania',
			'MU' => 'Mauritius',
			'YT' => 'Mayotte',
			'MA' => 'Morocco',
			'MZ' => 'Mozambique',
			'NA' => 'Namibia',
			'NE' => 'Niger',
			'NG' => 'Nigeria',
			'RE' => 'Réunion',
			'RW' => 'Rwanda',
			'SH' => 'Saint Helena',
			'ST' => 'Sao Tome and Principe',
			'SN' => 'Senegal',
			'SC' => 'Seychelles',
			'SL' => 'Sierra Leone',
			'SO' => 'Somalia',
			'ZA' => 'South Africa',
			'SD' => 'Sudan',
			'SZ' => 'Swaziland',
			'TZ' => 'Tanzania, United Republic of',
			'TG' => 'Togo',
			'TN' => 'Tunisia',
			'UG' => 'Uganda',
			'EH' => 'Western Sahara',
			'ZM' => 'Zambia',
			'ZW' => 'Zimbabwe',
		),
		'Antarctica' => array(
			'AQ' => 'Antarctica',
			'BV' => 'Bouvet Island',
			'TF' => 'French Southern Territories',
			'HM' => 'Heard Island and McDonald Islands',
			'GS' => 'South Georgia and the South Sandwich Islands',
		),
		'Asia' => array(
			'AF' => 'Afghanistan',
			'BH' => 'Bahrain',
			'BD' => 'Bangladesh',
			'BT' => 'Bhutan',
			'IO' => 'British Indian Ocean Territory',
			'BN' => 'Brunei Darussalam',
			'KH' => 'Cambodia',
			'CN' => 'China',
			'CX' => 'Christmas Island',
			'CC' => 'Cocos (Keeling) Island',
			'HK' => 'Hong Kong',
			'IN' => 'India',
			'ID' => 'Indonesia',
			'IR' => 'Iran',
			'IQ' => 'Iraq',
			'IL' => 'Israel',
			'JP' => 'Japan',
			'JO' => 'Jordan',
			'KZ' => 'Kazakhstan',
			'KP' => 'Korea, North',
			'KR' => 'Korea, South',
			'KW' => 'Kuwait',
			'KG' => 'Kyrgyzstan',
			'LA' => 'Laos',
			'LB' => 'Lebanon',
			'MO' => 'Macao',
			'MY' => 'Malaysia',
			'MV' => 'Maldives',
			'MN' => 'Mongolia',
			'MM' => 'Burma (Myanmar)',
			'NP' => 'Nepal',
			'OM' => 'Oman',
			'PK' => 'Pakistan',
			'PS' => 'Palestinian Territory, Occupied',
			'PH' => 'Philippines',
			'QA' => 'Qatar',
			'RU' => 'Russian Federation',
			'SA' => 'Saudi Arabia',
			'SG' => 'Singapore',
			'LK' => 'Sri Lanka',
			'SY' => 'Syria',
			'TW' => 'Taiwan',
			'TJ' => 'Tajikistan',
			'TH' => 'Thailand',
			'TL' => 'Timor-Leste',
			'TR' => 'Turkey',
			'TM' => 'Turkmenistan',
			'AE' => 'United Arab Emirates',
			'UZ' => 'Uzbekistan',
			'VN' => 'Viet Nam',
			'YE' => 'Yemen',
		),
		'Europe' => array(
			'AX' => 'Åland Islands',
			'AL' => 'Albania',
			'AD' => 'Andorra',
			'AM' => 'Armenia',
			'AT' => 'Austria',
			'AZ' => 'Azerbaijan',
			'BY' => 'Belarus',
			'BE' => 'Belgium',
			'BA' => 'Bosnia and Herzegovina',
			'BG' => 'Bulgaria',
			'HR' => 'Croatia',
			'CY' => 'Cyprus',
			'CZ' => 'Czech Republic',
			'DK' => 'Denmark',
			'EE' => 'Estonia',
			'FO' => 'Faeroe Islands',
			'FI' => 'Finland',
			'FR' => 'France',
			'PF' => 'French Polynesia',
			'GE' => 'Georgia',
			'DE' => 'Germany',
			'GI' => 'Gibraltar',
			'GR' => 'Greece',
			'GG' => 'Guernsey',
			'HU' => 'Hungary',
			'IS' => 'Iceland',
			'IE' => 'Ireland, Republic of (EIRE)',
			'IM' => 'Isle of Man',
			'IT' => 'Italy',
			'JE' => 'Jersey',
			'LV' => 'Latvia',
			'LI' => 'Liechtenstein',
			'LT' => 'Lithuania',
			'LU' => 'Luxembourg',
			'MK' => 'Macedonia (FYROM)',
			'MT' => 'Malta',
			'MD' => 'Moldova',
			'MC' => 'Monaco',
			'ME' => 'Montenegro',
			'NL' => 'Netherlands',
			'NC' => 'New Caledonia',
			'NO' => 'Norway',
			'PL' => 'Poland',
			'PT' => 'Portugal',
			'RO' => 'Romania',
			'SM' => 'San Marino',
			'RS' => 'Serbia',
			'SK' => 'Slovakia',
			'SI' => 'Slovenia',
			'ES' => 'Spain',
			'SE' => 'Sweden',
			'CH' => 'Switzerland',
			'UA' => 'Ukraine',
			'GB' => 'United Kingdom',
			'VA' => 'Vatican City State',
			'WF' => 'Wallis and Futuna',
		),
		'Oceania' => array(
			'AS' => 'American Samoa',
			'AU' => 'Australia',
			'CK' => 'Cook Islands',
			'FJ' => 'Fiji',
			'GU' => 'Guam',
			'KI' => 'Kiribati',
			'MH' => 'Marshall Islands',
			'FM' => 'Micronesia, Federated States of',
			'NR' => 'Nauru',
			'NZ' => 'New Zealand',
			'NU' => 'Niue',
			'NF' => 'Norfolk Island',
			'MP' => 'Northern Mariana Islands',
			'PW' => 'Palau',
			'PG' => 'Papua New Guinea',
			'PN' => 'Pitcairn',
			'WS' => 'Samoa',
			'SB' => 'Solomon Islands',
			'TK' => 'Tokelau',
			'TO' => 'Tonga',
			'TV' => 'Tuvalu',
			'VU' => 'Vanuatu',
		),
	);
	
	
	var $threeLetterCodes = array(
		'AF' => 'AFG',
		'AL' => 'ALB',
		'DZ' => 'DZA',
		'AS' => 'ASM',
		'AD' => 'AND',
		'AO' => 'AGO',
		'AI' => 'AIA',
		'AQ' => 'ATA',
		'AG' => 'ATG',
		'AR' => 'ARG',
		'AM' => 'ARM',
		'AW' => 'ABW',
		'AU' => 'AUS',
		'AT' => 'AUT',
		'AZ' => 'AZE',
		'BS' => 'BHS',
		'BH' => 'BHR',
		'BD' => 'BGD',
		'BB' => 'BRB',
		'BY' => 'BLR',
		'BE' => 'BEL',
		'BZ' => 'BLZ',
		'BJ' => 'BEN',
		'BM' => 'BMU',
		'BT' => 'BTN',
		'BO' => 'BOL',
		'BA' => 'BIH',
		'BW' => 'BWA',
		'BR' => 'BRA',
		'IO' => 'IOT',
		'VG' => 'VGB',
		'BN' => 'BRN',
		'BG' => 'BGR',
		'BF' => 'BFA',
		'MM' => 'MMR',
		'BI' => 'BDI',
		'KH' => 'KHM',
		'CM' => 'CMR',
		'CA' => 'CAN',
		'CV' => 'CPV',
		'KY' => 'CYM',
		'CF' => 'CAF',
		'TD' => 'TCD',
		'CL' => 'CHL',
		'CN' => 'CHN',
		'CX' => 'CXR',
		'CC' => 'CCK',
		'CO' => 'COL',
		'KM' => 'COM',
		'CK' => 'COK',
		'CR' => 'CRC',
		'HR' => 'HRV',
		'CU' => 'CUB',
		'CY' => 'CYP',
		'CZ' => 'CZE',
		'CD' => 'COD',
		'DK' => 'DNK',
		'DJ' => 'DJI',
		'DM' => 'DMA',
		'DO' => 'DOM',
		'EC' => 'ECU',
		'EG' => 'EGY',
		'SV' => 'SLV',
		'GQ' => 'GNQ',
		'ER' => 'ERI',
		'EE' => 'EST',
		'ET' => 'ETH',
		'FK' => 'FLK',
		'FO' => 'FRO',
		'FJ' => 'FJI',
		'FI' => 'FIN',
		'FR' => 'FRA',
		'PF' => 'PYF',
		'GA' => 'GAB',
		'GM' => 'GMB',
		'GE' => 'GEO',
		'DE' => 'DEU',
		'GH' => 'GHA',
		'GI' => 'GIB',
		'GR' => 'GRC',
		'GL' => 'GRL',
		'GD' => 'GRD',
		'GU' => 'GUM',
		'GT' => 'GTM',
		'GN' => 'GIN',
		'GW' => 'GNB',
		'GY' => 'GUY',
		'HT' => 'HTI',
		'VA' => 'VAT',
		'HN' => 'HND',
		'HK' => 'HKG',
		'HU' => 'HUN',
		'IN' => 'IND',
		'ID' => 'IDN',
		'IR' => 'IRN',
		'IQ' => 'IRQ',
		'IE' => 'IRL',
		'IM' => 'IMN',
		'IL' => 'ISR',
		'IT' => 'ITA',
		'CI' => 'CIV',
		'JM' => 'JAM',
		'JP' => 'JPN',
		'JE' => 'JEY',
		'JO' => 'JOR',
		'KZ' => 'KAZ',
		'KE' => 'KEN',
		'KI' => 'KIR',
		'KW' => 'KWT',
		'KG' => 'KGZ',
		'LA' => 'LAO',
		'LV' => 'LVA',
		'LB' => 'LBN',
		'LS' => 'LSO',
		'LR' => 'LBR',
		'LY' => 'LBY',
		'LI' => 'LIE',
		'LT' => 'LTU',
		'LU' => 'LUX',
		'MO' => 'MAC',
		'MK' => 'MKD',
		'MG' => 'MDG',
		'MW' => 'MWI',
		'MY' => 'MYS',
		'MV' => 'MDV',
		'ML' => 'MLI',
		'MT' => 'MLT',
		'MH' => 'MHL',
		'MR' => 'MRT',
		'MU' => 'MUS',
		'YT' => 'MYT',
		'MX' => 'MEX',
		'FM' => 'FSM',
		'MD' => 'MDA',
		'MC' => 'MCO',
		'MN' => 'MNG',
		'ME' => 'MNE',
		'MS' => 'MSR',
		'MA' => 'MAR',
		'MZ' => 'MOZ',
		'NA' => 'NAM',
		'NR' => 'NRU',
		'NP' => 'NPL',
		'NL' => 'NLD',
		'AN' => 'ANT',
		'NC' => 'NCL',
		'NZ' => 'NZL',
		'NI' => 'NIC',
		'NE' => 'NER',
		'NG' => 'NGA',
		'NU' => 'NIU',
		'KP' => 'PRK',
		'MP' => 'MNP',
		'NO' => 'NOR',
		'OM' => 'OMN',
		'PK' => 'PAK',
		'PW' => 'PLW',
		'PA' => 'PAN',
		'PG' => 'PNG',
		'PY' => 'PRY',
		'PE' => 'PER',
		'PH' => 'PHL',
		'PN' => 'PCN',
		'PL' => 'POL',
		'PT' => 'PRT',
		'PR' => 'PRI',
		'QA' => 'QAT',
		'CG' => 'COG',
		'RO' => 'ROU',
		'RU' => 'RUS',
		'RW' => 'RWA',
		'BL' => 'BLM',
		'SH' => 'SHN',
		'KN' => 'KNA',
		'LC' => 'LCA',
		'MF' => 'MAF',
		'PM' => 'SPM',
		'VC' => 'VCT',
		'WS' => 'WSM',
		'SM' => 'SMR',
		'ST' => 'STP',
		'SA' => 'SAU',
		'SN' => 'SEN',
		'RS' => 'SRB',
		'SC' => 'SYC',
		'SL' => 'SLE',
		'SG' => 'SGP',
		'SK' => 'SVK',
		'SI' => 'SVN',
		'SB' => 'SLB',
		'SO' => 'SOM',
		'ZA' => 'ZAF',
		'KR' => 'KOR',
		'ES' => 'ESP',
		'LK' => 'LKA',
		'SD' => 'SDN',
		'SR' => 'SUR',
		'SJ' => 'SJM',
		'SZ' => 'SWZ',
		'SE' => 'SWE',
		'CH' => 'CHE',
		'SY' => 'SYR',
		'TW' => 'TWN',
		'TJ' => 'TJK',
		'TZ' => 'TZA',
		'TH' => 'THA',
		'TL' => 'TLS',
		'TG' => 'TGO',
		'TK' => 'TKL',
		'TO' => 'TON',
		'TT' => 'TTO',
		'TN' => 'TUN',
		'TR' => 'TUR',
		'TM' => 'TKM',
		'TC' => 'TCA',
		'TV' => 'TUV',
		'UG' => 'UGA',
		'UA' => 'UKR',
		'AE' => 'ARE',
		'GB' => 'GBR',
		'US' => 'USA',
		'UY' => 'URY',
		'VI' => 'VIR',
		'UZ' => 'UZB',
		'VU' => 'VUT',
		'VE' => 'VEN',
		'VN' => 'VNM',
		'WF' => 'WLF',
		'EH' => 'ESH',
		'YE' => 'YEM',
		'ZM' => 'ZMB',
		'ZW' => 'ZWE',
	);
	
	//$_this =& Geography::getInstance();
	function &getInstance() {
		static $instance = array();
		if (!$instance) {
			$instance[0] =& new Geography();
		}
		return $instance[0];
	}
	
	function slugSort($arr){
		$modif_arr = array_map(array('Inflector','slug'),$arr);
		asort($modif_arr);
		foreach($arr as $key => $val){
			$modif_arr[$key] = $val;
		}
		return $modif_arr;
	}
	
	function flatCountries(){
		$_this =& Geography::getInstance();
		$countries = array();
		foreach($_this->countries as $c){
			$countries = array_merge($countries,$c);
		}
		return $countries;
	}
	
	function getCountries($filter = array(), $options = array()) {
		$defOpt = array(
			'translate' => true,
			'continent' => false,
		);
		if(!is_array($options)){
			$options = array('translate' =>$options);
		}
		$opt = array_merge($defOpt,$options);
		$_this =& Geography::getInstance();
		
		if($opt['continent']){
			$countries = $_this->countries;
		}else{
			$countries = array('all'=>$_this->flatCountries());
		}
		
		$final = array();
		foreach($countries as $continent => $list){
			if(!empty($filter)){
				$list = array_intersect_key($list,array_flip($filter));
			}
			
			if($opt['translate']){
				foreach ($list as &$val) {
					$val = __d('o2form',$val,true);
				}
				$continent = __($continent,true);
			}
			
			$list = $_this->slugSort($list);
			if(!empty($list)){
				$final[$continent] = $list;
			}
		}
		
		
		if(!$opt['continent']){
			$final = $final['all'];
		}
		return $final;
	}
	
	function getCountry($code, $translate = true) {
		$_this =& Geography::getInstance();
		$code = strtoupper($code);
		$countries = $_this->flatCountries();
		if(strlen($code) == 3){
			$code = $_this->code3To2($code,true);
		}
		if(!isset($countries[$code])){
			return false;
		}
		$val = $countries[$code];
		
		if($translate){
			$val = __d('o2form',$val,true);
		}
		
		return $val;
	}
	
	function getContinent($code, $translate = true){
		$_this =& Geography::getInstance();
		if(strlen($code) == 3){
			$code = $_this->code3To2($code,true);
		}
		$continents = $_this->countries;
		foreach($continents as $name => $countries){
			if(isset($countries[$code])){
				if($translate){
					return __($name,true);
				}else{
					return $name;
				}
			}
		}
		return null;
	}
	
	function code3To2($code,$returnDef = false){
		$_this =& Geography::getInstance();
		$ncode = strtoupper($code);
		$codes = array_flip($_this->threeLetterCodes);
		
		if(isset($codes[$ncode])){
			return $codes[$ncode];
		}elseif($returnDef ){
			return $code;
		}
		
		return null;
	}
	
	function code2To3($code,$returnDef = false){
		$_this =& Geography::getInstance();
		$ncode = strtoupper($code);
		$codes = $_this->threeLetterCodes;
		
		if(isset($codes[$ncode])){
			return $codes[$ncode];
		}elseif($returnDef ){
			return $code;
		}
		
		return null;
	}
	
	function getCountryCode($name,$returnDef = false) {
		$_this =& Geography::getInstance();
		$countries = $_this->flatCountries();
		
		if(strlen($name) == 2){
			$code = strtoupper($name);
			if(isset($countries[$code])){
				return $code;
			}
		}
		if(strlen($name) == 3){
			$code = $_this->code3To2($name);
			if(!empty($code)){
				return $code;
			}
		}
		
		$normalRight = strtolower(Inflector::slug($name));
		foreach ($countries as $code => $val) {
			$normalLeft = strtolower(Inflector::slug(__d('o2form',$val,true)));
			$normalLeft2 = strtolower(Inflector::slug($val));
			if($normalLeft == $normalRight || $normalLeft2 == $normalRight){
				return $code;
			}
		}
		if($returnDef){
			return $name;
		}
		return null;
	}
	
	function getRegions($country = null,$translate = true) {
		$_this =& Geography::getInstance();
		$allregions = $_this->regions;
		if(is_null($country) || $country === true){
			$regions = array();
			foreach ($allregions as $country => $r) {
				foreach ($r as $code => $region) {
					$regions[$country][$code] = __d('o2form',$region,true);
				}
			}
			return $regions;
		}elseif(!empty($country)){
			$countryCode = $_this->getCountryCode($country);
			if(empty($_this->regions[$countryCode])){
				return false;
			}
			$regions = $_this->regions[$countryCode];
			if($translate){
				foreach ($regions as &$val) {
					$val = __d('o2form',$val,true);
				}
			}
			return $regions;
		}
		return null;
	}
	
	function getLocalTerm($term, $locale = null, $translate = true,$returnDef = false){
		$_this =& Geography::getInstance();
		if(!empty($_this->localeTerms[$term])){
			$res = $_this->localeTerms[$term];
			if(!empty($locale)){
				if(!is_array($locale)){
					$locale = array($locale);
				}
				foreach ($locale as $elem) {
					if(!empty($res[$elem])){
						$res = $res[$elem];
					}
				}
			}
			if(!empty($res) && !is_array($res)){
				if($translate){
					$res = __d('o2form',$res,true);
				}
				return $res;
			}
		}
		if($returnDef){
			return $term;
		}
		return null;
	}
	
	
}
?>