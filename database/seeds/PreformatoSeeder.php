<?php

use Illuminate\Database\Seeder;

class PreformatoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $preformatos = array(
        {
          array('id' => 1,
            'inspection_subtype_id' => 1,
            'name' => 'expediting & inspection',
           'preformato' => '<table border="1" cellpadding="1" cellspacing="1" style="width:100%">
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
			<td style="width:70%"><input name="*contact*" style="width:100%" type="text" /></td>
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
</table>', 'state' => '1'),
          });

          foreach ($preformatos as $preformato) {
            Preformato::create($preformato);
          }
    }
}
