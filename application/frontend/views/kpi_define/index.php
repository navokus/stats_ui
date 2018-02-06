<?php
/**
 * Created by IntelliJ IDEA.
 * User: tuonglv
 * Date: 07/06/2016
 * Time: 10:50
 */
?>


<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-header">
				<h3 class="box-title">Game KPI Defination</h3>
				<div class="box-tools">
                	<a class="btn btn-box-tool" href="#" title="Copy to clipboard!"  id="copy">
						<img src="<?php echo base_url('public/frontend/dist/img/copy.png'); ?>" width="22px" height="22px" />
					</a>
                	<a class="btn btn-box-tool" href="#" title="Download excel file!" id="downloadExcel">
						<img src="<?php echo base_url('public/frontend/dist/img/export.gif'); ?>" width="20px" height="20px" />
					</a>
              </div>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
			
				<table id="kpiTable" class="table table-bordered table-striped" >
					<thead>
						<tr>
							<th>KPI</th>
							<th>Description/Formula</th>
							<th>KPI by timimg</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Active User (A)</td>
							<td>Unique User login</td>
							<td>A1: Active users in 1 day.<br>
								A7: Active users in 7 days.<br>
								A30: Active users in 30 days.<br>
								A60: Active users in 60 days.<br>
								A90: Active users in 90 days.<br>
								AM: Active users in 1 calendar month<br>
								</td>
						</tr>
						<tr>
							<td>New Register User (N)</td>
							<td>New Register User</td>
							<td>N1: N in 1 day.<br>
								N7: N in 7 days.<br>
								N30: N in 30 days.<br>
								N60: N in 60 days.<br>
								N90: N in 90 days.<br>
								NM: N in 1 calendar month<br>
								</td>
						</tr>
						<!-- <tr>
							<td>Retention Rate (RR)</td>
							<td>RR is defined as what percentage of the user who played game in day 1 are still playing after N days. <br> N = 1,7,30,60 or 90</td>
							<td>RR1: RR in 1 day.<br>
								RR7: RR in 7 days.<br>
								RR30: RR in 30 days.<br>
								RR60: RR in 60 days.<br>
								RR90: RR in 90 days.<br>
								RRM: RR in 1 calendar month<br>
								</td>
						</tr> -->
						<tr>
							<td>Retention Rate (RR)</td>
							<td>RR is defined as what percentage of the user who NEW registered & played game in N days before are still playing on selected day. 
															<br>
								Example: If a game has 100 <strong>NEW users</strong> on Monday and on Tuesday 44 users of <strong>100 NEW users</strong> return to play again, we have a 1-day retention rate of (44/100) = 0.44 or 44%.
							</td>
							<td>RR1: RR in 1 day.<br>
								RR7: RR in 7 days.<br>
								RR30: RR in 30 days.<br>
								RR60: RR in 60 days.<br>
								RR90: RR in 90 days.<br>
								RRM: RR in 1 calendar month<br>
								</td>
						</tr>
						<!-- <tr>
							<td>New User Retention Rate (NRR)</td>
							<td>NRR is defined as what percentage of the user who NEW registered & played game in day 1 are still playing after N days. <br> N = 1,7,30,60 or 90</td>
							<td>NRR1: NRR in 1 day.<br>
								NRR7: NRR in 7 days.<br>
								NRR30: NRR in 30 days.<br>
								NRR60: NRR in 60 days.<br>
								NRR90: NRR in 90 days.<br>
								NRRM: NRR in 1 calendar month<br>
								</td>
						</tr> -->
						<tr>
							<td>Churn Rate (CR)</td>
							<td>
							<br>
								Example: If a game has 100 <strong> active users</strong> on Monday and on Tuesday 40 users of  <strong> 100 active users</strong> return to play again, we have a 1-day churn rate of ((100-40)/100) = 0.60 or 60%.
							</td>
							<td>CR1: CR in 1 day.<br>
								CR7: CR in 7 days.<br>
								CR30: CR in 30 days.<br>
								CR60: CR in 60 days.<br>
								CR90: CR in 90 days.<br>
								CRM: CR in 1 calendar month<br>
								</td>
						</tr>
						
						<tr>
							<td>Paying User (PU)</td>
							<td>Number of users who are charging in N days</td>
							<td>PU1: PU in 1 day.<br>
								PU7: PU in 7 days.<br>
								PU30: PU in 30 days.<br>
								PU60: PU in 60 days.<br>
								PU90: PU in 90 days.<br>
								PUM: CR in 1 calendar month<br>
								</td>
						</tr>
						<tr>
							<td>Revenue (RV)</td>
							<td>Total Revenue in N days</td>
							<td>RV1: RV in 1 day.<br>
								RV7: RV in 7 days.<br>
								RV30: RV in 30 days.<br>
								RV60: RV in 60 days.<br>
								RV90: RV in 90 days.<br>
								RVM: RV in 1 calendar month<br>
								</td>
						</tr>
						
						<tr>
							<td>Average Revenue Per Paying User (ARPPU)</td>
							<td>ARPPU = RV / PU</td>
							<td>ARPPU1: ARPPU in 1 day. ARPPU1 = RV1 / PU1<br>
								ARPPU7: ARPPU in 7 days.<br>
								ARPPU30: ARPPU in 30 days.<br>
								ARPPU60: ARPPU in 60 days.<br>
								ARPPU90: ARPPU in 90 days.<br>
								ARPPUM: ARPPU in 1 calendar month<br>
								</td>
						</tr>
						
						<tr>
							<td>Average Revenue Per User (ARPU)</td>
							<td>ARPU = RV / A<br>
							<td>ARPU1: ARPU in 1 day. ARPU1 = RV1 / A1<br>
								ARPU7: ARPU in 7 days.<br>
								ARPU30: ARPU in 30 days.<br>
								ARPU60: ARPU in 60 days.<br>
								ARPU90: ARPU in 90 days.<br>
								ARPUM: ARPU in 1 calendar month<br>
								</td>
						</tr>
						<tr>
							<td>First Charge (NPU) </td>
							<td>Number of users who is the first charge in N days.</td>
							<td>NPU1: NPU in 1 day.<br>
								NPU7: NPU in 7 days.<br>
								NPU30: NPU in 30 days.<br>
								NPU60: NPU in 60 days.<br>
								NPU90: NPU in 90 days.<br>
								NPUM: NPU in 1 calendar month<br>
								</td>
						</tr>
						
						<tr>
							<td>Revenue of First Charge (NPU_RV) </td>
							<td>Revenue of First Charge</td>
							<td>NPU_RV1: NPU_RV in 1 day.<br>
								NPU_RV7: NPU_RV in 7 days.<br>
								NPU_RV30: NPU_RV in 30 days.<br>
								NPU_RV60: NPU_RV in 60 days.<br>
								NPU_RV90: NPU_RV in 90 days.<br>
								NPU_RVM: NPU_RV in 1 calendar month<br>
								</td>
						</tr>
						
						<tr>
							<td>Revenue of New  User (NNPU_RV) </td>
							<td>Revenue of New  User</td>
							<td>NNPU_RV1: NNPU in 1 day. <br>
								NNPU_RV7: NNPU in 7 days.<br>
								NNPU_RV30: NNPU in 30 days.<br>
								NNPU_RV60: NNPU in 60 days.<br>
								NNPU_RV90: NNPU in 90 days.<br>
								NNPU_RVM: NNPU in 1 calendar month<br>
							</td>
						</tr>
						
						<tr>
							<td>Number of New  User & New Paying (NNPU) </td>
							<td>New Login/Register user who charged money to game </td>
							<td>NNPU1: NNPU in 1 day<br>
								NNPU7: NNPU in 7 days.<br>
								NNPU30: NNPU in 30 days.<br>
								NNPU60: NNPU in 60 days.<br>
								NNPU90: NNPU in 90 days.<br>
								NNPUM: NNPU in 1 calendar month<br>
							</td>
						</tr>
						<tr>
							<td>PU Retention</td>
							<td>PU Retention is defined as what percentage of the user who NEW Paying User (First Charge) in N days before are still paying on selected day. <br> N = 1,7,30,60 or 90 <br>
							 Example: If a game has 100 <strong>NEW paying users</strong> on Monday and on Tuesday 44 users of <strong>100 NEW paying users</strong> return to pay again, we have a 1-day PU retention rate of (44/100) = 0.44 or 44%.
								
							</td>
							<td>PU Retention 1 day<br>
								PU Retention 7 days.<br>
								PU Retention 30 days.<br>
								PU Retention 60 days.<br>
								PU Retention 90 days.<br>
							</td>
						</tr>
						<tr>
							<td>Re-Paying Users</td>
							 <td>Example: If a game has 100 <strong>paying users</strong> on Monday and on Tuesday 44 users of <strong>100 paying users</strong> return to pay again, we have a 1-day Re-Paying Users rate of (44/100) = 0.44 or 44%.
							</td>
							<td>PU Retention 1 day<br>
								PU Retention 7 days.<br>
								PU Retention 30 days.<br>
								PU Retention 60 days.<br>
								PU Retention 90 days.<br>
							</td>
						</tr>
						<tr>
							<td>Conversion Rate (CVR) </td>
							<td>CVR = PU/A </td>
							<td>CVR1: CVR in 1 day<br>
								CVR7: CVR in 7 days.<br>
								CVR30: CVR in 30 days.<br>
								CVR60: CVR in 60 days.<br>
								CVR90: CVR in 90 days.<br>
								CVRM: CVR in 1 calendar month<br>
							</td>
						</tr>
						<tr>
							<td>ACU/PCU</td>
							<td>Average CCU/ Peak CCU</td>
							<td>Average CCU/ Peak CCU
							</td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<th>KPI</th>
							<th>Description/Formula</th>
							<th>KPI by timimg</th>
						</tr>
					</tfoot>
				</table>
			</div>
			<!-- /.box-body -->
		</div>
		<!-- /.box -->
	</div>
</div>
<script>
      $(function () {
        $('#kpiTable').DataTable({
        	paging: false,
          searching: true,
          ordering: false,
          info: true,
          responsive: true,
          dom: 'Bfrtip',
          buttons: [
                    'copy',
                    'excel'
                ]
        });
      });
    </script>