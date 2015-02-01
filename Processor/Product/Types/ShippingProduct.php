<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 1/28/2015
 * Time: 4:53 PM
 */
namespace Processor\Product\Types;

use CPath\Data\Map\IKeyMapper;
use CPath\Render\HTML\Attribute\Attributes;
use CPath\Render\HTML\Attribute\ClassAttributes;
use CPath\Render\HTML\Attribute\StyleAttributes;
use CPath\Render\HTML\Element\Form\HTMLForm;
use CPath\Render\HTML\Element\Form\HTMLInputField;
use CPath\Render\HTML\Element\Form\HTMLSelectField;
use CPath\Render\HTML\Element\HTMLElement;
use CPath\Render\HTML\Header\HTMLHeaderScript;
use CPath\Request\IRequest;
use CPath\Request\Validation\RequiredValidation;
use Processor\Invoice\Types\ShippingInvoice;
use Processor\Wallet\Type\AbstractWallet;

class ShippingProduct extends AbstractProductType
{
	const TYPE_NAME = 'shipping';
	const TYPE_DESCRIPTION = "Shipped Product";

	const PARAM_SHIPPING_FULL_NAME = 'shipping-name';
	const PARAM_PRODUCT_TYPE = 'product-type';

	const PARAM_SHIPPING_ADDRESS = 'shipping-address';
	const PARAM_SHIPPING_ADDRESS2 = 'shipping-address2';
	const PARAM_SHIPPING_CITY = 'shipping-city';
	const PARAM_SHIPPING_STATE = 'shipping-state';
	const PARAM_SHIPPING_ZIPCODE = 'shipping-zipcode';
	const PARAM_SHIPPING_COUNTRY = 'shipping-country';
	const PARAM_PRODUCT_TITLE = 'product-title';
	const PARAM_PRODUCT_TOTAL_COST = 'product-total-cost';

	const CLS_FIELDSET_SHIPPING_PRODUCT = 'fieldset-shipping-product';

	protected static $STATES = array(
		''                                                            => null,
		'AL - Alabama'                                                => 'AL',
		'AK - Alaska'                                                 => 'AK',
		'AS - American Samoa'                                         => 'AS',
		'AZ - Arizona'                                                => 'AZ',
		'AR - Arkansas'                                               => 'AR',
		'CA - California'                                             => 'CA',
		'CO - Colorado'                                               => 'CO',
		'CT - Connecticut'                                            => 'CT',
		'DE - Delaware'                                               => 'DE',
		'DC - District Of Columbia'                                   => 'DC',
		'FM - Federated States Of Micronesia'                         => 'FM',
		'FL - Florida'                                                => 'FL',
		'GA - Georgia'                                                => 'GA',
		'GU - Guam Gu'                                                => 'GU',
		'HI - Hawaii'                                                 => 'HI',
		'ID - Idaho'                                                  => 'ID',
		'IL - Illinois'                                               => 'IL',
		'IN - Indiana'                                                => 'IN',
		'IA - Iowa'                                                   => 'IA',
		'KS - Kansas'                                                 => 'KS',
		'KY - Kentucky'                                               => 'KY',
		'LA - Louisiana'                                              => 'LA',
		'ME - Maine'                                                  => 'ME',
		'MH - Marshall Islands'                                       => 'MH',
		'MD - Maryland'                                               => 'MD',
		'MA - Massachusetts'                                          => 'MA',
		'MI - Michigan'                                               => 'MI',
		'MN - Minnesota'                                              => 'MN',
		'MS - Mississippi'                                            => 'MS',
		'MO - Missouri'                                               => 'MO',
		'MT - Montana'                                                => 'MT',
		'NE - Nebraska'                                               => 'NE',
		'NV - Nevada'                                                 => 'NV',
		'NH - New Hampshire'                                          => 'NH',
		'NJ - New Jersey'                                             => 'NJ',
		'NM - New Mexico'                                             => 'NM',
		'NY - New York'                                               => 'NY',
		'NC - North Carolina'                                         => 'NC',
		'ND - North Dakota'                                           => 'ND',
		'MP - Northern Mariana Islands'                               => 'MP',
		'OH - Ohio'                                                   => 'OH',
		'OK - Oklahoma'                                               => 'OK',
		'OR - Oregon'                                                 => 'OR',
		'PW - Palau'                                                  => 'PW',
		'PA - Pennsylvania'                                           => 'PA',
		'PR - Puerto Rico'                                            => 'PR',
		'RI - Rhode Island'                                           => 'RI',
		'SC - South Carolina'                                         => 'SC',
		'SD - South Dakota'                                           => 'SD',
		'TN - Tennessee'                                              => 'TN',
		'TX - Texas'                                                  => 'TX',
		'UT - Utah'                                                   => 'UT',
		'VT - Vermont'                                                => 'VT',
		'VI - Virgin Islands'                                         => 'VI',
		'VA - Virginia'                                               => 'VA',
		'WA - Washington'                                             => 'WA',
		'WV - West Virginia'                                          => 'WV',
		'WI - Wisconsin'                                              => 'WI',
		'WY - Wyoming'                                                => 'WY',
		'AE - Armed Forces Africa \\ Canada \\ Europe \\ Middle East' => 'AE',
		'AA - Armed Forces America (except Canada)'                   => 'AA',
		'AP - Armed Forces Pacific'                                   => 'AP',
	);
	protected static $COUNTRIES = array(
		'US - United States Of America'                               => 'US',
		'AD - Andorra'                                                => 'AD',
		'AE - United Arab Emirates'                                   => 'AE',
		'AF - Afghanistan'                                            => 'AF',
		'AG - Antigua &amp; Barbuda'                                  => 'AG',
		'AI - Anguilla'                                               => 'AI',
		'AL - Albania'                                                => 'AL',
		'AM - Armenia'                                                => 'AM',
		'AN - Netherlands Antilles'                                   => 'AN',
		'AO - Angola'                                                 => 'AO',
		'AQ - Antarctica'                                             => 'AQ',
		'AR - Argentina'                                              => 'AR',
		'AS - American Samoa'                                         => 'AS',
		'AT - Austria'                                                => 'AT',
		'AU - Australia'                                              => 'AU',
		'AW - Aruba'                                                  => 'AW',
		'AZ - Azerbaijan'                                             => 'AZ',
		'BA - Bosnia And Herzegovina'                                 => 'BA',
		'BB - Barbados'                                               => 'BB',
		'BD - Bangladesh'                                             => 'BD',
		'BE - Belgium'                                                => 'BE',
		'BF - Burkina Faso'                                           => 'BF',
		'BG - Bulgaria'                                               => 'BG',
		'BH - Bahrain'                                                => 'BH',
		'BI - Burundi'                                                => 'BI',
		'BJ - Benin'                                                  => 'BJ',
		'BM - Bermuda'                                                => 'BM',
		'BN - Brunei Darussalam'                                      => 'BN',
		'BO - Bolivia'                                                => 'BO',
		'BR - Brazil'                                                 => 'BR',
		'BS - Bahama'                                                 => 'BS',
		'BT - Bhutan'                                                 => 'BT',
		'BU - Burma (no Longer Exists)'                               => 'BU',
		'BV - Bouvet Island'                                          => 'BV',
		'BW - Botswana'                                               => 'BW',
		'BY - Belarus'                                                => 'BY',
		'BZ - Belize'                                                 => 'BZ',
		'CA - Canada'                                                 => 'CA',
		'CC - Cocos (keeling) Islands'                                => 'CC',
		'CF - Central African Republic'                               => 'CF',
		'CG - Congo'                                                  => 'CG',
		'CH - Switzerland'                                            => 'CH',
		'CI - Côte D\'ivoire (ivory Coast)'                           => 'CI',
		'CK - Cook Iislands'                                          => 'CK',
		'CL - Chile'                                                  => 'CL',
		'CM - Cameroon'                                               => 'CM',
		'CN - China'                                                  => 'CN',
		'CO - Colombia'                                               => 'CO',
		'CR - Costa Rica'                                             => 'CR',
		'CS - Czechoslovakia (no Longer Exists)'                      => 'CS',
		'CU - Cuba'                                                   => 'CU',
		'CV - Cape Verde'                                             => 'CV',
		'CX - Christmas Island'                                       => 'CX',
		'CY - Cyprus'                                                 => 'CY',
		'CZ - Czech Republic'                                         => 'CZ',
		'DD - German Democratic Republic (no Longer Exists)'          => 'DD',
		'DE - Germany'                                                => 'DE',
		'DJ - Djibouti'                                               => 'DJ',
		'DK - Denmark'                                                => 'DK',
		'DM - Dominica'                                               => 'DM',
		'DO - Dominican Republic'                                     => 'DO',
		'DZ - Algeria'                                                => 'DZ',
		'EC - Ecuador'                                                => 'EC',
		'EE - Estonia'                                                => 'EE',
		'EG - Egypt'                                                  => 'EG',
		'EH - Western Sahara'                                         => 'EH',
		'ER - Eritrea'                                                => 'ER',
		'ES - Spain'                                                  => 'ES',
		'ET - Ethiopia'                                               => 'ET',
		'FI - Finland'                                                => 'FI',
		'FJ - Fiji'                                                   => 'FJ',
		'FK - Falkland Islands (malvinas)'                            => 'FK',
		'FM - Micronesia'                                             => 'FM',
		'FO - Faroe Islands'                                          => 'FO',
		'FR - France'                                                 => 'FR',
		'FX - France, Metropolitan'                                   => 'FX',
		'GA - Gabon'                                                  => 'GA',
		'GB - United Kingdom (great Britain)'                         => 'GB',
		'GD - Grenada'                                                => 'GD',
		'GE - Georgia'                                                => 'GE',
		'GF - French Guiana'                                          => 'GF',
		'GH - Ghana'                                                  => 'GH',
		'GI - Gibraltar'                                              => 'GI',
		'GL - Greenland'                                              => 'GL',
		'GM - Gambia'                                                 => 'GM',
		'GN - Guinea'                                                 => 'GN',
		'GP - Guadeloupe'                                             => 'GP',
		'GQ - Equatorial Guinea'                                      => 'GQ',
		'GR - Greece'                                                 => 'GR',
		'GS - South Georgia And The South Sandwich Islands'           => 'GS',
		'GT - Guatemala'                                              => 'GT',
		'GU - Guam'                                                   => 'GU',
		'GW - Guinea-bissau'                                          => 'GW',
		'GY - Guyana'                                                 => 'GY',
		'HK - Hong Kong'                                              => 'HK',
		'HM - Heard &amp; Mcdonald Islands'                           => 'HM',
		'HN - Honduras'                                               => 'HN',
		'HR - Croatia'                                                => 'HR',
		'HT - Haiti'                                                  => 'HT',
		'HU - Hungary'                                                => 'HU',
		'ID - Indonesia'                                              => 'ID',
		'IE - Ireland'                                                => 'IE',
		'IL - Israel'                                                 => 'IL',
		'IN - India'                                                  => 'IN',
		'IO - British Indian Ocean Territory'                         => 'IO',
		'IQ - Iraq'                                                   => 'IQ',
		'IR - Islamic Republic Of Iran'                               => 'IR',
		'IS - Iceland'                                                => 'IS',
		'IT - Italy'                                                  => 'IT',
		'JM - Jamaica'                                                => 'JM',
		'JO - Jordan'                                                 => 'JO',
		'JP - Japan'                                                  => 'JP',
		'KE - Kenya'                                                  => 'KE',
		'KG - Kyrgyzstan'                                             => 'KG',
		'KH - Cambodia'                                               => 'KH',
		'KI - Kiribati'                                               => 'KI',
		'KM - Comoros'                                                => 'KM',
		'KN - St. Kitts And Nevis'                                    => 'KN',
		'KP - Korea, Democratic People\'s Republic Of'                => 'KP',
		'KR - Korea, Republic Of'                                     => 'KR',
		'KW - Kuwait'                                                 => 'KW',
		'KY - Cayman Islands'                                         => 'KY',
		'KZ - Kazakhstan'                                             => 'KZ',
		'LA - Lao People\'s Democratic Republic'                      => 'LA',
		'LB - Lebanon'                                                => 'LB',
		'LC - Saint Lucia'                                            => 'LC',
		'LI - Liechtenstein'                                          => 'LI',
		'LK - Sri Lanka'                                              => 'LK',
		'LR - Liberia'                                                => 'LR',
		'LS - Lesotho'                                                => 'LS',
		'LT - Lithuania'                                              => 'LT',
		'LU - Luxembourg'                                             => 'LU',
		'LV - Latvia'                                                 => 'LV',
		'LY - Libyan Arab Jamahiriya'                                 => 'LY',
		'MA - Morocco'                                                => 'MA',
		'MC - Monaco'                                                 => 'MC',
		'MD - Moldova, Republic Of'                                   => 'MD',
		'MG - Madagascar'                                             => 'MG',
		'MH - Marshall Islands'                                       => 'MH',
		'ML - Mali'                                                   => 'ML',
		'MN - Mongolia'                                               => 'MN',
		'MM - Myanmar'                                                => 'MM',
		'MO - Macau'                                                  => 'MO',
		'MP - Northern Mariana Islands'                               => 'MP',
		'MQ - Martinique'                                             => 'MQ',
		'MR - Mauritania'                                             => 'MR',
		'MS - Monserrat'                                              => 'MS',
		'MT - Malta'                                                  => 'MT',
		'MU - Mauritius'                                              => 'MU',
		'MV - Maldives'                                               => 'MV',
		'MW - Malawi'                                                 => 'MW',
		'MX - Mexico'                                                 => 'MX',
		'MY - Malaysia'                                               => 'MY',
		'MZ - Mozambique'                                             => 'MZ',
		'NA - Namibia'                                                => 'NA',
		'NC - New Caledonia'                                          => 'NC',
		'NE - Niger'                                                  => 'NE',
		'NF - Norfolk Island'                                         => 'NF',
		'NG - Nigeria'                                                => 'NG',
		'NI - Nicaragua'                                              => 'NI',
		'NL - Netherlands'                                            => 'NL',
		'NO - Norway'                                                 => 'NO',
		'NP - Nepal'                                                  => 'NP',
		'NR - Nauru'                                                  => 'NR',
		'NT - Neutral Zone (no Longer Exists)'                        => 'NT',
		'NU - Niue'                                                   => 'NU',
		'NZ - New Zealand'                                            => 'NZ',
		'OM - Oman'                                                   => 'OM',
		'PA - Panama'                                                 => 'PA',
		'PE - Peru'                                                   => 'PE',
		'PF - French Polynesia'                                       => 'PF',
		'PG - Papua New Guinea'                                       => 'PG',
		'PH - Philippines'                                            => 'PH',
		'PK - Pakistan'                                               => 'PK',
		'PL - Poland'                                                 => 'PL',
		'PM - St. Pierre &amp; Miquelon'                              => 'PM',
		'PN - Pitcairn'                                               => 'PN',
		'PR - Puerto Rico'                                            => 'PR',
		'PT - Portugal'                                               => 'PT',
		'PW - Palau'                                                  => 'PW',
		'PY - Paraguay'                                               => 'PY',
		'QA - Qatar'                                                  => 'QA',
		'RE - Réunion'                                                => 'RE',
		'RO - Romania'                                                => 'RO',
		'RU - Russian Federation'                                     => 'RU',
		'RW - Rwanda'                                                 => 'RW',
		'SA - Saudi Arabia'                                           => 'SA',
		'SB - Solomon Islands'                                        => 'SB',
		'SC - Seychelles'                                             => 'SC',
		'SD - Sudan'                                                  => 'SD',
		'SE - Sweden'                                                 => 'SE',
		'SG - Singapore'                                              => 'SG',
		'SH - St. Helena'                                             => 'SH',
		'SI - Slovenia'                                               => 'SI',
		'SJ - Svalbard &amp; Jan Mayen Islands'                       => 'SJ',
		'SK - Slovakia'                                               => 'SK',
		'SL - Sierra Leone'                                           => 'SL',
		'SM - San Marino'                                             => 'SM',
		'SN - Senegal'                                                => 'SN',
		'SO - Somalia'                                                => 'SO',
		'SR - Suriname'                                               => 'SR',
		'ST - Sao Tome &amp; Principe'                                => 'ST',
		'SU - Union Of Soviet Socialist Republics (no Longer Exists)' => 'SU',
		'SV - El Salvador'                                            => 'SV',
		'SY - Syrian Arab Republic'                                   => 'SY',
		'SZ - Swaziland'                                              => 'SZ',
		'TC - Turks &amp; Caicos Islands'                             => 'TC',
		'TD - Chad'                                                   => 'TD',
		'TF - French Southern Territories'                            => 'TF',
		'TG - Togo'                                                   => 'TG',
		'TH - Thailand'                                               => 'TH',
		'TJ - Tajikistan'                                             => 'TJ',
		'TK - Tokelau'                                                => 'TK',
		'TM - Turkmenistan'                                           => 'TM',
		'TN - Tunisia'                                                => 'TN',
		'TO - Tonga'                                                  => 'TO',
		'TP - East Timor'                                             => 'TP',
		'TR - Turkey'                                                 => 'TR',
		'TT - Trinidad &amp; Tobago'                                  => 'TT',
		'TV - Tuvalu'                                                 => 'TV',
		'TW - Taiwan, Province Of China'                              => 'TW',
		'TZ - Tanzania, United Republic Of'                           => 'TZ',
		'UA - Ukraine'                                                => 'UA',
		'UG - Uganda'                                                 => 'UG',
		'UM - United States Minor Outlying Islands'                   => 'UM',
		'US - United States Of America '                              => 'US',
		'UY - Uruguay'                                                => 'UY',
		'UZ - Uzbekistan'                                             => 'UZ',
		'VA - Vatican City State (holy See)'                          => 'VA',
		'VC - St. Vincent &amp; The Grenadines'                       => 'VC',
		'VE - Venezuela'                                              => 'VE',
		'VG - British Virgin Islands'                                 => 'VG',
		'VI - United States Virgin Islands'                           => 'VI',
		'VN - Viet Nam'                                               => 'VN',
		'VU - Vanuatu'                                                => 'VU',
		'WF - Wallis &amp; Futuna Islands'                            => 'WF',
		'WS - Samoa'                                                  => 'WS',
		'YD - Democratic Yemen (no Longer Exists)'                    => 'YD',
		'YE - Yemen'                                                  => 'YE',
		'YT - Mayotte'                                                => 'YT',
		'YU - Yugoslavia'                                             => 'YU',
		'ZA - South Africa'                                           => 'ZA',
		'ZM - Zambia'                                                 => 'ZM',
		'ZR - Zaire'                                                  => 'ZR',
		'ZW - Zimbabwe'                                               => 'ZW',
	);

	public $title;
	public $total;

	public $full_name;
	public $address;
	public $address2;
	public $city;
	public $state;
	public $zip;
	public $country;

	public $created;

	public function __construct() {
	}

	/**
	 * @return String
	 */
	function getProductTitle() {
		return $this->title;
	}

	function getTotalCost() {
		return $this->total;
	}

	function getConfigFieldSet(IRequest $Request) {

		return new HTMLElement('fieldset', self::CLS_FIELDSET_CONFIG,
			new Attributes('data-' . static::PARAM_PRODUCT_TYPE, $this->getTypeName()),

			new HTMLElement('legend', 'legend-shipping', "Configure Product"),

			new HTMLElement('label', 'label-' . self::PARAM_PRODUCT_TITLE, "Product Title<br/>",
				new HTMLInputField(self::PARAM_PRODUCT_TITLE, $this->title,
					new Attributes('placeholder', '"My Product - $9.99"'),
					new RequiredValidation()
				)
			),

			"<br/><br/>",
			new HTMLElement('label', 'label-' . self::PARAM_PRODUCT_TOTAL_COST, "Total Cost<br/>",
				new HTMLInputField(self::PARAM_PRODUCT_TOTAL_COST, $this->total,
					new Attributes('placeholder', '"9.99"'),
					new RequiredValidation()
				)
			)
		);
	}

	/**
	 * Validate the request
	 * @param IRequest $Request
	 * @param HTMLForm $ThrowForm
	 * @throws \CPath\Request\Validation\Exceptions\ValidationException
	 * @return array|void optionally returns an associative array of modified field names and values
	 */
	function validateConfigRequest(IRequest $Request, HTMLForm $ThrowForm=null) {
		$Form = new HTMLForm('POST',
			$this->getConfigFieldSet($Request)
		);
		$Form->validateRequest($Request, $ThrowForm);

		$this->title = $Request[self::PARAM_PRODUCT_TITLE];
		$this->total = $Request[self::PARAM_PRODUCT_TOTAL_COST];
	}

	function getOrderFieldSet(IRequest $Request, $title = null) {
		$title ?: $title = $this->getTypeDescription();

		$FieldSet = new HTMLElement('fieldset', self::CLS_FIELDSET_PRODUCT . ' ' . self::CLS_FIELDSET_SHIPPING_PRODUCT,
			new HTMLHeaderScript('http://ziplookup.googlecode.com/git/zip-lookup/zip-lookup.js'),

			new Attributes('data-' . static::PARAM_PRODUCT_TYPE, $this->getTypeName()),

			new HTMLElement('legend', 'legend-shipping', $title),

			"<div style='display: inline-block'>",

			new HTMLElement('label', 'label-' . self::PARAM_SHIPPING_FULL_NAME, "Full Name<br/>",
				new HTMLInputField(self::PARAM_SHIPPING_FULL_NAME, $this->full_name,
					new Attributes('placeholder', '"Sam Bell"'),
					new Attributes('size', 10),
					new RequiredValidation()
				)
			),

			"<br/><br/>",
			new HTMLElement('label', 'label-' . self::PARAM_SHIPPING_ADDRESS, "Shipping Address<br/>",
				new HTMLInputField(self::PARAM_SHIPPING_ADDRESS,  $this->address,
					new Attributes('size', 12),
					new Attributes('placeholder', '"123 w. my street"'),
					new RequiredValidation()
				)
			),

			"<br/><br/>",
			new HTMLElement('label', 'label-' . self::PARAM_SHIPPING_ADDRESS, "Shipping Address #2<br/>",

				new HTMLInputField(self::PARAM_SHIPPING_ADDRESS2, $this->address2,
					new Attributes('size', 12),
					new Attributes('placeholder', '"#101"')
//					new RequiredValidation()
				)
			),

			"</div>",
			"<div style='float: right; margin-left: 1em;'>",

			new HTMLElement('label', 'label-' . self::PARAM_SHIPPING_ZIPCODE, "Zip Code<br/>",
				new HTMLInputField(self::PARAM_SHIPPING_ZIPCODE, $this->zip,
					new ClassAttributes('zip-lookup-field-zipcode'),
					new Attributes('placeholder', '"12345"'),
					new Attributes('size', 5),
					new RequiredValidation()
				)
			),

			"<br/><br/>",
			new HTMLElement('label', 'label-' . self::PARAM_SHIPPING_CITY, "City<br/>",
				new HTMLInputField(self::PARAM_SHIPPING_CITY, $this->city,
					new ClassAttributes('zip-lookup-field-city'),
					new Attributes('placeholder', '"Austin"'),
					new Attributes('size', 12),
					new RequiredValidation()
				)
			),

			"<br/><br/>",
			new HTMLElement('label', 'label-' . self::PARAM_SHIPPING_STATE, "State<br/>",
				new StyleAttributes('display', 'inline-block'),
				$SelectStates = new HTMLSelectField(self::PARAM_SHIPPING_STATE, self::$STATES,
					new Attributes('placeholder', '"TX"'),
					new ClassAttributes('zip-lookup-field-state-short'),
					new StyleAttributes('width', '4.5em'),
					new RequiredValidation()
				)
			),

			new HTMLElement('label', 'label-' . self::PARAM_SHIPPING_COUNTRY, "Country<br/>",
//				new StyleAttributes('float', 'right'),
				new StyleAttributes('display', 'inline-block'),
				$SelectCountry = new HTMLSelectField(self::PARAM_SHIPPING_COUNTRY, self::$COUNTRIES,
					new Attributes('placeholder', '"USA"'),
					new StyleAttributes('width', '4.5em'),
					new ClassAttributes('zip-lookup-field-state-country'),
					new RequiredValidation()
				)
			),

			"</div>"
		);

		$SelectCountry->setInputValue($this->country);
		$SelectStates->setInputValue($this->state);
		return $FieldSet;
	}

	/**
	 * Validate the request
	 * @param IRequest $Request
	 * @param HTMLForm $ThrowForm
	 * @throws \CPath\Request\Validation\Exceptions\ValidationException
	 * @return array|void optionally returns an associative array of modified field names and values
	 */
	function validateOrderRequest(IRequest $Request, HTMLForm $ThrowForm=null) {
		$Form = new HTMLForm('POST',
			$this->getOrderFieldSet($Request)
		);
		$Form->validateRequest($Request, $ThrowForm);

		$this->full_name = $Request[self::PARAM_SHIPPING_FULL_NAME];

		$this->address = $Request[self::PARAM_SHIPPING_ADDRESS];
		$this->address2 = $Request[self::PARAM_SHIPPING_ADDRESS2];
		$this->city = $Request[self::PARAM_SHIPPING_CITY];
		$this->state = $Request[self::PARAM_SHIPPING_STATE];
		$this->country = $Request[self::PARAM_SHIPPING_COUNTRY];
		$this->zip = $Request[self::PARAM_SHIPPING_ZIPCODE];

		$this->created = time();
	}

	/**
	 * @param IRequest $Request
	 * @param AbstractWallet $Wallet
	 * @return \Processor\Invoice\Types\AbstractInvoice
	 */
	function createNewInvoice(IRequest $Request, AbstractWallet $Wallet) {
		$this->validateOrderRequest($Request);

		$Invoice = new ShippingInvoice($this, $Wallet);

		return $Invoice;
	}

	/**
	 * Export to string
	 * @return String
	 */
	function exportToString() {
		$export = '';

		$export .= "\n" . $this->title;
		$export .= "\n";
		$export .= "\nTotal:   " . $this->total;
		$export .= "\nName:    " . $this->full_name;
		$export .= "\nAddress: " . $this->address;
		$export .= "\n         " . $this->address2;
		$export .= "\nCity:    " . $this->city;
		$export .= "\nState:   " . $this->state;
		$export .= "\nZip:     " . $this->zip;
		$export .= "\nCountry: " . $this->country;

		return $export;
	}

	/**
	 * Map data to the key map
	 * @param IKeyMapper $Map the map inst to add data to
	 * @internal param \CPath\Request\IRequest $Request
	 * @internal param \CPath\Request\IRequest $Request
	 * @return void
	 */
	function mapKeys(IKeyMapper $Map) {
		parent::mapKeys($Map);

		if($this->created) {
			$Map->map('shipping-full_name', $this->full_name);
			$Map->map('shipping-address', $this->address);
			$Map->map('shipping-address2', $this->address2);
			$Map->map('shipping-city', $this->city);
			$Map->map('shipping-state', $this->state);
			$Map->map('shipping-country', $this->country);
		}
	}


}
