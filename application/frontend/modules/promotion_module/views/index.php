<div class="col-md-12">
	<div class="box box-solid box-success">
		<div class="box-header with-border">
			<h3 class="box-title">Danh sách chương trình</h3>
		</div><!-- /.box-header -->
		<div class="box-body tab-content no-padding">

			<table class="table table-bordered table-striped no-margin">
				<tr>
					<th class="text-center">No</th>
					<th class="text-left">Chương trình</th>
					<th class="text-center">Thời gian</th>
					<th class="text-center">User</th>
					<th class="text-center">Doanh thu</th>
					<th class="text-center">Chức năng</th>
				</tr>
				<?php foreach ($list_promotion as $key => $value) : ?>
				<tr>
					<td class="text-center"><?php echo ($key+1); ?></td>
					<td><?php echo $value['PromotionName'] ?></td>
					<td class="text-center"><u><?php echo date('Y-m-d H:i', strtotime($value['FromDate']))  . '</u> - <u>' . date('Y-m-d H:i', strtotime($value['ToDate'])) ?></u></td>
					<td class="text-right text-light-blue"><b><?php echo number_format($value['AccountTotal']) ?></b></td>
					<td class="text-right text-green"><b><?php echo number_format($value['RevenueTotal']) ?></b></td>
					<td class="text-center">
						<a class="btn btn-danger btn-xs " href="<?php echo site_url('Promotion/index/' . $gameCode . '_' . $value['PromotionID'] . '?view=1') ?>" ><span class="fa fa-line-chart" aria-hidden="true"></span> Hiệu quả</a>
					</td>
				</tr>
				<?php endforeach; ?>
			</table>

		</div>
	</div>
</div>