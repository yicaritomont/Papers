<?php

use Illuminate\Database\Seeder;
use App\Preformato;

class PreformatoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function run()
    {
        $preformatos = array(
          array('id' => 1,
            'inspection_subtype_id' => 1,
            'name' => 'expediting & inspection',
           'format' => '<table border="1" cellpadding="1" cellspacing="1" style="width:100%">
	<tbody>
		<tr>
			<td colspan="2" rowspan="2" style="text-align:center">*company_logo*</td>
			<td colspan="4" rowspan="1" style="text-align:center"><strong>EXPEDITING &amp; INSPECTION</strong></td>
			<td colspan="2" rowspan="2" style="text-align:center">*iso_logo*</td>
		</tr>
		<tr>
			<td colspan="4" rowspan="1" style="text-align:center"><strong>INSPECTION REPORT</strong></td>
		</tr>
		<tr>
			<td><strong>CLIENT :</strong></td>
			<td>*client*</td>
			<td><strong>PROJECT :</strong></td>
			<td>*project*</td>
			<td><strong>N. PAGE:</strong></td>
			<td>*num_page*</td>
			<td><strong>OF</strong></td>
			<td>*tot_pages*</td>
		</tr>
		<tr>
			<td><strong>CONTRACT :</strong></td>
			<td>*contract*</td>
			<td colspan="2" rowspan="1"><strong>DATE OF CONTRACT :</strong></td>
			<td>*date_contract*</td>
			<td colspan="2" rowspan="1"><strong>CONTRACTUAL DELIVERY DATE</strong></td>
			<td>*date_contractual*</td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<table border="1" cellpadding="1" cellspacing="1" style="width:100%">
	<tbody>
		<tr>
			<td style="width:30%"><strong>CONTACT :</strong></td>
			<td style="width:70%"><input style="width:100%" type="text" /></td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<p>&nbsp;</p>

<div style="background:#072360; border:1px solid #ffffff"><span style="color:#ffffff">&nbsp;1 - REFERENCES AND SPECIFICATIONS</span></div>

<p>Contract&nbsp; *contract* between&nbsp; *company*&nbsp;&nbsp;</p>

<div style="background:#072360; border:1px solid #cccccc"><span style="color:#ffffff">&nbsp;2 - INSPECTION COPE</span></div>

<p>&nbsp;</p>

<table border="1" cellpadding="1" cellspacing="1" style="width:100%">
	<tbody>
		<tr>
			<td style="width:30%"><strong>TESTED EQUIPMENTS :</strong></td>
			<th style="width:70%"><input name="tested_equipments" style="width:100%" type="text" /></th>
		</tr>
	</tbody>
</table>

<p>&nbsp;<input name="raw_material" type="checkbox" value="raw_material" /><strong>Raw material reception :</strong></p>

<p><strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<input name="dimensional_test" type="checkbox" value="dimensional_test" /></strong> Dimensional test&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<input name="visual_inspection" type="checkbox" value="visual_inspection" />&nbsp;Visual inspection</p>

<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<input name="reviewing_certificates" type="checkbox" value="reviewing_certificates" />&nbsp;Reviewing certificates</p>

<p><input name="welders_homologation" type="checkbox" value="welders_homologation" />&nbsp;<strong>Welders homologatiom&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<input name="weldings_homologation" type="checkbox" value="weldings_homologation" />&nbsp;Weldings homologation</strong></p>

<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<input name="magnetic_particles" type="checkbox" value="magnetic_particles" />&nbsp;Magnetic particles&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<input name="penetrating_liquids" type="checkbox" value="penetrating_liquids" />&nbsp;Penetrating liquids</p>

<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<input name="x_rays" type="checkbox" value="x_rays" />&nbsp;X - rays&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;<input name="ut" type="checkbox" value="ut" />&nbsp;UT</p>

<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<input name="visual_inspection" type="checkbox" value="visual_inspection" />&nbsp;Visual inspection</p>

<p><input name="tests_essays" type="checkbox" value="test_essays" />&nbsp;<strong>Tests and essays</strong></p>

<p><strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<input name="visual_inspection2" type="checkbox" value="visual_inspection2" />&nbsp;</strong>Visual inspection&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<input name="dimensional_control" type="checkbox" value="dimensional_control" />&nbsp;Dimensional control</p>

<div style="background:#072360; border:1px solid #ffffff"><span style="color:#ffffff">&nbsp;3 - INSPECTION RESULT : </span></div>

<p><input name="satisfactory" type="radio" value="satisfactory" />&nbsp;<strong>Satisfactory&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <input name="satisfactory" type="radio" value="non_satisfactory" /> Non satisfactory</strong>&nbsp;</p>

<table border="1" cellpadding="1" cellspacing="1" style="width:100%">
	<tbody>
		<tr>
			<td><strong>Description and results</strong></td>
		</tr>
	</tbody>
</table>

<p>THe purpose of the visit is to control that everything is according with the last manufacturing schedule given by CMD and to be present in the running test</p>

<p>&nbsp;</p>

<p>The current status of the manufacturing process is, according to the following tables:</p>

<p><strong><u>Note :</u></strong>&nbsp;The internal control number at the workshop of the order purchase is *contract*</p>

<p>_The process for all the&nbsp;<strong><u>H.S.&nbsp; pinion</u></strong>&nbsp; will be:</p>

<p>A. Purchase<br />
B. Turning<br />
C. Raw cutting<br />
D. Gas Carburising<br />
E. Finishing Machining<br />
F. Cylindrical Grinding<br />
G. Teeth Grinning<br />
H. Final Control</p>

<table border="1" cellpadding="1" cellspacing="1" dir="ltr" style="width:100%">
	<tbody>
		<tr>
			<td style="width:20%">&nbsp;</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">A</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">B</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">C</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">D</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">E</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">F</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">G</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">H</td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa; width:20%">
			<p style="text-align:center">H. S.</p>

			<p style="text-align:center">Pinion</p>
			</td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
		</tr>
	</tbody>
</table>

<p>_Quality control :</p>

<p>&nbsp;</p>

<table border="1" cellpadding="1" cellspacing="1" style="width:100%">
	<tbody>
		<tr>
			<td style="width:10%">&nbsp;</td>
			<td style="background-color:#cccccc; width:50%">Documents</td>
			<td style="background-color:#cccccc; width:40%">Date</td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">1</td>
			<td><input style="width:100%" type="text" /></td>
			<td><input style="width:100%" type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">2</td>
			<td><input style="width:100%" type="text" /></td>
			<td><input style="width:100%" type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">3</td>
			<td><input style="width:100%" type="text" /></td>
			<td><input style="width:100%" type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">4</td>
			<td><input style="width:100%" type="text" /></td>
			<td><input style="width:100%" type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">5</td>
			<td><input style="width:100%" type="text" /></td>
			<td><input style="width:100%" type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">6</td>
			<td><input style="width:100%" type="text" /></td>
			<td><input style="width:100%" type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">7</td>
			<td><input style="width:100%" type="text" /></td>
			<td><input style="width:100%" type="text" /></td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<p><strong>2 .&nbsp;<u>L.S pinions/M.S. Wheels (Right &amp; Left side)</u></strong></p>

<p>_The process for all the&nbsp;<strong><u>L.S pinion (Right &amp; left side)</u></strong>&nbsp;will be :</p>

<p>A. Purchase<br />
B. Turning<br />
C. Raw cutting<br />
D. Gas Carburising<br />
E. Finishing Machining<br />
F. Cylindrical Grinding<br />
G. Teeth Grinning<br />
H. Final Control</p>

<p>&nbsp;</p>

<table border="1" cellpadding="1" cellspacing="1" dir="ltr" style="width:100%">
	<tbody>
		<tr>
			<td style="width:20%">&nbsp;</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">A</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">B</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">C</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">D</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">E</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">F</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">G</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">H</td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa; width:20%">
			<p style="text-align:center">L. S.</p>

			<p style="text-align:center">Pinion</p>
			</td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
		</tr>
	</tbody>
</table>

<p>_Quality control :&nbsp;</p>

<table border="1" cellpadding="1" cellspacing="1" style="width:100%">
	<tbody>
		<tr>
			<td style="width:10%">&nbsp;</td>
			<td style="background-color:#cccccc; width:50%">Documents</td>
			<td style="background-color:#cccccc; width:40%">Date</td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">1</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">2</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">3</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">4</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">5</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<p>_The process for all the&nbsp;<strong><u>M.S. wheels (Right &amp; left side)</u></strong>&nbsp;will be :</p>

<p>A. Purchase<br />
B. Turning<br />
C. Raw cutting<br />
D. Gas Carburising<br />
E. Finishing Machining<br />
F. Cylindrical Grinding<br />
G. Teeth Grinning<br />
H. Final Control</p>

<p>&nbsp;</p>

<table border="1" cellpadding="1" cellspacing="1" dir="ltr" style="width:100%">
	<tbody>
		<tr>
			<td style="width:20%">&nbsp;</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">A</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">B</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">C</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">D</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">E</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">F</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">G</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">H</td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa; width:20%">
			<p style="text-align:center">L. S.</p>

			<p style="text-align:center">Pinion</p>
			</td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
		</tr>
	</tbody>
</table>

<p>_Quality control :</p>

<table border="1" cellpadding="1" cellspacing="1" style="width:100%">
	<tbody>
		<tr>
			<td style="width:10%">&nbsp;</td>
			<td style="background-color:#cccccc; width:50%">Documents</td>
			<td style="background-color:#cccccc; width:40%">Date</td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">1</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">2</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">3</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">4</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">5</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<p><strong>3.&nbsp;<u>M.S. Pinions (assembly H.S Wheels)</u></strong></p>

<p>_The Process fir all the&nbsp;<strong><u>M.S. Pinion (Lef side)&nbsp;</u></strong>will be:</p>

<p>&nbsp;</p>

<p>A.&nbsp; &nbsp;Purchase<br />
B.&nbsp; &nbsp;Turning<br />
C.&nbsp; &nbsp;Raw cutting<br />
D.&nbsp; &nbsp;Gas Carbusing<br />
E.&nbsp; &nbsp;Finishing Machining<br />
F.&nbsp; &nbsp;Cylindrical Grinding<br />
G.&nbsp; &nbsp;Teeth Grinding<br />
H.&nbsp; &nbsp;Final Control</p>

<table border="1" cellpadding="1" cellspacing="1" style="width:100%">
	<tbody>
		<tr>
			<td style="width:20%">&nbsp;</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">A</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">B</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">C</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">D</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">E</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">F</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">G</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">H</td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa; width:20%">
			<p>M.S Pinion<br />
			Left Side</p>
			</td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<p>_Quality control</p>

<table border="1" cellpadding="1" cellspacing="1" style="width:100%">
	<tbody>
		<tr>
			<td style="width:10%">&nbsp;</td>
			<td style="background-color:#cccccc; width:50%">Documents</td>
			<td style="background-color:#cccccc; width:40%">Date</td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">1</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">2</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">3</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">4</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<p>The process for all the <strong><u>M.S pinion (Right side)</u></strong>&nbsp;will be:</p>

<p>A. Purchase<br />
B.&nbsp; Turning<br />
C.&nbsp; Raw cutting<br />
D.&nbsp; Gas Carburising<br />
E.&nbsp; Finishing Machining<br />
F.&nbsp; Cylindrical Grinding<br />
G.&nbsp; Teeth Grining<br />
H.&nbsp; Final Control</p>

<table border="1" cellpadding="1" cellspacing="1" style="width:100%">
	<tbody>
		<tr>
			<td style="width:20%">&nbsp;</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">A</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">B</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">C</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">D</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">E</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">F</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">G</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">H</td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa; width:20%">
			<p>M.S Pinion Right Side</p>
			</td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<p>_Quality control</p>

<table border="1" cellpadding="1" cellspacing="1" style="width:100%">
	<tbody>
		<tr>
			<td style="width:10%">&nbsp;</td>
			<td style="background-color:#cccccc; width:50%">Documents</td>
			<td style="background-color:#cccccc; width:40%">Date</td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">1</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">2</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">3</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">4</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
	</tbody>
</table>

<p>&nbsp;<strong>4.&nbsp;<u>H.S. Wheels (assembly M.S Pinions)</u></strong></p>

<p>_The process for all the&nbsp;<strong><u>H.S Wheel (left side)</u></strong>&nbsp;will be:</p>

<p>A.&nbsp; Purchase<br />
B.&nbsp; Raw cutting<br />
C.&nbsp; Gas Carburising<br />
D.&nbsp; Finishing Machining<br />
E.&nbsp; MS Pinion Assembly<br />
F.&nbsp; &nbsp;Balancing<br />
G.&nbsp; Final Control<br />
&nbsp;</p>

<table border="1" cellpadding="1" cellspacing="1" style="width:100%">
	<tbody>
		<tr>
			<td style="width:20%">&nbsp;</td>
			<td style="background-color:#cccccc; text-align:center; width:12%">A</td>
			<td style="background-color:#cccccc; text-align:center; width:11%">B</td>
			<td style="background-color:#cccccc; text-align:center; width:11%">C</td>
			<td style="background-color:#cccccc; text-align:center; width:12%">D</td>
			<td style="background-color:#cccccc; text-align:center; width:11%">E</td>
			<td style="background-color:#cccccc; text-align:center; width:11%">F</td>
			<td style="background-color:#cccccc; text-align:center; width:12%">G</td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa; width:20%">
			<p>H.S. Wheels</p>

			<p>Left Side</p>
			</td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<p>_Quality control</p>

<table border="1" cellpadding="1" cellspacing="1" style="width:100%">
	<tbody>
		<tr>
			<td style="width:10%">&nbsp;</td>
			<td style="background-color:#cccccc; width:50%">Documents</td>
			<td style="background-color:#cccccc; width:40%">Date</td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">1</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">2</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">3</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">4</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<p>_The process for all the&nbsp;<strong><u>H.S. Wheel (Right side)</u></strong>&nbsp;will be:</p>

<p>A.&nbsp; Purchase<br />
B.&nbsp; Raw cutting<br />
C.&nbsp; Gas Carburising<br />
D.&nbsp; Finishing Machinin<br />
E.&nbsp; MS Pinion Assembly<br />
F.&nbsp; &nbsp;Balancing<br />
G.&nbsp; Final Control<br />
&nbsp;</p>

<table border="1" cellpadding="1" cellspacing="1" style="width:100%">
	<tbody>
		<tr>
			<td style="width:20%">&nbsp;</td>
			<td style="background-color:#cccccc; text-align:center; width:12%">A</td>
			<td style="background-color:#cccccc; text-align:center; width:11%">B</td>
			<td style="background-color:#cccccc; text-align:center; width:11%">C</td>
			<td style="background-color:#cccccc; text-align:center; width:12%">D</td>
			<td style="background-color:#cccccc; text-align:center; width:11%">E</td>
			<td style="background-color:#cccccc; text-align:center; width:11%">F</td>
			<td style="background-color:#cccccc; text-align:center; width:12%">G</td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa; width:20%">
			<p>H.S. Wheels</p>

			<p>Right Side</p>
			</td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<p>_Quality control</p>

<table border="1" cellpadding="1" cellspacing="1" style="width:100%">
	<tbody>
		<tr>
			<td style="width:10%">&nbsp;</td>
			<td style="background-color:#cccccc; width:50%">Documents</td>
			<td style="background-color:#cccccc; width:40%">Date</td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">1</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">2</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">3</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">4</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<p><strong>5.&nbsp;<u>Output Shaft + L.S. Wheel</u></strong></p>

<p><strong>_</strong>The Process for all the&nbsp;<strong><u>Ouput Shaft</u></strong>&nbsp; will be:</p>

<p>A.&nbsp; Purchase<br />
B.&nbsp; Turning<br />
C.&nbsp; Cutting<br />
D.&nbsp; Final Control<br />
&nbsp;</p>

<table border="1" cellpadding="1" cellspacing="1" style="width:100%">
	<tbody>
		<tr>
			<td style="width:20%">&nbsp;</td>
			<td style="background-color:#cccccc; text-align:center; width:12%">A</td>
			<td style="background-color:#cccccc; text-align:center; width:11%">B</td>
			<td style="background-color:#cccccc; text-align:center; width:11%">C</td>
			<td style="background-color:#cccccc; text-align:center; width:12%">D</td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa; width:20%">
			<p>OUTPUT<br />
			SHAFT</p>
			</td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<p>_The process for all the&nbsp;<u>&nbsp;<strong>L.S. Wheel</strong></u>&nbsp; will be:</p>

<p>A.&nbsp; Purchase<br />
B.&nbsp; Raw cutting<br />
C.&nbsp; Gas Carburising<br />
D.&nbsp; Finishing Turning<br />
E.&nbsp; Output Shaft Assembly<br />
F.&nbsp; Boring<br />
G.&nbsp; Teeth Grinding<br />
H.&nbsp; Final Control<br />
&nbsp;</p>

<table border="1" cellpadding="1" cellspacing="1" dir="ltr" style="width:100%">
	<tbody>
		<tr>
			<td style="width:20%">&nbsp;</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">A</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">B</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">C</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">D</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">E</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">F</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">G</td>
			<td style="background-color:#cccccc; text-align:center; width:10%">H</td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa; width:20%">
			<p style="text-align:center">L. S.</p>

			<p style="text-align:center">Wheel</p>
			</td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<p>_Quality control&nbsp;&nbsp;<strong><u>Output Shaft + L.S. Wheel</u></strong></p>

<p>&nbsp;</p>

<table border="1" cellpadding="1" cellspacing="1" style="width:100%">
	<tbody>
		<tr>
			<td style="width:10%">&nbsp;</td>
			<td style="background-color:#cccccc; width:50%">Documents</td>
			<td style="background-color:#cccccc; width:40%">Date</td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">1</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">2</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">3</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">4</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">5</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">6</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">7</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">8</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">9</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">10</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa">11</td>
			<td><input type="text" /></td>
			<td><input type="text" /></td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<p><strong>6.&nbsp;<u>Gears&nbsp; Cases</u></strong></p>

<p>_The process for all the&nbsp;<strong><u>Gears&nbsp; Cases</u></strong>&nbsp; will be:</p>

<p>A.&nbsp; Purchase<br />
B.&nbsp; Machining<br />
C.&nbsp; Cutting<br />
D.&nbsp; Control<br />
E. Gears Preparation (Assembly)<br />
F.&nbsp; Piping (Assembly)<br />
G. Gears Assembly (Assembly)<br />
&nbsp;</p>

<table border="1" cellpadding="1" cellspacing="1" dir="ltr" style="width:100%">
	<tbody>
		<tr>
			<td style="width:20%">&nbsp;</td>
			<td style="background-color:#cccccc; text-align:center; width:12%">A</td>
			<td style="background-color:#cccccc; text-align:center; width:11%">B</td>
			<td style="background-color:#cccccc; text-align:center; width:11%">C</td>
			<td style="background-color:#cccccc; text-align:center; width:12%">D</td>
			<td style="background-color:#cccccc; text-align:center; width:11%">E</td>
			<td style="background-color:#cccccc; text-align:center; width:11%">F</td>
			<td style="background-color:#cccccc; text-align:center; width:12%">G</td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa; width:20%">
			<p style="text-align:center">Gears</p>

			<p style="text-align:center">Cases</p>
			</td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<p><strong>8.&nbsp;<u>&nbsp;Assembly</u></strong></p>

<p>_The process for all the&nbsp;<strong><u>Assembly</u></strong>&nbsp; will be:</p>

<p>A.&nbsp; Gears Preparation<br />
B.&nbsp; Piping<br />
C.&nbsp; Gears Assembly<br />
D.&nbsp; Teeth contact setting<br />
E. Final Assembly<br />
F. Running Test inspection</p>

<table border="1" cellpadding="1" cellspacing="1" dir="ltr" style="width:100%">
	<tbody>
		<tr>
			<td style="width:20%">&nbsp;</td>
			<td style="background-color:#cccccc; text-align:center; width:13%">A</td>
			<td style="background-color:#cccccc; text-align:center; width:14%">B</td>
			<td style="background-color:#cccccc; text-align:center; width:13%">C</td>
			<td style="background-color:#cccccc; text-align:center; width:13%">D</td>
			<td style="background-color:#cccccc; text-align:center; width:14%">E</td>
			<td style="background-color:#cccccc; text-align:center; width:13%">F</td>
		</tr>
		<tr>
			<td style="background-color:#aaaaaa; width:20%">
			<p style="text-align:center">Assembly</p>
			</td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<p><strong>9.&nbsp;&nbsp;<u>Running Test</u></strong>&nbsp;</p>

<p>During the last visit, Cemengal and the client were in the running test. Basically all the dates were notificated by CMD and it was not possible to open the gear cases because the running test was working during the visit.</p>

<p>CMD informed to Cemengal that to obtain an intermediate date it will be necessary to ask CMD and they will provide it.</p>

<p>The running tet was working since april, the 30, 2015 and will be finished until may, the 08, 2015 approximately.</p>

<div style="background:#072360; border:1px solid #cccccc"><span style="color:#ffffff">4 - NON SATISFACTORY RESULTS:</span></div>

<table border="1" cellpadding="1" cellspacing="1" style="width:100%">
	<tbody>
		<tr>
			<td>Description and results</td>
		</tr>
	</tbody>
</table>

<table border="1" cellpadding="1" cellspacing="1" style="width:100%">
	<tbody>
		<tr>
			<td><textarea cols="80" rows="10"></textarea></td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<div style="background:#072360; border:1px solid #cccccc"><span style="color:#ffffff">5 - CORRECTIVE:</span></div>

<table border="1" cellpadding="1" cellspacing="1" style="width:100%">
	<tbody>
		<tr>
			<td style="width:50%"><strong>Detail of the corrective terms to apply:</strong></td>
			<td style="width:25%"><strong>Beginning ate</strong></td>
			<td style="width:25%"><strong>Endinng date</strong></td>
		</tr>
		<tr>
			<td colspan="3">
			<ul>
				<li>&nbsp;&nbsp;<strong>Supplier :</strong></li>
			</ul>
			</td>
		</tr>
		<tr>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
		</tr>
		<tr>
			<td colspan="3">
			<ul>
				<li>&nbsp; &nbsp;<strong>CEMENGAL</strong></li>
			</ul>
			</td>
		</tr>
		<tr>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
			<td><textarea></textarea></td>
		</tr>
	</tbody>
</table>
<p>&nbsp;</p>',
'state' => 1),

);

          foreach ($preformatos as $preformato) {
            Preformato::create($preformato);
          }
    }
}
