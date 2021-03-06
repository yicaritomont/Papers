<?php

use Illuminate\Database\Seeder;
use App\Country;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function run()
    {
        $countrys = array(
            array('id' => 	1	, 'name' => 'Afganistán' , 'numcode' => '93'),
            array('id' => 	2	, 'name' => 'Albania' , 'numcode' => '355'),
            array('id' => 	3	, 'name' => 'Alemania' , 'numcode' => '49'),
            array('id' => 	4	, 'name' => 'Andorra' , 'numcode' => '376'),
            array('id' => 	5	, 'name' => 'Angola' , 'numcode' => '244'),
            array('id' => 	6	, 'name' => 'Anguila' , 'numcode' => '1 264'),
            array('id' => 	7	, 'name' => 'Antártida' , 'numcode' => '6721'),
            array('id' => 	8	, 'name' => 'Antigua y Barbuda' , 'numcode' => '1 268'),
            array('id' => 	9	, 'name' => 'Arabia Saudita' , 'numcode' => '966'),
            array('id' => 	10	, 'name' => 'Argelia' , 'numcode' => '213'),
            array('id' => 	11	, 'name' => 'Argentina' , 'numcode' => '54'),
            array('id' => 	12	, 'name' => 'Armenia' , 'numcode' => '374'),
            array('id' => 	13	, 'name' => 'Aruba' , 'numcode' => '297'),
            array('id' => 	14	, 'name' => 'Australia' , 'numcode' => '61'),
            array('id' => 	15	, 'name' => 'Austria' , 'numcode' => '43'),
            array('id' => 	16	, 'name' => 'Azerbaiyán' , 'numcode' => '994'),
            array('id' => 	17	, 'name' => 'Bahamas' , 'numcode' => '1 242'),
            array('id' => 	18	, 'name' => 'Bangladés' , 'numcode' => '880'),
            array('id' => 	19	, 'name' => 'Barbados' , 'numcode' => '1 246'),
            array('id' => 	20	, 'name' => 'Baréin' , 'numcode' => '973'),
            array('id' => 	21	, 'name' => 'Bélgica' , 'numcode' => '32'),
            array('id' => 	22	, 'name' => 'Belice' , 'numcode' => '501'),
            array('id' => 	23	, 'name' => 'Benín' , 'numcode' => '229'),
            array('id' => 	24	, 'name' => 'Bermudas' , 'numcode' => '1 441'),
            array('id' => 	25	, 'name' => 'Bielorrusia' , 'numcode' => '375'),
            array('id' => 	26	, 'name' => 'Bolivia, Estado Plurinacional de' , 'numcode' => '591'),
            array('id' => 	27	, 'name' => 'Bonaire, San Eustaquio y Saba' , 'numcode' => '5997'),
            array('id' => 	28	, 'name' => 'Bosnia y Herzegovina' , 'numcode' => '387'),
            array('id' => 	29	, 'name' => 'Botsuana' , 'numcode' => '267'),
            array('id' => 	30	, 'name' => 'Brasil' , 'numcode' => '55'),
            array('id' => 	31	, 'name' => 'Brunéi Darussalam' , 'numcode' => '673'),
            array('id' => 	32	, 'name' => 'Bulgaria' , 'numcode' => '359'),
            array('id' => 	33	, 'name' => 'Burkina Faso' , 'numcode' => '226'),
            array('id' => 	34	, 'name' => 'Burundi' , 'numcode' => '257'),
            array('id' => 	35	, 'name' => 'Bután' , 'numcode' => '975'),
            array('id' => 	36	, 'name' => 'Cabo Verde' , 'numcode' => '238'),
            array('id' => 	37	, 'name' => 'Camboya' , 'numcode' => '855'),
            array('id' => 	38	, 'name' => 'Camerún' , 'numcode' => '237'),
            array('id' => 	39	, 'name' => 'Canadá' , 'numcode' => '1'),
            array('id' => 	40	, 'name' => 'Chad' , 'numcode' => '235'),
            array('id' => 	41	, 'name' => 'Chile' , 'numcode' => '56'),
            array('id' => 	42	, 'name' => 'China, República Popular' , 'numcode' => '86'),
            array('id' => 	43	, 'name' => 'Chipre' , 'numcode' => '357'),
            array('id' => 	44	, 'name' => 'Colombia' , 'numcode' => '57'),
            array('id' => 	45	, 'name' => 'Comoras' , 'numcode' => '269'),
            array('id' => 	46	, 'name' => 'Congo, La República Democrática del' , 'numcode' => '242'),
            array('id' => 	47	, 'name' => 'Congo' , 'numcode' => '243'),
            array('id' => 	48	, 'name' => 'Corea, República de' , 'numcode' => '82'),
            array('id' => 	49	, 'name' => 'Corea, República Democrática Popular de' , 'numcode' => '850'),
            array('id' => 	50	, 'name' => 'Costa de Marfil' , 'numcode' => '225'),
            array('id' => 	51	, 'name' => 'Costa Rica' , 'numcode' => '506'),
            array('id' => 	52	, 'name' => 'Croacia' , 'numcode' => '385'),
            array('id' => 	53	, 'name' => 'Cuba' , 'numcode' => '53'),
            array('id' => 	54	, 'name' => 'Curazao' , 'numcode' => '599'),
            array('id' => 	55	, 'name' => 'Dinamarca' , 'numcode' => '45'),
            array('id' => 	56	, 'name' => 'Dominica' , 'numcode' => '1 767'),
            array('id' => 	57	, 'name' => 'Ecuador' , 'numcode' => '593'),
            array('id' => 	58	, 'name' => 'Egipto' , 'numcode' => '20'),
            array('id' => 	59	, 'name' => 'El Salvador' , 'numcode' => '503'),
            array('id' => 	60	, 'name' => 'Emiratos Árabes Unidos' , 'numcode' => '971'),
            array('id' => 	61	, 'name' => 'Eritrea' , 'numcode' => '291'),
            array('id' => 	62	, 'name' => 'Eslovaquia' , 'numcode' => '421'),
            array('id' => 	63	, 'name' => 'Eslovenia' , 'numcode' => '386'),
            array('id' => 	64	, 'name' => 'España' , 'numcode' => '34'),
            array('id' => 	65	, 'name' => 'Estados Unidos' , 'numcode' => '1'),
            array('id' => 	66	, 'name' => 'Estonia' , 'numcode' => '372'),
            array('id' => 	67	, 'name' => 'Etiopía' , 'numcode' => '251'),
            array('id' => 	68	, 'name' => 'Federacion Rusa' , 'numcode' => '7'),
            array('id' => 	69	, 'name' => 'Filipinas' , 'numcode' => '63'),
            array('id' => 	70	, 'name' => 'Finlandia' , 'numcode' => '358'),
            array('id' => 	71	, 'name' => 'Fiyi' , 'numcode' => '679'),
            array('id' => 	72	, 'name' => 'Francia' , 'numcode' => '33'),
            array('id' => 	73	, 'name' => 'Gabón' , 'numcode' => '241'),
            array('id' => 	74	, 'name' => 'Gambia' , 'numcode' => '220'),
            array('id' => 	75	, 'name' => 'Georgia' , 'numcode' => '995'),
            array('id' => 	76	, 'name' => 'Ghana' , 'numcode' => '233'),
            array('id' => 	77	, 'name' => 'Gibraltar' , 'numcode' => '350'),
            array('id' => 	78	, 'name' => 'Granada' , 'numcode' => '1 473'),
            array('id' => 	79	, 'name' => 'Grecia' , 'numcode' => '30'),
            array('id' => 	80	, 'name' => 'Groenlandia' , 'numcode' => '299'),
            array('id' => 	81	, 'name' => 'Guadalupe' , 'numcode' => '590'),
            array('id' => 	82	, 'name' => 'Guam' , 'numcode' => '1 671'),
            array('id' => 	83	, 'name' => 'Guatemala' , 'numcode' => '502'),
            array('id' => 	84	, 'name' => 'Guayana Francesa' , 'numcode' => '594'),
            array('id' => 	85	, 'name' => 'Guernsey' , 'numcode' => '44'),
            array('id' => 	86	, 'name' => 'Guinea-Bisáu' , 'numcode' => '245'),
            array('id' => 	87	, 'name' => 'Guinea Ecuatorial' , 'numcode' => '240'),
            array('id' => 	88	, 'name' => 'Guinea' , 'numcode' => '224'),
            array('id' => 	89	, 'name' => 'Guyana' , 'numcode' => '592'),
            array('id' => 	90	, 'name' => 'Haití' , 'numcode' => '509'),
            array('id' => 	91	, 'name' => 'Honduras' , 'numcode' => '504'),
            array('id' => 	92	, 'name' => 'Hong Kong' , 'numcode' => '852'),
            array('id' => 	93	, 'name' => 'Hungría' , 'numcode' => '36'),
            array('id' => 	94	, 'name' => 'India' , 'numcode' => '91'),
            array('id' => 	95	, 'name' => 'Indonesia' , 'numcode' => '62'),
            array('id' => 	96	, 'name' => 'Irak' , 'numcode' => '964'),
            array('id' => 	97	, 'name' => 'Irán, República Islámica de' , 'numcode' => '98'),
            array('id' => 	98	, 'name' => 'Irlanda' , 'numcode' => '353'),
            array('id' => 	99	, 'name' => 'Isla Bouvet' , 'numcode' => '47'),
            array('id' => 	100	, 'name' => 'Isla de Man' , 'numcode' => '44'),
            array('id' => 	101	, 'name' => 'Isla de Navidad' , 'numcode' => '61'),
            array('id' => 	102	, 'name' => 'Isla Norfolk' , 'numcode' => '6723'),
            array('id' => 	103	, 'name' => 'Islandia' , 'numcode' => '354'),
            array('id' => 	104	, 'name' => 'Islas Åland' , 'numcode' => '358 18'),
            array('id' => 	105	, 'name' => 'Islas Caimán' , 'numcode' => '1 345'),
            array('id' => 	106	, 'name' => 'Islas Cocos (Keeling)' , 'numcode' => '61'),
            array('id' => 	107	, 'name' => 'Islas Cook' , 'numcode' => '682'),
            array('id' => 	108	, 'name' => 'Islas Falkland (Malvinas)' , 'numcode' => '500'),
            array('id' => 	109	, 'name' => 'Islas Feroe' , 'numcode' => '298'),
            array('id' => 	110	, 'name' => 'Islas Georgias del Sur y Sandwich del Sur' , 'numcode' => '500'),
            array('id' => 	111	, 'name' => 'Islas Heard y Mcdonald' , 'numcode' => '1 672'),
            array('id' => 	112	, 'name' => 'Islas Marianas del Norte' , 'numcode' => '1 670'),
            array('id' => 	113	, 'name' => 'Islas Marshall' , 'numcode' => '692'),
            array('id' => 	114	, 'name' => 'Islas Salomón' , 'numcode' => '677'),
            array('id' => 	115	, 'name' => 'Islas Turcas y Caicos' , 'numcode' => '1 649'),
            array('id' => 	116	, 'name' => 'Islas Ultramarinas Menores de Estados Unidos' , 'numcode' => '1 808'),
            array('id' => 	117	, 'name' => 'Islas Virgenes Británicas' , 'numcode' => '1 284'),
            array('id' => 	118	, 'name' => 'Islas Virgenes de Los Estados Unidos' , 'numcode' => '1 340'),
            array('id' => 	119	, 'name' => 'Israel' , 'numcode' => '972'),
            array('id' => 	120	, 'name' => 'Italia' , 'numcode' => '39'),
            array('id' => 	121	, 'name' => 'Jamaica' , 'numcode' => '1 876'),
            array('id' => 	122	, 'name' => 'Japón' , 'numcode' => '81'),
            array('id' => 	123	, 'name' => 'Jersey' , 'numcode' => '44'),
            array('id' => 	124	, 'name' => 'Jordania' , 'numcode' => '962'),
            array('id' => 	125	, 'name' => 'Kazajistán' , 'numcode' => '7'),
            array('id' => 	126	, 'name' => 'Kenia' , 'numcode' => '254'),
            array('id' => 	127	, 'name' => 'Kirguistán' , 'numcode' => '996'),
            array('id' => 	128	, 'name' => 'Kiribati' , 'numcode' => '686'),
            array('id' => 	129	, 'name' => 'Kuwait' , 'numcode' => '965'),
            array('id' => 	130	, 'name' => 'Lesoto' , 'numcode' => '266'),
            array('id' => 	131	, 'name' => 'Letonia' , 'numcode' => '371'),
            array('id' => 	132	, 'name' => 'Líbano' , 'numcode' => '961'),
            array('id' => 	133	, 'name' => 'Liberia' , 'numcode' => '231'),
            array('id' => 	134	, 'name' => 'Libia' , 'numcode' => '218'),
            array('id' => 	135	, 'name' => 'Liechtenstein' , 'numcode' => '423'),
            array('id' => 	136	, 'name' => 'Lituania' , 'numcode' => '370'),
            array('id' => 	137	, 'name' => 'Luxemburgo' , 'numcode' => '352'),
            array('id' => 	138	, 'name' => 'Macao' , 'numcode' => '853'),
            array('id' => 	139	, 'name' => 'Macedonia, La Antigua República Yugoslava de' , 'numcode' => '389'),
            array('id' => 	140	, 'name' => 'Madagascar' , 'numcode' => '261'),
            array('id' => 	141	, 'name' => 'Malasia' , 'numcode' => '60'),
            array('id' => 	142	, 'name' => 'Malaui' , 'numcode' => '265'),
            array('id' => 	143	, 'name' => 'Maldivas' , 'numcode' => '960'),
            array('id' => 	144	, 'name' => 'Malí' , 'numcode' => '223'),
            array('id' => 	145	, 'name' => 'Malta' , 'numcode' => '356'),
            array('id' => 	146	, 'name' => 'Marruecos' , 'numcode' => '212'),
            array('id' => 	147	, 'name' => 'Martinica' , 'numcode' => '596'),
            array('id' => 	148	, 'name' => 'Mauricio' , 'numcode' => '230'),
            array('id' => 	149	, 'name' => 'Mauritania' , 'numcode' => '222'),
            array('id' => 	150	, 'name' => 'Mayotte' , 'numcode' => '262'),
            array('id' => 	151	, 'name' => 'México' , 'numcode' => '52'),
            array('id' => 	152	, 'name' => 'Micronesia, Estados Federados de' , 'numcode' => '691'),
            array('id' => 	153	, 'name' => 'Moldavia, República de' , 'numcode' => '373'),
            array('id' => 	154	, 'name' => 'Mónaco' , 'numcode' => '377'),
            array('id' => 	155	, 'name' => 'Mongolia' , 'numcode' => '976'),
            array('id' => 	156	, 'name' => 'Montenegro' , 'numcode' => '382'),
            array('id' => 	157	, 'name' => 'Montserrat' , 'numcode' => '1 664'),
            array('id' => 	158	, 'name' => 'Mozambique' , 'numcode' => '258'),
            array('id' => 	159	, 'name' => 'Myanmar' , 'numcode' => '95'),
            array('id' => 	160	, 'name' => 'Nabimia' , 'numcode' => '264'),
            array('id' => 	161	, 'name' => 'Nauru' , 'numcode' => '674'),
            array('id' => 	162	, 'name' => 'Nepal' , 'numcode' => '977'),
            array('id' => 	163	, 'name' => 'Nicaragua' , 'numcode' => '505'),
            array('id' => 	164	, 'name' => 'Nigeria' , 'numcode' => '234'),
            array('id' => 	165	, 'name' => 'Níger' , 'numcode' => '227'),
            array('id' => 	166	, 'name' => 'Niue' , 'numcode' => '683'),
            array('id' => 	167	, 'name' => 'Noruega' , 'numcode' => '47'),
            array('id' => 	168	, 'name' => 'Nueva Caledonia' , 'numcode' => '687'),
            array('id' => 	169	, 'name' => 'Nueva Zelanda' , 'numcode' => '64'),
            array('id' => 	170	, 'name' => 'Omán' , 'numcode' => '968'),
            array('id' => 	171	, 'name' => 'Países Bajos' , 'numcode' => '31'),
            array('id' => 	172	, 'name' => 'Pakistán' , 'numcode' => '92'),
            array('id' => 	173	, 'name' => 'Palaos' , 'numcode' => '680'),
            array('id' => 	174	, 'name' => 'Palestina, Estado de' , 'numcode' => '970'),
            array('id' => 	175	, 'name' => 'Panamá' , 'numcode' => '507'),
            array('id' => 	176	, 'name' => 'Papúa Nueva Guinea' , 'numcode' => '675'),
            array('id' => 	177	, 'name' => 'Paraguay' , 'numcode' => '595'),
            array('id' => 	178	, 'name' => 'Perú' , 'numcode' => '51'),
            array('id' => 	179	, 'name' => 'Pitcairn' , 'numcode' => '64'),
            array('id' => 	180	, 'name' => 'Polinesia Francesa' , 'numcode' => '689'),
            array('id' => 	181	, 'name' => 'Polonia' , 'numcode' => '48'),
            array('id' => 	182	, 'name' => 'Portugal' , 'numcode' => '351'),
            array('id' => 	183	, 'name' => 'Puerto Rico' , 'numcode' => '1 57'),
            array('id' => 	184	, 'name' => 'Qatar' , 'numcode' => '974'),
            array('id' => 	185	, 'name' => 'Reino Unido' , 'numcode' => '44'),
            array('id' => 	186	, 'name' => 'República Centroafricana' , 'numcode' => '236'),
            array('id' => 	187	, 'name' => 'República Checa' , 'numcode' => '420'),
            array('id' => 	188	, 'name' => 'República Democrática Popular Lao' , 'numcode' => '856'),
            array('id' => 	189	, 'name' => 'República Dominicana' , 'numcode' => '829'),
            array('id' => 	190	, 'name' => 'Reunión' , 'numcode' => '262'),
            array('id' => 	191	, 'name' => 'Ruanda' , 'numcode' => '250'),
            array('id' => 	192	, 'name' => 'Rumania' , 'numcode' => '40'),
            array('id' => 	193	, 'name' => 'Sahara Occidental' , 'numcode' => '212 28'),
            array('id' => 	194	, 'name' => 'Samoa Americana' , 'numcode' => '1 684'),
            array('id' => 	195	, 'name' => 'Samoa' , 'numcode' => '685'),
            array('id' => 	196	, 'name' => 'San Bartolomé' , 'numcode' => '590'),
            array('id' => 	197	, 'name' => 'San Cristóbal y Nieves' , 'numcode' => '1 869'),
            array('id' => 	198	, 'name' => 'San Marino' , 'numcode' => '378'),
            array('id' => 	199	, 'name' => 'San Martín (Parte Francesa)' , 'numcode' => '590'),
            array('id' => 	200	, 'name' => 'San Pedro y Miquelón' , 'numcode' => '508'),
            array('id' => 	201	, 'name' => 'San Vicente y Las Granadinas' , 'numcode' => '1 784'),
            array('id' => 	202	, 'name' => 'Santa Helena, Ascensión y Tristán de Acuña' , 'numcode' => '290'),
            array('id' => 	203	, 'name' => 'Santa Lucía' , 'numcode' => '1 758'),
            array('id' => 	204	, 'name' => 'Santa Sede (Ciudad Estado Vaticano)' , 'numcode' => '379'),
            array('id' => 	205	, 'name' => 'Santo Tomé y Principe' , 'numcode' => '239'),
            array('id' => 	206	, 'name' => 'Senegal' , 'numcode' => '221'),
            array('id' => 	207	, 'name' => 'Serbia' , 'numcode' => '381'),
            array('id' => 	208	, 'name' => 'Seychelles' , 'numcode' => '248'),
            array('id' => 	209	, 'name' => 'Sierra Leona' , 'numcode' => '232'),
            array('id' => 	210	, 'name' => 'Singapur' , 'numcode' => '65'),
            array('id' => 	211	, 'name' => 'Sint Maarten (Parte Neerlandesa)' , 'numcode' => '1 721'),
            array('id' => 	212	, 'name' => 'Siria, República Arabe de' , 'numcode' => '963'),
            array('id' => 	213	, 'name' => 'Somalia' , 'numcode' => '252'),
            array('id' => 	214	, 'name' => 'Sri Lanka' , 'numcode' => '94'),
            array('id' => 	215	, 'name' => 'Suazilandia' , 'numcode' => '268'),
            array('id' => 	216	, 'name' => 'Sudáfrica' , 'numcode' => '27'),
            array('id' => 	217	, 'name' => 'Sudán del Sur' , 'numcode' => '211'),
            array('id' => 	218	, 'name' => 'Sudán' , 'numcode' => '249'),
            array('id' => 	219	, 'name' => 'Suecia' , 'numcode' => '46'),
            array('id' => 	220	, 'name' => 'Suiza' , 'numcode' => '41'),
            array('id' => 	221	, 'name' => 'Surinam' , 'numcode' => '597'),
            array('id' => 	222	, 'name' => 'Svalbard y Jan Mayen' , 'numcode' => '47'),
            array('id' => 	223	, 'name' => 'Tailandia' , 'numcode' => '66'),
            array('id' => 	224	, 'name' => 'Taiwán, Provincia de China' , 'numcode' => '886'),
            array('id' => 	225	, 'name' => 'Tanzania, República Unida de' , 'numcode' => '255'),
            array('id' => 	226	, 'name' => 'Tayikistán' , 'numcode' => '992'),
            array('id' => 	227	, 'name' => 'Territorio Británico del Océano Índico' , 'numcode' => '246'),
            array('id' => 	228	, 'name' => 'Territorios Australes Franceses' , 'numcode' => '-'),
            array('id' => 	229	, 'name' => 'Timor-Leste' , 'numcode' => '670'),
            array('id' => 	230	, 'name' => 'Togo' , 'numcode' => '228'),
            array('id' => 	231	, 'name' => 'Tokelau' , 'numcode' => '690'),
            array('id' => 	232	, 'name' => 'Tonga' , 'numcode' => '676'),
            array('id' => 	233	, 'name' => 'Trinidad y Tobago' , 'numcode' => '1 868'),
            array('id' => 	234	, 'name' => 'Túnez' , 'numcode' => '216'),
            array('id' => 	235	, 'name' => 'Turkmenistán' , 'numcode' => '993'),
            array('id' => 	236	, 'name' => 'Turquía' , 'numcode' => '90'),
            array('id' => 	237	, 'name' => 'Tuvalu' , 'numcode' => '688'),
            array('id' => 	238	, 'name' => 'Ucrania' , 'numcode' => '380'),
            array('id' => 	239	, 'name' => 'Uganda' , 'numcode' => '256'),
            array('id' => 	240	, 'name' => 'Uruguay' , 'numcode' => '598'),
            array('id' => 	241	, 'name' => 'Uzbekistán' , 'numcode' => '998'),
            array('id' => 	242	, 'name' => 'Vanuatu' , 'numcode' => '678'),
            array('id' => 	243	, 'name' => 'Venezuela, República Bolivariana de' , 'numcode' => '58'),
            array('id' => 	244	, 'name' => 'Viet Nam' , 'numcode' => '84'),
            array('id' => 	245	, 'name' => 'Wallis y Futuna' , 'numcode' => '681'),
            array('id' => 	246	, 'name' => 'Yemen' , 'numcode' => '967'),
            array('id' => 	247	, 'name' => 'Yibuti' , 'numcode' => '253'),
            array('id' => 	248	, 'name' => 'Zambia' , 'numcode' => '260'),
            array('id' => 	249	, 'name' => 'Zimbabue' , 'numcode' => '263'),





        );

        foreach ($countrys as $country) {
            Country::create($country);
        }
    }
}
