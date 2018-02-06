$(function () {
	
    $.fn.dataTableExt.sErrMode = 'throw';

    // config calendar compare day
    var nowTemp = new Date();
    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

    var checkin = $('#dpd1').datepicker({
        format: 'dd/mm/yyyy',
        onRender: function (date) {
            return date.valueOf() > now.valueOf() ? 'disabled' : '';
        }
    }).on('changeDate', function (ev) {

        if (ev.date.valueOf() < checkout.date.valueOf()) {
            var newDate = new Date(ev.date)
            newDate.setDate(newDate.getDate() - 1);
            checkout.setValue(newDate);
        } else {
            checkout.setValue(checkout.date.valueOf());
        }
        checkin.hide();
        $('#dpd2')[0].focus();
    }).data('datepicker');

    var checkout = $('#dpd2').datepicker({
        format: 'dd/mm/yyyy',
        onRender: function (date) {
            return date.valueOf() > checkin.date.valueOf() - 1 ? 'disabled' : '';
        }
    }).on('changeDate', function (ev) {
        checkout.hide();
    }).data('datepicker');

    // end calendar compare day

    // config select box week
    $('#wpw1').change(function () {
        var curWeek = new Date($(this).val());
        var i = 0;

        var valuePreWeek = $("#wpw2").val();
        $("#wpw2 > option").each(function () {
            var preWeek = new Date(this.value);
            if (preWeek >= curWeek) {
                $(this).css('display', 'none');
                $(this).removeAttr('selected');
            } else {
                if (i == 0) {
                    var datePreWeek = new Date(valuePreWeek);
                    if (datePreWeek >= curWeek) {
                        $(this).attr('selected', 'selected');
                    }
                }
                $(this).css('display', 'block');
                i++;
            }
        });
    });
    $('#wpw1').change();
    // end select box week

    // config select box month
    $('#mpm1').change(function () {
        var curMonth = new Date($(this).val());
        var i = 0;

        var valuePreMonth = $("#mpm2").val();
        var equal = $("#mpm2").hasClass('equal');
        $("#mpm2 > option").each(function () {
            var preMonth = new Date(this.value);

            if (equal) {
                if (preMonth > curMonth) {
                    $(this).css('display', 'none');
                    $(this).removeAttr('selected');
                } else {
                    if (i == 0) {
                        var datePreMonth = new Date(valuePreMonth);
                        if (datePreMonth >= curMonth) {
                            $(this).attr('selected', 'selected');
                        }
                    }
                    $(this).css('display', 'block');
                    i++;
                }
            } else {
                if (preMonth >= curMonth) {
                    $(this).css('display', 'none');
                    $(this).removeAttr('selected');
                } else {
                    if (i == 0) {
                        var datePreMonth = new Date(valuePreMonth);
                        if (datePreMonth >= curMonth) {
                            $(this).attr('selected', 'selected');
                        }
                    }
                    $(this).css('display', 'block');
                    i++;
                }
            }
        });
    });
    $('#mpm1').change();
    // end config select box month

    $('#datepicker').daterangepicker({
        showWeekNumbers: true,
        singleDatePicker: true,
        format: 'YYYY-MM-DD'
    });

    $('#daypicker').daterangepicker({
        showWeekNumbers: true,
        singleDatePicker: true,
        format: 'DD/MM/YYYY'
    });


    // page Promotion Compare
    $('#promo-game').click(function () {
        var code = $(this).val();
        $.get(url + "/Promotion/listPromotionByGame/" + code, function (data) {
            $('#promo-promotion').html(data);
            console.log("done");
        })
    });


    $('[data-toggle="popover"]').popover({
        html: true,
        template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title text-center"></h3><div class="popover-content no-padding"></div></div>'
    });
    $('[data-widget="collapse"]').click(function () {
        $(window).resize();
    });

    $('div[id^="modal_"]').on('shown.bs.modal', function (e) {
        $(window).resize();
    });

    $('#content .collapsed-box:eq(0) .fa-plus').removeClass('fa-plus').addClass('fa-minus');
    $('#content .collapsed-box:eq(0)').removeClass('collapsed-box');

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

        // table compare game in page dashboard
        if (e.currentTarget.hash == '#compare_allGame' && $('#table-compare-all-game').length) {
            if (!$.fn.dataTable.isDataTable('#table-compare-all-game')) {
                // table compare all game
                var table_compare_all_game = $('#table-compare-all-game').DataTable({
                    paging: false,
                    searching: false,
                    pageLength: 1000,
                    ordering: false,
                    info: false,
                    order: [],
                    scrollX: true,
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel'
                    ]
                });
                new $.fn.dataTable.FixedColumns(table_compare_all_game, {
                    //heightMatch: 'none',
                    leftColumns: 2
                });
            }
        }

        // tab retention revenue
        if (e.currentTarget.hash == '#tab_2-2' && $('#table-revenue').length) {

            if (!$.fn.dataTable.isDataTable('#table-revenue')) {
                // table revenue
                var table_revenue = $('#table-revenue').DataTable({
                    responsive: true,
                    paging: false,
                    searching: false,
                    pageLength: 1000,
                    ordering: false,
                    order: [],
                    info: false,
                    //scrollY: false,
                    scrollX: true,
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel'
                    ]
                });
                new $.fn.dataTable.FixedColumns(table_revenue, {
                    heightMatch: 'none',
                    leftColumns: 1
                });
            }
        }


        $(window).resize();
    });

    // table retention revenue in page retention
    if ($('#table-revenue').length) {
        if (!$.fn.dataTable.isDataTable('#table-revenue')) {

            if ($('#table-revenue').hasClass('page-retention')) {
                // table revenue
                var table_revenue = $('#table-revenue').DataTable({
                    responsive: true,
                    paging: false,
                    searching: false,
                    pageLength: 1000,
                    ordering: false,
                    order: [],
                    info: false,
                    //scrollY: false,
                    scrollX: true,
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel'
                    ]
                });
                new $.fn.dataTable.FixedColumns(table_revenue, {
                    heightMatch: 'none',
                    leftColumns: 1
                });
            }
        }
    }

    // table comepare all game page compare
    if ($('#table-compare-all-game').length) {

        if ($('#table-compare-all-game').hasClass('compare-game')) {
            var table_compare_all_game = $('#table-compare-all-game').DataTable({
                paging: false,
                searching: false,
                ordering: false,
                order: [],
                pageLength: 1000,
                info: false,
                scrollX: true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel'
                ]

            });
            new $.fn.dataTable.FixedColumns(table_compare_all_game, {
                heightMatch: 'none',
                leftColumns: 2
            });
        }
    }

    if ($('#table-transfer').length) {

        // table transer paying
        var table_transfer = $('#table-transfer').DataTable({
            responsive: true,
            paging: false,
            searching: false,
            pageLength: 1000,
            order: [],
            ordering: false,
            info: false,
            //scrollY: false,
            scrollX: true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel'
            ]
        });
        new $.fn.dataTable.FixedColumns(table_transfer, {
            // heightMatch: 'none'
        });

    }

    if ($('#table-transfer-detail').length) {
        var table_transfer_detail = $('#table-transfer-detail').DataTable({
            responsive: true,
            paging: false,
            searching: false,
            order: [],
            ordering: false,
            pageLength: 1000,
            info: false,
            //scrollY: false,
            scrollX: true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel'
            ]
        });
        new $.fn.dataTable.FixedColumns(table_transfer_detail, {
            //heightMatch: 'none'
        });
    }


    if ($('#table-revenue2').length) {
        var table_revenue2 = $('#table-revenue2').DataTable({
            responsive: true,
            paging: false,
            order: [],
            searching: false,
            pageLength: 1000,
            ordering: false,
            info: false,
            scrollX: true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel'
            ]
        });
    }

    // compare one game dashboard
    $('select[name="gameCodeCompare"]').change(function () {
        var gameCode = $(this).val();

        // check call ajax from Dashboard or Compare Game
        var pageCompareGame = $(this).hasClass('compare-game');
        var url_CompareOneGame = '';
        if (pageCompareGame) {
            // page Compare Game
            url_CompareOneGame = url + "/CompareGame/compareProductOneGame/" + gameCode;
        } else {
            // page Dashboard
            url_CompareOneGame = url + "/Dashboard/compareProductOneGame/" + gameCode;
        }

        $.get(url_CompareOneGame, function (data) {
            $("#rs_compapre_onegame").html(data);
            // data table
            if ($('#table-compare-one-game').length) {
                var table_compare_one_game = $('#table-compare-one-game').DataTable({
                    responsive: true,
                    paging: false,
                    searching: false,
                    order: [],
                    ordering: false,
                    pageLength: 1000,
                    info: false,
                    //scrollY: false,
                    scrollX: true,
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel'
                    ]
                });
                new $.fn.dataTable.FixedColumns(table_compare_one_game, {
                    heightMatch: 'none'
                });
            }

            $(window).resize();
        });
    }).change();
    //$('select[name="gameCodeCompare"]');

    // data table revenue daily
    var table_revenue_daily = $('#table-revenue-daily').DataTable({
        responsive: true,
        paging: true,
        lengthChange: false,
        order: [],
        searching: false,
        pageLength: 1000,
        //ordering: true,
        info: false,
        scrollX: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel'
        ]
    });

    // data table-arppu
    var table_arppu = $('#table-arppu').DataTable({
        paging: true,
        lengthChange: false,
        pageLength: 1000,
        order: [],
        searching: false,
        //ordering: true,
        info: false,
        scrollX: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel'
        ]
    });


    // data active-account

    if ($('#active-account').length) {
        var active_account = $('#active-account').DataTable({
            paging: true,
            lengthChange: false,
            pageLength: 1000,
            searching: false,
            //ordering: true,
            order: [],
            info: false,
            scrollX: true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel'
            ]
        });
    }


    if ($('#kpi-report-active-user').length) {
        var kpi_report_active_user = $('#kpi-report-active-user').DataTable({
            lengthChange: false,
            pageLength: 1000,
            responsive: true,
            fixedColumns:   {
                leftColumns: 1
            },
            searching: false,
            info: false,
            scrollX: true,
            scrollY: "500px",
            order: [],
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel'
            ]
        });
    }

    if ($('#kpi-report-revenue').length) {
        var kpi_report_revenue = $('#kpi-report-revenue').DataTable({
            lengthChange: false,
            pageLength: 1000,
            responsive: true,
            fixedColumns:   {
                leftColumns: 1
            },
            searching: false,
            info: false,
            scrollX: true,
            scrollY: "500px",
            order: [],
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel'
            ]
        });
    }

    // if ($('#kpi-report-export').length) {
    //     var kpi_report_export = $('#kpi-report-export').DataTable({
    //         lengthChange: false,
    //         pageLength: 20,
    //         responsive: true,
    //         fixedColumns:   {
    //             leftColumns: 1
    //         },
    //         searching: true,
    //         info: false,
    //         scrollX: true,
    //         scrollY: "500px",
    //         order: [],
    //         dom: 'Bfrtip',
    //         buttons: [
    //             'copy', 'csv', 'excel'
    //         ]
    //     });
    // }

    if ($('#kpi-device-report').length) {
        var kpi_device_report = $('#kpi-device-report').DataTable({
            lengthChange: false,
            pageLength: 1000,
            responsive: true,
            fixedColumns:   {
                leftColumns: 1
            },
            searching: false,
            info: false,
            scrollX: true,
            scrollY: "500px",
            order: [],
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel'
            ]
        });
    }



    // export
    $("a.export").on('click', function () {
        var data = $(this).attr('export');
        var url = $(this).attr('data-url');
        var description = $(this).attr('data-description');
        $('#contentExportModel').html(description);
        $('.modal-footer button.btn-success').data( "export", data );
        $('.modal-footer button.btn-success').data( "data-url", url );
        $('#modelExport').modal('toggle');
    });
    $(".modal-footer button.btn-success").on('click', function(){
        var urlAPI = $(this).data("data-url");
        var params = $(this).data("export");
        console.log(urlAPI +  params);
        window.location.href = urlAPI + "/" + params;
        //$.get(urlAPI + "/" + params, function (data) {
        //    $('#modelExport').modal('toggle');
        //    console.log("done");
        //})

    });


    function cb(start, end) {
        $('#daterangepicker span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }
    cb(moment().subtract(29, 'days'), moment());

    $('input[name="daterangepicker"]').daterangepicker({
        alwaysShowCalendars:true,
        autoUpdateInput: false,
        locale: {
            format: 'DD/MM/YYYY',
            cancelLabel: 'Clear'
        },
        autoApply:true,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    $('input[name="daterangepicker"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
    });

    $('input[name="daterangepicker"]').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    $('input[name="datesinglepicker"]').daterangepicker({
        autoUpdateInput: false,
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
            format: 'DD/MM/YYYY',
            cancelLabel: 'Clear'
        },
    });

    $('input[name="datesinglepicker"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY'));
    });


    $('#all_kpi tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
    } );
    
    $('#user_kpi tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
    } );
    
    $('#revenue_kpi tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
    } );

    $('#kpidatepicker').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy'
    }).on('changeDate', function (ev) {
        $('#kpidatepicker').datepicker('hide');
        $('#kpidatepicker').val(this.val());
    });
    
    $(window).resize();
});

$(document).ready(function(){
	
	var amountScrolled = 300;
	// vinhdp
	$(window).scroll(function() {    
	    var scroll = $(window).scrollTop();   
	    var pos = 0;
	    if (scroll <= 50) {

	    	$("#sidebarToggle").offset({ top: 0});
	    }else{
	    	$("#sidebarToggle").offset({ top: 0});
	    }
	    
	    if ( $(window).scrollTop() > amountScrolled ) {
			$('a.back-to-top').fadeIn('slow');
		} else {
			$('a.back-to-top').fadeOut('slow');
		}
	});
	
	$('a.back-to-top').click(function() {
		$('html, body').animate({
			scrollTop: 0
		}, 700);
		return false;
	});
	
	// end
	
    if($("#revenue_kpi").length > 0){
    	
        $("#revenue_kpi").DataTable({
            responsive: true,
            paging: false,
            searching: false,
            ordering: false,
            info: false,
            scrollX: true,
            scrollY: "600px",
            dom: 'Bfrtip',
            buttons: [
                 'copy', 'csv', 'excel'
            ],
            fixedColumns:   {
                leftColumns: 1
            }
        });
    };
    
    if($("#user_kpi").length > 0){
        var table = $("#user_kpi").DataTable({
            responsive: true,
            paging: false,
            searching: false,
            ordering: false,
            info: false,
            scrollX: true,
            scrollY: "600px",
            dom: 'Bfrtip',
            buttons: [
                 'copy', 'csv', 'excel'
            ],
            fixedColumns:   {
                leftColumns: 1
            }
        });

    };
    
    if($("#all_kpi").length > 0){
        var table = $("#all_kpi").DataTable({
            responsive: true,
            paging: false,
            searching: false,
            ordering: false,
            info: false,
            scrollX: true,
            scrollY: "600px",
            dom: 'Bfrtip',
            buttons: [
                 'copy', 'csv', 'excel'
            ],
            fixedColumns:   {
                leftColumns: 1
            }
        });

    };

    $('.buttons-excel').each(function() {
    	$(this).attr("title", "Download table as excel file!");
    	$(this).html("<i class='fa fa-file-excel-o' aria-hidden='true'></i>");
    });
    $('.buttons-copy').each(function() {
    	$(this).attr("title", "Copy table to clipboard!");
 		$(this).html("<i class='fa fa-copy' aria-hidden='true'></i>");
	});
	$('.buttons-print').each(function() {
		$(this).attr("title", "Print table!");
    	$(this).html("<i class='fa fa-print' aria-hidden='true'></i>");
    });
    $('.buttons-csv').each(function() {
    	$(this).attr("title", "Download table as csv file!");
    	$(this).html("<i class='fa fa-file-text-o' aria-hidden='true'></i>");
    });

    //$(".dt-buttons").addClass("hidden");
	
	$("#slGame").select2();
	$("#slGameSelection").select2();
	$("#slTiming").select2({
		minimumResultsForSearch: Infinity
	});
	$("#wpw2").select2({
		minimumResultsForSearch: Infinity
	});
	$("#wpw1").select2({
		minimumResultsForSearch: Infinity
	});
	$("#mpm2").select2({
		minimumResultsForSearch: Infinity
	});
	$("#mpm1").select2({
		minimumResultsForSearch: Infinity
	});
	
	// event select time
    //$('select[name="options"]').change(function () {
    	
        var value = $('select[name="options"]').val();
        $('.option_time').addClass('hide');
        switch (value) {
            case '4' :
                $('.option_day').removeClass('hide');
                break;

            case '5' :
                $('.option_week').removeClass('hide');
                break;

            case '6' :
                $('.option_month').removeClass('hide');
                break;

            case '1' :
            case '2' :
            case '3' :
            default:
                $('.option_disable').removeClass('hide');
                break;

        }
    //});
    
    //$('select[name="options"]').change();
        
    $("#downloadExcel").on("click", function(e){
    	if($(".tab-pane.active .buttons-excel").length > 0){
    		
    		$(".tab-pane.active .buttons-excel").trigger("click");
    	} else {
    		console.log("adsads")
    		$(".buttons-excel").trigger("click");
    	}
    });
    $("#copy").on("click", function(e){
    	
    	if($(".tab-pane.active .buttons-copy").length > 0){
    		
    		$(".tab-pane.active .buttons-copy").trigger("click");
    	} else {
    		
    		$(".buttons-copy").trigger("click");
    	}
    });
	
    $("#dashboard-overview-choose").multipleSelect({
        width: 320,
        multiple: true,
        selectAll: false,
        placeholder: "Select ...",
        multipleWidth: 100
    });
    $("#dashboard-trend-chart-1-choose").multipleSelect({
        width: 320,
        multiple: true,
        selectAll: false,
        placeholder: "Select ...",
        multipleWidth: 100,
        onClick: function(view) {
            var $checkboxes = $("#dashboard-trend-chart-1-choose").next().find("input[type='checkbox']").not(":checked");
            var selectedLen = $("#dashboard-trend-chart-1-choose").multipleSelect('getSelects').length;
            if (selectedLen >= 6) {
                $checkboxes.prop("disabled", true);
            } else {
                $checkboxes.prop("disabled", false);
            }
        }
    });

    $("#dashboard-trend-chart-2-choose").multipleSelect({
        width: 320,
        multiple: true,
        selectAll: false,
        placeholder: "Select ...",
        multipleWidth: 100,
        onClick: function(view) {
            var $checkboxes = $("#dashboard-trend-chart-2-choose").next().find("input[type='checkbox']").not(":checked");
            var selectedLen = $("#dashboard-trend-chart-2-choose").multipleSelect('getSelects').length;
            if (selectedLen >= 6) {
                $checkboxes.prop("disabled", true);
            } else {
                $checkboxes.prop("disabled", false);
            }
        }
    });
    
    
    /**
     * Menu Highlight
     */
    var pgurl = location.pathname;
    //console.log(pgurl);
	var prefix = '';
	
	$("ul.sidebar-menu li ul li").each(function(){
		$(this).removeClass("active");
	});
	
	$("ul.sidebar-menu li ul li a").each(function() {
			
		var itemUrl = $(this).attr("href").replace(prefix, "");
		//console.log(itemUrl);
		if (itemUrl == pgurl || itemUrl == '/' || itemUrl.indexOf(pgurl) > -1)
			$(this).parent().addClass("active");
	})
	
	/**
	 * Jump to chart
	 */
	
	$(".revenue-chart").click(function(event){
         event.preventDefault();
         //calculate destination place
         var dest=0;
         var hash = "#section-revenue";
         if($(hash).offset().top > $(document).height()-$(window).height()){
              dest=$(document).height()-$(window).height();
         }else{
              dest=$(hash).offset().top;
         }
         //go to destination
         $('html,body').animate({scrollTop:dest}, 1000,'swing');
    });
	
	$(".user-chart").click(function(event){
        event.preventDefault();
        //calculate destination place
        var dest=0;
        var hash = "#section-user";
        if($(hash).offset().top > $(document).height()-$(window).height()){
             dest=$(document).height()-$(window).height();
        }else{
             dest=$(hash).offset().top;
        }
        //go to destination
        $('html,body').animate({scrollTop:dest}, 1000,'swing');
   });
	
	$(".games-menu-control").on("click", function(e){
		var value = $(this).data("value");
		$("#gameType").val(value);
		$(this).closest('form').submit();
	});
	
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		$(window).trigger('resize');
    });
});
